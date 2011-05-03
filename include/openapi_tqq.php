<?php
if(!defined('ISWBYL')) exit('Access Denied');

define( "MB_RETURN_FORMAT" , 'json' );
define( "MB_API_HOST" , 'open.t.qq.com' );

include_once('openapi_abstract_class.php');
require_once('opent.php');
include_once('tqqclient_class.php');

class openapi_tqq extends openapiAbstract{

	private $oClient=null;
	private $akey;
	private $skey;
	
	function __construct(){
		$this->akey=$GLOBALS['apiConfig'][$this->lfrom]["access_key"];
		$this->skey=$GLOBALS['apiConfig'][$this->lfrom]["screct_key"];
		
		$this->name="腾讯微博";
		$this->lfrom="tqq";  //必须与类名相同，即openapi_(tsina)
	}
		
	public function getLoginUrl($callbackurl){		
		//$o = new MBOpenTOAuth( MB_AKEY , MB_SKEY  );
		//$keys = $o->getRequestToken('http://h.t.net/sdk/callback.php');//这里填上你的回调URL
		//$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false,'');
		//$_SESSION['keys'] = $keys;

		$o = new MBOpenTOAuth( $this->akey , $this->skey);
		
		$keys = $o->getRequestToken($callbackurl);
		$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false ,'');
		
		$this->saveToken(0,array('uid'=>0,'tk'=>$keys['oauth_token'],'sk'=>$keys['oauth_token_secret']));
		return $aurl;
	}
		
	public function callback(){	
	/*
@require_once('config.php');
@require_once('oauth.php');
@require_once('opent.php');

$o = new MBOpenTOAuth( MB_AKEY , MB_SKEY , $_SESSION['keys']['oauth_token'] , $_SESSION['keys']['oauth_token_secret']  );
$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;//获取ACCESSTOKEN
$_SESSION['last_key'] = $last_key;
	*/
		$t=$this->readToken(0);

		if (!is_array($t))
			return false;

		$o = new MBOpenTOAuth( $this->akey , $this->skey , $t['tk'] , $t['sk']  );		
		$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
		
		//print_r($last_key);exit;
		if($last_key && $last_key['oauth_token']){
			$t=array(
				"tk"=>$last_key['oauth_token'],
				"sk"=>$last_key['oauth_token_secret']
			);
			
			$this->tokenOrlfromuid=$t;
			$uidarr=$this->getUserInfo();
			$uidarr['tk']=$t['tk'];
			$uidarr['sk']=$t['sk'];
			
			/*$userinfo=array(
				"tk"=>$t['tk'],
				"sk"=>$t['sk'],
				"uid"=>$uidarr['uid'],
				"name"=>$uidarr["name"]
			);*/
			$this->saveToken($uidarr['lfromuid'],$t);
			return $uidarr;
		}
		else
			return false;
	}
	
	public function getUserInfo(){
		$oarr0=$this->getClient()->getUserInfo();		
		$oarr=$oarr0['Data'];
		$uidarr=array();
		$uidarr['screen_name']=$oarr['Nick'];
		$uidarr['name']=$oarr['Name'];
		$uidarr['location']=$oarr['Location'];
		$uidarr['description']=$oarr['Introduction'];
		$uidarr['url']=$oarr[''];
		$uidarr['avatar']=$oarr['Head'];
		$uidarr['domain']=$oarr[''];
		$uidarr['gender']=$oarr['Sex']==1?'m':'f';
		$uidarr['followers']=$oarr['Fansnum'];
		$uidarr['followings']=$oarr['Idolnum'];
		$uidarr['tweets']=$oarr['Tweetnum'];

		$uidarr['verified']=$oarr['isVip'];
		$uidarr['verifyinfo']=$oarr['Verifyinfo'];

		$lfromuid=$oarr["Uid"];
		$uidarr["kuid"]=envhelper::packKUID($this->lfrom,$lfromuid);
		$uidarr["lfromuid"]=$lfromuid;
		$uidarr['lfrom']=$this->lfrom;
		$uidarr['name']=$uidarr['screen_name'];

		return $uidarr;
	}
	
	public function update( $status, $reply_id = NULL, $lat = NULL, $long = NULL, $annotations = NULL ){
		return $this->getClient()->update($status, $reply_id, $lat, $long, $annotations);
	}

	public function upload( $status , $pic_path, $lat = NULL, $long = NULL ){
		return $this->getClient()-> upload( $status , $pic_path, $lat, $long);
	}

	public function del( $sid ){
		return $this->getClient()->delete($sid);
	}

	public function show_user( $uid_or_name ){
		return $this->getClient()->show_user( $uid_or_name );
	}

	public function friends( $cursor = NULL , $count = 20 , $uid_or_name = NULL ){
		return $this->getClient()->friends( $cursor , $count , $uid_or_name );
	}

	public function followers( $cursor = NULL , $count = NULL , $uid_or_name = NULL ){
		return $this->getClient()->followers( $cursor, $count, $uid_or_name);
	}

	public function follow( $uid_or_name ){
		return $this->getClient()->follow( $uid_or_name );
	}

	public function unfollow( $uid_or_name ){
		return $this->getClient()->unfollow( $uid_or_name );
	}

	public function hot_users( $category = "default" ){
		return $this->getClient()->hot_users( $category);
	}

	public function is_followed( $target, $source = NULL ){
		return $this->getClient()-> is_followed($target, $source);
	}

	public function end_session(){
		return $this->getClient()->end_session();
	}

	public function update_profile_image($image_path){
		return $this->getClient()->update_profile_image($image_path);
	}


	private function getClient(){
		if ($this->oClient)	
			return $this->oClient;

		$token=$this->tokenOrlfromuid;
		if(!$token) {
			echo '没有令牌数据！';exit;
		}

		if(!is_array($token)) $token=$this->readToken($token);

		if (!is_array($token)) {
			echo '没有赋令牌数据！';exit;
		}
		$this->oClient=$c = new tqqClient( $this->akey , $this->skey , $token['tk'] , $token['sk']);
		return $c;
	}	
}