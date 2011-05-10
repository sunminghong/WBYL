<?php
if(!defined('APPIN')) {
	exit('Access Denied');
}

require_once(ROOT."./inc/sdk/api_client.php");
$skey = SESS::get('last_key');
$myinfo = userinfo();


//鑾峰彇褰撳墠鐢ㄦ埛鐨勪俊鎭?
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

//鏍规嵁fromuid鍜宼ouid锛岃幏鍙栨暟鎹簱璁板綍
function getdb($fromuid,$touid,$ok){
	$sql = "select * from secretlove where fromuid='{$fromuid}' and touid='{$touid}' and ok={$ok} limit 1";
	return DB::sqlgetall($sql);
}

//鎻愪氦鏆楁亱瀵硅薄
function submit(){
	global $myinfo;
	$toname = rq('touid');
	$fromuid = $myinfo['data']['name'];
	$fromnick = $myinfo['data']['nick'];
	
	if($toname=='')exit;
	if($toname==$fromuid){
		we('alert("浣犳暍鍐嶈嚜鎭嬬偣涔堬紵")');	
	}
	
	$db = getdb($fromuid,$toname,1);
	if(count($db)>0){
		we('alert("浣犱滑宸茬粡浜掍负鏆楁亱瀵硅薄鐨勫憖 杩橀渶瑕佸啀琛ㄧ櫧涓€娆″悧锛?)');		
	}
	
	$db = getdb($fromuid,$toname,0);
	if(count($db)>0){
		we('alert("鍙戝竷鎴愬姛 鐐瑰嚮銆愯浆鎾埌鑵捐寰崥銆戣Ta涔熸潵璇曡瘯鍚~")');		
	}

	$db = getdb($toname,$fromuid,0);
	
	if(count($db)>0){
		$sql = DB::update('secretlove',array('ok'=>1,'oktime'=>now()),'id='.$db[0]['id']);
		DB::openrs($sql);		
		
		//鍙戠淇?
		//global $skey;
		//$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
		/******************
		*鍙戠淇?
		*@c: 寰崥鍐呭
		*@ip: 鐢ㄦ埛IP(浠ュ垎鏋愮敤鎴锋墍鍦ㄥ湴)
		*@j: 缁忓害锛堝彲浠ュ～绌猴級
		*@w: 绾害锛堝彲浠ュ～绌猴級
		*@n: 鎺ユ敹鏂瑰井鍗氬笎鍙?
		**********************/
		//$p = array('c'=>'#鎴戝湪鏆楁亱浣?閫佸泹锛氬ぉ鍟︼紒浣犲拰'.$db[0]['fromnick'].'(@'. $db[0]['fromuid'] .')灞呯劧鏄簰鐩告殫鎭嬬殑瀵硅薄锛屽揩鑱旂郴Ta鍚э紒绁濇湁缂樹汉骞哥銆?,'ip'=>getip(),'j'=>'','w'=>'','n'=>$fromuid);
		//$c->postOneMail($p);	
		//$p = array('c'=>'#鎴戝湪鏆楁亱浣?閫佸泹锛氬ぉ鍟︼紒浣犲拰'.$fromnick.'(@'. $fromuid .')灞呯劧鏄簰鐩告殫鎭嬬殑瀵硅薄锛屽揩鑱旂郴Ta鍚э紒绁濇湁缂樹汉骞哥銆?,'ip'=>getip(),'j'=>'','w'=>'','n'=>$toname);
		//$c->postOneMail($p);		
		echo 'peiduiover("'. $db[0]['fromuid'] .'","'. $db[0]['fromnick'] .'");';		
		exit;
	}
	
	$sql = DB::insert('secretlove',array('fromuid'=>$fromuid,'fromnick'=>$fromnick,'touid'=>$toname,'ok'=>0,'ctime'=>now()));
	DB::openrs($sql);
	we('alert("鍙戝竷鎴愬姛 鐐瑰嚮銆愯浆鎾埌鑵捐寰崥銆戣Ta涔熸潵璇曡瘯鍚");');
}

function post(){
	global $skey,$indexUrl;
	$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
	/*
	*@c: 寰崥鍐呭
	*@ip: 鐢ㄦ埛IP(浠ュ垎鏋愮敤鎴锋墍鍦ㄥ湴)
	*@j: 缁忓害锛堝彲浠ュ～绌猴級
	*@w: 绾害锛堝彲浠ュ～绌猴級
	*@p: 鍥剧墖
	*@r: 鐖秈d
	*@type: 1 鍙戣〃 2 杞挱 3 鍥炲 4 鐐硅瘎
	**********************/
	$p = array(
		'c'=>'/瀹崇緸 鍢匡紝鎴戝湪鏆楁亱浣狅紝浼氫笉浼氬垰濂戒綘涔熸亱鐫€鎴戯紵浣犱滑涔熸潵璇曡瘯鍚э紵#鎴戝湪鏆楁亱浣? 鐐瑰嚮杩涘叆锛?.str_replace('index.php','',$indexUrl).'?fn=secretlove',
		'ip'=>getip(),
		'j'=>'',
		'w'=>'',
		'type'=>1,
		'p'=>array('image/jpeg','friendship_by_pukixitah.jpg',file_get_contents(ROOT.'./image/pic/'. rand(1,29) .'.jpg')),
	);
	$res = $c->postOne($p);
	//print_r($res);
	we('alert("杞挱鎴愬姛");');
}

function over(){
	$zb = rq('zb');
	$st = rq('st');
	$to = rq('to');
	
	global $skey,$indexUrl;
	$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']  );
		
	if($zb==1){
		$p = array(
			'c'=>'/鐖卞績 澶╁暒锛佹垜鍜孈'. $to .'灞呯劧鏄簰鐩告殫鎭嬬殑瀵硅薄锛佷綘浠篃鏉ヨ瘯璇曞惂~ #鎴戝湪鏆楁亱浣? 鐐瑰嚮杩涘叆锛?.str_replace('index.php','',$indexUrl).'?fn=secretlove',
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
<title>鎴戝湪鏆楁亱浣?/title>
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
        	<input name="okzb" id="okzb" type="checkbox" value="1" checked="checked" />杞挱鍒版垜鐨勫井鍗?<br/>
            <input name="okst" id="okst" type="checkbox" value="1" checked="checked" />鏀跺惉瀹樻柟寰崥 <br/>
        </div>
        <img src="image/secretlove/sure.gif" id="oksure" />
    </div>
    
	<div class="info tips-box">
   	  <img class="myhead" src="<?php echo $myinfo['data']['head']?>/100" />
	  <div class="intr">
        <p class="p"><a target="_blank" href="http://t.qq.com/<?php echo $myinfo['data']['name']?>"><?php echo $myinfo['data']['nick']?>(@<?php echo $myinfo['data']['name']?>)</a>锛屽鏋滀綘鏆楁亱鏌愪釜浜猴紝鍙互鍦ㄨ繖閲屾倓鎮勫憡璇夋垜锛屽鏋滀綘鏆楁亱鐨勪汉姝ｅソ鏆楁亱浣狅紝绯荤粺灏变細鍙戠淇″憡璇変綘浠咯鍟</p>
        <p class="p" style="margin-top:10px;">鍙戝竷鏆楁亱淇℃伅鏄笉浼氬叕寮€鐨勶紝璇锋斁蹇冧娇鐢紝绁濇湁缂樹汉骞哥锛?/p>
        </div>
  </div>    
    <div class="inputdiv clear pngfix">
        <input name="toname" type="text" class="input first tips-box" id="toname" value="鐩存帴杈撳叆濂藉弸鐨勮处鍙锋垨鐐归€夊ソ鍙? maxlength="50"/> <a href="###" id="cfbut"><img id="submit" src="image/secretlove/cf.jpg" /></a>        
      <div class="button"><a href="javascript:;" id="submita" onclick="submit()">鍙戝竷鏆楁亱淇℃伅</a></div>
    </div>    
	<div class="zhuanbo">
    	<textarea name="zhuanbocontent" id="zhuanbocontent">/瀹崇緸 鍢匡紝鎴戝湪鏆楁亱浣狅紝浼氫笉浼氬垰濂戒綘涔熸亱鐫€鎴戯紵#鎴戝湪鏆楁亱浣? 浣犱滑涔熸潵璇曡瘯鍚э紵<?php echo str_replace('index.php','',$indexUrl).'?fn=secretlove'?></textarea>
        <div id="txWB_W1"></div> <a href="javascript:;" onclick="post()"><img src="image/secretlove/b32.png" id="zhuanbobtn" /></a>
    </div>
</div>
<script type="text/javascript">var tencent_wb_name = "bystory";var tencent_wb_sign = "72579d0b273955134abca40950299f1491ac8d8a";var tencent_wb_style = "2";</script><script type="text/javascript" src="http://v.t.qq.com/follow/widget.js" charset="utf-8"/></script>

<div style="display:none"><script src="http://s20.cnzz.com/stat.php?id=3027510&web_id=3027510" language="JavaScript"></script></div>
</body>
</html>
