<?php 
include "config.php";
$function = $_POST['func'];

if ($function == "getCalenderFilters"){ // get new appointments
	$query = "SELECT * FROM physician;" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	$data="";
	if ($rows == 0){
		$data ='<select name="viewAppointmentsCalenderFilter_Filter" style="width:300px">
        	<option value="general">General Holidays</option>';
	}
	else {
		$data ='<select name="viewAppointmentsCalenderFilter_Filter" style="width:300px">
        	<option value="general">General Holidays</option>';
			for ($i = 0;$i < $rows; $i++){
				mysqli_data_seek($result,$i);	
				$record = mysqli_fetch_assoc($result);
				$data= $data.'<option value="'.$record['PhysicianID'].'">'.$record['Name'].'</option>';
			}
	}
	$data= $data.'</select>';
	$data = $data.'<input type="button" value="Filter" onClick="calenderFilterClick();">';
	echo $data;
}
else if ($function == "actionNewAppointment"){ // accept or decline
	$query = "SELECT * FROM guardian WHERE GuardianID='".$arg."'" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){
		echo "No records found!";
	}
	else{
		$topics=array("GuardianID"=>"Guardian ID","Name"=>"Name","DateOfBirth"=>"Date of Birth","Gender"=>"Gender","NIC"=>"NIC","Address"=>"Address","Mobile"=>"Contact");
		$data='<table>';
		$record = mysqli_fetch_assoc($result);
		foreach($topics as $key=>$value){
			$data= $data.'<tr><td width="100px">'.$topics[$key].'</td>';
			$data= $data.'<td>'.$record[$key].'</td></tr>';
		}
		$data= $data.'</table>';
		echo $data.'<br><br>';
	}
}

else if ($function == "getToday"){
	$query = "SELECT appointment.*,elder.FirstName, elder.MiddleName, elder.LastName FROM appointment INNER JOIN elder ON elder.ElderID = appointment.ElderID WHERE PhysicianID='".$_POST['arg']."' AND AppointmentDate = '".date('Y-m-d')."' ORDER BY AppointmentTime;" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	$row = 0;
	$page=0;
	$header = '<tr><th width="10">#</th><th width="80">Time</th><th width="100">ElderID</th><th>Elder Name</th><th width="300">Reason</th></tr>';
	$rows = mysqli_num_rows($result);
	$data = "";
	foreach($result as $listItem){
		if ($row == 0){
			$data = '<div id="viewTodayAppointmentTable'.$page.'"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
		}
		else if(($row+1) % 25 == 1){
			$data =$data.'<div id="viewTodayAppointmentTable'.$page.'" style="display:none;"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
		}	
		$data = $data."<tr><td><center>".($row+1)."</center></td>";
		$data = $data."<td><center>".date('h:i A',strtotime($listItem['AppointmentTime']))."</center></td>";
		$data = $data."<td><center>".$listItem['ElderID']."</center></td>";
		$data = $data."<td><center>".$listItem['FirstName'].' '.$listItem['MiddleName'].' '.$listItem['LastName']."</center></td>";
		$data = $data."<td><center>".$listItem['Reason']."</center></td></tr>";
		if ($rows > 25){
			if($row == $rows-1){ //last page
				$data = $data.'<tr style="background-color:#efefef;"><td colspan="5"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewTodayAppointmentTable'.($page).'\',\'viewTodayAppointmentTable'.($page-1).'\');"><div style="width:70%;margin-left:15%"><center>Page '.($page+1).'</center></div><div style="width:15%;"></div></td></tr></table></div>';
			}
			else if(($row+1) % 25 == 0){ //page break
				if($page == 0){ //page1
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="5"><div style="width:15%;"></div><div style="width:70%;float:left;margin-left:15%;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewTodayAppointmentTable'.($page).'\',\'viewTodayAppointmentTable'.($page+1).'\');"></td></tr></table></div>';
				}
				else{
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="5"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewTodayAppointmentTable'.($page).'\',\'viewTodayAppointmentTable'.($page-1).'\');"><div style="width:70%;float:left;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewTodayAppointmentTable'.($page).'\',\'viewTodayAppointmentTable'.($page+1).'\');"></td></tr></table></div>';
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
	if ($rows == 0){
		echo "No Records Found";
	}
	else
		echo $data;
}

else if ($function == "getAll"){
	$query = "";
	if($_POST['arg1'] != ""){
		$query = "SELECT appointment.*,elder.FirstName, elder.MiddleName, elder.LastName FROM appointment LEFT JOIN elder ON elder.ElderID = appointment.ElderID WHERE PhysicianID='".$_POST['arg']."' AND AppointmentDate = '".$_POST['arg1']."';" ;
	}
	else{
		$query = "SELECT appointment.*,elder.FirstName, elder.MiddleName, elder.LastName FROM appointment LEFT JOIN elder ON elder.ElderID = appointment.ElderID WHERE PhysicianID='".$_POST['arg']."' ORDER BY AppointmentDate;" ;
	}
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	$row = 0;
	$page=0;
	$header = '<tr><th width="10">#</th><th width="80">Time</th><th width="100">ElderID</th><th>Elder Name</th><th width="300">Reason</th></tr>';
	$rows = mysqli_num_rows($result);
	$data = "";
	foreach($result as $listItem){
		if ($row == 0){
			$data = '<div id="viewTodayAppointmentTable'.$page.'"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
		}
		else if(($row+1) % 25 == 1){
			$data =$data.'<div id="viewTodayAppointmentTable'.$page.'" style="display:none;"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
		}	
		$data = $data."<tr><td><center>".($row+1)."</center></td>";
		$data = $data."<td><center>".date('h:i A',strtotime($listItem['AppointmentTime']))."</center></td>";
		$data = $data."<td><center>".$listItem['ElderID']."</center></td>";
		$data = $data."<td><center>".$listItem['FirstName'].' '.$listItem['MiddleName'].' '.$listItem['LastName']."</center></td>";
		$data = $data."<td><center>".$listItem['Reason']."</center></td></tr>";
		if ($rows > 25){
			if($row == $rows-1){ //last page
				$data = $data.'<tr style="background-color:#efefef;"><td colspan="5"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewTodayAppointmentTable'.($page).'\',\'viewTodayAppointmentTable'.($page-1).'\');"><div style="width:70%;margin-left:15%"><center>Page '.($page+1).'</center></div><div style="width:15%;"></div></td></tr></table></div>';
			}
			else if(($row+1) % 25 == 0){ //page break
				if($page == 0){ //page1
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="5"><div style="width:15%;"></div><div style="width:70%;float:left;margin-left:15%;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewTodayAppointmentTable'.($page).'\',\'viewTodayAppointmentTable'.($page+1).'\');"></td></tr></table></div>';
				}
				else{
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="5"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewTodayAppointmentTable'.($page).'\',\'viewTodayAppointmentTable'.($page-1).'\');"><div style="width:70%;float:left;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewTodayAppointmentTable'.($page).'\',\'viewTodayAppointmentTable'.($page+1).'\');"></td></tr></table></div>';
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
	if ($rows == 0){
		echo "No Records Found";
	}
	else
		echo $data;
}

else if ($function == "getElderList"){
	$query = "SELECT ElderID FROM appointment WHERE PhysicianID='".$_SESSION['ID']."' AND AppointmentDate = '".date('Y-m-d')."' ORDER BY AppointmentTime;" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if($rows > 0){
		$record = mysqli_fetch_assoc($result);
		$data = $record['ElderID'];
		for($i = 1; $i < $rows;$i++){
			mysqli_data_seek($result,$i);
			$record = mysqli_fetch_assoc($result);
			$data = $data.",".$record['ElderID'];
		}
		echo $data;
	}
	//else echo $_SESSION['ID'];
}
else if ($function == "setVisitorsAppointment"){
	$query = "INSERT INTO visitors_appointment(AppointmentDate, Reason, Email, PhoneNumber, PhysicianID) VALUES('".$_POST['date']."','".$_POST['reason']."','".$_POST['mail']."','".$_POST['phonenumber']."', '".$_POST['phy']."');";
        mysqli_query($con,$query);
}

?>
