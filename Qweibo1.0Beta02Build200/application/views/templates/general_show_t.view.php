<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-03 09:37
 * Filename: general_show_t.view.php
 * Extend: base_1col.view.php
 * Description:	单条微博页模板 
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 新声明的变量:
 * 变量列表:
 * 已使用的变量:
 * 子模板可用变量: 
******************************************************************************/
ob_start();
?>
<script>pagename = "dantiaoweibo";</script>
<script type="text/javascript" src="./js/wbfuncs.js"></script>
<style>
.pagerwrapper{position:absolute;left:0;bottom:0;}
.pager{width:760px;}
.pager{
	-webkit-border-bottom-left-radius: 10px;
	-webkit-border-bottom-right-radius: 10px;
	-moz-border-radius-bottomleft: 10px;
	-moz-border-radius-bottomright: 10px;
	border-bottom-left-radius: 10px;
	border-bottom-right-radius: 10px;
	}
</style>
<?php
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="dantiaohead">
<?php
	require pathJoin( TEMPLATE_DIR,'common','tbody.view.php' ); 
?>
<?php if( isset($tall) && is_array($tall) && count($tall) > 0 ){ ?>
	<div class="zhuanbohead">转播和点评共<?php echo ($t[0]["count"]+$t[0]["mcount"]);?>条</div>
	<ul class="zhuanbolist">
	<?php foreach($tall as $ts){ ?>
		<li>
		<div><a href="./index.php?m=guest&u=<?php echo $ts["name"];?>"><?php echo $ts["nick"];?></a>
		<?php if( array_key_exists("isvip",$ts) && $ts["isvip"] ){ ?>
			<span class="renzheng"></span>
		<?php } ?>
		<?php if( array_key_exists("frommobile",$ts) && $ts["frommobile"] ){ ?>
			<span class="shouji"></span>
		<?php } ?>
		<span class="zhuanbotxt gray"><?php echo $ts["type"]==7 ? "点评":"转播"; ?>:</span><span><?php echo $ts["text"];?></span></div>
		<div class="timesourcetxt"><a href="./index.php?m=showt&tid=<?php echo $ts["id"];?>"><?php echo $ts["timestring"];?></a>&nbsp;来自 <?php echo $ts["from"];?></div>
		</li>
	<?php } ?>
	</ul>
<?php } ?>
</div>
<?php require pathJoin( TEMPLATE_DIR,'common','pagination.view.php' ); ?>
<?php
$content = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_1col.view.php' );
?>
