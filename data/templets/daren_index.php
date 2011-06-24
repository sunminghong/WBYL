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
<script>
var words=[
"谁发明了电灯？",
"鸡有牙齿？",
"地球有多少岁了？",
"鲁班就是鲁国人吗？",
"称为几何之父的人是谁？",
"鲨鱼肚子里有鱼鳔？",
"中国共产党成立与哪一天？",
"名人“三毛”是作家还是化学家？",
"“岁寒三友”是指？",
"为什么花一定要向着阳光？",
"圣诞老人装一定是红色吗？",
"水珠为什么是这个形状？",
"中国在南半球还是北半球？",
"为什么要堆雪人？",
"云是怎么形成的？",
"国际交际舞有哪些？",
"现在的香烟是油烟吗？",
"古代“鼎”最早是什么用途？",
"日光通过什么可以分解成七色？",
"比目鱼是热带鱼吗？",
"“廿”是什么意思？",
"人体骨骼有多少块？",
"蓝鲸是地球上最大的动物吗？"
];

$(document).ready(function(){
	var home=$('#div_home');
	var icou=6;
	setInterval(function(){
		if(icou>22) icou=0;
		var y=110*icou;
		var idx=icou % 6 +1 ;
		home.find('.ico'+idx).css('background-position','0 -'+ y +'px');
		home.find('.tips'+idx).html(words[icou]);
		icou++;
	},2000);

});

</script>
<? include $this->gettpl('daren_footer');?>