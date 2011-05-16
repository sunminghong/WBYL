<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2011-1-1
 * Filename: general_show_chat.view.php
 * Extend: base_1col.view.php
 * Description:	对话详细页
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 新声明的变量:
 * 变量列表:
 * 已使用的变量:
 * 子模板可用变量: 
******************************************************************************/
ob_start();
?>
<script type="text/javascript" src="./js/wbfuncs.js"></script>
<script>pagename = "duihua";</script>
<?php
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="duihuahead">
<h1 class="duihuatitle">相关对话&nbsp;<?php echo count($t); ?>&nbsp;条</h1>
<?php
	require pathJoin( TEMPLATE_DIR,'common','tbody.view.php' ); 
?>
</div>
<?php
$content = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_1col.view.php' );
?>
