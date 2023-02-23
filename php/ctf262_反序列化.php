<?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-12-03 02:37:19
# @Last Modified by:   h1xa
# @Last Modified time: 2020-12-03 16:05:38
# @message.php
# @email: h1xa@ctfer.com
# @link: https://ctfer.com

*/


error_reporting(0);

class message
{
    public $from;
    public $msg;
    public $to;
    public $token = 'user';

    public function __construct($f, $m, $t)
    {
        $this->from = $f;
        $this->msg = $m;
        $this->to = $t;
    }
}

$f = $_GET['f'];
$m = $_GET['m'];
$t = $_GET['t'];

if (isset($f) && isset($m) && isset($t)) {
    $msg = new message($f, $m, $t);
    $umsg = str_replace('fuck', 'loveU', serialize($msg));
    setcookie('msg', base64_encode($umsg));
    echo 'Your message has been sent';
}

highlight_file(__FILE__);


