<? if(!defined('ROOT')) exit('Access Denied');?>

<div id="mask"></div>
<div id="wrap2">
	<div style="width:676px;margin:auto;background-color:#a59d71; ">
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
			<p><a href="javascript:void(0);" onclick="_follow();">关注官方微博</a></p>
			<p><a href="<?=$orgwbsite?>">浏览官方微博</a></p>
			<p><b> 其他应用推荐</b></p>
			<p>&gt; <a href="http://q.5d13.cn/iq">IQ测试</a></p>
			<p>&gt; <a href="http://q.5d13.cn/eq">EQ测试</a></p>

		</div>
		<div class="wrap2bottom"></div>
	</div>
</div>

<div id="copyright">(c)Copyright by 米奇吧</div>

<div id="div_login">
	<? global $canLogin;?>
	<? foreach((array)$canLogin as $login) {?>
		<a href="?app=home&act=account&op=tologin&lfrom=<?=$login?>&fromurl=<? echo urlencode(URLBASE.'?op=ican');?>" border="0"><img height="32" src="images/btn_login_<?=$login?>_32.png" alt="用微博帐号登录" /></a> 
	<? } ?>
</div>


</body>
</html>