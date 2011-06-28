<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>
<div id="wrap2" style="background:#fff9cf;padding-bottom:25px;">
	<div style="width:676px;margin:auto;;overflow:hidden; ">
		<div class="topwrap2header pngfix"></div>
		<div style="width:676px;margin:auto;background-color:#a59d71;">
		<div class="div_list">
			<? foreach((array)$todaytoplist as $top) {?>
				<div class="top_list">
					<div class="top_list_text"><a href="?op=profile&uid=<?=$top['uid']?>" target="_blank">@<?=$top['name']?></a><!--<img src="<?=$urlbase?>images/weiboicon16_<?=$top['lfrom']?>.png" height=16 />--><? if($top['verified']==1 && 1==0) { ?><img src="<?=$urlbase?>images/vip_<?=$top['lfrom']?>.gif" alt="认证用户"><? } ?><br/>今日总分数<b><?=$top['score']?></b>分，总用时<b><?=$top['usetime']?></b>秒</div>
					<a href="?op=profile&uid=<?=$top['uid']?>" target="_blank"><img class="avatar" src="<?=$top['avatar']?>"/></a>
				</div>
			<? } ?>
		</div>

		<div class="div_list">
			<? foreach((array)$filishtoplist as $top) {?>
			<div class="top_list">
				<div class="top_list_text"><a href="?op=profile&uid=<?=$top['uid']?>" target="_blank">@<?=$top['name']?></a><!--<img src="<?=$urlbase?>images/weiboicon16_<?=$top['lfrom']?>.png" height=16 />--><? if($top['verified']==1 && 1==0) { ?><img src="<?=$urlbase?>images/vip_<?=$top['lfrom']?>.gif" alt="认证用户"><? } ?><br/>共完成了<b><?=$top['filishcount']?>次</b>问答，累计获得<?=$top['wincount']?>枚【达人勋章】。</div>
				<a href="?op=profile&uid=<?=$top['uid']?>" target="_blank"><img class="avatar" src="<?=$top['avatar']?>"/></a>
			</div>
			<? } ?>
		</div>
		
		<div class="div_list">
			<? foreach((array)$jifentoplist as $top) {?>
			<div class="top_list">
				<div class="top_list_text"><a href="?op=profile&uid=<?=$top['uid']?>" target="_blank">@<?=$top['name']?></a><!--<img src="<?=$urlbase?>images/weiboicon16_<?=$top['lfrom']?>.png" height=16 />--><? if($top['verified']==1 && 1==0) { ?><img src="<?=$urlbase?>images/vip_<?=$top['lfrom']?>.gif" alt="认证用户"><? } ?><br/>拥有<b><?=$top['jifen']?>枚</b>智慧币，累计获得<?=$top['alljifen']?>智慧币。</div>
				<a href="?op=profile&uid=<?=$top['uid']?>" target="_blank"><img class="avatar" src="<?=$top['avatar']?>"/></a>
			</div>
			<? } ?>
		</div>
		<div class="cl"></div>
		</div>
		<div class="topwrap2bottom pngfix"></div>
	</div>
</div>
<? include $this->gettpl('daren_footer');?>