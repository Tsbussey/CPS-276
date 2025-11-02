<?php /* ===== index.php (full replacement) ===== */
//
// Robust include: works if this file is in project root OR inside /files
//
$procDir = is_dir(__DIR__ . '/php') ? (__DIR__ . '/php') : (dirname(__DIR__) . '/php');
require_once $procDir . '/fileUploadProc.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>File Upload</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;max-width:800px;margin:2rem auto;padding:0 1rem}
    form{display:grid;gap:.75rem;margin-top:1rem}
    input[type=text]{padding:.5rem;border:1px solid #ccc;border-radius:8px}
    input[type=file]{padding:.5rem;border-radius:8px}
    .msg{margin:.75rem 0;padding:.75rem 1rem;border-radius:8px;border:1px solid #ddd;background:#fafafa}
    .nav{margin:.5rem 0 1rem}
    a,a:link,a:visited{ text-decoration:none }
    a:hover{ text-decoration:underline }
    h1{ margin:0 0 .75rem }

    /* Make the submit button blue like the example */
    button[type="submit"], input[type="submit"]{
      background:#0d6efd; color:#fff; border:1px solid #0d6efd;
      border-radius:8px; padding:.6rem 1rem; cursor:pointer;
      width:auto; justify-self:start;
    }
    button[type="submit"]:hover, input[type="submit"]:hover{ filter:brightness(.95) }
  </style>
</head>
<body>
  <h1>File Upload</h1>
  <div class="nav"><a href="listFiles.php">Show File List</a></div>

  <?php echo $output; /* form HTML from fileUploadProc.php */ ?>

  <script>
    // Tweak the labels/text to match your example WITHOUT editing the proc file.
    const nameLbl = document.querySelector('label[for="display_name"]');
    if (nameLbl) nameLbl.textContent = 'File Name';

    const pdfLbl = document.querySelector('label[for="pdf_file"]');
    if (pdfLbl) pdfLbl.style.display = 'none';  // hide "PDF file:"

    const submitBtn = document.querySelector('button[type="submit"], input[type="submit"]');
    if (submitBtn) submitBtn.textContent = 'Upload File';
  </script>
</body>
</html>