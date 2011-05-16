<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 16:35
 * Filename: baseindexnew.view.php
 * Extends: base_2col.view.php
 * Description: 首页基础模板
 * Comments: 已内置话题列表收起/展开效果，继承它的子页面不应再次实现。
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * 新声明的变量:
 * $baseindexextrahead 在首页基础模板head标签中插入数据，不设置则不插入任何数据
 * $screenuser 首页右侧边栏用户信息栏，必须设置
 * $highlightmenu 高亮菜单索引，必须设置
 * $huati 热门话题列表，不设置则显示 "没有热门话题"
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$lefthtml,$righthtml
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
 * 已使用的变量:
 * $extrahead,$righthtml
 * 子模板可用变量
 * $title,$user,$sitename,$lang,$arrowPosition,$lefthtml
 * $baseindexextrahead,$screenuser,$highlightmenu,$huati
******************************************************************************/
auto($baseindexextrahead);
required($screenuser,"screenuser",__FILE__);
optional($highlightmenu,-1);//不高亮任何菜单
optional($lang,"zh-cn");
auto($huati);
auto($updateinfo);//更新数量信息
?>
<?php
//插入脚本
ob_start();
?>
<?php
if(isset($baseindexextrahead)){
	echo $baseindexextrahead;
}
?>
<script>
$(function(){
	$.getScript("./js/updateInfo.js");
});
</script>
<?php
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();//print_r($screenuser);
?>
<div class="profile">
	<a class="head" href="./index.php?m=userhead" title="<?php echo "{$screenuser["nick"]}(@{$screenuser["name"]})";?>">
		<?php if( is_array($screenuser) && array_key_exists("head",$screenuser) && $screenuser["head"] && strlen($screenuser["head"]) > 0 ) { ?>
			<img src="<?php echo $screenuser["head"];?>" width=100 height=100/>
		<?php }else{ ?>
		<img src="./style/lang/<?php echo $lang;?>/images/default_head.png" width="100" height="100"/>
		<?php } ?>
	</a>
	<div class="info">
	<?php if(isset($updateinfo) && $updateinfo["fans"] > 0 ){ ?>
	<a href="./index.php?m=follower&u=<?php echo $screenuser["name"];?>" class="newfans" id="newfans"><div class="rightbg"><span id="newfanscount"><?php echo $updateinfo["fans"];?></span></div></a>
	<?php }else{ ?>
	<a href="./index.php?m=follower&u=<?php echo $screenuser["name"];?>" class="newfans" id="newfans" style="display:none;"><div class="rightbg"><span id="newfanscount"></span></div></a>
	<?php }?>
	<span>听众</span><a class="usehovereffect" href="./index.php?m=follower&u=<?php echo $screenuser["name"];?>"><?php echo $screenuser["fansnum"];?></a>
	<span>收听</span><a class="usehovereffect" href="./index.php?m=following&u=<?php echo $screenuser["name"];?>"><?php echo $screenuser["idolnum"];?></a>
	<span class="last">广播</span><a class="usehovereffect" href="./index.php?m=mine"><?php echo $screenuser["tweetnum"];?></a>
	</div>
	<div class="namelink">
		<span><?php echo $screenuser["nick"].(array_key_exists("isvip",$screenuser)&&$screenuser["isvip"]==1?"<small class=\"renzheng\" title=\"腾讯认证\"></small>":"");?></span>
		<a href="./index.php?m=index" title="<?php echo "http://".$_SERVER['SERVER_NAME'].str_replace("index.php","",$_SERVER['PHP_SELF'])."?".$screenuser["name"];?>"><?php echo limit("http://".$_SERVER['SERVER_NAME'].str_replace("index.php","",$_SERVER['PHP_SELF'])."?".$screenuser["name"],14);?></a>
	</div>
</div>
<div class="rightsp" ></div>
<div class="menus">
	<div class="menu <?php echo $highlightmenu===0?"active":"inactive";?>"><b style="background-position:0px 0px;"></b><a href="./index.php?m=index">我的主页</a></div>
	<div class="menusp"></div>
	<div class="menu <?php echo $highlightmenu===1?"active":"inactive";?>"><b style="background-position:-16px 0px;"></b><a href="./index.php?m=mine">我的广播</a></div>
	<div class="menusp"></div>
	<div class="menu <?php echo $highlightmenu===2?"active":"inactive";?>"><b style="background-position:-32px 0px;"></b><a href="./index.php?m=at">提到我的
	<?php if(isset($updateinfo) && $updateinfo["mentions"] > 0 ){ ?>
		<span class="menuhint">(<span id="newmentioncount"><?php echo $updateinfo["mentions"];?></span>)</span>
	<?php }else{ ?>
		<span class="menuhint hide">(<span id="newmentioncount"></span>)</span>
	<?php }?>
	</a></div>
	<div class="menusp"></div>
	<div class="menu <?php echo $highlightmenu===3?"active":"inactive";?>"><b style="background-position:-48px 0px;"></b><a href="./index.php?m=favor">我的收藏</a></div>
	<div class="menusp"></div>
	<div class="menu <?php echo $highlightmenu===4?"active":"inactive";?>"><b style="background-position:-64px 0px;"></b><a href="./index.php?m=inbox">私信
	<?php if(isset($updateinfo) && $updateinfo["private"] > 0 ){ ?>
		<span class="menuhint">(<span id="newmailcount"><?php echo $updateinfo["private"];?></span>)</span>
	<?php }else{ ?>
		<span class="menuhint hide">(<span id="newmailcount"></span>)</span>
	<?php }?>
	</a></div>
</div>
<?php
	if( array_key_exists("remenhuati",(array)$moduleconfig)){
		echo "<div class=\"rightsp\"></div>";
		require pathJoin( TEMPLATE_DIR,'common','recommend_topic.view.php' ); 
	}
?>
<div class="rightsp" ></div>
<?php 
	require pathJoin( TEMPLATE_DIR,'common','fav_topic.view.php' ); 
?>
<div class="rightsp" ></div>
<?php
$righthtml = ob_get_contents();
ob_end_clean();
?>
<?php
require_once pathJoin( TEMPLATE_DIR,'base_2col.view.php' );
?>
