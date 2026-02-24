<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

include '../includes/utils.php';

// Handle Add/Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product']) || isset($_POST['action_add'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $category_id = intval($_POST['category_id']);
        $brand_id = intval($_POST['brand_id']);
        $whatsapp = $conn->real_escape_string($_POST['whatsapp']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        
        // New Fields
        $type = $conn->real_escape_string($_POST['type']);
        $color = $conn->real_escape_string($_POST['color']);
        $size = $conn->real_escape_string($_POST['size']);
        $weight = $conn->real_escape_string($_POST['weight']);
        $diamond_info = $conn->real_escape_string($_POST['diamond_info']);
        $status = $conn->real_escape_string($_POST['status']);
        
        // Image Upload
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $uploaded_images = [];
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $key => $name_val) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $image_name = time() . '_' . $key . '_' . basename($_FILES["images"]["name"][$key]);
                    $target_file = $target_dir . $image_name;
                    
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
                        addWatermark($target_file);
                        $uploaded_images[] = "uploads/" . $image_name;
                    }
                }
            }
        }

        if (empty($uploaded_images)) {
            echo "<script>alert('No images uploaded or upload error.'); window.history.back();</script>";
            exit;
        }

        $primary_image = $uploaded_images[0];
        $sql = "INSERT INTO products (name, category_id, brand_id, type, color, size, weight, diamond_info, description, price, image, whatsapp_number, status) 
                VALUES ('$name', $category_id, $brand_id, '$type', '$color', '$size', '$weight', '$diamond_info', '$description', $price, '$primary_image', '$whatsapp', '$status')";
        
        if ($conn->query($sql) === TRUE) {
            $product_id = $conn->insert_id;
            
            // Insert all images into product_images table
            foreach ($uploaded_images as $idx => $path) {
                $is_primary = ($idx === 0) ? 1 : 0;
                $conn->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES ($product_id, '$path', $is_primary)");
            }
            
            echo "<script>alert('Product added successfully with " . count($uploaded_images) . " images'); window.location='manage_products.php';</script>";
            exit;
        } else {
            echo "<div style='color: red; padding: 20px; background: #fff; border: 1px solid red; margin: 20px;'>
                    <h3>Error adding product:</h3>
                    <p>" . $conn->error . "</p>
                    <p>SQL: " . htmlspecialchars($sql) . "</p>
                    <a href='manage_products.php'>Go Back</a>
                  </div>";
            exit;
        }
    } elseif (isset($_POST['delete_product'])) {
        $id = intval($_POST['id']);
        
        // Fetch all images for this product
        $images_res = $conn->query("SELECT image_path FROM product_images WHERE product_id = $id");
        while ($img_row = $images_res->fetch_assoc()) {
            $image_path = "../" . $img_row['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Deleting the product will automatically delete product_images due to foreign key ON DELETE CASCADE
        $conn->query("DELETE FROM products WHERE id = $id");

    } elseif (isset($_POST['bulk_price_update']) || isset($_POST['action_bulk'])) {
        $amount = floatval($_POST['amount']);
        $op = $_POST['operation'] === 'decrease' ? '-' : '+';
        
        // Use COALESCE to handle any NULL price fields
        $sql = "UPDATE products SET price = COALESCE(price, 0) $op $amount";
        if ($conn->query($sql)) {
            $updated = $conn->affected_rows;
            echo "<script>alert('Price updated for $updated products!'); window.location='manage_products.php';</script>";
        } else {
            echo "<script>alert('Error updating prices: " . addslashes($conn->error) . "'); window.location='manage_products.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Hay.Luxury</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Fix Admin CSS overrides */
        body {
            background-color: #f8f8f8; /* Contrast with white cards */
            padding-top: 0; /* Override style.css padding */
        }
        .admin-nav a { display: inline-block; padding: 10px 20px; }
        .product-grid { display: none; } /* Hide user-side grid styles if they leak */
    </style>
</head>
<body>

<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1 style="margin: 0; font-family: var(--font-heading); font-size: 32px;">Manage Products</h1>
        <div style="font-size: 14px; color: #888;">Add or Edit Inventory</div>
    </div>
    
    <div class="admin-nav">
        <a href="index.php">Dashboard</a>
        <a href="manage_categories.php">Categories</a>
        <a href="manage_brands.php">Brands</a>
        <a href="manage_products.php" style="color: var(--gold); border-bottom: 2px solid var(--gold);">Products</a>
        <a href="manage_gold_price.php">Gold Price</a>
        <a href="../index.php" target="_blank" style="float: right; color: var(--black);">Public Site</a>
    </div>

    <!-- Add Product Form -->
    <div style="background: #fff; padding: 30px; border: 1px solid #eee; border-radius: 4px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 40px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Add New Product</h3>
        <form method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>PRODUCT NAME</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>CATEGORY</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php
                        $cats = $conn->query("SELECT * FROM categories ORDER BY name");
                        while($c = $cats->fetch_assoc()) {
                            echo "<option value='{$c['id']}'>{$c['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>INTERNAL TYPE</label>
                    <select name="type" class="form-control" id="product_type">
                        <option value="">Select Type</option>
                        <option value="Necklace">Necklace</option>
                        <option value="Ring">Ring</option>
                        <option value="Earring">Earring</option>
                        <option value="Bracelet">Bracelet</option>
                        <option value="Bangle">Bangle</option>
                        <option value="Pendant">Pendant</option>
                        <option value="Set">Set</option>
                        <option value="Watch">Watch</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>BRAND</label>
                    <select name="brand_id" class="form-control" required>
                        <option value="">Select Brand</option>
                        <?php
                        $brands = $conn->query("SELECT * FROM brands ORDER BY name");
                        while($b = $brands->fetch_assoc()) {
                            echo "<option value='{$b['id']}'>{$b['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>COLOR (E.G., ROSE, YELLOW)</label>
                    <input type="text" name="color" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>SIZE (E.G., 15MM)</label>
                    <input type="text" name="size" class="form-control">
                </div>

                <div class="form-group">
                    <label>APPROX WEIGHT (E.G., 4.51G)</label>
                    <input type="text" name="weight" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>DIAMOND INFO (E.G., 0.0 CT)</label>
                    <input type="text" name="diamond_info" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>PRICE (AED)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label>WHATSAPP NUMBER</label>
                    <input type="text" name="whatsapp" class="form-control" required value="7899090083">
                </div>

                <div class="form-group">
                    <label>STOCK STATUS</label>
                    <select name="status" class="form-control">
                        <option value="In Stock">In Stock</option>
                        <option value="Sold Out">Sold Out</option>
                        <option value="Reserved">Reserved</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 20px;">
                <label>Description (Optional)</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
            </div>
            
            <div class="form-group">
                <label>Product Images (Select multiple)</label>
                <input type="file" name="images[]" class="form-control" required accept="image/*, .avif" multiple>
            </div>
            
            <input type="hidden" name="action_add" value="1">
            <button type="submit" name="add_product" class="btn" style="width: 200px; margin-top: 20px;">Add Product</button>
        </form>
    </div>

    <!-- Products List -->
    <div style="background: #fff; padding: 20px; border: 1px solid #eee; border-radius: 4px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; margin-bottom: 20px;">Existing Inventory</h3>
        
        <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label style="font-size: 10px; color: #888;">SEARCH (NAME, TYPE, REF)</label>
                <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label style="font-size: 10px; color: #888;">CATEGORY</label>
                <select name="filter_category" class="form-control">
                    <option value="">All Categories</option>
                    <?php
                    $cats = $conn->query("SELECT * FROM categories ORDER BY name");
                    while($c = $cats->fetch_assoc()) {
                        $sel = (isset($_GET['filter_category']) && $_GET['filter_category'] == $c['id']) ? 'selected' : '';
                        echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label style="font-size: 10px; color: #888;">BRAND</label>
                <select name="filter_brand" class="form-control">
                    <option value="">All Brands</option>
                    <?php
                    $brands = $conn->query("SELECT * FROM brands ORDER BY name");
                    while($b = $brands->fetch_assoc()) {
                        $sel = (isset($_GET['filter_brand']) && $_GET['filter_brand'] == $b['id']) ? 'selected' : '';
                        echo "<option value='{$b['id']}' $sel>{$b['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group" style="margin: 0;">
                <label style="font-size: 10px; color: #888;">STATUS</label>
                <select name="filter_status" class="form-control">
                    <option value="">All Status</option>
                    <option value="In Stock" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'In Stock') echo 'selected'; ?>>In Stock</option>
                    <option value="Sold Out" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'Sold Out') echo 'selected'; ?>>Sold Out</option>
                    <option value="Reserved" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'Reserved') echo 'selected'; ?>>Reserved</option>
                </select>
            </div>

        </form>

        <!-- Bulk Price Update Form -->
        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px dashed #ddd;">
            <h4 style="margin-top: 0; margin-bottom: 15px; font-size: 13px; color: #d94589; letter-spacing: 1px;">BULK PRICE UPDATE (ALL PRODUCTS)</h4>
            <form method="POST" style="display: flex; gap: 10px; align-items: end; background: #fff5f9; padding: 15px; border-radius: 4px; border: 1px solid #fbcfe8;">
                <div class="form-group" style="margin: 0;">
                    <label style="font-size: 10px; color: #888;">OPERATION</label>
                    <select name="operation" class="form-control" style="font-size: 12px;">
                        <option value="increase">Increase (+)</option>
                        <option value="decrease">Decrease (-)</option>
                    </select>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label style="font-size: 10px; color: #888;">AMOUNT (AED)</label>
                    <input type="number" name="amount" class="form-control" placeholder="E.g. 500" required style="font-size: 12px;">
                </div>
                <input type="hidden" name="action_bulk" value="1">
                <button type="submit" name="bulk_price_update" class="btn" style="padding: 10px 20px; background: #d94589; font-size: 12px;" onclick="return confirm('WARNING: Are you sure you want to update prices for ALL products? This action cannot be undone.')">Update All Prices</button>
            </form>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="40">REF</th>
                <th width="60">Image</th>
                <th>Brand</th>
                <th>Type</th>
                <th width="80">Color</th>
                <th>Size</th>
                <th width="60">Weight</th>
                <th width="60">Diamond</th>
                <th>Price</th>
                <th width="80">Status</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search_query = "";
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $s = $conn->real_escape_string($_GET['search']);
                $search_query .= " AND (p.name LIKE '%$s%' OR b.name LIKE '%$s%' OR p.type LIKE '%$s%' OR p.id = '$s')";
            }
            if (!empty($_GET['filter_category'])) {
                $cat_id = intval($_GET['filter_category']);
                $search_query .= " AND p.category_id = $cat_id";
            }
            if (!empty($_GET['filter_brand'])) {
                $brand_id = intval($_GET['filter_brand']);
                $search_query .= " AND p.brand_id = $brand_id";
            }
            if (!empty($_GET['filter_status'])) {
                $status = $conn->real_escape_string($_GET['filter_status']);
                $search_query .= " AND p.status = '$status'";
            }

            $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN brands b ON p.brand_id = b.id 
                    WHERE 1=1 $search_query
                    ORDER BY p.id DESC";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td><strong>#{$row['id']}</strong></td>
                        <td><img src='../{$row['image']}' style='width: 50px; height: 50px; object-fit: cover; border-radius: 4px;'></td>
                        <td><strong>{$row['brand_name']}</strong></td>
                        <td>{$row['type']}</td>
                        <td>{$row['color']}</td>
                        <td>{$row['size']}</td>
                        <td>{$row['weight']}</td>
                        <td>{$row['diamond_info']}</td>
                        <td>AED ".number_format($row['price'])."</td>
                        <td><span class='badge' style='background: ".($row['status'] == 'In Stock' ? '#28a745' : '#dc3545')."'>{$row['status']}</span></td>
                        <td>
                            <a href='edit_product.php?id={$row['id']}' class='btn' style='padding: 5px 10px; font-size: 10px; background: #6c757d;'>Edit</a>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='delete_product' class='btn btn-danger' style='padding: 5px 10px; font-size: 10px;' onclick='return confirm(\"Are you sure?\")'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
document.querySelector('select[name="category_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const categoryName = selectedOption.text.trim().toLowerCase();
    const typeSelect = document.getElementById('product_type');
    
    if (categoryName && categoryName !== 'select category') {
        // Find matching option in type dropdown (case-insensitive)
        for (let i = 0; i < typeSelect.options.length; i++) {
            const typeVal = typeSelect.options[i].value.toLowerCase();
            if (typeVal === categoryName || categoryName.includes(typeVal) || typeVal.includes(categoryName)) {
                typeSelect.selectedIndex = i;
                return;
            }
        }
        // If it's already set to a valid type, don't revert to "Other" just because category name changed
        if (typeSelect.value !== "" && typeSelect.value !== "Other") {
            return;
        }
        typeSelect.value = "Other";
    }
});

// Prevent multiple submissions for the Add Product form
const addForm = document.querySelector('form[enctype="multipart/form-data"]');
if (addForm) {
    addForm.addEventListener('submit', function() {
        const btn = this.querySelector('button[name="add_product"]');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Saving...';
        }
    });
}
</script>
</body>
</html>
