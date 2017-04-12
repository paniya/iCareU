<div align="center">

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

echo "<H1>Welcome Admin Panel - iCareU</H1>";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Admin Panel</title>

<link rel="stylesheet" type="text/css" href="admin.css" media="screen"/>
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css">
<link rel="stylesheet" href="css/home.css" />

<!-- common javascripts -->
	<script type="text/javascript" src="../js/jquery-1.2.6.min.js"></script>
	<script type="text/javascript" src="../js/activation.js"></script>
</head>

<body>

<br />
<br />


<ul id="navigation" class="nav-main">
	<li><a href="../icareu/index.php">Home</a></li> <!-- go to site home-->
    <li><a href="users.php">Users </a></li>
    <li><a href="register_user.php">Register User</a></li>
    </li>
    
    
    
	<div style=float:right>  <li><a href="logout.php">Logout</a></li> </div>
	
</ul>


<div style=height:65% id="inner-content">

</div>
<div id="footer"><div id="footer2">
            
    </div></div>
</div>
</body>
</html>
