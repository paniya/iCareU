selected_menu="viewElderProfile";
menu_index = 0;
selected_submenu = ["viewElderDetails","viewDrugAdd","viewPillPodAdd","viewAppointmentsCalender","viewMessagesGuardian","viewMyProfileDetails"];
var guardianAddNIC = false;
var elderAddNIC = false;
var guardianType="new";
var loaderGif = '<img src="images/loader.gif" class="loaderGif" >';
//================================ Manage Elder =======================================

// REGISTER NEW ELDER =================
function viewAddElderTab(){
	document.getElementById('viewElderRegisterNew').style.display='inline-block';
    document.getElementById('viewElderDetails').style.display='none';
	var today = new Date();
	document.getElementsByName("RegisterElderField")[3].setAttribute("max",(today.getFullYear()-40)+"-"+ (today.getMonth()+1)+"-"+ today.getDate());
	var navs = document.getElementsByName("coordinator_viewelder_navigator");
	for(i = 0;i< navs.length;i++){
		navs[i].style.display="none";
	}
	navs = document.getElementsByName("coordinator_viewelder_navigatorp");
	navs[navs.length-1].style.display="block";
	navs[navs.length-1].style.backgroundColor="#4eabf9";
	navs[navs.length-1].style.width="205px";
}

function registerElderCancelClick(){
	document.getElementById('viewElderRegisterNew').style.display='none';
	document.getElementById('viewElderRegisterNewMessage').style.display='none';
    document.getElementById('viewElderDetails').style.display='inline-block';
	document.getElementById('viewElderRegisterNew_All').style.display="inline-block";
	document.getElementById('viewElderRegisterNew_Actions').style.display="block";
	document.getElementsByName("coordinator_viewelder_navigator")[0].style.display="block";
	document.getElementsByName("coordinator_viewelder_navigatorp")[0].style.display="none";

	var elderDetails = document.getElementsByName("RegisterElderField");
	for(i = 0; i< elderDetails.length;i++){
		elderDetails[i].value="";
	}
	
	var guardianDetails = document.getElementsByName("RegisterGuardianField");
	for(i = 0; i< guardianDetails.length;i++){
		guardianDetails[i].value="";
	}
	document.getElementById("viewElderRegisterNew_GuardianIDText").value="";
	document.getElementById("viewElderRegisterNew_GuardianIDResult").innerHTML = "";
	document.getElementById("viewElderRegisterNew_GuardianIDResultH").value = "";	
	document.getElementsByClassName("NIC")[0].setAttribute("style","");
	document.getElementsByClassName("NIC")[1].setAttribute("style","");
}

function registerElderSubmit(){
	var validity = true;
	var errMsg = "";
	if(guardianType=="new") {
		document.getElementById("viewElderRegisterNew_GuardianIDResult").innerHTML = "";
		document.getElementById("viewElderRegisterNew_GuardianIDResultH").value = "";
	}
	if (!elderAddNIC) {validity = false; errMsg += "\n  - Elder's NIC Already Exist";}
	if (!guardianAddNIC && guardianType=="new") {validity = false; errMsg += "\n  - Guardian's NIC Already Exist";}
	if (guardianType=="exsist" && document.getElementById("viewElderRegisterNew_GuardianIDResultH").value.trim() == ""){validity = false; errMsg += "\n  - Guardian ID is Missing";}
	if(validity){
		document.getElementById("resultElderBox").innerHTML="";
		var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		var elderDetails = document.getElementsByName("RegisterElderField");
		var entry ="'" + elderDetails[0].value.trim() + "',"; //First Name
		entry +=   "'" + elderDetails[1].value.trim() + "',"; //Middle Name
		entry +=   "'" + elderDetails[2].value.trim() + "',"; //Last Name
		entry +=   "'" + elderDetails[3].value + "',"; //date
		entry +=   "'" + elderDetails[4].value + "',"; //NIC
		entry +=   "'" + elderDetails[5].value + "',"; //gender
		entry +=   "'" + elderDetails[6].value.trim() + "',"; //address
		entry +=   "'" + elderDetails[7].value + "',"; //mobile
		entry +=   "" + elderDetails[8].value + ","; //height
		entry +=   "" + elderDetails[9].value + ","; //weight
		if(elderDetails[10].value.trim() == "") elderDetails[10].value = "None";
		entry +=   "'" + elderDetails[10].value.trim() + "'"; //allergies
		data.append("func","addElder");
		data.append("arg",entry);
		data.append("arg1",elderDetails[11].value); //guardianID/New

		var guardianDetails = document.getElementsByName("RegisterGuardianField");
		entry ="'" + guardianDetails[0].value.trim() + "',"; //Full Name
		entry +=   "'" + guardianDetails[1].value + "',"; //NIC
		entry +=   "'" + guardianDetails[2].value + "',"; //Gender
		entry +=   "'" + guardianDetails[3].value + "',"; //Contact
		entry +=   "'" + guardianDetails[4].value.trim() + "'"; //Address
		data.append("arg2",entry);
		if(document.getElementById("registerElderPropic").value!=""){
			data.append("img",document.getElementById("registerElderPropicFile").files[0]);
		}
		xhr.onreadystatechange = function(){
			if(xhr.readyState==4 && xhr.status==200){
				if(xhr.responseText.trim().substr(0,2) == "ok"){
					var elderName = elderDetails[0].value +" "+ elderDetails[1].value +" "+ elderDetails[2].value;
					document.getElementById('viewElderRegisterNew_All').style.display="none";
					document.getElementById('viewElderRegisterNew_Actions').style.display="none";
					document.getElementById('viewElderRegisterNewMessage').innerHTML="<center>Successfully Registered <br><h3>"+ elderName +"</h3>with ID<br><h2>"+xhr.responseText.trim().substr(2,11)+
					"</h2><br><input type='button' value='ok' onClick=\"registerElderCancelClick();\"></center>";
					document.getElementById('viewElderRegisterNewMessage').style.display="inline-block";
				}
				else{
					alert(xhr.responseText.trim());
				}
			}
		}
		xhr.open('post','queryElder.php',true);
		xhr.send(data);
	}
	else{alert("Following Errors Occured!" + errMsg);}
		
	
}

// EDIT ELDER =================
function viewEditElderTab(){
	document.getElementById('viewElderEditElder').style.display='inline-block';
    document.getElementById('viewElderDetails').style.display='none';
	document.getElementById('btnSearchBack').style.display='none';
	var navs = document.getElementsByName("coordinator_viewelder_navigator");
	for(i = 0;i< navs.length;i++){
		navs[i].style.display="none";
		
	}
	navs = document.getElementsByName("coordinator_viewelder_navigatorq");
	navs[navs.length-1].style.display="block";
	navs[navs.length-1].style.backgroundColor="#4eabf9";
	navs[navs.length-1].style.width="205px";
}

function editElderCancelClick(){
	document.getElementById('viewElderEditElder').style.display='none';
	document.getElementById('viewElderRegisterNewMessage').style.display='none';
    document.getElementById('viewElderDetails').style.display='inline-block';
	var navs = document.getElementsByName("coordinator_viewelder_navigator");
	for(i = 0; i < navs.length;i++){
		navs[i].style.display="block";
	}
	document.getElementsByName("coordinator_viewelder_navigatorq")[0].style.display="none";
	document.getElementById('btnSearchBack').style.display='inline-block';
}

// REMOVE ELDER =================
function viewRemoveElderTab(){
	/*document.getElementById('viewElderRemoveElder').style.display='inline-block';
    document.getElementById('viewElderDetails').style.display='none';
	document.getElementById('btnSearchBack').style.display='none';
	var today = new Date();
	document.getElementsByName("RegisterElderField")[3].setAttribute("max",(today.getFullYear()-40)+"-"+ (today.getMonth()+1)+"-"+ today.getDate());
	var navs = document.getElementsByName("coordinator_viewelder_navigator");
	for(i = 0;i< navs.length;i++){
		navs[i].style.display="none";
	}
	navs = document.getElementsByName("coordinator_viewelder_navigatorr");
	navs[navs.length-1].style.display="block";
	navs[navs.length-1].style.backgroundColor="#4eabf9";
	navs[navs.length-1].style.width="205px";*/
	if (confirm("Are you sure to remove the elder?")){
		var xhrDL = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		data.append("func","deleteElder");
		document.getElementById("viewElderDetails_Loader").style.display="block";
		xhrDL.onreadystatechange = function(){
			if(xhrDL.readyState==4 && xhrDL.status==200){
				alert(xhrDL.responseText.trim());
			}
		}
		xhrDL.open('post','queryElder.php',true);
		xhrDL.send(data);
	}
}

/*function removeElderCancelClick(){
	document.getElementById('viewElderRemoveElder').style.display='none';
	document.getElementById('viewElderRegisterNewMessage').style.display='none';
    document.getElementById('viewElderDetails').style.display='inline-block';
	var navs = document.getElementsByName("coordinator_viewelder_navigator");
	for(i = 0; i < navs.length;i++){
		navs[i].style.display="block";
	}
	document.getElementsByName("coordinator_viewelder_navigatorr")[0].style.display="none";
	document.getElementById('btnSearchBack').style.display='inline-block';
}
*/
// EDIT GUARDIAN =================
function viewEditGuardianTab(){
	document.getElementById('viewElderEditGuardian').style.display='inline-block';
    document.getElementById('viewElderGuardianDetails').style.display='none';
	document.getElementById('btnSearchBack').style.display='none';
	var today = new Date();
	document.getElementsByName("RegisterElderField")[3].setAttribute("max",(today.getFullYear()-40)+"-"+ (today.getMonth()+1)+"-"+ today.getDate());
	var navs = document.getElementsByName("coordinator_viewelder_navigator");
	for(i = 0;i< navs.length;i++){
		navs[i].style.display="none";
	}
	navs = document.getElementsByName("coordinator_viewelder_navigators");
	navs[navs.length-1].style.display="block";
	navs[navs.length-1].style.backgroundColor="#4eabf9";
	navs[navs.length-1].style.width="205px";
}

function editGuardianCancelClick(){
	document.getElementById('viewElderEditGuardian').style.display='none';
	document.getElementById('viewElderRegisterNewMessage').style.display='none';
    document.getElementById('viewElderGuardianDetails').style.display='inline-block';
	var navs = document.getElementsByName("coordinator_viewelder_navigator");
	for(i = 0; i < navs.length;i++){
		navs[i].style.display="block";
	}
	document.getElementsByName("coordinator_viewelder_navigators")[0].style.display="none";
	document.getElementById('btnSearchBack').style.display='inline-block';
}

// ELDER DETAILS =================
function findElder(form){
	var navigatorList = document.getElementsByName("coordinator_viewelder_navigator");
	for(n = 1; n < navigatorList.length;n++){
		navigatorList[n].setAttribute("style","display:none;");
	}
	document.getElementById("findElderBox").style.display="none"
	document.getElementById("resultElderBox").style.display="none";
	document.getElementById("contentElderBox").style.display="none";
	var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","findElder");
	data.append("arg",document.getElementById("coordinatorFindElderName").value);
	document.getElementById("viewElderDetails_Loader").style.display="block";
	//alert(form.name);
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4 && xhr.status==200){
			resultElderBox.innerHTML= xhr.responseText.trim();
			document.getElementById("viewElderDetails_Loader").style.display="none";
			document.getElementById("resultElderBox").style.display="block";
			document.getElementById("findElderBox").style.display="block";
		}
	}
	xhr.open('post','queryElder.php',true);
	xhr.send(data);
}

function viewElderDetails(innerh){
	document.getElementById("findElderBox").style.display="none";
	document.getElementById("resultElderBox").style.display="none";
	document.getElementById("displayElderName").style.display="none";
	document.getElementById("contentElderBox").style.display="none";
	document.getElementById("viewElderDetails_Loader").style.display="block";
	var xhrelder = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getElder");
	data.append("arg",innerh);
	xhrelder.onreadystatechange = function(){
		if(xhrelder.readyState==4 && xhrelder.status==200){
			contentElderBox.innerHTML= xhrelder.responseText.trim();
			viewElderGuardianDetails();	
			viewElderReports();
			viewElderPillPod();
			document.getElementById("displayElderNameLabel").innerHTML = document.getElementById("ElderNamex").innerHTML;
			document.getElementById("displayElderName").style.display="block";
			document.getElementById("viewElderDetails_Loader").style.display="none";
			document.getElementById("contentElderBox").style.display="block";
			var navigatorList = document.getElementsByName("coordinator_viewelder_navigator");
			for(n = 1; n < navigatorList.length;n++){
				navigatorList[n].setAttribute("style","display:block;");
			}
		}
	}
	xhrelder.open('post','queryElder.php',true);
	xhrelder.send(data);

}

// ELDER GUARDIAN=====================================
function viewElderGuardianDetails(){
	var viewGuardianDetails = document.getElementById("viewElderGuardianDetails");
	viewGuardianDetails.innerHTML='<img src="images/loader.gif" class="loaderGif" style="display: block;" id="viewElderGuardianDetails_Loader">';
	var xhrguardian = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getGuardian");
	xhrguardian.onreadystatechange = function(){
		if(xhrguardian.readyState==4 && xhrguardian.status==200){
			viewGuardianDetails.innerHTML=xhrguardian.responseText.trim(); 
		}
	}
	xhrguardian.open('post','queryGuardian.php',true);
	xhrguardian.send(data);
	
}

// ELDER REPORTS=====================================
function viewElderReports(){
	document.getElementById("viewElderReports_Loader").style.display="block";
	var elderReportList = document.getElementById("elderReportList");
	var elderReportPreview = document.getElementById("elderReportPreview");
	var elderReportList = document.getElementById("elderReportList");
	var xhrreports = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getReports");
	xhrreports.onreadystatechange = function(){
		if(xhrreports.readyState==4 && xhrreports.status==200){
			elderReportList.innerHTML= '<input type="button" value="Add Report" style="margin-bottom:10px" onClick="document.getElementById('+"'elderReportAdd'"+').style.display='+"'block'"+';document.getElementById('+"'elderReportList'"+').style.display='+"'none'"+';">';
			elderReportList.innerHTML+= xhrreports.responseText.trim(); 
			elderReportPreview.style.display="none";
			elderReportList.style.display="block";
			document.getElementById("viewElderReports_Loader").style.display="none";
		}
	}
	xhrreports.open('post','queryElder.php',true);
	xhrreports.send(data);
	
}

function openElderReport(innerh){
	var elderReportPreview = document.getElementById("elderReportPreview");
	var elderReportList = document.getElementById("elderReportList");
	elderReportPreview.innerHTML ='<input type="button" value="Close Preview" style="display:block;" onClick="document.getElementById('+"'elderReportPreview'"+').style.display='+"'none'"+';document.getElementById('+"'elderReportList'"+').style.display='+"'block'"+';">';
	elderReportPreview.innerHTML += "<iframe src = 'reports/"+innerh+"' style='width:700px; height:900px;'></iframe>";
	elderReportPreview.style.display="block";
	elderReportList.style.display="none";
	var toolbarViewerRight = document.getElementById("toolbarViewerRight");
}

function addReport(form){
	var temppath = URL.createObjectURL(form.addReportFile.files[0]);
	var xhraddrep = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","addReports");
	data.append("desc",form.addReportDescription.value);
	data.append("status",form.addReportStatus.value);
	var today = new Date();
	var dat = today.getFullYear()+"-"+ (today.getMonth()+1)+"-"+ today.getDate();
	data.append("date",dat);
	//alert(form.addReportFile.files[0]);
	data.append("afile",form.addReportFile.files[0]);
	xhraddrep.onreadystatechange = function(){
		if(xhraddrep.readyState==4 && xhraddrep.status==200){
			viewElderReports(id);
			elderReportPreview.style.display="none";
			elderReportList.style.display="block";
		}
	}
	xhr.open('post','queryElder.php',true);
	xhr.upload.onProgress = function(e){
		if(e.lengthComputable){
			var comp = (e.loaded / e.total) *100;
			form.percUploadReport.innerHTML = comp+"% Uploaded...  ";
		}
	}
	xhr.send(data);
	document.getElementById('elderReportAdd').style.display='none';
    document.getElementById('elderReportList').style.display='block';
}

//ELDER PILL POD=====================================
function viewElderPillPod(){
	var viewElderPillPodDetails = document.getElementById("viewElderPillPodDetails");
	viewElderPillPodDetails.innerHTML='<img src="images/loader.gif" class="loaderGif" style="display: block;" id="viewElderPillPodDetails_Loader">'
	var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getPillPod");
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4 && xhr.status==200){
			viewElderPillPodDetails.innerHTML= xhr.responseText.trim(); 
		}
	}
	xhr.open('post','queryElder.php',true);
	xhr.send(data);
	
}

function assignPillPod(pres){
	var viewElderPillPodDetails = document.getElementById("viewElderPillPodDetails");
	var dev = document.getElementsByName("devList")[0].value;
	viewElderPillPodDetails.innerHTML='<img src="images/loader.gif" class="loaderGif" style="display: block;" id="viewElderPillPodDetails_Loader">'
	var xhrAPP = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","assignPillPod");
	data.append("pres",pres);
	data.append("dev",dev);
	xhrAPP.onreadystatechange = function(){
		if(xhrAPP.readyState==4 && xhrAPP.status==200){
			alert(xhrAPP.responseText.trim());
			viewElderPillPod();
		}
	}
	xhrAPP.open('post','queryElder.php',true);
	xhrAPP.send(data);
}
//ELDER BACK BUTTON==================================
function elderBack(thisItem){
	document.getElementById('contentElderBox').innerHTML="";
	document.getElementById('viewElderGuardianDetails').innerHTML="";
	document.getElementById('viewElderPillPodDetails').innerHTML="";
	document.getElementById('elderReportList').innerHTML="";
	document.getElementById('elderReportAdd').style.display='none';
	document.getElementById('displayElderName').style.display='none';
    document.getElementById('resultElderBox').style.display='block';
	document.getElementById('findElderBox').style.display='block';
	document.getElementById('displayElderNameLabel').innerHTML="";
	document.getElementsByName("coordinator_viewelder_navigator")[0].click();
	for (i = 1 ; i < document.getElementsByName("coordinator_viewelder_navigator").length;i++){
		document.getElementsByName("coordinator_viewelder_navigator")[i].setAttribute("style","display:none");
	}
}

function findGuardian(form){
	var elemF = document.getElementById("guardianNavigator");
	for(n = 1; n < elemF.elements.length;n++){
		elemF.elements[n].setAttribute("style","display:none;");
	}
	elemF.elements[0].setAttribute("style","display:block; background-position:0 0;");
	var resultGuardianBox = document.getElementById("resultGuardianBox");
	resultGuardianBox.innerHTML="";
	resultGuardianBox.style.display="block";
	var contentGuardianBox = document.getElementById("contentGuardianBox");
	contentGuardianBox.style.display="none";
	var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","findGuardian");
	data.append("arg",form.guardianName.value);
	//alert(form.name);
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4 && xhr.status==200){
			resultGuardianBox.innerHTML= xhr.responseText.trim();
		}
	}
	xhr.open('post','queryGuardian.php',true);
	xhr.send(data);
	
}

function viewGuardianDetails(innerh){
	var elemF = document.getElementById("guardianNavigator");
	for(n = 0; n < elemF.elements.length;n++){
		elemF.elements[n].setAttribute("style","display:block;");
	}
	elemF.elements[0].setAttribute("style","display:block; background-position:0 0;");
	var resultGuardianBox = document.getElementById("resultGuardianBox");
	resultGuardianBox.innerHTML="";
	resultGuardianBox.style.display="none";
	var contentGuardianBox = document.getElementById("contentGuardianBox");
	contentGuardianBox.style.display="block";
	var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getGuardian");
	data.append("arg",innerh);
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4 && xhr.status==200){
			contentGuardianBox.innerHTML= xhr.responseText.trim();	
			//viewGuardianElderDetails(document.getElementById("GuardianIDx").innerHTML);	
		}
	}
	xhr.open('post','queryGuardian.php',true);
	xhr.send(data);
	
}

function reportUploaderClick(){
	document.getElementById("elderReportAddButton").click();
}

function reportFileChange(thisItem){
	document.getElementById("elderReportAddButtonFake").value = thisItem.value;
	document.getElementById("elderReportAddButtonFake").style.width = "300px;";
}

function guardianNew(){
	document.getElementById("viewElderRegisterNew_GuardianIDText").value="";
	document.getElementById("viewElderRegisterNew_GuardianDetails").style.display="block";
    document.getElementById("viewElderRegisterNew_GuardianID").style.display="none";
	guardianType="new";
	var elderDetails = document.getElementsByName("RegisterGuardianField");
	var i;
	for(i=0;i<elderDetails.length;i++){
		elderDetails[i].setAttribute("required","required");
	}
}

function guardianExsist(){
	guardianType="exsist";
	document.getElementById("viewElderRegisterNew_GuardianDetails").style.display="none";
    document.getElementById("viewElderRegisterNew_GuardianID").style.display="block";
	document.getElementById("viewElderRegisterNew_GuardianIDResult").innerHTML = "";
	var elderDetails = document.getElementsByName("RegisterGuardianField");
	var i;
	for(i=0;i<elderDetails.length;i++){
		elderDetails[i].removeAttribute("required");
	}
}

function findGuardianID(){
	document.getElementById("viewElderRegisterNew_GuardianIDResultH").value ="";
	document.getElementById("viewElderRegisterNew_GuardianIDResult").innerHTML = "";
	var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	var ID = document.getElementById("viewElderRegisterNew_GuardianIDText").value;
	if(ID.length > 0){
		data.append("func","fillGuardian");
		data.append("arg",ID);
		var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		xhr.onreadystatechange = function(){
			if(xhr.readyState==4 && xhr.status==200){
				if(xhr.responseText.trim().indexOf("#") != -1){
					var resp = xhr.responseText.trim().split("#");
					document.getElementById("viewElderRegisterNew_GuardianIDResultH").value = resp[0];
					document.getElementById("viewElderRegisterNew_GuardianIDResult").innerHTML = resp[1];
				}
				else{document.getElementById("viewElderRegisterNew_GuardianIDResult").innerHTML = xhr.responseText.trim();}
			}
		}
		xhr.open('post','queryGuardian.php',true);
		xhr.send(data);
	}
	else{document.getElementById("viewElderRegisterNew_GuardianIDResult").innerHTML = "required field";}
}

function dateIDMatch(datex,idx){
	var monthList = [31,29,31,30,31,30,31,31,30,31,30,31];
	var year = getFullYear(datex).toString;
	var month = getMonth(datex);
	var day = getDate(datex);
	var daySerial = 0;
	for( i = 0; i <month-1;i++){
		daySerial = daySerial + monthList[i];
	}
	daySerial = daySerial+ day;
	serial = substr(year,2);
	serial = serial.daySerial;
	return (serial == substr(idx,0,5));
}

function elderNICBlur(val){
	elderAddNIC = false;
	document.getElementsByName("RegisterElderField")[4].setAttribute("style","background-position:right -40px");
	if(document.getElementsByName("RegisterElderField")[4].checkValidity() == true){
		var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		data.append("func","exsists");
		data.append("attr","NIC");
		data.append("arg",val);
		xhr.onreadystatechange = function(){
			if(xhr.readyState==4 && xhr.status==200){
				if(xhr.responseText.trim() == "valid"){	
					elderAddNIC = false; //guardian exist
					document.getElementsByName("RegisterElderField")[4].setAttribute("style","background-position:right -20px");
				}
				else {
					elderAddNIC = true;
					document.getElementsByName("RegisterElderField")[4].setAttribute("style","background-position:right 0px");
				}
			}
		}
		xhr.open('post','queryElder.php',true);
		xhr.send(data);
	}
}

function guardianNICBlur(val){
	guardianAddNIC = false;
	document.getElementsByName("RegisterGuardianField")[2].setAttribute("style","background-position:right -40px");
	if(document.getElementsByName("RegisterGuardianField")[2].checkValidity() == true){
		var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		data.append("func","exsists");
		data.append("attr","NIC");
		data.append("arg",val);
		xhr.onreadystatechange = function(){
			if(xhr.readyState==4 && xhr.status==200){
				if(xhr.responseText.trim() == "valid"){	
					guardianAddNIC = false;
					document.getElementsByName("RegisterGuardianField")[1].setAttribute("style","background-position:right -20px");
				}
				else {
					guardianAddNIC = true;
					document.getElementsByName("RegisterGuardianField")[1].setAttribute("style","background-position:right 0px");
				}
			}
		}
		xhr.open('post','queryGuardian.php',true);
		xhr.send(data);
	}
}

function propicSelClick(element){
	document.getElementById("registerElderPropicFile").click();
}

function propicSelChange(element){
	var reader = new FileReader();
	reader.onload = function (e) {
		document.getElementById("registerElderPropic").setAttribute("src",e.target.result);
	}
	reader.readAsDataURL(element.files[0]);
}

function clearImage(){
	document.getElementById("registerElderPropic").setAttribute("src","images/propic.png");
	document.getElementById("registerElderPropicFile").value="";
}

function elderFieldGender(){
	alert(document.getElementsByName("RegisterElderField_G").checked);
}

//================================ Devices =======================================

function addNewPillPod(){
	if (document.viewPillPodAdd.viewPillPodAdd_accessNumber.value.length !=10){
		alert("Check Access Number!");
	}
	else{
		
		var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var data = new FormData();
		data.append("func","addDevice");
		data.append("compartments",document.viewPillPodAdd.viewPillPodAdd_compartments.value);
		data.append("accessnumber",document.viewPillPodAdd.viewPillPodAdd_accessNumber.value);
		xhr.onreadystatechange = function(){
			if(xhr.readyState==4 && xhr.status==200){
				if(xhr.responseText.trim() != "error"){	
					document.viewPillPodAdd.viewPillPodAdd_compartments.value="5";
					document.viewPillPodAdd.viewPillPodAdd_accessNumber.value="";
					alert(xhr.responseText.trim());
				}
				else alert("Unknown Error Occured!");
			}
		}
		xhr.open('post','queryDevice.php',true);
		xhr.send(data);
			
		}
}

function viewDevices(){
	document.getElementById("viewPillPodInventory").innerHTML = '<img src="images/loader.gif" class="loaderGif" id="viewElderDetails_Loader">';
	var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getDevices");
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4 && xhr.status==200){
			document.getElementById("viewPillPodInventory").innerHTML = xhr.responseText.trim();
		}
	}
	xhr.open('post','queryDevice.php',true);
	xhr.send(data);
}

function switchTable(from, to){
	document.getElementById(from).style.display="none";
	document.getElementById(to).style.display="block";
}

//===================================Appointment======================================

function physiciansLoad(){
	var listBox = document.getElementsByName("viewAppointmentsCalenderFilter_Filter")[0];
	var listBox1 = document.getElementsByName("viewAppointmentsToday_Filter")[0];
	var listBox2 = document.getElementsByName("viewAppointmentsAll_Filter")[0];
	var listBox3 = document.getElementsByName("viewAppointmentsCalenderAddElem")[0];
	var phyList = document.getElementById("frontPhysicianList");
	listBox.innerHTML = listBox.innerHTML+phyList.innerHTML;
	listBox1.innerHTML=phyList.innerHTML;
	listBox2.innerHTML=phyList.innerHTML;
	listBox3.innerHTML=listBox3.innerHTML+phyList.innerHTML;
	phyList.innerHTML = "";
}

function calenderFilterClick(){
	var xhrCFC = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getHolidays");
	data.append("arg",document.getElementsByName("viewAppointmentsCalenderFilter_Filter")[0].value);
	document.getElementById("viewAppointmentsCalenderEntries").style.display = "none";
	xhrCFC.onreadystatechange = function(){
		if(xhrCFC.readyState==4 && xhrCFC.status==200){
			document.getElementById("viewAppointmentsCalenderEntries").innerHTML= xhrCFC.responseText.trim();
			document.getElementById("viewAppointmentsCalenderEntries").style.display = "block";
		}
	}
	xhrCFC.open('post','queryCalender.php',true);
	xhrCFC.send(data);
}

function calenderAddClick(){
	document.getElementById("viewAppointmentsCalenderFilter").style.display = "none";
	document.getElementById("viewAppointmentsCalenderEntries").style.display = "none";
	document.getElementById("viewAppointmentsCalenderAdd").style.display = "inline-block";
}

function calenderNewCancel(){
	document.getElementById("viewAppointmentsCalenderFilter").style.display = "inline-block";
	var items = document.getElementsByName("viewAppointmentsCalenderAddElem");
	items[0].value="general"
	items[1].value=items[2].value=items[3].value=items[4].value="";
	document.getElementById("viewAppointmentsCalenderAdd").style.display = "none";
}

function calenderFilterSubmit(){
	document.getElementById("viewAppointmentsCalenderAdd").style.display = "none";
	var items = document.getElementsByName("viewAppointmentsCalenderAddElem");
	var xhrACH = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","addHoliday");
	data.append("arg",items[0].value);
	data.append("arg1",items[1].value);
	data.append("arg2",items[2].value);
	data.append("arg3",items[3].value);
	data.append("arg4",items[4].value);
	document.getElementById("viewAppointmentsAllResults").style.display = "none";
	xhrACH.onreadystatechange = function(){
		if(xhrACH.readyState==4 && xhrACH.status==200){
			alert(xhrACH.responseText.trim());
			calenderNewCancel();
		}
	}
	xhrACH.open('post','queryCalender.php',true);
	xhrACH.send(data);
}

function getTodaysAppointments(){
	var xhrGTA = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getToday");
	data.append("arg",document.getElementsByName("viewAppointmentsToday_Filter")[0].value);
	document.getElementById("viewAppointmentsTodayResults").style.display = "none";
	xhrGTA.onreadystatechange = function(){
		if(xhrGTA.readyState==4 && xhrGTA.status==200){
			document.getElementById("viewAppointmentsTodayResults").innerHTML= xhrGTA.responseText.trim();
			document.getElementById("viewAppointmentsTodayResults").style.display = "inline-block";
		}
	}
	xhrGTA.open('post','queryAppointment.php',true);
	xhrGTA.send(data);
}

function getAllAppointments(){
	var xhrGAA = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getAll");
	data.append("arg",document.getElementsByName("viewAppointmentsAll_Filter")[0].value);
	data.append("arg1",document.getElementsByName("viewAppointmentsAll_Filter")[1].value);
	document.getElementById("viewAppointmentsAllResults").style.display = "none";
	xhrGAA.onreadystatechange = function(){
		if(xhrGAA.readyState==4 && xhrGAA.status==200){
			document.getElementById("viewAppointmentsAllResults").innerHTML= xhrGAA.responseText.trim();
			document.getElementById("viewAppointmentsAllResults").style.display = "inline-block";
		}
	}
	xhrGAA.open('post','queryAppointment.php',true);
	xhrGAA.send(data);
}

function viewAppointmentsAllFIlterClick(){
	document.getElementById("monthView").style.display="none";
}

function loadVisitorAppointments(){
	document.getElementById("viewAppointmentsvisiitors").innerHTML= '<img src="images/loader.gif" class="loaderGif" id="viewAppointmentsvisiitors_Loader">';
	var xhrLVA = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getVisitorsApp");
	xhrLVA.onreadystatechange = function(){
		if(xhrLVA.readyState==4 && xhrLVA.status==200){
			document.getElementById("viewAppointmentsvisiitors").innerHTML= xhrLVA.responseText.trim();
		}
	}
	xhrLVA.open('post','queryAppointment.php',true);
	xhrLVA.send(data);
}
/*===========================================Drug Store =========================================*/

function addNewDrug(){
	var fields = document.getElementsByName("viewDrugAddField");
	if(fields[3].value == ""){
		document.getElementById('drugDropdown1').setAttribute("style", "border-color:Red;");
	}
	else if(fields[4].value == ""){
		document.getElementById('drugDropdown2').setAttribute("style", "border-color:Red;");
	}
	else{
		var xhrAND = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
		var qry ="','"+fields[0].value + "',"; //drug
		qry +="'"+fields[1].value + "',"; //brand
		qry +=""+fields[2].value + ","; //weight
		qry +="'"+fields[3].value + "',"; //color
		qry +="'"+fields[4].value + "',"; //shape
		qry +="'"+fields[5].value + "');"; //Description
		var data = new FormData();
		data.append("func","saveDrug");
		data.append("qry",qry);
		xhrAND.onreadystatechange = function(){
			if(xhrAND.readyState==4 && xhrAND.status==200){
				alert(xhrAND.responseText.trim());
			}
		}
		xhrAND.open('post','queryDrug.php',true);
		xhrAND.send(data);
		resetDrugFields();
	}
}

function resetDrugFields(){
	document.viewDrugAdd.reset();
	document.getElementById('viewDrugAddField3').innerHTML='Select Color';
	document.getElementById('viewDrugAddField4').innerHTML='Select Shape';
	document.getElementById('drugDropdown1').setAttribute("style", "border-color:#4eabf9;");
	document.getElementById('drugDropdown2').setAttribute("style", "border-color:#4eabf9;");
}
function viewDrugs(){
	document.getElementById("viewDrugInventory").innerHTML = '<img src="images/loader.gif" class="loaderGif" id="viewElderDetails_Loader">';
	var xhr = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getDrug");
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4 && xhr.status==200){
			document.getElementById("viewDrugInventory").innerHTML = xhr.responseText.trim();
		}
	}
	xhr.open('post','queryDrug.php',true);
	xhr.send(data);
}
/*===========================================Messages=========================================*/

function viewVisitorMessages(){
	var msgBox = document.getElementById("viewMessagesVisitors");
	msgBox.innerHTML = loaderGif;
	var xhrvvm = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getMessages");
	xhrvvm.onreadystatechange = function(){
		if(xhrvvm.readyState==4 && xhrvvm.status==200){
			msgBox.innerHTML = xhrvvm.responseText.trim();
		}
	}
	xhrvvm.open('post','queryMessages.php',true);
	xhrvvm.send(data);
}

function viewGuardianMessages(){
	var msgBoxg = document.getElementById("viewMessagesGuardian");
	msgBoxg.innerHTML = loaderGif;
	var xhrVGMS = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","getGMessages");
	xhrVGMS.onreadystatechange = function(){
		if(xhrVGMS.readyState==4 && xhrVGMS.status==200){
			msgBoxg.innerHTML = xhrVGMS.responseText.trim();
		}
	}
	xhrVGMS.open('post','queryMessages.php',true);
	xhrVGMS.send(data);
}

function viewGMessage(gID){ // view messages from particular guardian
	var msgBoxg = document.getElementById("viewMessagesGuardian");
	msgBoxg.innerHTML = loaderGif;
	var xhrVGM = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","viewGMessages");
	data.append("arg",gID);
	xhrVGM.onreadystatechange = function(){
		if(xhrVGM.readyState==4 && xhrVGM.status==200){
			msgBoxg.innerHTML = xhrVGM.responseText.trim();
		}
	}
	xhrVGM.open('post','queryMessages.php',true);
	xhrVGM.send(data);
}

function replyMessage(id,index,elem){
	var rep = document.getElementsByClassName('msgReplybox')[index];
	rep.style.display="block";
	rep.innerHTML="<textarea id='msgReplyText'></textarea><br><input type='button' onClick='msgSend(" + index + "," + id + ");' value='Send' style='float:right;'><input type='button' onClick='msgCancel(" + index + ");' value='Cancel' style='float:right;margin-right:5px;'>";
	elem.style.display="none";
}

function msgCancel(index){
	var rep = document.getElementsByClassName('msgReplybox')[index];
	rep.style.display="none";
	rep.innerHTML="";
	document.getElementsByClassName('msgReply')[index].style.display="inline-block";
}

function msgSend(index,id){
var rep = document.getElementsByClassName('msgReplybox')[index];
var xhrMS = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
var data = new FormData();
	data.append("func","sendMessage");
	data.append("cont",document.getElementById('msgReplyText').value);
	data.append("index",id);
	xhrMS.onreadystatechange = function(){
		if(xhrMS.readyState==4 && xhrMS.status==200){
			if(xhrMS.responseText.trim() != "error"){
				alert("Message Sent Successfully!");
				rep.innerHTML= xhrMS.responseText.trim();
				document.getElementsByClassName('msgReply')[index].style.display="none";
				
			}
			else alert("Message Send Fail!");	
		}
	}
	xhrMS.open('post','queryMessages.php',true);
	xhrMS.send(data);
}
/*===========================================My Profile=========================================*/
function changePw(form){
	var fields = document.getElementsByName("MyProfileCPField");
	
	var xhrCP = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var pass = fields[0].value; //old password
	var newp = fields[1].value; //new password
	var conf = fields[2].value; //retyped new password 
	
	var data = new FormData();
	data.append("func","chgpw");
	data.append("pass",pass);
	data.append("new",newp);
	data.append("conf",conf);
	xhrCP.onreadystatechange = function(){
		if(xhrCP.readyState==4 && xhrCP.status==200){
			alert(xhrCP.responseText.trim());
			document.myProfileChangePW.reset();
		}
	}
	xhrCP.open('post','userSecurity.php',true);
	xhrCP.send(data);
}