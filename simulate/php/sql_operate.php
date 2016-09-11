<?php 

$DEVICE_TYPE_CONFIG=array(0x01=>"zx_config.json",0x02=>"kx_config.json",0x03=>"wbl_config.json",0x04=>"yyj_config.json",0x05=>"rqz_config.json",0x06=>"xdg_config.json",0x07=>"rsq_config.json",0x08=>"xwj_config.json",0x09=>"jsq_config.json",0x0a=>"zwytj_config.json");
//$g_authentication = NULL;		//cache

function getIndexByTable($table){
    global $DEVICE_TYPE_CONFIG;
    for ($i = 1; $i <= count($DEVICE_TYPE_CONFIG); $i++){
        if ($DEVICE_TYPE_CONFIG[$i] == $table)
        {
            return $i;
        }
    }
}
function setSqlConfig($data, $table)
{
	if (strchr($table, "authentication.json") !== false){
		//
	}else if (strchr($table, "device.json") !== false){
		//return getDeviceList(explode("/", $table)[0]);
	}else if (strchr($table, "config.json") !== false){
		return setDeviceConfig($data, explode("/", $table)[0], getIndexByTable(explode("/", $table)[1]));
	}else{
		return;
	}
}

function getSqlConfig($table){
	if (strchr($table, "authentication.json") !== false){
		return getAuthentication();
	}else if (strchr($table, "device.json") !== false){
		return getDeviceList(explode("/", $table)[0]);
	}else if (strchr($table, "config.json") !== false){
		return getDeviceConfig(explode("/", $table)[0], getIndexByTable(explode("/", $table)[1]));
	}else{
		return false;
	}
}

function addUser_mysql($user, $pwd){
	$con = connectDatabase();
	$sql = sprintf("select * from authentication where username='%s'", $user);
	$res = mysql_query($sql);
	$result = -1;
	if(mysql_fetch_array($res)){
		$result = 1;
	}else{
		$sql = sprintf("insert into authentication (username, password)
			values('%s', '%s')", $user, $pwd);
		$res = mysql_query($sql);
		$result = 0;
	}
	mysql_close($con);
	return $result;
}

function addDevice_mysql($user, $device){
	$userid = getUserIdByName($user);
	$con = connectDatabase();
	$sql = sprintf("select * from config where DEVICEID = '%s' and USERID = '%s'", $device, $userid);
	$res = mysql_query($sql);
	$result = -1;
	if(mysql_fetch_array($res)){
		$result = 1;
	}else{
		$sql = sprintf("insert into config (USERID, DEVICEID)
			values('%s', '%s')", $userid, $device);
		$res = mysql_query($sql);
		$result = 0;
	}
	mysql_close($con);
	return $result;
}

function delDevice_mysql($user, $device){
	$userid = getUserIdByName($user);
	$con = connectDatabase();
	$sql = sprintf("select * from config where DEVICEID = '%s' and USERID = '%s'", $device, $userid);
	$res = mysql_query($sql);
	$result = -1;
	if(mysql_fetch_array($res)){
		$sql = sprintf("delete from config where DEVICEID = '%s' and USERID = '%s'", $device, $userid);
		$res = mysql_query($sql);
		$result = 0;
	}else{
		$result = 1;
	}
	mysql_close($con);
	return $result;
}

function getAuthentication(){
	$con = connectDatabase();
	$res = mysql_query("select * from authentication");
	mysql_close($con);
	$result = new StdClass();
	while($row = mysql_fetch_array($res)){
		$result->$row["username"] = $row["password"];
	}
	return $result;
}

function getDeviceList($user, $type){
	$userid = getUserIdByName($user);
	$con = connectDatabase();
	$sql = sprintf("select DEVICEID from config where USERID = '%s'", $userid);
	$res = mysql_query($sql);
	$result;
	while($row = mysql_fetch_array($res)){
		$sql2 = sprintf("select * from device where DEVICEID = '%s'", $row["DEVICEID"]);	
		$res2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($res2);
		$result[] = array($row2["deviceType"], $row2["webFile"], $row2["deviceName"], $row2["DEVICEID"]);
	}
	mysql_close($con);
	return json_decode(json_encode($result));
}

function getUserIdByName($user){
	$con = connectDatabase();
	$sql = sprintf("select USERID from authentication where username='%s'", $user);
	$res = mysql_query($sql);
	if($row = mysql_fetch_array($res)){
		return $row["USERID"];
	}
	mysql_close($con);
}

function getDeviceConfig($user, $type){
	$userid = getUserIdByName($user);
	$con = connectDatabase();
	$sql = sprintf("select * from config where USERID = '%s' and DEVICEID = '%s'", $userid, $type);
	$res = mysql_query($sql);
	$result;
	if($row = mysql_fetch_array($res)){
		if ($row["deviceStatus"] == NULL){
			$result["config"] = array();
		}
		else{
			$result["config"] = explode(',', $row["deviceStatus"]);
		}

		if ($row["wifiStatus"] == NULL){
			$result["wifi"] = array();
		}
		else{
			$result["wifi"] = explode(',', $row["wifiStatus"]);
		}
	}
	mysql_close($con);
	return json_decode(json_encode($result));
}

function setDeviceConfig($data, $user, $type){
	$userid = getUserIdByName($user);
	$con = connectDatabase();
	$sql = sprintf("update config set deviceStatus = '%s', wifiStatus = '%s' where USERID = '%s' and DEVICEID = '%s'",
	 implode(',', $data["config"]), implode(',', $data["wifi"]), $userid, $type);
	$res = mysql_query($sql);

	mysql_close($con);
	return true;
}

function connectDatabase(){
	$con=mysql_connect("localhost:3306","root","");
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
		return false;
	}
	mysql_select_db("pcsimulate", $con);
	return $con;
}

function crateDatabase(){
	if (mysql_query("CREATE DATABASE pcsimulate",$con))
	{
		echo "Database created";
	}
	else
	{
		echo "Error creating database: " . mysql_error();
	}
	echo "<br>";
}

function createTable(){
	$sql = "CREATE TABLE authentication 
	(
		USERID int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(USERID),
		username varchar(20),
		password varchar(20)
	)";
	if(mysql_query($sql,$con))
	echo "create table authentication success";
	else
	echo "<br>create error ".mysql_error();

	$sql = "CREATE TABLE device 
	(
		DEVICEID int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(DEVICEID),
		deviceType varchar(20),
		webFile varchar(20),
		deviceName varchar(50),
		listIndex int
	)";
	if(mysql_query($sql,$con))
	echo "create table success";
	else
	echo "<br>create error? ".mysql_error();

	$sql = "CREATE TABLE config 
	(
		CONFIGID int NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(CONFIGID),
		deviceType varchar(20),
		webFile varchar(20),
		deviceName varchar(50),
		listIndex int
	)";
	if(mysql_query($sql,$con))
	echo "create table success";
	else
	echo "<br>create error? ".mysql_error();
}
?> 