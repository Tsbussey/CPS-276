<?php
require_once __DIR__ . '/../php/listFilesProc.php';
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
  </style>
</head>
<body>
  <div class="nav"><a href="index.php">← Back to Upload</a></div>
  <h1>Files</h1>
  <?php echo $output; ?>
</body>
</html>
