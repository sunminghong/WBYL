<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 18:31
 * Filename: user_mail_inbox.view.php
 * Extends: base_index.view.php
 * Description: 私信收件箱页模板
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
			<ul class="tabs"><li class="active tab82x33">收件箱</li><li class="tab82x33"><a href="./index.php?m=sendbox">发件箱</a></li></ul>
			<div class="sendmailwrapper"><span><?php if( isset($boxnum) ){ ?>收件箱共有<?php echo $boxnum;?>封信<?php } ?></span><a class="sendmail" href="#">发私信</a></div>
		</div>
	</div>
	<div id="mailinbox">
		<?php
			if( isset($box) && is_array($box) ){
				echo "<ul class=\"mailmain\">";
				foreach( $box as $t ){
					echo "<li class=\"mailcell\">";
					echo "<div class=\"mailtouxiang\"><a title=\"".$t["nick"]."(@".$t["name"].")"."\" href=\"./index.php?m=guest&u=".$t["name"]."\"><img alt=\"".$t["nick"]."的头像\" src=\"".( (array_key_exists("head",$t) && strlen($t["head"]) > 0) ? $t["head"]:"./style/images/default_head_small.png")."\"></img></a></div>";
					echo "<div class=\"mailbody\">";
					echo "<div><a href=\"./index.php?m=guest&u=".$t["name"]."\" class=\"nickname\">".$t["nick"]."</a>";
					echo ( $t["isvip"] ) ? "<span class=\"renzheng\"></span>" : ""; 
					echo "<span class=\"comma\">:</span></div>";
					echo "<div class=\"breakword breakall\">".$t["text"]."</div>"; 
					echo "<div class=\"bodybottom\">".$t["timestring"]."</div></div>";
					echo "<div class=\"mailaction\"><a class=\"sendmail smaller first\" href=\"javascript:;\" data-username=\"{$t["name"]}\">回信</a><a href=\"javascript:;\" class=\"delmail smaller last\" id=\"{$t["id"]}\">删除</a></div>";
					echo "</li>";
				}
				echo "</ul>";
			}else{
				echo "<div style=\"text-align:center;padding:20px;font-size:14px;font-weight:bold;\">暂时还没有人发私信给你</div>";
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
<input type="hidden" id="boxType" value="inbox" />
<script src="./js/iweibo/library.js"></script>
<script src="./js/iweibo/inbox.js"></script>
