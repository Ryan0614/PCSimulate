<?php 
	include_once 'WebSocket.php';
	error_reporting(E_ALL); // 主要屏蔽socket_select的警告提示
	$webSocket = new WebSocket('localhost', 12345);
	$socketRes = $webSocket->resource;
	$sockets = array();
	$sockets[] = $socketRes;
	
	while (true) {
		$changed = $sockets;
		socket_select($changed, $write = NULL, $except = NULL, NULL); // 阻塞住等待信息
		if($webSocket->socket == null) {
			// 第一次开启
			$client = socket_accept($socketRes); // 接收客户端的socket
			if($client !== false) {
				echo "in \n";
				$webSocket->socket = $client;
				$sockets[] = $client;
				$data = @socket_recv($client, $buffer, 2048, 0); // 读取客户端发送的内容
				if ($data == 0) {
					// 关闭socket
					die("connect error!");
				}
				echo $buffer . "\n";
				$webSocket->handShake($client, $buffer); // 握手
				echo "shake \n";
			}
		}else{
			// 处理代码
			$buffer = '';
			$data = @socket_recv($webSocket->socket, $buffer, 2048, 0); 
			if ($data != 0) {
				echo $webSocket->unwrap($buffer) . "\n";
				$webSocket->send($webSocket->socket, $webSocket->unwrap($buffer));
			}
		}
		sleep(1);
	}
	
?>