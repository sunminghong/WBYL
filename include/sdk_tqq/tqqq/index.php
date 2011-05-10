<?php
@header('Content-Type:text/html;charset=utf-8'); 
session_start();
require_once('config.php');
require_once('oauth.php');
require_once('opent.php');

$o = new MBOpenTOAuth( MB_AKEY , MB_SKEY  );
	$callbackurl='http://localhost:9999/weiboyule/include/tqq/tqqq/callback.php?lfrom=tqq&fromurl='.urlencode($_SERVER["HTTP_REFERER"]);
$keys = $o->getRequestToken($callbackurl);//这里填上你的回调URL
$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false,'');
$_SESSION['keys'] = $keys;
?>
<a href="<?php echo $aurl?>">用OAUTH授权登录</a>
