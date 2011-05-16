<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>环境检查-安装向导</title>
<link rel="stylesheet" type="text/css" href="css/install.css" />
<script type="text/javascript" src="js/install.js"></script>
</head>
<body>
<?php
require_once("install.var.php");
require_once("install.func.php");
?>
<div class="wrapper header"><h1>iWeibo管理中心</h1><span><a href="http://open.t.qq.com" target="_blank">腾讯微博开放平台</a> | <a href="http://open.t.qq.com/resource.php?i=2,0" target="_blank">帮助</a></span></div>
<div class="wrapper main">
<div class="mainA"></div>
<div class="mainB">
	<div class="step">
    <img src="../style/images/admin/0.gif" class="step1"/>
    </div>
    <h2 class="step1_title step1_title_1">环境检查</h2>
    <table width="806" border="0" cellspacing="1" cellpadding="0" class="table1">
    <tr>
    <th>项目</th>
    <th>iWeibo所需配置</th>
    <th>iWeibo最佳配置</th>
    <th>当前服务器</th>
    <th>结果</th>
    </tr>
<?php
	echo(surrounding_support($iWeibo_surrounding_list));
?>
    </table>
	<h2 class="step1_title step1_title_2">目录、文件权限检查</h2>
  <table width="806" border="0" cellspacing="1" cellpadding="0" class="table1">
    <tr>
    <th>目录文件</th>
    <th>状态</th>
    <th>结果</th>
    </tr>
    <?php
	echo(docs_support($iWeibo_file_list));
    ?>
 </table>
    <h2 class="step1_title step1_title_3">函数依赖性检查</h2>
  <table width="806" border="0" cellspacing="1" cellpadding="0" class="table1">
    <tr>
    <th>函数名称</th>
    <th>状态</th>
    <th>结果</th>
    </tr>
    <?php
    	echo(function_support($iWeibo_funs_list));
    ?>
  </table>
  <div class="stepnav">
  	<input type="button" value="重新检测" class="button" id="prevstep" onclick="location.reload();"/>
    <input type="button" value="下一步"  class="button" id="nextstep" disabled/>
  </div>
</div>
<div class="mainC"></div>
</div>
<div class="wrapper footer">Copyright &copy; 1998-2010 Tencent. All Rights Reserved.</div>
<script type="text/javascript">
window.onload=function()
	{
	var imgs=document.getElementsByTagName("img");
	for(var i=0;i<imgs.length;i++)
	{if(imgs[i].className=="no"){return;}}
	document.getElementById("nextstep").removeAttribute("disabled");
	document.getElementById("prevstep").setAttribute("disabled","disabled");
	document.getElementById("nextstep").onclick=function()
	{
		cookie.set('step2',true);
		window.location.href='step2.html';
	}
	}	
</script>
</body>
</html>
