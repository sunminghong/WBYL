<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 18:31
 * Filename: sixin.view.php
 * Extends: base_index.view.php
 * Description: 私信页发件箱模板
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$lefthtml
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
 * 新声明的变量:
 * $mailcount 发件箱/收件箱总数
 * $inbox 收件箱数据
 * $sendbox 发件箱数据
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$lefthtml
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
 * $mailcount,$inbox,$sendbox
 * 已使用的变量:
 * $highlightmenu,$baseindexextrahead,$lefthtml
 * 子模板可用变量:
 * $title,$user,$sitename,$lang,$arrowPosition
 * $screenuser,$huati
 * $mailcount,$inbox,$sendbox
******************************************************************************/
$highlightmenu = 4;
ob_start();
?>
<style>
.mailcontainer{min-height:900px;_height:900px;}
</style>
<?php
$baseindexextrahead =  ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="mailcontainer">
	<div class="mailtitle"><b class="sixin"></b><span class="title">私信</span><span class="shuoming">私信只能发送给你的听众，若想与朋友通过私信交流，请先相互收听</span></div>	
	<div class="titlebar">
		<div class="tabbar">
			<ul class="tabs"><li class="tab82x33"><a href="./index.php?m=inbox">收件箱</a></li><li class="active tab82x33">发件箱</li></ul>
			<div class="sendmailwrapper"><span><?php if( isset($boxnum) ){ ?>发件箱共有<?php echo $boxnum;?>封信<?php } ?></span><a class="sendmail" href="javascript:;">发私信</a></div>
		</div>
	</div>
	<div id="mailsendbox">
		<?php
			if( isset($box) && is_array($box) ){
				echo "<ul class=\"mailmain\">";
				foreach( $box as $t ){
					echo "<li class=\"mailcell\">";
					echo "<div class=\"mailbody\">";
					echo "<div class=\"title\">发送给&nbsp;<a href=\"./index.php?m=guest&u=".$t["toname"]."\" class=\"nickname\">".$t["tonick"]."</a><span class=\"comma\">:</span></div>";
					echo "<div class=\"breakword breakall\">".$t["text"]."</div>"; 
					echo "<div class=\"bodybottom\">".$t["timestring"]."</div></div>";
					echo "<div class=\"mailaction\"><a href=\"javascript:;\" class=\"sendmail bigger first\" data-username=\"".$t["toname"]."\">再写一封</a><a href=\"javascript:;\" class=\"smaller delmail last\" id=\"{$t["id"]}\">删除</a></div>";
					echo "</li>";
				}
				echo "</ul>";
			}else{
				echo "<div style=\"text-align:center;padding:20px;font-size:14px;font-weight:bold;\">你还没有发过私信</div>";
			}
		?>
	</div>
</div>
<?php require pathJoin( TEMPLATE_DIR,'common','pagination.view.php' ); ?>
<div class="D">
	<div class="bg" style="margin-left: 0pt;"></div>
	<div class="CR" style="width: 570px; margin: -86px 0pt 0pt -285px;">
		<table cellspacing="0" cellpadding="0" border="0" class="tbSendMsg">
			<tbody>
				<tr>
					<td class="tl"></td>
					<td class="tm"></td>
					<td class="tr"></td>
				</tr>
				<tr>
					<td class="lm"></td>
					<td>
						<div class="DWrap">
							<div class="DTitle" style="height: 0pt;"></div>
							<a href="#" class="DClose close" title="关闭">关闭</a>
							<div class="DLoad"></div>
							<div class="DCont">
							</div>
						</div>
					</td>
					<td class="rm"></td>
				</tr>
				<tr>
					<td class="bl"></td>
					<td class="bm"></td>
					<td class="br"></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php
$lefthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_index.view.php' );
?>
<script src="./js/iweibo/library.js"></script>
<script src="./js/iweibo/inbox.js"></script>
