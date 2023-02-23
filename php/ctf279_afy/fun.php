<?php
function sds_decode($str){
	return md5(md5($str.md5(base64_encode("sds")))."sds");
}
?>