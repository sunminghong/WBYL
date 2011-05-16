<?php

require_once '../config/sys_config.php';
require_once MB_ADMIN_DIR.'/inc/admin_act.php';

session_start();
$a = $_GET['act'];
$status = (int) $_GET['s'];
if($a == 'login'){
	require_once('conn.php');
	$upwd = $_POST['password'];
	$name = $_POST['account'];

	$link = mysql_connect($host, $root, $pwd);
	mysql_select_db($db, $link);
	mysql_query('set names utf8');
	$tn = MBAdmin::$dbName.MBAdmin::$userList;
	$sql = 'select u_id,u_name,u_status as s,u_priority as p from '.$tn.' where u_name="'.mysql_real_escape_string
($name).'" and u_password="'.md5($upwd).'" and u_status>=0';
	$result = mysql_query($sql,$link);
	if(mysql_num_rows($result)){
		$ret = mysql_fetch_assoc($result); 	
		if($ret['s']>0){
			$_SESSION["user"] = $ret['u_name'];
			$_SESSION["status"] = $ret['s'];
			$_SESSION["pro"] = $ret['p'];
			$_SESSION["userid"] = $ret['u_id'];
			header('Location: admin_recomm.php');
			exit();
		}else{
			header('Location: login.php?s=1');
			exit();
		}
	}else{
		$_SESSION["account"] = $_POST['account'];
		$_SESSION["bkurl"] = 'login.php';
		echo '<meta http-equiv="Refresh" content="0;URL=error.php?e=1" />';	
	}
}else{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录-iWeibo管理中心</title>
</head>
<style type="text/css">
body{margin:0;padding:0; text-align:center;color:#333;}
input[type='text'],input[type='password'],textarea{outline:0;}
input[type='text']:focus,input[type='password']:focus,textarea:focus{border-color:#50BFE5;}
a:link,a:visited{color:#3A8DC9; text-decoration:none; font-size:12px;}
a:hover,a:active{text-decoration:underline}
.wrapper{width:742px;margin:0 auto;}
.header{height:66px; background:url(../style/images/admin/logo.gif) 0 12px no-repeat; text-indent:-9999px;}
.main{background:url(../style/images/admin/bg_2.gif) repeat-y; }
.mainA{background:url(../style/images/admin/bg_1.gif) no-repeat; height:110px;}
.mainA p{margin:65px 0 0 50px;padding:0; text-align:left;font-size:12px;display:inline-block;width:100%;}
.mainB{min-height:300px;}
.mainC{background:url(../style/images/admin/bg_3.gif) no-repeat; height:7px;font-size:1px;}
img.icon,img.yes,img.no{background:url(../style/images/admin/icon.gif) no-repeat;vertical-align:middle;}
img.icon{width:10px;height:10px;}
img.yes{width:15px;height:12px; background-position:0 -10px;}
img.no{width:12px;height:12px;background-position:0 -22px;}
.mainB form{text-align:left;}
.mainB form ul{list-style:none;margin:45px 0 0 83px;padding:0;}
.mainB form ul li{padding:0;margin:0 0 34px;}
.mainB form ul strong{display:inline-block;width:100px; font-size:14px; }
.mainB form ul strong b{vertical-align:middle;font-family:宋体,Simsun; white-space:pre;}
.mainB form ul strong img.icon{margin-right:23px;}
.mainB form li.nomargin1{margin-bottom:0;}
.mainB form li.nomargin2{margin-top:5px;}
.mainB form li.nomargin2 a{padding:3px 0;}
.mainB form li label{font-size:12px;color:#949494; margin:0 10px;}
.mainB form li cite{font-size:12px; font-style:normal;}
.mainB form li cite img,.mainB form li cite font{vertical-align:middle;}
.mainB form li cite img{margin-right:8px;}
img.vertifycode{border:1px solid #E4EEF9;margin-left:8px\9;}
.txt{border:1px solid #B8BFC4;padding:5px 0;width:245px;border-radius:3px;-moz-border-radius:3px;padding-left:5px; -webkit-box-shadow:inset 0 0 5px #ccc; -moz-box-shadow:inset 0 0 5px #ccc; vertical-align:middle;margin-right:15px;}
.btn{background:url(../style/images/admin/btn1.gif) no-repeat;width:101px; height:31px;border:0; cursor:pointer; margin:0 10px\9;}
.mainB form li label.error{
	color:#f00;
}
.copyright{font-size:12px;color:#999; margin:24px auto; font-family:Arial;}
.footer{
	height:56px;
	clear:both;
	border-top:1px solid #ECF1F4;
	text-align:center; line-height:56px;font-size:12px;color:#999;font-family:Arial;
	width:100%;
	bottom:0;
}
</style>
<body>
<div class="wrapper header">iWeibo管理中心</div>
<div class="wrapper main">
	<div class="mainA"><p>请使用你安装iWeibo时设置的管理员帐号和密码，登录iWeibo管理中心。</p></div>
	<div class="mainB">
	  <form id="form1" name="form1" method="post" action="login.php?act=login">
	  <ul>
	  	<?php if($status){echo '<li><label class="error">该用户还未激活</label></li>';}?>
		  <li><strong><img src="../style/images/admin/0.gif" class="icon"/><b>帐  号：</b></strong> <input name="account" maxlength="100" value="<?php echo $_SESSION["account"];?>" type="text" class="txt" id="account"/> </li>
		  <li><strong><img src="../style/images/admin/0.gif" class="icon"/><b>密  码：</b></strong> <input name="password" maxlength="100" type="password" class="txt" id="password" maxlength="16"/> <?php if($_SESSION["account"]!=''){echo '<label class="error">用户名或密码错误</label>';}?> </li>
		  <li><strong></strong>
			<input class="btn"  type="submit" value=""/>
		  </li>
	  </ul>
	  </form>
	</div>
	<div class="mainC"></div>
</div>
<?php
	require_once('inc/admin_footer.php');
}
?>
