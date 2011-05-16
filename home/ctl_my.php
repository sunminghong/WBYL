<?php
if(!defined('ISWBYL')) exit('Access Denied');

class my extends ctl_base {

	public function index(){
		$this->set("title","我的地盘");	

		$this->display("home_my_index");
	}
	/*
	public function home_timeline(){
		$kuids=rq("kuid","");
		if (!$kuids) return "";

		$kuidarr=explode(",",$kuids);

		$ms=array();
		foreach($kuidarr as $kuid){
			$k=envhelper::parseKUID($kuid);
			$lfrom=$k['lfrom'];

			$api="sdk_".$lfrom."\openapi_class";
			importlib($api);
			$api=new openapi($kuid); 

			$ret=$api->home_timeline(); // done	
		}		
		echo json_encode($ms);
		exit;
	}
	
public function public_timeline(){

		$kuids=rq("kuid","");
		if (!$kuids) return "";

		$kuidarr=explode(",",$kuids);

		$ms=array();
		foreach($kuidarr as $kuid){
			$k=envhelper::parseKUID($kuid);
			$lfrom=$k['lfrom'];

			$api="sdk_".$lfrom."\openapi_class";
			importlib($api);
			$api=new openapi($kuid); 

			$ret=$api->home_timeline(); // done	
						
		}
		echo json_encode($ms);
		exit;
	}*/

	public function follow() {
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}
		
		$api=$this->getApi();
		$account=getAccount();

		if($account['lfrom']=='tqq')
			$uid="yihuiso";
		else
			$uid="1747738583";

		$isFollow=rq("follow",1);
		

		if($isFollow)
			$ret=$api->follow($uid); // done	
		else
			$ret= $api->unfollow($uid); // done
		echo "1";exit;
	}

	function syncfriends(){

		$api=$this->getApi();
		$account=getAccount();

		importlib("ppt_class");
		$ppt=new ppt();
		if(!$ppt->snsNeedSync($account['uid'])) {
			echo 0;exit;
		}
		$ppt-> syncSNS($api,1,$account['uid'],500,-1,$account['lfromuid']);

		$ppt-> syncSNS($api,2,$account['uid'],500,-1,$account['lfromuid']);
		echo '1'; exit;
	}
	
	function syncfollowers(){

		$api=$this->getApi();
		$account=getAccount();

		importlib("ppt_class");
		$ppt=new ppt();
		if(!$ppt->snsNeedSync($account['uid'])) {
			echo 0;exit;
		}
		$ppt-> syncSNS($api,2,$account['uid'],$account['lfromuid'],500);
		echo 1;exit;
	}
}
?>
