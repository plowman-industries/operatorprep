<?php
/**
 * Convert WW1/WW2 (page 1093) and T1/T2 (page 1004) simulators
 * to light theme, matching the D1/D2 (#fff background) aesthetic.
 *
 * Strategy:
 *   1. Extract the <style> block inside each simulator div.
 *   2. Apply a hex-color map via preg_replace_callback (single-pass, no cascade).
 *   3. Apply targeted str_replace fixes for accent colors on newly-light backgrounds.
 *   4. Apply targeted str_replace on SVG attribute colors (background panels, legends).
 *
 * Safe to re-run: guarded by checking for background:#fff in content.
 */
echo "Converting simulators to light theme...\n";

/**
 * @param int    $pid            WordPress page ID
 * @param string $div_id         The id="..." value of the root simulator div
 * @param array  $css_map        [lowercased-hex => new-hex]  applied to the <style> block only
 * @param array  $css_targeted   [old-string => new-string]   applied to the <style> block after css_map
 * @param array  $svg_attrs      [old-string => new-string]   applied to full content (SVG attribute patches)
 */
function apply_light_theme( $pid, $div_id, $css_map, $css_targeted, $svg_attrs ) {
    $page = get_post( $pid );
    if ( ! $page ) { echo "ERROR: page $pid not found\n"; return; }
    $c = $page->post_content;

    // Guard: already light?
    if ( strpos( $c, 'background:#fff;color:#1e293b' ) !== false ) {
        echo "Page $pid ($div_id): already light — skipping\n";
        return;
    }

    // Step 1 + 2: process the <style> block inside the simulator div
    $pattern = '/(<div id="' . preg_quote( $div_id, '/' ) . '">\s*<style>)(.*?)(<\/style>)/s';
    $c = preg_replace_callback(
        $pattern,
        function ( $m ) use ( $css_map, $css_targeted ) {
            $style = $m[2];

            // Single-pass hex replacement
            $style = preg_replace_callback(
                '/#([0-9a-fA-F]{6})\b/i',
                function ( $h ) use ( $css_map ) {
                    $key = '#' . strtolower( $h[1] );
                    return isset( $css_map[ $key ] ) ? $css_map[ $key ] : $h[0];
                },
                $style
            );

            // Targeted str_replace fixes after global map
            foreach ( $css_targeted as $old => $new ) {
                $style = str_replace( $old, $new, $style );
            }

            return $m[1] . $style . $m[3];
        },
        $c
    );

    // Step 3: SVG attribute patches (background panels, legend rects)
    foreach ( $svg_attrs as $old => $new ) {
        $c = str_replace( $old, $new, $c );
    }

    $result = wp_update_post( [ 'ID' => $pid, 'post_content' => $c ] );
    if ( is_wp_error( $result ) ) {
        echo "ERROR page $pid: " . $result->get_error_message() . "\n";
        return;
    }
    echo "OK: page $pid ($div_id) converted to light theme\n";
}


// ═══════════════════════════════════════════════════════════════════════════════
//  WW1/WW2  –  page 1093
// ═══════════════════════════════════════════════════════════════════════════════
apply_light_theme(
    1093,
    'opp-ww-diagram',

    // ── CSS hex map (applied to <style> block only) ──
    [
        '#0f172a' => '#ffffff',   // main bg, chip bg, close btn bg
        '#1e293b' => '#f8fafc',   // panel/card bg, label-bg fill
        '#334155' => '#e2e8f0',   // borders, label-bg stroke
        '#293548' => '#f1f5f9',   // hover bg
        '#1a2535' => '#f1f5f9',   // chip hover bg
        '#1a2e1a' => '#f0fdf4',   // completion section bg
        '#e2e8f0' => '#1e293b',   // main text color & SVG label fill
        '#f1f5f9' => '#0f172a',   // h1 heading color
        '#cbd5e1' => '#374151',   // chip text
        '#1a1400' => '#fffbeb',   // amber exam-tip callout bg
    ],

    // ── Targeted fixes: accent colors that land on newly-light backgrounds ──
    [
        // Completion screen (bg is now #f0fdf4, bright green text is illegible)
        '.ww-complete h3{color:#4ade80;'             => '.ww-complete h3{color:#16a34a;',
        'font-weight:800;color:#4ade80;margin:6px 0}'=> 'font-weight:800;color:#16a34a;margin:6px 0}',
        '.ww-complete p{color:#86efac;'              => '.ww-complete p{color:#166534;',
        // Drag score "Correct" counter (drag banner is now #f8fafc)
        '.ww-sc-correct{color:#4ade80;'              => '.ww-sc-correct{color:#16a34a;',
        // Chip done: light-green text/border → dark green
        'color:#4ade80;border-color:#4ade80;'        => 'color:#16a34a;border-color:#16a34a;',
    ],

    // ── SVG attribute patches (background panels & legend only) ──
    [
        // SOLIDS HANDLING background panel
        'fill="#0a0f1a" stroke="#334155" stroke-width="1" opacity="0.5"' =>
        'fill="#f1f5f9" stroke="#e2e8f0" stroke-width="1" opacity="1"',
        // LEGEND rect
        'fill="#1e293b" stroke="#334155" opacity="0.8"' =>
        'fill="#f1f5f9" stroke="#e2e8f0" opacity="1"',
        // SOLIDS HANDLING label text
        'fill="#475569" font-size="11" font-weight="600" font-family="sans-serif">SOLIDS HANDLING' =>
        'fill="#374151" font-size="11" font-weight="600" font-family="sans-serif">SOLIDS HANDLING',
    ]
);


// ═══════════════════════════════════════════════════════════════════════════════
//  T1/T2  –  page 1004
// ═══════════════════════════════════════════════════════════════════════════════
apply_light_theme(
    1004,
    'opp-tp-diagram',

    // ── CSS hex map ──
    [
        '#0b1320' => '#ffffff',   // main bg, chip bg, close btn bg
        '#132035' => '#f0f9ff',   // panel/card bg, label-bg fill
        '#1e3a5f' => '#bfdbfe',   // borders, label-bg stroke
        '#1a2e47' => '#eff6ff',   // hover bg
        '#0c2340' => '#eff6ff',   // completion bg, chip-done bg
        '#e2e8f0' => '#1e293b',   // main text & SVG label fill
        '#f1f5f9' => '#0b1320',   // h1 heading color
        '#cbd5e1' => '#374151',   // chip text
        '#0c1a00' => '#fffbeb',   // amber exam-tip callout bg
    ],

    // ── Targeted fixes ──
    [
        // Completion screen (bg now #eff6ff — bright sky-blue text illegible)
        '.tp-complete h3{color:#38bdf8;'              => '.tp-complete h3{color:#0369a1;',
        'font-weight:800;color:#38bdf8;margin:6px 0}' => 'font-weight:800;color:#0369a1;margin:6px 0}',
        '.tp-complete p{color:#7dd3fc;'               => '.tp-complete p{color:#0369a1;',
        // Drag score "Correct" counter (drag banner now #f0f9ff)
        '.tp-sc-correct{color:#7dd3fc;'               => '.tp-sc-correct{color:#0284c7;',
        // Chip done
        'color:#38bdf8;border-color:#38bdf8;'         => 'color:#0369a1;border-color:#0369a1;',
        // Info panel h2 (panel now #f0f9ff)
        '.tp-info h2{color:#38bdf8;'                  => '.tp-info h2{color:#0369a1;',
        // Drag banner "selected" label (banner now #f0f9ff)
        '.tp-db-sel{font-weight:700;color:#38bdf8;'   => '.tp-db-sel{font-weight:700;color:#0369a1;',
    ],

    // ── SVG attribute patches ──
    [
        // LEGEND rect
        'fill="#132035" stroke="#1e3a5f" opacity="0.9"' =>
        'fill="#f0f9ff" stroke="#bfdbfe" opacity="1"',
        // Legend heading text
        'fill="#94a3b8" font-size="9" font-weight="600" font-family="sans-serif">LEGEND' =>
        'fill="#374151" font-size="9" font-weight="600" font-family="sans-serif">LEGEND',
        // Legend detail text (Treatment Flow, chemical labels)
        'fill="#94a3b8" font-size="8" font-family="sans-serif">Treatment Flow' =>
        'fill="#475569" font-size="8" font-family="sans-serif">Treatment Flow',
    ]
);

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}
echo "DONE — both simulators converted to light theme.\n";
