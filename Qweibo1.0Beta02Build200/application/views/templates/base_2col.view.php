<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-01 19:49
 * Filename: searchbase.view.php
 * Extends: None
 * Description: 两栏基础模板 
 * 声明的变量:
 * $title 网页标题，如不设置，默认为"iWeibo"
 * $user 用户导航，如不设置则不显示导航
 * $sitename 站点名称(SEO优化)
 * $lang 根据不同语言重置搜索栏样式
 * $arrowPosition 导航小箭头位置，不设置则不显示小箭头
 * $extrahead 在两栏基础模板的head标签中插入数据，不设置则不插入任何数据
 * $lefthtml 左部部分HTML，不设置则不插入任何数据
 * $righthtml 右边部分HTML，不设置则不插入任何数据
******************************************************************************/
optional($title,"iWeibo");
auto($user);
optional($sitename,"我的iWeibo");
auto($lang);
auto($arrowPosition);
auto($extrahead);
auto($lefthtml);
auto($righthtml);
?>
<!doctype html>
<html>
	<head>
		<?php 
			require_once pathJoin( TEMPLATE_DIR, 'common', 'head.view.php' );
		?>
		<link rel="stylesheet" href="./style/css/global.css" type="text/css" media="screen, projection">
		<style>
		<?php if(isset($lang)){ ?>
			.search input.searchKey{background:url("./style/lang/<?php echo $lang;?>/images/searchform_bg.png") no-repeat left top;}
			.search input.searchKey:focus{background:url("./style/lang/<?php echo $lang;?>/images/searchform_bg.png") no-repeat left -25px;}
			.search button.searchBtn{background:url("./style/lang/<?php echo $lang;?>/images/searchform_bg.png") no-repeat bottom right;}
		<?php } ?>
		<?php if( isset( $arrowPosition ) ){ ?>
			.navarrow{left:<?php echo $arrowPosition;?>px;}
		<?php }else{ ?>
			.navarrow{display:none;}
		<?php } ?>
		<?php if( isset($logourl) && !empty($logourl) ){?>
		.header .logo{background-image:url("<?php echo($logourl=='./style/images/logo.png'?$logourl:('./style/images/admin/upload/'.$logourl));?>");}
		<?php } ?>
		</style>
		<!--[if lte IE 6]>
		<style>
		.gotopwrapper{position:absolute;}
		</style>
		<script>
			window.attachEvent("onscroll",function(){
					var fixedDiv = document.getElementById("gotopwrapper"),
						footer = document.getElementById("footer"),
						nowTop = document.documentElement.clientHeight + document.documentElement.scrollTop,
						maxTop = document.body.offsetHeight - footer.clientHeight,
						shouldTop = Math.min(nowTop,maxTop) - fixedDiv.clientHeight;
					if(fixedDiv){
							fixedDiv.style.top = shouldTop + "px";
					}
					
			},false);
		</script>
		<![endif]-->
		<script type="text/javascript" src="./js/gotoTop.js"></script>
		<?php
			if( isset($extrahead) ){
				echo $extrahead;
			}
		?>
	</head>
	<body>
		<?php 
			require_once pathJoin( TEMPLATE_DIR, 'common', 'header.view.php' );
		?>
		<div class="content">
			<div class="left">
			<?php 
				if( isset($lefthtml) ){
					echo $lefthtml;
				}
			?>
			</div>
			<div class="right">
			<?php 
				if( isset($righthtml) ){
					echo $righthtml;
				}
			?>
			</div>
			<div class="rightfix"></div>
		</div>
		<?php 
			require_once pathJoin( TEMPLATE_DIR, 'common', 'footer.view.php' );
		?>
		<div class="gotopwrapper" id="gotopwrapper">
			<div class="gotop">
				<a href="#"></a>
			</div>
		</div>
	</body>
</html>
