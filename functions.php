<?php
function ensureDir($dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

function compressImage($source, $destination, $max_width = 1200, $quality = 80) {
    if (!file_exists($source) || !function_exists('imagecreatefromjpeg')) {
        return; // защита от 500
    }
    list($width, $height, $type) = getimagesize($source);
    $ratio = $width / $height;
    if ($width > $max_width) {
        $width = $max_width;
        $height = (int)($width / $ratio);
    }
    $new_image = imagecreatetruecolor($width, $height);
    
    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = @imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $image = @imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $image = @imagecreatefromgif($source);
            break;
        default:
            return;
    }
    
    if ($image) {
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        imagejpeg($new_image, $destination, $quality);
        imagedestroy($image);
    }
    imagedestroy($new_image);
}

function getAlbums() {
    ensureDir('images/albums/');
    $folders = array_diff(scandir('images/albums/'), ['.', '..']);
    return array_filter($folders, function($f) { return is_dir('images/albums/' . $f); });
}
?>