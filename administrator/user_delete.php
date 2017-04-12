<?php
 $ID = $_GET['id'];

include_once('dbconnect.php');
$host  = $_SERVER['HTTP_HOST'];
$url   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

if(substr($row['ID'], 0, 1)==3){

$sql = "DELETE FROM physician WHERE ID ='$ID'";
mysqli_query($con,$sql) or die(mysql_error());
}else{

$sql = "DELETE FROM coordinator WHERE ID ='$ID'";
mysqli_query($con,$sql) or die(mysql_error());
}

?>
