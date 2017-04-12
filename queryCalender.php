<?php 
include "config.php";
$function = $_POST['func'];

if ($function == "getHolidays"){
	$query = "SELECT * FROM calender WHERE Person='".$_POST['arg']."' AND Date >= '".date("Y-m-d")."' ORDER BY Date DESC;" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows > 0){
		$row = 0;
		$page=0;
		$header = '<tr><th width="100">Date</th><th width="80">From</th><th width="80">To</th><th width="300">Reason</th></tr>';
		$data = "";
		foreach($result as $listItem){
			if ($row == 0){
				$data = '<div id="viewHolidayTable'.$page.'"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
			}
			else if(($row+1) % 25 == 1){
				$data =$data.'<div id="viewHolidayTable'.$page.'" style="display:none;"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
			}	
			$data = $data."<tr><td><center>".$listItem['Date']."</center></td>";
			$data = $data."<td><center>".date('h:i A',strtotime($listItem['From']))."</center></td>";
			$data = $data."<td><center>".date('h:i A',strtotime($listItem['To']))."</center></td>";
			$data = $data."<td><center>".$listItem['Reason']."</center></td></tr>";
			if ($rows > 25){
				if($row == $rows-1){ //last page
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="4"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewHolidayTable'.($page).'\',\'viewHolidayTable'.($page-1).'\');"><div style="width:70%;margin-left:15%"><center>Page '.($page+1).'</center></div><div style="width:15%;"></div></td></tr></table></div>';
				}
				else if(($row+1) % 25 == 0){ //page break
					if($page == 0){ //page1
						$data = $data.'<tr style="background-color:#efefef;"><td colspan="4"><div style="width:15%;"></div><div style="width:70%;float:left;margin-left:15%;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewHolidayTable'.($page).'\',\'viewHolidayTable'.($page+1).'\');"></td></tr></table></div>';
					}
					else{
						$data = $data.'<tr style="background-color:#efefef;"><td colspan="4"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewHolidayTable'.($page).'\',\'viewHolidayTable'.($page-1).'\');"><div style="width:70%;float:left;margin-left:15%"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewHolidayTable'.($page).'\',\'viewHolidayTable'.($page+1).'\');"></td></tr></table></div>';
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
		echo $data;
	}
	else echo "No Records Found";
		
}
else if ($function == "addHoliday"){
	$query = "INSERT INTO calender(Person, Date, `From`, `To`, Reason) VALUES ('".$_POST['arg']."', '".$_POST['arg1']."', '".$_POST['arg2']."', '".$_POST['arg3']."', '".$_POST['arg4']."');";
	if($con->query($query)){
		echo "Holiday Added Successfully";
	}
	else{
		echo "Error Occured!";
	}
}
?>