<?php
/**
 * CG Painting – reviewformulier (onder FAQ)
 * Slaat de review op in data/reviews.json en stuurt een e-mail.
 */

require_once __DIR__ . '/includes/reviews-lib.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

// Honeypot (spam)
if (!empty(trim($_POST['bedrijf'] ?? ''))) {
    echo json_encode(['ok' => true, 'message' => 'Bedankt!']);
    exit;
}

$naam         = isset($_POST['naam']) ? trim(strip_tags($_POST['naam'])) : '';
$email        = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
$plaats       = isset($_POST['plaats']) ? trim(strip_tags($_POST['plaats'])) : '';
$sterren      = isset($_POST['sterren']) ? (int) $_POST['sterren'] : 0;
$review_tekst = isset($_POST['review_tekst']) ? trim($_POST['review_tekst']) : '';
$review_tekst = strip_tags($review_tekst);

if ($naam === '' || $review_tekst === '' || $sterren < 1 || $sterren > 5) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Vul naam, score en uw review in.']);
    exit;
}

$review_row = [
    'id' => bin2hex(random_bytes(8)),
    'created_at' => date('c'),
    'naam' => $naam,
    'plaats' => $plaats,
    'email' => $email,
    'sterren' => $sterren,
    'tekst' => $review_tekst,
    'published' => true,
];

if (!reviews_append($review_row)) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Review kon niet worden opgeslagen. Probeer later opnieuw.']);
    exit;
}

$to_email = 'hello@cgpainting.be';
$subject  = '[Review website] ' . $naam;

$sterren_vis = str_repeat('★', $sterren) . str_repeat('☆', 5 - $sterren);

$html = '<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><title>Review</title></head>
<body style="margin:0;padding:24px;font-family:\'Segoe UI\',Arial,sans-serif;background:#f1f5f9;color:#1f2937;">
<div style="max-width:560px;margin:0 auto;background:#fff;padding:28px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
<h1 style="font-size:20px;margin:0 0 16px;color:#0f172a;">Nieuwe review via cgpainting.be</h1>
<table style="font-size:14px;width:100%;border-collapse:collapse;">
<tr><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;"><strong>Naam</strong></td><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;">' . htmlspecialchars($naam, ENT_QUOTES, 'UTF-8') . '</td></tr>
<tr><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;"><strong>Plaats</strong></td><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;">' . htmlspecialchars($plaats !== '' ? $plaats : '—', ENT_QUOTES, 'UTF-8') . '</td></tr>
<tr><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;"><strong>E-mail</strong></td><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;">' . htmlspecialchars($email !== '' ? $email : '—', ENT_QUOTES, 'UTF-8') . '</td></tr>
<tr><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;"><strong>Score</strong></td><td style="padding:8px 0;border-bottom:1px solid #f3f4f6;">' . $sterren . '/5 <span style="color:#ca8a04;">' . htmlspecialchars($sterren_vis, ENT_QUOTES, 'UTF-8') . '</span></td></tr>
</table>
<p style="margin:20px 0 8px;font-size:14px;font-weight:700;">Review</p>
<div style="font-size:14px;line-height:1.55;white-space:pre-wrap;">' . nl2br(htmlspecialchars($review_tekst, ENT_QUOTES, 'UTF-8')) . '</div>
<p style="margin-top:24px;font-size:12px;color:#6b7280;">Verzonden via het reviewformulier op de website.</p>
</div>
</body>
</html>';

$host = isset($_SERVER['HTTP_HOST']) ? preg_replace('/^www\./', '', $_SERVER['HTTP_HOST']) : 'cgpainting.be';
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: CG Painting <no-reply@' . $host . '>',
    'X-Mailer: PHP/' . phpversion(),
];
if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $headers[] = 'Reply-To: ' . $email;
}

$sent = @mail($to_email, $subject, $html, implode("\r\n", $headers));

if ($sent) {
    echo json_encode(['ok' => true, 'message' => 'Bedankt! Uw review is ontvangen.']);
} else {
    // Review staat al in reviews.json; e-mail kan op sommige hosts mislukken
    echo json_encode([
        'ok' => true,
        'message' => 'Bedankt! Uw review is opgeslagen. (E-mailmelding kon niet worden verstuurd.)',
        'mail_ok' => false,
    ]);
}
