<?php 
include "config.php";
$text = "";
$logininfo = "";
$profileType = "";
if(isset($_POST['login_user']) || isset($_POST['login_pass'])){
	$user = $_POST['login_user'];
	$pass = $_POST['login_pass'];
	$handle = fopen("test.txt","w");
	$wstr="";

	if (mysqli_connect_errno()){
		$wstr = $wstr."Error :".mysqli_connect_error();
	}
	$qry = 'SELECT * FROM users WHERE UserID = \''.$user.'\' AND Password = \''.$pass.'\'';
	$wstr= $wstr.$qry;
	$result = mysqli_query($con,$qry);
	$rows = mysqli_num_rows($result);
	$wstr= $wstr.$rows."\r\n";
	if ($rows == 1){
		$record = mysqli_fetch_assoc($result);
		$wstr= $wstr.$record['ID'];
		if ($record['ID'][0] == '2') { //Guardian
			$logininfo = "unauthorized login!";
		}
				
		else if ($record['ID'][0] == '0') echo "Admin";
		else{
			$logininfo = ""; 
			$_SESSION['ID'] = $record['ID'];
			$_SESSION['Type'] = $record['ID'][0]; //first letter
			$xml = simplexml_load_file("Headers.xml") or die("Error: Cannot create object");
			$headers = '';
			if ($record['ID'][0] == '5') {
				$qry = 'SELECT CONCAT(Title,\'. \',LastName) AS LastName FROM physician WHERE PhysicianID = \''.$record['ID'].'\'';
				$jsfile = "<script type='text/javascript' src='js/physician.js'></script>";
				$text = $jsfile.$text;
			}
			else if ($record['ID'][0] == '3'){
				$qry = 'SELECT * FROM coordinator WHERE CoordinatorID = \''.$record['ID'].'\'';
				$jsfile = "<script type='text/javascript' src='js/coordinator.js'></script>";
				$text = $jsfile.$text;
			}
			$result = mysqli_query($con,$qry);
			$record = mysqli_fetch_assoc($result);
			$_SESSION['Name'] = $record['LastName'];
			if($_SESSION['ID'][0] == "3") $_SESSION['InitName'] = initializeName($record['FirstName']." ".$record['MiddleName']." ".$record['LastName']);
			//$text = '<div id="current-user" style="display:none">'.$record['LastName'].'</div>'.$text;
		}
	}
	else $logininfo="Invalid username or password!";
	fwrite($handle,$wstr);
	fclose($handle);
}
else{
	if(isset($_POST['status'])){
		session_unset();
		session_destroy();
	}
}
		?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>iCareU</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="shortcut icon" href="images/favicon.png?v=1" type="image/x-icon">
	<link rel="icon" href="images/favicon.png?v=1" type="image/x-icon">
    <script type="text/javascript" src="js/javascr.js"></script>
	<!--[if lte IE 7]>
		<link rel="stylesheet" href="css/ie7.css" type="text/css" charset="utf-8">	
	<![endif]-->
</head>
<?php if(isset($_SESSION['ID'])) echo "<body>";
else echo '<body onLoad="self.focus(); document.loginForm.login_user.focus();">'; ?>
    <div id="header"> <!-- Header Tag ----------------------------->
        <div id="logo" style="float:left">
        	<img src="images/logo.png" alt="LOGO">
        </div>
        <div id="mainMenu">
        <ul>
        	<?php 
			$xml = simplexml_load_file("Headers.xml") or die("Error: Cannot create object");
			if(isset($_SESSION['ID'])){
				if ($_SESSION['Type'] == '5'){ // physician
					$profileType = "physician";
					foreach($xml->physician->children() as $listItem){
						echo $listItem;
					}
				}
				else if ($_SESSION['Type'] == '3'){ // coordinator
					$profileType = "coordinator";
					foreach($xml->coordinator->children() as $listItem){
						echo $listItem;
					}
				}
			}
            else{
				$profileType = "front";
				foreach($xml->front->children() as $listItem){
					echo $listItem;
				}
			}
            
			?>
            </ul>
        </div>	
        <div id="login">
                <?php 
						echo '<form name="loginForm" id="loginForm" action="" method="post" style="margin-top:13px;">&nbsp;';
					if(isset($_SESSION['ID'])){  
                        if ($_SESSION['ID'] != "") echo "<label>Hi,&nbsp;".trim($_SESSION['Name']).' </label>';
                        else echo "<label>Hi,&nbsp;". 'no data'.' </label>';
                        echo '<input type="submit" value="Sign out" name="signout">';
						echo '<input type="hidden" value="logged" name="status"></form>';}
                    else{
                        echo '<input type="text" style="padding-left:5px;" value="" tabindex="1" name="login_user" required placeholder="User Name">&nbsp;';
                        echo '<input type="password" style="padding-left:5px;" value="" tabindex="2" name="login_pass" required placeholder="Password" onKeyPress="GetChar(event,this);">&nbsp;';
                        echo '<input type="submit" value="Sign in" tabindex="3" name="signin">';
						//echo '<br><a href="forgotcre.php">I forgot my credidentials..</a>';
						if ($logininfo != "") echo "<br> <script> alert(\"".$logininfo.'");</script><a id="forgotlink" href="forgotcre.php">I forgot my credidentials..</a>';
                        echo '</form>';}
                ?>
                        
        </div> <!-- Login Close -->
        <div id="frontPhysicianList" style="display:none;">
               <?php 
			  	$qry = 'SELECT * FROM physician;';
				$result = mysqli_query($con,$qry);
				$rows = mysqli_num_rows($result);
				$innerData = "";//'<option value="-1" disabled selected hidden>Physician</option>';
				if ($rows > 0){
					for($i = 0 ; $i < $rows ; $i++){
						mysqli_data_seek($result,$i);
						$record = mysqli_fetch_assoc($result);
						$innerData= $innerData.'<option value="'.$record['PhysicianID'].'">'.$record['Title'].". ".$record['Name']." ".$record['LastName'].'</option>';
					}
					echo $innerData;
				} ?>
        </div> 
	</div> <!-- /#header -->
    <!-- content tag ------------------------------------------------------>
    <div id="content">
    	 <?php
			 $handle = fopen($profileType.".htm",'r');
			 $jsfile = "<script type='text/javascript' src='js/".$profileType.".js'></script>";
			 echo $jsfile.fread($handle,filesize($profileType.".htm"));
			 fclose($handle);
		 ?>
    </div> 
    <!-- Footer tag ------------------------------------------------------>
    <div id="footer" <?php if(isset($_SESSION['ID'])) echo " style='display:none;'"; ?>>
    	<br>
    	<img src="images/logo.png"><br>
      <table id="footerContactBox" style="float:left">
            <tr><td width="50"><img src="images/footerphone.png"></td><td>+94 77 0707 608</td></tr>
            <tr><td><img src="images/footeremail.png"></td><td><a href="mailto:icareu@gmail.com">icareu@gmail.com</a></td></tr>
            <tr><td><img src="images/footerfacebook.png"></td><td><a href="http://www.facebook.com/icareu" target="_blank">www.facebook.com/icareu</a></td></tr>
            <tr><td><img src="images/footerpost.png"></td><td><a href="https://www.google.lk/maps/place/75+Galle+Rd,+Colombo+00300" target="_blank">#75, Galle Road, Colombo 03, Sri Lanka</a></td></tr>
        </table>
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.766584536877!2d79.84518471477291!3d6.9184847950011505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25940b90eca33%3A0x66d2b7533996f04!2s75+Galle+Rd%2C+Colombo+00300!5e0!3m2!1sen!2slk!4v1484052163907" width="350" height="200" frameborder="0" style="border:0;margin-left:20px;" allowfullscreen></iframe>
    </div>
    <div id="footerBottom">
    Copyright © 2016 all rights reserved
    </div>
    
</body>
</html>