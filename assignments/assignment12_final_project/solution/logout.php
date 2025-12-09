<?php
declare(strict_types=1);

echo 'Logging OUt';
session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(),'',0,'/');
//session_regenerate_id(true);

// session_destroy(); setcookie(session_name(),'',time()-3600); header("Location: index.php?page=login"); exit;
setcookie(session_name(),'',time()-3600); header("Location: index.php?page=login"); exit;





