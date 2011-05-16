<?php
date_default_timezone_set('Asia/Shanghai');
define( "MB_admin", true );
session_start();
if($_SESSION['user'] == ''){
	header("Location: login.php");
	exit();
}
?>
