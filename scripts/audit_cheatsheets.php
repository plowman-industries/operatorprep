<?php
/**
 * Finds cheat sheet snippets and shows their background styling.
 * Run via: wp eval-file scripts/audit_cheatsheets.php --allow-root
 */
global $wpdb;

// Find snippets mentioning "cheat"
$rows = $wpdb->get_results("SELECT id, name, active, LENGTH(code) as bytes FROM ugk_snippets WHERE name LIKE '%cheat%' OR code LIKE '%cheat%' ORDER BY id");

echo "=== Snippets with 'cheat' ===" . PHP_EOL;
foreach ($rows as $r) {
    echo "ID {$r->id} | active:{$r->active} | {$r->bytes}b | {$r->name}" . PHP_EOL;
}

// For each, show any background color declarations
echo PHP_EOL . "=== Background styles in cheat sheet snippets ===" . PHP_EOL;
foreach ($rows as $r) {
    $full = $wpdb->get_var("SELECT code FROM ugk_snippets WHERE id={$r->id}");
    // Extract background-related CSS lines
    preg_match_all('/[^\n]*background[^\n]*/i', $full, $m);
    if ($m[0]) {
        echo PHP_EOL . "--- Snippet {$r->id} ({$r->name}) ---" . PHP_EOL;
        foreach (array_unique($m[0]) as $line) {
            echo "  " . trim($line) . PHP_EOL;
        }
    }
}

echo PHP_EOL . "DONE" . PHP_EOL;
