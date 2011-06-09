<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('daren_header');?>

<div id="content">
	<div class="mw">
<a href="javascript:void(0)" id="btn_starttest">开始测试</a>
	</div>
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
	<div id="test_main" style="border:1px solid #333;">
		<span class="div_question">
			<span id="question_index">问题 1/10：</span><br/>
			<span id="question">
			唑始懂了栽发生率；大富科开始懂了始懂了栽发生率；大富科栽发生率；大富科</span>
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