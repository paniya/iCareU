var slideIndex = 0;
slideMe();
selected_menu="front_home_content";
function slideMe(){
	var i;
	//if (document.getElementById("section_name").innerHTML == "frontPage"){	
		var slides = document.getElementsByClassName("front_slider");
		if (slides.length > 0){
			for (i = 0; i < slides.length; i++){
				slides[i].style.opacity = "0";
			}
			slideIndex++;
			if (slideIndex > slides.length){
				slideIndex = 1;
			}
			//document.getElementById("testvar").innerHTML = slideIndex;
			slides[slideIndex - 1].style.opacity = "100";
		}
		setTimeout(slideMe, 10000);
	//}
}

function moreStories(index){
	document.getElementById("front_home_ourservices").style.display = "none";
	var r = document.getElementsByClassName("front_home_service_story")[index].style.display = "block";
	
}

function hideStory(elem){
	elem.parentNode.parentNode.style.display = "none";
	document.getElementById("front_home_ourservices").style.display = "block";
}

function contactSubmit(){
	document.front_home_contact.setAttribute("novalidate","true");
	var elems = document.getElementsByName("home_contact_field");
	var xhrCS = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","sendMessage");
	data.append("sender",elems[0].value);
	data.append("phone",elems[1].value);
	data.append("email",elems[2].value);
	data.append("subject",elems[3].value);
	data.append("message",elems[4].value);
	for (i = 0; i< elems.length;i++){
		elems[i].value="";
	}
	xhrCS.onreadystatechange = function(){
		if(xhrCS.readyState==4 && xhrCS.status==200){
			alert(xhrCS.responseText.trim());
		}
	}
	xhrCS.open('post','queryMessages.php',true);
	xhrCS.send(data);
}

function appointmentSubmit(){
	
        document.front_home_contact.setAttribute("novalidate","true");
	var elems = document.forms["front_home_appointment"].getElementsByTagName("*");
        var xhrx = (window.XMLHttpRequest)? new XMLHttpRequest(): new activeXObject("Microsoft.XMLHTTP");
	var data = new FormData();
	data.append("func","setVisitorsAppointment");
	data.append("phy",document.getElementsByName("front_home_appointment_field")[0].value);
        data.append("date",document.getElementsByName("front_home_appointment_field")[1].value);
        data.append("reason",document.getElementsByName("front_home_appointment_field")[2].value);
        data.append("mail",document.getElementsByName("front_home_appointment_field")[3].value);
        data.append("phonenumber",document.getElementsByName("front_home_appointment_field")[4].value);
        alert("Your appointment has been set!");
	xhrx.open('post','queryAppointment.php',true);
	xhrx.send(data);
        for (i = 1; i< elems.length-1;i++){
		elems[i].value="";
	}
}
	
function physiciansLoad(){
	var listBox = document.getElementsByName("front_home_appointment_field")[0];
	var phyList = document.getElementById("frontPhysicianList");
	listBox.innerHTML= "<option disabled selected hidden value='-1'>Select Physician</option><br>" + phyList.innerHTML;
	phyList.innerHTML = "";
}

function phylistBlur(){
	var listBox = document.getElementsByName("front_home_appointment_field")[0];
	listBox.required= "required";
}


