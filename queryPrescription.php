<?php 
session_start();
$function = $_POST['func'];
$con = mysqli_connect("br-cdbr-azure-south-b.cloudapp.net","b4c9e4de10ed39","ac9164be","icareu");

function format($num){
	$formated=$num;
	for ($i = strlen($num); $i < 7; $i++){
		$formated = "0".$formated;
	}
	return $formated;
}

function getNextID(){
	//Get latest Prescription registered
	$con = mysqli_connect("br-cdbr-azure-south-b.cloudapp.net","b4c9e4de10ed39","ac9164be","icareu");
	$serial = substr(date("Y"),1,3);
	$query = "SELECT PrescriptionID FROM prescription WHERE PrescriptionID LIKE 'P".$serial."%' ORDER BY PrescriptionID DESC;" ;
	$result = mysqli_query($con,$query);
	//mysqli_data_seek($result,0);	
	$record = mysqli_fetch_assoc($result);
	$rows = mysqli_num_rows($result);
		if ($rows == 0){
			return "P".$serial."0000000";
		}
		else
			return "P".$serial.format(substr($record['PrescriptionID'],4) + 1);
}

if ($function == "getPrescriptions"){
	$query = "SELECT Date,PrescriptionID FROM prescription WHERE ElderID='".$_POST['arg']."' ORDER BY Date DESC;" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	$data="";
	if ($rows != 0){
		$data ='<table id="precriptionList"cellpadding="5" cellspacing="0" align="center">';
		$data= $data.'<tr onClick="listBox(this);newPrescriptionView();"><td>New</td></tr>';
			for ($i = 0;$i < $rows; $i++){
				mysqli_data_seek($result,$i);	
				$record = mysqli_fetch_assoc($result);
				$data= $data.'<tr onClick="listBox(this);selectedPrescriptionView(\''.trim($record['PrescriptionID']).'\');"><td>'.$record['Date'].'</td></tr>';
			}
			$data= $data.'</table>';
	}
	else{
		$data ='<table id="precriptionList"cellpadding="5" cellspacing="0" align="center">';
		$data= $data.'<tr onClick="listBox(this);newPrescriptionView();"><td>New</td></tr>';
		$data= $data.'</table>';
	}
	echo $data;
}

else if ($function == "savePrescription"){
	$nextID = getNextID();
	$query = "INSERT INTO prescription(PrescriptionID, ElderID, PhysicianID, Date, Note) VALUES ('".$nextID."','".$_POST['elder']."','".$_SESSION['ID']."','".date("Y-m-d")."','".$_POST['note']."');" ;
	if($con->query($query)){
		$entryQ = $_POST['entry'];
		$entryQ = str_replace("PrescriptionID", $nextID, $entryQ);
		$query = "INSERT INTO prescription_entry(PrescriptionID, DrugID, Dose, Pattern, Days, Emergency) VALUES ".$entryQ ;
		if($con->query($query)){
			echo"OK".$nextID;
		}
		else{
			echo mysqli_error($con)."\n".$entryQ;
		}
	}
	else echo mysqli_error($con)."\n".$nextID;
}

else if ($function == "listPrescription"){
	$query = "SELECT Date FROM prescription WHERE ElderID='".$_POST['arg']."'" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	$data="";
	if ($rows != 0){
		$data ='<table id="precriptionList"cellpadding="5" cellspacing="0" align="center">';
		$data= $data.'<tr onClick="listBox(this);newPrescriptionView();"><td>New</td></tr>';
			for ($i = 0;$i < $rows; $i++){
				mysqli_data_seek($result,$i);	
				$record = mysqli_fetch_assoc($result);
				$data= $data.'<tr onClick="listBox(this);selectedPrescriptionView();"><td>'.$record['Date'].'</td></tr>';
			}
			$data= $data.'</table>';
	}
	echo $data;
}

else if ($function == "viewPrescription"){
	$query = "SELECT b.BrandName, b.Weight, a.Dose,a.Pattern, a.Days, a.Emergency FROM prescription_entry a INNER JOIN drug b ON b.DrugID = a.DrugID WHERE a.PrescriptionID='".$_POST['arg']."' ;" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	$data="";
	if ($rows != 0){
		$data ='<table class="ctable" cellpadding="5" cellspacing="0" align="center">';
		$data= $data.'<tr><th width="100">Drug(weight)</th><th width="100">Dose(per meal)</th><th width="50">Morning</th><th width="50">Noon</th><th width="50">Night</th><th width="50">Meal</th><th width="50">No of Days</th><th width="50">Emergency Pill</th></tr>';
			for ($i = 0;$i < $rows; $i++){
				mysqli_data_seek($result,$i);	
				$record = mysqli_fetch_assoc($result);
				$data= $data.'<tr><td>'.$record['BrandName'].'('.$record['Weight'].'mg)</td>';
				$data= $data.'<td>'.$record['Dose'].'</td>';
				$data= $data.'<td><label class="tick" style="background-position:-2px '.($record['Pattern'][0] == "0" ? "-2px" : "-22px").' ;"></label> </td>';
				$data= $data.'<td><label class="tick" style="background-position:-2px '.($record['Pattern'][1] == "0" ? "-2px" : "-22px").' ;"></label> </td>';
				$data= $data.'<td><label class="tick" style="background-position:-2px '.($record['Pattern'][2] == "0" ? "-2px" : "-22px").' ;"></label> </td>';
				$data= $data.'<td>'.($record['Pattern'][3]=="0" ? "Before" : "After").'</td>';
				$data= $data.'<td>'.$record['Days'].'</td>';
				$data= $data.'<td><label class="tick" style="background-position:-2px '.($record['Emergency'] == "0" ? "-2px" : "-22px").' ;"></label> </td></tr>';
			}
			$data= $data.'</table>';
	}
	else $data ="Error Occured!";
	echo $data;
}

?>
