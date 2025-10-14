<?php
require_once "classes/Directories.php";

// Initialize variables to avoid undefined warnings
$message = "";
$link = "";

// Handle form submission
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
    margin: 20px 0 0 250px; /* Align upper-left */
    color: #212529;
  }
  h1 {
    font-weight: 600;
    margin-bottom: 10px;
  }
  p.instructions {
    margin-bottom: 25px;
    color: #333;
    max-width: 1000px; /* Prevent wrapping */
    white-space: nowrap; /* Keep sentence on one line */
  }
  form {
    max-width: 900px;
  }
  label {
    font-weight: normal; /* Unbold labels */
  }
  textarea {
    resize: none;
  }
</style>
</head>
<body>

  <h1>File and Directory Assignment</h1>
  <p class="instructions">Enter a folder name and the contents of a file. Folder names should contain alpha numeric characters only.</p>

  <?php if (!empty($message)): ?>
    <?php if (!empty($link)): ?>
        <p>File and directory were created</p>
        <p>
          <a href="<?= htmlspecialchars($link) ?>" target="_blank" style="text-decoration: none; color: #007bff;">
            Path where file is located
          </a>
        </p>
    <?php else: ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
<?php endif; ?>



  <form method="post">
    <div class="mb-3">
      <label for="dirname" class="form-label">Folder Name</label>
      <input type="text" id="dirname" name="dirname" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="content" class="form-label">File Content</label>
      <textarea id="content" name="content" rows="6" class="form-control" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>

</body>
</html>
