<?php
if(!defined('ISWBYL')) exit('Access Denied');

class my extends ctl_base {

	public function index(){
		$this->set("title","我的地盘");	

		$this->set("users",envhelper::readToken());
		$this->display("home_my_index");
	}
		
	public function home_timeline(){echo '[{"created_at":"Fri Apr 22 18:02:44 +0800 2011","id":9506123246,"text":"[\u592a\u5f00\u5fc3] \u53c8\u662f\u4e00\u4e2a\u5468\u672b","source":"<a href=\"http:\/\/weibo.com\" rel=\"nofollow\">\u65b0\u6d6a\u5fae\u535a<\/a>","favorited":false,"truncated":false,"in_reply_to_status_id":"","in_reply_to_user_id":"","in_reply_to_screen_name":"","geo":null,"mid":"5598346264670904802","user":{"id":2100628797,"screen_name":"zhuangxinli","name":"zhuangxinli","province":"37","city":"11","location":"\u5c71\u4e1c \u65e5\u7167","description":"","url":"","profile_image_url":"http:\/\/tp2.sinaimg.cn\/2100628797\/50\/5598433443\/1","domain":"","gender":"m","followers_count":0,"friends_count":31,"statuses_count":0,"favourites_count":0,"created_at":"Fri Apr 22 00:00:00 +0800 2011","following":false,"allow_all_act_msg":false,"geo_enabled":true,"verified":false},"annotations":[{"cartoon":false}]}]';
exit;
		$kuids=rq("kuid","");
		if (!$kuids) return "";

		$kuidarr=explode(",",$kuids);
		$users = envhelper::readToken();

		$ms=array();
		foreach($kuidarr as $kuid){
			$k=envhelper::parseKUID($kuid);
			$lfrom=$k['lfrom'];

			$api="openapi_".$lfrom;
			importlib($api);
			$api=new $api(); 

			$client=$api->getClient($kuid);	
			///print_r($api->getUserInfo($kuid));
			$ms  = $client->public_timeline(); // done	
						
		}
		echo json_encode($ms);
		exit;
	}
	
	public function public_timeline(){


		$kuids=rq("kuid","");
		if (!$kuids) return "";

		$kuidarr=explode(",",$kuids);
		$users = envhelper::readToken();

		$ms=array();
		foreach($kuidarr as $kuid){
			$k=envhelper::parseKUID($kuid);
			$lfrom=$k['lfrom'];

			$api="openapi_".$lfrom;
			importlib($api);
			$api=new $api(); 

			$client=$api->getClient($kuid);	
			///print_r($api->getUserInfo($kuid));
			$ms  = $client->home_timeline(); // done	
						
		}
		echo json_encode($ms);
		exit;
	}

}
?>
