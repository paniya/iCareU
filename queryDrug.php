<?php 
global $con;
include "config.php";
$function = $_POST['func'];

function format($num){
	$formated=$num;
	for ($i = strlen($num); $i < 5; $i++){
		$formated = "0".$formated;
	}
	return $formated;
}

function getNextID(){
	global $con;
	$serial = substr(date("Y"),1,3);
	$query = "SELECT DrugID FROM drug WHERE DrugID LIKE 'D".$serial."%' ORDER BY DrugID DESC;" ;
	$result = mysqli_query($con,$query);
	$record = mysqli_fetch_assoc($result);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){
		return "D".$serial."00000";
	}
	else return "D".$serial.format(substr($record['DrugID'],4) + 1);
}

if ($function == "getDrugList"){
	$query = "SELECT BrandName, DrugID, Weight FROM drug" ;
	$result = mysqli_query($con,$query);
	$rows = mysqli_num_rows($result);
	if ($rows == 0){
		echo "";
	}
	else{
		$data="";
		for ($i = 0;$i < $rows; $i++){
			mysqli_data_seek($result,$i);	
			$record = mysqli_fetch_assoc($result);
			$data= $data.'<option value="'.$record['DrugID'].'">'.$record['BrandName'].' ('.$record['Weight'].'mg)</option>';
		}
		echo $data;
	}
}

else if ($function == "getDrug"){
	$query = "SELECT * FROM drug;";
	$result = mysqli_query($con,$query);
	$row = 0;
	$page=0;
	$header = '<tr><th width="30">#</th><th width="150">Drug ID</th><th width="150">Drug Name</th><th width="150">Brand Name</th><th width="150">Colour</th><th width="150">Shape</th><th width="150">Weight</th></tr>';
	$rows = mysqli_num_rows($result);
	$data = "";
	foreach($result as $listItem){
		if ($row == 0){
			$data = '<div id="viewDrugTable'.$page.'"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
		}
		else if(($row+1) % 25 == 1){
			$data =$data.'<div id="viewDrugTable'.$page.'" style="display:none;"><table border="0" cellpadding="5px" cellspacing="0" class="ctable">'.$header;
		}
		
		$data = $data."<tr><td><center>".($row+1)."</center></td>";
		$data = $data."<td><center>".$listItem['DrugID']."</center></td>";
		$data = $data."<td><center>".$listItem['DrugName']."</center></td>";
		$data = $data."<td><center>".$listItem['BrandName']."</center></td>";
		$data = $data."<td><center>".$listItem['Colour']."</center></td>";	
		$data = $data."<td><center>".$listItem['Shape']."</center></td>";	
		$data = $data."<td><center>".$listItem['Weight']."mg</center></td></tr>";
		//start
		if ($rows > 25){
			if($row == $rows-1){ //last page
				$data = $data.'<tr style="background-color:#efefef;"><td colspan="7"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewDrugTable'.($page).'\',\'viewDrugTable'.($page-1).'\');"><div style="width:70%;margin-left:15%"><center>Page '.($page+1).'</center></div><div style="width:15%;"></div></td></tr></table></div><br><br>';
			}
			else if(($row+1) % 25 == 0){ //page break
				if($page == 0){ //page1
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="7"><div style="width:15%;"></div><div style="width:70%;float:left;margin-left:15%;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewDrugTable'.($page).'\',\'viewDrugTable'.($page+1).'\');"></td></tr></table></div><br><br>';
				}
				else{
					$data = $data.'<tr style="background-color:#efefef;"><td colspan="7"><input type="button" style="width:15%;float:left;" value="Previous Page" onclick="switchTable(\'viewDrugTable'.($page).'\',\'viewDrugTable'.($page-1).'\');"><div style="width:70%;float:left;"><center>Page '.($page+1).'</center></div><input type="button" style="width:15%;float:right;" value="Next Page" onclick="switchTable(\'viewDrugTable'.($page).'\',\'viewDrugTable'.($page+1).'\');"></td></tr></table></div><br><br>';
				}
				$page++;
			}
		}
		else{
			if($row == $rows-1){
				$data = $data.'</table></div><br><br>';
			}
		}
		$row++;
	}//end
	echo $data;
	
}

else if ($function == "saveDrug"){
	$nextID = getNextID();
	$query = "INSERT INTO drug(DrugID, DrugName, BrandName, Weight, Colour, Shape, `Description`) VALUES ('".$nextID.$_POST['qry'] ;
	if($con->query($query)){
		echo"Drug listed in database!\n ID: ".$nextID;
	}
	else{
		echo mysqli_error($con)."\n".$query;
	}
}
?>