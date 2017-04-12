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
    <li><a href="../index.php">Home</a></li> <!-- go to site home-->
    <li><a href="users.php">Users </a></li>
    <li><a href="register_user.php">Register User</a></li>
    </li>
    
    
    
    <div style=float:right>  <li><a href="logout.php">Logout</a></li> </div>
    
</ul>


<div style=height:65% id="inner-content">
<h2>User Registration</h2>
            
         <form action="" method="POST" value = "submit">
            <table  border="1" cellpadding="5" cellspacing="0" width="70%">
             <tr><td>ID</td>
             <td>
             <input type="number" name="ID"></td></tr>               
             <tr>
             <td>User Role</td>
             <td colspan=2><input type="radio" name="username" value="physician"> Physician
             <input type="radio" name="username" value="coordinator"> Co-ordinator</tr>
             <tr><td>First name</td>
             <td>
             <input type="text" name="FirstName"> </td></tr>
             <tr><td>Middle name</td>
             <td>
             <input type="text" name="MiddleName"> </td></tr>
             <tr><td>Last name</td>
             <td>
             <input type="text" name="LastName"> </td></tr>
             <tr><td>NIC</td>
             <td>
             <input type="number" name="NIC"> </td></tr>
             <tr><td>Date of Birth</td>
             <td>
             <input type="date" name="DateOfBirth"> </td></tr>
             <tr><td>Address</td>
             <td>
             <input type="text" name="Address"></td></tr>
             <tr><td>Contact Number</td>
             <td>
             <input type="number" name="Mobile"></td></tr>
             <tr><td>Email</td>
             <td>
             <input type="text" name="Email"></td></tr>
             <tr><td>Password</td>
             <td>
             <input type="text" name="Password"></td></tr>
             <td align="center" colspan=2>
             <input type="submit" value="submit" name="submit" id="submit">
             </td>
             </tr>
             </table>
         </form>
<?php 
    include_once('dbconnect.php');
    
        if(isset($_POST['submit'])){
            $ID =  $_POST['ID'];
            $FirstName =  $_POST['FirstName'];
            $MiddleName = $_POST['MiddleName'];
            $LastName  = $_POST['LastName'];
            $NIC =  $_POST['NIC'];
            $DateOfBirth =  $_POST['DateOfBirth'];
            $Address =  $_POST['Address'];
            $Mobile =  $_POST['Mobile'];
            $Home =  $_POST['Home'];
            $Email =  $_POST['Email'];
            $Password =  $_POST['Password'];

            if(username=='Physician'){
            
                $sql= "INSERT INTO `physician`(`PhysicianID`,`FirstName`,`MiddleName`,`LastName`,`NIC`,`DateOfBirth`,`Address`,`Mobile`,`Home`,`Email`,`Password`) VALUE ('$ID', '$username', '$FirstName','$MiddleName','$LastName', '$sname','$NIC' ,'$DateOfBirth','$Address', '$Mobile','Home', '$Email', '$Password' )";
              
            mysqli_query($con, $sql) or die(mysql_error());
            }else{
                $sql= "INSERT INTO `coordinator`(`CoordinatorID`,`FirstName`,`MiddleName`,`LastName`,`NIC`,`DateOfBirth`,`Address`,`Mobile`,`Home`,`Email`,`Password`) VALUE ('$ID', '$username', '$FirstName','$MiddleName','$LastName', '$sname','$NIC' ,'$DateOfBirth','$Address', '$Mobile','Home', '$Email', '$Password' )";
              
            mysqli_query($con, $sql) or die(mysql_error());
        }

        

}
?>
</div>
<div id="footer"><div id="footer2">
            
    </div></div>
</div>
</body>
</html>
