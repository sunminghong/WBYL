<?php
include_once(dirname(dirname(__FILE__))."/admin/inc/admin_act.php");
require_once("install.var.php");
require_once("install.func.php");
$step=$_GET["step"];
$action=$_GET["action"];
if ($step=="1")
{
logMsg("");
SaveSiteInfo();
echo("<script type=\"text/javascript\">window.location.href='step3.html';</script>");
}
elseif($step=="2")
{
	clearInstallLog();
	$DataBaseInfo=array('database_url'=>$_POST["database_url"],
						'database_ctype'=>$_POST["database_ctype"],
						'database_name'=>$_POST["database_name"],
						'database_user'=>$_POST["database_user"],
						'database_pasw'=>$_POST["database_pasw"],
						'database_prefix'=>$_POST["database_prefix"]
						);
	foreach($DataBaseInfo as $key => $item)
	{$DataBaseInfo[$key]=str_replace("'","",str_replace("\"","",$item));/*过滤掉双引号和单引号**/}
	
	$SiteAdminInfo=array(
						'adminName'=>$_POST["adminName"],
						'adminPasw'=>$_POST["adminPasw"],
						'adminPasw2'=>$_POST["adminPasw2"]
						);
	foreach($SiteAdminInfo as $key => $item)
	{$SiteAdminInfo[$key]=str_replace("'","",str_replace("\"","",$item));/*过滤掉双引号和单引号**/}
	$StationMasterInfo=array(
						'nickname'=>$_POST["nickname"],
						'email'=>$_POST["email"],
						'qq'=>$_POST["qq"],
						'msn'=>$_POST["msn"],
						'tel'=>$_POST["tel"]
						);
	
	if(SaveDataBaseInfo($DataBaseInfo))
	{
		if(execuSQL($DataBaseInfo,$SiteAdminInfo))
		{
		SetInstallInfo(true);
define("MB_DATABASE_PREFIX",$DataBaseInfo['database_prefix']);
$webMatser = new MBAdmin($DataBaseInfo["database_url"],$DataBaseInfo["database_user"],$DataBaseInfo["database_pasw"],$DataBaseInfo["database_name"]);
$webMatser->crfile('mod');
$webMatser->crfile('logo');
$webMatser->crfile('rtopic');
$webMatser->crfile('htopic');
$webMatser->crfile('ruser');
$webMatser->crfile('keyword');
$webMatser->crfile('tweet');
$webMatser->crfile('repost');
$webMatser->crfile('fuser');
$webMatser->crfile('foot');

		redirect("step4.html?action=success");
		}
		else
		{/*redirect("step4.html?action=fail&ret=1");*/}
	}
	else
	{
		redirect("step4.html?action=fail&ret=0");
	}	
}

if ($action=="removeInstallInfo")
{
	$a=$_GET["a"];
	$url=$_GET["url"];

	if (removeInstallInfo($a))
	{
		redirect($url);
	}
}
?>