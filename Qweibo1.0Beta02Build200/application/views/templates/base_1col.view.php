<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 19:59
 * Filename: base_1col.view.php
 * Description: 一栏基础模板
 * $title 网页标题，如不设置，默认为"iWeibo"
 * $user 用户导航，如不设置则不显示导航
 * $sitename 站点名称(SEO优化)
 * $lang 根据不同语言重置搜索栏样式
 * $arrowPosition 导航小箭头位置，不设置则不显示小箭头
 * $extrahead 在两栏基础模板的head标签中插入数据，不设置则不插入任何数据
 * $content 页面内容
******************************************************************************/
optional($title,"iWeibo");
auto($user);
optional($sitename,"我的iWeibo");
auto($lang);
auto($arrowPosition);
auto($extrahead);
auto($content);
?>
<!doctype html>
<html>
	<head>
		<?php 
			require_once pathJoin( TEMPLATE_DIR, 'common', 'head.view.php' );
		?>
		<link rel="stylesheet" href="./style/css/global.css" type="text/css" media="screen, projection"> 
		<style>
		<?php if( isset( $arrowPosition ) ){ ?>
			.navarrow{left:<?php echo $arrowPosition; ?>px;}
		<?php }else{ ?>
			.navarrow{display:none;}
		<?php } ?>
		<?php if( isset($logourl) && !empty($logourl) ){?>
		.header .logo{background-image:url("<?php echo($logourl=='./style/images/logo.png'?$logourl:('./style/images/admin/upload/'.$logourl));?>");}
		<?php } ?>
		</style>
		<?php
				if(isset($extrahead)){
					echo $extrahead;
				}
		?>
	</head>
	<body>
		<?php 
			require_once pathJoin( TEMPLATE_DIR, 'common', 'header.view.php' );
		?>
		<div class="content">
			<?php
				if(isset($content)){
					echo $content;
				}
			?>
		</div>
		<?php 
			require_once pathJoin( TEMPLATE_DIR, 'common', 'footer.view.php' );
		?>
	</body>
</html>
