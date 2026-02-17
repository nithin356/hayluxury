<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';
?>
<style>
    .logout-btn {
        float: right;
        font-size: 12px;
        color: red;
        text-transform: uppercase;
        margin-top: -30px;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hay.Luxury</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1 style="margin: 0; font-family: var(--font-heading); font-size: 32px;">Dashboard</h1>
        <div style="font-size: 14px; color: #888;">Welcome, Admin</div>
    </div>
    
    <div class="admin-nav">
        <a href="index.php" style="color: var(--gold); border-bottom: 2px solid var(--gold);">Overview</a>
        <a href="manage_categories.php">Categories</a>
        <a href="manage_brands.php">Brands</a>
        <a href="manage_products.php">Products</a>
        <a href="../index.php" target="_blank" style="float: right; color: var(--black);"><i class="fas fa-external-link-alt"></i> Public Site</a>
    </div>

    <div class="dashboard-stats" style="display: flex; gap: 30px; flex-wrap: wrap;">
        <?php
        $cat_count = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];
        $brand_count = $conn->query("SELECT COUNT(*) as count FROM brands")->fetch_assoc()['count'];
        $prod_count = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
        ?>
        
        <div class="stat-card" style="background: #fff; padding: 30px; flex: 1; min-width: 200px; border: 1px solid #eee; border-radius: 4px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s;">
            <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: #999; margin-bottom: 10px;">Categories</div>
            <div style="font-size: 40px; font-weight: 300; color: var(--gold); font-family: var(--font-heading);"><?php echo $cat_count; ?></div>
        </div>
        
        <div class="stat-card" style="background: #fff; padding: 30px; flex: 1; min-width: 200px; border: 1px solid #eee; border-radius: 4px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s;">
            <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: #999; margin-bottom: 10px;">Brands</div>
            <div style="font-size: 40px; font-weight: 300; color: var(--gold); font-family: var(--font-heading);"><?php echo $brand_count; ?></div>
        </div>
        
        <div class="stat-card" style="background: #fff; padding: 30px; flex: 1; min-width: 200px; border: 1px solid #eee; border-radius: 4px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s;">
            <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: #999; margin-bottom: 10px;">Products</div>
            <div style="font-size: 40px; font-weight: 300; color: var(--gold); font-family: var(--font-heading);"><?php echo $prod_count; ?></div>
        </div>
    </div>

</div>

</body>
</html>
