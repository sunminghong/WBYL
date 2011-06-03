<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博IQ测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iq_images/iq.css?v=5.34" />
	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> 
<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';</script>
<script type="text/javascript" src="<?=$templatepath?>/iq_images/iq_min.js?v=5.34"></script>
<? if($op=="ready" || $op=="showscore") { ?>
<style>

ul.test{ margin: 10px 0; line-height: 21px;}
ul.test li{color: #666; list-style: inside disc;text-align:left;
font: 12px/1.5em Tahoma, Arial, Helvetica, snas-serif;
}
ul.test li a {color:#f00;font-size:12px;line-height:18px;}
</style>
<? } else { ?>
<style>
dt {text-align:left;font-size:14px;font-weight:bold;}
li {text-align:left;padding-top:5px;padding-bottom:5px;list-style-type:none;}
ol.first li {list-style-type:decimal ;}
</style>
<? } ?>
</head>
<body>
<? include $this->gettpl('top');?>
	<div class="mainFrame">
		
<? if($op == 'showscore') { ?>	
<div class="ui-widget">
			<div class="login">
<? if(is_array($account) ) { ?>
			<img class="zhengshuico icoview" zsurl="<?=$iqScore['zsurl']?>" src="<?=$urlbase?>images/zhengshu_iq_ico_<?=$iqScore['iqlv']?>.png"/>
<font color="#ff3333"><?=$account['screen_name']?></font>, 你共测试<?=$iqScore['testCount']?> 次， 最高IQ值是 <?=$iqScore['iq']?> , 排名第<b> <?=$iqScore['top']?> </b>, 打败了 <b><?=$iqScore['win']?></b>人<? if($iqScore['lostname'] ) { ?>(包括<?=$iqScore['lostname']?>~_~）<? } ?>，加油！<a href="javascript:void(0);" onclick="if(typeof sendmsg =='function')sendmsg();">记录到微博</a>　<a href="?app=home&act=account&op=logout">切换帐号</a>
<? } else { ?>
<? global $canLogin;?>
			<? foreach((array)$canLogin as $login) {?>
				<a href="?app=home&act=account&op=tologin&lfrom=<?=$login?>" border="0"><img height="24" src="<?=$templatepath?>/images/btn_login_<?=$login?>.png" alt="用微博帐号登录" /></a> 
			<? } ?>
		<? } ?>
		<br/><br/>
<? if(($app=='eq')) { ?>
<a href="?app=eq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_eq.gif" alt="我来测试下EQ"/></a>
<a href="?app=iq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_iq.gif" alt="我来测试下IQ"/></a>
<? } else { ?>
<a href="?app=iq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_iq.gif" alt="我来测试下IQ"/></a>
<a href="?app=eq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_eq.gif" alt="我来测试下EQ"/></a>
<? } ?>
	 <a href="?app=iq&op=stats"><img src="<?=$templatepath?>/q_images/btn_stats.gif" alt="聪明排行榜" ></a>

			</div>			
			<div class="logo" style="position:relative;">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/logo_q.gif" alt="看看你有多聪明LOGO"/></a>
				<a href="<?=$orgwbsite?>" target="_blank" style="display:block;position:absolute;left:94px;top:50px;"><img src="<?=$templatepath?>/q_images/btn_5d13site_logo.gif" alt="我的一生官方微博孙铭鸿"/></a>
			</div>
		</div>

<div id="div_notice" style="clear:both;"></div>
<div id="ad_800_60" style="width:800px;height:60px;magin-top:5px;margin-bottom:5px;"></div>	
		<div class="contentFrame" style="clear:both;text-align:center; ">
			<div><p>
					<strong>您本次智商测试得分为<?=$score['nowiq']?>分 ，用时 <?=$score['newusetime']?></strong></p>
				</div>
			<div class="welcomeDiv1" style="">
				<img id="result_zhengshuPic" src="<?=$score['zsurl']?>" align="left" style="width:350px;margin-right:15px;"/>
				<br/>
				<span id="div_result" style="padding-top:20px;color:#000;line-height:1.5em;font-size:14px;">
你共测试<?=$score['testCount']?> 次， 最高IQ值是<?=$score['iq']?> , 排名第 <b><?=$score['top']?></b>，打败了 <b><?=$score['win']?></b>人
<? if($score['lostname']) { ?>（包括<?=$score['lostname']?>~_~）<? } ?>，并获得【<?=$score['chs']?>】证书（见右），好好学习，#天天向上#！！！
</span>
				<br/>
				<br/>专家点评：
				<span id="div_words" style="font-size:12px;font-weight:normal;color:#333;">					
					<?=$score['words']?>
					</span><br/> <br/> 
				<span style="font-size:12px;font-weight:normal;color:#999;">	<code>				
					0-89       -- 愚不可及 				
					90-99     -- 呆头呆脑<br/>				
					100-109 -- 波澜不兴
					110-119 -- 聪明过人<br/>
					120-129 -- 颖慧绝伦
					130-139 -- 旷世奇才<br/>
					140-?     -- 文曲星转世</code>
					</span><br/> <br/> <a href="javascript:void(0);"  onclick="sendmsg(true);"><img src="<?=$templatepath?>/iq_images/btn_tweet_blue.gif" /></a> <div style="height:2px;"></div>
									<a href="javascript:void(0);" onclick="follow(true);" title="谢谢你的关注，我们会定期在官网公告“聪明行情”！"><img src="<?=$templatepath?>/iq_images/btn_follow.gif" title="关注我"/></a>
<br/><br/>

  接下来，您还可以：
  <ul class="test">
    <li><a href="?app=iq&op=stats" target="_blank">看看你的好友的IQ情况&raquo;</a></li>
   <li><a href="?app=eq" target="_blank" style="font-size:140%;color:#00f;">进行情商（EQ）测试&raquo;</a></li>
    <li><a href="http://www.265g.com" target="_blank" title="265G是网页游戏第一门户！找网页游戏，就上265G!">上265g找找好玩的网页游戏！&raquo;</a></li>
	<li><a href="?app=iq&op=ready" style="font-size:140%;color:#f0f;">再测试一次（有两套题哟！）&raquo;</a></li>
  </ul>
				</div>
				<div style="clear:both;"></div>

<? } ?>

<? if($op=="ican" || $op=="icanv1" || $op=="icanv2") { ?>
		<div class="ui-widget">
			<div class="login">
				<font color="#ff3333"><?=$account['screen_name']?></font>, 微博IQ测试须在30分钟内完成（33题）<br/><br/>
			<span id="face"> <?=$score['newusetime']?></span>
			</div>			
<div class="logo" style="position:relative;">
				<a href="?app=eq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/logo_q.gif" alt="看看你有多聪明LOGO"/></a>
				<a href="<?=$orgwbsite?>" target="_blank" style="display:block;position:absolute;left:94px;top:50px;"><img src="<?=$templatepath?>/q_images/btn_5d13site_logo.gif" alt="我的一生官方微博孙铭鸿"/></a>
			</div>
		</div>
<div id="div_notice"></div>
		<div class="contentFrame" style="clear:both;text-align:center; ">
		<? if($op=="icanv1") { ?>
			<? include $this->gettpl('iq_v1');?>
		<? } else { ?>
			<? include $this->gettpl('iq_v2');?>
		<? } ?>
			</div>

<? } ?>
</div>
<? include $this->gettpl('iq_footer');?>