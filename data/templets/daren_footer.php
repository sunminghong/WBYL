<? if(!defined('ROOT')) exit('Access Denied');?>
<? if($op!="top") { ?>
<div id="mask"></div>

<? if($op!="profile") { ?>
<div id="wrap3">
	<div class="div_niuren pngfix">	
	<div style="width:103px;float:left;">&nbsp;</div>
	<? foreach((array)$yesterdayniurenlist as $top) {?>
		<div class="top_list pngfix">
				<? echo $qtypenamelist[$top["qtype"]][0];?><br/>
				<a href="?op=profile&uid=<?=$top['uid']?>" target="_blank"><img class="avatar" src="<?=$top['avatar']?>"/></a>				
		<a href="?op=profile&uid=<?=$top['uid']?>" target="_blank">@<?=$top['name']?>第三方的</a><!--<img src="<?=$urlbase?>images/weiboicon16_<?=$top['lfrom']?>.png" height=16 />--><? if($top['verified']==1 && 1==0) { ?><img src="<?=$urlbase?>images/vip_<?=$top['lfrom']?>.gif" title="认证用户" alt=""><? } ?>
			
		</div>
	<? } ?>

	</div>
</div>
<? } ?>

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
			<div class="top_list"><a href="?op=top" style="font-size:14.3px;" class="link_more" alt="更多">更多»</a></div>
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
<div id="text_content_help" style="display:none;">
		<p><b>“每日十问”规则：</b><br/>每天每个类别都有十道问题，可以重复多次答题，各类当天成绩以当天最好成绩为准；在同一天内，同一类别的题目是不变的；每天凌晨00:00:00统一更换各类别题目。</p>			

		<ul><b>智慧币获取规则：</b>
		<li><a href="javascript:void(0);" onclick="_follow();" style="color:#f00;">关注米奇吧，立即获取<b>100枚</b>智慧币！</a></li>
		<li>每天每类第一次发送成绩单到微博，奖励<b>2枚</b>智慧币。</li>
		<li>每完成一次回答，将会根据分数不同分别获得<b>1—5枚</b>智慧币，每天每类答题获得智慧币最多不超过<b>10枚</b>。</li>
		<li>当单次得分超过<b><? echo FULLMINUTE;?>分</b>，则可获得达人奖励，奖励 1 枚该类【达人勋章】及<b>30枚</b>智慧币（每天每类别最多可获得<b>1次</b>达人奖励）。</li>
		<li>每天凌晨将评出前一天的各类别的冠军，冠军将获得牛人奖励，奖励 1 枚该类【牛人勋章】及<b>50枚</b>智慧币（若同时为多个种类的冠军，奖励累加）</li>
		<li>收集50枚【达人勋章】可领取 1 枚【博士勋章】及168枚智慧币。</li>
		<li>收集5枚【博士勋章】和5枚【牛人勋章】（类别不限）可领取一枚【文曲星勋章】及1176枚智慧币。</li>
		</ul>
		<p><b>赚取智慧币攻略：</b><br/>“每日十问”模式下当天每类题目是不变的，所以“勤能补拙”————活用百度、谷歌，多做几遍，直至获得#达人证书#赚取30*n枚智慧币；如果幸运还可能获得一枚或n枚牛人勋章，又可以多赚50*n枚智慧币！</p>
</div>

<? if(!$isret) { ?>

<? if(intval($score['lastwincount']) >= 50) { ?>
<div id="text_content_notifyniuren" style="display:none;">
	<p>亲爱的 @<?=$score['name']?>：</p>
	<p>　　你的勤奋、博学我们一直在关注。恭喜！你收集到了<b>50枚</b>【达人勋章】，现在可以领取一次【博士奖励】（包括一枚【博士勋章】、【博士证书】及<b>168枚</b>#智慧币#）！</p>
	<div style="position:relative;width:424px;height:260px;margin-top:30px;">
		<img style="position:absolute;width:220px;top:0px;left:0px;" src="" />			
		<div style="position:absolute;width:220px;top:0px;left:230px;font-size:14px;font-weight:bold;">写下你的获奖感言：</div>
		<textarea class="textarea_msg" style="position:absolute;width:200px;height:122px;top:20px;left:230px;">好好学习，天天向上！几天的坚持、努力才使我领取了一枚【博士勋章】和【博士证书】，并获得了168枚#智慧币#。#我太有才了#！</textarea>
		<div style="position:absolute;width:400px;top:175px;left:80px;"><input type="checkbox" value="1" class="chb_sendmsg">发布到微博炫耀一下</div>
		<div style="position:absolute;width:400px;top:200px;left:80px;" >
			<a href="javascript:void(0);" class="btn_big btn_red_big" onclick="getboshizhengshu($('#div_xinzhi .textarea_msg').val(),$('#div_xinzhi .chb_sendmsg').attr('checked'));">领取奖励</a>
				
			</div>
		</div>
</div>
<? } elseif($op=='index' && intval($score['lastwincount']) >= 40) { ?>
<div id="text_content_notifyniuren" style="display:none;">
	<p>亲爱的 @<?=$score['name']?>：</p>
	<p>　　你的勤奋、博学我们一直在关注。每收集<b>50枚</b>【达人勋章】就可以领取一次【博士奖励】（包括一枚【博士勋章】、【博士证书】及<b>168枚</b>#智慧币#），你现在只差<b><? echo 50-$score['lastwincount'];?>枚</b>就可以领取一次了！</p>
	<div style="position:relative;width:424px;height:260px;margin-top:30px;">
		<img style="position:absolute;width:220px;top:0px;left:100px;" src="" />			

		
		<div style="position:absolute;width:400px;top:200px;left:120px;" >
			<a href="javascript:void(0);" class="btn_big btn_red_big" onclick="$('#div_xinzhi').hide();">我继续努力</a>
				
			</div>
		</div>	
</div>
<? } ?>
<? } ?>


<div id="div_xinzhi" class="xinzhi pngfix">
	<div class="content">
	</div>
	<div id="btn_xinzhi_close" class="btn_close"></div>
</div>
<div style="display:none;">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fd41b22314bd46c30150e50c4c78bc128' type='text/javascript'%3E%3C/script%3E"));
</script>
</div>

</body>
</html>