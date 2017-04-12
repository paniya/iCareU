<?php
/* Redirection vers une page différente du même dossier */
$extra = 'adminer-4.2.4.php';
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header("Location: http://$host$uri/$extra");
exit;
?>
