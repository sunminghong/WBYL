<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博IQ测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iq_images/iq.css" />	
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/daren_images/daren.css" />

<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';</script>

<? if($op == 'ican') { ?>
<script type="text/javascript" src="<?=$templatepath?>/iq_images/iq_min.js?v=1.0"></script>
<script type="text/javascript" src="<?=$templatepath?>/daren_images/daren.js?v=1.0"></script>
<style>
body{ background:url(<?=$templatepath?>/daren_images/pagebg.png) left top repeat;}
#test_main td {
	padding: 2px 10px 2px 10px; color:#111;
}
.answer-box {
	font-family: arial;
	width: 212px;
	height: 83px;
	padding: 20px;
	padding-right: 30px;
	font-size: 16px;text-align: center;
background: url('<?=$templatepath?>/daren_images/answer-box.png');

}
.answer-box-green {	font-family: arial;
	width: 212px;
	height: 83px;
	padding: 20px;
	padding-right: 30px;
	font-size: 16px;text-align: center;
	background: url('<?=$templatepath?>/daren_images/answer-box-correct.png');
}
.answer-box-red {
	background: url('<?=$templatepath?>/daren_images/answer-box-wrong.png');
}
.clickable {
cursor: hand;
cursor: pointer;
}
</style>
<? } ?>
<? if($op == 'showscore') { ?>
<script type="text/javascript" src="<?=$templatepath?>/iq_images/iq_min.js?v=1.01"></script>
<script><? if($op == 'showscore') { ?>usetime='0<?=$score['newusetime']?>' * 1; showtime(true);<? } ?></script>
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
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">-->
<? } ?>
			</div>
			
			<div class="logo">
				<a href="?app=daren" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>	
		<div class="ui-widget"><ul id="qmenu">
		<? foreach((array)$qtypelist as $kid => $ctype) {?>
			<li><a href="?app=daren&op=index&qtype=<?=$kid?>"><?=$ctype?></a></li>
		<?}?></ul>
		<div style="clear:both;font-size:0;height:0;"></div>
		</div>

<? if($op=="ready") { ?>
		<div class="contentFrame" style="clear:both;text-align:center; padding:15px;background:#FBFBFB;">
			<div class="ui-widget" style="text-align:center; color:#696a62; " id="ui-widget-result">
				<form method="post" action="" class="c_form">
				<table cellspacing="0" cellpadding="0" class="formtable" id="test_tr1">
				<caption>
				<h2>IQ测试，智商测试</h2>
				<p>通过国内公认的专业IQ评测系统，您可以更加客观全面的了解自己和身边朋友的智商水平，这是beta版</p>
				</caption>
				<tr>
				<th style="width:12em;"><a href="?app=daren&act=daren&op=ican"><img src="<?=$templatepath?>/iq_images/btn_ready.gif" alt="我准备好了，开始" /></a></th>
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
		</form>
		</div>
		</div>
<? } elseif($op == 'ican') { ?>
<form method="post" action="" class="c_form1">
<table cellspacing="0" cellpadding="0" class="formtable" id="test_tr2" style="margin-top:30px;border:5px solid #a8d0ea;background:#FBFBFB;">
<tr>
<td style="height1:550px;" valign="top" style="postion:relative; padding-top:30px; padding-bottome:20px;"><div id="test_main">
		<table style="margin: 0 auto;margin-top:30px;">
		<caption id="test_cap" style="text-align:left;font-size: 18px;line-height:25px;height:125px;color: #0071BC;font-family:'微软雅黑','黑体';margin:0px 15px 20px 0px;padding:0;">
		<span class="testlist" id="div_quest_index"></span></caption>
								<tbody><tr>
									<td><div id="answer1" class="clickable answer-box" style="opacity: 1; " onSelectStart="return false;"></div></td>
									<td><div id="answer2" class="answer-box clickable" style="opacity: 1; " onSelectStart="return false;"></div></td>
								</tr>
								<tr>
									<td><div id="answer3" class="answer-box clickable" style="opacity: 1; " onSelectStart="return false;"></div></td>
									<td><div id="answer4" class="answer-box clickable" style="opacity: 1; " onSelectStart="return false;"></div></td>
								</tr>
								<!--<tr>
									<td><div id="answer5" class="clickable answer-box" style="opacity: 1; ">E.以上都不是</div></td>
									<td><div class="clickable dont-know-button" style="text-align: center; border-top-color: rgb(194, 194, 194); border-right-color: rgb(194, 194, 194); border-bottom-color: rgb(194, 194, 194); border-left-color: rgb(194, 194, 194); color: rgb(194, 194, 194); "><img id="dont-know-choose-img" src="http://static.lingt.com/img/dont-know-button.png" style=""></div></td>
								</tr>-->
							</tbody></table>


</div></td>
<td style="width:190px;text-align:right;background:#FBFBFB url(<?=$templatepath?>/daren_images/border.png) left top repeat-y;" valign="middle">
	<div  style="font-size:24px;margin:20px 8px;line-height:30px;">总分<br/><span id="div_score">0</span></div>
	<div  style="font-size:24px;margin:20px 8px;line-height:30px;">总用时<div id="face">00:00</div></div>
	<div  style="font-size:24px;margin:30px 8px 5px 2px;line-height:30px;">本题倒计时</div>
	<div id="div__clock" style="float:right;background:url(<?=$templatepath?>/daren_images/clock_block.png?t=33ddd)  0 0 no-repeat; width:141px;height:141px;margin:5px 8px 5px 5px;"></div>
</td>
</tr>
</table>
</form>

<? } ?>

<? if($op == 'showscore') { ?>
		<form method="post" action="" class="c_form">
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

  接下来，您还可以：
  <ul class="test">
    <li><a href="#" target="_blank">看看你的好友的测试情况&raquo;</a></li>
    <li><a href="#" target="_blank">关注官方微博，“实时”了解最新的更新&raquo;</a></li>
	<li><a href="#" target="_blank">再测试一次&raquo;</a></li>
  </ul>


				</div>
				<div style="clear:both;"></div>
</form>
</div></div>
<? } ?>

</div>
<? include $this->gettpl('iq_footer');?>
