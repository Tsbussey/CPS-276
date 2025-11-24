# php/clearNames.php
<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../classes/Pdo_methods.php';

function respond($status, $payload) {
  echo json_encode(array_merge(['masterstatus' => $status], $payload));
  exit;
}

try {
  $pdo = new PdoMethods();

  // Use DELETE for safety on shared hosts; then reset auto_increment.
  $del = $pdo->otherNotBinded("DELETE FROM names");
  if ($del === 'error') {
    respond('error', ['msg' => 'Database error clearing names.']);
  }

  // Optional reset (ignore error silently if lacking privilege)
  $pdo->otherNotBinded("ALTER TABLE names AUTO_INCREMENT = 1");

  respond('success', ['names' => 'No names to display']);
}
catch (Throwable $e) {
  respond('error', ['msg' => 'Unexpected server error.']);
}
