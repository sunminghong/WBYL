<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-06 16:40
 * Filename: chakantaren.view.php
 * Extends: base_2col.view.php
 * Description: 查看他人页面模板
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition
 * 新声明的变量:
 * $chakantarenbottom
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * $chakantarenbottom
 * 已使用的变量:
 * $extrahead,$lefthtml,$righthtml
 * 子模板可用变量:
 * $title,$user,$sitename,$lang,$arrowPosition
 * $chakantarenbottom
******************************************************************************/
ob_start();
?>
<script>var pagename = "tarenguangbo";var screenusername="<?php echo $screenuser["name"]?>";</script>
<script type="text/javascript" src="./js/wbfuncs.js"></script>
<script type="text/javascript">
$(function(){
	$(".toggle").click(function(){
		var list = $(".headlist");
		list.toggle();
		var arrow = $(this).find("a:last-child");
		arrow.get(0).className = "icon "+( list.css("display")=="none"?"down":"up");
		if(arrow.hasClass("down")){
			arrow.attr("title","展开");
		}else{
			arrow.attr("title","收起");
		}
	});
});
</script>
<style>
.minisendbox#chakan ul.tlist,.minisendboxmain textarea,.minisendboxmain .bottomhtml{width:500px;}
</style>
<?php
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();
//lefthtml
?>
<input type="hidden" id="username" value="<?php echo $screenuser["name"]?>"></input>
<div class="chakantaren">
	<div class="profile">
	<div class="touxiang"><img src="<?php echo ( array_key_exists("head",$screenuser) && !empty($screenuser["head"])&&$screenuser["head"]!='http://app.qlogo.cn/120') ? $screenuser["head"]:"./style/images/default_head_120.jpg"; ?>" alt="<?php echo $screenuser["nick"]."的头像";?>" title="<?php echo $screenuser["nick"]."(@".$screenuser["name"].")"?>"/></div>
		<div class="rightpanel">
		<div><span class="nick"><?php echo $screenuser["nick"]?></span><?php if( $screenuser["isvip"] ){ ?><span class="renzheng"></span><?php } ?><span class="name gray">(@<?php echo $screenuser["name"]?>)</span></div>
		<div class="thelink"><a href="./index.php?m=guest&u=<?php echo $screenuser["name"];?>">http://<?php echo $_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];?>?<?php echo $screenuser["name"]?></a></div>
		<div class="theinfo">广播<a href="./index.php?m=guest&u=<?php echo $screenuser["name"]?>"><?php echo $screenuser["tweetnum"] ?></a>条<span class="theinfosp deepgray">|</span>听众<a href="./index.php?m=follower&u=<?php echo $screenuser["name"]?>" id="follower"><?php echo $screenuser["fansnum"]?></a>人<span class="theinfosp deepgray">|</span><?php echo getSexDesc($screenuser);?>收听<a href="./index.php?m=following&u=<?php echo $screenuser["name"]?>"><?php echo $screenuser["idolnum"]?></a>人</div>
			<div class="theaction cutoverflow">
			<div id="followinfo" class="toleft theleft">
			<?php if( array_key_exists("ismyidol",$screenuser) && $screenuser["ismyidol"] ){ //已收听?>
				<div class="followed">已收听&nbsp;&nbsp;<a id="dounfollow" href="javascript:;" title="取消收听">取消收听</a></div>
			<?php }else{//未收听 ?>
				<a id="dofollow" class="usebtns flowaction nohave" href="javascript:;" title="立即收听"></a>
			<?php } ?>
			</div>
				<div class="toright theright">
					<a href="javascript:void(0);" class="chat first" id="chatBtn" title="对话">对&nbsp;话</a>
					<!--<a href="javascript:void(0);" class="chat" id="mailBtn">私&nbsp;信</a>-->
				</div>
			</div>
		</div>
	</div>
	<?php 
		if(isset($chakantarenbottom)){
			echo $chakantarenbottom;
		} 
	?>
</div>
<?php
$lefthtml = ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="profiledetails">
<div class="detailtitle"><?php echo getSexDesc($screenuser); ?>的资料</div>
	<?php if( !$screenuser["isent"] ){?>
	<div class="detailgenda"><?php echo $screenuser["sex"]==2?"女":"男"; ?></div>
	<?php } ?>
	<div class="detaillocation">现居:&nbsp;&nbsp;<?php echo $screenuser["location"]?></div>
	<div class="detailintro breakword breakall">介绍:&nbsp;&nbsp;<?php echo $screenuser["introduction"] ?></div>
</div>
<?php if( $screenuser["isvip"] && !empty($screenuser["verifyinfo"]) ){  ?>
<div class="verifyinfo"><div class="usebtns verifyinfotitle"></div><?php echo $screenuser["verifyinfo"] ?></div>
<?php } ?>
<div class="rightsp"></div>
<div class="toggle"><?php echo getSexDesc($screenuser); ?>收听<a href="./index.php?m=following&u=<?php echo $screenuser["name"]?>"><?php echo $screenuser["idolnum"]?></a>人<a class="icon up" title="收起" href="javascript:void(0);"></a></div>
<div class="headlist">
	<?php 
		if( array_key_exists("idollist",$screenuser) ){ 
			$idollist_chunk = array_chunk( array_slice($screenuser["idollist"],0,12),3 );
			foreach( $idollist_chunk as $idolgrp ){  
	?>
	<ul class="heads">
		<?php foreach($idolgrp as $idol){ ?>
		<li><a href="./index.php?m=guest&u=<?php echo $idol["name"]?>" title="<?php echo "{$idol["nick"]}(@{$idol["name"]})";?>" class="head"><img src="<?php echo !empty($idol["head"])&&$idol["head"]!="http://app.qlogo.cn/50" ? $idol["head"] : "./style/images/default_head_small.png"; ?>"></img></a><a href="./index.php?m=guest&u=<?php echo $idol["name"]?>" class="text"><?php echo $idol["nick"]?></a></li>
		<?php } ?>
	</ul>
<?php } ?>
<?php if( count($screenuser["idollist"]) > 12 ){ ?>
<div class="clearleft toright h18 top10"><a class="viewall" href="./index.php?m=following&u=<?php echo $screenuser["name"]?>"></a></div>
<?php } ?>
<?php }else{ ?>
 	<?php echo getSexDesc($screenuser); ?>没有收听任何人
<?php } ?>
</div>
<div class="rightsp"></div>
<?php
$righthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_2col.view.php' );
?>
<div class="D">
	<div class="bg"></div>
	<div class="CR" style="width: 500px; margin: -74px 0pt 0pt -250px;">
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
<script src="./js/iweibo/library.js"></script>
<script src="./js/iweibo/guest.js"></script>