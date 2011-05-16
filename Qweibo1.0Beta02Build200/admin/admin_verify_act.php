<?php
session_start();
require_once '../config/sys_config.php';
require_once('conn.php');
require_once('inc/admin_act.php');
$verifyUserMgr = new MBAdminVerify($host,$root,$pwd,$db);

$act = $_GET['act'];
$continue = $_SERVER['HTTP_REFERER'];
$_SESSION["bkurl"] = $continue;

switch($act){
	case "upload":
		$uploaddir = $_SERVER['DOCUMENT_ROOT'].dirname(dirname($_SERVER["PHP_SELF"])).'/style/images/admin/upload/';
		if(in_array($_FILES['logo']['type'],array("image/bmp","image/x-png","image/png","image/jpeg","image/gif","image/pjpeg"))){
			if($_FILES['logo']['size'] > 2000000){//文件大小超限
				header('Location: error.php?e=115');	
				exit();
			}
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
			$v_icon = $uploaddir.'v_icon'.$extype;
			$fileuploadsuccess = move_uploaded_file($_FILES['logo']['tmp_name'],$v_icon);
			if($fileuploadsuccess) {
				$hd = MB_ADMIN_DIR.'/data/verify_icon.php';
				$hd = fopen(str_replace('/',DIRECTORY_SEPARATOR,$hd),'w');
				$str = "<?php\r\n\$verify_icon_file_name=\"".'v_icon'.$extype."\";\r\n?>";
				if(fwrite($hd, $str) === FALSE) {
					header('Location: error.php?e=114');	
					exit();
				}
				header('Location: suc.php');	
				exit();
			}else{
				header('Location: error.php?e=114');	
				exit();
			}
		}else{
			header('Location: error.php?e=116');	
			exit();
		}
		break;
	case "add":
		$v_name=trim($_POST["name"]);
		$v_desc=trim($_POST["desc"]);
		if(empty($v_name)||empty($v_desc)){
			header('Location: error.php?e=117');
			exit();
		}
		$paras = array();
		$paras["name"]=$v_name;
		$paras["desc"]=$v_desc;
		$retcode = $verifyUserMgr->add($paras);
		if($retcode){
			if($retcode > 1){
				header('Location: error.php?e='.$retcode);
				exit();
			}
			header('Location: suc.php');
			exit();
		}else{
			header('Location: error.php?e=101');	
			exit();
		}
	case "del":
		$v_id = (int)trim($_GET["id"]);
		$retcode = $verifyUserMgr->del($v_id);
		if($retcode){
			header('Location: suc.php');
			exit();
		}else{
			header('Location: error.php?e=118');	
			exit();
		}
	case "update":
		$v_id = (int)trim($_POST["id"]);
		$v_name=trim($_POST["name"]);
		$v_desc=trim($_POST["desc"]);
		if(empty($v_name)||empty($v_desc)){
			header('Location: error.php?e=117');
			exit();
		}
		$paras = array();
		$paras["name"]=$v_name;
		$paras["desc"]=$v_desc;
		$retcode = $verifyUserMgr->update($v_id,$paras);
		if($retcode){
			header('Location: suc.php');
			exit();
		}else{
			header('Location: error.php?e=119');	
			exit();
		}
}
?>