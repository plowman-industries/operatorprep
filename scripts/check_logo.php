<?php
/**
 * Finds the logo file and checks Astra header color settings.
 */
// Logo URL from WordPress
$logo_id  = get_theme_mod('custom_logo');
$logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '(not set)';
echo "Logo attachment ID: $logo_id" . PHP_EOL;
echo "Logo URL: $logo_url" . PHP_EOL;

// Check if it's an SVG or PNG
$ext = pathinfo(parse_url($logo_url, PHP_URL_PATH), PATHINFO_EXTENSION);
echo "File extension: $ext" . PHP_EOL;

// If PNG/JPG, get local path and check if it has transparency
if ($logo_id) {
    $local_path = get_attached_file($logo_id);
    echo "Local path: $local_path" . PHP_EOL;
    if (file_exists($local_path)) {
        echo "File exists: YES (" . filesize($local_path) . " bytes)" . PHP_EOL;
        if ($ext === 'png') {
            // Check PNG transparency via imagecolorat on first pixel
            $img = imagecreatefrompng($local_path);
            imagealphablending($img, false);
            imagesavealpha($img, true);
            // Sample top-left corner (should be transparent if logo has transparent bg)
            $color = imagecolorat($img, 0, 0);
            $alpha = ($color >> 24) & 0x7F; // 0=opaque, 127=transparent
            echo "Top-left pixel alpha: $alpha (0=opaque, 127=transparent)" . PHP_EOL;
            $rgb = imagecolorsforindex($img, $color);
            echo "Top-left pixel RGB: r={$rgb['red']} g={$rgb['green']} b={$rgb['blue']} a={$rgb['alpha']}" . PHP_EOL;
            imagedestroy($img);
        }
    }
}

// Astra header background from customizer
$astra_settings = get_option('astra-settings', []);
$header_bg = $astra_settings['header-bg-color'] ?? $astra_settings['hb-bg-color'] ?? '(not in astra-settings)';
echo PHP_EOL . "Astra header BG color setting: $header_bg" . PHP_EOL;

// Also check what the primary-header bar actually gets
echo "CSS var --c-ink resolves to: #0b1220 (from style.css :root)" . PHP_EOL;

// What color is the site header background via astra settings?
echo PHP_EOL . "=== Relevant Astra color settings ===" . PHP_EOL;
$keys = ['header-bg-color', 'hb-bg-color', 'header-main-bg-color', 'site-layout-outside-bg-obj'];
foreach ($keys as $k) {
    if (isset($astra_settings[$k])) {
        echo "  $k: " . (is_array($astra_settings[$k]) ? json_encode($astra_settings[$k]) : $astra_settings[$k]) . PHP_EOL;
    }
}

echo PHP_EOL . "DONE" . PHP_EOL;
