<div align="center">

<?php

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
<?php
include_once('dbconnect.php');

$sql = "(SELECT `PhysicianID`, `FirstName`, `LastName`, `NIC`, `Address`, `Mobile` , `Email` FROM `physician`) UNION (SELECT `CoordinatorID`, `FirstName`, `LastName`, `NIC`, `Address`, `Mobile` , `Email` FROM `coordinator`) ";

$result= mysqli_query($con,$sql) or die(mysql_error());
$row = mysqli_num_rows($result);


	echo "<table border='1' cellspacing = '2' cellpadding = '4'> 
		<tr>
			<th>User ID</th>
			<th>Name</th>
			<th>Address</th>
			<th>Contact No</th>
			<th>E-mail </th>
			<th colspan=4>Action</th>
		</tr>";

	while($row = mysqli_fetch_array($result))
	{
	
	echo "<tr>";
	echo "<td>" . substr($row['username'], 0, 3).strval($row['ID']). "</td>";
	echo "<td>" . $row['FirstName'] . " " . $row['LastName'] ."</td>";
	echo "<td>" . $row['Address'] . "</td>";
	echo "<td>" . $row['Mobile'] . "</td>";
	echo "<td>" . $row['Email'] . "</td>";
	echo "<td><a href='edit_user.php?id=".$row['ID']."''><img src='images/icons/edit.png' height='24'/></a></td>"; 
	?>
	<td> <a href="user_delete.php" onClick = "return confirm('DO you want to DELETE this user ?');"><img src='images/icons/delete.ico' height='24'/></a></td>
	<?php
	echo "</tr>";
  }
echo "</table>";

?>

</div>
<div id="footer"><div id="footer2">
            
    </div></div>
</div>
</body>
</html>    