<?php

//模式分隔符后的"i"标记这是一个大小写不敏感的搜索
if (!preg_match("/flag|system|php|cat|sort|shell|\.| |\'|\`|echo|\;|\(/i",
    "include\$_GET[a]?>&a=data://text/plain,<?=system('tac flag.php');?>")) {
    echo "未发现匹配的字符串 php。";
} else {
    echo "查找到匹配的字符串 php。";
}
