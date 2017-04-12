var selected_menu="";
var selected_submenu;
var menu_index = 0;
//MonthView Variables
minYear = 1993;
minMonth = 1; //(0-11)
minDate = 10;
maxYear = 2020;
maxMonth = 8;
maxDate = 30;
var calTarget = "";
var dx = new Date();
var dxNow = new Date();
var nod = [31,28,31,30,31,30,31,31,30,31,30,31];
var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
var cells = document.getElementsByClassName("dateCell");
//==================================================================

function GetChar(event,elem){
	if ((event.keyCode == 13) && (elem.name =="login_pass")){
		loginMe(document.getElementById("loginForm"));
	}
	else if ((event.keyCode == 13) && (elem.name =="patient_name")){
		//alert(document.findElderBox.name);
		findElder(document.getElementById("findElderBoxx"));
	}
}
//======================Main Menu================================

function menuItemClick(nextMenu,elem,myIndex){
	menu_index = myIndex;
	var eleList = document.getElementsByClassName("menuItem");
	for(i = 0; i < eleList.length;i++){
		eleList[i].removeAttribute("style");
	}
	elem.setAttribute("style","color:#4eabf9;font-weight:bolder;");
	//focusing default
	viewMenu(nextMenu);
	switch (nextMenu){
		//>>>>>>>>>>>>>>>>> coordinator
		case "viewElderProfile" :
								document.getElementById("viewElderProfile").style.display="block";
								//document.getElementById("addElderProfile").style.display="none";
								break;
		case "viewGuardianProfile" :
								document.getElementById("viewGuardianProfile").style.display="block";
								//document.getElementById("addGuardianProfile").style.display="none";
								break;
	}
}

function viewMenu(nextMenu){
	if(selected_menu !=''){
		var this_menu = document.getElementById(selected_menu);
		this_menu.style.display = "none";
	}
	var next_menu = document.getElementById(nextMenu);
	next_menu.style.display = "block";
	selected_menu = nextMenu;
}

function viewSub(nextSubmenu,ele){
	//alert(selected_submenu[menu_index]);
	if(selected_submenu[menu_index] !=''){
		var this_submenu = document.getElementById(selected_submenu[menu_index]);
		this_submenu.style.display = "none";
	}
	var next_submenu = document.getElementById(nextSubmenu);
	if (next_submenu != null)
		next_submenu.style.display = "block";
	var eleList = document.getElementsByName(ele.name);
	for(i = 0; i < eleList.length;i++){
		eleList[i].removeAttribute("class");
	}
	ele.setAttribute("class","navigator_selected");
	selected_submenu[menu_index] = nextSubmenu;
	switch (nextSubmenu){
		//>>>>>>>>>>>>>>>>> coordinator
		case "viewDrugInventory" :viewDrugs();
								break;
		case "viewAppointmentsToday" :
								//document.getElementById("viewGuardianProfile").style.display="block";
								//document.getElementById("addGuardianProfile").style.display="none";
								break;
		case "viewPillPodInventory" :viewDevices();break;
	}
}

function dropDownItemClick(elem,val,node,index,controller=""){
	document.getElementsByName(node)[index].value = val;
	document.getElementById(node+index.toString()).innerHTML = elem.childNodes[1].innerHTML;
	if (controller !="") document.getElementById(controller).setAttribute("style", "border-color:#4eabf9;");
}

function dropDownClick(elem) {
    document.getElementById(elem).classList.toggle("show");
}

window.onclick = function(event) {
  if (!event.target.matches('.dropdown')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
  if (!event.target.matches('.monthView') && !event.target.matches('.monthViewTarget') && !event.target.matches('.monthViewControl')) {
	var monthViews = document.getElementsByClassName("monthView");
    var i;
    for (i = 0; i < monthViews.length; i++) {
      var openMonthView = monthViews[i];
      if (openMonthView.style.display="block") {
        openMonthView.style.display="none";
      }
    }
  }
}

function listBox(elem){
	var elems = elem.parentNode.getElementsByTagName("tr");
	for(i = 0; i < elems.length;i++){
		elems[i].removeAttribute("style");
	}
	elem.setAttribute("style","background-color:#4eabf9;");
}

//================== Calender COntrol =====================
function Calander (div, minYear ,minMonth,minDate,maxYear,maxMonth,maxDate,target = "",acceptNull=false) {
    this.minYear = minYear;
	this.minMonth = minMonth; //(0-11)
	this.minDate = minDate;
	this.maxYear = maxYear;
	this.maxMonth = maxMonth;
	this.maxDate = maxDate;
	this.target = document.getElementById(target);
	this.div = div;
	this.acceptNull = acceptNull;
	this.dx = new Date();
	this.dxNow = new Date();
	this.cells = document.getElementById(div).getElementsByClassName("dateCell");
	control = document.getElementById(div);
	var tableCells="";
		for (i = 0; i< 5;i++){
			tableCells = tableCells + '<tr>';
			for(j = 0; j <7;j++){
				tableCells = tableCells + '<td class="dateCell" onClick="'+div+'.monthCellClick(this);"></td>'
			}
			tableCells = tableCells + '</tr>';
		}
		control.innerHTML='<table width="100%">'+
			'<tr><td align="left" width="25px"><input type="button" class="prevYear monthViewControl" value="<" onClick="'+div+'.lowerYear();"></td><td align="center" width="150px"><label class="yearLabel">2016</label></td><td align="right" width="25px"><input type="button" class="nextYear monthViewControl" value=">" onClick="'+div+'.higherYear();"></td></tr>'+
			'<tr><td align="left" width="25px"><input type="button" class="prevMonth monthViewControl" value="<" onClick="'+div+'.lowerMonth();"></td><td align="center" width="150px"><label class="monthLabel">August</label></td><td align="right" width="25px"><input type="button" class="nextMonth monthViewControl" value=">" onClick="'+div+'.higherMonth();"></td></tr>'+
			'<tr><td colspan="3"><table class="dates" width="100%">'+
			'<tr><th>S</th> <th>M</th> <th>T</th> <th>W</th> <th>T</th> <th>F</th> <th>S</th></tr>'+
			tableCells + '</table></td></tr>'+
		'</table>';
	control.setAttribute("class","monthView");
	control.setAttribute("style","display:none;");
	control.setAttribute("tabindex","0");
	this.target.setAttribute("class","monthViewTarget");
	this.target.setAttribute("readonly","readonly");
	//control.setAttribute("onblur",div+".showCalander(false);");
	this.checkLeap = function(){
		if(this.dx.getFullYear()%4 ==0){
			if(this.dx.getFullYear()%400 ==0)
				return 29;
			else if(this.dx.getFullYear()%100 == 0)
				return 28;
			else
				return 29;
		}
		else return 28;
	};
	this.me = document.getElementById(this.div);
	this.showCalander = function(vis){
		if(vis){
			this.me.style.display="block";
			if (acceptNull) this.target.value="";
		}
		else{
			this.me.style.display="none";
		}
	}
	
	this.toggle = function(){
		if(this.me.style.display=="none")this.showCalander(true);
		else is.showCalander(false);
	}
	
	this.drawDates = function(){
		nod[1] = this.checkLeap();
		var startX = this.dx.getDay()
		var markToday = (this.dx.getMonth() == this.dxNow.getMonth()) && (this.dx.getFullYear() == this.dxNow.getFullYear());
		var markMin = (this.dx.getMonth() == minMonth) && (this.dx.getFullYear() == minYear);
		var markMax = (this.dx.getMonth() == maxMonth) && (this.dx.getFullYear() == maxYear);
		for(i = 0 ; i< this.cells.length; i++){
			this.cells[i].innerHTML= "&nbsp;";
			this.cells[i].setAttribute("class","disabledCell dateCell");
		}
		if(nod[this.dx.getMonth()] + startX+1 > 35){
			for(i = startX ; i< 35; i++){
				this.cells[i].innerHTML= i-startX+1;
				this.cells[i].setAttribute("class","dateCell");
				if(markToday && (this.cells[i].innerHTML == this.dxNow.getDate()))
					this.cells[i].setAttribute("class","dateCell today");
				if(markMin && (this.cells[i].innerHTML < this.minDate))
					this.cells[i].setAttribute("class","dateCell disabledCell");
				if(markMax && (this.cells[i].innerHTML > this.maxDate))
					this.cells[i].setAttribute("class","dateCell disabledCell");
			}
			for(i = 0 ; i< nod[this.dx.getMonth()] + startX - 35; i++){
				this.cells[i].innerHTML=nod[this.dx.getMonth()]-((nod[this.dx.getMonth()] + startX - 35)-i)+1;
				this.cells[i].setAttribute("class","dateCell");
				if(markToday && (this.cells[i].innerHTML == this.dxNow.getDate()))
					this.cells[i].setAttribute("class","dateCell today");
				if(markMin && (this.cells[i].innerHTML <this.minDate))
					this.cells[i].setAttribute("class","dateCell disabledCell");
				if(markMax && (this.cells[i].innerHTML > this.maxDate))
					this.cells[i].setAttribute("class","dateCell disabledCell");
			}
		}
		else{
			for(i = startX ; i< nod[this.dx.getMonth()] + startX; i++){
				this.cells[i].innerHTML= i-startX+1;
				this.cells[i].setAttribute("class","dateCell");
				if(markToday && (this.cells[i].innerHTML == this.dxNow.getDate()))
					this.cells[i].setAttribute("class","dateCell today");
				if(markMin && (this.cells[i].innerHTML < this.minDate))
					this.cells[i].setAttribute("class","dateCell disabledCell");
				if(markMax && (this.cells[i].innerHTML > this.maxDate))
					this.cells[i].setAttribute("class","dateCell disabledCell");
			}
		}	
		document.getElementById(this.div).getElementsByClassName("monthLabel")[0].innerHTML=months[this.dx.getMonth()];
		document.getElementById(this.div).getElementsByClassName("yearLabel")[0].innerHTML=this.dx.getFullYear();
	};
	
	this.lowerMonth = function(){
		if(this.dx.getFullYear() == this.minYear){
			if(this.minMonth !=  this.dx.getMonth()){ //next click will be the min Month
				this.dx.setMonth(this.dx.getMonth()-1);
			}
		}
		else
			this.dx.setMonth(this.dx.getMonth()-1);
		this.drawDates();
	};
	
	this.lowerMonth = function (){
		if(this.dx.getFullYear() == this.minYear){
			if(this.minMonth !=  this.dx.getMonth()){ //next click will be the min Month
				this.dx.setMonth(this.dx.getMonth()-1);
			}
		}
		else
			this.dx.setMonth(this.dx.getMonth()-1);
		this.drawDates();
	};
	
	this.higherMonth = function (){
		if(this.dx.getFullYear() == this.maxYear){
			if(this.maxMonth !=  this.dx.getMonth()){ //next click will be the min Month
				this.dx.setMonth(this.dx.getMonth()+1);
			}
		}
		else
			this.dx.setMonth(this.dx.getMonth()+1);
		this.drawDates();
	};
	

	this.lowerYear = function (){
		if(this.minYear ==  this.dx.getFullYear()-1){ //next click will be the min year
			this.dx.setFullYear(this.dx.getFullYear()-1);
			while(this.dx.getMonth() < this.minMonth){
				this.dx.setMonth(this.dx.getMonth()+1);
			}
		}
		else if(this.minYear <  this.dx.getFullYear()){
			this.dx.setFullYear(this.dx.getFullYear()-1);
		}
		this.drawDates();
	}
	this.higherYear = function (){
		if(this.maxYear ==  this.dx.getFullYear()+1){ //next click will be the max year
			this.dx.setFullYear(this.dx.getFullYear()+1);
			while(this.dx.getMonth() > this.maxMonth)
				this.dx.setMonth(this.dx.getMonth()-1);
		}
		else if(this.maxYear >  this.dx.getFullYear()){
			this.dx.setFullYear(this.dx.getFullYear()+1);
		}
		this.drawDates();
	}

	this.monthCellClick = function (elem){
		if (!elem.classList.contains("disabledCell")){
			if (this.target == "")
				alert(this.dx.getFullYear() + "-" + this.dx.getMonth() + "-" + elem.innerHTML);
			else {
				this.target
				this.target.value = this.dx.getFullYear() + "-" + (this.dx.getMonth() +1< 10 ? "0"
				+(this.dx.getMonth()+1) : (this.dx.getMonth()+1))  + "-" + (elem.innerHTML < 10 ? "0" + elem.innerHTML : elem.innerHTML);
			}
			this.showCalander(false);
		}	
	}
	this.drawDates();
}