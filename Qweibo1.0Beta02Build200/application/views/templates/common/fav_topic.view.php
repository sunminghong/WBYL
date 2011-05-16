<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-29
 * Filename: fav_topic.view.php
 * Description: 订阅话题
******************************************************************************/
?>
<script type="text/javascript" src="./js/favTopic.js"></script>
<div class="toggle" id="toggledingyue" title="我的订阅">我的订阅<a class="icon up" title="收起" href="javascript:void(0);"></a></div>
<?php
	if( isset($dingyue) && is_array($dingyue) && count($dingyue) > 0 ){
		$dingyue_count = count($dingyue);//截取前10条之前的数据，如第11条存在说明有更多按钮		
		require_once pathJoin( TEMPLATE_DIR,'common','fav_topic_list.view.php' );
		if( $dingyue_count > 10 ){//输出第一屏页面，后台会尽量去拉11条数据，如第11条数据存在则显示查看更多按钮
			echo "<div id=\"viewmorefavtopicwrapper\">";
			echo "<a href=\"javascript:;\" class=\"viewmorefavtopic\" id=\"viewmorefavtopic\">查看更多<span class=\"textarrow\">↓</span></a>";
			echo "<a href=\"javascript:;\" class=\"viewmorefavtopic\" id=\"collapsefavtopic\" style=\"display:none;\">收起<span class=\"textarrow\">↑</span></a>";
			echo "</div>";
		}
	}else{
		echo "<div id=\"dingyue\" style=\"margin-left:17px;\">没有订阅话题。</div>";	
	}
?>
