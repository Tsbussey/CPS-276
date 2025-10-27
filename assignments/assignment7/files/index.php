<?php
require_once __DIR__ . '/../php/fileUploadProc.php';
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Assignment 7 PDO – Upload PDF</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;max-width:800px;margin:2rem auto;padding:0 1rem}
    form{display:grid;gap:.75rem;margin-top:1rem}
    input[type=text]{padding:.5rem;border:1px solid #ccc;border-radius:8px}
    input[type=file]{padding:.5rem;border-radius:8px}
    button{padding:.6rem 1rem;border-radius:8px;border:1px solid #999;background:#f5f5f5;cursor:pointer}
    .msg{margin:.75rem 0;padding:.75rem 1rem;border-radius:8px;border:1px solid #ddd;background:#fafafa}
    .nav{margin-top:1rem}
  </style>
</head>
<body>
  <h1>Upload a PDF</h1>
  <div class="nav"><a href="listFiles.php">Show File List</a></div>
  <?php echo $output; ?>
</body>
</html>
