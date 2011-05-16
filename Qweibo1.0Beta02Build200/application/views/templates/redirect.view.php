<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 20:04
 * Filename: http404.view.php
 * Extend: ../base_1col.view.php
 * Description: 标准404出错页面
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 新声明的变量:
 *
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$content
 * 已使用的变量:
 * $content,$extrahead
 * 子模板可用变量: 
 * $title,$user,$sitename,$lang,$arrowPosition
******************************************************************************/
required($redirectTo,"redirectTo",__FILE__);
optional($redirectWaitTime,3000);
ob_start();
?>
<style>
.content{height:495px;min-height:495px;background:white url("./style/images/error_bg.gif") repeat-x left bottom;}
.messagemainwrap{margin:70px 0px 30px 90px;}
.messagemainwrap .message{font-size:26px;width:90%;height:400px;color:#333;font-family:"MicroSoft YaHei",SimHei;background:url("./style/images/error_bg1.jpg") no-repeat right bottom;}
<?php if( isset($logourl) && !empty($logourl) ){?>
	.header .logo{background-image:url("<?php echo $logourl;?>");}
<?php } ?>
</style>
<script>
setTimeout("window.location.href='<?php echo $redirectTo;?>';",<?php echo $redirectWaitTime;?>);
</script>
<?php
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="messagemainwrap">
<?php if( isset($message) ){ ?>
<div class="message"><span class="<?php echo $message['type']."message";?>"><?php echo $message["text"];?></span>,
<?php } ?>
页面自动跳转中...
<div style="text-align:left;"><a href="<?php echo $redirectTo;?>"><?php echo $redirectTo;?></a></div>
</div>
<?php
	$content = ob_get_contents();
	ob_end_clean();
	require_once pathJoin( TEMPLATE_DIR,'base_1col.view.php' );
?>
