<?php
if(!defined('ISWBYL')) exit('Access Denied');

class account extends ctl_base
{
	function index(){ // 这里是首页
		$this->display("home_index");
	}

	function tologin(){		
		$lfrom=rq("lfrom","tsina");
		$callbackurl=URLBASE.'?act=account&op=callback&lfrom='.$lfrom.'&fromurl='.urlencode($_SERVER["HTTP_REFERER"]);
		
		$api="openapi_".$lfrom;
		importlib($api);
		$api=new $api();	
		$tourl=$api->getLoginUrl($callbackurl);
		header("Location: $tourl");
	}
	
	function callback(){
		$lfrom=rq("lfrom","tsina");
		$tourl=rq("fromurl","");
		
		$api="openapi_".$lfrom;
		importlib($api);
		$api=new $api();	

		$uidarr=$api->callback();
		if(!$uidarr){ //登录失败
			$tourl=WEBROOT."err_login.htm";
			header("Location: $tourl");exit;
		}else{
			importlib("ppt_class");
			$ppt=new ppt();
			$uid=$ppt->login($uidarr);
			$uidarr['uid']=$uid;

			envhelper::saveAccounts(envhelper::packKUID($lfrom,$uidarr['lfromuid']),$uidarr);

			if(!$tourl)
			$tourl="?act=my";
			header("Location: $tourl");
		}
	}

	function logout(){
		
		$account=getAccount();
		if(is_array($account) && isset($account["lfrom"]) && isset($account['lfromuid']))
		{
			$this->getApi()->end_session();
		}
		
		envhelper::clearAccounts();
	
		$tourl=$_SERVER["HTTP_REFERER"];
		if(!$tourl)
			$tourl="index.php";
		
		header("Location: $tourl");
	}
}	

?>