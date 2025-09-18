<?php
// --------------------
// Step 1: Even Numbers
// --------------------
$numbers = range(1, 50);
$evenNumbersArray = [];

foreach ($numbers as $num) {
    if ($num % 2 === 0) {
        $evenNumbersArray[] = $num;
    }
}

// Join with separator " - "
$evenNumbers = implode(" - ", $evenNumbersArray);


// --------------------
// Step 2: Heredoc Form
// --------------------
$form = <<<FORM
<form>
<div class="mb-3">
<label> Even Numbers: </label> {$evenNumbers}

</div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" value="name@example.com" >
  </div>
  <div class="mb-3">
    <label for="exampleTextarea" class="form-label">Example textarea</label>
    <textarea class="form-control" id="exampleTextarea" rows="3"></textarea>
  </div>
</form>
FORM;


// --------------------
// Step 3: Create Table
// --------------------
function createTable($rows, $cols) {
    $table = '<table class="table table-bordered">';
    for ($i = 0; $i < $rows; $i++) {
        $table .= "<tr>";
        for ($j = 0; $j < $cols; $j++) {
            $displayValue=$i+1;
            $displayValueCol=$j+1;
          $table .= "<td>Row {$displayValue} Col {$displayValueCol}</td>";
        }
        $table .= "</tr>";
    }
    $table .= "</table>";
    return $table;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assignment 2 PHP Basics</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">

    <?php
        echo $form;
        echo createTable(8, 6);
    ?>

</body>
</html>
