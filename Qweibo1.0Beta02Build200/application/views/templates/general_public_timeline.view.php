<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-14 20:19
 * Filename: general_public_timeline.view.php
 * Extends: base_2col.view.php
 * Description:	广播大厅页面 
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * 新声明的变量:
 * $huati 热门话题列表，不设置则显示 "没有热门话题"
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * $huati
 * 已使用的变量:
 * $extrahead,$righthtml,$arrowPosition
 * 子模板可用变量:
 * $title,$user,$sitename,$lang
 * $huati,$lefthtml
******************************************************************************/
$arrowPosition = 530;
?>
<?php
//插入脚本
ob_start();
?>
<style>
	/* 广播大厅样式重置 */
	.right{padding-top:10px;}
	.navarrow {height: 0;width: 0;font-size: 0;line-height: 0;border-color: transparent transparent #fff transparent;_border-color: red red #fff red;_filter: chroma(color = red);border-style: solid;border-width: 8px;}/*重置导航箭头颜色*/
	ul.umain li.umessage .ubody .utop{height:auto;}
	ul.umain li.umessage{border:none;}
	/* 广播大厅顶部轮换消息  */
	.utbodywrapper{position:relative;overflow:hidden;}
	.utbodywrapper .utmask{position:absolute;top:0;left:0;width:100%;height:100%;background:#fff;z-index:2;display:none;}
</style>
<script type="text/javascript" src="./js/wbfuncs.js"></script>
<script type="text/javascript" src="./js/guangbodating.js"></script>
<?php
$baseindexextrahead = ob_get_contents();
ob_end_clean();
ob_start();
//左侧DOM
?>
<div class="guangbodatingwrapper">
	<div class="guangbodating tocenter">
		<?php 
			//推荐广播
			if( array_key_exists("jinrituijian",(array)$moduleconfig) ){//推荐话题为空时不显示此模块
				echo "<div class=\"tuijianhuati tabbar\">";
				echo "<div class=\"toleft left12\">";
				if( !empty($moduleconfig["jinrituijian"]["name"]) ){
					echo "<span class=\"bold\" title=\"".$moduleconfig["jinrituijian"]["name"]."\">".limit($moduleconfig["jinrituijian"]["name"],15).":&nbsp;</span>";
				}else{
					echo "<span class=\"bold\">今日推荐:&nbsp;</span>";
				}
				if( !empty($moduleconfig["jinrituijian"]["value"]) ){
					echo "<a class=\"bold\" href=\"./index.php?m=topic&k=".urlencode($moduleconfig["jinrituijian"]["value"])."\" title=\"".$moduleconfig["jinrituijian"]["value"]."\">#<span id=\"jintituijian\">".limit($moduleconfig["jinrituijian"]["value"],16)."</span>#</a>";
				}else{
					echo "";
				}
				//echo "<div class=\"toright right12 fs12\"><a href=\"\">立即收听</a></div>";
				echo "</div>";
				echo "</div>";
				$ut = $moduleconfig["jinrituijian"]["data"];
				if( !empty($moduleconfig["jinrituijian"]["value"]) && is_array($ut) && count($ut) > 0 ){
					echo "<div class=\"utbodywrapper\" id=\"utbodywrapper\">";
					echo "<div class=\"utmask\"></div>";
					require pathJoin( TEMPLATE_DIR,'common','utbody.view.php' );
					echo "</div>";
				}else{
					echo "暂时还没有人发表含此话题的广播";
				}
			}
			//推荐用户
			if( array_key_exists("tuijianyonghu",(array)$moduleconfig) ){
				echo "<div class=\"linehead\">";
				echo "<div class=\"lineheadtitle\" title=\"";
				if( !empty($moduleconfig["tuijianyonghu"]["name"]) ){
					echo $moduleconfig["tuijianyonghu"]["name"];
				}else{
					echo "推荐的用户";
				}
				echo "\">";
				if( !empty($moduleconfig["tuijianyonghu"]["name"]) ){
					echo $moduleconfig["tuijianyonghu"]["name"];
				}else{
					echo "推荐的用户";
				}
				echo "</div>";
				echo "<div class=\"lineheadbg\"></div>";
				echo "</div>";
				echo "<div class=\"tuijianyonghuwrapper\">";
				if( is_array($moduleconfig["tuijianyonghu"]["data"]) && count($moduleconfig["tuijianyonghu"]["data"]) > 0 ){
						$tuijianyonghu_chunk = array_chunk($moduleconfig["tuijianyonghu"]["data"],3);
						foreach ($tuijianyonghu_chunk as $tuijianyonghu_grp){
							echo "<ul class=\"tuijianyonghu\">";
							foreach($tuijianyonghu_grp as $tuijianyonghu_single){
								echo "<li>";
								echo "<div class=\"leftpart\">";
								echo "<a class=\"tuijianyonghuhead tocenter\" href=\"./index.php?m=guest&u=".$tuijianyonghu_single["name"]."\" title=\"".$tuijianyonghu_single["nick"]."(@".$tuijianyonghu_single["name"].")\"><img src=\"".( array_key_exists("head",$tuijianyonghu_single)&&!empty($tuijianyonghu_single["head"])&&$tuijianyonghu_single["head"]!="http://app.qlogo.cn/50" ? $tuijianyonghu_single["head"]:"./style/images/default_head_small.png" )."\"></img></a>";
								if($tuijianyonghu_single["name"] != $user["name"] ){
									echo "<a class=\"flowactionsmall tocenter usebtns ".($tuijianyonghu_single["ismyidol"] ? "quxiao":"shouting")."\" href=\"javascript:;\" data-username=\"".$tuijianyonghu_single["name"]."\" title=\"".($tuijianyonghu_single["ismyidol"] ? "取消":"收听")."\"></a>";
								}
								echo "</div>";
								echo "<div class=\"rightpart\">";
								echo "<div style=\"line-height:18px;\">";
								echo "<a href=\"./index.php?m=guest&u=".$tuijianyonghu_single["name"]."\" title=\"".$tuijianyonghu_single["nick"]."(@".$tuijianyonghu_single["name"].")\" class=\"nickname block fs14 \">".$tuijianyonghu_single["nick"];
								if($tuijianyonghu_single["isvip"]){
									echo "<span class=\"renzheng\"></span>";
								}
								echo "</a>".$tuijianyonghu_single["introduction"];
								echo "</div>";
								echo "</div>";
								echo"</li>";
							}
							echo "</ul>";
						}
				}else{
					echo "没有推荐用户";
				}
				echo "</div>";
			}
		?>
		<div class="linehead" style="top:1px;">
			<div class="lineheadtitle" title="大家都在说">大家都在说</div>
			<div class="lineheadbg"></div>
		</div>
		<?php require pathJoin( TEMPLATE_DIR,'common','tbody.view.php' ); ?>
	</div>
</div>
<?php require pathJoin( TEMPLATE_DIR,'common','pagination.view.php' ); ?>
<?php
$lefthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_index.view.php' );
?>
