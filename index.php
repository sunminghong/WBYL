<?php
if(!$_GET["app"] && !isset($_GET["act"])){
	//$defapp="iq";
			$tourl="?app=iq";
			header("Location: $tourl");exit;
}
include_once('include/common.php');
LetGo();
?>