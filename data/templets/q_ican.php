<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博<? echo strtoupper($app);?>测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/q_images/main.css" />
<!--	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> -->
<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script>var app='<?=$app?>';var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';</script>
<script type="text/javascript" src="<?=$templatepath?>/q_images/main.js?v=1.6"></script>
<? if($op=="ready" || $op=="showscore") { ?>
<style>
ul.test{ margin: 10px 0; line-height: 21px;}
ul.test li{color: #666; list-style: inside disc;text-align:left;
font: 12px/1.5em Tahoma, Arial, Helvetica, snas-serif;
}
ul.test li a {color:#f00;font-size:12px;line-height:18px;}
</style>
<? } else { ?>
<? if($app=="eq") { ?>
	<style>
	#quiz_list {text-align:left;}
	#quiz_list ol {}
	#quiz_list li {margin:7px;color:#000;}
	#quiz_list input {margin:7px 7px; padding:1px;}
	#quiz_list label {margin-left:1px}

	</style>
<? } ?>
<? } ?>

</head>
<body>
<? include $this->gettpl('top');?>
	<div class="mainFrame">

<? if($op == 'showscore') { ?>		
		<div class="ui-widget">
			<div class="login">
<? if(is_array($account) ) { ?>
			<img class="zhengshuico icoview" zsurl="<?=$eqScore['zsurl']?>" src="<?=$urlbase?>images/zhengshu_eq_ico_<?=$eqScore['eqlv']?>.png"/>
<font color="#ff3333"><?=$account['screen_name']?></font> <img src="<?=$urlbase?>images/weiboicon16_<?=$account['lfrom']?>.png" height=16 /><? if($account['verified']==1) { ?><img src="<?=$urlbase?>images/vip_<?=$account['lfrom']?>.gif" title="认证用户" alt=""><? } ?>, 你共测试<?=$eqScore['testCount']?> 次， 最高<? echo strtoupper($app);?>值是 <?=$eqScore['eq']?> , 排名第<b> <?=$eqScore['top']?> </b>, 打败了 <b><?=$eqScore['win']?></b>人<? if($eqScore['lostname'] ) { ?>(包括<?=$eqScore['lostname']?>~_~）<? } ?>，加油！<a href="javascript:void(0);" onclick="if(typeof sendmsg =='function')sendmsg();">记录到微博</a><!--<a href="?act=account&op=logout">退出</a>-->
<? } else { ?>

	<strong>　　<? echo strtoupper($app);?>：</strong>就是常说的情商，与此对应的还有EQ、AQ、XQ...；
	<strong>微博<? echo strtoupper($app);?>测试：</strong>就是常说的情商测试。特别提醒，现在是<font color="#33ff33">beta</font>版，本测试准确度有一定的误差！
		<? } ?>
			</div>			
			<div class="logo">
				<a href="?app=eq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/eq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>

<div id="div_notice"></div>
<div id="ad_800_60" style="width:800px;height:60px;"></div>	
<div class="contentFrame" style="clear:both;text-align:center; ">

			<div><p>
					<strong>您本次情商测试得分为<?=$score['noweq']?>分 ，用时 <?=$score['newusetime']?></strong></p>
				</div>
			<div class="welcomeDiv1" style="">
				<img id="result_zhengshuPic" src="<?=$score['zsurl']?>" align="left"/>
				<br/>
				<span id="div_result" style="padding-top:20px;color:#000;line-height:1.5em;font-size:14px;">
你共测试<?=$score['testCount']?> 次， 最高<? echo strtoupper($app);?>值是<?=$score['eq']?> , 排名第 <b><?=$score['top']?></b>，打败了 <b><?=$score['win']?></b>人
<? if($score['lostname']) { ?>（包括<?=$score['lostname']?>~_~）<? } ?>，并获得【<?=$score['chs']?>】证书（见右）！！！
</span>
				<br/>
				<br/>专家点评：
				<span id="div_words" style="font-size:12px;font-weight:normal;color:#333;">					
					<?=$score['words']?>
					</span><br/> <br/> 
				<span style="font-size:12px;font-weight:normal;color:#999;">	<code>				
					0-89       -- 笨鸟先飞 <br/>				
					90-99     -- 左支右绌<br/>				
					100-109 -- 曲高和寡<br/>				
					110-119 -- 大道中庸<br/>
					120-129 -- 洞察秋毫<br/>
					130-139 -- 左右逢源<br/>
					140-?     -- 上善若水</code>
					</span><br/> <br/> <a href="javascript:void(0);"  onclick="sendmsg();"><img src="<?=$templatepath?>/q_images/btn_tweet_blue.gif" /></a> <div style="height:2px;"></div>
									<a href="javascript:void(0);" onclick="follow();" title="谢谢你的关注，我们会定期在官网公告“聪明行情”！"><img src="<?=$templatepath?>/q_images/btn_follow.gif" title="关注我"/></a>
<br/><br/>

  接下来，您还可以：
  <ul class="test">
    <li><a href="?app=eq&op=stats" target="_blank">看看你的好友的EQ情况&raquo;</a></li>
   <li><a href="?app=iq" target="_blank">进行智商（IQ）测试&raquo;</a></li>
    <li><a href="http://www.265g.com/?lfrom=5d13" target="_blank" title="265G是网页游戏第一门户！找网页游戏，就上265G!">如果你想玩游戏，可以上265g看看！&raquo;</a></li>
	<li><a href="?app=eq&op=ready">再测试一次&raquo;</a></li>
  </ul>
				</div>
				<div style="clear:both;"></div>

<? } ?>

<? if($op=="ican") { ?>
		
		<div class="ui-widget">
			<div class="login">
				<font color="#ff3333"><?=$account['screen_name']?></font>, 微博<? echo strtoupper($app);?>测试须在30分钟内完成（33题）<br/><br/>
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">--><span id="face"> <?=$score['newusetime']?></span>
			</div>			
			<div class="logo">
				<a href="?app=eq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/eq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>

<div id="div_notice"></div>
		<div class="contentFrame" style="clear:both;text-align:center; ">

			<div class="ui-widget" style="text-align:center; color:#696a62;" id="ui-widget-content">
			<p>
			<stong>提示：测试中，每一题请选择一个和自己最切合的答案，少选中性答案。</stong><br />
			<stong>友情提示：要本着对自己负责，对党、国家和人民负责的原则来测试，否则不准确！</stong></p>
<form name="quest" method="post" action="?app=eq&op=cacl" id="quest_form">
<input type="hidden" name="usetime" value="1" id="in_usetime"/>
<div id="quiz_list" style="margin-top:20px;">
<ol id="qlist">
<li id="q_1" class="q">我更喜欢住在：<br><label><input type="radio" name="s_16" value="1">嘈杂的市区</label><br><label><input type="radio" name="s_16" value="2">不太确定</label><br><label><input type="radio" name="s_16" value="3">僻静的郊区</label><br></li>
<li id="q_2" class="q">我坐在小房间里把门关上，但我仍觉得心里不安：<br><label><input type="radio" name="s_23" value="1">否</label><br><label><input type="radio" name="s_23" value="2">偶尔是</label><br><label><input type="radio" name="s_23" value="3">是</label><br></li>
<li id="q_3" class="q">在和人争辨或工作出现失误后，我常常：<br><label><input type="radio" name="s_14" value="1">继续有条不紊地工作</label><br><label><input type="radio" name="s_14" value="2">介于两者之间</label><br><label><input type="radio" name="s_14" value="3">感到震颤，精疲力竭，而不能继承安心工作</label><br></li>
<li id="q_4" class="q">我常用抛硬币、翻纸、抽签之类的游戏来猜测凶吉：<br><label><input type="radio" name="s_25" value="1">否</label><br><label><input type="radio" name="s_25" value="2">偶尔是</label><br><label><input type="radio" name="s_25" value="3">是</label><br></li>
<li id="q_5" class="q">工作中我愿意挑战艰巨的任务：<br><label><input type="radio" name="s_30" value="1">从不</label><br><label><input type="radio" name="s_30" value="2">几乎不</label><br><label><input type="radio" name="s_30" value="3">一半时间</label><br><label><input type="radio" name="s_30" value="4">大多数时间</label><br><label><input type="radio" name="s_30" value="5">总是</label><br></li>
<li id="q_6" class="q">在大街上，我常常避开我不愿打招呼的人：<br><label><input type="radio" name="s_5" value="1">从未如此</label><br><label><input type="radio" name="s_5" value="2">偶然如此</label><br><label><input type="radio" name="s_5" value="3">有时如此</label><br></li>
<li id="q_7" class="q">我会想到若干年后有什么使自己极为不安的事：<br><label><input type="radio" name="s_20" value="1">从来没有想过</label><br><label><input type="radio" name="s_20" value="2">偶尔想到过</label><br><label><input type="radio" name="s_20" value="3">经常想到</label><br></li>
<li id="q_8" class="q">睡梦中我常常被噩梦惊醒：<br><label><input type="radio" name="s_29" value="1">是</label><br><label><input type="radio" name="s_29" value="2">否</label><br></li>
<li id="q_9" class="q">我常发现别人好的意愿：<br><label><input type="radio" name="s_31" value="1">从不</label><br><label><input type="radio" name="s_31" value="2">几乎不</label><br><label><input type="radio" name="s_31" value="3">一半时间</label><br><label><input type="radio" name="s_31" value="4">大多数时间</label><br><label><input type="radio" name="s_31" value="5">总是</label><br></li>
<li id="q_10" class="q">我被朋友、同事起过绰号、讥讽过：<br><label><input type="radio" name="s_17" value="1">从来没有</label><br><label><input type="radio" name="s_17" value="2">偶尔有过</label><br><label><input type="radio" name="s_17" value="3">这是常有的事</label><br></li>
<li id="q_11" class="q">我从不因流言蜚语而气愤：<br><label><input type="radio" name="s_10" value="1">是的</label><br><label><input type="radio" name="s_10" value="2">介于两者之间</label><br><label><input type="radio" name="s_10" value="3">不是的</label><br></li>
<li id="q_12" class="q">我有能力克服各种困难：<br><label><input type="radio" name="s_1" value="1">是的</label><br><label><input type="radio" name="s_1" value="2">不一定</label><br><label><input type="radio" name="s_1" value="3">不是的</label><br></li>
<li id="q_13" class="q">不知为什么，有些人总是回避或冷淡我：<br><label><input type="radio" name="s_4" value="1">不是的</label><br><label><input type="radio" name="s_4" value="2">不一定</label><br><label><input type="radio" name="s_4" value="3">是的</label><br></li>
<li id="q_14" class="q">我常常被一些无谓的小事困扰：<br><label><input type="radio" name="s_15" value="1">没有</label><br><label><input type="radio" name="s_15" value="2">介于两者之间</label><br><label><input type="radio" name="s_15" value="3">是这样的</label><br></li>
<li id="q_15" class="q">在某种心境下我会因为困惑陷入空想将工作搁置下来:<br><label><input type="radio" name="s_27" value="1">是</label><br><label><input type="radio" name="s_27" value="2">否</label><br></li>
<li id="q_16" class="q">在就寝时，我常常：<br><label><input type="radio" name="s_12" value="1">极易入睡</label><br><label><input type="radio" name="s_12" value="2">介于两者之间</label><br><label><input type="radio" name="s_12" value="3">不易入睡</label><br></li>
<li id="q_17" class="q">我时常勉励自己，对未来充满希望：<br><label><input type="radio" name="s_33" value="1">从不</label><br><label><input type="radio" name="s_33" value="2">几乎不</label><br><label><input type="radio" name="s_33" value="3">一半时间</label><br><label><input type="radio" name="s_33" value="4">大多数时间</label><br><label><input type="radio" name="s_33" value="5">总是</label><br></li>
<li id="q_18" class="q">我不论到什么地方，都能清晰地辨别方向：<br><label><input type="radio" name="s_7" value="1">是的</label><br><label><input type="radio" name="s_7" value="2">不一定</label><br><label><input type="radio" name="s_7" value="3">不是的</label><br></li>
<li id="q_19" class="q">除去看见的世界外，我的心中：<br><label><input type="radio" name="s_19" value="1">没有另外的世界</label><br><label><input type="radio" name="s_19" value="2">记不清</label><br><label><input type="radio" name="s_19" value="3">还有另外的世界</label><br></li>
<li id="q_20" class="q">我常常觉得自己的家庭对自己不好，但是我又确切地认识他们的确对我好：<br><label><input type="radio" name="s_21" value="1">没这样想</label><br><label><input type="radio" name="s_21" value="2">说不清楚</label><br><label><input type="radio" name="s_21" value="3">是这样想的</label><br></li>
<li id="q_21" class="q">有人侵扰我时，我：<br><label><input type="radio" name="s_13" value="1">不露声色</label><br><label><input type="radio" name="s_13" value="2">介于两者之间</label><br><label><input type="radio" name="s_13" value="3">大声抗议，以泄己愤</label><br></li>
<li id="q_22" class="q">气候的变化：<br><label><input type="radio" name="s_9" value="1">不会影响我的情绪</label><br><label><input type="radio" name="s_9" value="2">介于两者之间</label><br><label><input type="radio" name="s_9" value="3">会影响我的情绪</label><br></li>
<li id="q_23" class="q">我能听取不同的意见，包括对自己的批评：<br><label><input type="radio" name="s_32" value="1">从不</label><br><label><input type="radio" name="s_32" value="2">几乎不</label><br><label><input type="radio" name="s_32" value="3">一半时间</label><br><label><input type="radio" name="s_32" value="4">大多数时间</label><br><label><input type="radio" name="s_32" value="5">总是</label><br></li>
<li id="q_24" class="q">我善于控制自己的面部表情：<br><label><input type="radio" name="s_11" value="1">是的</label><br><label><input type="radio" name="s_11" value="2">不太确定</label><br><label><input type="radio" name="s_11" value="3">不是的</label><br></li>
<li id="q_25" class="q">如果我能到一个新的环境，我要把生活安排得：<br><label><input type="radio" name="s_2" value="1">和从前相仿</label><br><label><input type="radio" name="s_2" value="2">不一定</label><br><label><input type="radio" name="s_2" value="3">和从前不一样</label><br></li>
<li id="q_26" class="q">当一件事需要我作决定时，我常觉得很难：<br><label><input type="radio" name="s_24" value="1">否</label><br><label><input type="radio" name="s_24" value="2">偶尔是</label><br><label><input type="radio" name="s_24" value="3">是</label><br></li>
<li id="q_27" class="q">我的神经脆弱，稍有刺激就会使我战栗:<br><label><input type="radio" name="s_28" value="1">是</label><br><label><input type="radio" name="s_28" value="2">否</label><br></li>
<li id="q_28" class="q">当我集中精力工作时，假使有人在旁边高谈阔论：<br><label><input type="radio" name="s_6" value="1">我仍能用心工作</label><br><label><input type="radio" name="s_6" value="2">介于两者之间</label><br><label><input type="radio" name="s_6" value="3">我不能专心且感到愤怒</label><br></li>
<li id="q_29" class="q">为了工作我早出晚归，早晨起床我常常感到疲劳不堪：<br><label><input type="radio" name="s_26" value="1">是</label><br><label><input type="radio" name="s_26" value="2">否</label><br></li>
<li id="q_30" class="q">有一种食物使我吃后呕吐：<br><label><input type="radio" name="s_18" value="1">没有</label><br><label><input type="radio" name="s_18" value="2">记不清</label><br><label><input type="radio" name="s_18" value="3">有</label><br></li>
<li id="q_31" class="q">天天我一回家就马上把门关上：<br><label><input type="radio" name="s_22" value="1">否</label><br><label><input type="radio" name="s_22" value="2">不清楚</label><br><label><input type="radio" name="s_22" value="3">是</label><br></li>
<li id="q_32" class="q">一生中，我觉得自已能达到我所预想的目标：<br><label><input type="radio" name="s_3" value="1">是的</label><br><label><input type="radio" name="s_3" value="2">不一定</label><br><label><input type="radio" name="s_3" value="3">不是的</label><br></li>
<li id="q_33" class="q">我热爱所学的专业和所从事的工作：<br><label><input type="radio" name="s_8" value="1">是的</label><br><label><input type="radio" name="s_8" value="2">不一定</label><br><label><input type="radio" name="s_8" value="3">不是的</label><br></li>
<!--<li id="q_34" class="q">你现在的年龄是：<input type="text" name="bornyear" value="" style="width:3em;" maxlength=2/>岁</li>-->
</ol></div>
<div style="clear:both"></div>
<div id="quest_append" style="margin:30px 0 0 30px;"></div>
	<div class="field" style="margin:20px;padding-left:20px;">
			<p>
							<a href="#" onclick="document.getElementById('quest_form').submit(); return false;" ><img src="<?=$templatepath?>/q_images/btn_showscore_<?=$app?>.gif" alt="查看我的分数"/></a></p>
	</div>
</form>

			</div>

<? } ?>
</div>
<? include $this->gettpl('iq_footer');?>