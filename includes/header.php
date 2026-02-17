<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAY.LUXURY</title>
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75' fill='%23FFD700'>⚜</text></svg>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false">
    <script>
    // Disable right-click
    document.addEventListener('contextmenu', event => event.preventDefault());

    // Disable key shortcuts like Ctrl+C, Ctrl+U, F12
    document.onkeydown = function(e) {
        if (e.keyCode == 123) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
            return false;
        }
        if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
            return false;
        }
        if (e.ctrlKey && e.keyCode == 'C'.charCodeAt(0)) {
            return false;
        }
    }

    let currentImages = [];
    let currentIndex = 0;

    function openLightbox(imagesJson, startIndex = 0) {
        currentImages = JSON.parse(imagesJson);
        currentIndex = startIndex;
        updateLightboxImage();
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scroll
    }

    function updateLightboxImage() {
        const img = document.getElementById('lightbox-img');
        const countDisplay = document.getElementById('lightbox-count');
        const thumbs = document.getElementById('lightbox-thumbnails');

        img.src = currentImages[currentIndex];
        img.classList.remove('zoomed');

        countDisplay.innerText = (currentIndex + 1) + ' / ' + currentImages.length;

        // Update thumbnails
        thumbs.innerHTML = '';
        currentImages.forEach((src, idx) => {
            const thumb = document.createElement('img');
            thumb.src = src;
            thumb.className = 'lightbox-thumb' + (idx === currentIndex ? ' active' : '');
            thumb.onclick = (e) => {
                e.stopPropagation();
                currentIndex = idx;
                updateLightboxImage();
            };
            thumbs.appendChild(thumb);
        });

        // Toggle arrows
        document.getElementById('lightbox-prev').style.display = currentImages.length > 1 ? 'block' : 'none';
        document.getElementById('lightbox-next').style.display = currentImages.length > 1 ? 'block' : 'none';
    }

    function prevImage(e) {
        if (e) e.stopPropagation();
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : currentImages.length - 1;
        updateLightboxImage();
    }

    function nextImage(e) {
        if (e) e.stopPropagation();
        currentIndex = (currentIndex < currentImages.length - 1) ? currentIndex + 1 : 0;
        updateLightboxImage();
    }

    // Zoom functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('lightbox-img').addEventListener('click', function(e) {
            e.stopPropagation();
            this.classList.toggle('zoomed');
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('lightbox').style.display === 'flex') {
                if (e.key === 'ArrowLeft') prevImage();
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'Escape') document.getElementById('lightbox').style.display = 'none';
            }
        });
    });
    </script>

    <div id="lightbox" onclick="this.style.display='none'; document.body.style.overflow='auto';">
        <span class="close"
            onclick="document.getElementById('lightbox').style.display='none'; document.body.style.overflow='auto';">&times;</span>

        <div id="lightbox-prev" class="lightbox-nav-btn prev" onclick="prevImage(event)">&#10094;</div>
        <div id="lightbox-next" class="lightbox-nav-btn next" onclick="nextImage(event)">&#10095;</div>

        <div class="lightbox-content-wrapper">
            <div class="protected-view" style="position: relative; max-width: 100%; max-height: 80vh;">
                <div class="image-protection-overlay"></div>
                <img id="lightbox-img" src="" alt="Full View">
            </div>

            <div id="lightbox-count" style="color: #fff; margin-top: 15px; font-size: 12px; letter-spacing: 2px;"></div>

            <div id="lightbox-thumbnails" class="lightbox-thumbs"></div>
        </div>
    </div>

    <div id="qa-banner" class="qa-banner">
        <div class="qa-text">
            QUALITY ASSURANCE: All products <span class="qa-highlight">18 karat gold and diamond</span> · In Dubai:
            <span class="qa-highlight">COD available</span> - Check, examine, and purchase with confidence
        </div>
        <button class="qa-close"
            onclick="document.getElementById('qa-banner').style.display='none'; document.body.style.paddingTop='0';">&times;</button>
    </div>

    <header>
        <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
        <a href="/admin/logout.php"
            style="position: absolute; top: 10px; right: 20px; font-size: 10px; text-transform: uppercase; color: red;">Logout</a>
        <?php endif; ?>

        <div class="top-bar">
            <div class="logo-container">
                <div class="logo">HAY.<span>LUXURY</span></div>
                <img src="<?php echo BASE_URL; ?>/penguin/peng.gif" alt="Penguin" class="penguin-mascot">
            </div>
        </div>

        <nav>
            <ul>
                <li><a href="/index.php"
                        class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' && !isset($_GET['cat']) ? 'active' : ''; ?>">All</a>
                </li>
                <?php
            // Fetch categories for the menu
            $cat_sql = "SELECT * FROM categories ORDER BY name";
            $cat_result = $conn->query($cat_sql);
            if ($cat_result->num_rows > 0) {
                while($cat_row = $cat_result->fetch_assoc()) {
                    $active = (isset($_GET['cat']) && $_GET['cat'] == $cat_row['id']) ? 'active' : '';
                    echo '<li><a href="/index.php?cat=' . $cat_row['id'] . '" class="' . $active . '">' . $cat_row['name'] . '</a></li>';
                }
            }
            ?>
                <!-- <li><a href="/hayluxury/submit_product.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'submit_product.php' ? 'active' : ''; ?>" style="color: var(--gold); border-bottom-color: var(--gold);">Sell</a></li> -->
            </ul>
        </nav>
    </header>
