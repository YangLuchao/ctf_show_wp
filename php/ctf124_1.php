<?php


// 把 hex2bin转化为10进制
echo base_convert("hex2bin", 36, 16);   //37907361743
echo "\n";
echo base_convert("8d3746fcf", 16, 36);  //hex2bin
echo "\n";
//把_GET 先转为16进制再转为10进制
echo hexdec(bin2hex("_GET"));  //1598506324
echo "\n";
echo base_convert("8d3746fcf", 16, 36)(dechex("1598506324"));  // 绕过过滤拿到 "_GET"

