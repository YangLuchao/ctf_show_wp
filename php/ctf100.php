<?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-09-16 11:25:09
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-21 22:10:28
# @link: https://ctfer.com

*/

highlight_file(__FILE__);
//include("ctfshow.php");
//flag in class ctfshow;
//$ctfshow = new ctfshow();
$v1 = $_GET['v1'];
echo $v1."|||   ";
$v2 = $_GET['v2'];
echo $v2."|||   ";
$v3 = $_GET['v3'];
echo $v3."|||   ";
$v0 = is_numeric($v1) and is_numeric($v2) and is_numeric($v3);
echo is_numeric($v1)."|||   ";
echo is_numeric($v2)."|||   ";
echo is_numeric($v3)."|||   ";
echo $v0;
//if ($v0) {
//    if (!preg_match("/\;/", $v2)) {
//        if (preg_match("/\;/", $v3)) {
//            eval("$v2('ctfshow')$v3");
//        }
//    }
//}
// payload:v1=21&v2=var_dump($ctfshow)/*&v3=*/;

?>
