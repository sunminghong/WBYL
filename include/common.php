<?php
//print_r($_SERVER);exit;
define('ISWBYL',true);
$PHP_SELF=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
define('ROOT', substr(__FILE__, 0, -18));
define('WEBROOT',substr($PHP_SELF,0,strrpos($PHP_SELF, '/')+1));
define('DATADIR', ROOT.'data/');
define('URLBASE',"http://".$_SERVER['HTTP_HOST'].WEBROOT); //.':'.$_SERVER['SERVER_PORT']


error_reporting (E_ALL & ~E_NOTICE);
// 默认时区设置
@date_default_timezone_set('PRC');

include ROOT.'./config.inc.php';
include_once(ROOT.'/include/function.php');

importlib("envhelper_class");
importlib("dbhelper_class");
importlib("ppt_class");
importlib("ctl_base_class");

header('Content-Type: text/html; charset='.DEFAULT_CHARTSET);

///include ROOT.'./include/db_mysql.class.php';

$account=false;
$accounts=envhelper::readAccounts();

if(is_array($accounts)){
	foreach($accounts as $acc){
		$account=$acc;
		break;
	}
}
//echo "accounts=";print_r($accounts);exit;

//时间
$mtime = explode(' ', microtime());
$timestamp = $mtime[1];
//$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];
$yymmdd=date("ymd");

//$view = new template();
//$view->defaulttpldir = ROOT.'./templets/'.$currTemplate;
//$view->tpldir = ROOT.'./templets/'.$currTemplate;
//$view->objdir = DATADIR.'./templets';
//$view->langfile = ROOT.'./templets/'.$currTemplate.'/templates.lang.php';

//$view->assign('templatepath',WEBROOT.'templets/'.$currTemplate);
//$view->assign('charset', DEFAULT_CHARTSET);

function getAccount(){
	global $account;
	return is_array($account)?$account:false;
}
function LetGo(){
	global $account,$defapp;
	if(!$defapp)$defapp="home";
	$app=empty($_GET['app'])?$defapp:$_GET['app'];
	$act=empty($_GET['act'])?$app:$_GET['act'];
	$op=empty($_GET['op'])?'index':$_GET['op'];
		
	include(ROOT.$app."\\ctl_".$act.".php");
	
	$cont=new $act();
	$cont->assign("app",$app);
	$cont->assign("act",$act);
	$cont->assign("op",$op);

	//$cont->assign('uid', $uid);
	//$cont->assign('username',$username);
	//$cont->assign('nickname',$nickname);

	$cont->assign("account",$account);
	$cont->$op();	
}
?>