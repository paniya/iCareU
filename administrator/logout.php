<?php
session_start();
if(!isset($_SESSION['valid_user']) || $_SESSION['valid_user']== false)
{
	echo 'invalid user';
	$host  = $_SERVER['HTTP_HOST'];
	$url   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: http://".$host.$url);
	exit;
}

session_destroy();
$host  = $_SERVER['HTTP_HOST'];
$url   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$a = explode("/",$url);

header("Location: http://".$host."/"."adminPanel1/index.php");
exit;
?>
