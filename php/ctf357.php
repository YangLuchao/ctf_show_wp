<?php
error_reporting(0);
highlight_file(__FILE__);
$url = $_POST['url'];
$x = parse_url($url);
if ($x['scheme'] === 'http' || $x['scheme'] === 'https') {
    $ip = gethostbyname($x['host']);
    echo '</br>' . $ip . '</br>';
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        die('ip!');
    }


    echo file_get_contents($_POST['url']);
} else {
    die('scheme');
}
?>