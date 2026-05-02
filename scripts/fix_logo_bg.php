<?php
/**
 * Makes logo background transparent — corrected alpha formula.
 * GD: alpha=0 = opaque, alpha=127 = transparent.
 * Background pixels (dist=0) → alpha=127 (fully transparent)
 * Edge pixels (dist=tolerance) → alpha=0 (fully opaque)
 *
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
global $wpdb;

$attachment_id = 112;
$upload_dir    = wp_upload_dir();
$base_dir      = $upload_dir['basedir'];
$bak_path      = $base_dir . '/2026/03/operatorprep-logo-banner.png.bak';
$out_rel       = '2026/03/operatorprep-logo-banner-v3.png';
$out_path      = $base_dir . '/' . $out_rel;

if (!file_exists($bak_path)) {
    echo "ERROR: backup not found at $bak_path" . PHP_EOL;
    exit(1);
}

// Load from original backup (untouched)
$img = imagecreatefrompng($bak_path);
imagealphablending($img, false);
imagesavealpha($img, true);

$w = imagesx($img);
$h = imagesy($img);
echo "Image: {$w}x{$h}" . PHP_EOL;

// Background color baked into the PNG: rgb(22, 31, 48) = #161f30
$tr = 22; $tg = 31; $tb = 48;
$tol = 20; // tolerance for anti-aliased edge pixels

$changed = 0;
for ($y = 0; $y < $h; $y++) {
    for ($x = 0; $x < $w; $x++) {
        $c  = imagecolorat($img, $x, $y);
        $r  = ($c >> 16) & 0xFF;
        $g  = ($c >>  8) & 0xFF;
        $b  = $c & 0xFF;

        $dist = sqrt(pow($r-$tr,2) + pow($g-$tg,2) + pow($b-$tb,2));

        if ($dist <= $tol) {
            // CORRECTED: dist=0 (background) → alpha=127 (transparent)
            //            dist=tol (edge)     → alpha=0   (opaque)
            $alpha = (int)((1 - $dist/$tol) * 127);
            imagesetpixel($img, $x, $y,
                imagecolorallocatealpha($img, $r, $g, $b, $alpha));
            $changed++;
        }
    }
}

echo "Pixels processed: $changed" . PHP_EOL;

// Verify top-left pixel
$px = imagecolorat($img, 0, 0);
$check_alpha = ($px >> 24) & 0x7F;
echo "Top-left alpha after processing: $check_alpha (should be 127)" . PHP_EOL;

// Save to new filename (cache-bust)
imagepng($img, $out_path, 9);
imagedestroy($img);
echo "Saved: $out_path" . PHP_EOL;

// Update WP attachment to use v3
$new_url = $upload_dir['baseurl'] . '/' . $out_rel;
$wpdb->update($wpdb->posts, ['guid' => $new_url], ['ID' => $attachment_id]);
update_post_meta($attachment_id, '_wp_attached_file', $out_rel);
$meta = wp_get_attachment_metadata($attachment_id);
if ($meta) { $meta['file'] = $out_rel; wp_update_attachment_metadata($attachment_id, $meta); }

// Purge caches
wp_cache_flush();
do_action('sg_cachepress_purge_cache');
if (function_exists('sg_cachepress_purge_cache')) sg_cachepress_purge_cache();

echo "WP attachment updated → $new_url" . PHP_EOL;
echo "DONE" . PHP_EOL;
