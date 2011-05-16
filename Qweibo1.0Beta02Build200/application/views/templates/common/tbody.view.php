<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-11-25 19:21
 * Filename: tbody.view.php
 * Description: 消息体模板
 * Sample: 单条消息格式 
 * Comment: 请不要移除任何标签和标签中的任何属性。
 *<ul class="tmain">	
 *	<li class="tmessage">
 * 		<div class="ttouxiang">
 * 			<a href="#"><img src="/style/images/default_head_small.png"></img></a>
 * 		</div>
 * 		<div class="tbody">
 * 			<a href="#">昵称</a><span class="renzheng"></span><span class="shouji"></span><span class="zhuanbo">转播</span>:&nbsp;<a href="#">话题</a>内容内容内容内容内容内容内容内容内容内容内容内容
 * 			sdl史史莱克低价房是死定了福建师大发史莱克低价房是死定了福建师大发史莱克低价房是死定了福建师大发莱克低价房是死定了福建师大发
 * 			<a href="#" class="tupian"><img src="/style/images/t_tupian.gif"></img></a>
 * 			<div class="tyinyong">
 * 					<a href="#">昵称</a><span class="renzheng"></span><span class="shouji"></span>:&nbsp;刚刚听到朋友说我在新浪、网易都有微博！其实都是假的！我的微博现在只在腾讯！只是证明下！哈哈！一路平安啦！
 * 					<a href="#" class="tupian"><img src="/style/images/t_tupian.gif"></img></a>
 * 					<div class="tbottomleft"><a href="#">今天&nbsp;14:50</a>&nbsp;<a href="#">来自&nbsp;iPhone</a>&nbsp;<a class="chakan" href="#">查看转播和点评(<b>14</b>)</a></div>
 * 			</div>
 * 			<div class="tbottom">
 * 				<div class="tbottomleft"><a href="#">今天&nbsp;14:50</a>&nbsp;<a href="#">来自&nbsp;iPhone</a>&nbsp;<a class="chakan" href="#">查看转播和点评(<b>14</b>)</a></div>
 * 				<div class="tbottomright"><a href="#">转播</a>|<a href="#">点评</a>|<a href="#">对话</a>|<a href="#" id="shoucang" class="shoucang nohave"></a></div>
 * 			</div>
 * 		</div>
 *  </li>
 *</ul>
 * Type:微博类型 1-原创发表、2-转载、3-私信 4-回复(对话) 5-空回 6-提及
 * 1,2,4 tbody已判断
 * 3 页面单独处理
 * 5,6 未处理
******************************************************************************/
auto($t);
auto($hasnext);
?>
<?php
	if( isset($t) && is_array($t) && count($t) > 0 ){
		echo "<ul class=\"tmain hasnext\" id=\"".(string)$hasnext."\" >";
		foreach($t as $_t){
			if(!count($_t)>0){//过滤数组为空的广播
				continue;
			}
			$__t = null;//转播帖子
			if( array_key_exists("source",$_t) && is_array($_t["source"]) ){//是否有转播内容	
				$__t = $_t["source"];
			};
			echo "<li class=\"tmessage\" id=\"".$_t["id"]."\">";
			echo "<div class=\"extra\"></div>";
			echo "<div class=\"ttouxiang\">";
			echo "<a href=\"./index.php?m=guest&u=".$_t["name"]."\" title=\"".$_t["nick"]."(@".$_t["name"].")"."\"><img src=\"".(strlen($_t["head"]) > 0&&$_t["head"]!="http://app.qlogo.cn/50" ?str_replace("http://app.qlogo.cn/120","./style/images/default_head_120.jpg",$_t["head"]):"./style/images/".($_GET["m"]=="showt"?"default_head_120.jpg":"default_head_small.png"))."\"></img></a>";
			echo "</div>";
			echo "<div class=\"tbody\">";
			echo "<a class=\"tname\" id=\"".$_t["name"]."\" href=\"./index.php?m=guest&u=".$_t["name"]."\">".$_t["nick"]."</a>";
			echo ( array_key_exists("isvip",$_t) && $_t["isvip"] ) ? "<span class=\"renzheng\"></span>" : ""; 
			echo ( array_key_exists("frommobile",$_t) && $_t["frommobile"] ) ? "<span class=\"shouji\"></span>" : ""; 
			echo ( isset($__t)&& $_t["type"]=="2") ? "<span class=\"zhuanbo\">转播</span>" : "";
			echo ( isset($__t)&& $_t["type"]=="7") ? "<span class=\"zhuanbo\">点评</span>" : "";
			echo ( isset($__t)&& $_t["type"]=="4") ? "<span class=\"zhuanbo\">对</span><a href=\"./index.php?m=guest&u=".$__t["name"]."\">".$__t["nick"]."</a>".(( array_key_exists("isvip",$__t) && $__t["isvip"] ) ? "<span class=\"renzheng\"></span>" : "")."<span class=\"zhuanbo\">说</span>" : "";
			echo "<span style=\"display:inline-block;\">:&nbsp;</span><span id=\"tbodytext\">".$_t["text"]."</span>";
			if( !isset($__t) ){//只显示原帖图片
				//显示图片链接
				//echo ( array_key_exists("ttupian",$_t) && $_t["ttupian"] ) ? "<a href=\"#\" class=\"tupianlink\"><img src=\"/style/images/t_tupian.gif\"></img></a>" : "";
				//显示图片
				if( array_key_exists("image",$_t) && $_t["image"] ){
					echo "<div class=\"ttupian\"><div class=\"loading hide\"><div class=\"loadingicon\"></div></div><img src=\"".$_t["image"]."\"></img></div>";
				}
			}
			if( isset($__t) && ($_t["type"]=="2" || $_t["type"]=="7") ){
				echo "<div class=\"tyinyong\" data-innerid=\"".$__t["id"]."\">";
				echo "<a href=\"./index.php?m=guest&u=".$__t["name"]."\">".$__t["nick"]."</a>";
				echo ( array_key_exists("isvip",$__t) && $__t["isvip"] ) ? "<span class=\"renzheng\"></span>" : ""; 
				echo ( array_key_exists("frommobile",$__t) && $__t["frommobile"] ) ? "<span class=\"shouji\"></span>" : ""; 
				echo ":&nbsp;".$__t["text"];
				//显示图片链接
				//echo (array_key_exists("ttupian",$__t) && $__t["ttupian"]) ? "<a href=\"#\" class=\"tupianlink\"><img src=\"/style/images/t_tupian.gif\"></img></a>" : "";
				//显示图片
				if( array_key_exists("image",$__t) && $__t["image"] ){
					echo "<div class=\"ttupian\"><div class=\"loading hide\"><div class=\"loadingicon\"></div></div><img src=\"".$__t["image"]."\"></img></div>";
				}
				echo "<div class=\"tbottomleft\"><a class=\"time\" id=\"{$__t["timestamp"]}\" href=\"./index.php?m=showt&tid=".$__t["id"]."\">".$__t["timestring"]."</a>"."&nbsp;来自".$__t["from"];
				if( array_key_exists("count",$__t) && array_key_exists("mcount",$__t) && ($__t["count"]+$__t["mcount"]) > 0 ){//只显示原帖的查看转播按钮
					echo "&nbsp;<a id=\"chakan\" class=\"taction\" href=\"javascript:;\">查看转播和点评(<b>".($__t["count"]/*转播数*/+$__t["mcount"]/*点评数*/)."</b>)</a>";
				}
				echo "</div></div>";
			}
			echo "<div class=\"tbottom\">";
			echo "<div class=\"tbottomleft\"><a class=\"time\" id=\"{$_t["timestamp"]}\" ".(array_key_exists("favtimestamp",$_t)&&!empty($_t["favtimestamp"])?"data-favtime=\"".$_t["favtimestamp"]."\"":"")." href=\"./index.php?m=showt&tid=".$_t["id"]."\">".$_t["timestring"]."</a>"."&nbsp;来自".$_t["from"];
			echo ($_t["type"]=="4")?"&nbsp<a class=\"chakanduihua\" href=\"./index.php?m=showdialog&tid=".$_t["id"]."\">查看对话</a>":"";
			if( !isset($__t) && array_key_exists("count",$_t) && array_key_exists("mcount",$_t) && ($_t["count"]+$_t["mcount"]) > 0 ){//只显示原帖的查看转播按钮
			 	echo "&nbsp;<a id=\"chakan\" class=\"taction\" href=\"javascript:;\">查看转播和点评(<b>".($_t["count"]/*转播数*/+$_t["mcount"]/*点评数*/)."</b>)</a>";
			}
			echo "</div>";
			echo "<div class=\"tbottomright\"><a id=\"zhuanbo\" class=\"taction\" href=\"javascript:;\">转播</a><span class=\"tactionsp\">|</span><a id=\"dianping\" class=\"taction\" href=\"javascript:;\">点评</a><span class=\"tactionsp\">|</span><a id=\""
				.($_t["name"]==$user["name"]?"shanchu":"duihua")."\" class=\"taction\" href=\"javascript:;\">".($_t["name"]==$user["name"]?"删除":"对话")."</a><span class=\"tactionsp\">|</span><a href=\"javascript:;\" title=\"".(array_key_exists("isfav",$_t)&&$_t["isfav"]?"取消收藏":"收藏")."\" id=\"shoucang\" class=\"taction shoucang "
				.(array_key_exists("isfav",$_t)&&$_t["isfav"]?"have":"nohave")."\"></a></div>";
			echo "</div></div></li>";
		}
		echo "</ul>";
	}
?>
