<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>你太有才了！ -  看看你有多聪明系列 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/daren_images/daren.css" />

<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="js/jquery.progressbar.js"></script>
<script>var op="<?=$op?>"; var logined=<?=$logined?>; var urlbase='<?=$urlbase?>'; var urltemplate="<?=$templatepath?>";</script>
<script type="text/javascript" src="<?=$templatepath?>/daren_images/daren.js?v=1.0"></script>
<!--[if IE 6]>
<script type="text/javascript" src="js/pngfix.js"></script>
<style type="text/css">.pngfix{behavior: url("js/iepngfix.htc");}</style>
<![endif]--> 
</head>
<body>


<div id="header">
	<div class="mw">
		<div id="logo" class="pngfix"><a href="<?=$urlbase?>" target="_blank">你太有才了！</a>
		</div>
		<div id="info">
		<? if(!$score) { ?>
		<img src="images/noavatar.gif" align="left" />hi，我是开发者 <a href="<?=$orgwbsite?>" target="_blank">@孙铭鸿</a>，#你太有才了#是一款丰富知识的小游戏，寓教于乐。每天的得分将是你参加高级玩法的筹码哟！
		<? } else { ?>
			<img src="<?=$score['avatar']?>" align="left" />
			<? if($isret) { ?>
			hi，我是<a href="?op=profile&uid=<?=$score['uid']?>" target="_blank">@<?=$score['name']?></a>。我共参加过<?=$score['filishcount']?>次每日测试，得到了 <b><?=$score['wincount']?>枚</b> 达人勋章、 <b><?=$score['topcount']?>枚</b> 牛人勋章。今天总分是 <b><?=$score['todaytotalscore']?>分</b>，呵呵！
			<? } else { ?>
			<a href="?op=profile&uid=<?=$account['uid']?>" target="_blank">@<?=$score['name']?></a>，你共参加过了<?=$score['filishcount']?>次每日测试，得到了 <b><?=$score['wincount']?>枚</b> 达人勋章、 <b><?=$score['topcount']?>枚</b> 牛人勋章。今天总分是 <b><?=$score['todaytotalscore']?>分</b>。
			<? } ?>
		<? } ?>
		<!--<div class="avatar_foot"><div>
		<div class="avatar_maozi"></div>-->
		</div>
	</div>
</div>