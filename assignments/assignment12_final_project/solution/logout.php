<?php
declare(strict_types=1);
session_destroy(); setcookie(session_name(),'',time()-3600); header("Location: index.php?page=login"); exit;
