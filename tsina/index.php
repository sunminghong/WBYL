<?php
	$tourl="../?lfrom=tsina";
	$retuid=$_GET["retuid"];
		if($retuid)
			$tourl.="&retuid=" .$retuid;
		
		$retapp=$_GET["retapp"];
		if($retapp)
			$tourl .="&retapp=".$retapp;
		header("Location: $tourl");exit;
?>