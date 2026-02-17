<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

// Handle Add/Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_category'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $slug = strtolower(str_replace(' ', '-', $name));
        $conn->query("INSERT INTO categories (name, slug) VALUES ('$name', '$slug')");
    } elseif (isset($_POST['delete_category'])) {
        $id = intval($_POST['id']);
        
        // Check if category has products
        $check = $conn->query("SELECT COUNT(*) as count FROM products WHERE category_id = $id");
        $row = $check->fetch_assoc();
        
        if ($row['count'] > 0) {
            echo "<script>alert('Cannot delete category: {$row['count']} products are still assigned to it.');</script>";
        } else {
            $conn->query("DELETE FROM categories WHERE id = $id");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Hay.Luxury</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background-color: #f8f8f8; padding-top: 0; }
        .admin-nav a { display: inline-block; padding: 10px 20px; }
        .table-container { background: #fff; padding: 30px; border-radius: 4px; border: 1px solid #eee; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1 style="margin: 0; font-family: var(--font-heading); font-size: 32px;">Manage Categories</h1>
        <div style="font-size: 14px; color: #888;">Organize your collection</div>
    </div>
    
    <div class="admin-nav">
        <a href="index.php">Dashboard</a>
        <a href="manage_categories.php" style="color: var(--gold); border-bottom: 2px solid var(--gold);">Categories</a>
        <a href="manage_brands.php">Brands</a>
        <a href="manage_products.php">Products</a>
         <a href="../index.php" target="_blank" style="float: right; color: var(--black);">Public Site</a>
    </div>

    <!-- Add Category Form -->
    <div style="background: #fff; padding: 30px; border: 1px solid #eee; border-radius: 4px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 30px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Add New Category</h3>
        <form method="POST">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. Necklaces">
            </div>
            <button type="submit" name="add_category" class="btn">Add Category</button>
        </form>
    </div>

    <!-- Categories List -->
    <h3>Existing Categories</h3>
    <table class="table">
        <thead>
            <tr>
                <th width="50">ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['slug']}</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='delete_category' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
