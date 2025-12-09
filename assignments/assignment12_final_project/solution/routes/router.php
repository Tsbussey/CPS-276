<?php
//  var_dump($_SESSION);


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
  'logout'         => __DIR__ . '/logout.php',
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
<?php 
}
require $allowed[$page];

/*
Q2) How does the router decide which file to load from the URL?
A2) I whitelist pages in $allowed, read ?page=..., validate it,
    and then require() the mapped view. If missing/invalid → redirect to login.
    This centralizes navigation and prevents arbitrary file access.

example:

    Point at the whitelist map:

$allowed = [
  'login'          => __DIR__ . '/../views/loginForm.php',
  'welcome'        => __DIR__ . '/../views/welcome.php',
  'addContact'     => __DIR__ . '/../views/addContactForm.php',
  'deleteContacts' => __DIR__ . '/../views/deleteContactTable.php',
  'addAdmin'       => __DIR__ . '/../views/addAdminForm.php',
  'deleteAdmins'   => __DIR__ . '/../views/deleteAdminsTable.php',
  'logout'         => __DIR__ . '/../logout.php',
];

Point at URL param read + invalid redirect:

$page = $_GET['page'] ?? 'login';
if (!isset($allowed[$page])) { header('Location: index.php?page=login'); exit; }

Point at the final loader (the switchboard):

require $allowed[$page];  // loads the correct view for ?page=...

One central whitelist prevents arbitrary files; cleaner and safer than separate PHP entry points.

Q4) Why organize into routes / views / controllers / includes?
A4) Separation of concerns:
    - routes/: single entry-point, URL mapping, access guards, shared layout
    - views/: presentation only
    - controllers/: DB + business logic
    - includes/: shared helpers (security, navigation)
    This removes duplication, makes maintenance/testing easier, and improves safety.

    example: 

    Point at these includes to show separation:

require_once __DIR__ . '/../includes/security.php';   // shared helpers
require_once __DIR__ . '/../includes/navigation.php'; // nav renderer

Point at layout wrapper to show shared template:

function render_page($title, $contentFn) { ?>
  <!-- shared HTML head / bootstrap / container -->
  <?php if ($title !== 'Login') { echo nav_links(); } ?>
  <main class="mt-3"><?php $contentFn(); ?></main>
<?php }

routes = central map/guards/layout; views = presentation; controllers = DB/logic; includes = shared utilities. Avoids duplication and keeps code maintainable.

Q5) Request flow (click → response):
A5) User clicks link (e.g., index.php?page=addContact) →
    router reads ?page, validates vs $allowed, applies guards →
    defines render_page() layout →
    require()s the target view →
    view can call a controller (DB ops) →
    HTML renders inside shared layout and returns to browser.

    example: 

    URL param read & whitelist

$page = $_GET['page'] ?? 'login';
if (!isset($allowed[$page])) { header('Location: index.php?page=login'); exit; }

Shared layout wrapper

// function render_page($title, $contentFn) { /* layout & nav */ 


// Load the view

// require $allowed[$page];  // hands control to the target view


// require_once __DIR__ . '/../controllers/loginProc.php';
// $result = handle_login($_POST);   // controller does DB & session

// Click link → router picks view via $allowed → view optionally calls controller (DB) → HTML rendered through render_page().

// */