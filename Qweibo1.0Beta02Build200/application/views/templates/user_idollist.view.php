<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-08 20:02
 * Filename: wodeshoutingview.php
 * Extends: base_index.view.php
 * Description: 我的听众与收听我的人基础模版
 * Comments: 已内置话题列表收起/展开效果，继承它的子页面不应再次实现。
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$lefthtml
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
 * 新声明的变量:
 * $tabname
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$lefthtml
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
 * $tabname
 * 已使用变量:
 * $lefthtml
 * 子模板可用变量:
 * $title,$user,$sitename,$lang,$arrowPosition
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
 * $tabname
******************************************************************************/
ob_start();
optional($lang,"zh-cn");
$tablist = array(
	array(
		"label" => "我收听的人",
		"link" => "",
		"isactive"=> true,
		"width"=>103,
		"height"=>33
	),
	array(
		"label" => "我的听众",
		"link" => "./index.php?m=follower",
		"isactive"=> false,
		"width"=>88,
		"height"=>33
	)
);
$tabdescription=array(
	"position"=>"bottom",
	"html"=>"我收听了<span class=\"bold\">".$unum."</span>人"	
);
$baseindexextrahead = "<style>.idolfans{padding-bottom:32px;}.pagerwrapper{position:absolute;bottom:0px;left:0px;}</style>";
$emptydescription = "<div style=\"text-indent:10px;margin-top:12px;font-size:14px;\">".($user["idolnum"] > 0 ?"暂时没有相关数据":"你暂时还没有人收听任何人")."</div>";
require_once(pathJoin(TEMPLATE_DIR,"common","idolfans.view.php"));
?>
<?php require pathJoin( TEMPLATE_DIR,'common','pagination.view.php' ); ?>
<?php
$lefthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_index.view.php' );
?>
