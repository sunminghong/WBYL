<?php
define('ISSAE',false);  //是否为sae平台

$apiConfig=array(
	"tsina"=>array(
		"name"=>'新浪微博',
		"domain_format" => 'http://weibo.com/{domain}',
		"access_key" =>'4106323544',
		"screct_key"=>'fdea0fd0626378d951a366e00c5444d7'
	)
);

	$app_dbpre_ppt="ppt.ppt_";			//定义各个应用的数据表的前缀，可以带数据库名，方便增加应用
	$app_dbpre_redchess="redchess.red_";
	$app_dbpre_iq="ppt.iq_";
if(!ISSAE){
	$DBCONFIG=array(
		array("dbhost"=>"localhost",
			"dbuser"=>"root",
			"dbpwd"=>"root"),		//此为该组数据服务器的主服务器（Master/Slave 结构里的Master）

		array("dbhost"=>"localhost",
			"dbuser"=>"root",
			"dbpwd"=>"root")		//此为slave服务器，可以有多个进行读负载均衡
	);

	define("DEFAULT_DB","ppt");			//默认的数据库
	$app_dbpre_ppt="ppt.ppt_";			//定义各个应用的数据表的前缀，可以带数据库名，方便增加应用
	$app_dbpre_redchess="redchess.red_";
	$app_dbpre_iq="ppt.iq_";
}

else{
	define('DEFAULT_DB', 'app_'.$_SERVER['HTTP_APPNAME']);

	$DBCONFIG=array(
	array("dbhost"=> 'm'.$_SERVER['HTTP_MYSQLPORT'].'.mysql.sae.sina.com.cn:'.$_SERVER['HTTP_MYSQLPORT'],
		"dbuser"=>SAE_ACCESSKEY,
		"dbpwd"=>SAE_SECRETKEY),	//此为该组数据服务器的主服务器（Master/Slave 结构里的Master）
	array("dbhost"=> 'm'.$_SERVER['HTTP_MYSQLPORT'].'.mysql.sae.sina.com.cn:'.$_SERVER['HTTP_MYSQLPORT'],
		"dbuser"=>SAE_ACCESSKEY,
		"dbpwd"=>SAE_SECRETKEY)		//此为slave服务器，可以有多个进行读负载均衡
	);

	$app_dbpre_ppt=SMH_DBNAME . ".ppt_";
	$app_dbpre_redchess=SMH_DBNAME . ".red_";
	$app_dbpre_iq=SMH_DBNAME . ".iq_";
	
	define('SMH_KEY', '9edcl1jnwma724VL0zCIcR7YSBdo7lv+FjPf55E');
	define('SMH_API', 'http://5d13.sinaapp.com/uc');
	define('SMH_IP', '');
	define('SMH_APPID', '3');
	define('SMH_PPP', '20');
}


define("DEFAULT_CHARTSET","utf8"); //默认的数据库连接编码

define("CookieDomain",""); //cookie域
define("CookiePre","wbyl"); //cookie键前缀


$currTemplate='default'; //定义采用哪个模版
