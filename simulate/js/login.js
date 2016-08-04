var xmlHttp
var g_user
window.onload=function(){
	/*var div1=document.getElementById('div1')
	div1.onclick=letstart*/
}
function login(){
    var user=$("input_LoginName")
    var pwd=$("input_PassWord")
    g_user=user

    checkUser(user, pwd)
    //
}
function register(){
	var user=$("input_LoginName")
    var pwd=$("input_PassWord")
    var pwd2=$("input_PassWord2")
	if (pwd!=pwd2)
	{
		alert(" Your new and confirm password are different. Please enter your passwords again.")
		return
	}

    registerUser(user, pwd, pwd2)
}

function $(s){
	return document.getElementById(s).value
}
function registerUser(user, pwd){
	if (user.length==0||pwd.length==0)
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
	
	var url="../php/authentication.php"
	url=url+"?t="+1
	url=url+"&u="+user
	url=url+"&p="+pwd
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function checkUser(user, pwd)	
{
	if (user.length==0||pwd.length==0)
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
	
	var url="../php/authentication.php"
	url=url+"?t="+2
	url=url+"&u="+user
	url=url+"&p="+pwd
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}
function foreach()	//循环删除cookie
{
  var strCookie=document.cookie;
  var arrCookie=strCookie.split("; "); // 将多cookie切割为多个名/值对
  for(var i=0;i <arrCookie.length;i++)
	{ // 遍历cookie数组，处理每个cookie对
	    var arr=arrCookie[i].split("=");
	    if(arr.length>0)
	    DelCookie(arr[0]);
	}
 
}
function GetCooki(offset)
{
	var endstr = document.cookie.indexOf (";", offset);
	if (endstr == -1)
		endstr = document.cookie.length;
		return decodeURIComponent(document.cookie.substring(offset, endstr));
}
function DelCookie(name)
{
	var exp = new Date();
	exp.setTime (exp.getTime() - 1);
	var cval = GetCookie (name);
	document.cookie = name + "=" + cval + "; expires="+ exp.toGMTString();
}

function GetCookie(name)
{
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen)
	{
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg)
			return GetCooki (j);
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0) 
			break;
	}
	return null;
}
function setCookie(){
	foreach()
	document.cookie = "pcsimulate=login=username=" +g_user+"; path=/";
}

function stateChanged() 
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		var json = eval('(' + xmlHttp.responseText + ')')
		if ("save" in json){
			if (json["save"]==0){
				alert("register success!")
				window.location.href="login.html"
			}
			if (json["save"]==1)
				alert("user is registered! input a new user please!")
		}else if("load" in json){
			if (json["load"]==0){
				setCookie()
				window.location.href="../php/mydevice.php"
			}
			if (json["load"]==1)
			alert("user error!")
			if (json["load"]==2)
			alert("password error!")
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