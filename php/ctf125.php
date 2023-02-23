<?php

/*
# -*- coding: utf-8 -*-
# @Author: Firebasky
# @Date:   2020-09-05 20:49:30
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-07 22:02:47
#
#
*/
error_reporting(0);
highlight_file(__FILE__);
include("flag.php");
$a=$_SERVER['argv'];
$c=$_POST['fun'];
$flag ="121";
$fl0g="12345";
$x = $_POST['CTF_SHOW'];
echo "<br>".$x."<br>";
$xx = $_POST['CTF_SHOW.COM'];
echo $xx."<br>";
echo isset($_POST['CTF_SHOW'])."<br>";
echo isset($_POST['CTF_SHOW.COM'])."<br>";
echo !isset($_GET['fl0g'])."<br>";
echo $c."<br>";
if(isset($_POST['CTF_SHOW'])&&isset($_POST['CTF_SHOW.COM'])&&!isset($_GET['fl0g'])){
    echo "12323232"."<br>";
    if(!preg_match("/\\\\|\/|\~|\`|\!|\@|\#|\%|\^|\*|\-|\+|\=|\{|\}|\"|\'|\,|\.|\;|\?|flag|GLOBALS|echo|var_dump|print/i", $c)&&$c<=16){
        eval("$c".";");
        if($fl0g==="flag_give_me"){
            echo $flag;
        }
    }
}


