<?php

define('ISWBYL',true);
$PHP_SELF=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
define('ROOT', substr(__FILE__, 0, -18));
define('WEBROOT',substr($PHP_SELF,0,strrpos($PHP_SELF, '/')+1));
define('DATADIR', ROOT.'data/');
define('URLBASE',"http://".$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].WEBROOT);


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

$accounts=envhelper::readAccounts();

$uid=0;
$username='';
$nickname='';

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

function LetGo(){
	global $accounts;
	$app=empty($_GET['app'])?'home':$_GET['app'];
	$act=empty($_GET['act'])?'home':$_GET['act'];
	$op=empty($_GET['op'])?'index':$_GET['op'];
		
	include($app."\\ctl_".$act.".php");
	
	$cont=new $act();
	$cont->assign("app",$app=="home"?"":$app);
	$cont->assign("act",$act=="home"?"":$act);
	$cont->assign("op",$op=="op"?"":$op);

	//$cont->assign('uid', $uid);
	//$cont->assign('username',$username);
	//$cont->assign('nickname',$nickname);

	$cont->assign("accounts",$accounts);
	$cont->$op();	
}
?>