<? if(!defined('ROOT')) exit('Access Denied');?>
<? if($op!="top") { ?>
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
					<div class="top_list_text"><a href="?op=profile&uid=<?=$top['uid']?>" target="_blank">@<?=$top['name']?></a><!--<img src="<?=$urlbase?>images/weiboicon16_<?=$top['lfrom']?>.png" height=16 />--><? if($top['verified']==1 && 1==0) { ?><img src="<?=$urlbase?>images/vip_<?=$top['lfrom']?>.gif" title="认证用户" alt=""><? } ?><br/>今日总分数<b><?=$top['score']?></b>分，总用时<b><?=$top['usetime']?></b>秒</div>
					<a href="?op=profile&uid=<?=$top['uid']?>" target="_blank"><img class="avatar" src="<?=$top['avatar']?>"/></a>
				</div>
			<? } ?>
		</div>
		<div class="div_list">
		<? if($op=='index') { ?>
			<p>总测试次数：<b><?=$allcount['alltestcount']?>次</b></p>
			<p>总使用人数：<b><?=$allcount['allusercount']?>人</b></p>
			<p>统计时间：<b><? echo date("m-d H:i:s");?></b></p>
		<? } ?>
			<p><a href="javascript:void(0);" onclick="_follow();">关注@米奇吧(官方微博)</a></p>
			<p><a href="<?=$orgwbsite?>">浏览@米奇吧(官方微博)</a></p>
			<p><b> 其他应用推荐</b></p>
			<p>&gt; <a href="http://q.5d13.cn/iq">IQ测试</a></p>
			<p>&gt; <a href="http://q.5d13.cn/eq">EQ测试</a></p>

		</div>
		<div class="wrap2bottom"></div>
	</div>
</div>
<? } ?>

<div id="copyright">(c)Copyright by <a href="http://www.miqiba.com" target="_blank">米奇吧</a></div>

<div id="div_help" class="pngfix"><div class="content">
	<!--<p>#你太有才了#就是通过回答问题、赚取#智慧币#的过程来帮助你“积累知识”。#你太有才了#将有多种答题模式，目前开放的有“每日十问”。</p>-->
		<p><b>“每日十问”规则：</b><br/>每天每个类别都有十道问题，可以重复多次答题，各类当天成绩以当天最好成绩为准；在同一天内，同一类别的题目是不变的；每天凌晨00:00:00统一更换各类别题目。</p>			

		<ul><b>智慧币获取规则：</b>
		<li><a href="javascript:void(0);" onclick="_follow();" style="color:#f00;">关注米奇吧，立即获取<b>10枚</b>智慧币！</a></li>
		<li>每天每类第一次发送成绩单到微博，奖励<b>2枚</b>智慧币。</li>
		<li>每完成一次回答，将会根据分数不同分别获得<b>1—5枚</b>智慧币，每天每类答题获得智慧币最多不超过<b>10枚</b>。</li>
		<li>当单次得分超过<b><? echo FULLMINUTE;?>分</b>，则可获得达人奖励，奖励 1 枚该类【达人勋章】及<b>30枚</b>智慧币（每天每类别最多可获得<b>1次</b>达人奖励）。</li>
		<li>每天凌晨将评出前一天的各类别的冠军，冠军将获得牛人奖励，奖励 1 枚该类【牛人勋章】及<b>50枚</b>智慧币（若同时为多个种类的冠军，奖励累加）</li>
		<li>收集50枚【达人勋章】可领取 1 枚【博士勋章】及168枚智慧币。</li>
		<li>收集5枚【博士勋章】和5枚【牛人勋章】（类别不限）可领取一枚【文曲星勋章】及1176枚智慧币。</li>

		</ul>
		<p><b>赚取智慧币攻略：</b><br/>“每日十问”模式下当天每类题目是不变的，所以“勤能补拙”————活用百度、谷歌，多做几遍，直至获得#达人证书#赚取30*n枚智慧币；如果幸运还可能获得一枚或n枚牛人勋章，又可以多赚50*n枚智慧币！</p>
		</div>
		<div id="btn_help_close"></div>
</div>
<div style="display:none;">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fd41b22314bd46c30150e50c4c78bc128' type='text/javascript'%3E%3C/script%3E"));
</script>
</div>


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