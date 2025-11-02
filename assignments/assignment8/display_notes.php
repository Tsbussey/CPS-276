/* ================================================================================================
 * File: assignment8/classes/display_notes.php
 * Why: Display page (date range → results table or “no notes” message).
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
  <title>Assignment 8 – Display Notes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h1 class="mb-4">Display Notes</h1>

  <form method="post" class="card p-3 shadow-sm mb-4">
    <div id="messageRegion" class="mb-2"><?= $notes ?></div>

    <div class="row g-3">
      <div class="col-md-6">
        <label for="begDate" class="form-label">Beginning Date</label>
        <input type="date" class="form-control" id="begDate" name="begDate" />
      </div>
      <div class="col-md-6">
        <label for="endDate" class="form-label">Ending Date</label>
        <input type="date" class="form-control" id="endDate" name="endDate" />
      </div>
    </div>

    <div class="mt-3">
      <button type="submit" name="getNotes" class="btn btn-primary">Get Notes</button>
      <a class="btn btn-outline-secondary" href="./index.php">Add Note</a>
    </div>
  </form>
</div>
</body>
</html>
