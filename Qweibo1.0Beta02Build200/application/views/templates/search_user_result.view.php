<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-03 00:27
 * Filename: search_user_result.view.php
 * Extends: base_search.view.php
 * Description: 用户搜索结果页
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition
 * $huati,$lefthtml,$searchextrahead
 * 新声明的变量:
 *
 * 变量列表:
 *
 * 已使用的变量:
 *
 * 子模板可用变量:
******************************************************************************/
ob_start();
?>
<div class="searchcontainer">
	<div class="searchtitle"><span class="title">用户搜索结果</span></div>
	<div class="searchbox">
		<form name="searchbox" id="searchbox" class="searchbox" method="get" action="./index.php">
			<fieldset class="searchField">
					<input type="hidden" name="m" value="searchuser">
					<div class="keytextwrap usebtns">
					<input type="text" class="keytext" maxlength="50" name="k" style="background-position: 0px 0px;" value="<?php echo $searchkey?$searchkey:"";?>">
					</div>
					<input class="searchboxsubmit" type="submit" value="搜索"></button>
			</fieldset>
		</form>
	</div>
	<div class="searchbar">
		<ul class="searchselector">
			<li class="last"><a href="./index.php?m=searchall&k=<?php echo urlencode($searchkey);?>">综合</a></li>
			<li class="active">用户</li>
			<li class="last"><a href="./index.php?m=searcht&k=<?php echo urlencode($searchkey);?>">广播</a></li>
		</ul>
	</div>
	<?php if( isset($unum) && $unum > 0 ){ ?>
		<div class="yonghutitle">用户<?php echo $unum;?>位</div>
		<?php require_once pathJoin(TEMPLATE_DIR,"common","ubody.view.php")?>
	<?php }else{ ?>
		<div style="text-indent:10px;margin-top:12px;font-size:14px;">没有找到与<span class="warm">"<?php echo $searchkey;?>"</span>相关的用户</div>
	<?php } ?>
		
</div>
<?php require pathJoin( TEMPLATE_DIR,'common','pagination.view.php' ); ?>
<?php
$lefthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_search.view.php' );
?>
