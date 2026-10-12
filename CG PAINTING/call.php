<?php
/**
 * CG Painting – Tap to call from Gmail (Gmail blocks direct tel: in emails)
 * Opens this page, then immediately triggers the phone dialer.
 */
$n = isset($_GET['n']) ? preg_replace('/[^0-9+]/', '', (string) $_GET['n']) : '';
if ($n !== '' && $n[0] !== '+') {
    $n = ($n[0] === '0') ? '+32' . substr($n, 1) : '+32' . $n;
}
$tel = $n ? ('tel:' . $n) : '';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bellen</title>
  <style>body{font-family:sans-serif;text-align:center;padding:2rem;margin:0;} a{display:inline-block;margin-top:1rem;padding:1rem 2rem;background:#059669;color:#fff;text-decoration:none;font-weight:700;border-radius:8px;}</style>
</head>
<body>
  <?php if ($tel): ?>
  <p>Nummer wordt gekozen…</p>
  <a href="<?php echo htmlspecialchars($tel); ?>" id="call">Tap om te bellen</a>
  <script>
    (function(){
      var u = <?php echo json_encode($tel); ?>;
      if (u) {
        try { window.location.href = u; } catch (e) {}
        document.getElementById('call').focus();
      }
    })();
  </script>
  <?php else: ?>
  <p>Geen nummer opgegeven.</p>
  <?php endif; ?>
</body>
</html>
