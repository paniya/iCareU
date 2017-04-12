selected_menu="viewPhysicianHome";
menu_index = 0;
selected_submenu = ["viewElderDetails","viewPrescrion","viewReports"];
var thisPatient=-1;
var topBarDateUpdated = false;
//var patientList =["10162530246","12016256002","12016256003","12016256004","12016256005"];
var patientList=[];
var thisPrescription =[];
var httpComplete = 0;
var thisPresEntry;
var selectedPresEntry=-1;

function loadElderList(){
	var xhrb = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getElderList");
	xhrb.onreadystatechange = function(){
		if(xhrb.readyState==4 && xhrb.status==200){
			//alert(xhrb.responseText.trim());
			if(xhrb.responseText.trim() != "")
				patientList = xhrb.responseText.trim().split(",");
			document.getElementById("startSessionButton").style.display="block";
		}
	}
	xhrb.open('post','queryAppointment.php',true);
	xhrb.send(data);
}

loadElderList();

function updateTopBarDate(){
	var weekDay = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
	var month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
	var d = new Date();
	var postfix = "";
	if (d.getDate()< 20 && d.getDate() > 10)
		postfix = "<sup>th</sup>";
	else if (d.getDate()%10 == 1)
		postfix = "<sup>st</sup>";
	else if (d.getDate()%10 == 2)
		postfix = "<sup>nd</sup>";
	else if (d.getDate()%10 == 3)
		postfix = "<sup>rd</sup>";
	else
		postfix = "<sup>th</sup>";
		
	try{
		document.getElementById("viewHomeTopBarDate").innerHTML=weekDay[d.getDay()]+", "+d.getDate()+postfix+" "+month[d.getMonth()]+" "+d.getFullYear();
		//document.getElementById("viewHomeTopBarQty").innerHTML=(thisPatient+1)+"/"+totalPatients;
		topBarDateUpdated = true;
	}
	catch (ex){
		topBarDateUpdated = false;
	}
	finally{
		if (topBarDateUpdated == false){
			setTimeout(updateTopBarDate,1000);
		}
	}
}

updateTopBarDate();

function updateTopBarTime(){
	var d = new Date();
	var hours,minutes,postfix;
	if (d.getHours() > 11){
		postfix="PM"
		hours = d.getHours()-12;		
	}
	else{
		postfix="AM";
		hours = d.getHours();
	}
	if(hours == 0) hours = 12;
	if(hours < 10) hours = "0"+hours;
	
	(d.getMinutes() < 10) ? minutes = "0" + d.getMinutes() : minutes = d.getMinutes();
	//document.getElementById("viewHomeTopBarTime").innerHTML=hours+":"+minutes+postfix;
	try{
		document.getElementById("viewHomeTopBarTime").innerHTML=hours+":"+minutes+postfix;
	}
	catch (ex){
	}
	finally{
		setTimeout(updateTopBarTime,1000);
	}
}
updateTopBarTime();

function nextClick(elem){
	if(patientList.length > 0){
		document.getElementById("welcomePage").style.display="none";
		document.getElementById("viewPhysicianHome").style.display="block";
	}
	document.getElementById("prescriptionPreview").style.display="none";
	var elderSection = document.getElementById("viewElderDetails");
	var prescribeSection = document.getElementById("viewPrescription");
	var reportSection = document.getElementById("viewReports");
	var prescriptionList = document.getElementById("prescriptionList");
	httpComplete = 0;
	elem.style.display="none";
	elderSection.innerHTML="";
	prescriptionList.innerHTML="";
	reportSection.innerHTML="";
	if((elem.value !="Finish")&& (patientList.length != 0)){
		if (thisPatient == -1) elem.value="Next";
		if (thisPatient < patientList.length){
			document.getElementById("viewHomeTopBarQty").innerHTML=(thisPatient+2)+"/"+patientList.length;
			if (thisPatient == patientList.length-2) elem.value ="Finish";
			thisPatient++;
		}
		else if(thisPatient == patientList.length-1)
			document.getElementById("viewHomeTopBarQty").innerHTML="";	
		// Load Elder Data --------------------------------------------------------------------------------		
		var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		data.append("func","getElder");
		data.append("arg",patientList[thisPatient]);
		//document.getElementById("elderLoaderGif").style.display="block";
		//alert(form.name);
		xhr.onreadystatechange = function(){
			if(xhr.readyState==4 && xhr.status==200){
				elderSection.innerHTML= xhr.responseText.trim();
				document.getElementById("elderDetailsControls").innerHTML=""; //remove edit button div tag
				//document.getElementById("elderLoaderGif").style.display="none";
				httpComplete +=1;
				if (httpComplete == 3) elem.style.display="block";
			}
		}
		xhr.open('post','queryElder.php',true);
		xhr.send(data);	
		// Load Report Data --------------------------------------------------------------------------------	
		var xhrx = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		data.append("func","getReports");
		data.append("arg",patientList[thisPatient]);
		xhrx.onreadystatechange = function(){
			if(xhrx.readyState==4 && xhrx.status==200){
				reportSection.innerHTML= xhrx.responseText.trim(); 
				httpComplete +=1;
				if (httpComplete == 3) elem.style.display="block";
			}
		}
		xhrx.open('post','queryElder.php',true);
		xhrx.send(data);
		var xhry = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		// Load Previous Prescriptions ----------------------------------------------------------------------
		data.append("func","getPrescriptions");
		data.append("arg",patientList[thisPatient]);
		xhry.onreadystatechange = function(){
			if(xhry.readyState==4 && xhry.status==200){
				prescriptionList.innerHTML= xhry.responseText.trim(); 
				prescriptionList.style.display = "inline-block";
				prescriptionList.style.display = "inline-block";
				httpComplete +=1;
				if (httpComplete == 3) elem.style.display="block";
			}
		}
		xhry.open('post','queryPrescription.php',true);
		xhry.send(data);
	}
	else if(patientList.length == 0){
		alert("You do not have any patients!");
	}
	else{
		document.getElementById("viewPhysicianHome").style.display="none";
		document.getElementById("viewHomeTopBarQty").innerHTML="";	
	}		
}

function openElderReport(innerh){
	var elderReportPreview = document.getElementById("reportPreview");
	var elderReportList = document.getElementById("viewReports");
	elderReportPreview.innerHTML ='<input type="button" value="Close Preview" onClick="document.getElementById('+"'reportPreview'"+').style.display='+"'none'"+';document.getElementById('+"'viewReports'"+').style.display='+"'block'"+';">';
	elderReportPreview.innerHTML += "<iframe src = 'reports/"+innerh+"' style='width:700px; height:900px;'></iframe>";
	elderReportPreview.style.display="block";
	elderReportList.style.display="none";
	//var toolbarViewerRight = document.getElementById("toolbarViewerRight");
}

function selectedPrescriptionView(id){
	var presPrev = document.getElementById("prescriptionPreview");
	presPrev.style.display="none";
	document.getElementById("newPrecription").style.display="none";
	var xhrSel = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		data.append("func","viewPrescription");
		data.append("arg",id.trim());
		xhrSel.onreadystatechange = function(){
			if(xhrSel.readyState==4 && xhrSel.status==200){
				presPrev.innerHTML= xhrSel.responseText.trim();
				presPrev.style.display="inline-block";
			}
		}
		xhrSel.open('post','queryPrescription.php',true);
		xhrSel.send(data);
	
	
}

function newPrescriptionView(){
	document.getElementById("prescriptionPreview").style.display="none";
	document.getElementById("newPrecription").style.display="inline-block";
}

function addNewEntry(){
	var ent = document.getElementsByName("newPresEntry");
	var pattern = document.getElementsByName("newPresEntryPattern");
	var presEntryList = document.getElementById("presEntryList").getElementsByTagName("table")[0].getElementsByTagName("tbody")[0];
	//Create Entry Object
	var fullPattern = (pattern[0].checked ? "1": "0")+(pattern[1].checked ? "1": "0")+(pattern[2].checked ? "1": "0")+ document.prescriptionEntry.newPresEntryPatternX.value;
	var entry = {drugID:ent[0].value, index:ent[0].selectedIndex, dose:document.prescriptionEntry.newPresEntryDose.value, pattern:fullPattern, emergency:ent[2].checked, days:ent[1].value};
	thisPrescription.push(entry);
	if(ent[0].selectedIndex > -1) ent[0].getElementsByTagName("Option")[ent[0].selectedIndex].setAttribute("disabled","disabled");
	//Add to List
	presEntryList.innerHTML += "<tr onClick='listBox(this);fillPresEntry(" + (thisPrescription.length-1) + ");'><td>"+ent[0].getElementsByTagName("Option")[ent[0].selectedIndex].innerHTML+"</td></tr>";
	var presEntryList = document.getElementById("savePresEntry").style.display="inline-block";
	document.prescriptionEntry.reset();
}

function editThisEntry(){
	alert();
}

function fillEntries(entryObj){
	var ent = document.getElementsByName("newPresEntry");
	var pattern = document.getElementsByName("newPresEntryPattern");
	//Refill Values
	ent[0].selectedIndex = entryObj.index;
	ent[1].value = entryObj.days;
	ent[2].checked = entryObj.emergency;
	document.prescriptionEntry.newPresEntryDose.value = entryObj.dose;
	pattern[0].checked = entryObj.pattern[0] == "1";
	pattern[1].checked = entryObj.pattern[1] == "1";
	pattern[2].checked = entryObj.pattern[2] == "1";
	document.prescriptionEntry.newPresEntryPatternX.value = entryObj.pattern[3];
}
function fillNewPresEntry(){
	fillEntries(thisPresEntry);
	var ent = document.getElementsByName("newPresEntry");
	if (selectedPresEntry > -1)
		ent[0].getElementsByTagName("Option")[thisPrescription[selectedPresEntry].index].setAttribute("disabled","disabled");
	selectedPresEntry=-1;
	document.getElementById("removePresEntry").style.display="none";
	document.getElementById("editPresEntry").style.display="none";
	document.getElementById("addPresEntry").style.display="inline-block";
}

function fillPresEntry(index){
	var ent = document.getElementsByName("newPresEntry");
	var pattern = document.getElementsByName("newPresEntryPattern");
	ent[0].getElementsByTagName("Option")[thisPrescription[index].index].removeAttribute("disabled");
	//Create Entry Object
	if(selectedPresEntry == -1){
		var fullPattern = (pattern[0].checked ? "1": "0")+(pattern[1].checked ? "1": "0")+(pattern[2].checked ? "1": "0")+ document.prescriptionEntry.newPresEntryPatternX.value;
		var entry = {drugID:ent[0].value, index:ent[0].selectedIndex, dose:document.prescriptionEntry.newPresEntryDose.value, pattern:fullPattern, emergency:ent[2].checked, days:ent[1].value};
		thisPresEntry = entry;
	}
	fillEntries(thisPrescription[index]);
	if (selectedPresEntry > -1)
		ent[0].getElementsByTagName("Option")[thisPrescription[selectedPresEntry].index].setAttribute("disabled","disabled");
	selectedPresEntry = index;
	document.getElementById("removePresEntry").style.display="inline-block";
	document.getElementById("editPresEntry").style.display="inline-block";
	document.getElementById("addPresEntry").style.display="none";
	
}

function saveNewPres(){
	document.getElementById("precriptionList").firstChild.firstChild.style.display="none";
	document.getElementById("precriptionList").display="none";
	document.getElementById("newPrecription").style.display="none";
	var xhrSave = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","savePrescription");
	data.append("elder",patientList[thisPatient]);
	data.append("note",document.getElementById("newPresNote").value);
	var entryQ = "";
	if (thisPrescription.length == 0) alert("No drugs selected for the prescription!")
	else{
		entryQ = "('PrescriptionID','"+thisPrescription[0]['drugID']+"','"+thisPrescription[0]['dose']+"','"+thisPrescription[0]['pattern']+"','"+thisPrescription[0]['days']+"',"+thisPrescription[0]['emergency']+")";
		for(i = 1; i< thisPrescription.length; i++){
			entryQ += ",('PrescriptionID','"+thisPrescription[i]['drugID']+"','"+thisPrescription[i]['dose']+"','"+thisPrescription[i]['pattern']+"','"+thisPrescription[i]['days']+"',"+thisPrescription[i]['emergency']+")";
		}
		entryQ +=";";
	}
	data.append("entry",entryQ);
	xhrSave.onreadystatechange = function(){
		if(xhrSave.readyState==4 && xhrSave.status==200){
			if(xhrSave.responseText.trim().substr(0,2)== "OK"){
				document.getElementById("precriptionList").innerHTML="";
				var xhry = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
				var data = new FormData();
				data.append("func","getPrescriptions");
				data.append("arg",patientList[thisPatient]);
				xhry.onreadystatechange = function(){
					if(xhry.readyState==4 && xhry.status==200){
						prescriptionList.innerHTML= xhry.responseText.trim(); 
						prescriptionList.style.display = "inline-block";
						document.getElementById("precriptionList").firstChild.firstChild.style.display="none";
					}
				}
				xhry.open('post','queryPrescription.php',true);
				xhry.send(data);
				
			}
			else alert(xhrSave.responseText.trim());
				 
		}
	}
	xhrSave.open('post','queryPrescription.php',true);
	xhrSave.send(data);
}

function remPresEntry(){
	thisPrescription.pop(selectedPresEntry);
	var presEntryList = document.getElementById("presEntryList").getElementsByTagName("table")[0].getElementsByTagName("tbody")[0];
	presEntryList.removeChild(presEntryList.getElementsByTagName("tr")[selectedPresEntry+1]);
	selectedPresEntry = -1;
	presEntryList.getElementsByTagName("tr")[0].click();
	if (thisPrescription.length == 0){
		document.getElementById("savePresEntry").style.display="none";
	}
	
}