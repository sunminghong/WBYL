<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-09 01:22
 * Filename: guest_timeline.view.php
 * Description: 查看他人广播
 * Extends: base_guest.view.php
******************************************************************************/
ob_start();
$_sexdescription = getSexDesc($screenuser);
?>
	<div class="messagelist">
	<div class="messagelisthead"><b class="guangbo icon">&nbsp;</b><b class="guangbo text"><?php echo $screenuser["nick"]?>说</b></div>
	<?php
		if( isset($t) && is_array($t) && count($t) > 0 ){
			require pathJoin( TEMPLATE_DIR,'common','tbody.view.php' ); 
		}else{
			echo "<div class=\"bold fs14\" style=\"text-align:center;margin-top:20px;height:660px;\">";
			echo getSexDesc($screenuser)."暂时还没有发表过广播";
			echo "</div>";
		}
	?>
	</div>
<?php require pathJoin( TEMPLATE_DIR,'common','nextscreen.view.php' );  ?>
<?php
$chakantarenbottom = ob_get_contents();
ob_end_clean();
require_once pathJoin( TEMPLATE_DIR,'base_guest.view.php' );
?>