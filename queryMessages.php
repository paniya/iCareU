<?php 
include "config.php";

if (isset($_SESSION['ID'])){
	if(isset($_POST["func"])){
		if($_POST["func"] == "sendMessage"){ //guardian -> Visitor(by email)
			$qry = "UPDATE messages SET ReplyBy='".$_SESSION['ID']."', Reply='".$_POST['cont']."', ReplyDate='".date('Y-m-d H:i:s')."', Status='rep' WHERE MessageID=".$_POST['index'].";";
			if($con->query($qry)){
				echo "<h4>".$_SESSION['InitName']." (".date('Y-m-d h:i:s A').")</h4><div class='msgReplyContent'>".trim(preg_replace("~\n~","<br>",$_POST["cont"]))."</div>";
			}
			else{
				echo "error";
			}
		}
		if($_POST["func"] == "sendGMessage"){ //guardian -> coord | coord -> guardian
			$qry = "INSERT INTO guardian_messages(Sender,Receiver,Message,DateSent,TimeSent) VALUES ('".$_SESSSION['ID']."','".$_POST["receiver"]."','".$_POST["message"]."','".date('Y-m-d')."','".date('h;i:s')."');";
			if($con->query($qry)){
				echo "sent";
			}
			else{
				echo "error";
			}
		}
		if($_POST["func"] == "getGMessage"){ // get messages from guardians
			$qry = "SELECT * FROM guardian_messages WHERE Sender = ".$_POST["sender"];
		}
		
		if($_POST["func"] == "getGSenders"){ // get senders
		
		}
		
		if($_POST["func"] == "getMessages"){ // get messages from visitors
			$qry = "SELECT messages.*, CONCAT(coordinator.FirstName,' ', coordinator.MiddleName,' ', coordinator.LastName) AS cName FROM messages LEFT JOIN coordinator ON messages.ReplyBy = coordinator.CoordinatorID ORDER BY Status, DateSent DESC;";
			if($result = mysqli_query($con, $qry)){
				if(mysqli_num_rows($result) == 0) echo "No Messages Found!";
				else{
					$_SESSION['messages'] = $result;
					$data = "<table width='100%' cellspacing='0'' cellpadding='0' >";
					$msg=0;
					while($row = mysqli_fetch_assoc($result)){
						if($row['Status'] == "new"){
							$data .= "<tr><td class='td1'><h3>".$row['SenderName']." <input class='msgReply' type='button' value='Reply' onClick='replyMessage(".$row['MessageID'].",".$msg.",this)'></h2> 
							<div class='msgContent'><u>".$row["Subject"]."</u><br>".trim(preg_replace("~\n~","<br>",$row['Message']))."<br></div>
							<div class='msgReplybox' style='display:none'></div></td>".
							"<td width=\"150\" class='td2'>".
							"<h4>".$row['DateSent']."<br><br>".$row['Email']."<br><br>".$row['PhoneNumber']." </h4></td></tr>
							<tr><td colspan='2'><br></td></tr>";
						}
						else if($row['Status'] == "rep"){
							$data .= "<tr><td class='td1'><h3>".$row['SenderName']."</h2> 
							<div class='msgContent'><u>".$row["Subject"]."</u><br>".trim(preg_replace("~\n~","<br>",$row['Message']))."<br></div>
							<div class='msgReplybox'>"."<h4>".initializeName($row['cName'])." (".$row['ReplyDate'].")</h4><div class='msgReplyContent'>".trim(preg_replace("~\n~","<br>",$row['Reply']))."</div></div></td>".
							"<td width=\"150\" class='td2'>".
							"<h4>".$row['DateSent']."<br><br>".$row['Email']."<br><br>".$row['PhoneNumber']." </h4></td></tr>
							<tr><td colspan='2'><br></td></tr>";
						}
						$msg++;
					}
					$data .= "</table>";
					echo $data;
				}
			}
			else{
				echo "Error Occured!";
			}
		}
	}		
}

else{
	if(isset($_POST["func"])){
		if($_POST["func"] == "sendMessage"){
			if (isset($_POST["sender"]) && isset($_POST["phone"]) && isset($_POST["email"]) && isset($_POST["subject"]) && isset($_POST["message"])){
				$qry = "INSERT INTO messages(SenderName, PhoneNumber, Email, Subject, Message, Status, DateSent) VALUES('".$_POST["sender"].
				"','".$_POST["phone"]."','".$_POST["email"]."','".$_POST["subject"]."','".$_POST["message"]."','new','".date('Y-m-d H:i:s')."');";
				if($con->query($qry)) echo "Your message was sent!";
				else echo "Your message was NOT sent!";
			}
		}
	}
}