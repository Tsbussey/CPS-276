<?php
/* =========================================================================
 * File: ~/public_html/cps276/assignments/assignment8/index.php
 * ========================================================================= */
declare(strict_types=1);
ini_set('display_errors','1'); error_reporting(E_ALL);

require __DIR__ . '/classes/Date_time.php';
$dt = new Date_time();
$dt->checkSubmit('add');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assignment 8 — Add Note</title>
  <style>
    :root { --maxw: 960px; --gap:.75rem; --muted:#f2f3f5; --border:#cfd3d9; --blue:#0d6efd; --blue-d:#0b5ed7; }
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:40px auto;max-width:var(--maxw);padding:0 20px;color:#111}
    h1{font-size:2.25rem;margin:0 0 6px}
    .nav{margin:2px 0 24px}.nav a{color:var(--blue);text-decoration:underline}
    form{display:grid;gap:var(--gap);max-width:720px}
    label{font-weight:600;margin-bottom:2px}
    input[type="datetime-local"],textarea{width:100%;padding:.62rem .7rem;border:1px solid var(--border);border-radius:8px;font:inherit}
    textarea{min-height:220px;resize:vertical}
    .msg{margin:4px 0 18px;padding:.6rem .8rem;border-radius:8px;background:var(--muted)}
    .actions{margin-top:10px;display:flex;gap:12px;align-items:center}
    .btn-primary{background:var(--blue);border:1px solid var(--blue-d);color:#fff;font-weight:700;font-size:.9rem;line-height:1;padding:.42rem .72rem;border-radius:6px;cursor:pointer}
    .btn-primary:hover{background:#0b63f0;border-color:#0a54c7}
    .btn-primary:active{background:#0a58ca;border-color:#094fb8}
  </style>
</head>
<body>
  <h1>Add Note</h1>
  <div class="nav"><a href="display_notes.php">Display Notes</a></div>

  <?php if ($dt->message): ?>
    <div class="msg"><?= htmlspecialchars($dt->message, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" novalidate>
    <label for="dateTime">Date and Time</label>
    <input type="datetime-local" id="dateTime" name="dateTime">

    <label for="note">Note</label>
    <textarea id="note" name="note" maxlength="2000"></textarea>

    <div class="actions">
      <button type="submit" name="addNote" class="btn-primary">Add Note</button>
    </div>
  </form>
</body>
</html>