var xmlHttp
var year
var month
var day
var hours
var minutes
var seconds
function document.onkeydown()//屏蔽键盘F5  
{  
   if(window.event.keyCode == 116)  
   {  
     window.event.keyCode=0;  
     event.cancelBubble=true;  
     return false;  
   }  
} 

window.onload=function(){
	if(!checkCookie()){
		window.location.href="../public/login.html"
	}
	letstart()

	showproduct()
	syncUI()
}

function checkCookie(){
	var strCookie=document.cookie;
	//将多cookie切割为多个名/值对
	var arrCookie=strCookie.split("; ");
	//遍历cookie数组，处理每个cookie对
	var a=arrCookie.length
	for(var i=0;i<arrCookie.length;i++){
		var arr=arrCookie[i].split("=");
		//找到名称为userId的cookie，并返回它的值
		if("pcsimulate"==arr[0]&&"username"==arr[2]){
			if(arr[1]=="login")
				return true;
		}
	}
	return false;
}

function getData(str)
{
	if (str.length==0)
	{ 
		alert(str)
		return
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	} 
	
	var url="../php/function.php"
	url=url+"?c="+str
	url=url+"&t="+0x01
	url=url+"&m="+0x01
	url=url+"&u="+getUserName()
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{
		if (xmlHttp.responseText=="")
			return 
		var json = eval('(' + xmlHttp.responseText + ')')
		if ("log" in json ){
			document.getElementById("log1").innerHTML=json["log"]
		}

		if ("update" in json){
			if (json["update"] == "null")
				return
		}
		if ("wifi" in json){
			// todo process wifi
		}
		if (json["update"] == "status"){
			location.reload(true)
		}

		if ("time" in json ){
			showServerTime(json["time"])
		}
	} 
}
function download(){
	//getData(0xf3)
	window.open("../download/comtest.exe")
}
function reportStatus(str){
	getData(0x32)
}
function mcuTime(){
	getData(0x06)	//6
}
function getUserName(){
	var strCookie=document.cookie;
	var arrCookie=strCookie.split("; ");
	var a=arrCookie.length
	for(var i=0;i<arrCookie.length;i++){
		var arr=arrCookie[i].split("=");
		if("pcsimulate"==arr[0]&&"username"==arr[2]){
			return arr[3];
		}
	}
	return "";
}

function checkTime(i)
{
	if (i<10) 
	{i="0" + i}
	return i
}

function showtime(time){
	var div1=document.getElementById('div_time')
	div1.innerHTML=time
}
function hex2ten(hex){
	return hex.toString(16)
}
function showServerTime(json){
	year=checkTime(hex2ten(json[0]))+checkTime(hex2ten(json[1]))
	month=checkTime(hex2ten(json[2]))
	day=checkTime(hex2ten(json[3]))
	hours=checkTime(hex2ten(json[4]))
	minutes=checkTime(hex2ten(json[5]))
	seconds=checkTime(hex2ten(json[6]))
	time=year+'-'+month+'-'+day +' '+hours+':'+minutes+':'+seconds
	showtime(time);
}

function updateTime(flag=false){
	if (flag){
		now=new Date()
	}else{
		now=new Date(year, month-1, day, hours, minutes, seconds)
		now.setTime(now.getTime()+1000)
	}
	year=now.getFullYear()
	month=checkTime(now.getMonth()+1)
	day=checkTime(now.getDate())
	hours=checkTime(now.getHours())
	minutes=checkTime(now.getMinutes())
	seconds=checkTime(now.getSeconds())
	time=year+'-'+month+'-'+day +' '+hours+':'+minutes+':'+seconds
	showtime(time)
}
function letstart(){
	updateTime(true)
	taskId=setInterval(updateTime,1000)
	taskId2=setInterval(getData, 500, 0xf3)
}
function rcvUartData(str){
	alert(str)
	getData(0xf3)
}
function syncSelect(div, sel){
	var select=document.getElementById(sel)
	var run_st=document.getElementById(div).innerHTML
	for(var i=0; i<select.options.length; i++){  
	    if(select.options[i].innerHTML == run_st){  
	        select.options[i].selected = true
	        break
	    }  
	} 
}
function syncText(div, text){
	var tp=document.getElementById(div).innerHTML
	document.getElementById(text).value=tp
}
function syncCheck(div, check){
	if (document.getElementById(div).innerHTML == "打开"){
		document.getElementById(check).checked=true
	}
}

function setStatus(json)
{
	if(json["cmd"]==6){
		showServerTime(json["payload"])
	}

}

function GetXmlHttpObject()
{
	var xmlHttp=null
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest()
	}
	catch (e)
	{
	// Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP")
		}
		catch (e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP")
		}
	}
	return xmlHttp
}

function showproduct(){
	document.getElementById('div_pro').innerHTML="&nbsp;&nbsp;&nbsp;"+"蒸箱"
}

function syncUI(){
	syncSelect("div0", "sel0")
	syncSelect("div3", "sel1")
	syncSelect("div4", "sel2")

	syncText("div5", "text0")
	syncText("div6", "text1")
	syncText("div7", "text2")
	syncText("div8", "text3")
	syncText("div9", "text4")

	syncCheck("div2", "check1")
	syncCheck("div18", "check2")
	syncCheck("div19", "check3")
	syncCheck("div21", "check4")
}


