<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$pagetitle?>你太有才了！ - 看看你有多聪明系列 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/daren_images/daren.css" />

<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
<? if($op=="ican") { ?>
	<link type="text/css" rel="stylesheet" href="js/colorbox.css" />
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<? } ?>
<!--[if IE 6]>
<script type="text/javascript" src="js/pngfix.js"></script>
<style type="text/css">.pngfix{behavior: url("js/iepngfix.htc");}</style>
<![endif]--> 
<script>var op="<?=$op?>"; var logined=<?=$logined?>; var urlbase='<?=$urlbase?>'; var urltemplate="<?=$templatepath?>";</script>
<script type="text/javascript" src="<?=$templatepath?>/daren_images/daren.js?v=1.2"></script>

</head>
<body>


<div id="header">
	<div class="mw">
		<div id="logo" class="pngfix"><a href="<?=$urlbase?>" target="_blank">你太有才了！</a>
		</div>
		<div id="info">
		<? if($op=="profile") { ?>
			<img src="<?=$totalcountlist['avatar']?>" align="left" />
			<span class="profile_title"><?=$totalcountlist['name']?>的成就</span>
		<? } else { ?>
			<? if(!$score) { ?>
			<img src="images/avatar_miqiba.jpg" align="left" />hi，我是开发者 <a href="<?=$orgwbsite?>" target="_blank">@米奇吧</a>，#你太有才了#是一款丰富知识的小游戏，寓教于乐。每天的努力赢取的#智慧币#，将是你参加高级玩法的筹码哟！
			<? } else { ?>
				<img src="<?=$score['avatar']?>" align="left" />
				<? if($isret && ($op=='index' || $op=='top')) { ?>
				hi，我是<a href="?op=profile&uid=<?=$score['uid']?>" target="_blank">@<?=$score['name']?></a>。我共参加过<?=$score['filishcount']?>次每日测试，得到了 <b><?=$score['wincount']?>枚</b> 达人勋章、 <b><?=$score['topcount']?>枚</b> 牛人勋章。今天总分是 <b><?=$score['todaytotalscore']?>分</b>，呵呵！
				<? } else { ?>
				<a href="?op=profile&uid=<?=$account['uid']?>" target="_blank">@<?=$score['name']?></a>，你共参加过了<?=$score['filishcount']?>次每日测试，得到了 <b><?=$score['wincount']?>枚</b> 达人勋章、 <b><?=$score['topcount']?>枚</b> 牛人勋章，拥有<b><?=$score['jifen']?>枚</b>智慧币。今天总分是 <b><?=$score['todaytotalscore']?>分</b>。
				<? } ?>
			<? } ?>
		<? } ?>
		</div>
		<div id="avatar_maozi"></div>
	</div>
</div>
<div  id="menunav">
	<div class="mw">
		<a href="<?=$urlbase?>"<? if($op=="index") { ?> class="curr"<? } ?>>首页</a>
		<a href="?op=ican"<? if($op=="ican") { ?> class="curr"<? } ?>>每日十问</a>
		<a href="?op=profile"<? if($op=="profile") { ?> class="curr"<? } ?>>你的成就</a>
		<a href="?op=top"<? if($op=="top") { ?> class="curr"<? } ?>>排行榜</a>
		<a href="javascript:void(0);" onclick="alert('想用智慧币兑换奖品？你赚多点再来吧！\n（奖品正在筹备，欢迎奖品合作！）')"<? if($op=="cake") { ?> class="curr"<? } ?>>兑换中心</a>
		<a href="javascript:void(0);" onclick="_follow();" style="color:#f00;">关注米奇吧，立即获取10枚智慧币！</a>
		<a id="btn_help" href="javascript:void(0);" class="pngfix">游戏攻略</a>
<div class="cl"></div>
		

	</div>
</div>
