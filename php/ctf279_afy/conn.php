<?php

$mysqluser="root";
$mysqlpwd="*******";
$mysqldb="sds";
$mysqlhost="localhost";
$mysqlport="3306";
$mysqli=@new mysqli($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);
if(mysqli_connect_errno()){
	die(mysqli_connect_error());
}


?>

