<?php
	$con = mysqli_connect("br-cdbr-azure-south-b.cloudapp.net", "b4c9e4de10ed39", "ac9164be", "icareu");

	$EID = $_POST["ElderID"];
	$AppD = $_POST["AppointmentDate"];
	$msg = $_POST["Reason"];
	$Phy = $_POST["PhysicianID"];
	
	$statement = mysqli_preprare($con, "INSERT INTO" user (ElderID, AppointmentDate, Reason, PhysicianID) VALUES(?,?,?,?));
	mysqli_stmt_bind_param($statement, "siss", $EID, $AppD, $msg, $Phy);
	mysqli_stmt_execute($statement);
	
	$response = array();
	$response["success"] = true;
	
	echo json_encode($response);
?>