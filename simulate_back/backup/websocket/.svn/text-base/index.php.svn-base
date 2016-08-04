<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<script>  
	
    var socket;  
      
    /** 
     * 初始化请求服务器端以建立websocket连接，host为本地localhost,请求的端口号为12345 
     * 之后为socket的各事件绑定事件内容 
     */  
    function init(){  
        var host = "ws://localhost:12345/websocket/server.php";  
        try{  
            socket = new WebSocket(host);  
            log('WebSocket - status '+socket.readyState);  
            socket.onopen    = function(msg){ log("Welcome - status "+this.readyState); };  
            socket.onmessage = function(msg){ log("Received: "+ msg.data); };  
            socket.onclose   = function(msg){ log("Disconnected - status "+this.readyState); };  
        } catch(ex) {  
            log(ex);  
        }  
    }  
      
    /** 
     * send方法发送消息到服务器端 
     */  
	function close(s){
		socket.close()
	} 
    function send(){  
        var msg = $("msg").value;  
        if (!msg) return false;  
          
        $("msg").value="";  
        try{  
            socket.send(msg);  
            log('Sent: '+msg);  
        } catch(ex) {  
            log(ex);  
        }
		//sleep(1000)
    }
	function sleep(numberMillis) { 
		var now = new Date(); 
		var exitTime = now.getTime() + numberMillis; 
		while (true) { 
			now = new Date(); 
			if (now.getTime() > exitTime) 
			return; 
		} 
	}
  
	 
    //初始化的其他方法   
    function $(id) {  
        return document.getElementById(id);  
    }  
    function log(msg) {  
        $("log").innerHTML+="<br>"+msg;  
    }  
    function onkey(event){  
        if (event.keyCode == 13){
			close()
			send();  
		}
    }  
    </script>  
</head>

<body onLoad="init()">  
     <h3>WebSocket v2.00</h3>  
     <div id="log"></div>  
	 <input type="button" onclick="close(1)" value="close"></input> 	 
     <input id="msg" type="textbox" onKeyPress="onkey(event)"/>  
     <button onClick="send()">Send</button> 
	 
    </body>  
</html>