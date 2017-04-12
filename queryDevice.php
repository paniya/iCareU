<?php 
global $con;
include "config.php";
$function = $_POST['func'];

function getDateSerial(){
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
	$serial ='4'.$serial.$daySerial;
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

function getNextID(){
	$serial = getDateSerial();
	$lastRecord = "";
	$lastDevice = "";
	$newDevice ="";
	//Get latest Device registered
	global $con;
	$query = "SELECT * FROM device WHERE DeviceID LIKE '".$serial."%' ORDER BY DeviceID DESC;" ;
	$result = mysqli_query($con,$query);
	mysqli_data_seek($result,0);	
	$record = mysqli_fetch_assoc($result);
	$rows = mysqli_num_rows($result);
	if ($rows != 0){
		$lastDevice =$record['DeviceID'];
	}
	else $lastDevice = "0";
	//Get Latest Temp ID Given
	$query = "SELECT * FROM tempid  WHERE id LIKE '".$serial."%' ORDER BY id DESC;" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){ //temp is empty, get id from last Device registered
		if($lastDevice == "0"){
			$serial = getCheckDigit($serial."000"); // OK
		}
		else{
			$serial = getCheckDigit(substr($lastDevice,0,10)+1);
		}
	}
	else{ // record present in the temp
		mysqli_data_seek($result,0); // get top most record
		$record = mysqli_fetch_assoc($result);
		$lastRecord = $record['id'];
		if((int)$lastRecord >= (int)$lastDevice){
			$serial = getCheckDigit((string)((int)substr($record['id'],0,10)+1));
			//echo "ser1:".$serial;
		}
		else{
			$serial = getCheckDigit((string)((int)substr($lastDevice,0,10)+1));
			//echo "ser2:".$serial;
		}
	}
	$query = "INSERT INTO tempid (id) VALUES ('".$serial."');" ; // insert to temp
	$con->query($query);
	return $serial; // return serial
}

if ($function == "addDevice"){
	$compartments = $_POST['compartments'];
	$accesNumber = $_POST['accessnumber'];
	$query = "SELECT * FROM device WHERE accessNumber = '".trim($accesNumber)."'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){
		$deviceID = getNextID();
		$query = "INSERT INTO device (DeviceID, accessNumber, NumberOfCompartments) VALUES ('".$deviceID."','".$accesNumber."',".$compartments.");";
		if($con->query($query) === TRUE){
			$query = "DELETE FROM tempid WHERE id ='".$deviceID."';" ;
			$con->query($query);
			echo "Device listed successfully with Device ID '".$deviceID."'!";
		}
		else{
			echo "Could not list the device!";
			echo "<br>".$query;
		}
	}
	else {
		mysqli_data_seek($result,0);	
		$record = mysqli_fetch_assoc($result);
		echo "Access Number Already Registered with Device ID '".$record["DeviceID"]."'";
	}
}
else if ($function == "getDevices"){
	$query = "SELECT device.*, b.*
			  FROM (
                    SELECT prescription.DeviceID as pDev,prescription.ElderID, prescription.Date
                    FROM prescription
                    GROUP BY pDev DESC
                   ) AS b
              RIGHT JOIN device ON b.pDev = device.DeviceID;";
	$result = mysqli_query($con,$query);
	$row = 0;
	$page=0;
	$header = '<tr><th width="30">#</th><th width="150">Device ID</th><th width="150">Access Number</th><th width="150">Compartments</th><th width="150">Elder ID</th></tr>';
	$rows = mysqli_num_rows($result);
	$data = "";
	$sty="";
	//echo $rows;
	foreach($result as $listItem){
		if ($row == 0){
			$data = '<div id="viewDevicesTable'.$page.'"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
		}
		else if(($row+1) % 25 == 1){
			$data =$data.'<div id="viewDevicesTable'.$page.'" style="display:none;"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
		}
		
		$data = $data."<tr".$sty."><td><center>".($row+1)."</center></td>";
		$data = $data."<td><center>".$listItem['DeviceID']."</center></td>";
		$data = $data."<td><center>".$listItem['AccessNumber']."</center></td>";
		$data = $data."<td><center>".$listItem['NumberOfCompartments']."</center></td>";
		$data = $data."<td><center>".$listItem['ElderID']."</center></td></tr>";
		//start
		if ($rows > 25){
			if($row == $rows-1){ //last page
				$data = $data.'<tr style="background-color:#efefef;"><td colspan="6"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewDevicesTable'.($page).'\',\'viewDevicesTable'.($page-1).'\');"><div style="width:70%;margin-left:15%"><center>Page '.($page+1).'</center></div><div style="width:15%;"></div></td></tr></table></div>';
			}
			else if(($row+1) % 25 == 0){ //page break
				if($page == 0){ //page1
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="6"><div style="width:15%;"></div><div style="width:70%;float:left;margin-left:15%;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewDevicesTable'.($page).'\',\'viewDevicesTable'.($page+1).'\');"></td></tr></table></div>';
				}
				else{
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="6"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewDevicesTable'.($page).'\',\'viewDevicesTable'.($page-1).'\');"><div style="width:70%;float:left;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewDevicesTable'.($page).'\',\'viewDevicesTable'.($page+1).'\');"></td></tr></table></div>';
				}
				$page++;
			}
		}
		else{
			if($row == $rows-1){
				$data = $data.'</table></div>';
			}
		}
		$row++;
	}//end
	echo $data."<br><br>";
	
}
?>
