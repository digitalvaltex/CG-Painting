<?php
/**
 * CG Painting – form submission handler
 * Sends email to hello@cgpainting.be with HTML template and file attachments.
 */

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$naam     = isset($_POST['naam']) ? trim(strip_tags($_POST['naam'])) : '';
$telefoon = isset($_POST['telefoon']) ? trim(strip_tags($_POST['telefoon'])) : '';
$email    = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
$postcode = isset($_POST['postcode']) ? trim(strip_tags($_POST['postcode'])) : '';
$stad     = isset($_POST['stad']) ? trim(strip_tags($_POST['stad'])) : '';
$type_werk = isset($_POST['type_werk']) ? trim(strip_tags($_POST['type_werk'])) : '';
$bericht  = isset($_POST['bericht']) ? trim(strip_tags($_POST['bericht'])) : '';

$type_labels = [
    'schilderwerken' => 'Schilderwerken',
    'dunpleisteren'  => 'Dunpleisteren',
    'behangen'       => 'Behangen',
    'decor'          => 'Decor',
    'renovatie'      => 'Renovatie werken',
];
$type_werk_label = isset($type_labels[$type_werk]) ? $type_labels[$type_werk] : $type_werk;

$to_email = 'hello@cgpainting.be';
$subject  = 'Nieuw bericht van ' . $naam;

$reply_subject = 'Re: ' . rawurlencode($subject);
$reply_link = $email ? ('mailto:' . $email . '?subject=' . $reply_subject) : '#';

$telefoon_tel = preg_replace('/[^0-9+]/', '', $telefoon);
if ($telefoon_tel && $telefoon_tel[0] !== '+') {
    if (substr($telefoon_tel, 0, 1) === '0') {
        $telefoon_tel = '+32' . substr($telefoon_tel, 1);
    } else {
        $telefoon_tel = '+32' . $telefoon_tel;
    }
}
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? 'cgpainting.be');
$script_dir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$call_link = $telefoon_tel ? ($base_url . ($script_dir === '/' ? '' : $script_dir) . '/call.php?n=' . rawurlencode($telefoon_tel)) : '#';

$calendar_title = 'Bel ' . $naam . ' - CG Painting';
$calendar_details = "Klant: $naam\nTelefoon: $telefoon\nE-mail: $email\nType werk: $type_werk_label\n\nBericht:\n$bericht";
$calendar_start = '';
$calendar_end = '';
try {
    $tz = new DateTimeZone('Europe/Brussels');
    $start = new DateTime('tomorrow 10:00', $tz);
    $start->setTimezone(new DateTimeZone('UTC'));
    $end = clone $start;
    $end->modify('+15 minutes');
    $calendar_start = $start->format('Ymd\THis\Z');
    $calendar_end = $end->format('Ymd\THis\Z');
} catch (Exception $e) {}
$calendar_url = $base_url . ($script_dir === '/' ? '' : $script_dir) . '/calendar-add.php?t=' . rawurlencode($calendar_title) . '&d=' . rawurlencode($calendar_details) . '&s=' . rawurlencode($calendar_start) . '&e=' . rawurlencode($calendar_end);

$html = '<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Nieuw bericht</title></head>
<body style="margin:0;padding:0;font-family:\'Segoe UI\',Arial,sans-serif;background:#1e293b;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;padding:24px 16px;">
<tr><td style="background:linear-gradient(135deg,#0E7AD9 0%,#085091 100%);border-radius:12px 12px 0 0;padding:28px 24px;text-align:center;">
<div style="color:#fff;font-size:22px;font-weight:700;">CG Painting</div>
<div style="color:rgba(255,255,255,0.9);font-size:14px;margin-top:4px;">Professionele schilderwerken</div>
<h1 style="color:#fff;font-size:20px;font-weight:700;margin:20px 0 0;">Nieuw bericht van ' . htmlspecialchars($naam) . '</h1>
</td></tr>
<tr><td style="background:#fff;padding:28px 24px;border-radius:0 0 12px 12px;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
<p style="color:#374151;font-size:15px;margin:0 0 20px;">Je hebt een nieuw bericht ontvangen via jouw website:</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#1f2937;">
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Naam:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . htmlspecialchars($naam) . '</td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>E-mail:</strong></td></tr>
<tr><td style="padding:6px 0 14px;"><a href="mailto:' . htmlspecialchars($email) . '" style="color:#0E7AD9;">' . htmlspecialchars($email) . '</a></td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Telefoon:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . htmlspecialchars($telefoon) . '</td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Postcode:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . htmlspecialchars($postcode) . '</td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Stad:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . htmlspecialchars($stad) . '</td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Type werk:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . htmlspecialchars($type_werk_label) . '</td></tr>
</table>
<p style="margin:24px 0 0;text-align:center;">
<a href="' . $reply_link . '" style="display:inline-block;padding:12px 20px;margin:4px;background:#0E7AD9;color:#fff;text-decoration:none;font-weight:700;font-size:14px;border-radius:8px;">Antwoord klant</a>
<a href="' . htmlspecialchars($call_link) . '" style="display:inline-block;padding:12px 20px;margin:4px;background:#059669;color:#fff;text-decoration:none;font-weight:700;font-size:14px;border-radius:8px;">Bel klant</a>
<a href="' . htmlspecialchars($calendar_url) . '" style="display:inline-block;padding:12px 20px;margin:4px;background:#7c3aed;color:#fff;text-decoration:none;font-weight:700;font-size:14px;border-radius:8px;">In agenda zetten (morgen bellen)</a>
</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#1f2937;margin-top:20px;">
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Bericht:</strong></td></tr>
<tr><td style="padding:6px 0 14px;white-space:pre-wrap;">' . nl2br(htmlspecialchars($bericht)) . '</td></tr>
</table>
<p style="margin:20px 0 0;font-size:12px;color:#6b7280;text-align:center;">CG Painting – offerteformulier</p>
</td></tr>
</table>
</body>
</html>';

$boundary = '----=_Part_' . md5(uniqid());
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: multipart/mixed; boundary="' . $boundary . '"',
    'From: CG Painting <no-reply@' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'cgpainting.be') . '>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
];

$body = '--' . $boundary . "\r\n";
$body .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
$body .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
$body .= chunk_split(base64_encode($html)) . "\r\n";

if (!empty($_FILES['fotos']) && is_array($_FILES['fotos']['tmp_name'])) {
    $files = $_FILES['fotos'];
    for ($i = 0; $i < count($files['tmp_name']); $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK && is_uploaded_file($files['tmp_name'][$i])) {
            $filename = $files['name'][$i] ?: 'foto-' . ($i + 1);
            $content = file_get_contents($files['tmp_name'][$i]);
            $body .= '--' . $boundary . "\r\n";
            $body .= 'Content-Type: ' . ($files['type'][$i] ?: 'application/octet-stream') . '; name="' . $filename . '"' . "\r\n";
            $body .= 'Content-Transfer-Encoding: base64' . "\r\n";
            $body .= 'Content-Disposition: attachment; filename="' . $filename . '"' . "\r\n\r\n";
            $body .= chunk_split(base64_encode($content)) . "\r\n";
        }
    }
}

$body .= '--' . $boundary . '--';

$sent = @mail($to_email, $subject, $body, implode("\r\n", $headers));

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
$redirectUrl = 'index.html#contact';

if ($sent) {
    if ($isAjax) {
        echo json_encode(['ok' => true, 'message' => 'Bedankt! We nemen zo snel mogelijk contact op.']);
    } else {
        header('Location: ' . $redirectUrl . '?sent=1', true, 302);
        exit;
    }
} else {
    if ($isAjax) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Verzenden mislukt. Probeer later opnieuw of bel ons.']);
    } else {
        header('Location: ' . $redirectUrl . '?error=1', true, 302);
        exit;
    }
}
