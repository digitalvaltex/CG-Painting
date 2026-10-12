<?php
/**
 * CG Painting – Add reminder to calendar (iOS → .ics, Android/desktop → Google Calendar)
 * Link from email opens this page; JS detects device and redirects accordingly.
 */
$title   = isset($_GET['t']) ? (string) $_GET['t'] : 'Bel klant - CG Painting';
$details = isset($_GET['d']) ? (string) $_GET['d'] : '';
$start   = isset($_GET['s']) ? (string) $_GET['s'] : '';
$end     = isset($_GET['e']) ? (string) $_GET['e'] : '';

$title   = rawurldecode($title);
$details = rawurldecode($details);

if (isset($_GET['format']) && $_GET['format'] === 'ics') {
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="reminder.ics"');
    $start_ics = preg_replace('/[^0-9TZ]/', '', $start);
    $end_ics   = preg_replace('/[^0-9TZ]/', '', $end);
    if (strlen($start_ics) < 15) {
        $dt = new DateTime('tomorrow 10:00', new DateTimeZone('Europe/Brussels'));
        $dt->setTimezone(new DateTimeZone('UTC'));
        $start_ics = $dt->format('Ymd\THis\Z');
        $dt->modify('+15 minutes');
        $end_ics = $dt->format('Ymd\THis\Z');
    }
    $details_esc = str_replace(["\r", "\n"], ['', "\\n"], $details);
    echo "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//CG Painting//Reminder//NL\r\n";
    echo "BEGIN:VEVENT\r\nDTSTART:$start_ics\r\nDTEND:$end_ics\r\nSUMMARY:" . str_replace(["\r", "\n", ',', ';'], ['', ' ', '\\,', '\\;'], $title) . "\r\n";
    echo "DESCRIPTION:" . str_replace(["\r", "\n", ',', ';'], ['', "\\n", '\\,', '\\;'], $details_esc) . "\r\n";
    echo "END:VEVENT\r\nEND:VCALENDAR\r\n";
    exit;
}

$google_url = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . rawurlencode($title) . '&details=' . rawurlencode($details);
if ($start && $end) {
    $google_url .= '&dates=' . $start . '/' . $end;
}
$self_ics = '?' . http_build_query(array_merge($_GET, ['format' => 'ics']));
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toevoegen aan agenda</title>
  <script>
    (function() {
      var ua = navigator.userAgent || '';
      var isIOS = /iPhone|iPad|iPod/i.test(ua);
      var isAndroid = /Android/i.test(ua);
      if (isIOS) {
        window.location.href = <?php echo json_encode($self_ics); ?>;
      } else {
        window.location.href = <?php echo json_encode($google_url); ?>;
      }
    })();
  </script>
</head>
<body>
  <p>Even geduld…</p>
</body>
</html>
