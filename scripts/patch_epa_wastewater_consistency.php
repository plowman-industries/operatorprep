<?php
/**
 * Patches the EPA Wastewater Resources page (epa-wastewater-resources) to match
 * the visual style of the two existing EPA pages:
 *
 *   1. Remove "// " prefix from eyebrow text (existing pages don't use it)
 *   2. Remove "// " prefix from section title labels (existing pages don't use it)
 *   3. Fix back link href from /wastewater-study-guides/ → javascript:history.back()
 *      (both existing pages use history.back())
 */
echo "Patching EPA wastewater page for consistency...\n";

$page = get_page_by_path( 'epa-wastewater-resources' );
if ( ! $page ) {
    echo "ERROR: page 'epa-wastewater-resources' not found\n";
    exit;
}

$c = $page->post_content;
$original = $c;

$patches = [
    // 1. Eyebrow — remove "// " prefix
    '// Wastewater Operator Study Resources'
        => 'Wastewater Operator Study Resources',

    // 2. Section titles — remove "// " prefix from each
    '// Compliance &amp; Permitting'
        => 'Compliance &amp; Permitting',
    '// Process Control &amp; Technical References'
        => 'Process Control &amp; Technical References',
    '// Tools &amp; Formulas'
        => 'Tools &amp; Formulas',
    '// Video Training'
        => 'Video Training',

    // 3. Back link href
    'href="/wastewater-study-guides/"'
        => 'href="javascript:history.back()"',
];

foreach ( $patches as $old => $new ) {
    if ( strpos( $c, $new ) !== false ) {
        echo "  Already patched — skipping: " . substr( $new, 0, 60 ) . "\n";
        continue;
    }
    if ( strpos( $c, $old ) === false ) {
        echo "  WARN: target not found: " . substr( $old, 0, 60 ) . "\n";
        continue;
    }
    $c = str_replace( $old, $new, $c );
    echo "  OK: " . substr( $old, 0, 50 ) . " → " . substr( $new, 0, 50 ) . "\n";
}

if ( $c === $original ) {
    echo "No changes needed — page already consistent.\n";
    exit;
}

$result = wp_update_post( [ 'ID' => $page->ID, 'post_content' => $c ] );
if ( is_wp_error( $result ) ) {
    echo "ERROR saving: " . $result->get_error_message() . "\n";
    exit;
}

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}

echo "DONE — wastewater EPA page patched for consistency.\n";
