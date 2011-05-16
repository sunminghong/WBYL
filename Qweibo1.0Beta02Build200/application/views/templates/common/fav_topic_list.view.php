<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-29
 * Filename: fav_topic.view.php
 * Description: 订阅话题列表
******************************************************************************/
?>
<?php
		echo "<ul class=\"huatilist dingyue\" id=\"dingyue\" ";
		if( count($dingyue) > 10 ){//有第11条数据存在，标记此DOM为有数据可拉
			echo "data-hasnext=\"1\"";
		}else{
			echo "data-hasnext=\"0\"";
		}
		echo ">";
		$dingyue = array_slice($dingyue,0,10);//取前10条数据输出
		foreach($dingyue as $_d){
			if( is_array($_d) && array_key_exists("name",$_d) && !empty($_d["name"]) ){
				echo "<li data-id=\"".$_d["id"]."\" data-timestamp=\"".$_d["timestamp"]."\"><a class=\"huati\" href=\"./index.php?m=topic&k=".MBUtil::urlEncode($_d["name"])."\" title=\"".$_d["name"]."\">".limit($_d["name"],9);
				if (!empty($_d["count"])) {
					echo "<span>(".$_d["count"].")</span>";
				}
				echo "</a>";
				echo "<a class=\"usebtns huatidel hide\" id=\"".$_d["id"]."\" href=\"javascript:;\" title=\"取消订阅\"></a>";
				echo "</li>";
			}
		}
		echo "</ul>";
?>
