# php/displayNames.php
<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../classes/Pdo_methods.php';

function respond($status, $payload) {
  echo json_encode(array_merge(['masterstatus' => $status], $payload));
  exit;
}

try {
  $pdo = new PdoMethods();
  $sql = "SELECT id, name FROM names ORDER BY name ASC";
  $records = $pdo->selectNotBinded($sql);

  if ($records === 'error') {
    respond('error', ['msg' => 'Database error fetching names.']);
  }

  if (count($records) === 0) {
    respond('success', ['names' => 'No names to display']);
  }

  // Build Bootstrap list-group
  $html = '<ul class="list-group">';
  foreach ($records as $row) {
    $safe = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); // XSS-safe
    $html .= "<li class=\"list-group-item\">{$safe}</li>";
  }
  $html .= '</ul>';

  respond('success', ['names' => $html]);
}
catch (Throwable $e) {
  respond('error', ['msg' => 'Unexpected server error.']);
}
