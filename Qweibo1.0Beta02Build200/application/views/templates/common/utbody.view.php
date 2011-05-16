<?php
/******************************************************************************
 * Author: michal
 * Filename: utbody.view.php
 * Description: 广播大厅推荐广播的消息体,ubody和tbody的综合体
******************************************************************************/
auto($ut);
?>
<ul class="umain">
	<?php foreach( $ut as $_ut ){ ?>
		<?php 
			$_sexstr = "";
			if( $_ut["isent"] ){
				$sexstr = "它";
			}else{
				$sexstr = ($_ut["sex"] == 1) ? "他":"她";
			}
		?>
		<li class="umessage" id="<?php echo $_ut["t"]["id"];?>">
			<div class="utouxiang">
 				<a class="head" href="./index.php?m=guest&u=<?php echo $_ut["t"]["name"];?>" title="<?php echo $_ut["t"]["nick"];?>(@<?php echo $_ut["t"]["name"]?>)"><img src="<?php echo ( array_key_exists("head",$_ut["t"]) && !empty($_ut["t"]["head"])&&$_ut["head"]!="http://app.qlogo.cn/50") ? $_ut["t"]["head"]:"./style/images/default_head_small.png"; ?>"></img></a>
				<?php if( $_ut["t"]["name"] != $user["name"] ){//推荐的广播中有自己的广播不显示收听按钮 ?>
 					<a class="flowactionsmall tocenter usebtns <?php echo $_ut["ismyidol"] ? "quxiao":"shouting"; ?>" href="javascript:;" data-username="<?php echo $_ut["t"]["name"];?>"></a>
 				<?php } ?>
 			</div>
 			<div class="ubody">
 				<div class="utop cutoverflow">
 					<div class="toleft utopleft">
 						<a href="./index.php?m=guest&u=<?php echo $_ut["t"]["name"];?>"><?php echo $_ut["t"]["nick"];?></a>&nbsp;<span class="gray smallfont"><?php if($_ut["t"]["isvip"]){?><span class="renzheng"></span><?php }?>(@<?php echo $_ut["t"]["name"];?>)</span>
 						<?php 
 							//所在地
 							//echo "<span class=\"gray smallfont\" style=\"display:block;clear:both;\">".(!empty($_ut["t"]["location"])?$sexstr."在".$_ut["t"]["location"]:"&nbsp;")."</span>"; 
 						?>
 					</div>
 				</div>
 				<div class="clearall usebtns ubodycontent">
 					<?php 
 					/*	echo "<div class=\"smallfont gray\">最近广播&nbsp;17分钟前&nbsp;来自QQ空间</div>";
 						echo "<div class=\"fs14 inkblue\">";
 						echo $_ut["t"]["text"];
	 					if( !empty( $_ut["t"]["image"] ) ) { 
	 						echo "<div class=\"ttupian\"><img src=\"".$_ut["t"]["image"]."\"></div>";
	 					} 
	 					echo "</div>";
	 				*/
 					echo limitHTML($_ut["t"]["text"].(empty( $_ut["t"]["image"])?"":"<span class=\"inkblue\">[图片]</span>"),60);
 					?>
 					<div class="smallfont gray">
 						<a class="time gray" id="<?php echo $_ut["t"]["timestamp"];?>" href="./index.php?m=showt&amp;tid=<?php echo $_ut["t"]["id"]; ?>"><?php echo $_ut["t"]["timestring"];?></a>&nbsp;来自<?php echo $_ut["t"]["from"]; ?>&nbsp;
 						<?php 
						/*	
						 	if( array_key_exists("count",$_ut["t"]) && $_ut["t"]["count"] > 0 ){
	 							echo "<a id=\"chakan\" class=\"taction\" href=\"javascript:;\">查看转播和点评(<b>".$_ut["t"]["count"]."</b>)</a>";
	 						} 
	 					*/
 						?>
					</div>
				</div>
 			</div>
		</li>
	<?php } ?>
</ul>