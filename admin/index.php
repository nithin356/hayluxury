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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        <a href="manage_gold_price.php">Gold Price</a>
        <a href="../index.php" target="_blank" style="float: right; color: var(--black);"><i class="fas fa-external-link-alt"></i> Public Site</a>
    </div>

    <div class="dashboard-stats" style="display: flex; gap: 30px; flex-wrap: wrap;">
        <?php
        $cat_count = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];
        $brand_count = $conn->query("SELECT COUNT(*) as count FROM brands")->fetch_assoc()['count'];
        $prod_count = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
        
        // Get gold price (24K)
        $gold_24k = 0;
        $gold_res = $conn->query("SELECT price_per_gram FROM gold_price_settings WHERE karat = '24K'");
        if ($gold_res && $gold_row = $gold_res->fetch_assoc()) {
            $gold_24k = $gold_row['price_per_gram'];
        }
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

        <a href="manage_gold_price.php" class="stat-card" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 30px; flex: 1; min-width: 200px; border: none; border-radius: 4px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s; text-decoration: none; cursor: pointer;">
            <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: #8892b0; margin-bottom: 10px;"><i class="fas fa-coins" style="color: var(--gold);"></i> Gold 24K/g</div>
            <div style="font-size: 40px; font-weight: 300; color: var(--gold); font-family: var(--font-heading);"><?php echo $gold_24k > 0 ? number_format($gold_24k) : '—'; ?></div>
        </a>
    </div>

</div>

</body>
</html>
