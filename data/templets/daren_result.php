<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>

<div id="content">
	<div class="mw">
		<div id="div_result" class="word_<?=$score['word']?>"></div>
		<div id="div_result_naotou" class="naotou_<?=$score['naotou']?>"></div>
		<div id="div_result_text">本次测试，你用了<?=$score['newusetime']?>秒钟，答对了<?=$score['rightcount']?>题，最后得分<?=$score['nowdaren']?>分<? if($score['givejifen'] ==30 ) { ?>，得到1枚<?=$qtypename?>达人勋章及达人证书<? } ?><? if($score['givejifen']>0 ) { ?>，并获得<? echo $score['givejifen']; ?>枚#智慧币#<? } ?>。你今天的总成绩是<? echo $score['todaytotalscore']?>分。
		</div>
		<div id="div_result_area">
			<div id="div_result_area_zhengshu"><? if($darenzhengshuurl ) { ?>	
				<img src="<?=$darenzhengshuurl?>" /><? } else { ?><img src="<?=$templatepath?>/daren_images/zhengshu/notdaren.gif" /><? } ?>			
			</div>
			<textarea id="result_sendstatus">#我太有才了#！今天的<?=$qtypename?>测试中，我用了<?=$score['newusetime']?>秒钟，答对了<?=$score['rightcount']?>题，最后得分<?=$score['nowdaren']?>分<? if($score['givejifen']>0 ) { ?>，并获得<?=$score['givejifen']?>枚#智慧币#<? } ?>。在今天的<?=$qtypename?>测试中，我现在排名第<? echo $score['todaytop'.$qtype]?>名，打败了<? echo $score['todaywin'.$qtype]?>人<? if($score['givejifen'] ==30 ) { ?>，还得到了1枚<?=$qtypename?>达人勋章哦<? } ?>。我今天的总成绩是<? echo $score['todaytotalscore']?>分。呵呵！<?=$score['retname']?>你们抽几分钟来玩玩吧！</textarea>
<br/><span>发送成绩单到微博，奖励<b>2枚</b>智慧币。</span>
			<div id="div_result_btns">
				<a href="javascript:void(0);" class="btn_small btn_green_small" id="sendstatus">记录到微博</a>
				<a href="?op=ican" class="btn_big btn_red_big">再测试一次</a>
			</div>
		</div>	
	</div>
</div>

<? include $this->gettpl('daren_footer');?>