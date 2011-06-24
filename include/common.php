<?php
//if(ISSAE)sae_xhprof_start();
define('SENTATNAME',false);
define('ISWBYL',true);
$PHP_SELF=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
define('ROOT', substr(__FILE__, 0, -18));
define('WEBROOT',substr($PHP_SELF,0,strrpos($PHP_SELF, '/')+1));
define('DATADIR', ROOT.'data/');
define('URLBASE',"http://".$_SERVER['HTTP_HOST'].WEBROOT); //.':'.$_SERVER['SERVER_PORT']


error_reporting (E_ALL & ~E_NOTICE);
// 默认时区设置
@date_default_timezone_set('PRC');

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pramga: no-cache");
header('Content-Type: text/html; charset=utf-8');//.DEFAULT_CHARTSET);


$account=$accounts=false;

include ROOT.'config.inc.php';
include_once(ROOT.'include/function.php');

importlib("envhelper_class");
importlib("dbhelper_class");
importlib("ppt_class");
importlib("ctl_base_class");

$lfrom=rq("lfrom","");
if($lfrom) {
	ssetcookie("lfrom",$lfrom);
}
else {
	$lfrom=sreadcookie("lfrom");
}
if($lfrom) 
	$canLogin=array($lfrom);

$accounts=envhelper::readAccounts();

$ret=envhelper::readRet();
if(is_array($accounts)){
	foreach($accounts as $acc){
		
		if(is_array($acc)) {
			if(!$lfrom || $lfrom==$acc['lfrom']) {
				$account=$acc;
				$lfrom=$account['lfrom'];
				break;
			}
		}
	}
}
if(!$lfrom) $lfrom="tsina";

//echo "accounts=";print_r($accounts);echo "account=";print_r($accounts);

//时间
$mtime = explode(' ', microtime());
$timestamp = $mtime[1];
//$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];
$yymmdd=date("ymd");


function getAccount(){
	global $account;
	return is_array($account)?$account:false;
}
function getApi(){
	$account=getAccount();
	if(!is_array($account)) return false;
	$api="sdk_".$account['lfrom']."/openapi_class";
	importlib($api);
	$api=new openapi($account['kuid']); 
	
	return $api;
}

function LetGo(){
	global $account,$defapp,$accounts,$lfrom;
	if(!$defapp)$defapp="home";
	$app=empty($_GET['app'])?$defapp:$_GET['app'];
	$act=empty($_GET['act'])?$app:$_GET['act'];
	$op=empty($_GET['op'])?'index':$_GET['op'];
	//$lfrom=$_GET['lfrom'];
		
	include(ROOT.$app."/ctl_".$act.".php");
	
	$cont=new $act();
	$cont->assign("app",$app);
	$cont->assign("act",$act);
	$cont->assign("op",$op);
	$cont->assign("lfrom",$lfrom);
	
	if(rq('template',''))
		$currTemplate=rq('template','');

	////$cont->set('qtype',readqtype());

	$cont->set("lfrom",$lfrom);
	if($lfrom=='tqq') 
		$cont->set('orgwbsite','http://t.qq.com/miqibar');
	else
		$cont->set('orgwbsite','http://weibo.com/miqibar');
	$cont->assign("account",$account);
	$cont->assign('logined', is_array($account)?'true':'false');

	$cont->force=rq("clstemplate",0);
	$cont->$op();
	
	dbhelper::close();
}

//if(ISSAE)sae_xhprof_end()
?>