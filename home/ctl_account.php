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
		
		$api="sdk_".$lfrom."/openapi_class";
		importlib($api);
		$api=new openapi();	
		$tourl=$api->getLoginUrl($callbackurl);
		header("Location: $tourl");
	}

	function tologinmini(){		
		$lfrom=rq("lfrom","tsina");
		$callbackurl=URLBASE.'?act=account&op=callback&lfrom='.$lfrom.'&fromurl=mini';
		
		$api="sdk_".$lfrom."/openapi_class";
		importlib($api);
		$api=new openapi();	
		$tourl=$api->getLoginUrl($callbackurl);
		header("Location: $tourl");
	}

	function callback(){
		$lfrom=rq("lfrom","tsina");
		$tourl=rq("fromurl","");

		$api="sdk_".$lfrom."/openapi_class";		
		importlib($api);
		$api=new openapi();	

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
			elseif($tourl=="mini") {
				$uidjson=json_encode($uidarr);
				echo '<script type="text/javascript"> parent.loginback('.$uidjson.');</script>';
				return;
			}
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
		SESS::delall();
	
		$tourl=$_SERVER["HTTP_REFERER"];
		if(!$tourl)
			$tourl="index.php";
		
		header("Location: $tourl");
	}
}	

?>