<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-28
 * Filename: pagination.view.php
 * Description: 翻页模块
 * <div class="pager">
 * <a class="previouspage" href="<?php echo $pageinfo["fronturl"]; ?>">&lt;&lt;上一页</a>
 * <a class="nextpage" href="<?php echo $pageinfo["nexturl"]; ?>">下一页&gt;&gt;</a>
 * </div>
******************************************************************************/
?>
<?php if( isset($pageinfo) && ( !empty($pageinfo["fronturl"]) || !empty($pageinfo["nexturl"]) ) ) {?>
<div class="pagerwrapper">
	<div class="pager">
		<?php if( array_key_exists("fronturl",$pageinfo) && !empty($pageinfo["fronturl"]) ){ ?>
		<a class="previouspage" href="<?php echo $pageinfo["fronturl"]; ?>">&lt;&lt;上一页</a>
		<?php } ?>
		<?php if( array_key_exists("nexturl",$pageinfo) && !empty($pageinfo["nexturl"]) ){ ?>
		<a class="nextpage" href="<?php echo $pageinfo["nexturl"]; ?>">下一页&gt;&gt;</a>
		<?php } ?>
	</div>
</div>
<?php } ?>