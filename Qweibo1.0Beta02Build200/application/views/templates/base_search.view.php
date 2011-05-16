<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 11:17
 * Filename: searchzonghe.view.php
 * Extends: base_2col.view.php
 * Description:	综合搜索页面 
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * 新声明的变量:
 * $huati 热门话题列表，不设置则显示 "没有热门话题"
 * $searchextrahead 搜索页head标签代码
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * $huati,$searchextrahead
 * 已使用的变量:
 * $extrahead,$righthtml
 * 子模板可用变量:
 * $title,$user,$sitename,$lang,$arrowPosition
 * $huati,$lefthtml,$searchextrahead
******************************************************************************/
?>
<?php
//插入脚本
ob_start();
?>
<style>
	.right{padding-top:10px;}
	.navarrow{display:none;}
	.pagerwrapper{position:absolute;left:0px;bottom:0px;}
</style>
<script type="text/javascript" src="./js/wbfuncs.js"></script>
<?php
if( isset($searchextrahead) ){
	echo $searchextrahead;
}
$extrahead = ob_get_contents();
ob_end_clean();
//插入右侧DOM
ob_start();
?>
<?php 
	require pathJoin( TEMPLATE_DIR,'common','recommend_topic.view.php' ); 
?>
<?php
$righthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_2col.view.php' );
?>
