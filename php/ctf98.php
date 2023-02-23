<?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-09-16 11:25:09
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-18 21:39:27
# @link: https://ctfer.com

*/

include("flag.php");
$flag = "123";
$_GET ? $_GET =& $_POST : 'flag';
$_GET['flag'] == 'flag' ? $_GET =& $_COOKIE : 'flag';
$_GET['flag'] == 'flag' ? $_GET =& $_SERVER : 'flag';
highlight_file($_GET['HTTP_FLAG'] == 'flag' ? $flag : __FILE__);

// 改一下代码，增强可读性

include('flag.php');
if ($_GET) {
    $_GET =& $_POST;//只要有输入的get参数就将get方法改变为post方法(修改了get方法的地址)
} else {
    "flag";
}
if ($_GET['flag'] == 'flag') {
    $_GET =& $_COOKIE;
} else {
    'flag';
}
if ($_GET['flag'] == 'flag') {
    $_GET =& $_SERVER;
} else {
    'flag';
}
if ($_GET['HTTP_FLAG'] == 'flag') {
    $flag;
} else {
    'flag';
}