<?php
/**
 * CG Painting – opslag en sortering van klantenreviews (JSON).
 * Bestand: data/reviews.json — handmatig bewerken om reviews te verbergen of te verwijderen.
 */
declare(strict_types=1);

function reviews_data_path(): string
{
    return dirname(__DIR__) . '/data/reviews.json';
}

/**
 * @return list<array<string, mixed>>
 */
function reviews_read_all(): array
{
    $path = reviews_data_path();
    if (!is_readable($path)) {
        return [];
    }
    $raw = file_get_contents($path);
    if ($raw === false || $raw === '') {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/**
 * @param list<array<string, mixed>> $list
 */
function reviews_save_all(array $list): bool
{
    $path = reviews_data_path();
    $dir = dirname($path);
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
            return false;
        }
    }
    $tmp = $path . '.tmp';
    $json = json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }
    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $path);
}

/**
 * @param array<string, mixed> $review
 */
function reviews_append(array $review): bool
{
    $list = reviews_read_all();
    $list[] = $review;
    return reviews_save_all($list);
}

/**
 * @param list<array<string, mixed>> $all
 * @return list<array<string, mixed>>
 */
function reviews_public_list(array $all): array
{
    $out = [];
    foreach ($all as $r) {
        if (!is_array($r)) {
            continue;
        }
        $pub = $r['published'] ?? true;
        if ($pub === true || $pub === 1 || $pub === '1') {
            $out[] = $r;
        }
    }
    return $out;
}

/**
 * 4–5 sterren eerst (willekeurige volgorde binnen die groep), daarna 1–3 (willekeurig).
 *
 * @param list<array<string, mixed>> $reviews
 * @return list<array<string, mixed>>
 */
function reviews_sort_display(array $reviews): array
{
    $high = [];
    $low = [];
    foreach ($reviews as $r) {
        $s = (int) ($r['sterren'] ?? 0);
        if ($s >= 4) {
            $high[] = $r;
        } else {
            $low[] = $r;
        }
    }
    shuffle($high);
    shuffle($low);
    return array_merge($high, $low);
}

/**
 * @param list<array<string, mixed>> $reviews
 * @return array{count:int, average:float|null, has_reviews:bool}
 */
function reviews_stats(array $reviews): array
{
    $n = count($reviews);
    if ($n === 0) {
        return [
            'count' => 0,
            'average' => null,
            'has_reviews' => false,
        ];
    }
    $sum = 0;
    foreach ($reviews as $r) {
        $sum += (int) ($r['sterren'] ?? 0);
    }
    return [
        'count' => $n,
        'average' => round($sum / $n, 1),
        'has_reviews' => true,
    ];
}

/**
 * Veilig object voor de website (geen e-mail).
 *
 * @param array<string, mixed> $r
 * @return array<string, mixed>
 */
function reviews_to_public_item(array $r): array
{
    return [
        'id' => (string) ($r['id'] ?? ''),
        'created_at' => (string) ($r['created_at'] ?? ''),
        'naam' => (string) ($r['naam'] ?? ''),
        'plaats' => (string) ($r['plaats'] ?? ''),
        'sterren' => (int) ($r['sterren'] ?? 0),
        'tekst' => (string) ($r['tekst'] ?? ''),
    ];
}
