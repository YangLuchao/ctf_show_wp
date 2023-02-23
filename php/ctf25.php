<?php

//error_reporting(0);
//include("flag.php");
//if(isset($_GET['r'])){
//    $r = $_GET['r'];
//    mt_srand(hexdec(substr(md5($flag), 0,8)));
//    $rand = intval($r)-intval(mt_rand());
//    if((!$rand)){
//        if($_COOKIE['token']==(mt_rand()+mt_rand())){
//            echo $flag;
//        }
//    }else{
//        echo $rand;
//    }
//}else{
//    highlight_file(__FILE__);
//    echo system('cat /proc/version');
//}


    mt_srand(665562636);
    $rand = intval(362211689)-intval(mt_rand());
    if((!$rand)) {
//        if (1228142661 == (mt_rand() + mt_rand())) {
//            echo 12;
//        }
        echo mt_rand() + mt_rand();
//        echo mt_rand();
    }