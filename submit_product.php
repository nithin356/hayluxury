<?php
include 'includes/header.php';
include 'includes/utils.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_product'])) {
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
    
    // Status for user submissions could be 'Reserved' or we could add a 'Pending' status.
    // For now, let's keep it 'In Stock' but maybe the client wants to review them.
    $status = 'In Stock'; 

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $uploaded_images = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $name_val) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $image_name = time() . '_user_' . $key . '_' . basename($_FILES["images"]["name"][$key]);
                $target_file = $target_dir . $image_name;
                
                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
                    addWatermark($target_file);
                    $uploaded_images[] = "uploads/" . $image_name;
                }
            }
        }
    }

    if (empty($uploaded_images)) {
        echo "<script>alert('Please upload at least one image.');</script>";
    } else {
        $primary_image = $uploaded_images[0];
        $sql = "INSERT INTO products (name, category_id, brand_id, type, color, size, weight, diamond_info, description, price, image, whatsapp_number, status) 
                VALUES ('$name', $category_id, $brand_id, '$type', '$color', '$size', '$weight', '$diamond_info', '$description', $price, '$primary_image', '$whatsapp', '$status')";
        
        if ($conn->query($sql) === TRUE) {
            $product_id = $conn->insert_id;
            foreach ($uploaded_images as $idx => $path) {
                $is_primary = ($idx === 0) ? 1 : 0;
                $conn->query("INSERT INTO product_images (product_id, image_path, is_primary) VALUES ($product_id, '$path', $is_primary)");
            }
            echo "<script>alert('Product submitted successfully! Thank you.'); window.location='index.php';</script>";
        } else {
            echo "<div class='container'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<div class="product-table-container" style="max-width: 800px; margin: 40px auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
    <h1 style="font-family: var(--font-heading); text-align: center; margin-bottom: 30px; letter-spacing: 2px;">SUBMIT YOUR PIECE</h1>
    
    <form method="POST" enctype="multipart/form-data">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>PRODUCT NAME</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Vintage Gold Ring">
            </div>
            
            <div class="form-group">
                <label>TYPE</label>
                <select name="type" class="form-control" required>
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
                <label>COLOR</label>
                <input type="text" name="color" class="form-control" placeholder="e.g. Yellow Gold">
            </div>
            
            <div class="form-group">
                <label>SIZE</label>
                <input type="text" name="size" class="form-control" placeholder="e.g. 52">
            </div>

            <div class="form-group">
                <label>WEIGHT</label>
                <input type="text" name="weight" class="form-control" placeholder="e.g. 12g">
            </div>
            
            <div class="form-group">
                <label>DIAMOND INFO</label>
                <input type="text" name="diamond_info" class="form-control" placeholder="e.g. 0.5ct VVS">
            </div>
            
            <div class="form-group">
                <label>ASKING PRICE (AED)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>CONTACT WHATSAPP</label>
                <input type="text" name="whatsapp" class="form-control" required value="7899090083">
            </div>
        </div>
        
        <div class="form-group" style="margin-top: 20px;">
            <label>DESCRIPTION / NOTES</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Tell us more about the piece..."></textarea>
        </div>
        
        <div class="form-group" style="margin-top: 20px;">
            <label>IMAGES (SELECT MULTIPLE)</label>
            <input type="file" name="images[]" class="form-control" required accept="image/*" multiple>
            <small style="color: #888; font-size: 10px; margin-top: 5px; display: block;">High quality images improve your chances of sale.</small>
        </div>
        
        <button type="submit" name="submit_product" class="btn" style="width: 100%; margin-top: 30px; height: 50px; font-size: 14px;">SUBMIT PIECE</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
