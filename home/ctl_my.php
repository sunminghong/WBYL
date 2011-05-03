<?php
if(!defined('ISWBYL')) exit('Access Denied');

class my extends ctl_base {

	public function index(){
		$this->set("title","我的地盘");	

		$this->display("home_my_index");
	}
		
	public function home_timeline(){
/*$aa=Array
        (
            [created_at] => Tue Apr 26 12:03:12 +0800 2011
            [id] => 9661134511
            [text] => 腾讯起诉360不正当竞争案刚刚宣判：1.360停止发行360隐私保护器；2.360删除涉案侵权内容的宣传；3.在360网站的首页及法制日报上公开发表声明，消除不利影响；4.360赔偿损失40万元；5.驳回其他诉讼请求。
            [source] => "<a href="http://weibo.com" rel="nofollow">新浪微博</a>" );*/

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
	}

	public function follow() {
		$uid=rq("fuid",false);
		if(!$uid) return;
		
		$isFollow=rq("follow",1);
		$account=getAccount();

		$api="openapi_tsina";
			importlib($api);
			$api=new $api($account['kuid']); 

		if($isFollow)
			$ret=$api->follow($uid); // done	
		else
			$ret= $api->unfollow($uid); // done
		echo "1";exit;
	}
}
?>
