<?php
session_start();
require_once '../config/sys_config.php';
require_once('conn.php');
require_once('inc/admin_act.php');
$filter = new MBAdminFilter($host,$root,$pwd,$db);

$act = $_GET['a'];
$model = $_GET['m'];

$url = $_SERVER['HTTP_REFERER'];
$_SESSION["bkurl"] = $url;
switch($act){
		case 'k':
			switch($model){
				case 'add':
					$name = $_POST['n'];
					$type = $_POST['type'];
					$user = $_SESSION['userid'];
					if($name != ''){
						$param = array(
							'key_words' => $name,
							'key_type_id' => $type,
							'key_add_time' => time(),
							'key_operator_id' => $user	
						);		
						$ret = $filter->add(MBAdmin::$keyWords,$param,'key_words');
						if($ret==1){
							$filter->crfile('keyword');
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
					echo $filter->delKeyword($id);
					break;
				case 'edit':
					$name = $_POST['n'];
					$type = $_POST['type'];
					$user = $_SESSION['userid'];
					$id = $_POST['id'];
					if($id != '' && $name != ''){
						$param = array(
							'n' => $name,
							'type' => $type,
							'oid' => $user	
						);		
						$ret = $filter->editKeyword($id,$param);
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
		case 't':
			switch($model){
				case 'add':
					$tid = $_POST['tid'];
					if($tid != ''){
						$param = array(
							'filter_tweet_id' => $tid,	
							'filter_tweet_text' => rhtmlencode($_POST['info']),
							'filter_add_time' => time(),
							'filter_operator_id' => $_SESSION['userid']
						);
						$ret = $filter->add(MBAdmin::$weiboFilter,$param,'filter_tweet_id');
						if($ret==1){
							$filter->crfile('tweet');
							header('Location: suc.php');
							exit();
						}elseif($ret==104){
							header('Location: error.php?e=107');	
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
					$filter->delT($id);					
					break;
			}
			break;
		case 'r':
			switch($model){
				case 'add':
					$tid = $_POST['tid'];
					if($tid != ''){
						$param = array(
							'filter_tweet_id' => $tid,	
							'filter_tweet_text' => $_POST['info'],
							'filter_add_time' => time(),
							'filter_operator_id' => $_SESSION['userid']
						);
						$ret = $filter->add(MBAdmin::$commentFilter,$param,'filter_tweet_id');
						if($ret==1){
							$filter->crfile('repost');
							header('Location: suc.php');
							exit();
						}elseif($ret==104){
							header('Location: error.php?e=108');	
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
					$filter->delR($id);					
					break;
			}
			break;			
		case 'u':
			switch($model){
				case 'add':
					$n = $_POST['name'];
					$nick = $_POST['nick'];
					if($n != '' && $nick != ''){
						$param = array(
							'black_name' => $n,	
							'black_nick' => $nick,
							'black_head_url' => $_POST['head'],
							'black_add_time' => time(),
							'black_operator_id' => $_SESSION['userid']
						);
						$ret = $filter->add(MBAdmin::$blackList,$param);
						if($ret==1){
							$filter->crfile('fuser');
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
					$filter->delUser($id);					
					break;
			}
			break;			
}
?>

