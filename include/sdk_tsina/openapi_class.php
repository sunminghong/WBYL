<?php
if(!defined('ISWBYL')) exit('Access Denied');
importlib('openapi_abstract_class');

include_once('oauth_class.php');
include_once('weiboclient_class.php');

class openapi extends openapiAbstract{

	private $oClient=null;
	private $akey;
	private $skey;
	
	function __construct($tokenOrlfromuid=""){
		$this->name="新浪微博";
		$this->lfrom="tsina";

		$this->akey=$GLOBALS['apiConfig'][$this->lfrom]["access_key"];
		$this->skey=$GLOBALS['apiConfig'][$this->lfrom]["screct_key"];
		
		$this->tokenOrlfromuid=$tokenOrlfromuid;
	}
		
	public function getLoginUrl($callbackurl){
		$o = new OAuth( $this->akey , $this->skey);
		
		$keys = $o->getRequestToken();
		$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callbackurl);
		
		$this->saveToken(0,array('uid'=>0,'tk'=>$keys['oauth_token'],'sk'=>$keys['oauth_token_secret']));
		return $aurl;
	}
		
	public function callback(){	
		$t=$this->readToken(0);

		if (!is_array($t))
			return false;
				
		$o = new OAuth( $this->akey , $this->skey , $t['tk'] , $t['sk']  );		
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
		$this->oClient=$c = new WeiboClient( $this->akey , $this->skey , $token['tk'] , $token['sk']);
		return $c;
	}	
	
	public function getUserInfo(){
		$oarr=$this->getClient()->getUserInfo(); 
		return $this->convertUserInfo($oarr);
	}

	//$type =2表示是粉丝列表，1为好友 ,0为我自己的信息
	private function convertUserInfo($oarr,$type=0) { 
		$uidarr=array();
		$uidarr['screen_name']=$oarr['screen_name'];
		$uidarr['name']=$oarr['name'];
		$uidarr['location']=$oarr['location'];
		$uidarr['description']=$oarr['description'];
		$uidarr['url']=$oarr['url'];
		$uidarr["avatar"]=$oarr["profile_image_url"];
		$uidarr['domain']=empty($oarr['domain'])?$oarr['id']:$oarr['domain'];
		$uidarr['sex']=($oarr['gender']=="m")?1:(($oarr['gender']=="f")?2:0);
		$uidarr['followers']=$oarr['followers_count'];
		$uidarr['tweets']=$oarr['statuses_count'];
		$uidarr['followings']=$oarr['friends_count'];
		
		$uidarr['verified']=$oarr['verified'];
		$uidarr['verifyinfo']=$oarr[''];
		$uidarr['type'] =$oarr['following']?3:$type;

		$lfromuid=$oarr["id"];
		$uidarr["kuid"]=envhelper::packKUID($this->lfrom,$lfromuid);
		$uidarr["lfromuid"]=$lfromuid;
		$uidarr['lfrom']=$this->lfrom;
		$uidarr['name']=$uidarr['screen_name'];
//print_r($uidarr);exit;
		return $uidarr;
	}	

	public function update( $status, $reply_id = NULL, $lat = NULL, $long = NULL, $annotations = NULL ){
		$rel=$this->getClient()->update($status, $reply_id, $lat, $long, $annotations);
		$rel=array('wbid'=>$rel['id']);
		return $rel;
	}

	public function upload( $status , $pic_path, $lat = NULL, $long = NULL ){
		$rel= $this->getClient()-> upload( $status , $pic_path, $lat, $long);
		print_r($rel);
/*    [thumbnail_pic] => http://ww2.sinaimg.cn/thumbnail/682c5fd7jw1dh98natpehj.jpg
    [bmiddle_pic] => http://ww2.sinaimg.cn/bmiddle/682c5fd7jw1dh98natpehj.jpg
    [original_pic] => http://ww2.sinaimg.cn/large/682c5fd7jw1dh98natpehj.jpg*/
		$rel=array('wbid'=>$rel['id'],'small_pic'=>$rel['thumbnail_pic'],'middle_pic'=>$rel['bmiddle_pic'],'big_pic'=>$rel['original_pic']);
		return $rel;
	}

	public function repost($wbid,$msg,$is_comment=0) {
		$rel=$this->getClient()-> repost( $wbid , $msg, $is_comment );
		if(isset($rel['error_code']) && isset($rel['error'])) 
			return false;
		else
			return true;
	}
	
	/**
	 * 对一条微博信息进行评论
	 * 为防止重复，发布的信息与最后一条评论信息一样话，将会被忽略。
	 * 
	 * @access public
	 * @param int64 $sid 要评论的微博id
	 * @param string $text 评论内容
	 * @param int64 $cid 要评论的评论id
	 * @return array
	 */
	function send_comment( $sid , $text , $cid = NULL ) {
		return $this->getClient()-> repost( $wbid , $text, $cid );
	}

	public function del( $sid ){
		return $this->getClient()->delete($sid);
	}

	public function show_user( $uid_or_name ){
		return $this->getClient()->show_user( $uid_or_name );
	}

	public function friends( $cursor = NULL , $count = 20 , $uid_or_name = NULL ){
		$f_list=$this->getClient()->friends( $cursor , $count , $uid_or_name );
		$list=$f_list['users'];
		$arrs=array();
		if(is_array($list)) {
			foreach($list as $li){
				$arrs[]=$this->convertUserInfo($li);
			}
		}
		//echo $f_list["next_cursor"] ;
		return array("next_cursor"=>$f_list["next_cursor"] ,"users"=>$arrs);
	}

	public function followers( $cursor = NULL , $count = NULL , $uid_or_name = NULL ){
		$f_list=$this->getClient()->followers( $cursor, $count, $uid_or_name);
		$list=$f_list['users'];
		$arrs=array();
		if(is_array($list)) {
			foreach($list as $li){
				$arrs[]=$this->convertUserInfo($li);
			}
		}
		//echo $f_list["next_cursor"] ;
		return array("next_cursor"=>$f_list["next_cursor"],"users"=>$arrs);
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

	public function is_follwers( $target, $source = NULL ){
		throw new exception();
	}
	public function end_session(){
		return $this->getClient()->end_session();
	}

	public function update_profile_image($image_path){
		return $this->getClient()->update_profile_image($image_path);
	}


}