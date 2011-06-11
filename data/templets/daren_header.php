<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>快乐达人！ -  看看你有多聪明系列 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/daren_images/daren.css" />

<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="js/jquery.progressbar.js"></script>
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';</script>
<script type="text/javascript" src="<?=$templatepath?>/daren_images/daren.js?v=1.0"></script>

</head>
<body>
<div id="header">
	<div class="mw">
		<div id="logo"><a href="<?=$urlbase?>" target="_blank">知识达人</a>
		</div>
		<div id="info">
		<img src="images/noavatar.gif" align="left" />@<?=$score['name']?>，你现在共参加过了<?=$score['filishcount']?>次测试，得到了 <b><?=$score['wincount']?>枚</b> 达人勋章、 <b><?=$score['topcount']?>枚</b> 牛人勋章。今天总分是 <b><?=$score['todaytotalscore']?>分</b>。
		<!--<div class="avatar_foot"><div>
		<div class="avatar_maozi"></div>-->
		</div>
	</div>
</div>