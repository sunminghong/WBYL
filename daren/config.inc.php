<?php
define('ISSAE',false);  //是否为sae平台
define('SAVELOG',true); //是否保存测试log 
define('NOADS',false); //是否不显示广告

$canLogin=array("tsina");

//我的一生
$apiConfig=array(
	"tsina"=>array(
		"name"=>'新浪微博',
		"orguid" => '1747738583', //官方微博帐号
		'orgwbsite'=>'http://weibo.com/5d13',
		"domain_format" => 'http://weibo.com/{domain}',
		"access_key" =>'4106323544',
		"screct_key"=>'fdea0fd0626378d951a366e00c5444d7'
	),
	"tqq"=>array(
		"name"=>'腾讯微博',
		"orguid" => 'yihuiso', //官方微博帐号
		'orgwbsite'=>'http://t.qq.com/yihuiso',
		"domain_format" => 'http://t.qq.com/{domain}',
		"access_key" =>'633e42f93e2f4a8a9981711ee890cf99',
		"screct_key"=>'957cdac92749aa9e346ffc44830ee875'
	)
);

//火不火，给你看看
$apiConfig=array(
	"tsina"=>array(
		"name"=>'新浪微博',
		"orguid" => '1747738583', //官方微博帐号
		"domain_format" => 'http://weibo.com/{domain}',
		"access_key" =>'3011873221',
		"screct_key"=>'554994f0eb7256d950b52611782e027d'
	),
	"tqq"=>array(
		"name"=>'腾讯微博',
		"orguid" => 'yihuiso', //官方微博帐号
		"domain_format" => 'http://t.qq.com/{domain}',
		"access_key" =>'97dc6a2ccc694339a67e3be67d27f064',
		"screct_key"=>'b6fc750ad4f8c7efb2133de97a9c83e3'
	)
);


//nitaiyoucaile 
$apiConfig=array(
	"tsina"=>array(
		"name"=>'新浪微博',
		"orguid" => '2164059262', //官方微博帐号
		"domain_format" => 'http://weibo.com/{domain}',
		"access_key" =>'1053757779',
		"screct_key"=>'ba0b63eb6fe4f7328eb8b564fe5e2d34'
	),
	"tqq"=>array(
		"name"=>'腾讯微博',
		"orguid" => 'miqibar', //官方微博帐号
		"domain_format" => 'http://t.qq.com/{domain}',
		"access_key" =>'c36dcbe769fb437ab4d3c586d28b1a02',
		"screct_key"=>'3e32ed6339533e52c0cfe5223b51d2a4'
	)
);

if(!ISSAE){
	$DBCONFIG=array(
		array("dbhost"=>"localhost",
			"dbuser"=>"youcai",
			"dbpwd"=>"youcai!@#$%^"),		//此为该组数据服务器的主服务器（Master/Slave 结构里的Master）

		array("dbhost"=>"localhost",
			"dbuser"=>"youcai",
			"dbpwd"=>"youcai!@#$%^")		//此为slave服务器，可以有多个进行读负载均衡
	);

	$MEMCONFIG=array(
		'ip'=>'127.0.0.1',
		'port' => '11330',
	);

	define("DEFAULT_DB","youcai");			//默认的数据库
}

else{
define('DEFAULT_DB',SAE_MYSQL_DB);
	$DBCONFIG=array(
		array("dbhost"=>SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT,
			"dbuser"=>SAE_MYSQL_USER,
			"dbpwd"=>SAE_MYSQL_PASS),		//此为该组数据服务器的主服务器（Master/Slave 结构里的Master）

		array("dbhost"=>SAE_MYSQL_HOST_S.":".SAE_MYSQL_PORT,
			"dbuser"=>SAE_MYSQL_USER,
			"dbpwd"=>SAE_MYSQL_PASS)		//此为slave服务器，可以有多个进行读负载均衡
	);

/*	define('DEFAULT_DB', 'app_'.$_SERVER['HTTP_APPNAME']);
	$DBCONFIG=array(
	array("dbhost"=> 'm'.$_SERVER['HTTP_MYSQLPORT'].'.mysql.sae.sina.com.cn:'.$_SERVER['HTTP_MYSQLPORT'],
		"dbuser"=>SAE_ACCESSKEY,
		"dbpwd"=>SAE_SECRETKEY),	//此为该组数据服务器的主服务器（Master/Slave 结构里的Master）
	array("dbhost"=> 'm'.$_SERVER['HTTP_MYSQLPORT'].'.mysql.sae.sina.com.cn:'.$_SERVER['HTTP_MYSQLPORT'],
		"dbuser"=>SAE_ACCESSKEY,
		"dbpwd"=>SAE_SECRETKEY)		//此为slave服务器，可以有多个进行读负载均衡
	);*/

}
	$app_dbpre_ppt=DEFAULT_DB . ".ppt_";
	$app_dbpre_redchess=DEFAULT_DB . ".red_";
	$app_dbpre_iq=DEFAULT_DB . ".iq_";	
	$app_dbpre_eq=DEFAULT_DB . ".eq_";	
	$app_dbpre_log=DEFAULT_DB .".log_";
	$app_dbpre_daren=DEFAULT_DB .".daren_";
	$app_dbpre_ilike=DEFAULT_DB .'.ilike_';

define("DEFAULT_CHARTSET","utf8"); //默认的数据库连接编码

define("CookieDomain",""); //cookie域
define("CookiePre","wbyl"); //cookie键前缀


$currTemplate='darennew'; //定义采用哪个模版
