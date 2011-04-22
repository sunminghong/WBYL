<?php
if(!defined('ISWBYL')) exit('Access Denied');

class account extends ctl_base
{
	function index(){ // 这里是首页
		$this->display("home_index");
	}

	function tologin(){
		$lfrom=rq("lfrom","tsina");
		$callbackurl=URLBASE.'?app=home&act=account&op=callback&lfrom=tsina';
		
		$api="openapi_".$lfrom;
		importlib($api);
		$api=new $api();	
		$tourl=$api->getLoginUrl($callbackurl);
		header("Location: $tourl");
	}
	
	function callback(){
		$lfrom=rq("lfrom","tsina");
		
		$api="openapi_".$lfrom;
		importlib($api);
		$api=new $api();	


		$uidarr=$api->callback();
		if(!$uidarr){ //登录失败
			$tourl=WEBROOT."err_login.htm";
			header("Location: $tourl");exit;
		}else{
			$ppt=new ppt();
			$uid=$ppt->login($uidarr);
					
			$userinfo=array(
				"lfrom"=>$lfrom,
				"lfromuid"=>$uidarr['lfromuid'],
				"name"=>$uidarr["name"],
				"uid"=>$uid
			);
			envhelper::saveToken(envhelper::packKUID($lfrom,$userinfo['lfromuid']),$userinfo);			
			
			$tourl="?app=home&act=my";
			header("Location: $tourl");
		}
	}
	
}	

?>
