<?php
/*
-----------------------------------
Assignment 4 – PHP Calculator Q&A
-----------------------------------

1. Why separate index.php and Calculator.php?
   - index.php handles the form UI, while Calculator.php processes the calculation logic.
   - This separation follows good modular design practices and makes the code easier to maintain.

2. Role of $_SERVER["REQUEST_METHOD"]:
   - Ensures calculations are only performed when the form is submitted via POST.
   - Prevents code from running automatically when the page first loads.

3. Why validate user input?
   - Validation ensures that the input fields contain numeric values.
   - This prevents runtime errors or unexpected results if users enter invalid data.

4. How switch statements improve readability:
   - The switch statement allows for cleaner, more organized handling of multiple operations compared to multiple if-else statements.

5. How error handling is done for division:
   - Division by zero is checked with a conditional before performing the operation.
   - If the second number is zero, a custom message “Cannot divide by zero” is returned instead of performing the calculation.
*/

function performCalculation() {
    $num1 = $_POST['num1'] ?? '';
    $num2 = $_POST['num2'] ?? '';
    $operator = $_POST['operator'] ?? '';
    $result = '';

    // Input validation
    if (!is_numeric($num1) || !is_numeric($num2)) {
        return "Error: Please enter valid numbers for both inputs.";
    }

    $num1 = floatval($num1);
    $num2 = floatval($num2);

    // Perform calculation
    switch ($operator) {
        case 'add':
            $result = $num1 + $num2;
            break;
        case 'subtract':
            $result = $num1 - $num2;
            break;
        case 'multiply':
            $result = $num1 * $num2;
            break;
        case 'divide':
            if ($num2 == 0) {
                $result = "Error: Cannot divide by zero.";
            } else {
                $result = $num1 / $num2;
            }
            break;
        default:
            $result = "Error: Invalid operation selected.";
            break;
    }

    // Format output neatly
    return "Result: " . $result;
}
