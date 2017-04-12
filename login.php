<?php 
include "config.php";
$func = $_POST['func'];
if ($func == "login"){
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$handle = fopen("test.txt","w");
	$wstr="";
	if (mysqli_connect_errno()){
		$wstr = $wstr."Error :".mysqli_connect_error();
	}
	mysqli_select_db($con,"icareu");
	$qry = 'SELECT * FROM users WHERE UserID = \''.$user.'\' AND Password = \''.$pass.'\'';
	$wstr= $wstr.$qry;
	$result = mysqli_query($con,$qry);
	$rows = mysqli_num_rows($result);
	$wstr= $wstr.$rows."\r\n";
	if ($rows == 1){ // Gauardian Login
		$record = mysqli_fetch_assoc($result);
		$wstr= $wstr.$record['ID'];
		if ($record['ID'][0] == '2') {
			if (isset($_POST['device'])){
				if ($_POST['device']=='mobile'){
					$_SESSION['ID'] = $record['ID'];
					$_SESSION['ElderID'] = 10162530246;
					echo $_SESSION['ID'];
				}
			}
			else echo "unauthorized";
		}
				
		else if ($record['ID'][0] == '0') echo "Admin";
		else{ 
			$_SESSION['ID'] = $record['ID'];
			$_SESSION['Type'] = $record['ID'][0];
			$xml = simplexml_load_file("Headers.xml") or die("Error: Cannot create object");
			$headers = '';
			if ($record['ID'][0] == '1') {
				$qry = 'SELECT * FROM physician WHERE PhysicianID = \''.$record['ID'].'\'';
				$text =  readfile('physician.htm');
				foreach($xml->physician->children() as $listItem){
					$headers = $headers.$listItem;
				}
			}
			else if ($record['ID'][0] == '3'){
				$qry = 'SELECT * FROM coordinator WHERE CoordinatorID = \''.$record['ID'].'\'';
				$text =  readfile('coordinator.htm');
				foreach($xml->coordinator->children() as $listItem){
					$headers = $headers.$listItem;
				}
			}
			$result = mysqli_query($con,$qry);
			$record = mysqli_fetch_assoc($result);
			$_SESSION['Name'] = $record['LastName'];
			$_SESSION['InitName'] = initializeName($record['FirstName']." ".$record['MiddleName']." ".$record['LastName']);
			$text = '<div id="current-user" style="display:none">'.$record['LastName'].'</div>'.$text;
			$text = '<div id="header-file" style="display:none">'.$headers.'</div>'.$text;
			echo $text;
		}
		
	}
	else echo "invalid";
	fwrite($handle,$wstr);
	fclose($handle);
}

else if ($func == "logout"){
	session_unset();
	session_destroy();
	$xml = simplexml_load_file("Headers.xml") or die("Error: Cannot create object");
	$headers = '';
	foreach($xml->front->children() as $listItem){
		$headers = $headers.$listItem;
	}
	$text = '<div id="header-file" style="display:none">'.$headers.'</div>';
	$cont = readfile('front.htm');
	$text = $text.$cont;
	echo $text;		
}
?>
	