<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>
<div id="content">
	<div class="mw">
	<div id="div_ready"></div>
	<div id="div_ready_naotou" class="naotou_weixiao"></div>
	<div id="div_qtypeicos">
		<div class="fl"><div class="ico boygirl" qtypename="综合" qtype="1"></div></div>
		<div class="fl"><div class="ico emaobi" qtypename="文学" qtype="2"></div></div>
		<div class="fl"><div class="ico xianzhuangshu" qtypename="历史" qtype="3"></div></div>
		<div class="fl"><div class="ico qiqiu" qtypename="地理" qtype="4"></div></div>
		<div class="fl"><div class="ico zhongniandafunan" qtypename="常识" qtype="5"></div></div>
		<div class="fl"><div class="ico houzi" qtypename="自然" qtype="6"></div></div>
		<div class="fl"><div class="ico xianweijin" qtypename="科技" qtype="7"></div></div>
		<div class="fl"><div class="ico niudun" qtypename="物理天文" qtype="8"></div></div>
		<div class="fl"><div class="ico lanqiu" qtypename="文体" qtype="9"></div></div>
		<div class="fl"><div class="ico diannao" qtypename="电脑" qtype="10"></div></div>
		<div class="fl"><div class="ico shiguan" qtypename="其它" qtype="11"></div></div>
		<div style="clear:both;"></div>
	</div>
	</div>
</div>

<div id="icohover" style="display:none;">
	<div class="ico_tips"></div>
	<div class="ico_big"></div>
</div>
<!--测试主区域-->
<div id="div_test" class="mw">
	<div id="div_helper">找我求助很便宜的，每次<b>1枚</b>智慧币，你还可以求助<b><?=$score['jifen']?>次</b>！<br/>
	<a href="javascript:void(0);" class="btn_tiny" id="btn_helper">求助</a>
	</div>
	<div class="timeline pngfix">
		<div id="progress" class="pngfix"></div>
		<div id="div_icos">
			<b class="pngfix"></b>
			<b class="pngfix"></b>
			<b class="pngfix"></b>
			<b class="pngfix"></b>
			<b class="pngfix"></b>
			<b class="pngfix"></b>
			<b class="pngfix"></b>
			<b class="pngfix"></b>
			<b class="pngfix"></b>
			<b class="pngfix"></b>
		</div>
	</div>
	<div id="test_main">
		<span class="div_question">
			<span id="question_index">问题 1/10：</span><br/>
			<span id="question">正在读取题目。。。</span>
		</span>
		<div class="div_answer" id="div_answer">
			<a class="answer pngfix" href="#continue"><span id="answer1"></span></a>
			<a class="answer pngfix" href="#continue"><span id="answer2"></span></a>
			<a class="answer pngfix" href="#continue"><span id="answer3"></span></a>
			<a class="answer pngfix" href="#continue"><span id="answer4"></span></a>
			<div class="cl"></div>
		</div>
	</div>
</div>

<div id="div_wait"></div>
<? include $this->gettpl('daren_footer');?>