<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-09 00:24
 * Filename: idolfans.view.php
 * Description: 收听的人，听众统一模块
 * 变量列表:
 * $tablist
 * $tabdescription
 * Comment:
 * $tablist格式
 * $tablist = array(
 *		array(
 *			"label" => "我收听的人",//显示的文字
 *			"link" => "",//tab链接
 *			"isactive"=>false,//是否高亮
 *			"width"=> 73, //tab宽度,支持73,88,103，适用于三个汉字，五个汉字，六个汉字的情况
 *			"height"=> 33 //请固定为33像素
 *		)//代表一个tab
 *		array(
 *		)//代表一个tab
 * )
 * $infotext格式
 * $tabdescription = array(
 *		"position" => "bottom",//请设置为right或bottom之一
 *		"html" => "string"//文字内容
 * )
******************************************************************************/
required($tablist,"tablist",__FILE__);
auto($tabdescription);//可不设置
?>
<div class="idolfans">
	<div class="idolfanshead">
		<div class="tabbar">
				<ul class="tabs">
					<?php foreach( $tablist as $_tab){ ?>
						<?php if($_tab["isactive"]){ ?>
							<li class="tab<?php echo $_tab["width"]?>x<?php echo $_tab["height"] ?> active"><?php echo $_tab["label"] ?></li>
						<?php }else{ ?>
							<li class="tab<?php echo $_tab["width"]?>x<?php echo $_tab["height"] ?>"><a href="<?php echo $_tab["link"] ?>"><?php echo $_tab["label"] ?></a></li>
						<?php } ?>
					<?php } ?>
				</ul>
				<?php if( isset($tabdescription) && $tabdescription["position"] === "right" ){ ?>
					<div class="idolfansrightinfotext gray"><?php echo $tabdescription["html"] ?></div>
				<?php } ?>
		</div>
		<?php if( isset($tabdescription) && $tabdescription["position"] === "bottom" ){ ?>
			<div class="idolfansbottominfotext"><?php echo $tabdescription["html"] ?></div>	
		<?php } ?>
	<?php
		if( isset($u) && is_array($u) && count($u) > 0 ){
			require_once pathJoin(TEMPLATE_DIR,"common","ubody.view.php");
		}else{
			if( isset($emptydescription) ){
				echo $emptydescription;
			}
		}
	?>
	</div>
</div>
