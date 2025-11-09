<?php
require_once __DIR__ . '/bootstrap.php';

$output = '<div class="card p-3"><ul class="list-group list-group-flush">';
try {
  $pdo  = new PdoMethods();
  $rows = $pdo->selectBinded('SELECT id, file_name, file_path, created_at FROM documents ORDER BY created_at DESC');

  if (!$rows) {
    $output .= '<li class="list-group-item">No files uploaded yet.</li>';
  } else {
    foreach ($rows as $r) {
      $name = htmlspecialchars($r['file_name']);
      $href = htmlspecialchars($r['file_path']);
      $output .= "<li class='list-group-item d-flex justify-content-between align-items-center'>
                    <a href='{$href}' target='_blank' rel='noopener noreferrer'>{$name}</a>
                    <span class='text-muted small'>ID {$r['id']}</span>
                  </li>";
    }
  }
} catch (Throwable $e) {
  $output .= '<li class="list-group-item text-danger">' . htmlspecialchars($e->getMessage()) . '</li>';
}
$output .= '</ul></div>';