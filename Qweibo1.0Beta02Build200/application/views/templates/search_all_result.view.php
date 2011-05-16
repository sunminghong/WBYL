<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-03 00:27
 * Filename: search_all_result.view.php
 * Extends: base_search.view.php
 * Description: 综合搜索结果页
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

<?php 
$searchextrahead = ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="searchcontainer">
	<div class="searchtitle"><span class="title">综合搜索结果</span></div>
	<div class="searchbox">
		<form name="searchbox" id="searchbox" class="searchbox" method="get" action="./index.php">
			<fieldset class="searchField">
					<input type="hidden" name="m" value="searchall">
					<div class="keytextwrap usebtns">
					<input type="text" class="keytext" maxlength="50" name="k" style="background-position: 0px 0px;" value="<?php echo $searchkey?$searchkey:"";?>">
					</div>
					<input class="searchboxsubmit" type="submit" value="搜索"></button>
			</fieldset>
		</form>
	</div>	
	<div class="searchbar">
		<ul class="searchselector">
			<li class="active">综合</li>
			<li><a href="./index.php?m=searchuser&k=<?php echo urlencode($searchkey);?>">用户</a></li>
			<li class="last"><a href="./index.php?m=searcht&k=<?php echo urlencode($searchkey);?>">广播</a></li>
		</ul>
	</div>
	<div class="yonghutitle">用户<?php echo $unum;?>位
		<?php if( $unum > 0 ){ ?>
		<a href="./index.php?m=searchuser&k=<?php echo urlencode($searchkey);?>">查看全部</a>
		<?php } ?>
	</div>
	<?php if( isset($u) && is_array($u) ){ ?>
		<ul class="yonghulist">
		<?php foreach( $u as $y ){?>
			<li>
				<div class="headmodel">
					<a class="touxiang" href="./index.php?m=guest&u=<?php echo strip_tags($y["name"])?>" title="<?php echo strip_tags($y["nick"])."(@".strip_tags($y["name"]).")";?>">
					<?php if( array_key_exists("head",$y) && strlen($y["head"]) > 0 && $y["head"]!="http://app.qlogo.cn/50" ){ ?>
					<img src="<?php echo $y["head"];?>"></img>
					<?php }else{ ?>
					<img src="./style/images/default_head_small.png"></img>
					<?php } ?>
					</a>
					<a class="flowactionsmall tocenter usebtns <?php echo $y["ismyidol"] ? "quxiao":"shouting" ?>" href="javascript:;" data-username="<?php echo strip_tags($y["name"]);?>"></a>
				</div>
				<div class="xinxi">
				<div class="nick"><a href="./index.php?m=guest&u=<?php echo strip_tags($y["name"])?>"><?php echo $y["nick"];?></a>
				<?php if( array_key_exists("isvip",$y) && $y["isvip"] ){ ?>
					<span class="renzheng"></span> 
				<?php } ?>
				</div>
				<div class="name"><?php echo $y["name"];?></div>
				<div class="info">听众<a href="./index.php?m=follower&u=<?php echo strip_tags($y["name"]);?>" id="fanscount"><?php echo $y["fansnum"];?></a>人&nbsp;<?php echo getSexDesc($y);?>收听<a href="./index.php?m=following&u=<?php echo strip_tags($y["name"]);?>" id="idolcount"><?php echo $y["idolnum"];?></a>人</div>
				</div>
			</li>
		<?php } ?>
		</ul>
	<?php }else{ ?>
		<div style="padding-left:10px;font-size:14px;">没有找到与<span class="warm">"<?php echo $searchkey;?>"</span>相关的用户</div>
	<?php } ?>
	<?php 
		if( !isset($tnum) ){
			$tnum = 0;
		}
	?>
	<div class="guangbotitle">广播<?php echo $tnum;?>条
	<?php if( $tnum > 0 ){ ?>
		<a href="./index.php?m=searcht&k=<?php echo urlencode($searchkey);?>">查看全部</a>
	<?php } ?>
	</div>
	<?php
		if( $tnum > 0 ){
			require pathJoin( TEMPLATE_DIR,'common','tbody.view.php' ); 
		}else{
			echo "<div style=\"padding-left:10px;font-size:14px;\">没有找到与<span class=\"warm\">\"".$searchkey."\"</span>相关的广播</div>";
		}
	?>
</div>
<?php require pathJoin( TEMPLATE_DIR,'common','pagination.view.php' ); ?>
<?php
$lefthtml = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_search.view.php' );
?>
