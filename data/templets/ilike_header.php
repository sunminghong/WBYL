<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>看看我的范儿</title>

<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="js/jquery.easydrag.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="js/pngfix.js"></script>
<style type="text/css">.pngfix{behavior: url("js/iepngfix.htc");}</style>
<![endif]--> 
<link href="<?=$templatepath?>/ilike_images/css.css" rel="stylesheet" type="text/css" />
<script>var op="<?=$op?>"; var wbisload=false; var templateurl='<?=$templatepath?>'; var urlbase='<?=$urlbase?>';<? if($account) { ?>var logined=true; var myuid='<?=$account['uid']?>';<? } else { ?>var logined=false;  var myuid='';<? } ?>var lfrom='<?=$lfrom?>';</script>
</head>
<body>
	<div id="topbg">    
    <div id="topcon">    
	<!--<div id="logo"><b style="color:#f00;">看</b><b style="color:#f90;">看</b><b style="color:#ff0;">我</b><b style="color:#090;">的</b><b style="color:#f0c;">范</b><b style="color:#f0f;">儿</b> <sup style="font-size:12px;color:#666;">V1.0</sup></div>-->
	<div id="logo"><a href="<?=$urlbase?>"><img src="<?=$templatepath?>/ilike_images/logo_55.gif" title="看看我的范儿的LOGO"/></div>
	<div id="menu">
		<a href="<?=$urlbase?>" title="主页">看范儿</a>
	</div>
	<? if($account) { ?>
	<div id="aboutfaner"><img style="height:45px;" onerror="this.src='<?=$templatepath?>/images/noavatar.gif';"  src="<?=$account['avatar']?>" align="right"/><?=$account['screen_name']?><img src="<?=$urlbase?>images/weiboicon16_<?=$account['lfrom']?>.png" height=16 /><? if($account['verified']==1) { ?><img src="<?=$urlbase?>images/vip_<?=$account['lfrom']?>.gif" title="认证用户" alt=""><? } ?>，
		<? if($score) { ?>
		你现在得<b id="myscore"><?=$score['avg']?></b>分/<b id="mybyratecount"><?=$score['byratecount']?></b>人，被分享<b id="mybyratecount"><?=$score['bysharecount']?></b>次，被关注<b id="mybyratecount"><?=$score['byfollowcount']?></b>次，<br/>上传<b id="mypiccount"><?=$score['piccount']?></b>个范儿，看了<b id="myratecount"><?=$score['ratecount']?></b>个范儿，共评出 <b id="myratescore"><?=$score['ratescore']?></b>分，分享<b id="mybyratecount"><?=$score['sharecount']?></b>次 。
		<? } else { ?>
		你现在得<b id="myscore">0</b>/<b id="mybyratecount">0</b>次，上传<b id="mypiccount">0</b>个范儿，<br/>看了<b id="myratecount">0</b>个范儿，共评出 <b id="myratescore">0</b>分。
		<? } ?></div>
	<? } else { ?>
	<div id="aboutfaner" style="color:#ccc;">北京话"范儿"就是"劲头""派头"的意思，包含气质、有情调、有品味、有型、有个性等多种含义，可意会不可言传。看看你的范儿，评评TA的范儿！</div>
	<? } ?>
    <div style="clear:both;font-size:0;"></div>
    </div>
	</div>
	<div id="nav">
		<div class="navcon">
			<a href="?act=daytop">每日之最</a>　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　<a href="javascript:void(0);" onclick="_follow('');" style="color:#FF0080;">关注官方微博（孙铭鸿）</a>
		</div>
	</div>