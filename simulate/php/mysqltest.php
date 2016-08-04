<head>
    <title>mysql</title>
</head>

<?php 
include_once('protocol.php');

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

mysql_select_db("pcsimulate", $con);
$sql = "CREATE TABLE Users 
(
	UserID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(UserID),
	UserID varchar(15),
	UserName varchar(20),
	PassWord varchar(20)
)";
echo mysql_error();
$query=mysql_query($sql,$con);
echo $query;
mysql_close($con);
echo "<br>";

echo "<br>";
 





echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
echo "********************** PC模拟器协议测试 END*********************";
?> 