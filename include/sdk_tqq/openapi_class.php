<?php
if(!defined('ISWBYL')) exit('Access Denied');

define( "MB_RETURN_FORMAT" , 'json' );
define( "MB_API_HOST" , 'open.t.qq.com' );

importlib('openapi_abstract_class');
require_once('opent.php');
include_once('MBApiClient_class.php');

class openapi extends openapiAbstract{

	private $oClient=null;
	private $akey;
	private $skey;
	
	function __construct($tokenOrlfromuid=""){
		$this->tokenOrlfromuid=$tokenOrlfromuid;
		$this->name="腾讯微博";
		$this->lfrom="tqq";
		$this->akey=$GLOBALS['apiConfig'][$this->lfrom]["access_key"];
		$this->skey=$GLOBALS['apiConfig'][$this->lfrom]["screct_key"];		
	}
		
	public function getLoginUrl($callbackurl){		
		$o = new MBOpenTOAuth( $this->akey , $this->skey);
		
		$keys = $o->getRequestToken($callbackurl);
		$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false ,'');

		$this->saveToken(0,array('uid'=>0,'tk'=>$keys['oauth_token'],'sk'=>$keys['oauth_token_secret']));
		return $aurl;
	}
		
	public function callback(){	

		$t=$this->readToken(0);

		if (!is_array($t))
			return false;

		$o = new MBOpenTOAuth( $this->akey , $this->skey , $t['tk'] , $t['sk']  );		
		$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
		
		//print_r($last_key);exit;
		if($last_key && $last_key['oauth_token']){
			$t=array(
				"tk"=>$last_key['oauth_token'],
				"sk"=>$last_key['oauth_token_secret'],
				"name"=>$last_key['name']
			);
			
			$this->tokenOrlfromuid=$t;
			$uidarr=$this->getUserInfo();
			$uidarr['tk']=$t['tk'];
			$uidarr['sk']=$t['sk'];

			$this->saveToken($uidarr['lfromuid'],$t);
			return $uidarr;
		}
		else
			return false;
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
		//print_r($token);echo $this->akey;echo $this->skey;
		$this->oClient=$c = new MBApiClient( $this->akey , $this->skey , $token['tk'] , $token['sk']);
		return $c;
	}	
	
	public function getUserInfo(){
		$oarr0=$this->getClient()->getUserInfo();	
		//print_r($oarr0);
		$oarr=$oarr0['data'];

		return $this->convertUserInfo($oarr);
	}
	//$type =2表示是粉丝列表，1为好友 ,0为我自己的信息
	private function convertUserInfo($oarr,$type=0) { 

		$uidarr=array();
		$uidarr['screen_name']=$oarr['nick'];
		$uidarr['name']=$oarr['name'];
		$uidarr['location']=$oarr['location'];
		$uidarr['description']=$oarr['introduction'];
		$uidarr['url']=$oarr[''];
		$uidarr['avatar']=$oarr['head'];
		$uidarr['domain']=$oarr['name'];
		$uidarr['sex']=$oarr['sex'];
		$uidarr['followers']=$oarr['fansnum'];
		$uidarr['followings']=$oarr['idolnum'];
		$uidarr['tweets']=$oarr['tweetnum'];

		$uidarr['verified']=$oarr['isvip'];
		$uidarr['verifyinfo']=$oarr['verifyinfo'];
		$uidarr['type'] =$oarr['Isidol']?3:$type;

		$lfromuid=$oarr["name"];
		$uidarr["kuid"]=envhelper::packKUID($this->lfrom,$lfromuid);
		$uidarr["lfromuid"]=$lfromuid;
		$uidarr['lfrom']=$this->lfrom;
		$uidarr['name']=$uidarr['screen_name'];

		return $uidarr;
}
	public function update( $status, $reply_id = NULL, $lat = NULL, $long = NULL, $annotations = NULL ){
		$p =array(
			'c' =>$status,
			'ip' => $_SERVER['REMOTE_ADDR'], 
			'j' => '',
			'w' => ''
		);

		return $this->getClient()->postOne($p);
	}

	public function upload( $status , $pic_path, $lat = NULL, $long = NULL ){
		$p=array(
			'c'=>$status,
			'ip'=>$_SERVER['REMOTE_ADDR'], 
			'j' => '',
			'w' => ''
			);

		if($pic_path && $pic_path!=''){
			$ext = strtolower(pathinfo($pic_path , PATHINFO_EXTENSION ));
			$mime = OAuthUtil::get_image_mime($pic_path);
			$p['p'] = array($mime,'tmp.'.$ext,file_get_contents($pic_path));
		}	
		
		$rel=$this->getClient()->postOne($p);

		$wbid=$rel['data'];
		$wbid=$wbid['id'];

		$p=array('id'=>$wbid);
		$wb=$this->getClient()->getOne($p);

		$pic=$wb['data']['image'][0];
		$rel=array('id'=>$wbid,'thumbnail_pic'=>$pic."/160",'bmiddle_pic'=>$pic."/460",'original_pic'=>$pic."/2000");
		//print_r($rel);
		return $rel;
	}

	public function del( $sid ){
		return $this->getClient()->delete($sid);
	}

	public function show_user( $uid_or_name ){
		return $this->getClient()->show_user( $uid_or_name );
	}

	public function friends( $cursor = NULL , $count = 20 , $uid_or_name = NULL ){
		$back=$this->getClient()->getMyfans(array('n'=>$uid_or_name,'type'=>1,'start'=>$cursor,'num'=>$count));
		
		$list=$back['data']['info'];

		$arrs=array();
		if(is_array($list)) {
			foreach($list as $li){
				$arrs[]=$this->convertUserInfo($li,1);
			}
		}
		if($back['data']['hasNext']==0) {
			$has=1;
		}
		else $has=0;
		return array("next_cursor"=>$has,"users"=>$arrs);
	}

	public function followers( $cursor = NULL , $count = NULL , $uid_or_name = NULL ){
		$back=$this->getClient()->getMyfans(array('n'=>$uid_or_name,'type'=>0,'start'=>$cursor,'num'=>$count));
		
		$list=$back['data']['info'];

		$arrs=array();
		if(is_array($list)) {
			foreach($list as $li){
				$arrs[]=$this->convertUserInfo($li,2);
			}
		}

		if($back['data']['hasNext']==0) {
			$has=1;
		}
		else $has=0;
		return array("next_cursor"=>$has,"users"=>$arrs);
	}

	public function follow( $uid_or_name ){
		$p =array(
			'n' => $uid_or_name,
			'type' => 1
		);
		return $this->getClient()->setMyidol($p);
	}

	public function unfollow( $uid_or_name ){
		$p =array(
			'n' => $uid_or_name,
			'type' => 0	
		);
		return $this->getClient()->setMyidol($p);
	}

	public function hot_users( $category = "default" ){
		throw new Exception();
	}

//是我跟随的吗？
	public function is_followed( $target, $source = NULL ){
		return $this->getClient()->checkFriend(array("n"=>$target,"type"=>1));
	}
//是我的粉丝吗？
	public function is_follwers( $target, $source = NULL ){
		return $this->getClient()->checkFriend(array("n"=>$target,"type"=>0));
	}
	public function end_session(){
		//return $this->getClient()->end_session();
	}

	public function update_profile_image($image_path){
		throw new Exception();
	}


}