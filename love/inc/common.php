<?php
require_once(ROOT."./inc/config.php");

set_magic_quotes_runtime(0);
if(function_exists('date_default_timezone_set')) date_default_timezone_set('PRC');

DEBUG?error_reporting(7):error_reporting(0);

$magic_quote = get_magic_quotes_gpc();
if(empty($magic_quote)) 
{
	$_GET = sqlEncode($_GET);
	$_POST = sqlEncode($_POST);
	$_REQUEST = sqlEncode($_REQUEST);
	$_COOKIE = sqlEncode($_COOKIE);
}

//SQL缂栫爜
function sqlEncode($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = sqlEncode($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

//鍘绘帀SQL缂栫爜
function sqlDecode($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = sqlDecode($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

//鍙栨秷HTML浠ｇ爜
function htmlEncode($string) {
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

//鑾峰彇鏂囦欢鍐呭
function getFile($filename){
	$content = '';
	if(function_exists('file_get_contents')) {
		@$content = file_get_contents($filename);
	} else {
		if(@$fp = fopen($filename, 'r')) {
			@$content = fread($fp, filesize($filename));
			@fclose($fp);
		}
	}
	return $content;
}

//鍐欏叆鏂囦欢
function setFile($filename, $writetext, $openmod='w') {
	if(@$fp = fopen($filename, $openmod)) {
		flock($fp, 2);
		fwrite($fp, $writetext);
		fclose($fp);
		return true;
	} else {
		return false;
	}
}

//瀛楃涓茶В瀵嗗姞瀵?
function aen($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	// 闅忔満瀵嗛挜闀垮害 鍙栧€?0-32;
	// 鍔犲叆闅忔満瀵嗛挜锛屽彲浠ヤ护瀵嗘枃鏃犱换浣曡寰嬶紝鍗充究鏄師鏂囧拰瀵嗛挜瀹屽叏鐩稿悓锛屽姞瀵嗙粨鏋滀篃浼氭瘡娆′笉鍚岋紝澧炲ぇ鐮磋В闅惧害銆?
	// 鍙栧€艰秺澶э紝瀵嗘枃鍙樺姩瑙勫緥瓒婂ぇ锛屽瘑鏂囧彉鍖?= 16 鐨?$ckey_length 娆℃柟
	// 褰撴鍊间负 0 鏃讹紝鍒欎笉浜х敓闅忔満瀵嗛挜
	$key = md5($key ? $key : 'IAmCiNiao!!');
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

//鎶撳彇鏁版嵁鏂规硶灏佽
/*
$url : 鎶撳彇鐨剈rl璺緞
$poststr 锛?POST鐨勬暟鎹紝榛樿涓虹┖
$con 锛?鏇村璁剧疆锛宎rray(CURLOPT_COOKIE=>'cookname=cookval&cn2=cv2')
*/
function getHttpPage($url,$poststr='',$con=''){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5); //瓒呮椂鏃堕棿锛堢锛?
	if(!isn($poststr)){
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$poststr); 	
	}
	if(is_array($con)){
		foreach($con as $key => $value){
			curl_setopt($ch,$key,$value); 
		}
	}
	$html = curl_exec($ch);
	curl_close($ch);
	return $html;
}

//妫€鏌ラ偖绠辨槸鍚︽湁鏁?
function isEmail($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

//褰撳墠鏃堕棿
function now(){
	return date("Y-m-d H:i:s");	
}

//鏃堕棿鐩稿姞
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

//鍙栨椂闂村樊
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
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    if($time1 && $time2){
    	return bcdiv(($time2-$time1),$div);
    }else{
		return false;
    }
}

//鍙栧瓧绗︿覆宸﹀彸
//left('abcde',-1) = abcd
function left($str,$len){
	if((int)$len>0){
		return substr($str,0,$len);	
	}else{
		return substr($str,0,strlen($str)+$len);		
	}
}
function right($str,$len){
	if($len>0){
		return substr($str,strlen($str)-$len,strlen($str));
	}else{
		return substr($str,abs($len),strlen($str));	
	}
}

//瀛楃涓叉煡鎵?
//鏈壘鍒拌繑鍥?-1 锛堣鏌ユ壘鐨勫瓧绗︿覆锛岃鏌ユ壘鐨勶紝浠庡墠杩樻槸鍚庯細榛樿鍓嶏級
function indexOf($str,$find,$rev=0){
	if($rev==0){
		$res=stripos($str,$find);	
	}else{
		$res=strripos($str,$find);
	}
	if(isn($res)){
		return -1;	
	}else{
		return $res;
	}
}

//鍒ゆ柇鏄惁涓虹┖鍊?
function isn($str){
	if(is_array($str)){
		return 0;
	}
	if(is_null($str) || strlen($str)==0){
		return 1;	
	}else{
		return 0;	
	}
}

//鑾峰彇瀹㈡埛绔疘P
function getip(){
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	return $onlineip;
}
//鏄惁鏁板瓧绠€鍐?
function isNum($str){
    $isnum = 0;
    if(is_numeric($str)) $isnum = 1;
	return $isnum;
}

//鏇夸唬die鍑芥暟
function we($str){
	DB::closeall();
	echo $str;
	exit;
}

//Request鏂规硶绠€鍐?
function rq($name){
	return $_GET[$name];	
}
function rf($name){
	return $_POST[$name];	
}
function r($name){
	return $_REQUEST[$name];	
}

function _rq($name){
	return sqlDecode(rq($name));	
}
function _rf($name){
	return sqlDecode(rf($name));	
}
function _r($name){
	return sqlDecode(r($name));	
}

##SQL璇彞绫?
class DB{
	public static $conns = array();
	/*
	鍙栧緱鏁版嵁搴撹繛鎺ヨ祫婧?
	浠巗erverconfig涓浇鍏?_CONFIG['db']閰嶇疆椤?
	*/
	public function getconnG(){
		global $_CONFIG;
		return self::conndb($_CONFIG['db']);	
	}	
	/*
	杩炴帴鏁版嵁搴擄紝閰嶇疆鏂囦欢鏍煎紡
	array(
		'ip'=>'localhost', #鏁版嵁搴撴湇鍔″櫒IP
		'username'=>'root', #鐢ㄦ埛鍚?
		'pwd'=>'root', #瀵嗙爜
		'database'=>'game3', #鏁版嵁搴撳悕
		'charset'=>'utf8', #榛樿缂栫爜
	)
	*/
	public function conndb($dbconf){
		$key = $dbconf['database'] .'_'. $dbconf['username'] .'_'. $dbconf['pwd']; 
		if(self::$conns[$key]){
			return self::$conns[$key];
		}
		
		$conn = @mysql_connect($dbconf['ip'], $dbconf['username'], $dbconf['pwd']);
		if(!$conn)self::error("鏁版嵁搴撴湇鍔″櫒杩炴帴瓒呮椂");	
		$db = @mysql_select_db($dbconf['database'],$conn);
		if(!$db)self::error("{$dbconf['database']}璇诲彇閿欒");
		mysql_query("SET NAMES '".$dbconf['charset']."'",$conn);
		self::$conns[$key]=$conn;
		return $conn;
	}	
	
	/*鍏抽棴鏁版嵁搴?/
	public function closeall(){
		foreach(self::$conns as $key=>$val){
			@mysql_close($val);
			@mysql_free_result();
			self::$conns = array();
		}
	}
	
	/*杩斿洖鏁版嵁搴撹褰曢泦*/
	public function openrs($sql){
		if($sql=='') self::error('OPENRS鏃禨QL璇彞蹇呴』瀛樺湪');
		$conn = self::getconnG();
		$res = @mysql_query($sql,$conn);
		if(mysql_error($conn)) self::error();
		return $res;
		unset($res,$conn);
	}
	
	/*
	浜嬪姟鏂瑰紡鎵цSQL璇彞
	澶氭潯SQL璇彞浠?; 鍙峰垏鍒嗭紝璇彞涓笉寰楀惈鏈?鍙?
	*/
	public function exesql($sql,$count=false){
		if($sql=='') self::error('EXESQL鏃禨QL璇彞蹇呴』瀛樺湪');

		global $exesqlCount;
		$errnum = 0; $errorTxt = '';
		$exesqlCount = array();
		$sqlArr = split (';',$sql);
		$conn = self::getconnG();
		mysql_query('BEGIN',$conn); #寮€鍚簨鍔?
		
		foreach ($sqlArr as $sqltxt) {
			$sqltxt = trim($sqltxt);
			if($sqltxt=='') continue;
			$res = mysql_query($sqltxt,$conn);

			if(mysql_error($conn)){
				$errnum++; #SQL璇彞鏈夎
				$errorTxt .= mysql_error($conn) . "\r\n";
			}else{
				if(!$count) continue;
				#鏍规嵁SQL璇彞锛屽鍔犺褰曞€?
				if(strtoupper(left($sqltxt,6))=="SELECT"){
					array_push($exesqlCount,mysql_num_rows($res));
				}else if(strtoupper(left($sqltxt,6))=="INSERT"){
					array_push($exesqlCount,mysql_insert_id());
				}else{
					array_push($exesqlCount,mysql_affected_rows()); 	
				}
			}
		}
		if($errnum>0){
			#鍥炴粴鏁版嵁
			mysql_query("ROLLBACK",$conn);
			self::error($errorTxt);
			return false;
		}else{
			mysql_query("COMMIT",$conn);
			return true;
		}
		unset($sqlArr,$conn,$res,$errorTxt,$errnum);
	}
	
	/*璁板綍闆嗚浆鏁扮粍*/
	public function getrow($rs,$fmttype=1){
		$conf=array(MYSQL_NUM,MYSQL_ASSOC,MYSQL_BOTH);
		$res = mysql_fetch_array($rs,$conf[$fmttype]);	
		if(is_array($res))$res = array_change_key_case($res); 
		return $res;
		unset($res,$conf);
	}
	
	/*鍙杝ql璇彞绗竴琛岀涓€鍒楃殑鍊?/
	public function sqlval($sql){
		$res = self::getrow(self::openrs($sql),0);
		if(!is_array($res)){
			return '';	
		}else{
			return $res[0];
		}
		unset($res);
	}
	
	/*
	鍙朣QL璇彞缁撴灉鏁扮粍
	indexkey涓虹粨鏋滄暟缁勭殑key鐢ㄦ暟鎹簱鐨勫摢涓垪
	*/
	public function sqlgetall($sql,$indexkey=''){
		$res = array();
		$sqlres = self::openrs($sql);
			
		while($row=self::getrow($sqlres)){
			if($indexkey==''){
				$res[] = $row;	
			}else{
				$res[$row[$indexkey]] = $row;	
			}
		}
		return $res;
		unset($res,$sqlres,$row);
	}
		
	/*
    澶勭悊鎵€鏈夊嚭閿欎俊鎭?
    $errMsg鑷畾涔夌殑鍑洪敊淇℃伅
	*/
	public function error($errMsg=''){
		if ($errMsg == "") {
            $m = "mysql error:\r\n";
            $m .= mysql_errno() . ":" . mysql_error () . "\r\n";
			$m .= 'URL:'.$_SERVER["REQUEST_URI"];
			echo $m;
        }else{
            $m .= "mysql error:\r\n";
            $m .= $errMsg . "\r\n";
			$m .= 'URL:'.$_SERVER["REQUEST_URI"];
			echo $m;
        }
		$log="=====================================\r\n";
		$log .= date("Y-m-d H:i:s")."\r\n";
		$log .= $m ."\r\n";
		echo $log;
		//@file_put_contents("game/log/database.log",$log,FILE_APPEND);
		unset($m);
		exit();
	}	
	/*
	鏇存柊SQL璇彞
	$tablename 琛ㄥ悕
	$param 鏇存柊鏁版嵁锛屽瓧绗︿覆鎴朼rray
	$where 鏇存柊鏉′欢
	杩斿洖SQL璇彞
	*/
	public function update($tablename,$param,$where=''){
		if(is_array($param)){
			$paramArr=array();
			foreach($param as $key => $val){
				array_push($paramArr,"$key='".addslashes($val)."'");
			}
			$param = implode(',',$paramArr);	
		}
		unset($paramArr,$key,$val);
		return 'UPDATE `'.strtoupper(trim($tablename)) .'` SET '. $param . ($where ? ' WHERE ' . $where : '');
	}
	/*
	鎻掑叆SQL璇彞
	$tablename 琛ㄥ悕
	$param 鎻掑叆鏁版嵁array
	杩斿洖SQL璇彞
	*/
	public function insert($tablename,$param){
		if(!is_array($param)){
			self::error("INSERT璇彞閿欒锛歿$param} 绫诲瀷涓嶇");	
		}
		$paramArr=array();
		foreach($param as $key => $val){
			array_push($paramArr,"$key='".addslashes($val)."'");
		}
		$param = implode(',',$paramArr);
		unset($paramArr,$key,$val);
		return 'INSERT INTO `'.strtoupper(trim($tablename)) .'` SET '. $param;
	}
	/*
	鍒犻櫎SQL璇彞
	$tablename 琛ㄥ悕
	$where 鍒犻櫎鏉′欢
	杩斿洖SQL璇彞
	*/
	public function delete($tablename,$where=''){
		if (!$where) {
            self::error("鍒犻櫎鍑芥暟蹇呴』鎸囧畾鏉′欢");
            return 0;
        }
        return 'DELETE FROM `' . $tablename . '` WHERE ' . $where;
	}
	
	/*
	鏌ヨSQL璇彞
	$fields 瀛楁
	$tablename 琛ㄥ悕
	$where 鏉′欢
	$orderby 鎺掑簭
	$limit 璁板綍鏉℃暟
	杩斿洖SQL璇彞
	*/
	public function select($fields,$tablename,$where='',$orderby='',$limit=''){
		$sql = 'SELECT ' . $fields . ' FROM `' . $tablename .'`' . ($where ? ' WHERE ' . $where : '') . ($orderby ? ' ORDER BY ' . $orderby . ' ':'') . ($limit ? ' limit ' . $limit : '');
        return $sql;
		unset($sql);
	}
}


##MEMCACHE绫?
class CACHE{
	public static $conns = array();
	
	#鏍规嵁閰嶇疆鏂囦欢锛屽彇杩炴帴璧勬簮
	function getconnG(){
		global $_CONFIG;
		return self::connCache($_CONFIG['memcache']);	
	}
	
	#杩炴帴鍒癿emcache
	public function connCache($dbconf){
		$key = $dbconf['ip'] .'.'. $dbconf['port']; 
		
		if(self::$conns[$key])
			return self::$conns[$key];
			
		$conn = memcache_connect($dbconf['ip'], $dbconf['port']);
		self::$conns[$key]=$conn;
		return $conn;
	}	
	
	#璁剧疆缂撳瓨鍐呭
	public function set($key,$val,$timeout=0){
		return memcache_set(self::getconnG(),$key,$val,0,$timeout);#timeout绉掑悗瓒呮椂
	}
	
	#璇诲彇缂撳瓨鍐呭
	public function get($key){
		return memcache_get(self::getconnG(),$key);
	}
	
	#鍒犻櫎鎸囧畾key缂撳瓨
	public function del($key,$delay=0){	
		return memcache_delete(self::getconnG(),$key,$delay); #delay绉掑悗鍒犻櫎
	}
	
	#鍒犻櫎鎵€鏈夌紦瀛?
	public function delall(){	
		memcache_flush(self::getconnG());
	}
}

##娓告垙SESSIOON绫?
class SESS{
	public static $init = 0;
	public static $preKey = 'CiNiAoSeSs';
	
	#鍒濆鍖杝ession
	public function initSession(){
		if(self::$init==1) return;
		global $_CONFIG; #鍔犺浇閰嶇疆鏂囦欢锛屽皢session淇濆瓨鍒癿emecahe
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');  
		ini_set("session.save_handler","files");
		session_save_path( "session/");
		ini_set('session.gc_maxlifetime',600); 	
		session_start();
		self::$init = 1;
	}
	
	#璇诲彇session
	public function get($key){
		self::initSession();
		return $_SESSION[self::$preKey.$key];
	}
	
	#璁剧疆session
	public function set($key,$val){
		self::initSession();
		$_SESSION[self::$preKey.$key] = $val;
	}
	
	#鍒犻櫎鎸囧畾session
	public function del($key){
		self::initSession();
		unset($_SESSION[self::$preKey.$key]);
	}
	
	#鍒犻櫎鎵€鏈塻ession
	public function delall($key){
		self::initSession();
		session_destroy();
	}
		
}
?>
