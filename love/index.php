<?php
header("Content-type: text/html;charset=utf-8");
define('APPIN',true);
define('DEBUG',false);
define('ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
require_once(ROOT."./inc/common.php");
require_once(ROOT."./inc/sdk/config.php");
require_once(ROOT."./inc/sdk/oauth.php");
require_once(ROOT."./inc/sdk/opent.php");

//缁熶竴鍏ュ彛妯″紡
//浣跨敤?app=folder.file妯″紡鎸囧畾鏂囦欢
$indexUrl = $myurl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$getApp = rq('app');
if(strlen($getApp)==0){
	$fn = rq('fn');
	if($fn=='')$fn='secretlove';
	$myurl = $indexUrl.'?app=callback&fn='.$fn;
	$o = new MBOpenTOAuth( MB_AKEY , MB_SKEY  );
	$keys = $o->getRequestToken($myurl);//杩欓噷濉笂浣犵殑鍥炶皟URL
	$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false,'');
	SESS::set('keys',$keys);
	echo "<script language='javascript'>"; 
	echo "location='{$aurl}';"; 
	echo "</script>";
	exit;
}
$file = str_replace('.',DIRECTORY_SEPARATOR,$getApp).'.php';
require(ROOT.$file);
DB::closeall();
?>
