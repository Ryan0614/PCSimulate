<head>
    <title>php test</title>
</head>
<?php 
// require_once("http://localhost:8080/JavaBridge/java/Java.inc");
// $System = java("java.lang.System");
// echo $System->getProperties();
include_once('protocol.php');
include_once('sql_operate.php');

$DEVICE_type=array(0x01=>"蒸箱",0x02=>"烤箱",0x03=>"微波炉",0x04=>"油烟机",0x05=>"燃气灶",
	0x06=>"消毒柜",0x07=>"热水器",0x08=>"洗碗机",0x09=>"净水器",0x10=>"蒸微一体机");

/************协议格式*************/
$head=0xF4F5;
$len=0x0008;			//从type开始到整个数据包结束所占用的字节数
$type=0x0100;			//高八位代表设备类型 低八位代表产品型号
$cmd=0x06;				//由发送方给出，接收方响应命令时需把命令号返回给发送方
$stat=0x01;				//请求（0x01）和响应（0x02）
$flags=0x0000;			//flags预留，默认为0x0000
$payload=array();	//内容对应具体命令
$crc=0x0100;			//各字节相异或，crc[1]=异或结果，默认crc[0] = 0x00
/************协议格式 END*********/
$cmd_t=0x02;
switch ($cmd_t){
    case 0x01:
        $data=wifiQueryDeviceInfo($cmd_t);
        break;
    case 0x02:
        $data=wifiQueryHeartbeat($cmd_t);
        break;
    case 0x03:
        $data=wifiSendWifiStatus($cmd_t);
        break;
    case 0x05:
        $data=wifiSendConfigTime($cmd_t);
        break;
    case 0x0f:
        $data=wifiSendStartUpdate($cmd_t);
        break;
    case 0x10:
        $data=wifiSendUpdateData($cmd_t);
        break;
    case 0x11:
        $data=wifiSendEndUpdate($cmd_t);
        break;
    case 0x30:
        $data=wifiQueryStatus($cmd_t);
        break;
    case 0x31:
        $data=wifiControlStatus($cmd_t);
        break;

    default:
        break;
}
echo "*********************** PC模拟器协议测试************************";
echo "<br>";
//$result=processProtocol($data);

$log = new StdClass();
$log->log="123";
print_r( $log);


echo "<br>";

echo "<br>";

echo "<br>";

echo "<br>";






echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
echo "********************** PC模拟器协议测试 END*********************";

function wifiQueryDeviceInfo($cmd_t){
	$data["head"]=0xF4F5;
	$data["type"]=0x0100;
	$data["cmd"]=$cmd_t;
	$data["stat"]=0x01;
	$data["flags"]=0x0000;
	$data["payload"]=array();
	$data["len"]=count($data["payload"])+8;
	$data["crc"]=getCrc($data);
	return $data;
}
function wifiQueryHeartbeat($cmd_t){
	$data["head"]=0xF4F5;
    $data["type"]=0x0100;
    $data["cmd"]=$cmd_t;
    $data["stat"]=0x01;
    $data["flags"]=0x0000;
    $data["payload"]=array();
    $data["len"]=count($data["payload"])+8;
    $data["crc"]=getCrc($data);
    return $data;
}
function wifiSendWifiStatus($cmd_t){
	$data["head"]=0xF4F5;
	$data["type"]=0x0100;
	$data["cmd"]=$cmd_t;
	$data["stat"]=0x01;
	$data["flags"]=0x0000;
	$data["payload"][]=0x01;	//Wifi模式 bit0——station模式， bit1——ap模式，bit2——easylink模式
	$data["payload"][]=0x01;	//Wifi是否成功连接路由器（或IKCC）,0: 未连接, 1: 连接; 2：正在连接
	$data["payload"][]=0x00;	//Wifi是否与IKCC建立socket连接，0: 未连接, 1: 连接;（Internet是否OK）；2：正在连接
	$data["payload"][]=0x32;	//表示WiFi模组当前连接AP的信号强度（RSSI）
	$data["payload"][]=0x06;	//表示WIFI当前信道, 1 ~ 13
	$data["payload"][]=0x01;	//表示WIFI当前模式　2.4G /5G  bgn /ac
	$data["payload"][]=0x00;	//错误状态码，0x01-ssid不存在，0x02-密码错误
	$data["payload"][]=0x00;	//预留
	$data["len"]=count($data["payload"])+8;
	$data["crc"]=getCrc($data);
	return $data;
}
function wifiSendConfigTime($cmd_t){
	$data["head"]=0xF4F5;
	$data["type"]=0x0100;
	$data["cmd"]=$cmd_t;
	$data["stat"]=0x01;
	$data["flags"]=0x0000;
	$data["payload"][]=0x11;
	$data["payload"][]=0x11;
	$data["payload"][]=0x11;
	$data["payload"][]=0x11;
	$data["payload"][]=0x11;
	$data["payload"][]=0x11;
	$data["payload"][]=0x11;

	$data["len"]=count($data["payload"])+8;
	$data["crc"]=getCrc($data);
	return $data;
}
function wifiSendStartUpdate($cmd_t){
	
}
function wifiSendUpdateData($cmd_t){
	
}
function wifiSendEndUpdate($cmd_t){
	
}
function wifiQueryStatus($cmd_t){
	$data["head"]=0xF4F5;
	$data["type"]=0x0100;
	$data["cmd"]=$cmd_t;
	$data["stat"]=0x01;
	$data["flags"]=0x0000;
	$data["payload"]=array();
	$data["len"]=count($data["payload"])+8;
	$data["crc"]=getCrc($data);
	return $data;
}
function wifiControlStatus($cmd_t){
	$data["head"]=0xF4F5;
	$data["type"]=0x0100;
	$data["cmd"]=$cmd_t;
	$data["stat"]=0x01;
	$data["flags"]=0x0000;
	$data["payload"]=array();
	$data["payload"][]=0xff;
	$data["payload"][]=0xff;
	$data["payload"][]=0x01;		//开机关机
	$data["payload"][]=0x00;		//开始暂停
	$data["payload"][]=0x01;		//照明灯
	$data["payload"][]=0x80;		//工作模式
	$data["payload"][]=0xff;		//当前菜谱
	$data["payload"][]=0x02;
	$data["payload"][]=0x01;		//设定工作总时间
	$data["payload"][]=0x15;
	$data["payload"][]=18;			//预约开始时间
	$data["payload"][]=11;
	$data["payload"][]=11;			//设定温度值1
	$data["payload"][]=24;
	$data["payload"][]=33;
	$data["len"]=count($data["payload"])+8;
	$data["crc"]=getCrc($data);
	return $data;
}
?> 