<?php
/**
 * Fix mode button visibility on all three simulators.
 *
 * After the light-theme conversion the inactive buttons ended up with
 * near-invisible text (#94a3b8 / #64748b) on white backgrounds.
 * This patch gives inactive buttons a clear outlined look so Quiz Mode
 * and Drag & Drop are obviously clickable.
 *
 * Changes per simulator
 * ─────────────────────
 *  Inactive  : white bg, #334155 text, 2px solid #64748b border
 *  Hover     : #eff6ff bg, #1d4ed8 text, #3b82f6 border  (blue hint)
 *  Active    : unchanged (already blue + white text)
 */

echo "Fixing mode button visibility on all simulators...\n";

// ── shared new styles ──────────────────────────────────────────────────────
// We only replace the background / color / border portion of the rule so
// we don't have to reproduce every other property.

$inactive_new = 'background:#fff;color:#334155;border:2px solid #64748b;';
$hover_new    = '{background:#eff6ff;color:#1d4ed8;border-color:#3b82f6}';

// ───────────────────────────────────────────────────────────────────────────
//  Helper
// ───────────────────────────────────────────────────────────────────────────
function patch_mode_btns( $pid, $label, array $patches ) {
    $page = get_post( $pid );
    if ( ! $page ) { echo "  ERROR: page $pid not found\n"; return; }
    $c = $page->post_content;

    foreach ( $patches as $old => $new ) {
        if ( strpos( $c, $new ) !== false ) {
            echo "  $label – already patched (skipping): " . substr( $new, 0, 60 ) . "…\n";
            continue;
        }
        if ( strpos( $c, $old ) === false ) {
            echo "  WARN $label – target not found: " . substr( $old, 0, 60 ) . "…\n";
            continue;
        }
        $c = str_replace( $old, $new, $c );
        echo "  OK $label – patched: " . substr( $new, 0, 60 ) . "…\n";
    }

    $result = wp_update_post( [ 'ID' => $pid, 'post_content' => $c ] );
    if ( is_wp_error( $result ) ) {
        echo "  ERROR saving page $pid: " . $result->get_error_message() . "\n";
    } else {
        echo "  Saved page $pid ($label)\n";
    }
}

// ═══════════════════════════════════════════════════════════════════════════
//  WW1/WW2  –  page 1093
//  After light-theme conversion the inactive rule became:
//    background:#f8fafc;color:#94a3b8;border:1.5px solid #e2e8f0;
//  Hover became:
//    .ww-mode-btn:hover{background:#f1f5f9;color:#1e293b;border-color:#475569}
// ═══════════════════════════════════════════════════════════════════════════
patch_mode_btns( 1093, 'WW', [
    // inactive
    'background:#f8fafc;color:#94a3b8;border:1.5px solid #e2e8f0;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}'
        =>
    'background:#fff;color:#334155;border:2px solid #64748b;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}',

    // hover
    '.ww-mode-btn:hover{background:#f1f5f9;color:#1e293b;border-color:#475569}'
        =>
    '.ww-mode-btn:hover{background:#eff6ff;color:#1d4ed8;border-color:#3b82f6}',
] );

// ═══════════════════════════════════════════════════════════════════════════
//  T1/T2  –  page 1004
//  After light-theme conversion the inactive rule became:
//    background:#f0f9ff;color:#94a3b8;border:1.5px solid #bfdbfe;
//  Hover became:
//    .tp-mode-btn:hover{background:#eff6ff;color:#1e293b;border-color:#2563eb}
// ═══════════════════════════════════════════════════════════════════════════
patch_mode_btns( 1004, 'T1/T2', [
    // inactive
    'background:#f0f9ff;color:#94a3b8;border:1.5px solid #bfdbfe;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}'
        =>
    'background:#fff;color:#334155;border:2px solid #64748b;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}',

    // hover
    '.tp-mode-btn:hover{background:#eff6ff;color:#1e293b;border-color:#2563eb}'
        =>
    '.tp-mode-btn:hover{background:#eff6ff;color:#1d4ed8;border-color:#3b82f6}',
] );

// ═══════════════════════════════════════════════════════════════════════════
//  D1/D2  –  page 1094
//  Was already light; inactive rule:
//    background:#f8fafc;color:#64748b;border:1.5px solid #e2e8f0;
//  Hover:
//    .dd-mode-btn:hover{background:#f1f5f9;color:#1e293b;border-color:#cbd5e1}
// ═══════════════════════════════════════════════════════════════════════════
patch_mode_btns( 1094, 'D1/D2', [
    // inactive
    'background:#f8fafc;color:#64748b;border:1.5px solid #e2e8f0;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}'
        =>
    'background:#fff;color:#334155;border:2px solid #64748b;padding:8px 20px;border-radius:8px;cursor:pointer;font-size:.9em;font-weight:600;transition:all .18s;width:100%}',

    // hover
    '.dd-mode-btn:hover{background:#f1f5f9;color:#1e293b;border-color:#cbd5e1}'
        =>
    '.dd-mode-btn:hover{background:#eff6ff;color:#1d4ed8;border-color:#3b82f6}',
] );

wp_cache_flush();
if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
    sg_cachepress_purge_cache();
}

echo "DONE — mode button visibility fixed on all three simulators.\n";
