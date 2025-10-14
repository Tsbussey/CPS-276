<?php


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
