<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博IQ测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iq_images/iq.css" />
	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> 
<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';</script>
<script type="text/javascript" src="<?=$templatepath?>/iq_images/iq_min.js?v=5.3"></script>
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
<div id="topbg">
	<div id="topmenu">
    	<span class="toplink">您还可以玩：<a href="http://ciniao.me/wbapp/?a=xm&from=sina" target="_blank">羡慕嫉妒恨</a></span>
    	
    </div>
</div>
	<div class="mainFrame">
		
<? if($op == 'showscore') { ?>	
<div class="ui-widget">
			<div class="login">
				<font color="#ff3333"><?=$account['screen_name']?></font>, 微博IQ测试须在30分钟内完成（33题）<br/><br/>
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">--><span id="face"> <?=$score['newusetime']?></span>
			</div>			
			<div class="logo">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>
<div id="div_notice"></div>
<div id="ad_800_60" style="width:800px;height:60px;"></div>	
		<div class="contentFrame" style="clear:both;text-align:center; ">
			<div><p>
					<strong>您本次智商测试得分为<?=$score['nowiq']?>分 ，用时 <?=$score['newusetime']?></strong></p>
				</div>
			<div class="welcomeDiv1" style="">
				<img id="result_zhengshuPic" src="<?=$score['zsurl']?>" align="left"/>
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
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">--><span id="face"> <?=$score['newusetime']?></span>
			</div>			
			<div class="logo">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明" /></a>
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