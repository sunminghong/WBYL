<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>

<div id="content_profile">
	<div class="mw">
		<div id="profile_header">
			<!--<img src="<?=$totalcountlist['avatar']?>" align="left" />-->
			<div class="profile_title">拥有<b><?=$totalcountlist['jifen']?>枚</b>智慧币<br/>消费<b><? echo $totalcountlist['alljifen']-$totalcountlist['jifen'];?>枚</b>智慧币 </div>
			<div class="profile_text">
				<textarea id="profile_text_msg"><? if($totalcountlist['allenjifen']>=1000 ) { ?><? echo $totalcountlist['name']==$account['name']?'#我太有才了#':'@'.$totalcountlist['name'].'，我的偶像，#你太有才了#';?>！竟然得到了<? if($totalcountlist['wenquxingcount']>0 ) { ?><?=$totalcountlist['wenquxingcount']?>枚文曲星勋章，<? } ?><? if($totalcountlist['boshicount']>0 ) { ?><?=$totalcountlist['boshicount']?>枚博士勋章，<? } ?><? if($totalcountlist['topcount']>0 ) { ?><?=$totalcountlist['topcount']?>枚牛人勋章，<? } ?><? if($totalcountlist['wincount']>0 ) { ?><?=$totalcountlist['wincount']?>枚达人勋章，<? } ?><?=$totalcountlist['alljifen']?>枚#智慧币#！？OH，my gold!
				<? } else { ?><? echo $totalcountlist['name']==$account['name']?'#我太有才了#':'@'.$totalcountlist['name'].'，#你太有才了#';?>！只得到了<? if($totalcountlist['wenquxingcount']>0 ) { ?><?=$totalcountlist['wenquxingcount']?>枚文曲星勋章，<? } ?><? if($totalcountlist['boshicount']>0 ) { ?><?=$totalcountlist['boshicount']?>枚博士勋章，<? } ?><? if($totalcountlist['topcount']>0 ) { ?><?=$totalcountlist['topcount']?>枚牛人勋章，<? } ?><? if($totalcountlist['wincount']>0 ) { ?><?=$totalcountlist['wincount']?>枚达人勋章，<? } ?><?=$totalcountlist['alljifen']?>枚#智慧币#！？
				<? } ?>
				</textarea>
				<a href="javascript:void(0);" id="btn_profile_sendmsg" class="btn_tiny">发送</a>
			</div>
		</div>
		<div class="profile_xunzhang_list">
			<div class="xuanzhang_area0">
				<div class="xunzhang xunzhang_wenquxing0">
					<i><?=$totalcountlist['wenquxingcount']?></i>
				</div>
				<div class="xunzhang_area">	
					<? foreach((array)$wenquxinglist as $log) {?>
					<div class="xunzhang xunzhang_wenquxing">
						<b><?=$log?></b>
					</div>
					<? } ?>
					<div class="cls"></div>
				</div>
				
			<div class="cls"></div>
			</div>
			<div class="xuanzhang_area0">
				<div class="xunzhang xunzhang_boshi0">
					<i><?=$totalcountlist['boshicount']?></i>
				</div>
				<div class="xunzhang_area">
					<? foreach((array)$boshilist as $log) {?>
					<div class="xunzhang xunzhang_boshi">
						<b><?=$log?></b>
					</div>
					<? } ?>
					<div class="cls"></div>
				</div>
				
				<div class="cls"></div>
			</div>
			<div class="xuanzhang_area0">
				<div class="xunzhang xunzhang_niuren0">
					<i><?=$totalcountlist['topcount']?></i>
				</div>
				<div class="xunzhang_area">
					<? foreach((array)$qtypetoplist as $log) {?>
					<div class="xunzhang xunzhang_niuren">
						<div><? echo $qtypenamelist[$log["qtype"]][0];?><br/><u><?=$log['winday']?></u></div>
					</div>
					<? } ?>
					<div class="cls"></div>
				</div>
				
				<div class="cls"></div>
			</div>
			<div class="xuanzhang_area0">
				<div class="xunzhang xunzhang_daren0">
					<i><?=$totalcountlist['wincount']?></i>
				</div>
				<div class="xunzhang_area">
					<? foreach((array)$qtypecountlist as $log) {?>
						<? if($log["wincount"]>0) { ?>
					<div class="xunzhang xunzhang_daren">
						<div><? echo $qtypenamelist[$log["qtype"]][0];?><br/><strong>×</strong><font><?=$log['wincount']?></font></div>
					</div>
						<? } ?>
					<? } ?>

					<div class="cls"></div>
				</div>
				<div class="cls"></div>
			</div>

			<div class="cls"></div>
		</div>
	</div>
</div>

<? include $this->gettpl('daren_footer');?>