<?php
	$tourl="../?app=q&lfrom=".$_GET['lfrom'];
	$retuid=$_GET["retuid"];
		if($retuid)
			$tourl.="&retuid=" .$retuid;
		
		$retapp=$_GET["retapp"];
		if($retapp)
			$tourl .="&retapp=".$retapp;
		header("Location: $tourl");exit;
?>