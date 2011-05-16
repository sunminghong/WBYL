<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-29
 * Filename: nextscreen.view.php
 * Description: 下一屏模块
 * <div class="pagerwrapper">
 * 	<div class="nextscreen">
 * 		<div class="nextscreenleft">
 * 			<div class="loading hide"></div>
 * 			<a class="stext" id="more" href="javascript:void(0);"></a>
 * 		</div>
 * 		<a class="nextscreenright" href="#">
 * 			<div class="stext"></div>
 * 		</a>
 * 	</div>
 * </div>
******************************************************************************/
?>
<?php if( isset($hasnext) && $hasnext==0 ) {?>
<div class="pagerwrapper">
	<div class="nextscreen">
		<div class="loading hide"></div>
		<a class="stext" id="more" href="javascript:void(0);"></a>
	</div>
</div>
<?php } ?>