<?php 
Global $con;
include "config.php";
$function = $_POST['func'];
$arg = '';
if(isset($_POST['arg'])) $arg = $_POST['arg'];
function getDateSerial($type){
	$monthList = array(31,29,31,30,31,30,31,31,30,31,30,31);
	$year = date("Y");
	$month = date("m");
	$day = date("d");
	$daySerial = 0;
	for( $i = 0; $i <$month-1;$i++){
		$daySerial = $daySerial + $monthList[$i];
	}
	$daySerial = $daySerial+ $day;
	$serial = substr($year,1);
	$serial =$type.$serial.$daySerial;
	return $serial;
}

function getCheckDigit($IDserial){
	$digit = $IDserial;
	while(mb_strlen($digit) > 1){
		$thisVal = 0;
		for($i = 0; $i < mb_strlen($digit);$i++){
			$thisVal = $thisVal + (int)substr($digit,$i,1);
		}
		$digit = (string)$thisVal;
	}
	return $IDserial.$digit;
}

function getNextID($type){
	Global $con;
	$serial = "";
	$lastRecord = "";
	$lastDevice = "";
	$newDevice ="";
	//Get latest Device registered
	if($type=="elder"){
		$serial = getDateSerial(1);
		$query = "SELECT * FROM elder WHERE ElderID LIKE '".$serial."%' ORDER BY ElderID DESC;" ;
		$result = mysqli_query($con,$query);
		mysqli_data_seek($result,0);	
		$record = mysqli_fetch_assoc($result);
		$rows = mysqli_num_rows($result);
		if ($rows != 0){
			$lastDevice =$record['ElderID'];
		}
		else $lastDevice = "0";
	}
	else if($type=="guardian"){
		$serial = getDateSerial(2);
		$query = "SELECT * FROM guardian WHERE GuardianID LIKE '".$serial."%' ORDER BY GuardianID DESC;" ;
		$result = mysqli_query($con,$query);
		mysqli_data_seek($result,0);	
		$record = mysqli_fetch_assoc($result);
		$rows = mysqli_num_rows($result);
		if ($rows != 0){
			$lastDevice =$record['GuardianID'];
		}
		else $lastDevice = "0";
	}
	if($lastDevice == "0"){
		$serial = getCheckDigit($serial."000"); // OK
	}
	else{
		$serial = getCheckDigit(substr($lastDevice,0,10)+1);
	}
	return $serial; // return serial
}

function getTotalTablets($dose, $pattern, $days, $em) {
	if($em){
		return $days;
	}
	else{
		$pat =0;
		for($i = 0; $i < 3; $i++){
			$pat += $pattern[$i];
		}
		return $dose*$pat*$days;
	}
}
if ($function == "findElder"){//load elders table when searched
	$query = "SELECT FirstName,MiddleName,LastName,ElderID,NIC FROM elder WHERE CONCAT_WS(' ',FirstName,MiddleName,LastName) LIKE '%".$arg."%' OR ElderID LIKE '%".$arg."%' OR NIC LIKE '%".$arg."%'" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){
		echo "No records found!";
	}
	else{
		$data='<table class="ctable" border="0" cellpadding="5px" cellspacing="0">
		<tr><th width="100px">ID</th>';
		$data=$data.'<th width="100px">NIC</th>';
		$data=$data.'<th>Name</th></tr>';
		$sty ="";
		for ($i = 0;$i < $rows; $i++){
			mysqli_data_seek($result,$i);	
			$record = mysqli_fetch_assoc($result);
			$data= $data.'<tr  onClick="viewElderDetails(\''.$record['ElderID'].'\');" style="cursor:pointer;"><td>'.$record["ElderID"].'</td>';
			$data= $data.'<td>'.$record["NIC"].'</td>';
			$data= $data.'<td>'.$record["FirstName"].' '.$record["MiddleName"].' '.$record["LastName"].'</td></tr>';
		}
		echo $data.'</table>';
	}
}

else if ($function == "getElder"){ //get elder details from database
	$query = "SELECT * FROM elder WHERE ElderID='".$arg."'" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){
		echo "No records found!";
		$_SESSION['elderID'] = "";
	}
	else{
		$_SESSION['elderID'] = $arg;
		$topics=array("ElderID"=>"Elder ID","FirstName"=>"Full Name","DateOfBirth"=>"Date of Birth","Gender"=>"Gender","NIC"=>"NIC","Allergies"=>"Allergies","Height"=>"Height","Weight"=>"Weight","Address"=>"Address","ContactNumber"=>"Contact");
		$data='<table>';
		$record = mysqli_fetch_assoc($result);
		$pic="";
		if(file_exists(sprintf("%s/profile/%s",dirname(__FILE__),$arg.'.jpg'))){
			$image = sprintf("%s/profile/%s",dirname(__FILE__),$arg.'.jpg');
			$imgData = base64_encode(file_get_contents($image));
			$pic = 'data:'.mime_content_type($image).';base64,'.$imgData;
		}
		else $pic = "images/propic.png";
		foreach($topics as $key=>$value){
			$data= $data.'<tr><td width="100px">'.$topics[$key].'</td>';
			if($key=="FirstName"){
				$data= $data.'<td><label name="elderDetailsView">'.$record[$key].' '.$record['MiddleName'].' '.$record['LastName'].'</label></td></tr>';
			}
			else if($key=="ElderID"){
				$data= $data.'<td><label name="elderDetailsView">'.$record[$key].'</label></td><td rowspan="11" valign="top"><img style="margin-left:75px;"src="'.$pic.'" width="200" height="200" ></td></tr>';
			}
			else $data= $data.'<td><label name="elderDetailsView">'.$record[$key].'</label></td></tr>';
		}
		//control buttons (Edit, Delete)
		//<input type="button" value="Edit Details" onClick="viewEditElderTab();">&nbsp;
		$data= $data.'</table><br><div id="elderDetailsControls"><input type="button" value="Remove Elder" onClick="viewRemoveElderTab();"><div>';
		$data= $data.'<div id="GuardianIDx" style="display:none;">'.$record['GuardianID'].'</div>';
		$data= $data.'<div id="ElderIDx" style="display:none;">'.$record['ElderID'].'</div>';
		$data= $data.'<div id="ElderNamex" style="display:none;">'.$record['FirstName'].' '.$record['MiddleName'].' '.$record['LastName'].'</div>';
		echo $data.'<br><br>';
	}
}

else if ($function == "addElder"){ //
	//arg is elder query
	$arg1 = $_POST['arg1']; //guardian id
	$arg2 = $_POST['arg2']; //guardian query
	$query="";
	$elderSerial = getNextID("elder");
	$guardianSerial = "";
	if ($arg1 == ""){
		$guardianSerial = getNextID("guardian");
		$query1 = "INSERT INTO guardian(GuardianID,Name,NIC,Gender,Mobile,Address) VALUES ('".$guardianSerial."',".$arg2.");" ; // insert in to Guardian
		$query2 = "INSERT INTO elder(ElderID,FirstName,MiddleName,LastName,DateOfBirth,NIC,Gender,Address,ContactNumber,Height,Weight,Allergies,GuardianID) VALUES ('".$elderSerial."',".$arg.",'".$guardianSerial."');" ; // insert in to Elder
		if($con->query($query1)){
			if($con->query($query2)){
				if(isset($_FILES['img'])){
					$filex = $_FILES['img']['tmp_name'];
					$elderN = preg_split("~'~",$query2);
					$tofile = sprintf("%s/profile/%s",dirname(__FILE__),$elderSerial.'.jpg');
					move_uploaded_file($filex,$tofile);
				}
				echo "ok".$elderSerial;
			}
			else echo "Error adding Elder. Guardian Added with GuardianID '".$guardianSerial."'.";
		}
		else {echo "Error adding Guardian.";
		echo $query1."\n".$query2;}
	}
	else {
		$query2 = "INSERT INTO elder(ElderID,FirstName,MiddleName,LastName,DateOfBirth,NIC,Gender,Address,ContactNumber,Height,Weight,Allergies,GuardianID) VALUES ('".$elderSerial."',".$arg.",'".$arg1."');" ; // insert in to Elder
		if(isset($_FILES['img'])){
			$filex = $_FILES['img']['tmp_name'];
			$elderN = preg_split("~'~",$query2);
			$tofile = sprintf("%s/profile/%s",dirname(__FILE__),$elderSerial.'.jpg');
			move_uploaded_file($filex,$tofile);
		}
		if($con->query($query2)){
			echo "ok".$elderSerial;
		}
		else{
			echo "Error adding Elder.\n".mysqli_error($con);
		}
		
	}
	//$con->query($query);
}

else if ($function == "getReports"){
	$query = "SELECT * FROM report WHERE ElderID='".$_SESSION['elderID']."'" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){
		echo "<br>No reports found!";
	}
	else{
		$data='<table class="ctable" border="0" cellpadding="5px" cellspacing="0">
		<tr><th width="70px">Date</th>';
		$data=$data.'<th width="300px">Report Name</th>';
		$data=$data.'<th width="70px">Status</th></tr>';
		for ($i = 0;$i < $rows; $i++){
			mysqli_data_seek($result,$i);	
			$record = mysqli_fetch_assoc($result);
			$data= $data.'<tr onClick="openElderReport(\''.$record["File"].'\');" style="cursor:pointer;"><td>'.$record["Date"].'</td>';
			$data= $data.'<td>'.$record["Name"].'</td>';
			$data= $data.'<td>'.$record["Status"].'</td></tr>';
		}
		echo $data.'</table>';
	}
}

else if ($function == "addReports"){
	$filex = $_FILES['afile']['tmp_name'];
	$shafile = sha1_file($filex).'.pdf';
	$tofile = sprintf("%s/reports/%s",dirname(__FILE__),$shafile);
	move_uploaded_file($filex,$tofile);
	$query = "INSERT INTO `report`(`Date`, `ElderID`, `Name`, `Status`, `File`) VALUES ('".date('Y-m-d')."','".$_SESSION['elderID']."','".$_POST['desc']."','".$_POST['status']."','".$shafile."')";
	$result = mysqli_query($con,$query);
}

else if ($function == "exsists"){
	$query = "SELECT * FROM elder WHERE ".$_POST['attr']."='".$arg."';" ;
	$result = mysqli_query($con,$query);
	if(mysqli_num_rows($result) > 0) echo "valid";
	else echo "invalid";
}

else if ($function == "getPillPod"){
//10162530246
	$query = "SELECT d.*, CONCAT(coordinator.FirstName,' ',coordinator.MiddleName,' ',coordinator.LastName) AS coName 
						FROM(SELECT physician.Name, physician.LastName, physician.Title, c.* FROM ( SELECT b.*, drug.DrugName, drug.Colour, drug.Shape, drug.Weight, drug.Description  AS did FROM (SELECT pr.*, pe.* 
                         FROM(SELECT p.PrescriptionID as pid, p.ElderID, p.CoordinatorID, p.PhysicianID,p.Date, p.Note, p.DeviceID FROM prescription as p WHERE ElderID = '".$_SESSION['elderID']."' ORDER BY `Date` DESC LIMIT 1) AS pr 
                         INNER JOIN prescription_entry as pe ON pr.pid  = pe.PrescriptionID) AS b INNER JOIN drug ON drug.DrugID = b.DrugID) AS c INNER JOIN physician ON c.PhysicianID = physician.PhysicianID) AS d LEFT JOIN coordinator ON coordinator.CoordinatorID = d.CoordinatorID;" ;
	$entries = mysqli_query($con,$query);
	if (mysqli_num_rows($entries) == 0) {
		echo "No pill pod assigned!";
	}
	else{
		$row = mysqli_fetch_assoc($entries);		  
		$data = "";
		if(trim($row['DeviceID']) != ''){
			$data = "<table><tr><td width=\"150\">Physician</td><td>".$row['Title'].". ".initializeName($row['Name']." ".$row['LastName'])." (".$row['PhysicianID'].")</td></tr>";
			$data .= "<tr><td>Coordinator</td><td>".initializeName($row['coName'])." (".$row['CoordinatorID'].")</td></tr>";
			$data .= "<tr><td>PIllPod</td><td>".$row['DeviceID']."</td></tr>";
			$data .= "<tr><td>Prescription Date</td><td>".$row['Date']."</td></tr>";
			$data .= "<tr><td>Prescription Note</td><td>".$row['Note']."</td></tr>";
			$data .= "<tr><td>Prescription</td><td>".$row['PrescriptionID']."</td></tr>";
			$data .= "<tr><td colspan='2'>";
			$data .= "<table class='ctable' cellpadding='5px' cellspacing='0' border='0'>";
			$data .= "<tr><th>Container</th><th>DrugName</th><th>Colour</th><th>Shape</th><th>Weight</th><th>Total Tablets</th><th width='150'>Description</th></tr>";
			for($i = 0; $i < mysqli_num_rows($entries); $i++){
				mysqli_data_seek($entries,$i);
				$row = mysqli_fetch_assoc($entries);
				$data .= "<tr><td>".($i+1)."</td><td>".$row['DrugName']."</td><td>".$row['Colour']."</td><td>".$row['Shape']."</td><td>".($row['Weight']."mg")."</td><td>".getTotalTablets($row['Dose'],$row['Pattern'],$row['Days'],$row['Emergency'])."</td><td width='150'>".$row['did']."</td></tr>";
			}
			$data .= "</table></td></tr></table>";
			echo $data;
		}
		else{
		$query = "SELECT * FROM (SELECT device.*, b.*
				  FROM (
						SELECT prescription.DeviceID as pDev,prescription.ElderID, prescription.Date
						FROM prescription
						GROUP BY pDev DESC
					   ) AS b
				  RIGHT JOIN device ON b.pDev = device.DeviceID WHERE device.NumberOfCompartments >= ".mysqli_num_rows($entries).") AS a WHERE a.ElderID IS NULL ORDER BY NumberOfCompartments;";
			$dev = mysqli_query($con,$query);
			$devList = "<select name='devList'  style='width:150px;'>";
			for($j=0; $j < mysqli_num_rows($dev); $j++){
				mysqli_data_seek($dev,$j);
				$thisDev = mysqli_fetch_assoc($dev);
				$devList .= "<option value='".$thisDev['DeviceID']."'>".$thisDev['DeviceID']." (".$thisDev['NumberOfCompartments'].")</option>";
			}
			$devList .= "</select>";
			$data = "<table><tr><td width=\"150\">Physician</td><td>".$row['Title'].". ".initializeName($row['Name']." ".$row['LastName'])." (".$row['PhysicianID'].")</td></tr>";
			$data .= "<tr><td>PIllPod</td><td>".$devList." <input type='button' value='Assign' onClick='assignPillPod(\"".$row['PrescriptionID']."\");'></td></tr>";
			$data .= "<tr><td>Prescription Date</td><td>".$row['Date']."</td></tr>";
			$data .= "<tr><td>Prescription Note</td><td>".$row['Note']."</td></tr>";
			$data .= "<tr><td>Prescription</td><td>".$row['PrescriptionID']."</td></tr>";
			$data .= "<tr><td colspan='2'>";
			$data .= "<table class='ctable' cellpadding='5px' cellspacing='0' border='0'>";
			$data .= "<tr><th>Container</th><th>DrugName</th><th>Colour</th><th>Shape</th><th>Weight</th><th>Total Tablets</th><th width='150'>Description</th></tr>";
			for($i = 0; $i < mysqli_num_rows($entries); $i++){
				mysqli_data_seek($entries,$i);
				$row = mysqli_fetch_assoc($entries);
				$data .= "<tr><td>".($i+1)."</td><td>".$row['DrugName']."</td><td>".$row['Colour']."</td><td>".$row['Shape']."</td><td>".($row['Weight']."mg")."</td><td>".getTotalTablets($row['Dose'],$row['Pattern'],$row['Days'],$row['Emergency'])."</td><td width='150'>".$row['did']."</td></tr>";
			}
			$data .= "</table></td></tr></table>";
			echo $data;
		}
	}
}
else if ($function == "assignPillPod"){
	$qry = "UPDATE prescription SET DeviceID = '".trim($_POST['dev'])."', CoordinatorID='".$_SESSION['ID']."' WHERE PrescriptionID='".trim($_POST['pres'])."';";
	
	if($con->query($qry)) echo "Successfully Assigned!";
	else echo "Error Occurred!";
}

else if($function == "deleteElder"){
	$qry = "UPDATE elder SET  ElderStatus=0  WHERE ElderID = '".$_SESSION['elderID']."';";
	if($con->query($qry)) echo "Elder Removed Successfully!";
	else echo "Elder Remove Failed!";
}
?>
