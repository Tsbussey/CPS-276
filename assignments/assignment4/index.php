<?php
$output = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'Calculator.php';
    $output = performCalculation();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assignment 4: Calculator</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

  <h2 class="mb-3">PHP Calculator</h2>

  <form method="post" class="mb-3">

    <div class="mb-3">
      <label for="num1" class="form-label">Enter First Number:</label>
      <input type="text" class="form-control" id="num1" name="num1" required>
    </div>

    <div class="mb-3">
      <label for="num2" class="form-label">Enter Second Number:</label>
      <input type="text" class="form-control" id="num2" name="num2" required>
    </div>

    <div class="mb-3">
      <label for="operator" class="form-label">Select Operation:</label>
      <select id="operator" name="operator" class="form-select">
        <option value="add">Addition</option>
        <option value="subtract">Subtraction</option>
        <option value="multiply">Multiplication</option>
        <option value="divide">Division</option>
      </select>
    </div>

    <div class="mb-3">
      <button type="submit" class="btn btn-primary">Calculate</button>
      <button type="reset" class="btn btn-secondary">Clear</button>
    </div>

    <div class="mb-3">
      <label for="result" class="form-label">Result:</label>
      <textarea id="result" class="form-control" rows="2" readonly><?php echo $output; ?></textarea>
    </div>

  </form>

</body>
</html>
