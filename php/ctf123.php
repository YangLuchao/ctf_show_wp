<?php

/*
# -*- coding: utf-8 -*-
# @Author: Firebasky
# @Date:   2020-09-05 20:49:30
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-07 22:02:47
# @email: h1xa@ctfer.com
# @link: https://ctfer.com

*/
error_reporting(0);
highlight_file(__FILE__);
include("flag.php");
$fl0g = "123";
$flag = "123";
$a=$_SERVER['argv'];
$c=$_POST['fun'];
echo $_POST['CTF_SHOW']."<br>";
echo $_POST['CTF_SHOW.COM']."<br>";
if(isset($_POST['CTF_SHOW'])&&isset($_POST['CTF_SHOW.COM'])&&!isset($_GET['fl0g'])){
    // c 是fun c不能有特殊符号 c长度要小于18
    if(!preg_match("/\\\\|\/|\~|\`|\!|\@|\#|\%|\^|\*|\-|\+|\=|\{|\}|\"|\'|\,|\.|\;|\?/", $c)&&$c<=18){
        // 先执行C
        eval("$c".";");
        if($fl0g==="flag_give_me"){
            echo $flag;
        }
    }
}
?>