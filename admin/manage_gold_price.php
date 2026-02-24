<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
include '../includes/db.php';

// Ensure table exists
$conn->query("CREATE TABLE IF NOT EXISTS `gold_price_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `karat` varchar(10) NOT NULL,
  `price_per_gram` decimal(10,2) NOT NULL DEFAULT 0.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `karat` (`karat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$conn->query("CREATE TABLE IF NOT EXISTS `gold_price_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `karat` varchar(10) NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// Insert default karat rows if missing
$karats = ['24K', '22K', '21K', '18K'];
foreach ($karats as $k) {
    $conn->query("INSERT IGNORE INTO gold_price_settings (karat, price_per_gram) VALUES ('$k', 0)");
}

$success_msg = '';
$error_msg = '';

// Handle gold price update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_gold_prices'])) {
    $updated = 0;
    foreach ($_POST['price'] as $karat => $price) {
        $karat_safe = $conn->real_escape_string($karat);
        $new_price = floatval($price);

        // Get old price for history
        $old_res = $conn->query("SELECT price_per_gram FROM gold_price_settings WHERE karat = '$karat_safe'");
        $old_price = 0;
        if ($old_row = $old_res->fetch_assoc()) {
            $old_price = floatval($old_row['price_per_gram']);
        }

        if ($old_price != $new_price) {
            $conn->query("UPDATE gold_price_settings SET price_per_gram = $new_price WHERE karat = '$karat_safe'");
            $conn->query("INSERT INTO gold_price_history (karat, old_price, new_price) VALUES ('$karat_safe', $old_price, $new_price)");
            $updated++;
        }
    }
    $success_msg = $updated > 0 ? "Gold prices updated! ($updated karat" . ($updated > 1 ? 's' : '') . " changed)" : "No changes detected.";
}

// Handle bulk recalculate
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_recalculate'])) {
    $selected_karat = $conn->real_escape_string($_POST['calc_karat']);
    $making_charge_pct = floatval($_POST['making_charge']);
    $wastage_pct = floatval($_POST['wastage']);
    $filter_brand = isset($_POST['calc_brand']) ? intval($_POST['calc_brand']) : 0;
    $filter_category = isset($_POST['calc_category']) ? intval($_POST['calc_category']) : 0;

    // Get gold price per gram for selected karat
    $gold_res = $conn->query("SELECT price_per_gram FROM gold_price_settings WHERE karat = '$selected_karat'");
    $gold_row = $gold_res->fetch_assoc();
    $gold_rate = floatval($gold_row['price_per_gram']);

    if ($gold_rate <= 0) {
        $error_msg = "Gold price for $selected_karat is not set. Please update it first.";
    } else {
        // Build WHERE clause
        $where = "WHERE weight IS NOT NULL AND weight != '' AND weight REGEXP '[0-9]'";
        if ($filter_brand > 0) $where .= " AND brand_id = $filter_brand";
        if ($filter_category > 0) $where .= " AND category_id = $filter_category";

        // Fetch matching products
        $products = $conn->query("SELECT id, weight, price FROM products $where");
        $updated_count = 0;
        $skipped = 0;

        while ($p = $products->fetch_assoc()) {
            // Extract numeric weight from string like "4.51g" or "4.51 g" or "4.51"
            preg_match('/([0-9]+\.?[0-9]*)/', $p['weight'], $matches);
            if (!empty($matches[1])) {
                $weight_grams = floatval($matches[1]);
                
                // Calculate: (weight × gold_rate) + wastage% + making_charge%
                $base_price = $weight_grams * $gold_rate;
                $wastage_amt = $base_price * ($wastage_pct / 100);
                $subtotal = $base_price + $wastage_amt;
                $making_amt = $subtotal * ($making_charge_pct / 100);
                $final_price = round($subtotal + $making_amt, 2);

                $conn->query("UPDATE products SET price = $final_price WHERE id = {$p['id']}");
                $updated_count++;
            } else {
                $skipped++;
            }
        }

        $success_msg = "Bulk recalculated! $updated_count products updated using $selected_karat @ AED " . number_format($gold_rate, 2) . "/g.";
        if ($skipped > 0) $success_msg .= " ($skipped skipped — no valid weight)";
    }
}

// Fetch current prices
$prices = [];
$res = $conn->query("SELECT * FROM gold_price_settings ORDER BY FIELD(karat, '24K', '22K', '21K', '18K')");
while ($row = $res->fetch_assoc()) {
    $prices[$row['karat']] = $row;
}

// Fetch recent history
$history = $conn->query("SELECT * FROM gold_price_history ORDER BY changed_at DESC LIMIT 20");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold Price Manager - Hay.Luxury</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f8f8; padding-top: 0; }
        .admin-nav a { display: inline-block; padding: 10px 20px; }

        .gold-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .gold-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(197,160,89,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .gold-card h2 {
            margin: 0 0 5px 0;
            font-family: var(--font-heading);
            color: var(--gold);
            font-size: 24px;
        }
        .gold-card .subtitle {
            color: #8892b0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
        }

        .karat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .karat-box {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(197,160,89,0.3);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }
        .karat-box:hover {
            border-color: var(--gold);
            background: rgba(197,160,89,0.08);
        }
        .karat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--gold);
            margin-bottom: 8px;
            font-weight: 600;
        }
        .karat-price {
            font-size: 28px;
            font-family: var(--font-heading);
            color: #fff;
            margin-bottom: 8px;
        }
        .karat-input {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(197,160,89,0.3);
            border-radius: 4px;
            background: rgba(0,0,0,0.3);
            color: #fff;
            font-size: 16px;
            text-align: center;
            font-family: var(--font-body);
            outline: none;
            transition: border-color 0.3s;
        }
        .karat-input:focus {
            border-color: var(--gold);
        }
        .karat-updated {
            font-size: 10px;
            color: #666;
            margin-top: 8px;
        }

        .calc-section {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .calc-section h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        .calc-section .section-desc {
            font-size: 12px;
            color: #888;
            margin-bottom: 20px;
        }
        .calc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            align-items: end;
        }
        .calc-grid .form-group { margin: 0; }
        .calc-grid label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            display: block;
            margin-bottom: 5px;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .history-table th {
            background: #f5f5f5;
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            border-bottom: 1px solid #eee;
        }
        .history-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f5f5f5;
        }
        .price-up { color: #dc3545; }
        .price-down { color: #28a745; }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: #fff;
            border: none;
            padding: 12px 30px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-radius: 4px;
            cursor: pointer;
            font-family: var(--font-body);
            transition: all 0.3s;
        }
        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(197,160,89,0.4);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .preview-box {
            background: #fffbf0;
            border: 1px dashed var(--gold);
            border-radius: 6px;
            padding: 15px 20px;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
            display: none;
        }
        .preview-box.visible { display: block; }
    </style>
</head>
<body>

<div class="admin-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <h1 style="margin: 0; font-family: var(--font-heading); font-size: 32px;">Gold Price Manager</h1>
        <div style="font-size: 14px; color: #888;"><i class="fas fa-coins" style="color: var(--gold);"></i> Dubai Gold Rate</div>
    </div>
    
    <div class="admin-nav">
        <a href="index.php">Dashboard</a>
        <a href="manage_categories.php">Categories</a>
        <a href="manage_brands.php">Brands</a>
        <a href="manage_products.php">Products</a>
        <a href="manage_gold_price.php" style="color: var(--gold); border-bottom: 2px solid var(--gold);">Gold Price</a>
        <a href="../index.php" target="_blank" style="float: right; color: var(--black);"><i class="fas fa-external-link-alt"></i> Public Site</a>
    </div>

    <?php if ($success_msg): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success_msg; ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?></div>
    <?php endif; ?>

    <!-- Gold Rate Card -->
    <form method="POST">
        <div class="gold-card">
            <h2><i class="fas fa-coins"></i> Dubai Gold Rate</h2>
            <div class="subtitle">Price per gram in AED &bull; Update when rates change</div>
            
            <div class="karat-grid">
                <?php foreach ($prices as $karat => $data): ?>
                <div class="karat-box">
                    <div class="karat-label"><?php echo $karat; ?> Gold</div>
                    <div class="karat-price" id="display_<?php echo $karat; ?>">
                        AED <?php echo number_format($data['price_per_gram'], 2); ?>
                    </div>
                    <input type="number" step="0.01" name="price[<?php echo $karat; ?>]" 
                           class="karat-input" 
                           value="<?php echo $data['price_per_gram']; ?>"
                           placeholder="Price per gram"
                           oninput="document.getElementById('display_<?php echo $karat; ?>').innerText = 'AED ' + parseFloat(this.value || 0).toFixed(2)">
                    <div class="karat-updated">
                        Updated: <?php echo date('d M Y, h:i A', strtotime($data['updated_at'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div style="text-align: center; margin-top: 25px;">
                <button type="submit" name="update_gold_prices" class="btn-gold">
                    <i class="fas fa-save"></i> Save Gold Prices
                </button>
            </div>
        </div>
    </form>

    <!-- Bulk Recalculate Section -->
    <div class="calc-section">
        <h3><i class="fas fa-calculator" style="color: var(--gold);"></i> Bulk Price Recalculator</h3>
        <div class="section-desc">
            Recalculate product prices using: <strong>(Weight × Gold Rate) + Wastage% + Making Charge%</strong>. 
            Only products with a valid weight will be updated.
        </div>

        <form method="POST" id="calcForm">
            <div class="calc-grid">
                <div class="form-group">
                    <label>Gold Karat</label>
                    <select name="calc_karat" class="form-control" id="calcKarat" onchange="updatePreview()">
                        <?php foreach ($prices as $karat => $data): ?>
                        <option value="<?php echo $karat; ?>" data-rate="<?php echo $data['price_per_gram']; ?>">
                            <?php echo $karat; ?> — AED <?php echo number_format($data['price_per_gram'], 2); ?>/g
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Wastage %</label>
                    <input type="number" step="0.1" name="wastage" class="form-control" value="0" min="0" max="100" id="calcWastage" oninput="updatePreview()">
                </div>

                <div class="form-group">
                    <label>Making Charge %</label>
                    <input type="number" step="0.1" name="making_charge" class="form-control" value="0" min="0" max="200" id="calcMaking" oninput="updatePreview()">
                </div>

                <div class="form-group">
                    <label>Filter by Brand</label>
                    <select name="calc_brand" class="form-control">
                        <option value="0">All Brands</option>
                        <?php
                        $brands = $conn->query("SELECT * FROM brands ORDER BY name");
                        while ($b = $brands->fetch_assoc()) {
                            echo "<option value='{$b['id']}'>{$b['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Filter by Category</label>
                    <select name="calc_category" class="form-control">
                        <option value="0">All Categories</option>
                        <?php
                        $cats = $conn->query("SELECT * FROM categories ORDER BY name");
                        while ($c = $cats->fetch_assoc()) {
                            echo "<option value='{$c['id']}'>{$c['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group" style="display: flex; align-items: flex-end;">
                    <button type="submit" name="bulk_recalculate" class="btn-gold" style="width: 100%;" 
                            onclick="return confirm('This will recalculate and overwrite prices for matching products. Continue?')">
                        <i class="fas fa-sync-alt"></i> Recalculate
                    </button>
                </div>
            </div>

            <!-- Live Preview -->
            <div class="preview-box visible" id="previewBox">
                <i class="fas fa-lightbulb" style="color: var(--gold);"></i>
                <strong>Preview:</strong>
                <span id="previewText">For a 5g product → AED 0.00</span>
            </div>
        </form>
    </div>

    <!-- Price Change History -->
    <div class="calc-section">
        <h3><i class="fas fa-history" style="color: var(--gold);"></i> Price Change History</h3>
        <div class="section-desc">Recent gold price updates</div>

        <table class="history-table">
            <thead>
                <tr>
                    <th>Karat</th>
                    <th>Old Price (AED/g)</th>
                    <th>New Price (AED/g)</th>
                    <th>Change</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($history && $history->num_rows > 0):
                    while ($h = $history->fetch_assoc()):
                        $diff = $h['new_price'] - $h['old_price'];
                        $direction = $diff >= 0 ? 'up' : 'down';
                        $icon = $diff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                        $cls = $diff >= 0 ? 'price-up' : 'price-down';
                ?>
                <tr>
                    <td><strong><?php echo $h['karat']; ?></strong></td>
                    <td>AED <?php echo number_format($h['old_price'], 2); ?></td>
                    <td>AED <?php echo number_format($h['new_price'], 2); ?></td>
                    <td class="<?php echo $cls; ?>">
                        <i class="fas <?php echo $icon; ?>"></i>
                        AED <?php echo number_format(abs($diff), 2); ?>
                    </td>
                    <td><?php echo date('d M Y, h:i A', strtotime($h['changed_at'])); ?></td>
                </tr>
                <?php 
                    endwhile;
                else:
                ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #999; padding: 30px;">
                        No price changes recorded yet. Update gold prices above to start tracking.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function updatePreview() {
    const select = document.getElementById('calcKarat');
    const rate = parseFloat(select.options[select.selectedIndex].getAttribute('data-rate')) || 0;
    const wastage = parseFloat(document.getElementById('calcWastage').value) || 0;
    const making = parseFloat(document.getElementById('calcMaking').value) || 0;
    
    // Example calculation for a 5g product
    const sampleWeight = 5;
    const basePrice = sampleWeight * rate;
    const wastageAmt = basePrice * (wastage / 100);
    const subtotal = basePrice + wastageAmt;
    const makingAmt = subtotal * (making / 100);
    const finalPrice = subtotal + makingAmt;

    document.getElementById('previewText').innerHTML = 
        'For a <strong>' + sampleWeight + 'g</strong> product @ ' + select.value + ' → ' +
        'Base: AED ' + basePrice.toFixed(2) + 
        (wastage > 0 ? ' + Wastage: AED ' + wastageAmt.toFixed(2) : '') +
        (making > 0 ? ' + Making: AED ' + makingAmt.toFixed(2) : '') +
        ' = <strong>AED ' + finalPrice.toFixed(2) + '</strong>';
}

// Run on page load
updatePreview();
</script>

</body>
</html>
