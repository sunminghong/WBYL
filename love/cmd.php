<?php
require_once(ROOT."./inc/sdk/api_client.php");
$skey = SESS::get('last_key');

$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
//鏃堕棿绾?
$p =array(
	'f' => 0,
	't' => 0,		
	'n' => 5 
);
//var_dump($c->getTimeline($p));

//鎷夊彇username鐨勪俊鎭?
$p =array(
	'f' => 0,
	't' => 0,		
	'n' => 5,
   	'name' => 'bystory'	
);
print_r($c->getTimeline($p));

//鎷夊彇骞挎挱澶у巺娑堟伅
$p =array(
	'p' => 0,
	'n' => 5		
);
//var_dump($c->getPublic($p));

//鎷夊彇鍏充簬鎴戠殑娑堟伅
$p =array(
	'f' => 0,
	'n' => 5,		
	't' => 0,
	'l' => '',
	'type' => 1
);
//var_dump($c->getMyTweet($p));
//
//鍗曟潯娑堟伅
$p =array(
	'id' => 26016073563599 
);
//var_dump($c->getOne($p));
//
//鍙戞秷鎭?
//	*@content: 寰崥鍐呭
$p =array(
	'c' => '鐏溅渚?,
	'ip' => $_SERVER['REMOTE_ADDR'], 
	'j' => '',
	'w' => ''
);
//var_dump($c->postOne($p));
//
//	*@content: 寰崥鍐呭
$p =array(
	'id' => 14511064212422
);
//var_dump($c->delOne($p));
$p =array(
	'c' => '杞挱鐏溅渚?,
	'ip' => $_SERVER['REMOTE_ADDR'], 
	'j' => '',
	'w' => '',
	'type' => 1,
	'r' => 10511064707448 
);
//var_dump($c->postOne($p));

$p =array(
	'c' => '杞挱鐏溅渚?,
	'ip' => $_SERVER['REMOTE_ADDR'], 
	'j' => '',
	'w' => '',
	'type' => 2,
	'r' => 10511064707448 
);
//var_dump($c->postOne($p));


$p =array(
	'n' => 20, 
	'f' => 0,
	'reid' => 11016107749292 
);
//print_r($c->getReplay($p));
//print_r($c->getUserInfo());
$p =array(
	'n' => 'username', 
);
//print_r($c->getUserInfo($p));

//
$p =array(
	'n' => 'username',
    'type' => 2	
);
//print_r($c->setMyidol($p));

$p =array(
	'f' => 0,
	'n' => 5,		
	't' => 0,
	'type' => 0
);
//print_r($c->getMailBox($p));
//
$p =array(
	'k' => 'username',
	'n' => 10,		
	'p' => 0,
	'type' => 2
);
//print_r($c->getSearch($p));
//
////
$p =array(
	'type' => 3,		
	'n' => 5,
	'pos' => 0
);
//print_r($c->getHotTopic($p));

$p =array(
	'op' => 0		
);
//print_r($c->getUpdate($p));
//31016124947861
$p =array(
	'id' => 31016124947861,
	'type' => 0	
);
//print_r($c->postFavMsg($p));

$p =array(
	'id' => 10109507010925991304,
	'type' => 0	
);
//print_r($c->postFavTopic($p));
//
$p =array(
	'f' => 0,
	'n' => 5,
	't' => 0,
	'lid' => 0,
	'type' => 1	
);
//print_r($c->getFav($p));
$p =array(
	'list' => '鍥涘窛娲伨,寰崥绠€鏄撻璋?
);
//print_r($c->getTopicId($p));
//
$p =array(
	'list' => '10109507010925991304,14318500857773196362'
);
//print_r($c->getTopicList($p));
?>
