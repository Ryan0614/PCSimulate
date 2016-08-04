<?php
include_once('protocol.php');
$cmd=isset($_GET["c"])?$_GET["c"]:"";
$user=isset($_GET["u"])?$_GET["u"]:"";
$type=isset($_GET["t"])?$_GET["t"]:"";
$mode=isset($_GET["m"])?$_GET["m"]:"";   //配置wifi选项


$deviceType=array("全部","蒸箱","烤箱","微波炉","油烟机","燃气灶","消毒柜");
$deviceTypeIndex=array("全部","ZX001.php","KX001.php","WBL001.php","YYJ001.php","RQZ001.php","XDG001.php");
$model=0;

if ($cmd == 0x06)	//mcu校时
{
	M_S_ConfigTime($cmd, $type*256+$model);
}else if ($cmd == 0x32){	//mcu 上报状态
    M_S_ReportStatus($cmd, $type*256+$model);
}
else if ($cmd == 0x04){  //mcu 配置wifi
    M_S_ConfigWifi($cmd, $type*256+$model, getPayload($mode));
}
else if ($cmd == 0x07){     //mcu 查询wifi mac
    M_S_WifiMac($cmd, $type*256+$model);
}else if ($cmd == 0xf1){  
    if (file_exists("config/".$user."/device.json")){
        $userDevice=getConfig($user."/device.json");

        for ($i=0; $i<count($userDevice); $i++){
            if ($userDevice[$i][3] == $type){
                $log["response"]=$deviceType[$type]." 已存在!";
                echo json_encode($log);
                return;
            }
        }
    }  
    $userDevice[]=array($deviceType[$type], $deviceTypeIndex[$type], $deviceType[$type]."001", $type);
    setConfig($userDevice, $user."/device.json");
    $log["response"]=$deviceType[$type]." 添加成功!";
    echo json_encode($log);
    return;
}else if ($cmd == 0xf2){
    if (file_exists("config/".$user."/device.json")){
        $userDevice=getConfig($user."/device.json");

        for ($i=0; $i<count($userDevice); $i++){
            if ($userDevice[$i][3] == $type){
                if ($i==count($userDevice)-1){
                    unset($userDevice[$i]);
                }else{
                    $k;
                    for ($j=$i; $j<count($userDevice)-1; $j++){
                        $userDevice[$j]=$userDevice[$j+1];      //元素循环前移
                        $k=$j+1;
                    }
                    unset($userDevice[$k]);                     //删除末尾元素
                }
                setConfig($userDevice, $user."/device.json");
                $log["response"]=$deviceType[$type]." 删除成功!";
                echo json_encode($log);
                return;
            }
        }
    }  
    $log["response"]=$deviceType[$type]." 未配置!";
    echo json_encode($log);
    return;
}else if($cmd == 0xf3) {
    //更新UI
    //处理WIFI发送过来的消息
    readData();
}


function getPayload($mode,$ssid="ssid",$pwd="123456"){
    $data[0]=$mode;   //1 恢复出厂 2重启wifi 4wifi休眠 8wifi进入厂测 10wifi进入easylink 17ap模式及参数 18station模式及参数
    if ($mode==0x11 || $mode==0x12){
        $data[]=strlen($ssid);
        for ($i=0;$i<strlen($ssid);$i++) {
            $data[]=$ssid[$i];
        }
        $data[]=strlen($pwd);
        for ($i=0;$i<strlen($pwd);$i++) {
            $data[]=$pwd[$i];
        }
    }else{
        $data[]=0x00;
    }
    return $data;
}

?>