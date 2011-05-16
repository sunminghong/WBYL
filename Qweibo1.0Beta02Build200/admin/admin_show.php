<?php
require_once '../config/sys_config.php';
require_once('inc/admin_head.php');
require_once('inc/admin_headhtml.php');
?>
	<div class="rms">
		<div class="menu">
		<?php
			$cmenu=array("自定义LOGO","页脚设置","搜索功能设置");
			$cindex=(int) $_GET['cid'];
			$p=(int) $_GET['p'];
			$cstr="";
			foreach($cmenu as $k => $itemValue)
			{
				if($cindex==$k){
					$cstr.="<span>".$itemValue."</span> | ";
				}else{
					$cstr.='<a href="admin_show.php?cid='.$k.'">'.$itemValue.'</a> | ';
				}
			}
			echo $cstr;
		?>
		</div>
		<div class="mainB">
		<?php
			switch($cindex){
				case 0:
					require_once('show/logo.php');
					break;
				case 1:
					require_once('show/page.php');	
					break;
				case 2:
					require_once('show/search.php');	
					break;
				default:
					require_once('show/logo.php');
					break;
			}
		?>
		</div>
	</div>
	<div style="clear:both"></div>
</div>
<?php
	require_once('inc/admin_footer.php');
?>

