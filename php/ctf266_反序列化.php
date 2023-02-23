<?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-12-04 23:52:24
# @Last Modified by:   h1xa
# @Last Modified time: 2020-12-05 00:17:08
# @email: h1xa@ctfer.com
# @link: https://ctfer.com

*/

highlight_file(__FILE__);

include('flag.php');
// 伪协议
$cs = file_get_contents('php://input');


class ctfshow
{
    public $username = 'xxxxxx';
    public $password = 'xxxxxx';

    public function __construct($u, $p)
    {
        $this->username = $u;
        $this->password = $p;
    }

    public function login()
    {
        return $this->username === $this->password;
    }

    public function __toString()
    {
        return $this->username;
    }

    // 析构函数输出flag
    public function __destruct()
    {
        global $flag;
        echo $flag;
    }
}
// 反序列化
$ctfshowo = @unserialize($cs);
if (preg_match('/ctfshow/', $cs)) {
    throw new Exception("Error $ctfshowo", 1);
}



