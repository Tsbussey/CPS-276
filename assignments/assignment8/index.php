/* ================================================================================================
 * File: assignment8/classes/index.php
 * Why: Add Note page (posts to itself; Date_time handles logic + messages).
 * ==============================================================================================*/
<?php
declare(strict_types=1);

require_once __DIR__ . '/Db_conn.php';
require_once __DIR__ . '/Date_time.php';

$dt = new Date_time();
$notes = $dt->checkSubmit();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Assignment 8 – Add Note</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1 class="mb-4">Add Note</h1>

  <form method="post" class="card p-3 shadow-sm">
    <div id="messageRegion" class="mb-2"><?= $notes ?></div>

    <div class="mb-3">
      <label for="dateTime" class="form-label">Date and Time</label>
      <input type="datetime-local" class="form-control" id="dateTime" name="dateTime" />
    </div>

    <div class="mb-3">
      <label for="note" class="form-label">Note</label>
      <textarea class="form-control" id="note" name="note" rows="4" placeholder="Type your note..."></textarea>
    </div>

    <button type="submit" name="addNote" class="btn btn-primary">Add Note</button>
    <a class="btn btn-outline-secondary" href="./display_notes.php">Display Notes</a>
  </form>
</div>
</body>
</html>
