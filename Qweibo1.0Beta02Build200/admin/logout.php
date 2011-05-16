<?php
@include_once MB_CONF_DIR.'/user_config.php';
require_once('inc/admin_check.php');
unset($_SESSION['user']);
unset($_SESSION['status']);
unset($_SESSION['pro']);
unset($_SESSION['u_id']);
unset($_SESSION['bkurl']);
unset($_SESSION['account']);
unset($_SESSION['u_name']);
unset($_SESSION['s']);
unset($_SESSION['p']);
header('Location: login.php');
?>
