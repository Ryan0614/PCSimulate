<head>
    <title>mysql</title>
</head>

<?php 
//include_once('protocol.php');

echo "*********************** PC模拟器协议测试************************";
echo "<br>";


echo "<br>";
$con=mysql_connect("localhost:3306","root","");
if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
// if (mysql_query("CREATE DATABASE pcsimulate",$con))
// {
// 	echo "Database created";
// }
// else
// {
// 	echo "Error creating database: " . mysql_error();
// }
echo "<br>";
//table
mysql_select_db("pcsimulate", $con);
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
	deviceName varchar(50)
)";
if(mysql_query($sql,$con))
echo "create table success";
else
echo "<br>create error? ".mysql_error();

$sql = "CREATE TABLE config 
(
	CONFIGID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(CONFIGID),
	USERID int NOT NULL,
	DEVICEID int NOT NULL,
	deviceStatus varchar(100),
	wifiStatus varchar(20),
	listIndex int
)";
if(mysql_query($sql,$con))
echo "create table success";
else
echo "<br>create error? ".mysql_error();



// mysql_query("INSERT INTO authentication ( username, password) 
// 	VALUES ( 'shiyja', 'shiyinjun')");
// echo "<br>insert error ".mysql_error();

// mysql_query("INSERT INTO device (deviceType, webFile, deviceName) 
// 	VALUES ('蒸箱', 'ZX001.php', '蒸箱001')");
// echo "<br>insert error? ".mysql_error();


//mysql_query("INSERT INTO config (USERID, DEVICEID, deviceStatus, wifiStatus, listIndex) 
 //	VALUES ('1', '1', '1,0,1,128,0,1,20,34,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0', '', '0')");



mysql_close($con);
echo "<br>";

echo "<br>";
 


echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
echo "********************** PC模拟器协议测试 END*********************";
?> 