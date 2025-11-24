<?php
/* =========================================================================
   File: php/clearNames.php      (DROP-IN REPLACEMENT)
   ========================================================================= */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../classes/Pdo_methods.php';

function ok(array $payload = []) { echo json_encode(array_merge(['masterstatus' => 'success'], $payload)); exit; }
function err(string $msg)       { echo json_encode(['masterstatus' => 'error', 'msg' => $msg]); exit; }

try {
  $pdo = new PdoMethods();
  $del = $pdo->otherNotBinded("DELETE FROM names");
  if ($del === 'error') err('Database error clearing names.');

  // Optional: reset auto-increment; ignore failure if not permitted.
  $pdo->otherNotBinded("ALTER TABLE names AUTO_INCREMENT = 1");

  // Your main.js will re-fetch the list; this msg is shown under #msg
  ok(['msg' => 'All names cleared.']);
} catch (Throwable $e) {
  err('Unexpected server error.');
}
