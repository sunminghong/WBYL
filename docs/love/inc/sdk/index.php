<?php
@header('Content-Type:text/html;charset=utf-8'); 
require_once('config.php');
session_start();
require_once('oauth.php');
require_once('opent.php');

$o = new MBOpenTOAuth( MB_AKEY , MB_SKEY  );
$keys = $o->getRequestToken('http://10.0.3.100/TQQ/callback.php');//杩欓噷濉笂浣犵殑鍥炶皟URL
$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false,'');
$_SESSION['keys'] = $keys;
?>
<a href="<?php echo $aurl?>">鐢∣AUTH鎺堟潈鐧诲綍</a>
