<?php
$flashMessage = '';
$flashClass   = 'alert-info';

require_once __DIR__ . '/classes/PdoMethods.php';

if (($_POST['upload'] ?? '') === '1') {
  try {
    $fileNameInput = trim((string)($_POST['file_name'] ?? ''));
    if ($fileNameInput === '') { throw new InvalidArgumentException('Please provide a file name.'); }

    if (!isset($_FILES['pdf']) || $_FILES['pdf']['error'] !== UPLOAD_ERR_OK) {
      throw new RuntimeException('Upload failed or no file provided.');
    }

    $tmpPath  = $_FILES['pdf']['tmp_name'];
    $origName = $_FILES['pdf']['name'];
    $size     = (int)$_FILES['pdf']['size'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $tmpPath);
    finfo_close($finfo);
    if ($mime !== 'application/pdf' && !preg_match('/\.pdf$/i', $origName)) {
      throw new InvalidArgumentException('Only PDF files are allowed.');
    }
    if ($size > 5 * 1024 * 1024) { throw new InvalidArgumentException('File too large (max 5MB).'); }

    $filesDir = dirname(__DIR__) . '/files';
    if (!is_dir($filesDir) && !mkdir($filesDir, 0755, true)) {
      throw new RuntimeException('Cannot create files directory.');
    }
    if (!is_writable($filesDir)) { throw new RuntimeException('Files directory is not writable.'); }

    $diskName = bin2hex(random_bytes(16)) . '.pdf';
    $destPath = $filesDir . '/' . $diskName;
    if (!move_uploaded_file($tmpPath, $destPath)) {
      throw new RuntimeException('Failed to move uploaded file.');
    }

    $publicPath = 'files/' . $diskName;

    $db  = new PdoMethods(); // now works with your DatabaseConn
    $sql = 'INSERT INTO documents (file_name, file_path) VALUES (:file_name, :file_path)';
    $id  = $db->otherBinded($sql, [
      ':file_name' => $fileNameInput,
      ':file_path' => $publicPath,
    ]);

    $flashMessage = 'Upload complete. ID: ' . htmlspecialchars((string)$id);
    $flashClass   = 'alert-success';
  } catch (Throwable $e) {
    $flashMessage = $e->getMessage();
    $flashClass   = 'alert-danger';
  }
}