<?php
include_once('./libs/Smarty.class.php');  //如果在php.ini文件中将include_path添加了smart的目录这里就直接写Smarty.class.php就可以了。
include_once('protocol.php');

$smarty = new Smarty();
$smarty -> template_dir = "../public";     //模板存放目录
$smarty -> compile_dir = "./Templates_c";     //编译目录
// $smarty -> left_delimiter = "{{";             //左定界符
// $smarty -> right_delimiter = "}}";             //右定界符

//get cookie
$hello=explode("=", $_COOKIE["pcsimulate"]);
//default
$hello=explode("=", $_COOKIE["pcsimulate"]);
if (file_exists("config/".$hello[2]."/device.json")){
	$config=getConfig($hello[2]."/device.json");
}else{
	$config=new StdClass;
}

if(!file_exists("config/".$hello[2]."/zx_config.json")){
	$config=getDefaultConfig(0x01);
	setConfig($config, $hello[2]."/zx_config.json");
}
$cfg=getConfig($hello[2]."/zx_config.json");
$config=$cfg->config;
$wifi=$cfg->wifi;


//select 
$run_status=array("开机", "关机", "待机");
$work_mode=array("自动模式", "手动模式1", "手动模式2","手动模式3", "手动模式4");
$cook_book=array("本地菜谱1", "本地菜谱2", "网络菜谱1", "网络菜谱2");



if (isset($_POST["run_status"])){
	$run_statu_value=$_POST["run_status"];
	$cook_book_value=$_POST["cook_book"];
	$work_mode_value=$_POST["work_mode"];
	$total_time=$_POST["text0"];
	$start_time=$_POST["text1"];
	$temper1=$_POST["text2"];
	$temper2=$_POST["text3"];
	$temper3=$_POST["text4"];
	//check
	$lamp=array("关闭", "关闭", "关闭", "关闭");
	$ll=$_POST["check"];
	for($i=0; $i<count($ll)-1; $i++)
	{
		$lamp[$ll[$i]-1] = "打开";
	}
	//*******************修改配置数据*********************
	$config[0]=(int)$run_statu_value;
	$config[2]=$lamp[0]=="打开"?1:0;
	if($work_mode[$work_mode_value] == "自动模式"){
		$config[3]=128;
	}else{
		$config[3]=(int)$work_mode_value;
	}
	if(stripos($cook_book[$cook_book_value], "本地菜谱")==0){
		$config[4]=0xff;
		$config[5]=(int)substr($cook_book[$cook_book_value], strlen("本地菜谱"));
	}else{
		$config[4]=0x00;
		$config[5]=(int)substr($cook_book[$cook_book_value], strlen("网络菜谱"));
	}
	$config[6]=(int)($total_time/60);
	$config[7]=$total_time%60;
	$sttime=explode(':', $start_time);
	$config[8]=(int)$sttime[0];
	$config[9]=(int)$sttime[1];
	$config[10]=(int)$temper1;
	$config[11]=(int)$temper2;
	$config[12]=(int)$temper3;
	$config[21]=$lamp[1]=="打开"?1:0;
	$config[22]=$lamp[2]=="打开"?1:0;
	$config[25]=$lamp[3]=="打开"?1:0;
	$cfg->config=$config;
	setConfig($cfg, $hello[2]."/zx_config.json");
	$smarty -> assign('log',"set option success!");

}else{
	$smarty -> assign('log','load data success!');
}

//*******************载入配置数据*********************
$smarty -> assign('data0',$run_status[$config[0]]);
$smarty -> assign('data1',$config[1]==0?"开始":"暂停");
	$smarty -> assign('data2',$config[2]==0?"关闭":"打开");
if($config[3]/128 == 1)
{
	$work="自动模式";
}else{
	$work="手动模式".($config[3]%128);
}
$smarty -> assign('data3_1',$work);
$smarty -> assign('data3_2', $work_mode);
if ($config[4]=0xff){
	$cook="本地菜谱";
}else{
	$cook="网络菜谱";
}
//设备状态
$smarty -> assign('data4', $cook.$config[5]);
$smarty -> assign('data5', $config[6]*60+$config[7]);
$smarty -> assign('data6',$config[8].":".($config[9]<10?("0".$config[9]):$config[9]));
$smarty -> assign('data7',$config[10]);
$smarty -> assign('data8',$config[11]);
$smarty -> assign('data9',$config[12]);
$smarty -> assign('data10',$config[13]);
$smarty -> assign('data11',$config[14]);
$smarty -> assign('data12',$config[15]);
$smarty -> assign('data13',$config[16]);
$smarty -> assign('data14',$config[17]);
$smarty -> assign('data15',$config[18]);
$smarty -> assign('data16',$config[19]);
$smarty -> assign('data17',$config[20]);
$smarty -> assign('data18', $config[21]==0?"关闭":"打开");
$smarty -> assign('data19', $config[22]==0?"关闭":"打开");
$smarty -> assign('data20',$config[23]*60+$config[24]);
$smarty -> assign('data21', $config[25]==0?"关闭":"打开");
$smarty -> assign('data22',$config[26]);
$smarty -> assign('data23',$config[27]);
$smarty -> assign('data24',$config[28]);
$smarty -> assign('data25',$config[29]);

//wifi
if (count($wifi)){
	$smarty -> assign('wifi1', "0");
	$smarty -> assign('wifi2', "0");
	$smarty -> assign('wifi3', "0");
	$smarty -> assign('wifi4', "0");
	$smarty -> assign('wifi5', "0");
	$smarty -> assign('wifi6', "0");
}else{
	$smarty -> assign('wifi1', "0");
	$smarty -> assign('wifi2', "0");
	$smarty -> assign('wifi3', "0");
	$smarty -> assign('wifi4', "0");
	$smarty -> assign('wifi5', "0");
	$smarty -> assign('wifi6', "0");
}
//设备列表
if (file_exists("config/".$hello[2]."/device.json")){
	$config=getConfig($hello[2]."/device.json");
}else{
	$config=new StdClass;
}
$smarty -> assign("devices", $config);

$smarty -> display('ZX001.html');

?>