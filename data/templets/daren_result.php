<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>

    [wincount] => 0
    [filishcount] => 0
    [testcount] => 100000
    [todaytotalscore] => 180
    [todaytotalusetime] => 16
    [todayscore1] => 100
    [todayusetime1] => 7
    [todaytop1] => 1
    [todaywin1] => 100
    [retname] => @亱_初脗 ，@lisa1234 ，@扬勋无敌 ，
    [newusetime] => 7
    [rightcount] => 10
    [nowdaren] => 100
<div id="content">
	<div id="div_result" class="mw">
		<div id="div_result_naotou"></div>
		<div id="div_result_text">这是今天第几次测试，用了<?=$score['newusetime']?>秒钟，答对了<?=$score['rightcount']?>，最后得分<?=$score['nowdaren']?><? if($score['nowdaren'] == 100 ) { ?>，得到1枚<?=$qtypename?>达人勋章。<? } ?></div>
		<div id="div_result_area">
			<img id="" src="" />
			<textarea id="">@{}</textarea>
			<div id="div_result_btns"></div>
		</div>
	</div>
</div>

<? include $this->gettpl('daren_footer');?>