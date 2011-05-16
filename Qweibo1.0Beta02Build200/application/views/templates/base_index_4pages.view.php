<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 17:57
 * Filename: base_index_4pages.view.php
 * Extends: base_index.view.php
 * Description: 我的主页，我的广播，提到我的，我的收藏基础模板
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$lefthtml
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
 * 新声明的变量:
 * $tmainParentWidth 消息列表宽度,默认值为570像素
 * $theader 消息列表头部
 * $t 消息列表数组,用户生成消息体
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$lefthtml
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
 * $tmainParentWidth,$theader,$t
 * 已使用变量:
 * $baseindexextrahead,$lefthtml
 * 子模板可用变量:
 * $title,$user,$sitename,$lang,$arrowPosition
 * $screenuser,$highlightmenu,$huati
 * $tmainParentWidth,$theader,$t
******************************************************************************/
ob_start();
optional($tmainParentWidth,570);
optional($lang,"zh-cn");
auto($hasnext);//是否有下一屏
?>
<script type="text/javascript">
	<?php 
		echo "var pagename = \"";
		if( $highlightmenu == 0 ){
			echo "zhuye";
		}else if( $highlightmenu == 1 ){
			echo "guangbo";
		}else if( $highlightmenu == 2 ){
			echo "tidao";
		}else if( $highlightmenu == 3 ){
			echo "shoucang";
		}
		echo "\";";
	?>
</script>
<script type="text/javascript" src="./js/wbfuncs.js"></script>
<script type="text/javascript" src="./js/updateTime.js"></script>
<style>
		.sendbox .actionlist div b{background:url("./style/lang/<?php echo $lang;?>/images/sendbox_btns.gif") no-repeat;}
		.sendbox .txtandbutton button.sendbtn{background:url('./style/lang/<?php echo $lang;?>/images/sendbox_btns.gif')}
		/* 消息体父容器 */
		.tcontainer{width:<?php echo $tmainParentWidth;?>px;margin-left:10px;min-height:900px;_height:900px;}
		ul.tmain li.tmessage .tbody{float:left;width:<?php echo ($tmainParentWidth-10-74-10);?>px;padding-top:10px;padding-right:10px;font-size:14px;color:#333;}
        /* 输入框容器 */
		.sendboxcontainer{width:590px;height:180px;}
		.sendboxcontainer .sendbox .actionlist{left:25px;}
		/*.content{background:#e6e6e4;}*/
</style>
<?php
$baseindexextrahead = ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="sendboxcontainer">
	<?php
		require pathJoin( TEMPLATE_DIR,'common','sendbox.view.php' ); 
	?>
</div>
<div class="tcontainer">
	<?php 
		echo "<div class=\"ttitle\">";
		echo "<b class=\"";
		if( $highlightmenu == 0 ){
			echo "";
		}else if( $highlightmenu == 1 ){
			echo "guangbo";
		}else if( $highlightmenu == 2 ){
			echo "tidao";
		}else if( $highlightmenu == 3 ){
			echo "shoucang";
		}
		echo "\"></b>";
		echo "<span>";
		if( $highlightmenu == 0 ){
			echo "我的主页";
		}else if( $highlightmenu == 1 ){
			echo "我的广播";
		}else if( $highlightmenu == 2 ){
			echo "提到我的";
		}else if( $highlightmenu == 3 ){
			echo "我的收藏";
		}
		echo "</span>";
		echo "</div>"
	?>
	<a class="newmessage" id="newmessage" href="javascript:;" style="display:none;"><div class="newmessageloading hide" id="newmessageloading"></div>有<span class="newmessagecount" id="newmessagecount"></span>条新消息，点击查看</a>
	<?php
		if( isset($t) && is_array($t) && count($t) > 0 ){
			require pathJoin( TEMPLATE_DIR,'common','tbody.view.php' );
		}else{
			echo "<div class=\"bold fs14\" style=\"text-align:center;margin-top:20px;height:660px;\">";
			if( $highlightmenu == 0 ){
				echo "暂时还没有任何广播";
			}else if( $highlightmenu == 1 ){
				echo "暂时还没发表过广播";
			}else if( $highlightmenu == 2 ){
				echo "暂时还没有提到你的消息";
			}else if( $highlightmenu == 3 ){
				echo "暂时还没有收藏任何广播";
			}
			echo "</div>";
		}
	?>
</div>
<?php require pathJoin( TEMPLATE_DIR,'common','nextscreen.view.php' );  ?>
<?php
$lefthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_index.view.php' );
?>
