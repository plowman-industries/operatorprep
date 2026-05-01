<?php
/**
 * Verifies logo transparency and force-busts cache by renaming the file
 * and updating the WordPress attachment record to point to the new filename.
 *
 * Run via: wp eval-file scripts/fix_logo_bg.php --allow-root
 */
global $wpdb;

$attachment_id = 112;
$upload_dir    = wp_upload_dir();
$base_dir      = $upload_dir['basedir'];
$rel_path      = '2026/03/operatorprep-logo-banner.png';
$orig_path     = $base_dir . '/' . $rel_path;
$bak_path      = $orig_path . '.bak';

// ── 1. Verify current file transparency ────────────────────────────────────
if (!file_exists($orig_path)) { echo "ERROR: file not found"; exit(1); }
$img = imagecreatefrompng($orig_path);
imagealphablending($img, false);
imagesavealpha($img, true);
$px = imagecolorat($img, 0, 0);
$a  = ($px >> 24) & 0x7F;
$r  = ($px >> 16) & 0xFF;
$g  = ($px >>  8) & 0xFF;
$b  = ($px      ) & 0xFF;
imagedestroy($img);
echo "Top-left pixel: r=$r g=$g b=$b alpha=$a (127=transparent)" . PHP_EOL;

if ($a < 100) {
    echo "Transparency NOT saved correctly — re-applying from backup..." . PHP_EOL;
    if (!file_exists($bak_path)) { echo "ERROR: no backup found"; exit(1); }

    // Re-process from backup
    $img = imagecreatefrompng($bak_path);
    imagealphablending($img, false);
    imagesavealpha($img, true);
    $w = imagesx($img); $h = imagesy($img);
    $target_r=22; $target_g=31; $target_b=48; $tol=18;
    $changed=0;
    for ($y=0;$y<$h;$y++) {
        for ($x=0;$x<$w;$x++) {
            $c=$img=imagecreatefrompng($bak_path); break 2;
        }
    }
    imagedestroy($img);
    // Simpler: just use imagecolortransparent approach
    $img2 = imagecreatefrompng($bak_path);
    imagealphablending($img2, false);
    imagesavealpha($img2, true);
    $w=imagesx($img2); $h=imagesy($img2);
    $changed=0;
    for ($y=0;$y<$h;$y++) {
        for ($x=0;$x<$w;$x++) {
            $c=imagecolorat($img2,$x,$y);
            $r2=($c>>16)&0xFF; $g2=($c>>8)&0xFF; $b2=$c&0xFF;
            $dist=sqrt(pow($r2-22,2)+pow($g2-31,2)+pow($b2-48,2));
            if ($dist<=18) {
                $new_alpha=(int)(($dist/18)*127);
                imagesetpixel($img2,$x,$y,imagecolorallocatealpha($img2,$r2,$g2,$b2,$new_alpha));
                $changed++;
            }
        }
    }
    imagepng($img2, $orig_path, 9);
    imagedestroy($img2);
    echo "Re-processed: $changed pixels made transparent" . PHP_EOL;
}

// ── 2. Save a cache-busted copy with a new filename ─────────────────────────
$new_rel  = '2026/03/operatorprep-logo-banner-v2.png';
$new_path = $base_dir . '/' . $new_rel;
copy($orig_path, $new_path);
echo "Cache-busted copy saved: $new_path" . PHP_EOL;

// ── 3. Update WordPress attachment to point to new file ─────────────────────
$new_url  = $upload_dir['baseurl'] . '/' . $new_rel;
$old_guid = get_the_guid($attachment_id);

$wpdb->update(
    $wpdb->posts,
    ['guid' => $new_url],
    ['ID'   => $attachment_id]
);
update_post_meta($attachment_id, '_wp_attached_file', $new_rel);

// Update metadata sizes to point to new base name
$meta = wp_get_attachment_metadata($attachment_id);
if ($meta) {
    $meta['file'] = $new_rel;
    wp_update_attachment_metadata($attachment_id, $meta);
}

echo "Attachment URL updated to: $new_url" . PHP_EOL;

// ── 4. Purge all caches ───────────────────────────────────────────────────────
wp_cache_flush();
if (function_exists('sg_cachepress_purge_cache')) { sg_cachepress_purge_cache(); echo "SG cache purged" . PHP_EOL; }
do_action('sg_cachepress_purge_cache');
do_action('litespeed_purge_all');

echo "DONE" . PHP_EOL;
echo "New logo URL: $new_url" . PHP_EOL;
