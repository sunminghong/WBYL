<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>

<div id="content">
	<div id="div_ready" class="mw">
		<div id="div_ready_naotou" class="naotou_weixiao">
			<div id="div_qtypeicos">
				<div class="ico boygirl" qtypename="综合" qtype="1"></div>
				<div class="ico emaobi" qtypename="文学" qtype="2"></div>
				<div class="ico xianzhuangshu" qtypename="历史" qtype="3"></div>
				<div class="ico qiqiu" qtypename="地理" qtype="4"></div>
				<div class="ico zhongniandafunan" qtypename="常识" qtype="5"></div>
				<div class="ico houzi" qtypename="自然" qtype="6"></div>
				<div class="ico xianweijin" qtypename="科技" qtype="7"></div>
				<div class="ico niudun" qtypename="物理天文" qtype="8"></div>
				<div class="ico lanqiu" qtypename="文体" qtype="9"></div>
				<div class="ico diannao" qtypename="电脑" qtype="10"></div>
				<div class="ico shiguan" qtypename="其它" qtype="11"></div>

			</div>
<!--			<a href="#" onclick="init()">测试一下</a> -->
		</div>
	</div>
</div>

				<div id="icohover" style="display:none;">
					<div class="ico_tips"></div>
					<div class="ico_big"></div>
				</div>
<div id="mask"></div>
<!--测试主区域-->
<div id="div_test" class="mw">
	<div class="timeline">
		<div id="progress"></div>
		<div id="div_icos">
			<b></b>
			<b></b>
			<b></b>
			<b></b>
			<b></b>
			<b></b>
			<b></b>
			<b></b>
			<b></b>
			<b></b>
		</div>
	</div>
	<div id="test_main">
		<span class="div_question">
			<span id="question_index">问题 1/10：</span><br/>
			<span id="question">正在读取题目。。。</span>
		</span>
		<div class="div_answer" id="div_answer">
			<a class="answer" href="javascript:void(0);"><span id="answer1"></span></a>
			<a class="answer" href="javascript:void(0);"><span id="answer2"></span></a>
			<a class="answer" href="javascript:void(0);"><span id="answer3"></span></a>
			<a class="answer" href="javascript:void(0);"><span id="answer4"></span></a>
			<div class="cl"></div>
		</div>
	</div>
</div>

<? include $this->gettpl('daren_footer');?>