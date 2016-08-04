<?php
/************协议格式 大端字节序*************/
// $HEAD=0xF4F5;
// $LEN=0x0100;            //从type开始到整个数据包结束所占用的字节数 
// $TYPE=0x0100;           //高八位代表设备类型 低八位代表产品型号
// $CMD=0x01;              //由发送方给出，接收方响应命令时需把命令号返回给发送方
// $STAT=0x01;             //请求（0x01）和响应（0x02）
// $FLAGS=0x0000;          //Flags预留，默认为0x0000
// $PAYLOAD="12345678";    //内容对应具体命令
//$CRC=0x0100;            //各字节相异或，CRC[1]=异或结果，默认CRC[0] = 0x00
/************协议格式 END********************/
$DEVICE_TYPE=array(0x01=>"蒸箱",0x02=>"烤箱",0x03=>"微波炉",0x04=>"油烟机",0x05=>"燃气灶",
    0x06=>"消毒柜",0x07=>"热水器",0x08=>"洗碗机",0x09=>"净水器",0x10=>"蒸微一体机");

$DEVICE_TYPE_CONFIG=array(0x01=>"zx_config.json",0x02=>"kx_config.json",0x03=>"wbl_config.json",0x04=>"yyj_config.json",0x05=>"rqz_config.json",0x06=>"xdg_config.json",0x07=>"rsq_config.json",0x08=>"xwj_config.json",0x09=>"jsq_config.json",0x10=>"zwytj_config.json");

function BITMASK($ii){
    return 1<<$ii;
}

	// HOW TO USE PHP TO WRITE TO YOUR SERIAL PORT: TWO METHODS
    // Use this code to write directly to the COM1 serial port
    // First, you want to set the mode of the port. You need to set
    // it only once; it will remain the same until you reboot.
    // Note: the backticks on the following line will execute the
    // DOS 'mode' command from within PHP

function readUART(){
    // `mode com4: BAUD=115200 PARITY=N data=8 stop=1 xon=off`;
 //    $fp = fopen ("COM4:", "w+");
 //    if (!$fp) {
 //        echo "Uh-oh. Port not opened.";
 //    } else {
 //        $e = chr(27);
 //        $string  = $e . "A" . $e . "H300";
 //        $string .= $e . "V100" . $e . "XL1SATO";
 //        $string .= $e . "Q1" . $e . "Z";
 //        echo $string;
 //        fputs ($fp, $string );
    //  echo fgets($fp, 128);
 //        fclose ($fp);
 //    }
}
function sendUART($str){

}
function parserData($str){      //done
    $sLen=strlen($str);
    for($j=0;$j<$sLen;$j++){
        $s[]=ord($str[$j]);
    }
    $data["head"]=$s[0]*256+$s[1];
    $data["len"]=$s[2]*256+$s[3];
    $data["type"]=$s[4]*256+$s[5];
    $data["cmd"]=$s[6];
    $data["stat"]=$s[7];
    $data["flags"]=$s[8]*256+$s[9];
    $data["payload"]=array();
    for ($i=0; $i< $data["len"]-8; $i++){
        $data["payload"][]=$s[10+$i];
    }    
    $data["crc"]=$s[$sLen-2]*256+$s[$sLen-1];
    return $data;
    //return processProtocol($data);
}
function readData(){    //done
    //将uart的string解析成数组
    $send="read";
    $d=udpSend($send);
    if ($d=="null" ||$d==null){ //no data  or  no server
        return;
    }
    $data=parserData($d);
    processProtocol($data);
}
function sendData($str){    //done
    //将数组解析成uart的string
    //json to array
    $data[]=(int)($str["head"]/256);
    $data[]=$str["head"]%256;
    $data[]=(int)($str["len"]/256);
    $data[]=$str["len"]%256;
    $data[]=(int)($str["type"]/256);
    $data[]=$str["type"]%256;
    $data[]=$str["cmd"];
    $data[]=$str["stat"];
    $data[]=(int)($str["flags"]/256);
    $data[]=$str["flags"]%256;
    for($i=0;$i<count($str["payload"]);$i++){
        $data[]=$str["payload"][$i];
    }
    $data[]=(int)($str["crc"]/256);
    $data[]=$str["crc"]%256;

    //array ascii to chr
    for($j=0;$j<count($data);$j++){
        $s[]=chr($data[$j]);
    }
    //chr to string
    $send=implode('', $s);
    $response=udpSend($send);
    processProtocol(parserData($response));
}
function responseData($str){
    //将数组解析成uart的string
    //json to array
    $data[]=(int)($str["head"]/256);
    $data[]=$str["head"]%256;
    $data[]=(int)($str["len"]/256);
    $data[]=$str["len"]%256;
    $data[]=(int)($str["type"]/256);
    $data[]=$str["type"]%256;
    $data[]=$str["cmd"];
    $data[]=$str["stat"];
    $data[]=(int)($str["flags"]/256);
    $data[]=$str["flags"]%256;
    for($i=0;$i<count($str["payload"]);$i++){
        $data[]=$str["payload"][$i];
    }
    $data[]=(int)($str["crc"]/256);
    $data[]=$str["crc"]%256;

    //array ascii to chr
    for($j=0;$j<count($data);$j++){
        $s[]=chr($data[$j]);
    }
    //chr to string
    $send=implode('', $s);
    udpSendNoRead($send);
}
//$ipaddress="192.168.200.122";   //gyy
//$ipaddress="127.0.0.1";
function udpSendNoRead($sendMsg = '', $ip = "127.0.0.1", $port = '8888'){  
    $handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr);  
    if( !$handle ){  
        echo "create fail";
        return "create fail";  
    }  
    fwrite($handle, $sendMsg);  
    fclose($handle); 
}  
function udpSend($sendMsg = '', $ip = "127.0.0.1", $port = '8888'){  
    $handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr);  
    if( !$handle ){  
        echo "create fail";
        return "create fail";  
    }  
    fwrite($handle, $sendMsg);  
    $result = fread($handle, 1024);  
    fclose($handle);  
    return $result;  
}  

function setConfig($data, $file){
    if ($file !="authentication.json")
    {
        $dir=explode('/', $file);
        if(!is_dir("config/".$dir[0]))
            mkdir("config/".$dir[0]);
    }
    file_put_contents("config/".$file,json_encode($data));
}
function getConfig($file){
    $d=file_get_contents("config/".$file);
    //$d=json_decode($d, true);     //解析数组
    $d=json_decode($d);
    return $d;
}
function processProtocol($data){
    global $Sfunc;
    global $Rfunc;
    global $DEVICE_TYPE;

    
    if ($data["head"]!=0xF4F5){
        echo "head error!->".$data["head"];
        return ;
    }
    if ($data["len"]!=(count($data["payload"])+8)){
        echo "len error!->".$data["len"];
        return M_R_WrongMsg($data["type"], 0x04);
    }
    if (!array_key_exists((int)($data["type"]/256), $DEVICE_TYPE)){
        echo "type error!->".$data["type"];
        return M_R_WrongMsg($data["type"], 0x01);
    }
    if (!array_key_exists($data["cmd"], $Sfunc)){
        echo "cmd error!->".$data["cmd"];
        return M_R_WrongMsg($data["type"], 0x03);
    }
    if ($data["flags"]!=0x0000){
        echo "flags error!->".$data["flags"];
        return;
    }
    if ((int)($data["crc"]%256)!=getCrc($data)){
        echo "crc error!->".$data["crc"];
        return;
    }
    if ($data["stat"]==0x01 ){
        return call($Sfunc[$data["cmd"]], $data);
    }else if($data["stat"]==0x02){
        return call($Rfunc[$data["cmd"]], $data);
    }else{
        echo "stat error!->".$data["stat"];
        return M_R_WrongMsg($data["type"], 0x03);
    }

}

function CRC_XModem($data, $len)
{
    $crc=0x00;          // initial value
    $polynomial=0x1021;
    for($index=0;$index<$len;$index++){
        $b = $data[$index];
        for ($i = 0; $i < 8; $i++){
            $bit = (($b   >> (7-$i) & 1) == 1);
            $c15 = (($crc >> 15    & 1) == 1);
            $crc <<= 1;
            if ($c15 ^ $bit){
                $crc ^= $polynomial;
            }
        }
    }
    $crc&=0xffff;
    return $crc;
}

function getCrc($st){
    $crc=($st["head"])^$st["type"]^$st["len"]^$st["cmd"]^$st["stat"]^$st["flags"]^getPaylodCrc($st["payload"]);
    return (($crc/256)^($crc%256));

    // $len=$st["len"]+2;  //不包括crc 2字节

    // $data[]=(int)($st["head"]/256);
    // $data[]=$st["head"]%256;
    // $data[]=(int)($st["len"]/256);
    // $data[]=$st["len"]%256;
    // $data[]=(int)($st["type"]/256);
    // $data[]=$st["type"]%256;
    // $data[]=$st["cmd"];
    // $data[]=$st["stat"];
    // $data[]=(int)($st["flags"]/256);
    // $data[]=$st["flags"]%256;
    // for($i=0;$i<count($st["payload"]);$i++){
    //     $data[]=$st["payload"][$i];
    // }
    // return CRC_XModem($data, $len);
}
function getPaylodCrc($pay){
    $ret=0;
    for ($i=0; $i < count($pay); $i++) { 
        $ret=$ret^$pay[$i];
    }
    return $ret;
}

function call($func, $data){
    return $func($data);
}

function W_S_DeviceInfo($data){     //done
    $response["head"]=0xF4F5;
    $response["type"]=$data["type"];
    $response["cmd"]=$data["cmd"];
    $response["stat"]=0x02;
    $response["flags"]=0x0000;
    // 16 16 16 32 32
    $info="FTSD-02       
HW-FT-V1.0    
SF-FT-V2.3    
ID                            
KEY                             ";
    for ($i=0; $i<112;$i++){
        $response["payload"][]=$info[$i];
    }
    $response["len"]=count($response["payload"])+8;
    $response["crc"]=getCrc($response);
    
    responseData($response);
    //return to html
    $config = new StdClass();
    $config->update="null";
    $config->log="wifi get device info!";
    echo json_encode($config);
}

function M_R_DeviceInfo(){
    // no do
}

function W_S_Heartbeat($data){      //done
    $response["head"]=0xF4F5;
    $response["type"]=$data["type"];
    $response["cmd"]=$data["cmd"];
    $response["stat"]=0x02;
    $response["flags"]=0x0000;
    $response["payload"]=array();
    $response["len"]=count($response["payload"])+8;
    $response["crc"]=getCrc($response);
    
    responseData($response);

    //return to html
    $config=new StdClass;
    $config->update="null";
    $config->log="wifi send heartbeat!";
    echo json_encode($config);
}

function M_R_Heartbeat(){
    // no do
}

function W_S_WifiStatus($data){     //done
    //UI作相应的处理 todo
    global $DEVICE_TYPE_CONFIG;
    $result=getConfig(explode("=", $_COOKIE["pcsimulate"])[2].'/'.$DEVICE_TYPE_CONFIG[(int)($data["type"]/256)]);
    $result->wifi=$data["payload"];
    setConfig($result, explode("=", $_COOKIE["pcsimulate"])[2].'/'.$DEVICE_TYPE_CONFIG[(int)($data["type"]/256)]);
    $response["head"]=0xF4F5;
    $response["type"]=$data["type"];
    $response["cmd"]=$data["cmd"];
    $response["stat"]=0x02;
    $response["flags"]=0x0000;
    $response["payload"]=array();
    $response["len"]=count($response["payload"])+8;
    $response["crc"]=getCrc($response);
    
    responseData($response);

    //return to html
    $config=$result;
    $config->update="wifi";
    $config->log="wifi send wifi status!";
    echo json_encode($config);
}

function M_R_WifiStatus(){
    // no do
}

function M_S_ConfigWifi($cmd, $type, $payload){
    $data["head"]=0xF4F5;
    $data["type"]=$type;
    $data["cmd"]=$cmd;
    $data["stat"]=0x01;
    $data["flags"]=0x0000;
    $data["payload"]=$payload;
    $data["len"]=count($data["payload"])+8;
    $data["crc"]=getCrc($data);
    
    sendData($data);

    // $response["head"]=0xF4F5;
    // $response["type"]=$type;
    // $response["cmd"]=$cmd;
    // $response["stat"]=0x02;
    // $response["flags"]=0x0000;
    // $response["payload"][]=0x00;
    // $response["len"]=count($response["payload"])+8;
    // $response["crc"]=getCrc($response);

    // processProtocol($response);
}

function W_R_ConfigWifi($data){     //done
    $data["update"]="null";

    echo json_encode($log);
    if ($data["payload"][0]==0x00){
        $data["log"]="mcu config Wifi success !";
    }else{
        $data["log"]="mcu config Wifi fail !";
    }
    echo json_encode($data);
}

function W_S_ConfigTime($data){     //todo set time
    $response["head"]=0xF4F5;
    $response["type"]=$data["type"];
    $response["cmd"]=$data["cmd"];
    $response["stat"]=0x02;
    $response["flags"]=0x0000;
    $response["payload"][]=0x00;
    $response["len"]=count($response["payload"])+8;
    $response["crc"]=getCrc($response);
    
    responseData($response);
    //return to html
    $config=new StdClass;
    $config->update="time";
    $config->time=$data["payload"];
    $config->log="wifi send config time!";
    echo json_encode($config);
}

function M_R_ConfigTime(){
    // no do
}

function M_S_ConfigTime($cmd, $type){
    $data["head"]=0xF4F5;
    $data["type"]=$type;
    $data["cmd"]=$cmd;
    $data["stat"]=0x01;
    $data["flags"]=0x0000;
    $data["payload"]=array();
    $data["len"]=count($data["payload"])+8;
    $data["crc"]=getCrc($data);
    
    sendData($data);

    // $response["head"]=0xF4F5;
    // $response["type"]=$type;
    // $response["cmd"]=$cmd;
    // $response["stat"]=0x02;
    // $response["flags"]=0x0000;
    // //$response["time"]=date("Y-m-d H:i:s",time());
    
    // //0x20 0x15 0x06 0x29 0x10 0x55 0x30
    // $response["payload"][]=0x20;
    // $response["payload"][]=0x02;
    // $response["payload"][]=0x11;
    // $response["payload"][]=0x11;
    // $response["payload"][]=0x11;
    // $response["payload"][]=0x11;
    // $response["payload"][]=0x11;
    // $response["len"]=count($response["payload"])+8;
    // $response["crc"]=getCrc($response);

    // processProtocol($response);
    
}

function W_R_ConfigTime($data){     //done
    $data["update"]="time";
    $data["log"]="check time success!";
    $data["time"]=$data["payload"];
    // $data["time"][]=0x20;
    // $data["time"][]=0x20;
    // $data["time"][]=0x20;
    // $data["time"][]=0x20;
    // $data["time"][]=0x20;
    // $data["time"][]=0x20;
    // $data["time"][]=0x20;
	echo json_encode($data);
}

function M_S_WifiMac($cmd, $type){  //done
    $data["head"]=0xF4F5;
    $data["type"]=$type;
    $data["cmd"]=$cmd;
    $data["stat"]=0x01;
    $data["flags"]=0x0000;
    $data["payload"]=array();
    $data["len"]=count($data["payload"])+8;
    $data["crc"]=getCrc($data);
    
    sendData($data);

    // $response["head"]=0xF4F5;
    // $response["type"]=$type;
    // $response["cmd"]=$cmd;
    // $response["stat"]=0x02;
    // $response["flags"]=0x0000;
    // $response["payload"]=array(0x3c,0x46,0x06,0xab,0xac,0x4f);

    // $response["len"]=count($response["payload"])+8;
    // $response["crc"]=getCrc($response);

    // processProtocol($response);
}
function int2hex($in){
    $he=array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f');
    return $he[$in];
}
function hex2mac($data){
    $str="";
    for ($i=0;$i<count($data);$i++){
        $str=$str.int2hex((int)($data[$i]/16));
        $str=$str.int2hex($data[$i]%16);
 
        if ($i<count($data)-1){
            $str=$str.":";
        }
    }
    return $str;
}
function W_R_WifiMac($data){        //done
    $mac=hex2mac($data["payload"]);
    $data["update"]="null";
    $data["log"]="Wifi Mac: ".$mac;
    echo json_encode($data);
}   

function M_R_WrongMsg($type, $err_code){    //done
    // 错误码（err_code)可为以下的值：
    // 1：设备类型错误
    // 2：设备型号错误
    // 3：命令不可识别
    // 其它保留
    $response["head"]=0xF4F5;
    $response["type"]=$type;
    $response["cmd"]=0x0e;
    $response["stat"]=0x02;
    $response["flags"]=0x0000;
    $response["payload"]=array($err_code);

    $response["len"]=count($response["payload"])+8;
    $response["crc"]=getCrc($response);
    sendData($response);
}

function W_R_WrongMsg($data){
    $err=array("校验错误", "设备类型错误", "设备型号错误", "命令不可识别");
    $data["log"]=$err[$data["payload"][0]];
    echo json_encode($data);
}

function W_S_StartUpdate($data){
    // $response["head"]=0xF4F5;
    // $response["type"]=$data["type"];
    // $response["cmd"]=$data["cmd"];
    // $response["stat"]=0x02;
    // $response["flags"]=0x0000;
    // $response["payload"]=array();
    // $response["len"]=count($response["payload"])+8;
    // $response["crc"]=getCrc($response);
    
    // sendData($response);
}

function M_R_StartUpdate(){
    // no do
}

function W_S_UpdateData(){

}

function M_R_UpdateData(){
    // no do
}

function W_S_EndUpdate(){

}

function M_R_EndUpdate(){
    // no do
}

//6种设备各不同
function W_S_ReadStatus($data){
    global $DEVICE_TYPE_CONFIG;
    $result=getConfig(explode("=", $_COOKIE["pcsimulate"])[2].'/'.$DEVICE_TYPE_CONFIG[(int)($data["type"]/256)]);

    $response["head"]=0xF4F5;
    $response["type"]=$data["type"];
    $response["cmd"]=$data["cmd"];
    $response["stat"]=0x02;
    $response["flags"]=0x0000;
    $response["payload"]=$result->config;
    $response["len"]=count($response["payload"])+8;
    $response["crc"]=getCrc($response);
    
    responseData($response);

    //return to html
    $config=$result;
    $config->update="null";
    $config->log="wifi read status!";
    echo json_encode($config);
}


function M_R_ReadStatus($result, $data){
    //no do
}

function W_S_ControlStatus($data){
    switch ((int)($data["type"]/256))
    {
    case 1:
        return ControlStatus_ZX($data);
        break;
    case 2:
        break;
    case 3:
        break;
    case 4:
        break;
    case 5:
        break;
    case 6:
        break;
    case 7:
        break;
    case 8:
        break;
    case 9:
        break;
    case 10:
        break;
    default:
        break;
    }
}
function ControlStatus_ZX($data){
    global $DEVICE_TYPE_CONFIG;
    $result=getConfig(explode("=", $_COOKIE["pcsimulate"])[2].'/'.$DEVICE_TYPE_CONFIG[(int)($data["type"]/256)]);
    $attr=$data["payload"][0]*256+$data["payload"][1];
    if ($attr & 0x01){
        $result->config[0]=$data["payload"][2];
    }
    if ($attr & 2){
        $result->config[1]=$data["payload"][3];
    }
    if ($attr & 4){
        $result->config[2]=$data["payload"][4];
    }
    if ($attr & 8){
        $result->config[3]=$data["payload"][5];
    }
    if ($attr & 16){
        $result->config[4]=$data["payload"][6];
        $result->config[5]=$data["payload"][7];
    }
    if ($attr & 32){
        $result->config[6]=$data["payload"][8];
        $result->config[7]=$data["payload"][9];
    }
    if ($attr & 64){
        $result->config[8]=$data["payload"][10];
        $result->config[9]=$data["payload"][11];
    }
    if ($attr & 128){
        $result->config[10]=$data["payload"][12];
    }
    if ($attr & 256){
        $result->config[11]=$data["payload"][13];
    }
    if ($attr & 512){
        $result->config[12]=$data["payload"][14];
    }
    //$result->isupdate=true;
    setConfig($result, explode("=", $_COOKIE["pcsimulate"])[2].'/'.$DEVICE_TYPE_CONFIG[(int)($data["type"]/256)]);
    $response["head"]=0xF4F5;
    $response["type"]=$data["type"];
    $response["cmd"]=$data["cmd"];
    $response["stat"]=0x02;
    $response["flags"]=0x0000;
    $response["payload"]=array();
    $response["len"]=count($response["payload"])+8;
    $response["crc"]=getCrc($response);
    
    responseData($response);

    //return to html
    $config = new StdClass();
    $config->update="status";
    $config->log="wifi update device status!";
    echo json_encode($config);
}

function M_R_ControlStatus(){
    //no do
}

function M_S_ReportStatus($cmd, $type){
    global $DEVICE_TYPE_CONFIG;

    $result=getConfig(explode("=", $_COOKIE["pcsimulate"])[2].'/'.$DEVICE_TYPE_CONFIG[(int)($type/256)]);
    $data["head"]=0xF4F5;
    $data["type"]=$type;
    $data["cmd"]=$cmd;
    $data["stat"]=0x01;
    $data["flags"]=0x0000;
    $data["payload"]=$result->config;
    $data["len"]=count($data["payload"])+8;
    $data["crc"]=getCrc($data);

    sendData($data);

    // $response["head"]=0xF4F5;
    // $response["type"]=$type;
    // $response["cmd"]=$cmd;
    // $response["stat"]=0x02;
    // $response["flags"]=0x0000;
    // $response["payload"]=array();
    // $response["len"]=count($response["payload"])+8;
    // $response["crc"]=getCrc($response);

    // processProtocol($response);
}

function W_R_ReportStatus($data){
    $log["update"]="null";
    $log["log"]="mcu report status success!";
    echo json_encode($log);
}

function getDefaultConfig($type){
    switch ($type){
    case 0x01:
        return getZXConfig();
        break;
    default:
        break;
    }
}
function getZXConfig(){
    $res[0]=0;          //0关机 1待机 2开机           0
    $res[1]=0;          //0~255 当前工作状态      1
    $res[2]=0;         //0关 1开 照明灯          2
    $res[3]=0x80;       //Bit:7：0/1  手动/自动Bit:0-Bit6：0~128 （当前工作在手动模式几）3
                        //手动定义：1-普通蒸，2-过温蒸，3-除垢，4-预约，5-解冻，
    $res[4]=0xff;         //0xffxx 代表本地固定菜谱ID 否则网络菜谱ID  4
    $res[5]=2;         
    $res[6]=0;         //0~5  小时 设定工作总时间    5
    $res[7]=0;         //0~60 分钟
    $res[8]=17;         //预约开始时间 17:30      6
    $res[9]=30;          
    $res[10]=30;        //温度1 0~255 暂定温度系数2 7   
    $res[11]=50;        //温度2 0~255 暂定温度系数2 8
    $res[12]=80;        //温度3 0~255 暂定温度系数2 9
    $res[13]=0;       //功率(0~255)*10  255全开 10
    $res[14]=0;        //功率(0~255)*10  255全开    11
    $res[15]=0;       //上温区 (0~255)*2           12
    $res[16]=0;       //中温区 0~255               13
    $res[17]=0;        //下温区 0~255              14
    $res[18]=0;        // 1百分比 0~100             15
    $res[19]=0;        // 2百分比 0~100             16
    $res[20]=0;       //压力,0~255 意义待定       17
    $res[21]=0;         //0关 1开 加热膜         18
    $res[22]=0;         //0关 1开 贯流热风机       19
                        
    $res[23]=0;         //0~5  小时 工作剩余时间    20
    $res[24]=0;         //0~60 分钟
    $res[25]=0;         //0关 1开 门电子锁            21
    $res[26]=0;         //0关 1开 门控开关状态      22
    $res[27]=0;         //0~255 食物位置            23
    $res[28]=0;         //故障编码                  24
    $res[29]=0;         //OTA是否支持 0不支持 1支持 25 

    $data["config"]=$res;
    $data["wifi"]=array();
    return $data;
}

$Sfunc[0x01]="W_S_DeviceInfo";
$Sfunc[0x02]="W_S_Heartbeat";
$Sfunc[0x03]="W_S_WifiStatus";
$Sfunc[0x04]="M_S_ConfigWifi";
$Sfunc[0x05]="W_S_ConfigTime";
$Sfunc[0x06]="M_S_ConfigTime";
$Sfunc[0x07]="M_S_WifiMac";
$Sfunc[0x0E]="M_S_WrongMsg";
$Sfunc[0x0F]="W_S_StartUpdate";
$Sfunc[0x10]="W_S_UpdateData";
$Sfunc[0x11]="W_S_EndUpdate";
$Sfunc[0x30]="W_S_ReadStatus";
$Sfunc[0x31]="W_S_ControlStatus";
$Sfunc[0x32]="M_S_ReportStatus";

$Rfunc[0x01]="M_R_DeviceInfo";
$Rfunc[0x02]="M_R_Heartbeat";
$Rfunc[0x03]="M_R_WifiStatus";
$Rfunc[0x04]="W_R_ConfigWifi";
$Rfunc[0x05]="M_R_ConfigTime";
$Rfunc[0x06]="W_R_ConfigTime";
$Rfunc[0x07]="W_R_WifiMac";
$Rfunc[0x0E]="W_R_WrongMsg";
$Rfunc[0x0F]="M_R_StartUpdate";
$Rfunc[0x10]="M_R_UpdateData";
$Rfunc[0x11]="M_R_EndUpdate";
$Rfunc[0x30]="M_R_ReadStatus";
$Rfunc[0x31]="M_R_ControlStatus";
$Rfunc[0x32]="W_R_ReportStatus";

?>