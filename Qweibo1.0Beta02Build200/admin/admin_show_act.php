<?php
session_start();
require_once '../config/sys_config.php';
require_once('conn.php');
require_once('inc/admin_act.php');
$show = new MBAdminShow($host,$root,$pwd,$db);

$act = $_GET['a'];
$model = $_GET['m'];
$url = $_SERVER['HTTP_REFERER'];
$_SESSION["bkurl"] = $url;
switch($act){
	case 'l':
		$uploaddir = $_SERVER['DOCUMENT_ROOT'].dirname(dirname($_SERVER["PHP_SELF"])).'/style/images/admin/upload/';
		if(in_array($_FILES['logo']['type'],array("image/bmp","image/x-png","image/png","image/jpeg","image/gif","image/pjpeg"))){
			if($_FILES['logo']['size'] < 2000000){
				switch($_FILES['logo']['type']){
					case 'image/x-png':
					case 'image/png':
						$extype ='.png';
						break;
					case 'image/jpeg':
					case 'image/pjpeg':
						$extype ='.jpg';
						break;			
					case 'image/gif':
						$extype ='.gif';
						break;			
					case 'image/bmp':
						$extype ='.bmp';
						break;			
				}
				$uploadfile = $uploaddir.'logo'.$extype;
				$logourl = 'logo'.$extype;
				if(move_uploaded_file($_FILES['logo']['tmp_name'],$uploadfile)) {
					$ret = $show->changeLogo($logourl);
					if($ret>=0){
						$show->crfile('logo');
						header('Location: suc.php');
						exit();
					}else{
						header('Location: error.php?e=101');	
						exit();
					}
				} else {
					header('Location: error.php?e=114');	
					exit();
				}
			}else{
				header('Location: error.php?e=115');	
				exit();
			}
		}else{
			header('Location: error.php?e=116');	
			exit();
		}
		break;
	case 'r':
		$r = $_POST['open'];
		$ret = $show->changeRewrite($r);
			if($ret>=0){
				header('Location: suc.php');
				exit();
			}else{
				header('Location: error.php?e=101');	
				exit();
			}
		break;
	case 's':
		$r = $_POST['s'];
		$ret = $show->changeSearch($r);
			if($ret>=0){
				$show->crfile('search');
				header('Location: suc.php');
				exit();
			}else{
				header('Location: error.php?e=101');	
				exit();
			}
		break;		
	case 'p':
		//$v = (int) $_POST['visible'];
		//$h = $_POST['head'];
		$f = $_POST['foot'];
		if($f != ''){
			$p = array(
				//'v' => $v,
				//'h' => $h,
				'f' => $f	
			);
			$ret = $show->changePage($p);
			if($ret>=0){
				$show->crfile('foot');
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
?>
