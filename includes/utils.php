<?php
// Shared utility functions for Hay.Luxury

/**
 * Adds multiple burned-in watermarks to an image
 * @param string $path Path to the image file
 */
function addWatermark($path) {
    if (!extension_loaded('gd')) return; 

    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    switch($extension) {
        case 'jpeg':
        case 'jpg': $img = @imagecreatefromjpeg($path); break;
        case 'png':  $img = @imagecreatefrompng($path); break;
        default: return;
    }
    
    if (!$img) return;

    $text = "HAY.LUXURY";
    $font_size = 5; 
    
    $grey = imagecolorallocatealpha($img, 128, 128, 128, 50); 
    $white = imagecolorallocatealpha($img, 255, 255, 255, 60);
    
    $width = imagesx($img);
    $height = imagesy($img);
    
    $font_width = imagefontwidth($font_size);
    $font_height = imagefontheight($font_size);
    $text_width = $font_width * strlen($text);
    
    // 1. Center
    imagestring($img, $font_size, ($width/2) - ($text_width/2) + 1, ($height/2) + 1, $text, $grey);
    imagestring($img, $font_size, ($width/2) - ($text_width/2), ($height/2), $text, $white);
    
    // 2. Top Left
    imagestring($img, $font_size, 21, 21, $text, $grey);
    imagestring($img, $font_size, 20, 20, $text, $white);
    
    // 3. Top Right
    imagestring($img, $font_size, $width - $text_width - 19, 21, $text, $grey);
    imagestring($img, $font_size, $width - $text_width - 20, 20, $text, $white);
    
    // 4. Bottom Left
    imagestring($img, $font_size, 21, $height - $font_height - 19, $text, $grey);
    imagestring($img, $font_size, 20, $height - $font_height - 20, $text, $white);

    // 5. Bottom Right
    imagestring($img, $font_size, $width - $text_width - 21, $height - $font_height - 21, $text, $grey);
    imagestring($img, $font_size, $width - $text_width - 20, $height - $font_height - 20, $text, $white);

    switch($extension) {
        case 'jpeg':
        case 'jpg': imagejpeg($img, $path, 90); break;
        case 'png':  imagepng($img, $path); break;
    }
    imagedestroy($img);
}
?>
