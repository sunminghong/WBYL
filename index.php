<?php
if((!isset($_GET["app"]) || !$_GET['app']) && (!isset($_GET["act"]) || !$_GET["act"])){
	//$defapp="iq";
	$tourl="?app=iq";
	header("Location: $tourl");exit;
}
include_once('include/common.php');
LetGo();
?>