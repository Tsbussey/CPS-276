<?php
$output = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'processNames.php';
    $output = addClearNames();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assignment 3: Name List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2 class="mb-3">Add Names</h2>

  <form method="post">
    <!-- Buttons go directly under heading -->
    <div class="mb-3">
      <button type="submit" name="action" value="add" class="btn btn-primary">Add Name</button>
      <button type="submit" name="action" value="clear" class="btn btn-primary">Clear Names</button>
    </div>

    <!-- Input field -->
    <div class="mb-3">
      <label for="fullname" class="form-label">Enter Name:</label>
      <input type="text" id="fullname" name="fullname" class="form-control">
    </div>

    <!-- Textarea with label -->
    <div class="mb-3">
      <label for="namelist" class="form-label">List of Names:</label>
      <textarea style="height: 500px;" class="form-control" id="namelist" name="namelist"><?php echo $output ?></textarea>
    </div>
  </form>

</body>
</html>
