<? if(!defined('ROOT')) exit('Access Denied');?>

<div id="mask"></div>
<div id="wrap2">
	<div style="width:676px;margin:auto;background-color:#a59d71;overflow:hidden; ">
		<div class="wrap2header"></div>
		<div class="div_list">
			<? foreach((array)$testlist as $test) {?>
			<div class="msg_list">
				<i><?=$test['testtime']?></i><br/><a href="?op=profile&uid=<?=$test['uid']?>" target="_blank">@<?=$test['name']?></a>，刚刚用<b><?=$test['usetime']?>秒</b>完成了<?=$test['qtypename']?>知识问答，最后得分<b><?=$test['score']?>分</b>。
			</div>
			<? } ?>
		</div>
		<div class="div_list">
			<? foreach((array)$todaytoplist as $top) {?>
				<div class="top_list">
					<div class="top_list_text"><a href="?op=profile&uid=<?=$test['uid']?>" target="_blank">@<?=$top['name']?></a><!--<img src="<?=$urlbase?>images/weiboicon16_<?=$top['lfrom']?>.png" height=16 />--><? if($top['verified']==1 && 1==0) { ?><img src="<?=$urlbase?>images/vip_<?=$top['lfrom']?>.gif" title="认证用户" alt=""><? } ?><br/>今日总分数<b><?=$top['score']?></b>分，总用时<b><?=$top['usetime']?></b>秒</div>
					<a href="?op=profile&uid=<?=$test['uid']?>" target="_blank"><img class="avatar" src="<?=$top['avatar']?>"/></a>
				</div>
			<? } ?>
		</div>
		<div class="div_list">
			<p><a href="javascript:void(0);" onclick="_follow();">关注@米奇吧(官方微博)</a></p>
			<p><a href="<?=$orgwbsite?>">浏览@米奇吧(官方微博)</a></p>
			<p><b> 其他应用推荐</b></p>
			<p>&gt; <a href="http://q.5d13.cn/iq">IQ测试</a></p>
			<p>&gt; <a href="http://q.5d13.cn/eq">EQ测试</a></p>

		</div>
		<div class="wrap2bottom"></div>
	</div>
</div>

<div id="copyright">(c)Copyright by <a href="http://www.miqiba.com" target="_blank">米奇吧</a></div>
<!--
<div id="div_login">
	<? global $canLogin;?>
	<? foreach((array)$canLogin as $login) {?>
		<a href="?app=home&act=account&op=tologin&lfrom=<?=$login?>&fromurl=<? echo urlencode(URLBASE.'?op=ican');?>" border="0"><img height="32" src="images/btn_login_<?=$login?>_32.png" alt="用微博帐号登录" /></a> 
	<? } ?>
</div>
-->

<div id="div_help" class="pngfix"><div class="content">
	<!--<p>#你太有才了#就是通过回答问题、赚取#智慧币#的过程来帮助你“积累知识”。#你太有才了#将有多种答题模式，目前开放的有“每日十问”。</p>-->
		<p><b>“每日十问”规则：</b><br/>每天每个类别都有十道问题，可以重复多次答题，各类当天成绩以当天最好成绩为准；在同一天内，同一类别的题目是不变的；每天凌晨00:00:00统一更换各类别题目。</p>			
		<p><b>智慧币获取规则：</b></p>
		<ul>
		<li>每完成一次问答得1枚智慧币</li>
		<li>得分超过120分可以得到当天的达人证书及<b>30枚</b>智慧币（每类每天最多只能得到一次大人证书）</li>
		<li>每天凌晨换题目时评出前天各类别的第一名，颁发一枚#牛人勋章#及奖励<b>50枚</b>智慧币</li>
		<li>收集8枚不同类别#达人证书#可以领取一枚博士勋章及证书并奖励<b>180枚</b>智慧币</li>
		<li>收集10枚不同类别牛人证书可以领取一次文曲星勋章并奖励500积分</li>
		</ul>
		<p><b>赚取智慧币攻略：</b><br/>“每日十问”模式下当天每类题目是不变的，所以“勤能补拙”————活用百度、谷歌，多做几遍，直至取得#达人证书#赚取30*n枚智慧币；如果幸运还可能取得一枚或n枚牛人勋章，又可以多赚50*n枚智慧币！</p>
		</div>
		<div id="btn_help_close"></div>
</div>

<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fd41b22314bd46c30150e50c4c78bc128' type='text/javascript'%3E%3C/script%3E"));
</script>


		<div id="div_tips" class="small_Yellow" style="display:none;">
			<table class="CP_w"><thead><tr><th class="tLeft"><span></span></th><th class="tMid"><span></span></th><th class="tRight"><span></span></th></tr></thead><tbody><tr><td class="tLeft"><span></span></td><td class="tMid">
			<div class="yInfo" id="div_tips_info">
			
			</div></td>
			<td class="tRight"><span></span></td></tr></tbody><tfoot><tr><td class="tLeft"><span></span></td><td class="tMid"><span></span></td><td class="tRight"><span></span></td></tr></tfoot>
			</table>
			<div class="close"><a href="javascript:void(0)" id="div_help_btn_close">&nbsp;</a></div>
		</div>

</body>
</html>