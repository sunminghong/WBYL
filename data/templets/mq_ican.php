<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-mquiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博<? echo strtoupper($app);?>测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/q_images/main.css?v=1.63" />
<!--	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
<script>var app='<?=$app?>';var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';</script>
<script type="text/javascript" src="<?=$templatepath?>/mq_images/main.js?v=1.63"></script>
<? if($op=="ready" || $op=="showscore") { ?>
<style>
ul.test{ margin: 10px 0; line-height: 21px;}
ul.test li{color: #666; list-style: inside disc;text-align:left;
font: 12px/1.5em Tahoma, Arial, Helvetica, snas-serif;
}
ul.test li a {color:#f00;font-size:12px;line-height:18px;}
</style>
<? } else { ?>
<? if($app=="mq") { ?>
	<style>

ol.questions {margin:5px 0px 5px 20px;text-align:left;width:600px;}

ol.questions li {padding-left:1em;padding:5px;border:1px solid #eee;margin:5px;}
ul.choices li {list-style-type:none; border:0px;font-size:12px;}

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
			<img class="zhengshuico icoview" zsurl="<?=$mqScore['zsurl']?>" src="<?=$urlbase?>images/zhengshu_mq_ico_<?=$mqScore['mqlv']?>.png"/>
<font color="#ff3333"><?=$account['screen_name']?></font> <img src="<?=$urlbase?>images/weiboicon16_<?=$account['lfrom']?>.png" height=16 /><? if($account['verified']==1) { ?><img src="<?=$urlbase?>images/vip_<?=$account['lfrom']?>.gif" title="认证用户" alt=""><? } ?>, 你共测试<?=$mqScore['testCount']?> 次， 最高<? echo strtoupper($app);?>值是 <?=$mqScore['mq']?> , 排名第<b> <?=$mqScore['top']?> </b>, 打败了 <b><?=$mqScore['win']?></b>人<? if($mqScore['lostname'] ) { ?>(包括<?=$mqScore['lostname']?>~_~）<? } ?>，加油！<a href="javascript:void(0);" onclick="if(typeof sendmsg =='function')sendmsg();">记录到微博</a>　<a href="?app=home&act=account&op=logout">切换帐号</a>
<? } ?><br/><br/>

<? if(($app=='mq')) { ?>
<a href="?app=mq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_mq.gif" alt="我来测试下MQ"/></a>
<a href="?app=iq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_iq.gif" alt="我来测试下IQ"/></a>
<? } else { ?>
<a href="?app=iq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_iq.gif" alt="我来测试下IQ"/></a>
<a href="?app=mq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_mq.gif" alt="我来测试下MQ"/></a>
<? } ?>
	 <a href="?app=mq&op=stats"><img src="<?=$templatepath?>/q_images/btn_stats.gif" alt="聪明排行榜" ></a>

			</div>			
			<div class="logo" style="position:relative;">
				<a href="?app=mq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/logo_q.gif" alt="看看你有多聪明LOGO"/></a>

				<? if($lfrom=='tqq') { ?>
				<a href="http://t.qq.com/yihuiso" target="_blank" style="display:block;position:absolute;left:94px;top:50px;"><img src="<?=$templatepath?>/q_images/btn_5d13site_logo.gif" alt="我的一生官方微博孙铭鸿"/></a>
				<? } else { ?>
				<a href="http://weibo.com/5d13" target="_blank" style="display:block;position:absolute;left:94px;top:50px;"><img src="<?=$templatepath?>/q_images/btn_5d13site_logo.gif" alt="我的一生官方微博孙铭鸿"/></a>
				<? } ?>
			</div>
		</div>

<div id="div_notice" style="clear:both;"></div>
<div class="contentFrame" style="clear:both;text-align:center; ">

			<div><p>
					<strong>您本次德商测试得分为<?=$score['nowmq']?>分 ，用时 <?=$score['newusetime']?></strong></p>
				</div>
			<div class="welcomeDiv1" style="">
				<img id="result_zhengshuPic" src="<?=$score['zsurl']?>" align="left" style="width:350px;margin-right:15px;"/>
				<br/>
				<span id="div_result" style="padding-top:20px;color:#000;line-height:1.5em;font-size:14px;">
你共测试<?=$score['testCount']?> 次， 最高<? echo strtoupper($app);?>值是<?=$score['mq']?> , 排名第 <b><?=$score['top']?></b>，打败了 <b><?=$score['win']?></b>人
<? if($score['lostname']) { ?>（包括<?=$score['lostname']?>~_~）<? } ?>，并获得【<?=$score['chs']?>】证书（见右）！！！
</span>
				<br/>
				<br/>专家点评：
				<span id="div_words" style="font-size:12px;font-weight:normal;color:#333;">					
					<?=$score['words']?>
					</span><br/> <br/> 
				<span style="font-size:12px;font-weight:normal;color:#999;">	<code>				
					　　38~50分：你的MQ优秀，只需在保持现有道德品质的基础上，多交一些高德商的朋友，以求与他们互补；<br/>
　　19~37分：你的MQ一般，需要有针对性地提高自己的德商，使你的品格更进一步；<br/>
　　19分以下：你的MQ已经严重影响到你的工作和生活，建议立即去看心理医生，或者考虑对现状来一次根本性的改变。 </code>
					</span><br/> <br/> <a href="javascript:void(0);"  onclick="sendmsg();"><img src="<?=$templatepath?>/q_images/btn_tweet_blue.gif" /></a> <div style="height:2px;"></div>
									<a href="javascript:void(0);" onclick="follow(true);" title="谢谢你的关注，我们会定期在官网公告“聪明行情”！"><img src="<?=$templatepath?>/q_images/btn_follow.gif" title="关注我"/></a>
<br/><br/>

  接下来，您还可以：
  <ul class="test">
    <li><a href="?app=mq&op=stats" target="_blank">看看你的好友的MQ情况&raquo;</a></li>
   <li><a href="?app=iq" target="_blank" style="font-size:140%;color:#00f;">进行智商（IQ）测试&raquo;</a></li>
    <li><a href="http://www.265g.com/?lfrom=5d13" target="_blank" title="265G是网页游戏第一门户！找网页游戏，就上265G!">如果你想玩游戏，可以上265g看看！&raquo;</a></li>
	<li><a href="?app=mq&op=ready">再测试一次&raquo;</a></li>
  </ul>
				</div>
				<div style="clear:both;"></div>

<? } ?>

<? if($op=="ican") { ?>
		
		<div class="ui-widget">
			<div class="login">
				<font color="#ff3333"><?=$account['screen_name']?></font>, 微博<? echo strtoupper($app);?>测试须在5分钟内完成（10题）<br/><br/>
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">--><span id="face"> <?=$score['newusetime']?></span>
			</div>			

			<div class="logo" style="position:relative;">
				<a href="?app=mq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/logo_q.gif" alt="看看你有多聪明LOGO"/></a>
				<a href="<?=$orgwbsite?>" target="_blank" style="display:block;position:absolute;left:94px;top:50px;"><img src="<?=$templatepath?>/q_images/btn_5d13site_logo.gif" alt="我的一生官方微博孙铭鸿"/></a>
			</div>
		</div>

<div id="div_notice"></div>
		<div class="contentFrame" style="clear:both;text-align:center; ">

			<div class="ui-widget" style="text-align:center; color:#696a62;" id="ui-widget-content">
			<p>
			<stong>提示：测试中，每一题请选择一个和自己最切合的答案，少选中性答案。</stong><br />
			<stong>友情提示：要本着对自己负责，对党、国家和人民负责的原则来测试，否则不准确！</stong></p>
<form name="quest" method="post" action="?app=mq&op=cacl" id="quest_form" onsubmit="return calculate();">
<input type="hidden" name="usetime" value="1" id="in_usetime"/>
<ol class="questions">
	<li class="question">
	当看到朋友受伤害时，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_1" value="1" type="radio">理解朋友的痛苦并去安慰他们</label></li>
		<li class="choice">
		<label>
		<input name="s_1" value="2" type="radio">陪着朋友流泪或者心烦意乱</label></li>
		<li class="choice">
		<label>
		<input name="s_1" value="3" type="radio">表现出无动于衷的态度</label></li>
		<li class="choice">
		<label>
		<input name="s_1" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	面对别人的需要和感情表达时，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_2" value="1" type="radio">正确解读别人的非言语暗示（如手势、面部表情和语调等）</label></li>
		<li class="choice">
		<label>
		<input name="s_2" value="2" type="radio">很会留意观察别人的面部表情，并给予恰当的反应</label></li>
		<li class="choice">
		<label>
		<input name="s_2" value="3" type="radio">无法共享别人的感情表达</label></li>
		<li class="choice">
		<label>
		<input name="s_2" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	当看见有人作弊或者以强欺弱时，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_3" value="1" type="radio">不惧威胁，帮助弱者，告发不正当行为</label></li>
		<li class="choice">
		<label>
		<input name="s_3" value="2" type="radio">知道应该怎样正确行事，但不会多管闲事</label></li>
		<li class="choice">
		<label>
		<input name="s_3" value="3" type="radio">内心虽有波动，但仍然无动于衷</label></li>
		<li class="choice">
		<label>
		<input name="s_3" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	每当做错事后，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_4" value="1" type="radio">对自己的错误或不妥当的行为感到愧疚</label></li>
		<li class="choice">
		<label>
		<input name="s_4" value="2" type="radio">承认错误，勇敢说声“对不起”</label></li>
		<li class="choice">
		<label>
		<input name="s_4" value="3" type="radio">想方设法狡辩或者掩饰错误</label></li>
		<li class="choice">
		<label>
		<input name="s_4" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	渴望去做某件事而未被允许，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_5" value="1" type="radio">管住自己的冲动和欲望</label></li>
		<li class="choice">
		<label>
		<input name="s_5" value="2" type="radio">考虑后果，忍耐一下，克服行为上的冲动</label></li>
		<li class="choice">
		<label>
		<input name="s_5" value="3" type="radio">固执行事，不达目的不罢休，或者阳奉阴违</label></li>
		<li class="choice">
		<label>
		<input name="s_5" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	在工作时，某人有急事要约你出去，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_6" value="1" type="radio">婉言拒绝或者答复人家忙完之后再与其联络</label></li>
		<li class="choice">
		<label>
		<input name="s_6" value="2" type="radio">会出去，但心里总惦记着手头的事情</label></li>
		<li class="choice">
		<label>
		<input name="s_6" value="3" type="radio">无论忙闲，坚决调班，且不计后果</label></li>
		<li class="choice">
		<label>
		<input name="s_6" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	每当遇到老年人或残疾人时，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_7" value="1" type="radio">不自觉地主动过去帮助他们</label></li>
		<li class="choice">
		<label>
		<input name="s_7" value="2" type="radio">言语和行为上对他们保持尊重</label></li>
		<li class="choice">
		<label>
		<input name="s_7" value="3" type="radio">远离、回避或从心里厌恶他们</label></li>
		<li class="choice">
		<label>
		<input name="s_7" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	当有同事遭到捉弄或者冷遇时，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_8" value="1" type="radio">不计回报地阻止别人的恶意行为</label></li>
		<li class="choice">
		<label>
		<input name="s_8" value="2" type="radio">拒绝参与侮辱和嘲笑的行为</label></li>
		<li class="choice">
		<label>
		<input name="s_8" value="3" type="radio">感情上麻木不仁，甚至有点幸灾乐祸</label></li>
		<li class="choice">
		<label>
		<input name="s_8" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	当遇到长相难看或者举止怪异的人，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_9" value="1" type="radio">对他们表现出宽容、友好和坦诚</label></li>
		<li class="choice">
		<label>
		<input name="s_9" value="2" type="radio">不随便对他们评判、分类或抱有成见</label></li>
		<li class="choice">
		<label>
		<input name="s_9" value="3" type="radio">嘲笑他们的缺点和差异，甚至参与对其谩骂</label></li>
		<li class="choice">
		<label>
		<input name="s_9" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
	<li class="question">
	针对某件事，同事发表意见或看法时，你会：<ul class="choices">
		<li class="choice">
		<label>
		<input name="s_10" value="1" type="radio">在提出自己的看法之前，认真倾听对方的意见</label></li>
		<li class="choice">
		<label>
		<input name="s_10" value="2" type="radio">客观地评价对方的观点</label></li>
		<li class="choice">
		<label>
		<input name="s_10" value="3" type="radio">经常打断同事的话，自说自话</label></li>
		<li class="choice">
		<label>
		<input name="s_10" value="4" type="radio">不知道</label></li>
	</ul>
	</li>
</ol>

	<div class="field" style="margin:20px;padding-left:20px;">
			<p>
							<a href="#" onclick="javascript:calculate();" >查看我的分数<!--<img src="<?=$templatepath?>/q_images/btn_showscore_<?=$app?>.gif" alt="查看我的分数"/>--></a></p>
	</div>
</form>

			</div>

<? } ?>
</div>
<? include $this->gettpl('iq_footer');?>