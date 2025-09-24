<?php
/*
-----------------------------------
Assignment 3 – Q&A
-----------------------------------
(same comments as before...)
*/

function addClearNames() {
    session_start(); // keep track of names between requests

    if (!isset($_SESSION['names'])) {
        $_SESSION['names'] = [];
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add' && !empty($_POST['fullname'])) {
        $fullName = trim($_POST['fullname']);
        $parts = explode(" ", $fullName);

        if (count($parts) == 2) {
            $first = ucfirst(strtolower($parts[0]));
            $last  = ucfirst(strtolower($parts[1]));
            $formatted = "$last, $first";

            // Add to session array
            $_SESSION['names'][] = $formatted;
            sort($_SESSION['names']); // keep sorted
        }
    } elseif ($action === 'clear') {
        // Instantly clear names
        $_SESSION['names'] = [];
        return ""; // immediately empty textarea
    }

    // Return the names as one string with line breaks
    return implode("\n", $_SESSION['names']);
}
