<?php
require_once '../config/sys_config.php';
require_once('inc/admin_head.php');
require_once('inc/admin_headhtml.php');
?>
	<div class="rms">
		<div class="menu">
		<?php
			$pro = $_SESSION['pro'];
			if($pro > 1){
				$cmenu=array("修改密码","管理帐号");
			}else{
				$cmenu=array("修改密码");
			}
			$cindex=(int) $_GET['cid'];
			$p=(int) $_GET['p'];
			$cstr="";
			foreach($cmenu as $k => $itemValue)
			{
				if($cindex==$k){
					$cstr.="<span>".$itemValue."</span> | ";
				}else{
					$cstr.='<a href="admin_manage.php?cid='.$k.'">'.$itemValue.'</a> | ';
				}
			}
			echo $cstr;
		?>
		</div>
		<div class="mainB">
		<?php
			switch($cindex){
				case 0:
					require_once('manage/pwd.php');
					break;
				case 1:
					require_once('manage/manage.php');	
					break;
				default:
					require_once('manage/pwd.php');
					break;
			}
		?>
		</div>
	</div>
</div>
<?php
	require_once('inc/admin_footer.php');
?>
