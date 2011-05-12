<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博IQ测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iq_images/iq.css" />	
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iqtest_images/iqtest.css" />

<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';</script>
<? if($op == 'ican') { ?>
<script type="text/javascript" src="<?=$templatepath?>/iqtest_images/iqtest.js?v=1.0"></script>
<? } ?>
<? if($op == 'showscore') { ?>
<script type="text/javascript" src="<?=$templatepath?>/iq_images/iq_min.js?v=1.0"></script>
<? } ?>
</head>
<body>

	<div class="mainFrame">

		<div class="ui-widget">			
			<div class="login">

<? if($op=="ready") { ?>
<? if(is_array($account) ) { ?>
			<img class="zhengshuico icoview" zsurl="<?=$iqScore['zsurl']?>" src="<?=$urlbase?>images/zhengshu_iq_ico_<?=$iqScore['iqlv']?>.png"/>
<font color="#ff3333"><?=$account['screen_name']?></font>, 你共测试<?=$iqScore['testCount']?> 次， 最高IQ值是 <?=$iqScore['iq']?> , 排名第<b> <?=$iqScore['top']?> </b>, 打败了 <b><?=$iqScore['win']?>%</b> 的人<? if($iqScore['lostname'] ) { ?>(包括<?=$iqScore['lostname']?>~_~）<? } ?>加油！<a href="javascript:void(0);" onclick="if(typeof sendmsg =='function')sendmsg();">记录到微博</a><!--<a href="?act=account&op=logout">退出</a>-->
<? } else { ?>
<a href="?act=account&op=tologin" border="0"><img height1="37" src="<?=$templatepath?>/images/sign-in-with-sina-32.png" alt="用微博帐号登录" /></a> 
<? } ?>
<? } else { ?>
				<font color="#ff3333"><?=$account['screen_name']?></font>, 微博IQ测试须在30分钟内完成（33题）<br/><br/>
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">--><span id="face">00:00</span>
<? } ?>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>	

		<div class="contentFrame" style="clear:both;text-align:center; ">
			<div class="ui-widget" style="text-align:center; color:#696a62; " id="ui-widget-result">
				<form method="post" action="" class="c_form">
<? if($op=="ready") { ?>
				<table cellspacing="0" cellpadding="0" class="formtable" id="test_tr1">
				<caption>
				<h2>IQ测试，智商测试</h2>
				<p>通过国内公认的专业IQ评测系统，您可以更加客观全面的了解自己和身边朋友的智商水平，这是beta版</p>
				</caption>
				<tr>
				<th style="width:12em;"><a href="?app=iq&act=iqtest&op=ican"><img src="<?=$templatepath?>/iq_images/btn_ready.gif" alt="我准备好了，开始" /></a></th>
				<td style="text-align:left;">
				IQ测试共计33道测试题，为了方便你操作，所有题型都设计成了单选题。为了保证测试的准确性，请你注意以下方面：
						<ul class="test">
							<li>1。保持最佳思维状态（根据本站统计，人在三分饥的时候思维状态最佳）</li>
							<li>2。身边没有阿猫阿狗吧？如果有，请给足粮食，让它安静点！</li>
							<li>3。如果你测试结果不理想，请不要气馁，可以过一段时间再来一次（测试间隔最好在一个月以上）！</li>
							<li>4。如果结果显示你是天才，请不要窃喜，因为前有爱因斯坦，后有更天才的天才！</li>
						</ul>
				</td>
				</tr>
				</table>

<? } elseif($op == 'ican') { ?>
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
			<h2>您本次智商测试得分为<?=$score['nowiq']?>分 ，用时 <?=$score['newusetime']?></h2>
			<p>恭喜！您的测试结果已经计算出来了。现在，可以根据提示，进一步使用其他功能...</p>
			</caption>

			</table>
			<div class="welcomeDiv1" style="">
				<img id="result_zhengshuPic" src="<?=$urlbase?>images/zhengshu_iq_1.png" align="left"/>
				<br/>
				<span id="div_result" style="padding-top:20px;color:#000;line-height:1.5em;font-size:14px;">
你共测试<?=$score['testCount']?> 次， 最高IQ值是<?=$score['iq']?> , 排名第 <b><?=$score['top']?></b>，打败了 <b><?=$score['win']?>%</b> 的人
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
