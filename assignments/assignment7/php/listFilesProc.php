<?php
declare(strict_types=1);

$output = '';
require_once __DIR__ . '/../classes/Pdo_methods.php';

try {
  $pdo = new PdoMethods();
  $rows = $pdo->select("SELECT id, display_name, file_path FROM documents ORDER BY id DESC", []);
  if ($rows === 'error') {
    $output = '<p class="empty">Could not retrieve files.</p>';
    return;
  }
  if (!$rows) {
    $output = '<p class="empty">No files uploaded yet.</p>';
    return;
  }
  $lis = array_map(function($r){
    $name = htmlspecialchars($r['display_name'], ENT_QUOTES, 'UTF-8');
    $href = htmlspecialchars($r['file_path'], ENT_QUOTES, 'UTF-8');
    return '<li><a href="'.$href.'" target="_blank" rel="noopener">'.$name.'</a></li>';
  }, $rows);
  $output = '<ul>'.implode('', $lis).'</ul>';
} catch (Throwable $t) {
  $output = '<p class="empty">Unexpected error.</p>';
}
