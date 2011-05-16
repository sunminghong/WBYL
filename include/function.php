<?php

function importlib($libname){
	include_once(ROOT.'include/'.$libname.".php");
}


function smh_auth($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	return authcode($string, $operation,$key, $expiry );	
}

// 函数示例，显示当前日期
function mydate(){
	echo date("Y-m-d");	
}

//function getTemplatePath(){
//	if($GLOBALS['currTemplate'])
//		return $GLOBALS['currTemplate'];
//	else
//		return 'default';
//}


function getTimestamp(){
	$time=explode(" ", microtime());
	$timestamp=$time[1];
	return $timestamp;
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}



//取消HTML代码
function shtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

//字符串解密加密
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;	// 随机密钥长度 取值 0-32;
				// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
				// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
				// 当此值为 0 时，则不产生随机密钥

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

//cookie设置
function ssetcookie($var, $value, $life=0) {
	//setcookie(CookiePre.$var, $value, $life?(time()+$life):0, "/",CookieDomain);
	if($value==="")
		SESS::del($var);
	else
		SESS::set($var,$value);

}
function sreadcookie($key){
	//return $_COOKIE[CookiePre.$key];
	return SESS::get($key);
}


##MEMCACHE类
class CACHE{
	public static $conns = array();
	
	#根据配置文件，取连接资源
	function getconnG(){
		if(ISSAE) {
			$mmc = memcache_init();
			if($mmc==false)
			{
				echo "mc init failed\n";exit;
			}
			else
			{
				return $mmc;
			}
		}
		global $MEMCONFIG;
		return self::connCache($MEMCONFIG);	
	}
	
	#连接到memcache
	public function connCache($dbconf){
		$key = $dbconf['ip'] .'.'. $dbconf['port']; 
		
		if(self::$conns[$key])
			return self::$conns[$key];
			
		$conn = memcache_connect($dbconf['ip'], $dbconf['port']);
		self::$conns[$key]=$conn;
		return $conn;
	}	
	
	#设置缓存内容
	public function set($key,$val,$timeout=0){
		return memcache_set(self::getconnG(),$key,$val,0,$timeout);#timeout秒后超时
	}
	
	#读取缓存内容
	public function get($key){
		return memcache_get(self::getconnG(),$key);
	}
	
	#删除指定key缓存
	public function del($key,$delay=0){	
		return memcache_delete(self::getconnG(),$key,$delay); #delay秒后删除
	}
	
	#删除所有缓存
	public function delall(){	
		memcache_flush(self::getconnG());
	}
}

##游戏SESSIOON类
class SESS{
	public static $init = 0;
	public static $preKey = 'CookiePre';
	
	#初始化session
	public function initSession(){
		if(self::$init==1) return;
		/*global $_CONFIG; #加载配置文件，将session保存到memecahe
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');  
		ini_set("session.save_handler","files");
		////session_save_path( "session/");
		ini_set('session.gc_maxlifetime',600); 	*/
		session_start();
		self::$init = 1;
	}
	
	#读取session
	public function get($key){
		self::initSession();
		return $_SESSION[self::$preKey.$key];
	}
	
	#设置session
	public function set($key,$val){
		self::initSession();
		$_SESSION[self::$preKey.$key] = $val;
	}
	
	#删除指定session
	public function del($key){
		self::initSession();
		unset($_SESSION[self::$preKey.$key]);
	}
	
	#删除所有session
	public function delall(){
		self::initSession();
		session_destroy();
	}
		
}

//获取在线IP
function getonlineip() {
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
		return $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
}

// 获取客户端IP地址
function get_client_ip(){
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
       $ip = getenv("HTTP_CLIENT_IP");
   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
       $ip = getenv("HTTP_X_FORWARDED_FOR");
   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
       $ip = getenv("REMOTE_ADDR");
   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
       $ip = $_SERVER['REMOTE_ADDR'];
   else
       $ip = "unknown";
   return($ip);
}
	

function rq($k,$default=''){
	if(isset($_GET[$k]))
		return $_GET[$k];
	else 
		return $default;	
}


function rf($k,$default=''){
	if(isset($_POST[$k]))
		return $_POST[$k];
	else 
		return $default;	
}

//时间处理方法
function now(){
	return date("Y-m-d H:i:s");	
}

//
function dateAdd($unit = "d",$int,$date) {
	$date_time_array = getdate(strtotime($date));
    $hours = $date_time_array["hours"];
    $minutes = $date_time_array["minutes"];
    $seconds = $date_time_array["seconds"];
    $month = $date_time_array["mon"];
    $day = $date_time_array["mday"] ;
    $year = $date_time_array["year"] ;
    switch ($unit) {
    	case "yyyy": $year +=$int;
   			break;
    	case "q": $month +=($int*3);
   			break;
    	case "m": $month +=$int;
			break;
    	case "y": $day+=$int;
			break;
    	case "d": $day+=$int;
			break;
    	case "w": $day+=$int;
			break;
    	case "ww": $day+=($int*7);
			break;
    	case "h": $hours+=$int;
			break;
    	case "n": $minutes+=$int;
			break;
    	case "s": $seconds+=$int;
			break;
    }
    $timestamp = mktime($hours ,$minutes, $seconds,$month ,$day, $year);
    return date("Y-m-d H:i:s",$timestamp);
}
/*
function dateDiff($unit="",$date1 , $date2){
	switch ($unit){
    case "s": $div = 1 ;
		break;
	case "i": $div = 60;
		break;
	case "h": $div = 3600;
		break;
	case "d": $div = 86400;
		break;
	case "m": $div= 2592000;
		break;
	case "y": $div = 946080000;
		break;
	default: $div = 86400;
	}
	if(!is_numeric($date1))
		$time1 = strtotime($date1);
	if(!is_numeric($date2))
		$time2 = strtotime($date2);

    if($time1 && $time2){
    	//return bcdiv(($time2-$time1),$div);
		return bcdiv($time2,$div)- bcdiv($time1,$div);
    }else{
		return false;
    }
}*/

//月不准确，有闰月的影响
function dateDiff($unit="",$time1 , $time2){
	if(!is_numeric($time1))
		$time1 = strtotime($time1);
	if(!is_numeric($time2))
		$time2 = strtotime($time2);

	switch ($unit){
    case "s": 
		return $time2-$time1;
		break;
	case "i":
		$time1=strtotime(strftime('%Y-%m-%d %H:%M',$time1));
		$time2=strtotime(strftime('%Y-%m-%d %H:%M',$time2));
		return intval(($time2-$time1)/60);
	case "h": 
		$time1=strtotime(strftime('%Y-%m-%d %H',$time1));
		$time2=strtotime(strftime('%Y-%m-%d %H',$time2));
		return intval(($time2-$time1)/3600);
	case "d": 
		$time1=strtotime(strftime('%Y-%m-%d',$time1));
		$time2=strtotime(strftime('%Y-%m-%d',$time2));
		return intval(($time2-$time1)/86400);
	case "m":
		$time1=strtotime(strftime('%Y-%m',$time1));
		$time2=strtotime(strftime('%Y-%m',$time2));
		return intval(($time2-$time1)/2592000);
	case "y": 
		$time1=intval(strftime('%Y',$time1));
		$time2=intval(strftime('%Y',$time2));
		return intval($time2-$time1);
	default: 
		$time1=strtotime(strftime('%Y-%m-%d %H',$time1));
		$time2=strtotime(strftime('%Y-%m-%d %H',$time2));
		return intval(($time2-$time1)/86400);
	}
	
}




//取字符串左右
//left('abcde',-1) = abcd
//取字符串左右
//left('abcde',-1) = abcd
function left($str,$len){
	if((int)$len>0){
		return mb_substr($str,0,$len,'utf8');	
	}else{
		return mb_substr($str,0,strlen($str)+$len,'utf8');		
	}
}
function right($str,$len){
	if($len>0){
		return mb_substr($str,strlen($str)-$len,strlen($str),'utf8');
	}else{
		return mb_substr($str,abs($len),strlen($str),'utf8');	
	}
}
function len($str){
	return mb_strlen($str,'utf8');	
}

function getEl($xmlobj,$name){
	$el=$xmlobj->xpath($name);
	if($el)
	return $el[0]."";
	else
		return '';
}
	
?>