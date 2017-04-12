
<html>
<head>
<title>TimeTable</title>
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 200px;
    background-color: #f1f1f1;
}

li a {
    display: block;
    color: #000;
    padding: 8px 16px;
    text-decoration: none;
	background-color: #000066;
    color: white;
	font-size:16px;
	font-family:'Raleway',sans-serif;
	font-weight:700;
}

li a:hover{
    background-color: #555;
    color: white;
}
</style>
<meta charset="UTF-8">
	<link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="shortcut icon" href="images/favicon.png?v=1" type="image/x-icon">
	<link rel="icon" href="images/favicon.png?v=1" type="image/x-icon">
    <script type="text/javascript" src="js/javascr.js"></script>
	<!--[if lte IE 7]>
		<link rel="stylesheet" href="css/ie7.css" type="text/css" charset="utf-8">	
	<![endif]-->
</head>
<body>
<div id="header">
<div id="logo" style="float:left">
        	<img src="images/logo.png" alt="LOGO">
            
        </div>
        <div id="logo" style="float:right">
        	<img src="images/timetable.jpg" width="85" style="top:-12px; position:relative;">
        <font style="top:-60px; position:relative;color: #FFFFFF; font-family: 'Raleway',sans-serif; font-size: 18px; font-weight: 500;"><?php
echo date('Y-m-d') . '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;' . date("l");
?> </font>
        </div>
        <div id="mainMenu" style="text-align: center;font-size: 30px;font-family: Arial,Helvetica,sans-serif;margin-top: 10px;font-weight: 600;color: rgb(255,255,255);"> &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;DOCTORS' TIMETABLE</div>
        </div>
</div><br>
<div style="color: #00004d; font-family: 'Raleway',sans-serif; font-size: 18px; font-weight: 500; line-height: 32px; margin: 0 0 24px; left:250; position:relative; width:750">
The following is for guidance only to help you plan your appointment with a preferred doctor. It does not guarantee availability as the doctors may sometimes be attending to other duties. So, please make sure you call us and reserve a Date & Time after doing the online appointment. Prior to your booking check the availability of the doctor too.<hr width="750" align="left"></div>
<script type="text/javascript">
   function show(id) {

	   var e = document.getElementById(id);
	   var buttons = ["t1", "t2", "t3"];
	   for (i = 0; i < buttons.length; i++) { 
    		if(buttons[i]==id){
				e.style.display = 'block';
				}else{
					var f = document.getElementById(buttons[i])
					f.style.display = 'none';
					}
			
		}
   }
</script>
<div style="left:25; position:absolute">
<ul>
  <li><a onClick="show('t1')">SEARCH DOCTOR</a></li>
  <li><a onClick="show('t2')">TODAY'S SCHEDULE</a></li>
  <li><a onClick="show('t3')">CLOSED TIMES</a></li>
</ul></div>
			<br><div id="t1" style="display:block; left:250; position:relative; width:1000;">
			<form method="post" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]);
?>">
			<select name="doctor" style="width:175px; height:23px;">
			<?php
$con = mysqli_connect("us-cdbr-azure-southcentral-f.cloudapp.net","bfe87d83d52bf6","4d3b3617","icareu16") or die("Can't Connect to the Database.");
$sql = mysqli_query($con, "SELECT PhysicianID, Title, `Name`, LastName FROM physician");
while ($row = $sql->fetch_assoc()) {
    echo '<option value="' . $row['PhysicianID'] . '">' . $row['Title'] . ' ' . $row['Name'] . ' ' . $row['LastName'] . '</option>';
}
?>
			</select>
            &nbsp;<input type='submit' value="Search" name='submit'><br>
            </form>
            
 			
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id  = $_POST['doctor'];
    $sql = mysqli_query($con, "SELECT Title, `Name`, LastName FROM physician WHERE PhysicianID=$id");
    while ($row = $sql->fetch_assoc()) {
        $docname = $row['Title'] . ' ' . $row['Name'] . ' ' . $row['LastName'];
    }
    echo "<div style='text-align: left;font-size: 20px;font-family: Arial,Helvetica,sans-serif;margin-left: 50px;font-weight: 800;color: rgb(48, 71, 112);'>$docname's Schedule This Week</div>" . "<br>";
    $enddate = date('Y-m-d', strtotime('+ 6 day'));
    $query   = "SELECT Date, `From`, `To` FROM calender INNER JOIN physician ON physician.PhysicianID=calender.Person WHERE calender.Person='$id' AND Date >= '" . date('Y-m-d') . "' AND Date <= '$enddate' ORDER BY Date;";
    $result  = mysqli_query($con, $query);
    
    
    
    $days   = array(
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    );
    $header = '<tr>';
    foreach ($days as $x) {
        if ($x == date("l")) {
            for ($i = 0; $i < 7; $i++) {
                $header = $header . '<th width="100" style="color:white">' . date('l', strtotime('+' . $i . ' days')) . '<br>' . date('Y-m-d', strtotime('+' . $i . ' day')) . '</th>';
            }
            break;
        }
    }
    $header = $header . '</tr>';
    $data   = "<table border='0' cellpadding='12px' cellspacing='0' class='ctable'>" . $header;
    
    
    $rows = mysqli_num_rows($result);
    if ($rows == 0) {
        for ($i = 0; $i < 7; $i++) {
            $appdate    = date('Y-m-d', strtotime('+' . $i . ' days'));
            $appoNumber = patientnumber($appdate, $id);
            $data       = $data . '<td><center>' . "Available " . "<br>" . "Patient No: " . $appoNumber . '</center></td>';
            
        }
        //echo "<div style='text-align: center;font-size: 15px;font-family: Arial,Helvetica,sans-serif;font-weight: 500;color: rgb(192, 17, 17);'>"."No Records Found!"."</div>";
    }
    for ($j = 0; $j < $rows; $j++) {
        mysqli_data_seek($result, $j);
        $record = mysqli_fetch_assoc($result);
        $data   = $data . '<tr>';
        
        for ($i = 0; $i < 7; $i++) {
            if ($record['Date'] == date('Y-m-d', strtotime('+' . $i . ' day'))) {
                $data = $data . '<td><center>' . $record['From'] . '-' . $record['To'] . '<br>' . "Not Available" . '</center></td>';
            } else {
                //if($j==0){$data = $data.'<td><center>'."Available".'</center></td>';}
                //else{$data = $data.'<td><center>'." ".'</center></td>';}
                $data = $data . '<td bgcolor="#d9ffcc"><center>' . " " . '</center></td>';
            }
        }
        $data = $data . '</tr>';
    }
    $data = $data . '<tr>';
    if ($rows != 0) {
        for ($i = 0; $i < 7; $i++) {
            
            $appdate    = date('Y-m-d', strtotime('+' . $i . ' day'));
            $appoNumber = patientnumber($appdate, $id);
            $data       = $data . '<td><center>' . "Patient No: " . $appoNumber . '</center></td>';
            
        }
    }
    $data = $data . '</tr>';
    $data = $data . '</table>';
    echo "<div align='centre'>" . $data . "</div>";
    echo "<br>*Please refer to our closed times as well.<br><br><br><br>";
    
}

?></div>
<div id="t2" style="display:none; left:250; position:relative; width:1000;">
<?php

echo "<div style='text-align: left;font-size: 20px;font-family: Arial,Helvetica,sans-serif;margin-left: 20px;font-weight: 500;color: rgb(48, 71, 112);'>This is our schedule Today.</div>" . "<br>";
?>

</div>

<div id="t3" style="display:none; left:250; position:relative; width:1000;">
<?php

echo "<div style='text-align: left;font-size: 20px;font-family: Arial,Helvetica,sans-serif;margin-left: 20px;font-weight: 500;color: rgb(48, 71, 112);'>Please note that we are closed on following dates and times.</div>" . "<br>";
$query  = "SELECT Date, `From`, `To` FROM calender WHERE Person='general' AND Date >= '" . date('Y-m-d') . "' ORDER BY Date;";
$result = mysqli_query($con, $query);

$header = '<tr><th width="100" style="color:white">' . 'Date' . '</th><th width="100" style="color:white">' . 'From' . '</th><th width="100" style="color:white">' . 'To' . '</th></tr>';
$data   = "<table border='0' cellpadding='12px' cellspacing='0' class='ctable' align='left'>" . $header;


$rows = mysqli_num_rows($result);
if ($rows == 0) {
    
}
for ($j = 0; $j < $rows; $j++) {
    mysqli_data_seek($result, $j);
    $record = mysqli_fetch_assoc($result);
    $data   = $data . '<tr><td bgcolor="#ffb3b3"><center>' . $record['Date'] . '</center></td>';
    $data   = $data . '<td><center>' . $record['From'] . '</center></td>';
    $data   = $data . '<td><center>' . $record['To'] . '</center></td></tr>';
}
$data = $data . '</table>';
echo "<div align='center'>" . $data . "</div>";



?>
<?php
function patientnumber($appdate, $phyid)
{
    $con = mysqli_connect("us-cdbr-azure-southcentral-f.cloudapp.net","bfe87d83d52bf6","4d3b3617","icareu16") or die("Can't Connect to the Database.");
    $sql3 = "SELECT PhysicianID FROM appointment WHERE PhysicianID='$phyid' AND AppointmentDate='$appdate';";
    
    $result = mysqli_query($con, $sql3);
    $rows   = mysqli_num_rows($result);
    return $rows + 1;
}

?>
</div>
<div id="footerBottom" style="position:fixed;bottom:6;left:6;right:6;">Copyright © 2016 all rights reserved</div>
</body>
</html>

