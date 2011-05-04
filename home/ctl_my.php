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

			$api="openapi_".$lfrom;
			importlib($api);
			$api=new $api($kuid); 

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

			$api="openapi_".$lfrom;
			importlib($api);
			$api=new $api($kuid); 

			$ret=$api->home_timeline(); // done	
						
		}
		echo json_encode($ms);
		exit;
	}*/

	public function follow() {
		//$uid=rq("fuid","1747738583");
		//if(!$uid) return;
		
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
		$list=$api->friends(-1,100,$account['lfromuid']);
		$ppt-> syncSNS($list,0,$account['uid']);

		$list=$api->followers(-1,100,$account['lfromuid']);
		$ppt-> syncSNS($list,1,$account['uid']);
		echo '1'; exit;
	}
	
	function syncfollowers(){
		$api=$this->getApi();
		$account=getAccount();
		
		$list=$api->followers(-1,100,$account['lfromuid']);
		importlib("ppt_class");
		$ppt=new ppt();
		$ppt-> syncSNS($list,1,$account['uid']);

		echo 1;exit;
	}
}
?>
