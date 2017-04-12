<?php 
session_start();
error_reporting(E_ALL);
date_default_timezone_set('Asia/Colombo');
Global $con;
//$con = mysqli_connect("us-cdbr-azure-southcentral-f.cloudapp.net","bfe87d83d52bf6","4d3b3617","icareu16");
$con = mysqli_connect("localhost","root","","icareu");

//=== root folder
//$root = "/"; <=== for remote host
$root = "/wwwroot"; // <=== for local host 

$timeoutPeriod = 1800; // in seconds
function loginCheck($username, $password){
	return true;
}

function debug($string){
	$handle = fopen("debugout.txt","a");
	fwrite($handle,date('Y-m-d h:i:s A')." : ".$string."\n");
	fclose($handle);
}

function initializeName($name){
	try{
		$parts = preg_split("~ ~",$name);
		$init = '';
		for ($i = 0; $i < count($parts)-1; $i++){
			$init .= $parts[$i][0].'.';
		}		
		return $init." ".$parts[count($parts)-1];
	}
	catch (Exception $e){
		return $name;
	}
}

function shortName($name){
	$parts = preg_split("~ ~",$name);
	$sname = $parts[count($parts)-2]." ".$parts[count($parts)-1];
	return $sname;
}
?>