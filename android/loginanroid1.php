<?php
	$con = mysqli_connect("us-cdbr-azure-southcentral-f.cloudapp.net", "bfe87d83d52bf6", "4d3b3617", "icareu16");

	$ID = $_POST["UserID"];
	$Password = $_POST["Password"];

    $sql = "SELECT * FROM users WHERE `UserID`='$ID' and  `Password`='$Password' ";
	$re = mysqli_query($con,$sql);
	if(mysqli_num_rows($re) == 1){
		while($row = mysqli_fetch_assoc($re)){
			$did = $row["UserID"];
		}
		
		echo "Login Success :$did";
	}
	else{
		echo "Login Failed";
	}
?>