<?php

/*
# -*- coding: utf-8 -*-
# @Author: h1xa
# @Date:   2020-12-02 17:44:47
# @Last Modified by:   h1xa
# @Last Modified time: 2020-12-02 19:29:02
# @email: h1xa@ctfer.com
# @link: https://ctfer.com

*/

error_reporting(0);
highlight_file(__FILE__);
//include('flag.php');
$flag = 123;
class ctfShowUser
{
    public $username = 'xxxxxx';
    public $password = 'xxxxxx';
    public $isVip = false;

    public function checkVip()
    {
        return $this->isVip;
    }

    public function login($u, $p)
    {
        // username = xxxxxx
        // password = xxxxxx
        if ($this->username === $u && $this->password === $p) {
            $this->isVip = true;
        }
        return $this->isVip;
    }

    public function vipOneKeyGetFlag()
    {
        if ($this->isVip) {
            global $flag;
            echo "your flag is " . $flag;
        } else {
            echo "no vip, no flag";
        }
    }
}

// get username
$username = $_GET['username'];
// get password
$password = $_GET['password'];

if (isset($username) && isset($password)) {
    $user = new ctfShowUser();
    // 登录返回true
    if ($user->login($username, $password)) {
        // vip true
        if ($user->checkVip()) {
            $user->vipOneKeyGetFlag();
        }
    } else {
        echo "no vip,no flag";
    }
}

