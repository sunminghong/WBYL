<? if(!defined('ROOT')) exit('Access Denied');?>

<div id="wrap2" class="mw">
	<div class="wrap2header"></div>
	<div class="div_list">
		<? foreach((array)$testlist as $test) {?>
		<div class="msg_list">
			<i><?=$test['testtime']?></i><br/>@<?=$test['name']?>，刚刚用<b><?=$test['usetime']?>秒</b>完成了<?=$test['qtypename']?>知识问答，最后得分<b><?=$test['score']?>分</b>。在无可奈何花落去 棋栽夺茜
		</div>
		<? } ?>
	</div>
	<div class="div_list">
		<? foreach((array)$todaytoplist as $top) {?>
			<div class="top_list">
				<div class="top_list_text">@<?=$top['name']?><!--<img src="<?=$urlbase?>images/weiboicon16_<?=$top['lfrom']?>.png" height=16 />--><? if($top['verified']==1 && 1==0) { ?><img src="<?=$urlbase?>images/vip_<?=$top['lfrom']?>.gif" title="认证用户" alt=""><? } ?><br/>今日总分数<b><?=$top['score']?></b>分，总用时<b><?=$top['usetime']?></b>秒</div>
				<img class="avatar" src="<?=$top['avatar']?>"/>
			</div>
		<? } ?>
	</div>
	<div class="div_list">
<p><a href="<?=$orgsiteurl?>">浏览官方微博</a></p>
<p><b>其他应用推荐</b></p>
<p><a href="http://q.5d13.cn/iq">IQ测试</a></p>
<p><a href="http://q.5d13.cn/eq">EQ测试</a></p>

	</div>
	<div class="wrap2bottom"></div>
</div>

<p>l;asjkdflaskf;alsdfk</p>
</body>
</html>