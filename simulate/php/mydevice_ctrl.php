<?php
include_once('./libs/Smarty.class.php');  //如果在php.ini文件中将include_path添加了smart的目录这里就直接写Smarty.class.php就可以了。
include_once('protocol.php');

$smarty = new Smarty();
$smarty -> template_dir = "../public";     //模板存放目录
$smarty -> compile_dir = "./Templates_c";     //编译目录
// $smarty -> left_delimiter = "{{";             //左定界符
// $smarty -> right_delimiter = "}}";             //右定界符

$hello=explode("=", $_COOKIE["pcsimulate"]);
if (file_exists("config/".$hello[2]."/device.json")){
	$config=getConfig($hello[2]."/device.json");
}else{
	$config=new StdClass;
}
$smarty -> assign("devices", $config);
$smarty -> display('mydevice_ctrl.html');
?>