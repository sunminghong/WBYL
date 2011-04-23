<?php
if(!defined('APPIN')) {
	exit('Access Denied');
}

require_once(ROOT."./inc/sdk/api_client.php");
$skey = SESS::get('last_key');
//读取列表
function load(){
	$type = rq('t');
	$start = rq('s');
	
	$sessKey = 'Fans_'. $type . '_' . $start;
	
	if(SESS::get($sessKey)!=''){
		$json = SESS::get($sessKey);
	}else{
		global $skey;
		$p = array('num'=>30,'start'=>$start,'n'=>'','type'=>$type);
		$c = new MBApiClient( MB_AKEY , MB_SKEY , $skey['oauth_token'] , $skey['oauth_token_secret']);	
		$arr = $c->getMyfans($p);
		
		$json = 'var data = {';
		$json .='	hasnext:'. $arr['data']['hasnext'] .',info:[';
		foreach($arr['data']['info'] as $key=>$val){
			$json .= "{name:'{$val['name']}',nick:'{$val['nick']}',head:'{$val['head']}'},";	
		}
		$json .= "'']};";
		SESS::set($sessKey,$json);
	}
	echo $json;	
	echo "fmtFansList(data);";
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
<link href="image/public.css" rel="stylesheet" type="text/css" />
<style>html,body{ background:#fff; overflow:hidden;}</style>
<script src="image/jquery.min.js"></script>
<script src="image/public.js"></script>
</head>
<body>
<div id="fans_menu">
	<a href="javascript:;" class="fmenuon" id="a0" onclick="setfans(0);">我的听众</a>
    <a href="javascript:;" onclick="setfans(1);" id="a1">我的偶像</a>
</div>
<div id="fans_list"> <div class="fans_loading">loading ...</div></div>
<div id="fans_page">
	<a href="javascript:;" class="p" onclick="fanspage(-30)"> << 上一页</a>
    <a href="javascript:;" class="n" onclick="fanspage(30)">下一页 >> </a>
</div>
<script>
if('<?php echo rq('t')?>'!=''){
	startType = <?php echo rq('t')?>;
}
$(document).ready(function(){
	setpos();
	loadfans();
});
</script>
</body>
</html>
