<?php
/* =========================================================================
 * File: ~/public_html/cps276/assignments/assignment8/display_notes.php
 * ========================================================================= */
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);

require __DIR__ . '/classes/Date_time.php';
$dt = new Date_time();
$dt->checkSubmit('display');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Assignment 8 — Display Notes</title>
<style>
  :root { --w: 960px; --gap:.75rem; --muted:#f4f6f8; --blue:#0d6efd; --blue-d:#0b5ed7; --border:#d7dbe0;}
  body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:40px auto;max-width:var(--w);padding:0 20px;color:#111}
  h1{font-size:2.25rem;line-height:1.15;margin:0 0 6px}
  .nav{margin:2px 0 24px} .nav a{color:var(--blue);text-decoration:underline}

  form{display:grid;gap:.9rem;max-width:720px;margin-bottom:18px}
  label{font-weight:600;margin-bottom:2px;display:block}
  input[type="date"]{width:100%;padding:.55rem .7rem;border:1px solid var(--border);border-radius:8px;font:inherit}
  .form-col{display:block}

  .msg{margin:4px 0 18px;padding:.6rem .8rem;border-radius:8px;background:var(--muted)}

  .btn-primary{
    background:var(--blue);
    border:1px solid var(--blue-d);
    color:#fff;
    font-weight:700;
    font-size:.9rem;
    line-height:1;
    padding:.42rem .72rem;
    border-radius:6px;
    cursor:pointer;
  }
  .btn-primary:hover{background:#0b63f0;border-color:#0a54c7}
  .btn-primary:active{background:#0a58ca;border-color:#094fb8}

  table{width:100%;border-collapse:collapse}
  th,td{border:1px solid var(--border);padding:.6rem .7rem;text-align:left;vertical-align:top}
  th{background:#fafbfc}
  tbody tr:nth-child(even){background:#fbfcfe}
</style>
</head>
<body>
  <h1>Display Notes</h1>
  <div class="nav"><a href="index.php">Add Note</a></div>

  <?php if ($dt->message): ?>
    <div class="msg"><?= htmlspecialchars($dt->message, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <div class="form-col">
      <label for="begDate">Beginning Date</label>
      <input type="date" id="begDate" name="begDate">
    </div>
    <div class="form-col">
      <label for="endDate">Ending Date</label>
      <input type="date" id="endDate" name="endDate">
    </div>
    <div><button type="submit" name="getNotes" class="btn-primary">Get Notes</button></div>
  </form>

  <?php if ($dt->rows): ?>
    <table>
      <thead><tr><th>Date and Time</th><th>Note</th></tr></thead>
      <tbody>
      <?php foreach ($dt->rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars(Date_time::fmt((int)$r['date_time']), ENT_QUOTES, 'UTF-8') ?></td>
          <td><?= nl2br(htmlspecialchars($r['note'], ENT_QUOTES, 'UTF-8')) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>
