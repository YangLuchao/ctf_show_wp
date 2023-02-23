<?php

highlight_file(__FILE__);

class ctfshowvip
{
    public $username;
    public $password;
    public $code;

    // 构建的时候调用
    public function __construct($u, $p)
    {
        $this->username = $u;
        $this->password = $p;
    }

    // 反序列化执行是会被调用
    public function __wakeup()
    {
        if ($this->username != '' || $this->password != '') {
            die('error');
        }
    }

    // 调用对象方法时调用
    public function __invoke()
    {
        eval($this->code);
    }

    // 序列化是会判断是否存在__sleep函数，存在优先调用后再进行序列化
    public function __sleep()
    {
        $this->username = '';
        $this->password = '';
    }

    // 反序列化存在时，不会执行__wakeup
    public function __unserialize($data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->code = $this->username . $this->password;
    }

    // 析构函数
    public function __destruct()
    {
        // 0x36 === 877
        if ($this->code == 0x36d) {
            // 落文件
            // 名字，内容
            file_put_contents($this->username, $this->password);
        }
    }
}

unserialize($_GET['vip']);