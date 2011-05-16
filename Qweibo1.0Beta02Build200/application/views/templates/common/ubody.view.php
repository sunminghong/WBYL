<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-08 16:04
 * Filename: ubody.view.php
 * Description: 用户列表模版
 * Sample: 单条消息格式 
 *
 * <ul class="umain">	
 * 	<li class="umessage">
 * 		<div class="utouxiang">
 *  			<a href="#"><img src="/style/images/default_head_small.png"></img></a>
 *  		</div>
 *  		<div class="ubody">
 *			<div class="utop cutoverflow">
 *				<div class="toleft utopleft"><a href="#">昵称</a><span class="renzheng"></span>&nbsp;<span class="gray smallfont">(@ghuksz)</span></div>
 *				<div class="toright utopright"><a class="usebtns flowaction have" href=""></a></div>
 *			</div>
 *			<div class="clearall usebtns ubodycontent">
 *				<div class="smallfont gray">最近广播 10月26日 23:37 来自网页</div>
 *				<div class="smallfont"><a href="">李宇春范晓萱加盟《龙门飞甲》挑战“富二代”和“发电机” 由徐克和张之亮共同执导的“新概念武侠”3D巨作《龙门飞甲》已于10月10日正式开机。主演阵容除了之前曝光的李连杰、周迅、陈坤、桂纶镁和樊少皇外，同为歌坛偶像级人物的李宇春和范晓萱近日也宣布加盟影片。</a></div>
 *  				<div class="ubottomleft smallfont gray">听众<a class="bold" href="">245801203</a>人&nbsp;&nbsp;&nbsp;&nbsp;她收听<a class="bold" href="#">32</a>人</div>
 *			</div>
 *  		</div>
 *  </li>
 * </ul>
******************************************************************************/
auto($u);
?>
<ul class="umain">
	<?php foreach( $u as $_u ){ ?>
	<li class="umessage" id="n<?php echo strip_tags($_u["name"]) ?>">
		<div class="utouxiang"><a class="head" href="./index.php?m=guest&u=<?php echo strip_tags($_u["name"]) ?>" title="<?php echo $_u["nick"] ?>(@<?php echo strip_tags($_u["name"]) ?>)"><img src="<?php echo ( array_key_exists("head",$_u) && !empty($_u["head"])&&$_u["head"]!="http://app.qlogo.cn/50")? $_u["head"]:"./style/images/default_head_small.png"; ?>"></img></a></div>
		<div class="ubody">
			<div class="utop cutoverflow">
			<div class="toleft utopleft"><a href="./index.php?m=guest&u=<?php echo strip_tags($_u["name"]) ?>"><?php echo $_u["nick"] ?></a><?php if($_u["isvip"]){?><span class="renzheng"></span><?php }?>&nbsp;<span class="gray smallfont">(@<?php echo $_u["name"] ?>)</span></div>
				<div class="toright utopright"><a class="usebtns flowaction <?php echo ($_u["ismyidol"])?"have":"nohave"; ?>" href="javascript:void(0);" title="<?php echo ($_u["ismyidol"])?"取消收听":"立即收听"; ?>" data-username="<?php echo strip_tags($_u["name"]);?>"></a></div>
			</div>
			<div class="clearall usebtns ubodycontent">
			<?php if(array_key_exists("tag",$_u) && !empty($_u["tag"]) && is_array($_u["tag"]) ){ ?>
				<div class="smallfont">
				<?php $_endtag = end($_u["tag"]); ?>
				<?php foreach( $_u["tag"] as $_tag){ ?>
					<?php //<a href="javascript:;"> ?><span class="gray"><?php echo $_tag["name"] ?></span><?php //</a> ?><?php if( $_tag !== $_endtag ){ ?><span class="deepgray">&nbsp;&nbsp;|&nbsp;</span><?php } ?>
				<?php } ?>
				</div>
			<?php } 
				if(is_array($_u["tweet"]) && count($_u["tweet"])>0 && !empty($_u["tweet"][0]["id"])){
			?>
			<div class="smallfont gray">最近广播&nbsp;<?php echo $_u["tweet"][0]["timestring"] ?>&nbsp;来自<?php echo $_u["tweet"][0]["from"] ?></div>
				<div class="smallfont">
						<a href="./index.php?m=guest&u=<?php echo strip_tags($_u["name"]); ?>"><?php echo strip_tags ($_u["tweet"][0]["text"]) ?></a>
				</div>
			<?php } else {?>
				<div class="smallfont">最近没有广播哦。</div>
			<?php }?>
				<div class="ubottomleft smallfont gray">听众<a class="bold follower" href="./index.php?m=follower&u=<?php echo strip_tags($_u["name"]) ?>"><?php echo $_u["fansnum"] ?></a>人&nbsp;&nbsp;&nbsp;&nbsp;<?php //echo getSexDesc($_u); ?>收听<a class="bold following" href="./index.php?m=following&u=<?php echo strip_tags($_u["name"]) ?>"><?php echo $_u["idolnum"] ?></a>人</div>
			</div>
		</div>
	</li>	
	<?php } ?>
</ul>
<script type="text/javascript" src="./js/iweibo/follow.js"></script>
