<?php
session_start();
$host  = $_SERVER['HTTP_HOST'];
$url   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	
	
if(!isset($_SESSION['valid_user'])||!isset($_POST['user_name']) )
{
	echo 'invalid user';
	
	header("Location: http://".$host.$url);
	exit;

}

echo $_SESSION['valid_user'] . "<br>";
include_once('dbconnect.php');

$user_name = $_POST['user_name'];
$password = $_POST['password'];

$sql = "select userId from adminuser WHERE user_name = '".$user_name."' and password = '" . $password."'";
echo $sql;

$res = mysqli_query($con,$sql) or die(mysql_error());
$row = mysqli_fetch_array($res);
echo $row;


if(!$row)
{
	
	$_SESSION['incorrect_comb'] = true;
	header("Location: http://".$host.$url);
	exit;
		
}
else 
{
	$_SESSION['valid_user'] = true;	
	header("Location: http://".$host.$url."/admin_panel.php");
	exit;
	
}
	
?>
