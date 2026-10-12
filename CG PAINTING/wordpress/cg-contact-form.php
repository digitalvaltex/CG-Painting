<?php
/**
 * CG Painting – WordPress contact form (Shortcode + wp_mail, no plugins)
 *
 * Install: copy this file into your child theme and in functions.php add:
 *   require_once get_stylesheet_directory() . '/cg-contact-form.php';
 * Or paste the contents of cg-contact-form-inline.php into functions.php.
 *
 * Usage: on a page or template use shortcode [cg_contact_form]
 * Email goes to: hello@cgpainting.be (or change CG_CONTACT_TO below)
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CG_CONTACT_TO', 'hello@cgpainting.be'); // or get_option('admin_email')

/**
 * Shortcode: [cg_contact_form]
 * Renders the offerte form with same structure/CSS as the static site.
 */
add_shortcode('cg_contact_form', function () {
    $nonce = wp_create_nonce('cg_contact_nonce');
    $show_success = isset($_GET['cg_sent']) && $_GET['cg_sent'] === '1';
    $show_error   = isset($_GET['cg_error']) && $_GET['cg_error'] === '1';

    ob_start();
    ?>
    <div class="contact-form-panel cg-contact-form">
        <?php if ($show_success): ?>
            <div class="form-message success" role="alert">Bedankt! We nemen zo snel mogelijk contact op.</div>
        <?php endif; ?>
        <?php if ($show_error): ?>
            <div class="form-message error" role="alert">Verzenden mislukt. Probeer later opnieuw of bel ons.</div>
        <?php endif; ?>

        <form class="cg-form" method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="cg_contact_nonce" value="<?php echo esc_attr($nonce); ?>">
            <input type="hidden" name="cg_contact_action" value="submit">

            <div class="form-row form-row--two">
                <div class="form-field">
                    <label class="form-label">Naam <span class="req">*</span></label>
                    <input type="text" class="input" name="naam" placeholder="Uw naam" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Telefoon <span class="req">*</span></label>
                    <input type="tel" class="input" name="telefoon" placeholder="+32 123 45 67 89" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label class="form-label">E-mail <span class="req">*</span></label>
                    <input type="email" class="input" name="email" placeholder="uw.email@example.com" required>
                </div>
            </div>

            <div class="form-row form-row--two">
                <div class="form-field">
                    <label class="form-label">Postcode <span class="req">*</span></label>
                    <input type="text" class="input" name="postcode" placeholder="3500" required>
                </div>
                <div class="form-field">
                    <label class="form-label">Stad <span class="req">*</span></label>
                    <input type="text" class="input" name="stad" placeholder="Hasselt" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label class="form-label">Type werk <span class="req">*</span></label>
                    <select name="type_werk" class="select" required>
                        <option value="">Selecteer een optie</option>
                        <option value="schilderwerken">Schilderwerken</option>
                        <option value="dunpleisteren">Dunpleisteren</option>
                        <option value="behangen">Behangen</option>
                        <option value="decor">Decor</option>
                        <option value="renovatie">Renovatie werken</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label class="form-label">Upload foto's (optioneel)</label>
                    <div class="file-input-wrapper">
                        <span class="file-input-icon">↑</span>
                        <span class="file-input-text">Klik om foto's te uploaden</span>
                        <input type="file" name="fotos[]" multiple accept="image/*">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-field">
                    <label class="form-label">Bericht</label>
                    <textarea class="textarea" name="bericht" placeholder="Vertel ons meer over uw project..." rows="4"></textarea>
                </div>
            </div>

            <div class="form-submit-row">
                <button type="submit" class="btn-primary btn-submit">Vraag offerte aan</button>
                <p class="form-privacy">Door dit formulier in te dienen, gaat u akkoord met ons privacybeleid. Uw gegevens worden vertrouwelijk behandeld.</p>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
});

/**
 * Handle form submit and send HTML email via wp_mail
 */
add_action('init', function () {
    if (!isset($_POST['cg_contact_action']) || $_POST['cg_contact_action'] !== 'submit') {
        return;
    }

    if (!isset($_POST['cg_contact_nonce']) || !wp_verify_nonce($_POST['cg_contact_nonce'], 'cg_contact_nonce')) {
        wp_die(__('Security check failed.', 'cg-painting'));
    }

    $naam     = sanitize_text_field($_POST['naam'] ?? '');
    $telefoon = sanitize_text_field($_POST['telefoon'] ?? '');
    $email    = sanitize_email($_POST['email'] ?? '');
    $postcode = sanitize_text_field($_POST['postcode'] ?? '');
    $stad     = sanitize_text_field($_POST['stad'] ?? '');
    $type_werk = sanitize_text_field($_POST['type_werk'] ?? '');
    $bericht  = sanitize_textarea_field($_POST['bericht'] ?? '');

    $type_labels = [
        'schilderwerken' => 'Schilderwerken',
        'dunpleisteren'  => 'Dunpleisteren',
        'behangen'       => 'Behangen',
        'decor'          => 'Decor',
        'renovatie'      => 'Renovatie werken',
    ];
    $type_werk_label = isset($type_labels[$type_werk]) ? $type_labels[$type_werk] : $type_werk;

    $to      = CG_CONTACT_TO;
    $subject = 'Nieuw bericht van ' . $naam;
    $reply_subject = 'Re: ' . $subject;
    $reply_link = $email ? 'mailto:' . esc_attr($email) . '?subject=' . rawurlencode($reply_subject) : '#';

    $telefoon_tel = preg_replace('/[^0-9+]/', '', $telefoon);
    if ($telefoon_tel && $telefoon_tel[0] !== '+') {
        $telefoon_tel = (substr($telefoon_tel, 0, 1) === '0') ? '+32' . substr($telefoon_tel, 1) : '+32' . $telefoon_tel;
    }
    $call_link = $telefoon_tel ? home_url('/call.php?n=' . rawurlencode($telefoon_tel)) : '#';

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
    $calendar_url = home_url('/calendar-add.php?t=' . rawurlencode($calendar_title) . '&d=' . rawurlencode($calendar_details) . '&s=' . rawurlencode($calendar_start) . '&e=' . rawurlencode($calendar_end));

    $html = '<!DOCTYPE html>
<html lang="nl">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Nieuw bericht</title></head>
<body style="margin:0;padding:0;font-family:\'Segoe UI\',Arial,sans-serif;background:#1e293b;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;padding:24px 16px;">
<tr><td style="background:linear-gradient(135deg,#0E7AD9 0%,#085091 100%);border-radius:12px 12px 0 0;padding:28px 24px;text-align:center;">
<div style="color:#fff;font-size:22px;font-weight:700;">CG Painting</div>
<div style="color:rgba(255,255,255,0.9);font-size:14px;margin-top:4px;">Professionele schilderwerken</div>
<h1 style="color:#fff;font-size:20px;font-weight:700;margin:20px 0 0;">Nieuw bericht van ' . esc_html($naam) . '</h1>
</td></tr>
<tr><td style="background:#fff;padding:28px 24px;border-radius:0 0 12px 12px;box-shadow:0 4px 24px rgba(0,0,0,0.08);">
<p style="color:#374151;font-size:15px;margin:0 0 20px;">Je hebt een nieuw bericht ontvangen via jouw website:</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#1f2937;">
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Naam:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . esc_html($naam) . '</td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>E-mail:</strong></td></tr>
<tr><td style="padding:6px 0 14px;"><a href="mailto:' . esc_attr($email) . '" style="color:#0E7AD9;">' . esc_html($email) . '</a></td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Telefoon:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . esc_html($telefoon) . '</td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Postcode:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . esc_html($postcode) . '</td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Stad:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . esc_html($stad) . '</td></tr>
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Type werk:</strong></td></tr>
<tr><td style="padding:6px 0 14px;">' . esc_html($type_werk_label) . '</td></tr>
</table>
<p style="margin:24px 0 0;text-align:center;">
<a href="' . esc_attr($reply_link) . '" style="display:inline-block;padding:12px 20px;margin:4px;background:#0E7AD9;color:#fff;text-decoration:none;font-weight:700;font-size:14px;border-radius:8px;">Antwoord klant</a>
<a href="' . esc_attr($call_link) . '" style="display:inline-block;padding:12px 20px;margin:4px;background:#059669;color:#fff;text-decoration:none;font-weight:700;font-size:14px;border-radius:8px;">Bel klant</a>
<a href="' . esc_attr($calendar_url) . '" style="display:inline-block;padding:12px 20px;margin:4px;background:#7c3aed;color:#fff;text-decoration:none;font-weight:700;font-size:14px;border-radius:8px;">In agenda zetten (morgen bellen)</a>
</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#1f2937;margin-top:20px;">
<tr><td style="padding:6px 0;border-bottom:1px solid #f3f4f6;"><strong>Bericht:</strong></td></tr>
<tr><td style="padding:6px 0 14px;white-space:pre-wrap;">' . nl2br(esc_html($bericht)) . '</td></tr>
</table>
<p style="margin:20px 0 0;font-size:12px;color:#6b7280;text-align:center;">CG Painting – offerteformulier</p>
</td></tr>
</table>
</body>
</html>';

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Valtex Digital <no-reply@valtexdigital.com>',
        'Reply-To: ' . $naam . ' <' . $email . '>',
    ];

    $attachments = [];
    $base = '';
    if (!empty($_FILES['fotos']) && is_array($_FILES['fotos']['tmp_name'])) {
        $files = $_FILES['fotos'];
        $upload_dir = wp_upload_dir();
        $base = isset($upload_dir['basedir']) ? $upload_dir['basedir'] : sys_get_temp_dir();
        $base = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'cg_contact_' . wp_rand(10000, 99999);
        wp_mkdir_p($base);
        for ($i = 0; $i < count($files['tmp_name']); $i++) {
            if (!empty($files['tmp_name'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK && is_uploaded_file($files['tmp_name'][$i])) {
                $name = sanitize_file_name($files['name'][$i] ?: 'foto-' . ($i + 1));
                $path = $base . DIRECTORY_SEPARATOR . $name;
                if (copy($files['tmp_name'][$i], $path)) {
                    $attachments[] = $path;
                }
            }
        }
    }

    $sent = wp_mail($to, $subject, $html, $headers, $attachments);

    foreach ($attachments as $path) {
        if (file_exists($path)) {
            @unlink($path);
        }
    }
    if (!empty($base) && is_dir($base)) {
        @rmdir($base);
    }

    $redirect = wp_get_referer();
    if (!$redirect) {
        $redirect = home_url('/');
    }
    $redirect = remove_query_arg(['cg_sent', 'cg_error'], $redirect);
    $redirect = add_query_arg($sent ? 'cg_sent' : 'cg_error', '1', $redirect);
    $redirect .= '#contact';

    wp_safe_redirect($redirect);
    exit;
});
