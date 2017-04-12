<?php 
session_start();
$user = $_POST['user'];
$pass = $_POST['pass'];
$handle = fopen("test.txt","w");
$wstr="";
$con = mysqli_connect("br-cdbr-azure-south-b.cloudapp.net","b4c9e4de10ed39","ac9164be","icareu");
if (mysqli_connect_errno()){
	$wstr = mysqli_connect_error();
}
mysqli_select_db($con,"icareu");
$qry = "SELECT * FROM coordinator WHERE userid = '".$user."' AND password = '".$pass."'";
$result = mysqli_query($con,$qry);
$rows = mysqli_num_rows($result);
$wstr= $wstr.$rows+"\n";
$wstr= $wstr.$qry;
if ($rows == 1){
	$record = mysqli_fetch_assoc($result);
	$_SESSION['user']=$record['Name'];
	echo $record['Name'];
}
else echo "invalid";
fwrite($handle,$wstr);
fclose($handle);
?>
	