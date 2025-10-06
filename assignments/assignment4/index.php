<?php
require_once "Calculator.php";

$Calculator = new Calculator();

$result = "";
$result .= $Calculator->calc("*", 10, 2);
$result .= $Calculator->calc("*", 4.56, 2);
$result .= $Calculator->calc("/", 10, 2);
$result .= $Calculator->calc("/", 10, 3);
$result .= $Calculator->calc("/", 10, 0);
$result .= $Calculator->calc("/", 0, 10);
$result .= $Calculator->calc("-", 10, 2);
$result .= $Calculator->calc("-", 10, 20);
$result .= $Calculator->calc("+", 10.5, 2);
$result .= $Calculator->calc("+", 10.5, 0);
$result .= $Calculator->calc("*", 10);
$result .= $Calculator->calc("+","a",10);
$result .= $Calculator->calc("+",10,"a");
$result .= $Calculator->calc(10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      color: #212529;
      margin: 0px 50px 0px 50px auto;
      max-width: 700px;
      line-height: 1.6;
    }
    h1 {
      font-weight: 500;
      margin-bottom: 0px;
    }
    main p {
      margin-bottom: 1rem;
    }
  </style>
</head>
<body class="container">
  <h1>Calculator Output</h1>
  <main>
    <?php echo $result ?>
  </main>
</body>
</html>
