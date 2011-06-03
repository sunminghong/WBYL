<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('ilike_header');?>
    <div id="main">
		<div id="daytop_wrap">
			<div id="daytop_left">
				<h1 >昨天之最</h1>
				<h2>历史之最</h2>
				<div id="pastdatelist">
				<? foreach((array)$daytoplist as $top) {?>
					  <a href="<?=$urlbase?>#<?=$top['picid']?>" title="<?=$top['name']?>"><div class="imgwrap"><img src="<?=$top['small_pic']?>"></div></a>
				<? } ?>
				</div>
			</div>

			<div id="daytop_right">
				<div id="daytop_bestpic">
					<a href="<?=$urlbase?>#<?=$daytoplist[0]['picid']?>" title="<?=$daytoplist[0]['name']?>"><img src="<?=$daytoplist[0]['big_pic']?>" id="photodiv_img"/></a>
					
				</div>
				<div id="daytop_yestdate" class="pngfix"><?=$tmonth?><span><?=$tday?></span></div>
			</div>
			<div style="clear:both;font-size:0;"></div>
		</div>
    </div>
    
	<? include $this->gettpl('ilike_footer');?>