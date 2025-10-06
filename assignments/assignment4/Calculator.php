<?php
/*
-----------------------------------------------------------
Assignment 4 – Calculator Class
Author: [Your Name]
Course: CPS 276
-----------------------------------------------------------
Q&A
-----------------------------------------------------------
1️⃣ Why use require_once "Calculator.php"?
   ➤ It ensures the Calculator class is loaded only once.
     Using require or include could load it multiple times,
     causing redeclaration errors.

2️⃣ How does divide prevent division by zero?
   ➤ The calc() method checks if $num2 == 0 before dividing,
     and returns a descriptive message rather than attempting division.

3️⃣ How to add exponentiation (^)?
   ➤ Add a new case "^" to the switch statement and use pow($num1, $num2).

4️⃣ Difference between Calculator class and $Calculator object?
   ➤ The class defines the blueprint; $Calculator is an instance
     created from that blueprint to use its methods.

5️⃣ Why check that inputs are numbers?
   ➤ Prevents runtime errors and ensures correct math logic.

6️⃣ Why separate index.php (display) from Calculator.php (logic)?
   ➤ Separating logic and presentation improves maintainability,
     readability, and scalability.
-----------------------------------------------------------
*/

class Calculator {

    public function calc($operator = null, $num1 = null, $num2 = null) {

        // Missing arguments
        if ($operator === null || $num1 === null || $num2 === null) {
            return "<p>Cannot perform operation. You must have three arguments. A string for the operator (+,-,*,/) and two integers or floats for the numbers.</p>";
        }

        // Validate numeric inputs
        if (!is_numeric($num1) || !is_numeric($num2)) {
            return "<p>Cannot perform operation. You must have three arguments. A string for the operator (+,-,*,/) and two integers or floats for the numbers.</p>";
        }

        // Convert to float for consistent output
        $num1 = floatval($num1);
        $num2 = floatval($num2);
        $result = null;

        switch ($operator) {
            case '+':
                $result = $num1 + $num2;
                break;
            case '-':
                $result = $num1 - $num2;
                break;
            case '*':
                $result = $num1 * $num2;
                break;
            case '/':
                if ($num2 == 0) {
                    return "<p>The calculation is $num1 / $num2. The answer is cannot divide a number by zero.</p>";
                }
                $result = $num1 / $num2;
                break;
            default:
                return "<p>Cannot perform operation. You must have three arguments. A string for the operator (+,-,*,/) and two integers or floats for the numbers.</p>";
        }

        return "<p>The calculation is $num1 $operator $num2. The answer is $result.</p>";
    }
}
?>
