<?php
/**
 * @author caiaolin
 * create in 2013-1-21
 */

 class WebSocket {
 	
  	public $shakeHand = false; 
  	public $socket = null;
  	public $resource = null;
 	
 	/**
 	 * 构造函数
 	 * @param string $address
 	 * @param int $port
 	 * @return resource
 	 */
 	public function __construct($address, $port) {
 		$master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() failed");
 		socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1) or die("socket_option() failed");
 		socket_bind($master, $address, $port) or die("socket_bind() failed");
 		socket_listen($master, 20) or die("socket_listen() failed");
 		echo "Server Started : ".date('Y-m-d H:i:s')."\n";
 		echo "Master socket  : ".$master."\n";
 		echo "Listening on   : ".$address." port ".$port."\n\n";
 		$this->resource = $master;
 	}
 	
 	/**
 	 * 获取http头部信息
 	 * @param string $request
 	 * @return array
 	 */
 	private function getHeader($request) {
 		$url = $host = $origin = null;
 		// 获取url信息
 		if(preg_match("/GET (.*) HTTP\/1\.1\r\n/", $request, $match)){ 
 			$url = $match[1]; 
 		}
 		// 获取host地址
 		if(preg_match("/Host: (.*)\r\n/", $request, $match)){ 
 			$host = $match[1]; 
 		}
 		// websocket的源
 		if(preg_match("/Sec-WebSocket-Origin: (.*)\r\n/", $request, $match)){ 
 			$origin = $match[1]; 
 		}
 		// 获取密钥
 		if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $request, $match)){ 
 			$key = $match[1]; 
 		}
 		return array($url, $host, $origin, $key);
 	}
 	
 	/**
 	 * 发送握手协议
 	 * @param resource $socket
 	 * @param string $buffer
 	 * @return bool
 	 */
 	public function handShake($socket, $buffer) {
 		if(empty($socket) || empty($buffer)) {
 			return false;
 		}
 		list($url , $host, $origin, $key) = $this->getHeader($buffer);
 		$key .= '258EAFA5-E914-47DA-95CA-C5AB0DC85B11'; // 密钥(可不加)
 		$hashData = base64_encode(sha1($key, true)); // base64加密
 		// websocket协议
 		$upgrade  = "HTTP/1.1 101 Switching Protocols\r\n" .
 					"Upgrade: websocket\r\n" .
 					"Connection: Upgrade\r\n" .
 					"Sec-WebSocket-Accept: " . $hashData . "\r\n" .
//  					"Sec-WebSocket-Protocol: websocket\r\n" .
 					"\r\n";
 		socket_write($socket, $upgrade, strlen($upgrade)); // 输出内容到客户端
 		return true;
 	}
 	
 	/**
 	 * 转换二进制流为字符串
 	 * @param string $buffer
 	 * @return string
 	 */
 	public function unwrap($buffer) {
 		$mask = array();
 		$data = "";
 		$msg = unpack("H*", $buffer);
 		
 		$head = substr($msg[1],0,2);
 		
 		if (hexdec($head{1}) === 8) {
 			$data = false;
 		} else if (hexdec($head{1}) === 1) {
 			$mask[] = hexdec(substr($msg[1],4,2));
 			$mask[] = hexdec(substr($msg[1],6,2));
 			$mask[] = hexdec(substr($msg[1],8,2));
 			$mask[] = hexdec(substr($msg[1],10,2));
 		
 			$s = 12;
 			$e = strlen($msg[1])-2;
 			$n = 0;
 			for ($i= $s; $i<= $e; $i+= 2) {
 				$data .= chr($mask[$n%4]^hexdec(substr($msg[1],$i,2)));
 				$n++;
 			}
 		}
 		
 		return $data;
 	}
 	
 	/**
 	 * 转换字符串为二进制流
 	 * @param string $string
 	 * @return string
 	 */
 	public function wrap($string) {
 		$frame = array();
 		$frame[0] = "81";
 		$len = strlen($string);
 		$frame[1] = $len < 16 ? "0".dechex($len) : dechex($len);
 		$frame[2] = $this->ord_hex($string);
 		$data = implode("",$frame);
 		return pack("H*", $data);
 	}
 	
 	/**
 	 * 发送内容给客户端
 	 * @param resource $client
 	 * @param string $message
 	 * @return bool
 	 */
 	public function send($client, $message = '') {
 		if(empty($client) || empty($message)) {
 			return false;
 		}
 		echo "send \n";
 		$message = $this->wrap($message);
 		socket_write($client, $message, strlen($message));
 		return false;
 	}
 	
 	/**
 	 * 关闭socket
 	 * @param resource $socket
 	 * @return bool
 	 */
 	public function closeWebSocket($socket) {
 		$this->resource = null;
 		$this->shakeHand = false;
 		$this->socket = null;
 		socket_close($socket);
 	}
 	
 	/**
 	 * 进制转换
 	 * @param string $data
 	 * @return string
 	 */
 	private function ord_hex($data) {
 		$msg = "";
 		$l = strlen($data);
 		for ($i= 0; $i< $l; $i++) {
 			$msg .= dechex(ord($data{$i}));
 		}
 	
 		return $msg;
 	}
 	
 }
?>