<<<<<<< Updated upstream
<?php /* ===== listFiles.php (full replacement) ===== */
//
// Robust include: works if this file is in project root OR inside /files
//
$procDir = is_dir(__DIR__ . '/php') ? (__DIR__ . '/php') : (dirname(__DIR__) . '/php');
require_once $procDir . '/listFilesProc.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>File List</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;max-width:800px;margin:2rem auto;padding:0 1rem}
    ul{padding-left:1.25rem}
    .nav{margin:.5rem 0 1rem}
    .empty{color:#666}
    a,a:link,a:visited{ text-decoration:none }
    a:hover{ text-decoration:underline }
    h1{ margin:0 0 .75rem }
=======
<?php
require_once __DIR__ . '/php/listFilesProc.php';
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Assignment 7 PDO – Files</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;max-width:800px;margin:2rem auto;padding:0 1rem}
    ul{padding-left:1.25rem}
    .nav{margin-bottom:1rem}
    .empty{color:#666}
>>>>>>> Stashed changes
  </style>
</head>
<body>
  <div class="nav"><a href="index.php">← Back to Upload</a></div>
<<<<<<< Updated upstream
  <h1>File List</h1>
  <?php echo $output; /* list HTML from listFilesProc.php */ ?>
=======
  <h1>Files</h1>
  <?php echo $output; ?>
>>>>>>> Stashed changes
</body>
</html>
