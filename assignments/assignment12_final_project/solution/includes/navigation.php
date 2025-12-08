<?php
// Minimal: keep all links visible; only remove underlines.
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function nav_links(): string {
  return implode(' &nbsp; ', [
    '<a style="text-decoration:none" href="index.php?page=addContact">Add Contact</a>',
    '<a style="text-decoration:none" href="index.php?page=deleteContacts">Delete Contact(s)</a>',
    '<a style="text-decoration:none" href="index.php?page=addAdmin">Add Admin</a>',
    '<a style="text-decoration:none" href="index.php?page=deleteAdmins">Delete Admin(s)</a>',
    '<a style="text-decoration:none" href="logout.php">Logout</a>',
  ]);
}
