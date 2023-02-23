<?php
$array= localeconv();
foreach ($array as $val){
    echo "值是：" . $val ."\n";
}
//echo '/n';
echo pos(localeconv())."\n";
$array2 = scandir(pos(localeconv()));
foreach ($array2 as $val){
    echo "值是：" . $val ."\n";
}
//array_reverse 倒序
//next 第二个
// show_source显示源码
//echo show_source(next(array_reverse(scandir(pos(localeconv())))));