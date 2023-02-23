<?php
session_start();
unset($_SESSION['login']);
unset($_SESSION['error']);
header("location:login.php");
?>