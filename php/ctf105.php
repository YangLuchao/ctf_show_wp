<?php

/*
# -*- coding: utf-8 -*-
# @Author: Firebasky
# @Date:   2020-09-16 11:25:09
# @Last Modified by:   h1xa
# @Last Modified time: 2020-09-28 22:34:07

*/

highlight_file(__FILE__);
$flag = "123";
error_reporting(0);
$error = '你还想要flag嘛？';
$suces = '既然你想要那给你吧！';
foreach ($_GET as $key => $value) {
    if ($key === 'error') {
        die("what are you doing?!");
    }
    $$key = $$value;
}
foreach ($_POST as $key => $value) {
    if ($value === 'flag') {
        die("what are you doing?!");
    }
    $$key = $$value;
}
if (!($_POST['flag'] == $flag)) {
    die($error);
}
echo "your are good" . $flag . "\n";
die($suces);

// php的变量覆盖 payload： GET: ?suces=flag POST: error=suces