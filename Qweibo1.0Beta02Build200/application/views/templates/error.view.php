<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 20:04
 * Filename: http404.view.php
 * Extend: base_1col.view.php
 * Description: 标准404出错页面
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 新声明的变量:
 * $error
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 已使用的变量:
 * $content,$extrahead
 * 子模板可用变量: 
 * $title,$user,$sitename,$lang,$arrowPosition
 * Comment:
 * $error格式
 * $error = array(
 *		"message"=>"",//错误信息
 *		"code"=>"",//错误代号
 *		"showcode"=>""//是否显示错误代号
 * )
******************************************************************************/
ob_start();
?>
<style>
/* rewrite */
.content{height:495px;min-height:495px;background:white url("./style/images/error_bg.gif") repeat-x left bottom;}
/* 错误页面 */
.errormain{margin:5px 0px;padding:70px 0px 30px 90px;}
ul.errormessages{width:90%;height:410px;color:#333;font:normal normal normal 12px/1.75 Tahoma, Arial;background:url("./style/images/error_bg1.jpg") no-repeat right bottom;}
ul.errormessages .errortext{font-size:26px;font-family:"MicroSoft YaHei",SimHei;line-height:26px;}
ul.errormessages li{line-height:16px;margin-top:10px;}
.option{background-position:-202px -16px;background-repeat:no-repeat;color:#fff;padding-left:5px;height:18px;line-height:18px;margin-top:10px;}
a.goback:hover {text-decoration:underline;}
<?php if( isset($logourl) && !empty($logourl) ){?>
	.header .logo{background-image:url("<?php echo $logourl;?>");}
<?php } ?>
</style>
<?php
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="errormain">
<ul class="errormessages">
<?php 
	if( !isset($error) ){
		$error = array(
			"message"=>"未知错误",
			"code"=>"未知错误代码",
			"showcode"=>False,
		);
	}
	echo "<li class=\"errortext\">".$error["message"]."</li>";
	echo "<li class=\"errortext ".($error["showcode"]?"":"hide")."\">错误代码:".$error["code"]."</li>";
?>
<li><a class="goback mediumfont" href="javascirpt:;" onclick="var len=history.length;if(len>1 || (len==1 && navigator.userAgent.indexOf('MSIE')!=-1)){history.go(-1);return false;}else{location.href='/'}">返回到之前浏览的页面</a></li>
<li class="mediumfont bold">或者:</li>
<li>
		<div class="usebtns option">1&nbsp;&nbsp;&nbsp;&nbsp;<a href="./index.php?m=index">我的主页</a></div>
		<div class="usebtns option">2&nbsp;&nbsp;&nbsp;&nbsp;<a href="./index.php?m=public">看看大家在说什么</a></div>
</li>
</ul>
</div>
<?php
	$content = ob_get_contents();
	ob_end_clean();
	require_once pathJoin( TEMPLATE_DIR,'base_1col.view.php' );
?>
