<?php
error_reporting(0);
//$c="echo `nl ctf28.php`;";
//echo 222;
//if(isset($c)){
//    echo 111;
//    if(!preg_match("/flag/i", $c)){
//        echo 1234444;
//        eval($c);
//    } else {
//        echo 12;
//    }
//    echo 23;
//}else{
//    echo 34;
//    highlight_file(__FILE__);
//}
//$l = localeconv();
//echo $l;
echo show_source(next(array_reverse(scandir(pos(localeconv())))));
//通过货币信息(localeconv())取这个点(pos())来到当前目录(scandir())把目录结果进行翻转(array_reverse())取向下一个，然后展示源码(show_source)