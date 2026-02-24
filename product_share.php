<?php
include 'includes/db.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    http_response_code(404);
    echo 'Product not found';
    exit;
}

$product_sql = "SELECT p.*, b.name AS brand_name, c.name AS category_name
                FROM products p
                JOIN brands b ON p.brand_id = b.id
                JOIN categories c ON p.category_id = c.id
                WHERE p.id = {$product_id}
                LIMIT 1";
$product_result = $conn->query($product_sql);

if (!$product_result || $product_result->num_rows === 0) {
    http_response_code(404);
    echo 'Product not found';
    exit;
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

$choose_preview_image = function (array $paths) use ($expand_image_candidates): string {
    $candidates = $expand_image_candidates($paths);
    $best_path = '';
    $best_score = -1;

    foreach ($candidates as $path) {
        $parsed_path = parse_url($path, PHP_URL_PATH) ?: $path;
        $ext = strtolower(pathinfo($parsed_path, PATHINFO_EXTENSION));
        $relative = ltrim($parsed_path, '/');
        $full_path = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);

        $exists = is_file($full_path);
        $size_score = ($exists && is_readable($full_path)) ? filesize($full_path) : 0;
        $format_score = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) ? 500000000 : 100000000;
        $transform_penalty = strpos($parsed_path, '.transform.') !== false ? -20000000 : 0;

        $score = $format_score + $size_score + $transform_penalty;

        if ($score > $best_score) {
            $best_score = $score;
            $best_path = $path;
        }
    }

    return $best_path;
};

$selected_image = $choose_preview_image($image_candidates);
$site_base = 'https://' . ($_SERVER['HTTP_HOST'] ?? '');
$image_url = $selected_image ? $site_base . '/' . ltrim($selected_image, '/') : '';
$share_url = $site_base . '/product_share.php?id=' . $product_id;

$preview_version = (string)time();
if ($selected_image) {
    $selected_relative_for_version = ltrim(parse_url($selected_image, PHP_URL_PATH) ?: $selected_image, '/');
    $selected_full_for_version = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $selected_relative_for_version);
    if (is_file($selected_full_for_version)) {
        $preview_version = (string)(@filemtime($selected_full_for_version) ?: time());
    }
}

$preview_image_url = $site_base . '/share_image.php?id=' . $product_id . '&v=' . $preview_version;

$image_width = null;
$image_height = null;
if ($selected_image) {
    $selected_relative = ltrim(parse_url($selected_image, PHP_URL_PATH) ?: $selected_image, '/');
    $selected_full = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $selected_relative);
    if (is_file($selected_full)) {
        $size_info = @getimagesize($selected_full);
        if ($size_info && isset($size_info[0], $size_info[1])) {
            $image_width = (int)$size_info[0];
            $image_height = (int)$size_info[1];
        }
    }
}

$title = trim(($product['brand_name'] ?? '') . ' ' . ($product['name'] ?? ''));
if ($title === '') {
    $title = 'HAY.LUXURY Product';
}

$description_parts = [
    'REF: ' . $product['id'],
    'Type: ' . ($product['type'] ?: '-'),
    'Color: ' . ($product['color'] ?: '-'),
    'Size: ' . ($product['size'] ?: '-'),
    'Approx Weight: ' . ($product['weight'] ?: '-')
];
$description = implode(' | ', $description_parts);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HAY.LUXURY">
    <meta property="og:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($description); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($share_url); ?>">
    <?php if ($image_url): ?>
        <meta property="og:image" content="<?php echo htmlspecialchars($preview_image_url); ?>">
        <?php if ($image_width && $image_height): ?>
            <meta property="og:image:width" content="<?php echo $image_width; ?>">
            <meta property="og:image:height" content="<?php echo $image_height; ?>">
        <?php endif; ?>
    <?php endif; ?>

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($description); ?>">
    <?php if ($image_url): ?>
        <meta name="twitter:image" content="<?php echo htmlspecialchars($preview_image_url); ?>">
    <?php endif; ?>
</head>

<body>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <p><?php echo htmlspecialchars($description); ?></p>
    <?php if ($image_url): ?>
        <p><img src="<?php echo htmlspecialchars($preview_image_url); ?>" alt="<?php echo htmlspecialchars($title); ?>" style="max-width: 320px; width: 100%; height: auto;"></p>
    <?php endif; ?>
    <p><a href="<?php echo htmlspecialchars(BASE_URL); ?>/index.php">View Collection</a></p>
</body>

</html>
