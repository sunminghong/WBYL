<?php
require_once '../config/sys_config.php';
require_once('inc/admin_head.php');
require_once MB_COMM_DIR.'/log.class.php';
require_once MB_COMM_DIR.'/util.class.php';
require_once MB_APP_DIR.'/global.class.php';
require_once MB_CTRL_DIR.'/base_mod.class.php';
require_once MB_COMM_DIR.'/exception.class.php';

try{
	$logger = new MBLog($mbConfig["log_path"], 'admin');
	MBGlobal::setLogger($logger);
	$base = new BaseMod();
	$base->checkLogin();
	$api = MBGlobal::getApiClient();
}catch(MBException $e){
	header('Location: error.php?e=10000');
}

require_once('inc/admin_headhtml.php');
?>
	<div class="rms">
		<div class="menu">
		<?php
			$cmenu=array("关键字屏蔽","屏蔽微博","屏蔽点评");
			$cindex=(int) $_GET['cid'];
			$p=(int) $_GET['p'];
			$kp = (int) $_GET['kp'];
			$kw= $_GET['k'];
			$cstr="";
			foreach($cmenu as $k => $itemValue)
			{
				if($cindex==$k){
					$cstr.="<span>".$itemValue."</span> | ";
				}else{
					$cstr.='<a href="admin_filter.php?cid='.$k.'">'.$itemValue.'</a> | ';
				}
			}
			echo $cstr;
		?>
		</div>
		<div class="mainB">
		<?php
			switch($cindex){
				case 0:
					require_once('filter/keyword.php');
					break;
				case 1:
					require_once('filter/t.php');	
					break;
				case 2:
					require_once('filter/repost.php');	
					break;
				default:
					require_once('filter/keyword.php');
					break;
			}
		?>
		</div>
	</div>
</div>
<?php
	require_once('inc/admin_footer.php');
?>

