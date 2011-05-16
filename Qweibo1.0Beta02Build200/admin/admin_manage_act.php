<?php
session_start();
require_once '../config/sys_config.php';
require_once('conn.php');
require_once('inc/admin_act.php');
$admin = new MBAdminManage($host,$root,$pwd,$db);
$act = $_GET['a'];
$model = $_GET['m'];

$url = $_SERVER['HTTP_REFERER'];
$url =  strstr($url,'?',true);
if($url == ''){
	$url = $_SERVER['HTTP_REFERER'];
}
$turl = 'http://'.$_SERVER['HTTP_HOST'].str_replace("admin_manage_act.php","admin_manage.php",$_SERVER['PHP_SELF']);
if($url != $turl){//echo($url."<br/>".$turl);exit();
	header('Location: error.php?e=11');	
	exit();
}
$_SESSION["bkurl"] = $_SERVER['HTTP_REFERER'];
switch($act){
	case 'pwd':
		$u = $_POST['n'];
		$op = $_POST['oldpwd'];
		$np = $_POST['newpwd'];
		$np1 = $_POST['newpwd1'];

		if($u!='' && $op != '' && $np!= '' && $np1 != ''){
			if($np == $np1){
				if($op != $np){
					$ret = $admin->changepwd($u,$op,$np);
					if($ret==1){
						header('Location: suc.php');
						exit();
					}elseif($ret==104){
						header('Location: error.php?e=104');	
						exit();
					}else{
						header('Location: error.php?e=106');	
						exit();
					}	
				}else{
					header('Location: error.php?e=111');	
					exit();
				}	
			}else{
				header('Location: error.php?e=112');	
				exit();
			}
		}else{
			header('Location: error.php?e=102');	
			exit();
		}
		break;
	case 'm':
		switch($model){
			case 'add':
				$pro = $_SESSION['pro'];
				if($pro<2){
					header('Location: error.php?e=11');	
					exit();
				}else{
					$n = $_POST['n'];
					$p = $_POST['p'];
					$s = $_POST['s'];
					$pr = $_POST['pr'];
					if($n!= '' && $p !='' && $s!= '' && $pr != ''){
						$param = array(
							'n' => $n,
							'p' => $p,
							's' => $s,
							'at' => time(),
							'pr'=> $pr	
						);
						$ret = $admin->add(MBAdmin::$userList,$param);
							if($ret==1){
								header('Location: suc.php');
								exit();
							}elseif($ret==105){
								header('Location: error.php?e=105');	
								exit();
							}else{
								header('Location: error.php?e=101');	
								exit();
							}		
					}else{
						header('Location: error.php?e=102');	
						exit();
					}
				}
				break;
			case 'del':
				$pro = $_SESSION['pro'];
				$nowuid = $_SESSION['userid'];
				if($pro<2){
					header('Location: error.php?e=11');	
					exit();
				}else{
					$id= (int) $_POST['id'];
					if($id == $nowuid){
						echo 2;
					}else{
						$ret = $admin->delUser($id);
					}
				}
				break;		
			case 'edit':
				$id = $_POST['uid'];
				$n = $_POST['n'];
				$s = $_POST['s'];
				$pr = $_POST['pr'];
				if($id != '' && $n != ''){
					$param = array(
						'n' => $n,
						's' => $s,
						'pr'=> $pr	
					);
					$ret = $admin->editUser($id,$param);
					if($ret==1){
						header('Location: suc.php');
						exit();
					}else if($ret == 31){
						header('Location: error.php?e=31');	
						exit();
					}else if($ret == 32){
						header('Location: error.php?e=32');	
						exit();
					}else{
						header('Location: error.php?e=101');	
						exit();
					}
				}else{
					header('Location: error.php?e=102');	
					exit();
				}
				break;				
		}
		break;
}
?>
