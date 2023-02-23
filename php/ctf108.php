<?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-09-16 11:25:09
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-28 23:53:55

*/


highlight_file(__FILE__);
error_reporting(0);
$flag = "123";
//if (ereg ("^[a-zA-Z]+$", $_GET['c'])===FALSE)  {
//    echo 789;
//    die('error');
//}
//只有36d的人才能看到flag
echo intval('877a')."<br>";
if(intval(strrev($_GET['c']))==0x36d){
    echo $flag;
}

?>