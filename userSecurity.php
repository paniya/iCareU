<?php
include 'config.php';
error_reporting(E_ALL);
if (isset($_POST['func'])){
	if($_POST['func'] == "sendcode"){ // send the security code
		// get device from the header(save this setting in sessions)
		
		//if mobile check the validity of the user
		
		//else if web page ? check validity of the use
		
		// if ok send the email
		
		// save code related to user in database
	}
	else if($_POST['func'] == "chgpw"){ // change pw by providing old pw
		$qry = "SELECT * FROM users WHERE ID='".$_SESSION['ID']."' AND Password='".$_POST['pass']."';";
		$result=mysqli_query($con,$qry);
		if (mysqli_num_rows($result)==1){
			if($_SESSION['ID'][0]=="2"){ //if a guardian ? allow to use old password
				try {
					if($_POST['new'] == $_POST['conf'] && strlen($_POST['new']) >=6 && preg_match("~[A-Z][a-z]|[a-z][A-Z]~",$_POST['new'])){
						$qry = "UPDATE users SET Password = '".$_POST['new']."' WHERE ID = '".$_SESSION['ID']."';";
						if($con->query($qry)) echo "ok";
						else echo "sql_error :".$qry;
					}
					else echo "input_error";
				}
				catch (Exception $e){
					echo "param_error";
				}
			}
			else{ //otherwise do not, coord and physicians
				try {
					if($_POST['new'] == $_POST['conf'] && $_POST['new'] != $_POST['pass'] && strlen($_POST['new']) >=8 && preg_match("~[A-Z][a-z]|[a-z][A-Z]~",$_POST['new'])){
						$qry = "UPDATE users SET Password = '".$_POST['new']."' WHERE ID = '".$_SESSION['ID']."';";
						if($con->query($qry)) echo "Password Changed Successfully!";
						else echo "Error in Query :".$qry;
					}
					else echo "Input Field(s) does not match the required Pattern.";
				}
				catch (Exception $e){
					echo "Not Enough Parameters!";
				}
			}
		}
		else echo "Old password do not match";
	}
	else if($_POST['func'] == "restpw"){ // reset pw by providing the code
		// when adding a elder check for existing email
	}
	else echo "unknown error";
}

$to      = 'dinimz@live.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: admin@icareu.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
/*
if(mail($to, $subject, $message, $headers)){
	echo "successfull";
}
else
	echo "error";
	*/