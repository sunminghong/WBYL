<?php
if(!defined('APPIN')) {
	exit('Access Denied');
}

$skey = SESS::get('keys');
$o = new MBOpenTOAuth( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;//鑾峰彇ACCESSTOKEN
SESS::set('last_key',$last_key);
echo "<script language='javascript'>"; 
echo "location='". $indexUrl ."?app=". rq('fn') ."';"; 
echo "</script>";
?>

