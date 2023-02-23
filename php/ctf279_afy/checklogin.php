<?php
error_reporting(0);
session_start();
require 'conn.php';
$_POST['userid']=!empty($_POST['userid'])?$_POST['userid']:"";
$_POST['userpwd']=!empty($_POST['userpwd'])?$_POST['userpwd']:"";
$username=$_POST['userid'];
$userpwd=$_POST['userpwd'];
// fixme sql注入
$sql="select sds_password from sds_user where sds_username='".$username."' order by id limit 1;";
$result=$mysqli->query($sql);
$row=$result->fetch_array(MYSQLI_BOTH);
if($result->num_rows<1){
	$_SESSION['error']="1";
	header("location:login.php");
	return;
}
// fixme sql注入+联合查询：select sds_password from sds_user where sds_username='' union select 1;#
if(!strcasecmp($userpwd,$row['sds_password'])){
	$_SESSION['login']=1;
	$result->free();
	$mysqli->close();
	header("location:index.php");
	return;
}
$_SESSION['error']="1";
header("location:login.php");
?>