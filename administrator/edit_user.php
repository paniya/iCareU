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
    <li><a href="?page=users">Users </a></li>
    <li><a href="register_user.php">Register User</a></li>
    </li>
    
    
    
    <div style=float:right>  <li><a href="logout.php">Logout</a></li> </div>
    
</ul>


<div style=height:65% id="inner-content">
<?php
       include_once('dbconnect.php');
            $ID = $_GET['id']; 
            $sql="SELECT * FROM `user` WHERE ID='$ID'";
            $result= mysqli_query($con, $sql);
        
 while($row = mysqli_fetch_array($result)):;?>
  

    <h2>Edit User - <?php echo $row['fname']." ".$row['sname'];?></h2>
         <form action = "" method = "POST" value = "submit">   
        
            <table  border="0" cellpadding="5" cellspacing="0" width="70%">
             <tr><td>ID</td>
             <td>
             <input type="number" name="ID" value="<?php echo $row['ID']; ?>"></td></tr>     
             <tr>
             <td>User Role</td>
             <td colspan=2><input type="radio" name="username" value="<?php echo $row['username']; ?>"> Physician
             <input type="radio" name="username" value="<?php echo $row['username'];?>"> Co-ordinator</tr>
             <tr><td>First name</td>
             <td>
             <input type="text" name="fname" value="<?php echo $row['fname']; ?>"> </td></tr>
             <tr><td>Last name</td>
             <td>
             <input type="text" name="sname" value="<?php echo $row['sname']; ?>"> </td></tr>
             <tr><td>NIC</td>
             <td>
             <input type="number" name="NIC" value="<?php echo $row['NIC']; ?>"> </td></tr>
             <tr><td>Date of Birth</td>
             <td>
             <input type="date" name="DateOfBirth" value="<?php echo $row['DateOfBirth']; ?>"> </td></tr>
             <tr><td>Address</td>
             <td>
             <input type="text" name="Address" value="<?php echo $row['Address']; ?>"></td></tr>
             <tr><td>Contact Number - Mobile</td>
             <td>
             <input type="number" name="Mobile" value="<?php echo $row['Mobile']; ?>"></td></tr>
             <tr><td>Contact Number - Home</td>
             <td>
             <input type="number" name="Home" value="<?php echo $row['Home']; ?>"></td></tr>
             <tr><td>Email</td>
             <td>
             <input type="text" name="Email" value="<?php echo $row['Email']; ?>"></td></tr>
             <td align="center" colspan=2>
             <input type="submit" value="UPDATE" name="submit" id="submit">
             </td>
             </tr>
             </table>

         </form>    
         <?php 
         $ID = $_GET['id'];
    
         if(isset($_POST['submit'])){
            $ID =  $_POST['ID'];
            $username =  $_POST['username'];
            $fname = $_POST['fname'];
            $sname  = $_POST['sname'];
            $NIC =  $_POST['NIC'];
            $DateOfBirth =  $_POST['DateOfBirth'];
            $Address =  $_POST['Address'];
            $Mobile =  $_POST['Mobile'];
            $Home =  $_POST['Home'];
            $Email =  $_POST['Email'];
            
            $sql= "UPDATE `user`(`ID`,`username`,`fname`,`sname`,`NIC`,`DateOfBirth`,`Address`,`Mobile`,`Home`,`Email`) SET (ID='$ID', username='$username', fname='$fname',sname= '$sname',NIC='$NIC' ,DateOfBirth='$DateOfBirth',Address='$Address', Mobile='$Mobile',Home='Home', Email='$Email') WHERE ID='$ID'" ; 
            mysqli_query($con, $sql);
        }


           

        endwhile;
        ?>
</div>
<div id="footer"><div id="footer2">
            
    </div></div>
</div>
</body>
</html>    