<?php
// /views/addAdminForm.php
// Removes visual stars; preserves required validation and Bootstrap styling.

require_once __DIR__ . '/../includes/security.php';
if (!userIsAdmin()) { header('Location: index.php?page=login'); exit; }

$sticky = $sticky ?? [
  'fname' => '', 'lname' => '', 'email' => '', 'status' => '', 'message' => '',
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* WHY: Some templates inject "*" via CSS. Neutralize them. */
    label.required::after,
    .form-label.required::after,
    [data-required="true"]::after { content: none !important; }
  </style>
</head>
<body class="p-4">
  <div class="container">
    <nav class="mb-3">
      <a class="me-3" href="index.php?page=addContact">Add Contact</a>
      <a class="me-3" href="index.php?page=deleteContacts">Delete Contact(s)</a>
      <a class="me-3 fw-bold" href="index.php?page=addAdmin">Add Admin</a>
      <a class="me-3" href="index.php?page=deleteAdmins">Delete Admin(s)</a>
      <a href="index.php?page=logout">Logout</a>
    </nav>

    <h2 class="mb-3">Add Admin</h2>

    <?php if (!empty($sticky['message'])): ?>
      <div class="alert alert-info"><?= htmlspecialchars($sticky['message']) ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?page=addAdmin" novalidate>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="fname" class="form-label">First Name</label>
          <input id="fname" name="fname" type="text" class="form-control" required
                 value="<?= htmlspecialchars($sticky['fname']) ?>">
        </div>
        <div class="col-md-6">
          <label for="lname" class="form-label">Last Name</label>
          <input id="lname" name="lname" type="text" class="form-control" required
                 value="<?= htmlspecialchars($sticky['lname']) ?>">
        </div>

        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <input id="email" name="email" type="email" class="form-control" required
                 value="<?= htmlspecialchars($sticky['email']) ?>">
        </div>
        <div class="col-md-6">
          <label for="password" class="form-label">Password</label>
          <input id="password" name="password" type="password" class="form-control" required>
        </div>

        <div class="col-md-6">
          <label for="status" class="form-label">Status</label>
          <select id="status" name="status" class="form-select" required>
            <option value="" <?= $sticky['status']===''?'selected':''; ?> disabled>Please Select a Status</option>
            <option value="staff" <?= $sticky['status']==='staff'?'selected':''; ?>>staff</option>
            <option value="admin" <?= $sticky['status']==='admin'?'selected':''; ?>>admin</option>
          </select>
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary">Add Admin</button>
        </div>
      </div>
    </form>
  </div>
</body>
</html>
