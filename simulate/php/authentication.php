<?php
include_once('protocol.php');
$type=isset($_GET["t"])?$_GET["t"]:"";
$user=isset($_GET["u"])?$_GET["u"]:"";
$pwd=isset($_GET["p"])?$_GET["p"]:"";

$result=new StdClass;
if ($type==1){  //save
    if($CONFIG_TYPE == 0){
        addUser_Json($user, $pwd);
    }else{
        addUser_Sql($user, $pwd, $result);
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

function addUser_Sql($user, $pwd, $result){
    $result->save = addUser_mysql($user, $pwd);
}

function addUser_Json($user, $pwd, $result){
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
}
?>