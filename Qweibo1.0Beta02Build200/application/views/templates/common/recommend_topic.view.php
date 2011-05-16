<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 19:51
 * Filename: recommend_topic.view.php
 * Description: 热门话题模板
******************************************************************************/
?>
<?php 
		echo "<div class=\"toggle\" id=\"togglehuati\" title=\"";
		if( !empty($moduleconfig["remenhuati"]["name"]) ){
			echo $moduleconfig["remenhuati"]["name"];
		}else{
			echo "热门话题";
		}
		echo "\">";
		if( !empty($moduleconfig["remenhuati"]["name"]) ){
			if(mb_strlen($moduleconfig["remenhuati"]["name"],"utf-8") > 9){
				echo limit($moduleconfig["remenhuati"]["name"],8);
			}else{
				echo limit($moduleconfig["remenhuati"]["name"],9);
			}
		}else{
			echo "热门话题";
		}
		echo "<a class=\"icon up\" title=\"收起\" href=\"javascript:void(0);\"></a>";
		echo "</div>";
		if( is_array($moduleconfig["remenhuati"]["data"]) && count($moduleconfig["remenhuati"]["data"]) > 0 ){
			echo "<ul class=\"huatilist\" id=\"huati\">";
			foreach($moduleconfig["remenhuati"]["data"] as $_h){
				if( is_array( $_h ) && array_key_exists("name",$_h) ){
					echo "<li><a href=\"./index.php?m=topic&k=".urlencode($_h["name"])."\">".limit($_h["name"],9);
					if (!empty($_h["count"])) {
						echo "<span>(".$_h["count"].")</span>";
					}//end if
					echo "</a></li>";
				}//end if
			}//end for
			echo "</ul>";
		}else{
			echo "<div id=\"huati\" style=\"margin-left:17px;\">暂时没有";
			if( !empty($moduleconfig["remenhuati"]["name"]) ){
				echo $moduleconfig["remenhuati"]["name"];
			}else{
				echo "热门话题";
			}
			echo "</div>";
		}
?>
