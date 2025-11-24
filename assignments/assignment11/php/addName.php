# php/addName.php
<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../classes/Pdo_methods.php';

function respond($status, $payload) {
  echo json_encode(array_merge(['masterstatus' => $status], $payload));
  exit;
}

try {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw, true);

  if (!is_array($data) || !isset($data['name'])) {
    respond('error', ['msg' => 'Invalid request.']);
  }

  $input = trim($data['name']);
  // Require "First Last" (at least two tokens)
  $parts = preg_split('/\s+/', $input, 2, PREG_SPLIT_NO_EMPTY);

  if (!$parts || count($parts) < 2) {
    respond('error', ['msg' => 'Enter first and last name separated by a space.']);
  }

  [$first, $last] = [$parts[0], $parts[1]];
  $first = trim($first);
  $last  = trim($last);

  if ($first === '' || $last === '') {
    respond('error', ['msg' => 'Enter first and last name separated by a space.']);
  }

  $formatted = "{$last}, {$first}";

  $pdo = new PdoMethods();
  $sql = "INSERT INTO names (name) VALUES (:name)";
  $bindings = [
    [':name', $formatted, 'str']
  ];
  $result = $pdo->otherBinded($sql, $bindings);

  if ($result === 'error') {
    respond('error', ['msg' => 'Database error adding name.']);
  }

  respond('success', ['msg' => "Added: {$formatted}"]);
}
catch (Throwable $e) {
  respond('error', ['msg' => 'Unexpected server error.']);
}
