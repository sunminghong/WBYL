<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>

<div id="contentindex">

	<div id="div_home" class="mw">
		<div class="ico ico1"></div>
		<div class="ico ico2"></div>
		<div class="ico ico3"></div>
		<div class="ico ico4"></div>
		<div class="ico ico5"></div>
		<div class="ico ico6"></div>

		<div class="tips tips1">谁发明了电灯？</div>
		<div class="tips tips2">鸡有牙齿？</div>
		<div class="tips tips3">地球有多少岁了？</div>
		<div class="tips tips4">鲁班就是鲁国人吗？</div>
		<div class="tips tips5">称为几何之父的人是谁？</div>
		<div class="tips tips6">鲨鱼肚子里有鱼鳔？</div>

		<a id="btn_start" href="?op=ican" onclick="return checklogin();" class="pngfix"></a>
		
	</div>
</div>

<? include $this->gettpl('daren_footer');?>