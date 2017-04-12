<?php
session_start();
$_SESSION['valid_user'] = false;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Login</title>

<link href="login-box.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div style="padding: 100px 0 0 100px;" align="center">


<div id="login-box" >

<H2>Login</H2>
Wellcome to Admin Panel
<br />
<?php
if(isset($_SESSION['incorrect_comb']))
{
	if($_SESSION['incorrect_comb'] == true)
	{
		echo '<br><p forecolor="red">Incorrect User Name - Password Combination </p> ';
		unset($_SESSION['incorrect_comb']);
	//session_destroy();
	}
}
?>
<form id="login-form" action="login.php" method="post">
<div id="login-box-name" style="margin-top:20px;">
	User Name:</div>
	<div id="login-box-field" style="margin-top:20px;">
	<input name="user_name" class="form-login" title="Username" value="" size="30" maxlength="2048" />
	</div>
<div id="login-box-name">
Password:</div>
<div id="login-box-field">
<input name="password" type="password" class="form-login" title="Password" value="" size="30" maxlength="2048" /></div>
<br />

<br />
<br />
<input type="image" value="submit" src="images/login-btn.png" width="103" height="42" style="margin-left:90px;" />
</form>





</div>

</div>













</body>
</html>
