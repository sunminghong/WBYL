<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-09 16:57
 * Filename: general_show_topic.view.php
 * Extends: base_2col.view.php
 * Description:	话题页面
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * $sendboxhead
 * 新声明的变量:
 * $huati 热门话题列表，不设置则显示 "没有热门话题"
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * $huati,$sendboxhead
 * 已使用的变量:
 * $extrahead,$righthtml,$lefthtml,$sendboxhead
 * 子模板可用变量:
 * $title,$user,$sitename,$lang,$arrowPosition
 * $huati
******************************************************************************/
?>
<?php
//插入脚本
ob_start();
?>
<script type="text/javascript" src="./js/wbfuncs.js"></script>
<script type="text/javascript" src="./js/updateTime.js"></script>
<script>$(function(){
	$(".sendbox").find("textarea").val("#<?php echo $ht["text"]; ?>#".replace("&lt;","<").replace("&gt;",">"));
	MSG.countTxt();
});</script>
<style>
	.right{padding-top:10px;}
	.navarrow{display:none;}
</style>
<style>
	/* 话题页 */
	.huatimain{width:590px;padding-top:15px;}
	.huatimain .leftbox{width:560px;margin:0 auto;}
	.huatimain .sendboxwrapper{height:210px;border:1px solid #e7e7e7;background:#f7f7f7;-moz-border-radius: 4px;/* Firefox */-webkit-border-radius: 4px;/* Safari,Chrome */ -khtml-border-radius: 4px;/*Linux Browsers */ border-radius: 4px;/* Browsers support css3 */border-color:#e7e7e7;/* For IE curve border */disabled_behavior: url(./style/css/third-party/PIE.htc);/* IE*/}
	.huatimain .sendboxwrapper .sendbox{background:none;}
	.huatimain .sendboxwrapper .sendbox .actionlist{left:10px;}
	.huatimain a.htflowaction{display:inline-block;width:73px;height:21px;}
	.huatimain a.htflowaction.shouting{background-position:-59px -32px;}
	.huatimain a.htflowaction.quxiao{background-position:-59px -55px;}
</style>
<!--[if lte IE 6]>
<style>
	.huatimain .sendboxwrapper .sendbox .txtandbutton{bottom:-15px;/*fix PIE.htc bug ie6下貌似会忽略margin position为absolute时显示错误*/}
</style>
<![endif]-->
<?php
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();
//左侧DOM
?>
<div class="huatimain">
	<div class="sendboxwrapper leftbox"?>
	<?php
			$sendboxhead = "<label>和&nbsp;#".$ht["text"]."#&nbsp;话题相关的广播</label>".
						   "<div class=\"gray\" style=\"padding-left:20px;\"><a class=\"usebtns htflowaction ".($ht["isfav"]?"quxiao":"shouting")."\" title=\"".($ht["isfav"]?"取消收听":"立即收听")."\" id=\"".$ht["id"]."\" style=\"margin-top:4px;\" href=\"javascript:;\"></a>&nbsp;&nbsp;<span class=\"bold\" id=\"htflowcount\">".$ht["favnum"]."</span>人收听此话题</div>";
			require pathJoin(TEMPLATE_DIR,'common','sendbox.view.php'); 
	?>
	</div>
	<div class="leftbox">
		<div class="ttitle"><b class="guangbo"></b><span>一共有<?php echo $ht["tweetnum"]; ?>条广播</span></div>
		<?php 
			require pathJoin( TEMPLATE_DIR,'common','tbody.view.php' ); 
		?>
	</div>
</div>
<?php require pathJoin( TEMPLATE_DIR,'common','pagination.view.php' ); ?>
<?php
$lefthtml = ob_get_contents();
ob_end_clean();
ob_start();
//右侧DOM
?>
<?php 
	require pathJoin( TEMPLATE_DIR,'common','recommend_topic.view.php' ); 
?>
<div class="rightsp" ></div>
<?php 
	require pathJoin( TEMPLATE_DIR,'common','fav_topic.view.php' ); 
?>
<div class="rightsp" ></div>
<?php
$righthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_2col.view.php' );
?>
