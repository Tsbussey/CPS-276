<?php
// Minimal: keep all links visible; only remove underlines.
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function nav_links(): string {
  
  if ($_SESSION['role']=='admin') {

    // Admin view (role-driven menu)
  return implode(' &nbsp; ', [
    '<a style="text-decoration:none" href="index.php?page=addContact">Add Contact</a>',
    '<a style="text-decoration:none" href="index.php?page=deleteContacts">Delete Contact(s)</a>',
    '<a style="text-decoration:none" href="index.php?page=addAdmin">Add Admin</a>',
    '<a style="text-decoration:none" href="index.php?page=deleteAdmins">Delete Admin(s)</a>',
    '<a style="text-decoration:none" href="logout.php">Logout</a>',
  ]);
    // Staff view (role-driven menu)
}
elseif ($_SESSION['role']=='staff'){ 
  return implode(' &nbsp; ', [
    '<a style="text-decoration:none" href="index.php?page=addContact">Add Contact</a>',
    '<a style="text-decoration:none" href="index.php?page=deleteContacts">Delete Contact(s)</a>',
    '<a style="text-decoration:none" href="logout.php">Logout</a>',
  ]);


}
}

/*
Q1) How did you implement different navigation menus or page access based on user roles (e.g., staff vs admin)?
    What security considerations are important?

A1) I read the role from the session and render links conditionally:
    - Staff: Add Contact, Delete Contact(s), Logout
    - Admin: Staff links + Add Admin, Delete Admin(s)

    SECURITY: The navbar is only UX. Actual access control is enforced on the server
    (see router guard in routes/router.php) so a staff user can’t reach admin pages via URL.

    example code: 

    Point at this conditional (admin menu):

    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
  return implode(' &nbsp; ', [
    '<a ... href="index.php?page=addContact">Add Contact</a>',
    '<a ... href="index.php?page=deleteContacts">Delete Contact(s)</a>',
    '<a ... href="index.php?page=addAdmin">Add Admin</a>',       // admin-only
    '<a ... href="index.php?page=deleteAdmins">Delete Admin(s)</a>', // admin-only
    '<a ... href="logout.php">Logout</a>',
  ]);
}

Then point at the staff menu:

if (isset($_SESSION['role']) && $_SESSION['role'] === 'staff') {
  return implode(' &nbsp; ', [
    '<a ... href="index.php?page=addContact">Add Contact</a>',
    '<a ... href="index.php?page=deleteContacts">Delete Contact(s)</a>',
    '<a ... href="logout.php">Logout</a>',
  ]);
}

*/