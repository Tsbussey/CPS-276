<?php
declare(strict_types=1);
function nav_links(): string {
  $links = [];
  $status = $_SESSION['user']['status'] ?? null;
  if ($status) {
    $links[] = ['Add Contact', 'addContact'];
    $links[] = ['Delete Contact(s)', 'deleteContacts'];
    if ($status === 'admin') { $links[] = ['Add Admin', 'addAdmin']; $links[] = ['Delete Admin(s)', 'deleteAdmins']; }
    $links[] = ['Logout', 'logout'];
  }
  ob_start(); ?>
  <nav class="mb-3">
    <?php foreach ($links as [$label,$page]): ?>
      <a class="me-3" href="index.php?page=<?= $page ?>"><?= htmlspecialchars($label) ?></a>
    <?php endforeach; ?>
  </nav>
  <?php return ob_get_clean();
}
