<?php include 'includes/header.php'; ?>

<!-- Status Bar -->
<div class="status-bar">
    <?php
    $total_pieces = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
    ?>
    <span><?php echo $total_pieces; ?> pieces</span>
    <span>Read Only</span>
    <span>Copy Protected</span>
</div>

<!-- Search Bar -->
<div class="search-container">
    <form method="GET" action="index.php">
        <?php if(isset($_GET['cat'])): ?>
            <input type="hidden" name="cat" value="<?php echo htmlspecialchars($_GET['cat']); ?>">
        <?php endif; ?>
        <?php if(isset($_GET['brand'])): ?>
            <input type="hidden" name="brand" value="<?php echo htmlspecialchars($_GET['brand']); ?>">
        <?php endif; ?>
        <i class="fas fa-search search-icon"></i>
        <input type="text" name="q" class="search-input" placeholder="Search by brand, name or type..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
    </form>
</div>

<!-- Filter Section -->
<div class="filter-section">
    
    <div class="filter-tags">
        <a href="index.php<?php echo isset($_GET['cat']) ? '?cat='.$_GET['cat'] : ''; ?>" class="filter-tag <?php echo !isset($_GET['brand']) ? 'active' : ''; ?>">All</a>
        <?php
        // Fetch brands with counts, optionally filtered by category
        $cat_filter = isset($_GET['cat']) ? "WHERE id IN (SELECT brand_id FROM products WHERE category_id = " . intval($_GET['cat']) . ")" : "";
        
        $brand_sql = "SELECT b.id, b.name, COUNT(p.id) as count 
                      FROM brands b 
                      LEFT JOIN products p ON b.id = p.brand_id 
                      ";
        
        if (isset($_GET['cat'])) {
            $brand_sql .= " AND p.category_id = " . intval($_GET['cat']);
        }
        
        $brand_sql .= " GROUP BY b.id HAVING count > 0 ORDER BY count DESC, b.name ASC";
        
        $brand_result = $conn->query($brand_sql);
        
        while($brand = $brand_result->fetch_assoc()) {
            $url_params = $_GET;
            $url_params['brand'] = $brand['id'];
            // Keep search query in filter links
            $url = 'index.php?' . http_build_query($url_params);
            
            $active = (isset($_GET['brand']) && $_GET['brand'] == $brand['id']) ? 'active' : '';
            
            echo '<a href="'.$url.'" class="filter-tag '.$active.'">'.$brand['name'].' ('.$brand['count'].')</a>';
        }
        ?>
    </div>
</div>

<!-- Product Grid -->
    <?php
    $sql = "SELECT p.*, b.name as brand_name, c.name as category_name 
            FROM products p 
            JOIN brands b ON p.brand_id = b.id 
            JOIN categories c ON p.category_id = c.id 
            WHERE 1=1";
            
    if (isset($_GET['cat'])) {
        $sql .= " AND p.category_id = " . intval($_GET['cat']);
    }
    
    if (isset($_GET['brand'])) {
        $sql .= " AND p.brand_id = " . intval($_GET['brand']);
    }

    if (isset($_GET['q']) && !empty($_GET['q'])) {
        $q = $conn->real_escape_string($_GET['q']);
        $sql .= " AND (p.name LIKE '%$q%' OR b.name LIKE '%$q%' OR p.type LIKE '%$q%')";
    }
    
    $sql .= " ORDER BY p.id DESC";
    
    $result = $conn->query($sql);
    ?>

    <!-- Product Table View -->
    <div class="product-table-container" style="padding: 20px 40px; overflow-x: auto;">
        <table class="luxury-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Brand</th>
                    <th>Type</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Status</th>
                    <th class="desktop-hide">Approx Weight</th>
                    <th>Diamond</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $status_label = $row['status'];
                        $status_class = 'available';

                        if ($status_label === 'In Stock') {
                            $status_label = 'Available';
                            $status_class = 'available';
                        } elseif ($status_label === 'Sold Out') {
                            $status_class = 'sold-out';
                        } elseif ($status_label === 'Reserved') {
                            $status_class = 'reserved';
                        }
                        
                        $status_html = "<span class='status-badge {$status_class}'>{$status_label}</span>";
                        
                        // Fetch all images for this product
                        $images_sql = "SELECT image_path FROM product_images WHERE product_id = {$row['id']} ORDER BY is_primary DESC, id ASC";
                        $images_res = $conn->query($images_sql);
                        $image_paths = [];
                        while($img_row = $images_res->fetch_assoc()) {
                            $image_paths[] = $img_row['image_path'];
                        }
                        if (empty($image_paths)) $image_paths[] = $row['image']; // Fallback
                        
                        $images_json = htmlspecialchars(json_encode($image_paths), ENT_QUOTES, 'UTF-8');

                        echo "<tr>";
                        echo "<td data-label='Image'>
                                <div class='image-container' onclick=\"openLightbox('$images_json')\" style='cursor: pointer;'>
                                    <div class='image-protection-overlay'></div>
                                    <img src='{$row['image']}' alt='{$row['name']}' class='table-img'>
                                </div>
                              </td>";
                        echo "<td data-label='Brand'><span class='brand-tag' style='display:none;'>{$row['brand_name']}</span></td>";
                        echo "<td data-label='Type'>" . ($row['type'] ? $row['type'] : '-') . "</td>";
                        echo "<td data-label='Color'>" . ($row['color'] ? $row['color'] : '-') . "</td>";
                        echo "<td data-label='Size'>" . ($row['size'] ? $row['size'] : '-') . "</td>";
                        echo "<td data-label='Status'>{$status_html}</td>";
                        echo "<td data-label='Approx Weight' class='desktop-hide'>" . ($row['weight'] ? $row['weight'] : '-') . "</td>";
                        echo "<td data-label='Diamond'>" . ($row['diamond_info'] ? $row['diamond_info'] : '-') . "</td>";
                        $host = $_SERVER['HTTP_HOST'] ?? '';
                        $image_url = "https://{$host}/" . ltrim($row['image'], '/');
                        $preview_url = "https://{$host}/product_share.php?id={$row['id']}";

                        $wa_message = "I am interested in:\n" .
                                     "*Name:* {$row['name']}\n" .
                                     "*REF:* {$row['id']}\n" .
                                     "*Brand:* {$row['brand_name']}\n" .
                                     "*Type:* {$row['type']}\n" .
                                     "*Color:* " . ($row['color'] ?: '-') . "\n" .
                                     "*Size:* " . ($row['size'] ?: '-') . "\n" .
                                     "*Approx Weight:* " . ($row['weight'] ?: '-') . "\n" .
                                     "*Preview:* {$preview_url}\n" .
                                     "*Image:* {$image_url}";
                        $wa_encoded = urlencode($wa_message);
                        
                        echo "<td data-label='Contact'>
                            <a href='https://wa.me/{$row['whatsapp_number']}?text={$wa_encoded}' target='_blank' class='whatsapp-btn-small'>
                                <i class='fab fa-whatsapp'></i> Inquire
                            </a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align:center; padding: 40px;'>No pieces found in this collection.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

<?php include 'includes/footer.php'; ?>
