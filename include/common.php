<?php
//if(ISSAE)sae_xhprof_start();
define('ISWBYL',true);
$PHP_SELF=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
define('ROOT', substr(__FILE__, 0, -18));
define('WEBROOT',substr($PHP_SELF,0,strrpos($PHP_SELF, '/')+1));
define('DATADIR', ROOT.'data/');
define('URLBASE',"http://".$_SERVER['HTTP_HOST'].WEBROOT); //.':'.$_SERVER['SERVER_PORT']


error_reporting (E_ALL & ~E_NOTICE);
// 默认时区设置
@date_default_timezone_set('PRC');

include ROOT.'config.inc.php';
include_once(ROOT.'include/function.php');

importlib("envhelper_class");
importlib("dbhelper_class");
importlib("ppt_class");
importlib("ctl_base_class");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pramga: no-cache");
header('Content-Type: text/html; charset=utf-8');//.DEFAULT_CHARTSET);

$account=false;
$accounts=envhelper::readAccounts();
$ret=envhelper::readRet();
if(is_array($accounts)){
	foreach($accounts as $acc){
		$account=$acc;
		break;
	}
}
if(!is_array($account))
	$lfrom='tsina';
else
	$lfrom=$account['lfrom'];
//echo "accounts=";print_r($accounts);exit;


//时间
$mtime = explode(' ', microtime());
$timestamp = $mtime[1];
//$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];
$yymmdd=date("ymd");


function getAccount(){
	global $account;
	return is_array($account)?$account:false;
}

function readqtype(){
	$qtype=rq("qtype",0);
	if($qtype!=0) {
		ssetcookie("daren_qtype",$qtype);
		return $qtype;
	}
	$qtype=intval("0".sreadcookie("daren_qtype",$qtype));
	return $qtype;
}

function LetGo(){
	global $account,$defapp,$accounts;
	if(!$defapp)$defapp="home";
	$app=empty($_GET['app'])?$defapp:$_GET['app'];
	$act=empty($_GET['act'])?$app:$_GET['act'];
	$op=empty($_GET['op'])?'index':$_GET['op'];
		
	include(ROOT.$app."/ctl_".$act.".php");
	
	$cont=new $act();
	$cont->assign("app",$app);
	$cont->assign("act",$act);
	$cont->assign("op",$op);
	
	$cont->set('qtype',readqtype());

	$cont->set("lfrom",$lfrom);
	$cont->assign("account",$account);
	$cont->$op();	
}

//if(ISSAE)sae_xhprof_end()
?>