<?php
/* =========================================================================
   File: php/addName.php         (DROP-IN REPLACEMENT)
   ========================================================================= */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../classes/Pdo_methods.php';

function ok(array $payload = []) { echo json_encode(array_merge(['masterstatus' => 'success'], $payload)); exit; }
function err(string $msg)       { echo json_encode(['masterstatus' => 'error', 'msg' => $msg]); exit; }

try {
  $data = json_decode(file_get_contents('php://input'), true);
  if (!is_array($data) || !isset($data['name'])) err('Invalid request.');

  $raw = trim((string)$data['name']);
  // Require "First Last" (two tokens) because the assignment shows that format
  $parts = preg_split('/\s+/', $raw, 2, PREG_SPLIT_NO_EMPTY);
  if (!$parts || count($parts) < 2) err('Enter first and last name separated by a space.');

  $first = trim($parts[0]);
  $last  = trim($parts[1]);
  if ($first === '' || $last === '') err('Enter first and last name separated by a space.');

  $formatted = "{$last}, {$first}";

  $pdo = new PdoMethods();
  $res = $pdo->otherBinded(
    "INSERT INTO names (name) VALUES (:name)",
    [[":name", $formatted, "str"]]
  );
  if ($res === 'error') err('Database error adding name.');

  // Your main.js re-calls displayNames(); just return a message here.
  ok(['msg' => "Added: {$formatted}"]);
} catch (Throwable $e) {
  err('Unexpected server error.');
}
