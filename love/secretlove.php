<?php
if(!defined('APPIN')) {
	exit('Access Denied');
}

require_once(ROOT."./inc/sdk/api_client.php");
$skey = SESS::get('last_key');
$myinfo = userinfo();


//获取当前用户的信息
function userinfo(){
	global $skey;
	$cache = SESS::get('myuserinfo');
	if(is_array($cache)){
		return $cache;	
	}else{
		$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
		$myinfo = $c->getUserInfo();
		SESS::set('myuserinfo',$myinfo);
		return $myinfo;
	}
}

//根据fromuid和touid，获取数据库记录
function getdb($fromuid,$touid,$ok){
	$sql = "select * from secretlove where fromuid='{$fromuid}' and touid='{$touid}' and ok={$ok} limit 1";
	return DB::sqlgetall($sql);
}

//提交暗恋对象
function submit(){
	global $myinfo;
	$toname = rq('touid');
	$fromuid = $myinfo['data']['name'];
	$fromnick = $myinfo['data']['nick'];
	
	if($toname=='')exit;
	if($toname==$fromuid){
		we('alert("你敢再自恋点么？")');	
	}
	
	$db = getdb($fromuid,$toname,1);
	if(count($db)>0){
		we('alert("你们已经互为暗恋对象的呀 还需要再表白一次吗？")');		
	}
	
	$db = getdb($fromuid,$toname,0);
	if(count($db)>0){
		we('alert("发布成功 点击【转播到腾讯微博】让Ta也来试试吧~~")');		
	}

	$db = getdb($toname,$fromuid,0);
	
	if(count($db)>0){
		$sql = DB::update('secretlove',array('ok'=>1,'oktime'=>now()),'id='.$db[0]['id']);
		DB::openrs($sql);		
		
		//发私信
		//global $skey;
		//$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
		/******************
		*发私信
		*@c: 微博内容
		*@ip: 用户IP(以分析用户所在地)
		*@j: 经度（可以填空）
		*@w: 纬度（可以填空）
		*@n: 接收方微博帐号
		**********************/
		//$p = array('c'=>'#我在暗恋你#送囍：天啦！你和'.$db[0]['fromnick'].'(@'. $db[0]['fromuid'] .')居然是互相暗恋的对象，快联系Ta吧！祝有缘人幸福。','ip'=>getip(),'j'=>'','w'=>'','n'=>$fromuid);
		//$c->postOneMail($p);	
		//$p = array('c'=>'#我在暗恋你#送囍：天啦！你和'.$fromnick.'(@'. $fromuid .')居然是互相暗恋的对象，快联系Ta吧！祝有缘人幸福。','ip'=>getip(),'j'=>'','w'=>'','n'=>$toname);
		//$c->postOneMail($p);		
		echo 'peiduiover("'. $db[0]['fromuid'] .'","'. $db[0]['fromnick'] .'");';		
		exit;
	}
	
	$sql = DB::insert('secretlove',array('fromuid'=>$fromuid,'fromnick'=>$fromnick,'touid'=>$toname,'ok'=>0,'ctime'=>now()));
	DB::openrs($sql);
	we('alert("发布成功 点击【转播到腾讯微博】让Ta也来试试吧~");');
}

function post(){
	global $skey,$indexUrl;
	$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
	/*
	*@c: 微博内容
	*@ip: 用户IP(以分析用户所在地)
	*@j: 经度（可以填空）
	*@w: 纬度（可以填空）
	*@p: 图片
	*@r: 父id
	*@type: 1 发表 2 转播 3 回复 4 点评
	**********************/
	$p = array(
		'c'=>'/害羞 嘿，我在暗恋你，会不会刚好你也恋着我？你们也来试试吧？#我在暗恋你# 点击进入：'.str_replace('index.php','',$indexUrl).'?fn=secretlove',
		'ip'=>getip(),
		'j'=>'',
		'w'=>'',
		'type'=>1,
		'p'=>array('image/jpeg','friendship_by_pukixitah.jpg',file_get_contents(ROOT.'./image/pic/'. rand(1,29) .'.jpg')),
	);
	$res = $c->postOne($p);
	//print_r($res);
	we('alert("转播成功");');
}

function over(){
	$zb = rq('zb');
	$st = rq('st');
	$to = rq('to');
	
	global $skey,$indexUrl;
	$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
		
	if($zb==1){
		$p = array(
			'c'=>'/爱心 天啦！我和@'. $to .'居然是互相暗恋的对象！你们也来试试吧~ #我在暗恋你# 点击进入：'.str_replace('index.php','',$indexUrl).'?fn=secretlove',
			'ip'=>getip(),
			'j'=>'',
			'w'=>'',
			'type'=>1,
		);
		$res = $c->postOne($p);	
	}
	
	if($zb==1){
		$p=array(
			'n'=>'bystory',
			'type'=>1,
		);
		$res = $c->setMyidol($p);
	}
}

$act = rq('act');
if(!empty($act)){
	if(function_exists($act)){
		eval($act.'();');	
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我在暗恋你</title>
<link rel="shortcut icon" href="http://mat1.gtimg.com/www/mb/favicon.ico"/> 
<link href="image/public.css" rel="stylesheet" type="text/css" />
<link href="image/secretlove/css.css" rel="stylesheet" type="text/css" />
<script src="image/jquery.min.js"></script>
<script src="image/public.js"></script>
<script src="image/secretlove/js.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="image/secretlove/pngfix.js"></script>
<style type="text/css">.pngfix{behavior: url("image/secretlove/iepngfix.htc");}</style>
<![endif]-->
</head>
<body>
<div class="task" style="display:none"></div>

<div id="logo" class="pngfix"></div>
<div id="content" class="tips-box" style="height:355px; overflow:hidden">

	<div class="ok pngfix">
    	<div class="okname"></div>
    	<div class="checkbox">
        	<input name="okzb" id="okzb" type="checkbox" value="1" checked="checked" />转播到我的微博 <br/>
            <input name="okst" id="okst" type="checkbox" value="1" checked="checked" />收听官方微博 <br/>
        </div>
        <img src="image/secretlove/sure.gif" id="oksure" />
    </div>
    
	<div class="info tips-box">
   	  <img class="myhead" src="<?php echo $myinfo['data']['head']?>/100" />
	  <div class="intr">
        <p class="p"><a target="_blank" href="http://t.qq.com/<?php echo $myinfo['data']['name']?>"><?php echo $myinfo['data']['nick']?>(@<?php echo $myinfo['data']['name']?>)</a>，如果你暗恋某个人，可以在这里悄悄告诉我，如果你暗恋的人正好暗恋你，系统就会发私信告诉你们俩啦~</p>
        <p class="p" style="margin-top:10px;">发布暗恋信息是不会公开的，请放心使用，祝有缘人幸福！</p>
        </div>
  </div>    
    <div class="inputdiv clear pngfix">
        <input name="toname" type="text" class="input first tips-box" id="toname" value="直接输入好友的账号或点选好友" maxlength="50"/> <a href="###" id="cfbut"><img id="submit" src="image/secretlove/cf.jpg" /></a>        
      <div class="button"><a href="javascript:;" id="submita" onclick="submit()">发布暗恋信息</a></div>
    </div>    
	<div class="zhuanbo">
    	<textarea name="zhuanbocontent" id="zhuanbocontent">/害羞 嘿，我在暗恋你，会不会刚好你也恋着我？#我在暗恋你# 你们也来试试吧？<?php echo str_replace('index.php','',$indexUrl).'?fn=secretlove'?></textarea>
        <div id="txWB_W1"></div> <a href="javascript:;" onclick="post()"><img src="image/secretlove/b32.png" id="zhuanbobtn" /></a>
    </div>
</div>
<script type="text/javascript">var tencent_wb_name = "bystory";var tencent_wb_sign = "72579d0b273955134abca40950299f1491ac8d8a";var tencent_wb_style = "2";</script><script type="text/javascript" src="http://v.t.qq.com/follow/widget.js" charset="utf-8"/></script>

<div style="display:none"><script src="http://s20.cnzz.com/stat.php?id=3027510&web_id=3027510" language="JavaScript"></script></div>
</body>
</html>
