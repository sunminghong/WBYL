<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-11-20 01:42
 * Filename: head.php
 * Description: 网页中<head>标签部分样式
******************************************************************************/
auto($logourl);
?>	
	<title><?php echo $title;?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="apple-touch-icon-precomposed" href="http://mat1.gtimg.com/www/mb/images/microblog_72_72.png" />
	<link rel="shortcut icon" href="http://mat1.gtimg.com/www/mb/favicon.ico"/> 
	<link rel="stylesheet" href="./style/css/base.css" type="text/css" media="screen, projection">
	<script type="text/javascript" src="./js/third-party/jquery/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="./js/third-party/jquery/jquery.selectRange.js"></script>
	<script type="text/javascript" src="./js/third-party/jquery/jquery.elastic.js"></script>
	<script type="text/javascript" src="./js/iweibo/global.js"></script>
	<script type="text/javascript">
	$(function(){
			//非必要JS,异步加载,加快页面打开速度
			var jqueryPlugins = ["jquery.color.min.js","jquery.backgroundPosition.js"/*,"jquery.selectRange.js","jquery.elastic.js"*/];
			$.each(jqueryPlugins,function( k,v){
				$.getScript("./js/third-party/jquery/"+v);
			});
	});
	</script>
	<!--[if lte IE 6]>
	<script type="text/javascript" src="./js/third-party/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">
			//IE6 PNG透明
			DD_belatedPNG.fix(".logo");
	</script>
	<![endif]-->