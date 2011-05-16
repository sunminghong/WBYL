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
	require_once('inc/admin_headhtml.php');
}catch(MBException $e){
	header('Location: error.php?e=10000');
}
?>
	<div class="rms">
		<div class="menu">
		<?php
			//$cmenu=array("推荐话题定制","推荐用户定制","热门转播管理","热门话题管理","热门用户管理","新注册用户推荐","模块启用设置");
			$cmenu=array("最热话题定制","热门话题排行榜定制","用户推荐定制","模块启用设置");
			$cindex=(int) $_GET['cid'];
			$p=(int) $_GET['p'];
			$cstr="";
			foreach($cmenu as $k => $itemValue)
			{
				if($cindex==$k){
					$cstr.="<span>".$itemValue."</span> | ";
				}else{
					$cstr.='<a href="admin_recomm.php?cid='.$k.'">'.$itemValue.'</a> | ';
				}
			}
			echo $cstr;
		?>
		</div>
		<div class="mainB">
		<?php
			switch($cindex){
				case 0:
					require_once('recomm/ht.php');
					break;
				case 1:
					require_once('recomm/htopic.php');	
					break;
				case 2:
					require_once('recomm/ruser.php');	
					break;
				case 3:
					require_once('recomm/mod.php');	
					break;
				default:
					require_once('recomm/ht.php');
					break;
			}
		?>
		</div>
	</div>
</div>
<?php
	require_once('inc/admin_footer.php');
?>
