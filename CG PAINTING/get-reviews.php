<?php
/**
 * CG Painting – publieke lijst reviews + statistieken (JSON voor de website).
 */
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=60');

require_once __DIR__ . '/includes/reviews-lib.php';

$all = reviews_read_all();
$pub = reviews_public_list($all);
$sorted = reviews_sort_display($pub);
$stats = reviews_stats($pub);

$out = [];
foreach ($sorted as $r) {
    $out[] = reviews_to_public_item($r);
}

echo json_encode([
    'ok' => true,
    'reviews' => $out,
    'stats' => $stats,
], JSON_UNESCAPED_UNICODE);
