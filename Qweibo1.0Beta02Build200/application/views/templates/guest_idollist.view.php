<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-09 01:22
 * Filename: guest_idollist.view.php
 * Description: 查看他人收听的人
 * Extends: base_guest.view.php
******************************************************************************/
ob_start();
$tablist = array(
	array(
		"label" => getSexDesc($screenuser)."收听的人",
		"link" => "",
		"isactive"=> true,
		"width"=>103,
		"height"=>33
	),
	array(
		"label" => getSexDesc($screenuser)."的听众",
		"link" => "./index.php?m=follower&u=".$screenuser["name"],
		"isactive"=> false,
		"width"=>88,
		"height"=>33
	)
);
$tabdescription=array(
	"position"=>"right",
	"html"=>(isset($_GET["m"])&&$_GET["m"]=="following"?"收听":"听众").$unum."人"	
);
$emptydescription = "<div style=\"text-indent:10px;margin-top:12px;font-size:14px;\">".($screenuser["idolnum"] > 0 ? "暂时没有相关数据" :getSexDesc($screenuser)."暂时还没有人收听任何人")."</div>";
require_once pathJoin(TEMPLATE_DIR,"common","idolfans.view.php");
?>
<?php require pathJoin( TEMPLATE_DIR,'common','pagination.view.php' ); ?>
<?php
$chakantarenbottom = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_guest.view.php' );
?>
