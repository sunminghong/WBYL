<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-09 16:44
 * Filename: sendbox.view.php
 * Description: 输入框
******************************************************************************/
optional($sendboxhead,"<label>来，说说你在做什么，想什么</label>");
?>
<div class="sendbox">
	<?php echo $sendboxhead;?>
	<form action="./index.php" id="sendTweet" target="sendTweet" method="post" enctype="multipart/form-data">
	<input type="hidden" name="m" value="a_addt" />
	<input type="hidden" name="type" value="1" />
	<input type="hidden" name="callback" value="" />
	<input type="hidden" name="format" value="html" />
	<div class="holder">
		<div class="holderhighlight"></div>
		<textarea id="msgTxt" autocomplete="off" name="content"></textarea>
	</div>
	<div class="actionlist zbottom">
		<div class="first"><b class="huati"></b><a href="#" id="newTopic" title="创建/加入话题讨论，汇聚相同热点广播">话题</a></div>
		<div><b class="zhaopian"></b><a href="#" class="zhaopiantxt" >照片</a><span class="usebtns cancelPic" title="取消上传"></span></div>
	</div>
	<div class="actionlist ztop">
	<div style="width:40px;height:20px;"></div>
	<?php 
//		<div class="first"><b class="shipin"></b><a href="#">视频</a></div>
//		<div><b class="yinyue"></b><a href="#">音乐</a><span></span></div>
//		<!--TODO：针对简体中文，繁体中文推出微博消息快速格式化工具-->
//		<!--<div><b class=""></b><a href="#">工具</a><span></span></div>-->
	?>
	</div>
	<div class="zmiddle">
		<input type="file" id="uploadImg" name="pic" title="可选择jpg、jpeg、gif、png格式，文件小于2M"/>
	</div>
	</form>
	<iframe style="display:none" name="sendTweet"></iframe>
	<div class="txtandbutton" style="">
		<div id="sendbtn" class="sendbtn" title="快捷键 Ctrl+Enter"></div>
		<div class="text" id="overflowHint"><span id="countHint">还能输入</span><span class="count">140</span>字</div>
	</div>
</div>
<script src="./js/iweibo/library.js"></script>
<script src="./js/iweibo/index.js"></script>
