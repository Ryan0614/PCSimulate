var xmlHttp
var isshow=0;//0小窗口没有显示，1小窗口已显

window.onload=function(){
	if(!checkCookie()){
		window.location.href="../public/login.html"
	}
	$("div_username").innerHTML=getUserName()
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

function addDevice(){
	if($("tab1").value == 0){
		alert("请选择添加的设备类型!")
		return
	}
	addDevice_AJ($("tab1").value)
}

function delDevice(value){
	delDevice_AJ(value)
}


function $(s){
	return document.getElementById(s)
}
function addDevice_AJ(deviceType)
{
	if (deviceType.length==0)
	{ 
		alert("user or password is null!")
		return
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	} 
	
	var url="../php/function.php"
	url=url+"?c="+0xf1
	url=url+"&u="+getUserName()
	url=url+"&t="+deviceType
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function delDevice_AJ(deviceType)
{
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	} 
	
	var url="../php/function.php"
	url=url+"?c="+0xf2
	url=url+"&u="+getUserName()
	url=url+"&t="+deviceType
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}


function stateChanged() 
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		var json = eval('(' + xmlHttp.responseText + ')')
		if ("response" in json){
			alert(json["response"])
			location.reload(true)
		}
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


// function creatediv()
// {            
//     var msgw,msgh,bordercolor;
//     msgw=400;//提示窗口的宽度
//     msgh=505;//提示窗口的高度
//     var sWidth,sHeight;
//     if( top.location == self.location )
//         doc = document;
//     var docElement = doc.documentElement;
//     sWidth=docElement.clientWidth;
//     sHeight = docElement.clientHeight;
//     if( docElement.scrollHeight > sHeight )
//         sHeight = docElement.scrollHeight;
//     var bgObj=document.createElement("div");
//     bgObj.setAttribute("id","bgDiv");
//     bgObj.style.position="absolute";
//     bgObj.style.top="0";
//     bgObj.style.left="0";
//     bgObj.style.background="#777";
//     bgObj.style.filter="progid:DXImageTransform.Microsoft.Alpha(style=3,opacity=25,finishOpacity=75";
//     bgObj.style.opacity="0.6";
//     bgObj.style.width=sWidth + "px";
//     bgObj.style.height=sHeight + "px";
//     bgObj.style.zIndex = "10000";
//     document.body.appendChild(bgObj);
        
//     var msgObj=document.createElement("div");
//     msgObj.setAttribute("id","msgDiv");
//     msgObj.setAttribute("align","center");
//     msgObj.style.position = "absolute";
//     msgObj.style.left = "50%";
//     msgObj.style.background="#fff";
//     msgObj.style.marginLeft = "-200px" ;
//     msgObj.style.top = (document.documentElement.clientHeight/2+document.documentElement.scrollTop-252)+"px";
//     msgObj.style.width = msgw + "px";
//     msgObj.style.height =msgh + "px";
//     msgObj.style.zIndex = "10001";
//     msgObj.innerHTML = "这是弹出的小窗口！<br /><a href=\"javascript:void(0);\" onclick=\"delWinD();return false;\">点我关闭窗口</a>";
//     document.body.appendChild(msgObj);
// }

// function delWinD()
// {
//    document.getElementById("bgDiv").style.display="none";
//    document.getElementById("msgDiv").style.display="none";
//    isshow=0;
// }
// function show()
// {  
//     isshow=1;
//     if(!document.getElementById("msgDiv"))//小窗口不存在
//         creatediv();
//     else
//     {
//         document.getElementById("bgDiv").style.display="";
//         document.getElementById("msgDiv").style.display="";
//         document.getElementById("msgDiv").style.top=(document.documentElement.clientHeight/2+document.documentElement.scrollTop-252)+"px";
//     }  
// }