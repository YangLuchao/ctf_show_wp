<?php
//Firebasky
error_reporting(0);
$a="1234567890zxcvbnmlkjhgfdsaqwertyuiop";//字典
for($i=0;$i<36;$i++){
    for($j=0;$j<36;$j++){
        $token=$a[$i].$a[$j];
// echo md5($token)."\n";
        $token = md5($token);
        if(substr($token, 1,1)===substr($token, 14,1) && substr($token,
                14,1) ===substr($token, 17,1)){
            if((intval(substr($token, 1,1))+intval(substr($token,
                        14,1))+substr($token, 17,1))/substr($token, 1,1)===intval(substr($token,
                    31,1))){
                echo "success"."\n";
                echo $a[$i].$a[$j];
                mt_srand(372619038);
                echo mt_rand();
                exit(0);
            }
        }
    }
} ?>