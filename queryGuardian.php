<?php 
include "config.php";
$function = $_POST['func'];
$arg = '';
if(isset($_POST['arg'])) $arg = $_POST['arg'];
if ($function == "findGuardian"){ //find guardian
	$query = "SELECT * FROM guardian WHERE Name LIKE '%".$arg."%' OR GuardianID LIKE '%".$arg."%' OR NIC LIKE '%".$arg."%'" ;
	//
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
			if ($i % 2 == 1)
				$sty =' style="background-color:#fff;"';
			else
				$sty ="";
			$data= $data.'<tr'.$sty.'><td><label class="clickcell" onClick="viewGuardianDetails(this.innerHTML);">'.$record["GuardianID"].'</label></td>';
			$data= $data.'<td>'.$record["NIC"].'</td>';
			$data= $data.'<td>'.$record["Name"].'</td></tr>';
		}
		echo $data.'</table>';
	}
}

else if ($function == "fillGuardian"){ // fill existing guardian ID in elder registration
	$query = "SELECT * FROM guardian WHERE GuardianID='".$arg."' OR NIC ='".$arg."'" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){
		echo "No records found!";
	}
	else if($rows > 1){
		echo "Invalid ID!";
	}
	else{
		$record = mysqli_fetch_assoc($result);
		echo $record['GuardianID']."#".$record['Name'];
	}
}


else if ($function == "getGuardian"){ // get details of a guardian
	if($_SESSION["elderID"] != ""){
		$query = "SELECT * FROM guardian WHERE GuardianID IN(SELECT GuardianID FROM elder WHERE ElderID='".$_SESSION["elderID"]."');" ;
		$result = mysqli_query($con,$query);
		$rows = mysqli_num_rows($result);
		if ($rows == 0){
			echo "No records found!";
		}
		else{
			$topics=array("GuardianID"=>"Guardian ID","Name"=>"Name","Gender"=>"Gender","NIC"=>"NIC","Address"=>"Address","Mobile"=>"Contact");
			$data='<table>';
			$record = mysqli_fetch_assoc($result);
			foreach($topics as $key=>$value){
				$data= $data.'<tr><td width="100px">'.$topics[$key].'</td>';
				$data= $data.'<td>'.$record[$key].'</td></tr>';
			}
			$data= $data.'</table>';
			echo $data.'<br><br>';
			echo '<input type="button" value="Edit Details" onClick="viewEditGuardianTab();">';
		}
	}
	else{
		echo "Invalid Elder Selected!";
	}
}

else if ($function == "exsists"){
	$query = "SELECT * FROM guardian WHERE ".$_POST['attr']."='".$arg."';" ;
	//debug($query);
	$result = mysqli_query($con,$query);
	if(mysqli_num_rows($result) > 0) echo "valid";
	else echo "invalid";
	//debug(mysqli_num_rows($result));
}
else if ($function == "editGuardianDetails"){
    
    if(($_POST['name'])!=""){
        $query = "UPDATE guardian SET Name='".$_POST['name']."' WHERE GuardianID IN(SELECT GuardianID FROM elder WHERE ElderID='".$_SESSION["elderID"]."');" ;
	mysqli_query($con,$query);
        
    }
    if(($_POST['nic'])!=""){
        $query = "UPDATE guardian SET NIC='".$_POST['nic']."' WHERE GuardianID IN(SELECT GuardianID FROM elder WHERE ElderID='".$_SESSION["elderID"]."');" ;
	mysqli_query($con,$query);
        
    }
    if(isset($_POST['gender'])){
        $query = "UPDATE guardian SET Gender='".$_POST['gender']."' WHERE GuardianID IN(SELECT GuardianID FROM elder WHERE ElderID='".$_SESSION["elderID"]."');" ;
	mysqli_query($con,$query);
        
    }
    if(($_POST['contact'])!=""){
        $query = "UPDATE guardian SET Mobile='".$_POST['contact']."' WHERE GuardianID IN(SELECT GuardianID FROM elder WHERE ElderID='".$_SESSION["elderID"]."');" ;
	mysqli_query($con,$query);
        
    }
    if(($_POST['address'])!=""){
        $query = "UPDATE guardian SET Address='".$_POST['address']."' WHERE GuardianID IN(SELECT GuardianID FROM elder WHERE ElderID='".$_SESSION["elderID"]."');" ;
	mysqli_query($con,$query);
        
    }
}
?>
