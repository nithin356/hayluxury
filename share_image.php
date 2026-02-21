<?php
include 'includes/db.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    http_response_code(404);
    exit('Invalid product');
}

$product_sql = "SELECT image FROM products WHERE id = {$product_id} LIMIT 1";
$product_result = $conn->query($product_sql);
if (!$product_result || $product_result->num_rows === 0) {
    http_response_code(404);
    exit('Product not found');
}

$product = $product_result->fetch_assoc();

$image_candidates = [];
$images_sql = "SELECT image_path
              FROM product_images
              WHERE product_id = {$product_id}
              ORDER BY is_primary DESC, id ASC";
$images_result = $conn->query($images_sql);
if ($images_result) {
    while ($img_row = $images_result->fetch_assoc()) {
        if (!empty($img_row['image_path'])) {
            $image_candidates[] = $img_row['image_path'];
        }
    }
}
if (!empty($product['image'])) {
    $image_candidates[] = $product['image'];
}

$expand_image_candidates = function (array $paths): array {
    $expanded = [];

    foreach ($paths as $path) {
        if (!$path) {
            continue;
        }

        $expanded[] = $path;

        if (strpos($path, '.transform.') !== false) {
            $expanded[] = strstr($path, '.transform.', true);
        }
    }

    return array_values(array_unique($expanded));
};

$choose_source_image = function (array $paths) use ($expand_image_candidates): string {
    $candidates = $expand_image_candidates($paths);
    $best_path = '';
    $best_score = -1;

    foreach ($candidates as $path) {
        $parsed_path = parse_url($path, PHP_URL_PATH) ?: $path;
        $ext = strtolower(pathinfo($parsed_path, PATHINFO_EXTENSION));
        $relative = ltrim($parsed_path, '/');
        $full_path = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);

        $exists = is_file($full_path);
        if (!$exists) {
            continue;
        }

        $size_score = is_readable($full_path) ? (int)filesize($full_path) : 0;
        $format_score = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif'], true) ? 100000000 : 0;
        $transform_penalty = strpos($parsed_path, '.transform.') !== false ? -20000000 : 0;

        $score = $format_score + $size_score + $transform_penalty;

        if ($score > $best_score) {
            $best_score = $score;
            $best_path = $parsed_path;
        }
    }

    return $best_path;
};

$selected_image = $choose_source_image($image_candidates);
if (!$selected_image) {
    http_response_code(404);
    exit('Image not found');
}

$relative_path = ltrim($selected_image, '/');
$source_path = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative_path);
if (!is_file($source_path)) {
    http_response_code(404);
    exit('Image not found');
}

$cache_dir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'share_previews';
if (!is_dir($cache_dir)) {
    @mkdir($cache_dir, 0775, true);
}

$source_mtime = @filemtime($source_path) ?: time();
$cache_key = md5($selected_image . '|' . (string)$source_mtime);
$cache_file = $cache_dir . DIRECTORY_SEPARATOR . 'product_' . $product_id . '_' . $cache_key . '.jpg';

$serve_jpeg = function (string $path): void {
    header('Content-Type: image/jpeg');
    header('Cache-Control: public, max-age=86400');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
};

if (is_file($cache_file)) {
    $serve_jpeg($cache_file);
}

$extension = strtolower(pathinfo($source_path, PATHINFO_EXTENSION));
$generated = false;

if ($extension === 'jpg' || $extension === 'jpeg') {
    $generated = @copy($source_path, $cache_file);
} elseif (extension_loaded('gd')) {
    $source_image = null;

    if ($extension === 'png' && function_exists('imagecreatefrompng')) {
        $source_image = @imagecreatefrompng($source_path);
    } elseif ($extension === 'webp' && function_exists('imagecreatefromwebp')) {
        $source_image = @imagecreatefromwebp($source_path);
    } elseif ($extension === 'avif' && function_exists('imagecreatefromavif')) {
        $source_image = @imagecreatefromavif($source_path);
    } elseif ($extension === 'gif' && function_exists('imagecreatefromgif')) {
        $source_image = @imagecreatefromgif($source_path);
    }

    if ($source_image) {
        $width = imagesx($source_image);
        $height = imagesy($source_image);

        $max_width = 1600;
        if ($width > $max_width) {
            $new_width = $max_width;
            $new_height = (int)round(($height / $width) * $new_width);
        } else {
            $new_width = $width;
            $new_height = $height;
        }

        $canvas = imagecreatetruecolor($new_width, $new_height);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        imagecopyresampled($canvas, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $generated = imagejpeg($canvas, $cache_file, 92);

        imagedestroy($canvas);
        imagedestroy($source_image);
    }
}

if (!$generated && class_exists('Imagick')) {
    try {
        $imagick = new Imagick($source_path);
        $imagick->setImageBackgroundColor('white');
        $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

        if ($imagick->getImageWidth() > 1600) {
            $imagick->resizeImage(1600, 0, Imagick::FILTER_LANCZOS, 1);
        }

        $imagick->setImageFormat('jpeg');
        $imagick->setImageCompressionQuality(92);
        $generated = $imagick->writeImage($cache_file);
        $imagick->clear();
        $imagick->destroy();
    } catch (Exception $e) {
        $generated = false;
    }
}

if ($generated && is_file($cache_file)) {
    $serve_jpeg($cache_file);
}

// Last fallback: serve original as-is if conversion failed.
$mime_map = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'webp' => 'image/webp',
    'avif' => 'image/avif',
    'gif' => 'image/gif'
];

header('Content-Type: ' . ($mime_map[$extension] ?? 'application/octet-stream'));
header('Cache-Control: public, max-age=3600');
header('Content-Length: ' . filesize($source_path));
readfile($source_path);
exit;
