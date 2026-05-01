<?php
/**
 * Makes the logo PNG background transparent so it blends with the header.
 * Replaces pixels matching the logo's dark-navy background (#161f30) with transparent.
 * The OP badge mark itself is untouched.
 *
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */

$attachment_id = 112; // operatorprep-logo-banner.png
$file_path     = get_attached_file($attachment_id);

if (!file_exists($file_path)) {
    echo "ERROR: File not found at $file_path" . PHP_EOL;
    exit(1);
}

// Back up original
$backup = $file_path . '.bak';
if (!file_exists($backup)) {
    copy($file_path, $backup);
    echo "Backup created: $backup" . PHP_EOL;
} else {
    echo "Backup already exists: $backup (skipping)" . PHP_EOL;
}

// Load image
$img = imagecreatefrompng($file_path);
if (!$img) {
    echo "ERROR: Could not load PNG" . PHP_EOL;
    exit(1);
}
imagealphablending($img, false);
imagesavealpha($img, true);

$w = imagesx($img);
$h = imagesy($img);
echo "Image size: {$w}x{$h}" . PHP_EOL;

// Target background color from the logo: rgb(22, 31, 48) = #161f30
$target_r = 22; $target_g = 31; $target_b = 48;
$tolerance = 18; // allow slight variation for anti-aliasing

$changed = 0;
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $color = imagecolorat($img, $x, $y);
        $a = ($color >> 24) & 0x7F;
        $r = ($color >> 16) & 0xFF;
        $g = ($color >>  8) & 0xFF;
        $b = ($color      ) & 0xFF;

        $dist = sqrt(
            pow($r - $target_r, 2) +
            pow($g - $target_g, 2) +
            pow($b - $target_b, 2)
        );

        if ($dist <= $tolerance) {
            // Scale alpha based on proximity (soft edge for anti-aliased pixels)
            $new_alpha = (int)(($dist / $tolerance) * 127);
            $transparent = imagecolorallocatealpha($img, $r, $g, $b, $new_alpha);
            imagesetpixel($img, $x, $y, $transparent);
            $changed++;
        }
    }
}

echo "Pixels made transparent: $changed" . PHP_EOL;

// Save in-place
imagepng($img, $file_path, 9);
imagedestroy($img);

echo "Saved to: $file_path" . PHP_EOL;

// Clear WordPress image cache
wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $file_path));
wp_cache_flush();
echo "DONE — logo background is now transparent. Backup at: $backup" . PHP_EOL;
