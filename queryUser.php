<?php 
include "config.php";

function checkCode($code){
	return true;
}

function checkPassword($password){
	return true;
}

if ($func == "changePw"){ // change password
	if (isset($_POST['code'])){ //by code
		if(isset($_POST['new']) && isset($_POST['confirm'])){
			if($_POST['new'] == $_POST['confirm'] && checkCode($_POST['code']) && checkPassword($_POST['new']) && checkPassword($_POST['confirm'])){
				$query = "UPDATE users SET Password = '".$_POST['new']."' WHERE code = '".$_POST['code']."';";
				if($con->query($query))
					echo "success";
				else echo "error";
			}
			else echo "crderror";
		}
		else echo "crderror";
	}
	else if (isset($_POST['oldpw'])){ //by old password
		if (isset($_SESSION['ID'])){
			
		}
		else{
			echo "notlogged";
		}
	}
	else{
		echo "crderror";
	}
}