<?php
//echo "~02^1#Paracetamol#0#White#Desc1#1101#2#10^0#Amoxillin#2#YB#Desc2#1101#2#10^Guardian Name^Guardian Number^Elder Name^2016-12-03^02:15~";
include "config.php";
$qry = "SELECT f.*, drug.* FROM (SELECT d.*, e.eName, e.gName, e.Mobile 
									FROM(SELECT c.*, prescription_entry.* FROM (SELECT device.*, b.*
										FROM (SELECT prescription.DeviceID as pDev,prescription.ElderID, prescription.Date, prescription.PrescriptionID as presID
											FROM prescription GROUP BY pDev DESC ) AS b
											INNER JOIN device ON b.pDev = device.DeviceID WHERE device.AccessNumber='".$_GET['num']."') AS c
										INNER JOIN prescription_entry ON c.presID =prescription_entry.PrescriptionID) AS d 
									INNER JOIN (SELECT CONCAT(elder.FirstName,' ',elder.MiddleName,' ',elder.LastName)AS eName, elder.ElderID, guardian.Name as gName, guardian.Mobile 
												FROM elder 
												INNER JOIN guardian on elder.GuardianID = guardian.GuardianID)AS e ON d.ElderID = e.ElderID)as f 
							INNER JOIN drug ON f.DrugID = drug.DrugID ORDER BY f.Emergency, drug.DrugID;";

$result = mysqli_query($con,$qry);
if(mysqli_num_rows($result) > 0){
	$row = mysqli_fetch_assoc($result);
	$data =  "~".((mysqli_num_rows($result) < 10) ? "0".mysqli_num_rows($result) : mysqli_num_rows($result))."^";
	for ($i = 0; $i < mysqli_num_rows($result);$i++){
		mysqli_data_seek($result,$i);
		$row=mysqli_fetch_assoc($result);
		$data .=  $row['Emergency']."#".$row['DrugName']."#".$row['Shape']."#".$row['Colour']."#".$row['Description']."#".$row['Pattern']."#".$row['Dose']."#".$row['Days']."^";
	}
	$data .=shortName($row['gName'])."^".$row['Mobile']."^".shortName($row['eName'])."^".date('Y-m-d^H:i:s~');
	echo $data;
}
else echo "error";
//No.of Chambers
//Container details
//===Emergency
//===Name
//===Shape
//===color
//===description
//===Pattern =>mor,noon,night,before/after
//===dose
//===days
//Guardian Name
//Guardian Number
//Elder NAme
//Date
//Time

