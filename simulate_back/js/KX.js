var xmlHttp

function getData(str)
{
	if (str.length==0)
	{ 
		alert(str)
		document.getElementById("div0_0").innerHTML=""
		return
	}
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	} 
	
	var url="protocol.php"
	url=url+"?q="+str
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
} 
function showtime(){
	var now=new Date()
	var year=now.getFullYear()
	var month=now.getMonth()+1
	var day=now.getDate()
	var hours=now.getHours()
	var minutes=now.getMinutes()
	var seconds=now.getSeconds()
	time=year+'/'+month+'/'+day +'/'+hours+':'+minutes+':'+seconds
	var div1=document.getElementById('div0')
	div1.innerHTML=time
}
function letstart(){
taskId=setInterval(showtime,500)
}
function showproduct(){
	var div = document.getElementById('kx').innerHTML
	var arr = div.split("</i>");
	document.getElementById('div00').innerHTML="&nbsp;&nbsp;&nbsp;"+arr[1]
}
window.onload=function(){
	/*var div1=document.getElementById('div1')
	div1.onclick=letstart*/
	letstart()
	showproduct();
	getData(0)
}
function stateChanged() 
{
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		var tt = ""
		var json = eval('(' + xmlHttp.responseText + ')')
		
		switch (json[0])
		{
		case 2:
			tt = "开机"
			break
		case 0:
			tt = "关机"
			break
		case 1:
			tt = "待机"
			break
		default:
			break
		}
		document.getElementById("div0_0").innerHTML=tt

		setStatus(json)
	} 
}

function setStatus(json)
{
	var s = new Array()
	s[0] = "1"
	s[1] = json[1] == 0?"关":"开"
	
	if (json[2]>>7)
	{
		s[2] = "自动模式"
	}else{
		s[2] = "手动模式"+ json[2]
	}
	
	if (json[3] == 0xff)
	{
		s[3] = "本地菜谱"+json[4]
	}else{
		s[3] = "网络菜谱"+json[4]
	}
	s[5] = json[5]+"分钟"
	s[6] = json[6]+":"+json[7]
	s[8] = json[8]+":"+json[9]
	s[10] = json[10]
	s[11] = json[11]
	s[12] = json[12]
	s[13] = json[13]*10+"W"
	s[14] = json[14]*10+"W"
	s[15] = json[15]*2
	s[16] = json[16]
	s[17] = json[17]
	s[18] = json[18]+"%"
	s[19] = json[19]+"%"
	s[20] = json[20]
	s[21] = json[21] == 0?"关":"开"
	s[22] = json[22] == 0?"关":"开"
	s[23] = json[23] == 0?"普通":(json[23] == 1?"二级加热":"微压")
	s[24] = json[24]
	s[25] = json[25] == 0?"关":"开"
	s[26] = json[26] == 0?"关":"开"
	s[27] = json[27]
	s[28] = json[28]
	s[29] = json[29] == 0?"不支持":"支持"


	document.getElementById("div0_1").innerHTML=s[1]
	document.getElementById("div0_2").innerHTML=s[2]
	document.getElementById("div0_3").innerHTML=s[3]
	document.getElementById("div0_5").innerHTML=s[5]
	document.getElementById("div0_6").innerHTML=s[6]
	document.getElementById("div0_8").innerHTML=s[8]
	document.getElementById("div0_10").innerHTML=s[10]
	document.getElementById("div0_11").innerHTML=s[11]
	document.getElementById("div0_12").innerHTML=s[12]
	document.getElementById("div0_13").innerHTML=s[13]
	document.getElementById("div0_14").innerHTML=s[14]
	document.getElementById("div0_15").innerHTML=s[15]
	document.getElementById("div0_16").innerHTML=s[16]
	document.getElementById("div0_17").innerHTML=s[17]
	document.getElementById("div0_18").innerHTML=s[18]
	document.getElementById("div0_19").innerHTML=s[19]
	document.getElementById("div0_20").innerHTML=s[20]
	document.getElementById("div0_21").innerHTML=s[21]
	document.getElementById("div0_22").innerHTML=s[22]
	document.getElementById("div0_23").innerHTML=s[23]
	document.getElementById("div0_24").innerHTML=s[24]
	document.getElementById("div0_25").innerHTML=s[25]
	document.getElementById("div0_26").innerHTML=s[26]
	document.getElementById("div0_27").innerHTML=s[27]
	document.getElementById("div0_28").innerHTML=s[28]
	document.getElementById("div0_29").innerHTML=s[29]

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