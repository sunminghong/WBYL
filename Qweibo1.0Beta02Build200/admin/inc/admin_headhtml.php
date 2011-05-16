<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iWeibo管理中心</title>
<link rel="stylesheet" type="text/css" href="../style/css/admin/admin_global.css" />
<link rel="stylesheet" type="text/css" href="../style/css/third-party/ui.css" />
<?php require_once('admin_comm.php');?>
</head>
<body>
<div class="header"><div>欢迎你，<?php echo $_SESSION['user'];?><span><a href="../admin/admin_manage.php" id="changepassword">修改密码</a> | <a href="logout.php">退出</a></span> </div></div>
<div class="header_bottom"></div>
<div class="main">
	<div class="mainA">
	<ul>
	<li <?php if(preg_match('/^\/admin\/admin_recomm.php/i', $thisUri)){echo 'class="current"'; }?>><a href="admin_recomm.php">推荐类管理</a></li>
	<li <?php if(preg_match('/^\/admin\/admin_show.php/i', $thisUri)){echo 'class="current"'; }?>><a href="admin_show.php">显示设置</a></li>
	<li <?php if(preg_match('/^\/admin\/admin_filter.php/i', $thisUri)){echo 'class="current"'; }?>><a href="admin_filter.php">屏蔽设置</a></li>
	<li style="display:none;" <?php if(preg_match('/^\/admin\/admin_verify.php/i', $thisUri)){echo 'class="current"'; }?>><a href="admin_verify.php">认证管理</a></li>
	<li <?php if(preg_match('/^\/admin\/admin_manage.php/i', $thisUri)){echo 'class="current"'; }?>><a href="admin_manage.php">系统帐号设置</a></li>
	</ul>
	</div>
