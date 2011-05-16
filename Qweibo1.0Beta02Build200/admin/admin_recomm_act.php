<?php
session_start();
require_once '../config/sys_config.php';
header("Content-type: text/html; charset=utf-8"); 
require_once('conn.php');
require_once('inc/admin_act.php');
require_once MB_CTRL_DIR.'/base_mod.class.php';
require_once MB_APP_DIR.'/global.class.php';
$recomm = new MBAdminRecomm($host,$root,$pwd,$db);

try{
	$logger = new MBLog($mbConfig["log_path"], 'admin');
	MBGlobal::setLogger($logger);
	$base = new BaseMod();
	$base->checkLogin();
	$api = MBGlobal::getApiClient();
}catch(MBException $e){
	header('Location: error.php?e=10000');
	exit();
}

$act = $_GET['a'];
$model = $_GET['m'];
$url = $_SERVER['HTTP_REFERER'];
$_SESSION["bkurl"] = $url;
switch($act){
		case 'ht':
			switch($model){
				case 'add':
					$name = $_POST['t'];
					$s = $_POST['s'];
					$e = $_POST['e'];
					$user = $_SESSION['userid'];
					
					if($name != '' && $s != '' && $e != ''){
						$param = array(
							'ht_text' => $name,
							'ht_add_time' => $s,
							'ht_enable_time' => $e,
							'ht_operator_id' => $user	
						);		
						$ret = $recomm->addHT(MBAdmin::$recommTopic,$param,'ht_text');
						if($ret==1){
							$recomm->crfile('rtopic');
							header('Location: suc.php');
							exit();
						}elseif($ret==109){
							header('Location: error.php?e=109');	
							exit();
						}elseif($ret==104){
							header('Location: error.php?e=104');	
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
				case 'del':
					$id= (int) $_POST['id'];
					$ret = $recomm->delTopic($id);
					break;
				case 'edit':
					$name = $_POST['t'];
					$s = $_POST['s'];
					$e = $_POST['e'];
					$user = $_SESSION['userid'];
					$id = $_POST['id'];
					if($id != '' && $name!='' && $s != '' && $e != ''){
						$param = array(
							'ht_text' => $name,
							'ht_add_time' => $s,
							'ht_enable_time' => $e,
							'ht_operator_id' => $user	
						);		
						$ret = $recomm->editTopic($id,$param);
						if($ret==109){
							header('Location: error.php?e=109');	
							exit();
						}elseif($ret>=0){
							header('Location: suc.php');
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
		case 'ru':
			switch($model){
				case 'add':
					$u = $_POST['uname'];
					$wurl = $_POST['url'];
					$info = $_POST['info'];	
					if($u != '' && $wurl !='' && $info != ''){
						$param = array(
							'recomm_weibo_name' => $u,	
							'recomm_weibo_url' => $wurl,
							'recomm_user_introduction' => $info,
							'recomm_sort_id' => $_POST['sort'],
							'recomm_add_time' => time(),
							'recomm_operator_id' => $_SESSION['userid']
						);
						$ret = $recomm->add(MBAdmin::$recommUser,$param,'recomm_weibo_name');
						if($ret==1){
							$recomm->crfile('ruser');
							header('Location: suc.php');
							exit();
						}elseif($ret==104){
							header('Location: error.php?e=104');	
							exit();
						}else{
							header('Location: error.php?e=101');	
							exit();
						}
					}else{
						header('Location: error.php?e=113');
						exit();
					}
					break;
				case 'del':
					$id= (int) $_POST['id'];
					$ret = $recomm->delrUser($id);					
					break;
				case 'edit':
					$u = $_POST['uname'];
					$wurl = $_POST['url'];
					$info = $_POST['info'];	
					if($u!='' && $wurl != '' && $info !=''){
						$param = array(
							'uname' => $u,
							'url' => $wurl,
							'info' => $info,
							'sort' => $_POST['sort'],
							'oname' => $_SESSION['userid']	
						);		
						$ret = $recomm->editrUser((int) $_POST['uid'],$param);
						if($ret>=0){
							header('Location: suc.php');
							exit();
						}else{
							header('Location: error.php?e=101');	
							exit();
						}	
					}else{
						header('Location: error.php?e=113');	
						exit();
					}					
					break;
			}
			break;
		case 'hht'://热门话题
			switch($model){
				case 'add':
					$name = $_POST['t'];
					$sort = $_POST['sort'];
					$status = $_POST['status'];
					$user = $_SESSION['userid'];
					if($name != '' ){
						$param = array(
							'hot_ht_text' => $name,
							'hot_sort_id' => $sort,
							'hot_status' => $status,
							'hot_add_time' => time(),
							'hot_operator_id' => $user	
						);
						$hothtidparams = Array(
							"list"=>Array(
								$name
							)
						);
						//尝试取话题id,失败后静默处理
						try{
							$hothtid = $api->getTopicId($hothtidparams);
							if(!empty($hothtid["data"]["info"][0]["id"])){
								$param["hot_ht_id"] = $hothtid["data"]["info"][0]["id"];
							}else{
								$param["hot_ht_id"] = 0;
							}
						}catch(Exception $e){
							$param["hot_ht_id"] = 0;
						}
						$ret = $recomm->add(MBAdmin::$hotTopic,$param,'hot_ht_text');
						if($ret==1){
							$recomm->crfile('htopic');
							header('Location: suc.php');
							exit();
						}elseif($ret==104){
							header('Location: error.php?e=104');	
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
				case 'del':
					$id= (int) $_POST['id'];
					$ret = $recomm->delHotTopic($id);
					break;
				case 'edit':
					$id = $_POST['id'];
					$name = $_POST['t'];
					$sort = $_POST['sort'];
					$status = $_POST['status'];
					$user = $_SESSION['userid'];
					if($id!='' && $name!=''){
						$param = array(
							'hot_id' => $id,
							'hot_ht_text' => $name,
							'hot_sort_id' => $sort,
							'hot_status' => $status,
							'hot_operator_id' => $user	
						);		
						$ret = $recomm->editHotTopic($id,$param);
						if($ret>=0){
							header('Location: suc.php');
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
		case 'hu':
			switch($model){
				case 'add':
					$name = $_POST['name'];
					$nick = $_POST['nick'];
					$sort = $_POST['sort'];
					$status = $_POST['status'];
					$user = $_SESSION['userid'];
					if($name != '' && $nick != ''){
						$param = array(
							'hot_name' => $name,
							'hot_nick' => $nick,
							'hot_sort_id' => $sort,
							'hot_status' => $status,
							'hot_add_time' => time(),
							'hot_operator_id' => $user	
						);		
						$ret = $recomm->add(MBAdmin::$hotUser,$param,'hot_name');
						if($ret){
							header('Location: suc.php');
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
				case 'del':
					$id= (int) $_POST['id'];
					$ret = $recomm->delHotUser($id);
					break;
				case 'edit':
					$id = $_POST['uid'];
					$name = $_POST['name'];
					$nick = $_POST['nick'];
					$sort = $_POST['sort'];
					$status = $_POST['status'];
					$user = $_SESSION['userid'];
					if($id != '' && $name != '' && $nick != ''){
						$param = array(
							'hot_name' => $name,
							'hot_nick' => $nick,
							'hot_sort_id' => $sort,
							'hot_status' => $status,
							'hot_operator_id' => $user	
						);		
						$ret = $recomm->editHotUser($id,$param);
						if($ret>=0){
							header('Location: suc.php');
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
		case 'nu':
			switch($model){
				case 'add':
					$u = $_POST['uname'];
					$wurl = $_POST['url'];
					if($u != '' && $wurl != ''){
						$param = array(
							'recomm_weibo_name' => $u,	
							'recomm_weibo_url' => $wurl,
							'recomm_user_introduction' => $_POST['info'],
							'recomm_sort_id' => $_POST['sort'],
							'recomm_add_time' => time(),
							'recomm_operator_id' => $_SESSION['userid']
						);
						$ret = $recomm->add(MBAdmin::$recommNewUser,$param,'recomm_weibo_name');
						if($ret){
							header('Location: suc.php');
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
				case 'del':
					$id= (int) $_POST['id'];
					$ret = $recomm->delNewUser($id);					
					break;
				case 'edit':
					$u = $_POST['uname'];
					$wurl = $_POST['url'];
					if($u!='' && $wurl != ''){
						$param = array(
							'uname' => $_POST['uname'],
							'url' => $_POST['url'],
							'info' => $_POST['info'],
							'sort' => $_POST['sort'],
							'oname' => $_SESSION['userid']	
						);		
						$ret = $recomm->editNewUser((int) $_POST['uid'],$param);
						if($ret){
							header('Location: suc.php');
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
		case 'mod':
			switch($model){
				case 'c':
					$id = (int) $_POST['id']; 
					$status = (int) $_POST['status'];
					if($id>0){
						$recomm->changeModStatus($id,$status);		
					}else{
						echo 0;
					}
					break;
				default:
					$n = $_POST['mn'];
					$c = (int) $_POST['mp'];
					$pnum = (int) $_POST['pnum'];
					if($n != ''){
					$ret = $recomm->changeMod($c,$n,$pnum);
						if($ret >= 0){
							header('Location: suc.php');
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
