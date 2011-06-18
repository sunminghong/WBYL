<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>

<div id="content_profile">
	<div class="mw">
		<div id="profile_header">
			<img src="<?=$totalcountlist['avatar']?>" align="left" />
			<div class="profile_title"><?=$totalcountlist['name']?>的成就</div>
			<div class="profile_text">
				<textarea ></textarea>
				<a class="btn_tiny">发送</a>
			</div>
		</div>

		<div>还有<?=$totalcountlist['jifen']?>才币</div>
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
					<? foreach((array)$qtypecountlist as $log) {?>
						<? if($log["topcount"]>0) { ?>
					<div class="xunzhang xunzhang_niuren">
						<div><? echo $qtypenamelist[$log["qtype"]][0];?><br/><strong>×</strong><font><?=$log['topcount']?></font></div>
					</div>
						<? } ?>
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