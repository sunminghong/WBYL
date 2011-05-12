<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博IQ测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/test_images/test.css" />
	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> 
<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';<? if($account) { ?>var myuid='<?=$account['uid']?>';<? } else { ?>var myuid='';<? } ?></script>
<script type="text/javascript" src="<?=$templatepath?>/test_images/test.js?v=1.0"></script>
</head>
<body>

	<div class="mainFrame">

		<div class="ui-widget">			
			<div class="login">
				<font color="#ff3333"><?=$account['screen_name']?></font>, 微博IQ测试须在30分钟内完成（33题）<br/><br/>
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">--><span id="face">00:00</span>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>	
		

		<div class="contentFrame" style="clear:both;text-align:center; ">
			<div class="ui-widget" style="text-align:center; color:#696a62; " id="ui-widget-result">

<form method="post" action="" class="c_form">

<? if($op == 'ican') { ?>
<table cellspacing="0" cellpadding="0" class="formtable" id="test_tr2">
<tr><td id="test_main" colspan="2"></td></tr>
</table>
<br/><br/>
<table id="btn_b" style="display:none;" width="100%" height="50px;"><tr>
<td align="left">
<a hiddenFocus="true" href="javascript:void(0)" style="display:none;" id="btn_up" class="btntest"> &laquo;上一题 </a></td>
<td align="right">
<a hiddenFocus="true" href="javascript:void(0)" id="btn_down" class="btntest"> 下一题&raquo; </a></td></tr></table>
<? } ?>


<? if($op == 'showscore') { ?>

			<table cellspacing="0" cellpadding="0" class="formtable">
			<caption>
			<h2>您本次智商测试得分为<?=$score['iq']?>分 ，用时 <?=$score['newusetime']?></h2>
			<p>恭喜！您的测试结果已经计算出来了。现在，可以根据提示，进一步使用其他功能...</p>
			</caption>

			</table>
			<div class="welcomeDiv1" style="">
				<img id="result_zhengshuPic" src="<?=$urlbase?>images/zhengshu_iq_1.png" align="left"/>
				<br/>
				<span id="div_result" style="padding-top:20px;color:#000;line-height:1.5em;font-size:14px;">
你共测试<?=$score['testCount']?> 次， 最高IQ值是<?=$score['iq']?> , 排名第 <b><?=$score['top']?></b>，打败了 <b><?=$score['win']?>%</b> 的人{<?=$score['lostname']?>
<? if($score['lostname']) { ?>（包括<?=$score['lostname']?>~_~）<? } ?>，并获得【<?=$score['chs']?>】证书（见右），好好学习，#天天向上#！！！
</span>
				<br/>
				<br/>专家点评：
				<span id="div_words" style="font-size:12px;font-weight:normal;color:#333;">					
					<?=$score['words']?>
					</span><br/> <br/> 
				<span style="font-size:12px;font-weight:normal;color:#999;">					
					0-89  -- 愚不可及 <br/>				
					90-99  -- 呆头呆脑<br/>				
					100-109 -- 波澜不兴<br/>				
					110-119 -- 聪明过人<br/>				
					120-129 -- 颖慧绝伦<br/>				
					130-139 -- 旷世奇才<br/>
					140-??? -- 文曲星转世
					</span><br/> <br/> <a href="javascript:void(0);"  onclick="sendmsg();"><img src="<?=$templatepath?>/iq_images/btn_tweet_blue.gif" /></a>
<br/><br/>
				<!--
				<a href="javascript:void(0);" onclick="follow();" title="谢谢你的关注，我们会定期在官网公告“聪明行情”！"><img src="<?=$templatepath?>/iq_images/btn_follow.gif" title="关注我"/></a> <div style="height:2px;"></div>
				<a href="?app=iq&op=stats"><img src="<?=$templatepath?>/iq_images/btn_stats_green.gif" alt="聪明排行榜" ></a> <div style="height:2px;"></div>
				<a href="?app=iq&op=ready"><img src="<?=$templatepath?>/iq_images/btn_testagain.gif" alt="再测试一次"/></a> <div style="height:2px;"></div>
-->
  接下来，您还可以：
  <ul class="test">
    <li><a href="#" target="_blank">看看你的好友的测试情况&raquo;</a></li>
    <li><a href="#" target="_blank">关注官方微博，“实时”了解最新的更新&raquo;</a></li>
	<li><a href="#" target="_blank">再测试一次&raquo;</a></li>
  </ul>


				</div>
				<div style="clear:both;"></div>


<? } ?>
</form>
</div></div>
</div>
<? include $this->gettpl('iq_footer');?>
