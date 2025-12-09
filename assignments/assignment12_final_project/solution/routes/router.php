<?php
// ==============================
// file: solution/routes/router.php
// ==============================
$allowed = [
  'login'          => __DIR__ . '/../views/loginForm.php',
  'welcome'        => __DIR__ . '/../views/welcome.php',
  'addContact'     => __DIR__ . '/../views/addContactForm.php',
  'deleteContacts' => __DIR__ . '/../views/deleteContactTable.php',
  'addAdmin'       => __DIR__ . '/../views/addAdminForm.php',
  'deleteAdmins'   => __DIR__ . '/../views/deleteAdminsTable.php',
  'logout'         => __DIR__ . '/../views/logout.php',
];
$page = $_GET['page'] ?? 'login';
if (!isset($allowed[$page])) { header('Location: index.php?page=login'); exit; }

require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/navigation.php';

function render_page($title, $contentFn) { ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <?php if ($title !== 'Login') { echo nav_links(); } ?>
    <main class="mt-3"><?php $contentFn(); ?></main>
  </div>
</body>
</html>
<?php }
require $allowed[$page];
