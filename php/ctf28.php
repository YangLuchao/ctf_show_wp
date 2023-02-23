<?php
$myfile = fopen("num1-100.txt", "w") or die("Unable to open file!");
$i = 100;
for ($i = 1; $i <=100; $i++){
    fwrite($myfile, $i. "\n");
}
fclose($myfile);