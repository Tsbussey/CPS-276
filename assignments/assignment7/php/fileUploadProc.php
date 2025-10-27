<?php
declare(strict_types=1);

$output = '';
require_once __DIR__ . '/../classes/Pdo_methods.php';

const MAX_BYTES = 5_000_000; // why: keep uploads reasonable

function render_form(string $message = ''): string {
  $msg = $message !== '' ? '<div class="msg">'.$message.'</div>' : '';
  return $msg . '
  <form method="post" enctype="multipart/form-data" novalidate>
    <label for="display_name">File name (label):</label>
    <input type="text" id="display_name" name="display_name" maxlength="255" required>
    <label for="pdf_file">PDF file:</label>
    <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf" required>
    <button type="submit" name="upload" value="1">Upload</button>
  </form>';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $output = render_form();
  return;
}

$displayName = trim($_POST['display_name'] ?? '');
$err = [];

if ($displayName === '') { $err[] = 'Please enter a file name.'; }

if (!isset($_FILES['pdf_file']) || ($_FILES['pdf_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
  $err[] = 'Please choose a PDF file.';
}

if (!$err) {
  $file = $_FILES['pdf_file'];
  if ($file['error'] !== UPLOAD_ERR_OK) { $err[] = 'Upload failed (code '.$file['error'].').'; }
  if ($file['size'] <= 0 || $file['size'] > MAX_BYTES) { $err[] = 'File must be >0 and ≤ '.number_format(MAX_BYTES).' bytes.'; }

  // strict MIME + extension check (why: stop spoofed files)
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime  = $finfo->file($file['tmp_name']);
  $extOk = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) === 'pdf';
  if ($mime !== 'application/pdf' || !$extOk) { $err[] = 'Only PDF files are allowed.'; }
}

if ($err) {
  $output = render_form(implode('<br>', $err));
  return;
}

$uniq   = bin2hex(random_bytes(8));
$targetDir = dirname(__DIR__) . '/files';
if (!is_dir($targetDir)) { mkdir($targetDir, 0775, true); } // ensure dir exists
$targetRel = 'files/' . $uniq . '.pdf';
$targetAbs = dirname(__DIR__, 1) . '/' . $targetRel;

if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetAbs)) {
  $output = render_form('Could not move uploaded file.');
  return;
}

try {
  $pdo = new PdoMethods();
  $sql = "INSERT INTO documents (display_name, file_path) VALUES (:display_name, :file_path)";
  $bindings = [
    [':display_name', $displayName, 'str'],
    [':file_path',    $targetRel,   'str'],
  ];
  $result = $pdo->otherBinded($sql, $bindings);
  if ($result === 'error') {
    @unlink($targetAbs);
    $output = render_form('Database error while saving record.');
    return;
  }
} catch (Throwable $t) {
  @unlink($targetAbs);
  $output = render_form('Unexpected error.');
  return;
}

$msg = 'Uploaded ✓ — <a href="'.$targetRel.'" target="_blank" rel="noopener">view file</a>.';
$output = render_form($msg);
