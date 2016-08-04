<?php
include_once('protocol.php');
$type=isset($_GET["t"])?$_GET["t"]:"";
$user=isset($_GET["u"])?$_GET["u"]:"";
$pwd=isset($_GET["p"])?$_GET["p"]:"";

$result=new StdClass;
if ($type==1){  //save
    if (file_exists("config/authentication.json")){
        $userConfig=getConfig("authentication.json");
    }else{
        $userConfig=new StdClass;
    }
    if (array_key_exists($user, $userConfig)){
        $result->save=1; //账号已存在
    }else{
        $userConfig->$user=$pwd;  
        $result->save=0;
        setConfig($userConfig, "authentication.json");
    }

}else if($type==2){
    $userConfig=getConfig("authentication.json");
    if (!array_key_exists($user, $userConfig)){
        $result->load=1;      // 无用户
    }else if($userConfig->$user!=$pwd){
        $result->load=2;       //密码错误
    }else if($userConfig->$user==$pwd){
        $result->load=0;
    }
}
echo json_encode($result);
?>