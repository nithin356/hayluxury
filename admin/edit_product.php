<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

include '../includes/utils.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if (!$product) {
    header("Location: manage_products.php");
    exit;
}

// Handle Delete Image
if (isset($_POST['delete_image_id'])) {
    $img_id = intval($_POST['delete_image_id']);
    $img_res = $conn->query("SELECT * FROM product_images WHERE id = $img_id AND product_id = $id");
    if ($img_row = $img_res->fetch_assoc()) {
        $img_path = "../" . $img_row['image_path'];
        if (file_exists($img_path)) unlink($img_path);
        $conn->query("DELETE FROM product_images WHERE id = $img_id");
        
        // If it was the primary image, pick another one to be primary
        if ($img_row['is_primary']) {
            $next_res = $conn->query("SELECT * FROM product_images WHERE product_id = $id LIMIT 1");
            if ($next_row = $next_res->fetch_assoc()) {
                $next_id = $next_row['id'];
                $next_path = $next_row['image_path'];
                $conn->query("UPDATE product_images SET is_primary = 1 WHERE id = $next_id");
                $conn->query("UPDATE products SET image = '$next_path' WHERE id = $id");
            } else {
                $conn->query("UPDATE products SET image = '' WHERE id = $id");
            }
        }
    }
    header("Location: edit_product.php?id=$id");
    exit;
}

// Handle Set Primary
if (isset($_POST['set_primary_id'])) {
    $img_id = intval($_POST['set_primary_id']);
    $img_res = $conn->query("SELECT * FROM product_images WHERE id = $img_id AND product_id = $id");
    if ($img_row = $img_res->fetch_assoc()) {
        $img_path = $img_row['image_path'];
        $conn->query("UPDATE product_images SET is_primary = 0 WHERE product_id = $id");
        $conn->query("UPDATE product_images SET is_primary = 1 WHERE id = $img_id");
        $conn->query("UPDATE products SET image = '$img_path' WHERE id = $id");
    }
    header("Location: edit_product.php?id=$id");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $brand_id = intval($_POST['brand_id']);
    $whatsapp = $conn->real_escape_string($_POST['whatsapp']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $type = $conn->real_escape_string($_POST['type']);
    $color = $conn->real_escape_string($_POST['color']);
    $size = $conn->real_escape_string($_POST['size']);
    $weight = $conn->real_escape_string($_POST['weight']);
    $diamond_info = $conn->real_escape_string($_POST['diamond_info']);
    $status = $conn->real_escape_string($_POST['status']);

    // Handle New Images Upload
    if (!empty($_FILES['images']['name'][0])) {
        $target_dir = "../uploads/";
        foreach ($_FILES['images']['name'] as $key => $name_val) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $image_name = time() . '_' . $key . '_' . basename($_FILES["images"]["name"][$key]);
                $target_file = $target_dir . $image_name;
                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
                    addWatermark($target_file);
                    $image_path = "uploads/" . $image_name;
                    $conn->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES ($id, '$image_path', 0)");
                }
            }
        }
        
        // If product currently has no primary image, set the first new one as primary
        $check_primary = $conn->query("SELECT id FROM product_images WHERE product_id = $id AND is_primary = 1");
        if ($check_primary->num_rows == 0) {
            $first_res = $conn->query("SELECT * FROM product_images WHERE product_id = $id LIMIT 1");
            if ($first_row = $first_res->fetch_assoc()) {
                $first_id = $first_row['id'];
                $first_path = $first_row['image_path'];
                $conn->query("UPDATE product_images SET is_primary = 1 WHERE id = $first_id");
                $conn->query("UPDATE products SET image = '$first_path' WHERE id = $id");
            }
        }
    }

    $sql = "UPDATE products SET 
            name = '$name', category_id = $category_id, brand_id = $brand_id, 
            type = '$type', color = '$color', size = '$size', 
            weight = '$weight', diamond_info = '$diamond_info', 
            description = '$description', price = $price, 
            whatsapp_number = '$whatsapp', status = '$status' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product updated successfully'); window.location='manage_products.php';</script>";
        exit;
    } else {
        echo "<div style='color: red; padding: 20px; background: #fff; border: 1px solid red; margin: 20px;'>
                <h3>Error updating product:</h3>
                <p>" . $conn->error . "</p>
                <p>SQL: " . htmlspecialchars($sql) . "</p>
                <a href='edit_product.php?id=$id'>Go Back</a>
              </div>";
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Hay.Luxury</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background: #f8f8f8; color: #333;">
<div class="admin-container" style="max-width: 800px; margin: 50px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
    <h1 style="margin-bottom: 30px; text-align: center;">EDIT PRODUCT</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>PRODUCT NAME</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>CATEGORY</label>
                <select name="category_id" class="form-control" id="category_id_select" required>
                    <?php
                    $cats = $conn->query("SELECT * FROM categories ORDER BY name");
                    while($c = $cats->fetch_assoc()) {
                        $sel = ($c['id'] == $product['category_id']) ? 'selected' : '';
                        echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>INTERNAL TYPE</label>
                <select name="type" class="form-control" id="product_type">
                    <option value="">Select Type</option>
                    <?php
                    $types = ["Necklace", "Ring", "Earring", "Bracelet", "Bangle", "Pendant", "Set", "Watch", "Other"];
                    foreach($types as $t) {
                        $sel = ($product['type'] == $t) ? 'selected' : '';
                        echo "<option value='$t' $sel>$t</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>BRAND</label>
                <select name="brand_id" class="form-control" required>
                    <?php
                    $brands = $conn->query("SELECT * FROM brands ORDER BY name");
                    while($b = $brands->fetch_assoc()) {
                        $sel = ($b['id'] == $product['brand_id']) ? 'selected' : '';
                        echo "<option value='{$b['id']}' $sel>{$b['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>COLOR</label>
                <input type="text" name="color" class="form-control" value="<?php echo htmlspecialchars($product['color']); ?>">
            </div>
            
            <div class="form-group">
                <label>SIZE</label>
                <input type="text" name="size" class="form-control" value="<?php echo htmlspecialchars($product['size']); ?>">
            </div>

            <div class="form-group">
                <label>APPROX WEIGHT</label>
                <input type="text" name="weight" class="form-control" value="<?php echo htmlspecialchars($product['weight']); ?>">
            </div>
            
            <div class="form-group">
                <label>DIAMOND INFO</label>
                <input type="text" name="diamond_info" class="form-control" value="<?php echo htmlspecialchars($product['diamond_info']); ?>">
            </div>
            
            <div class="form-group">
                <label>PRICE (AED)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>WHATSAPP</label>
                <input type="text" name="whatsapp" class="form-control" value="<?php echo htmlspecialchars($product['whatsapp_number']); ?>" required>
            </div>

            <div class="form-group">
                <label>STOCK STATUS</label>
                <select name="status" class="form-control">
                    <option value="In Stock" <?php if($product['status'] == 'In Stock') echo 'selected'; ?>>In Stock</option>
                    <option value="Sold Out" <?php if($product['status'] == 'Sold Out') echo 'selected'; ?>>Sold Out</option>
                    <option value="Reserved" <?php if($product['status'] == 'Reserved') echo 'selected'; ?>>Reserved</option>
                </select>
            </div>
        </div>
        
        <div class="form-group" style="margin-top: 20px;">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>
        
        <div class="form-group" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <label>Product Gallery</label>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
                <?php
                $images_res = $conn->query("SELECT * FROM product_images WHERE product_id = $id ORDER BY is_primary DESC, id ASC");
                while ($img_row = $images_res->fetch_assoc()) {
                    ?>
                    <div style="position: relative; border: 1px solid #ddd; padding: 10px; border-radius: 4px; background: <?php echo $img_row['is_primary'] ? '#fff9fa' : '#fff'; ?>;">
                        <img src="../<?php echo $img_row['image_path']; ?>" style="width: 100%; height: 120px; object-fit: contain; margin-bottom: 10px;">
                        
                        <?php if ($img_row['is_primary']): ?>
                            <span style="display: block; font-size: 10px; color: var(--gold); text-align: center; margin-bottom: 5px; font-weight: bold;">PRIMARY IMAGE</span>
                        <?php else: ?>
                            <button type="submit" name="set_primary_id" value="<?php echo $img_row['id']; ?>" class="btn" style="width: 100%; padding: 5px; font-size: 10px; background: var(--gold); color: #fff; margin-bottom: 5px;">Set Primary</button>
                        <?php endif; ?>
                        
                        <button type="submit" name="delete_image_id" value="<?php echo $img_row['id']; ?>" class="btn btn-danger" style="width: 100%; padding: 5px; font-size: 10px;" onclick="return confirm('Delete this image?')">Delete</button>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        
        <div class="form-group" style="margin-top: 30px; background: #fdfdfd; padding: 20px; border: 1px dashed #ccc;">
            <label>Add More Images</label>
            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
        </div>
        
        <div style="margin-top: 30px; display: flex; gap: 15px;">
            <button type="submit" name="update_product" class="btn" style="flex: 1;">Update Product</button>
            <a href="manage_products.php" class="btn" style="flex: 1; background: #888; text-decoration: none; text-align: center; color: #fff; line-height: 40px;">Cancel</a>
        </div>
    </form>
</div>
<script>
document.getElementById('category_id_select').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const categoryName = selectedOption.text.trim().toLowerCase();
    const typeSelect = document.getElementById('product_type');
    
    if (categoryName && categoryName !== 'select category') {
        for (let i = 0; i < typeSelect.options.length; i++) {
            const typeVal = typeSelect.options[i].value.toLowerCase();
            if (typeVal === categoryName || categoryName.includes(typeVal) || typeVal.includes(categoryName)) {
                typeSelect.selectedIndex = i;
                return;
            }
        }
        // Don't overwrite if already set to something besides empty/Other
        if (typeSelect.value !== "" && typeSelect.value !== "Other") {
            return;
        }
        typeSelect.value = "Other";
    }
});
</script>
</body>
</html>
