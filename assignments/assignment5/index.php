<?php
require_once "classes/Directories.php";

$message = "";
$link = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dir = new Directories($_POST['dirname'] ?? '', $_POST['content'] ?? '');
    [$message, $link] = $dir->createDirectory();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>File and Directory Assignment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      margin: 50px auto;
      max-width: 700px;
    }
    h1 { font-weight: normal; margin-bottom: 30px; }
    .output { margin-bottom: 20px; }
  </style>
</head>
<body>
  <h1>Assignment 5 - File Directory</h1>

  <?php if ($message): ?>
    <div class="output alert alert-info">
      <?= htmlspecialchars($message) ?><br>
      <?php if ($link): ?>
        <a href="<?= htmlspecialchars($link) ?>" target="_blank">Path where the file is located</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label for="dirname" class="form-label">Enter folder name:</label>
      <input type="text" id="dirname" name="dirname" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="content" class="form-label">Enter text to write inside file:</label>
      <textarea id="content" name="content" rows="4" class="form-control" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Create Directory</button>
  </form>
</body>
</html>
