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

//SQL编码
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

//去掉SQL编码
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

//取消HTML代码
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

//获取文件内容
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

//写入文件
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

//字符串解密加密
function aen($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	// 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥
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

//抓取数据方法封装
/*
$url : 抓取的url路径
$poststr ： POST的数据，默认为空
$con ： 更多设置，array(CURLOPT_COOKIE=>'cookname=cookval&cn2=cv2')
*/
function getHttpPage($url,$poststr='',$con=''){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5); //超时时间（秒）
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

//检查邮箱是否有效
function isEmail($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

//当前时间
function now(){
	return date("Y-m-d H:i:s");	
}

//时间相加
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

//取时间差
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

//取字符串左右
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

//字符串查找
//未找到返回 -1 （被查找的字符串，要查找的，从前还是后：默认前）
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

//判断是否为空值
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

//获取客户端IP
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
//是否数字简写
function isNum($str){
    $isnum = 0;
    if(is_numeric($str)) $isnum = 1;
	return $isnum;
}

//替代die函数
function we($str){
	DB::closeall();
	echo $str;
	exit;
}

//Request方法简写
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

##SQL语句类
class DB{
	public static $conns = array();
	/*
	取得数据库连接资源
	从serverconfig中载入$_CONFIG['db']配置项
	*/
	public function getconnG(){
		global $_CONFIG;
		return self::conndb($_CONFIG['db']);	
	}	
	/*
	连接数据库，配置文件格式
	array(
		'ip'=>'localhost', #数据库服务器IP
		'username'=>'root', #用户名
		'pwd'=>'root', #密码
		'database'=>'game3', #数据库名
		'charset'=>'utf8', #默认编码
	)
	*/
	public function conndb($dbconf){
		$key = $dbconf['database'] .'_'. $dbconf['username'] .'_'. $dbconf['pwd']; 
		if(self::$conns[$key]){
			return self::$conns[$key];
		}
		
		$conn = @mysql_connect($dbconf['ip'], $dbconf['username'], $dbconf['pwd']);
		if(!$conn)self::error("数据库服务器连接超时");	
		$db = @mysql_select_db($dbconf['database'],$conn);
		if(!$db)self::error("{$dbconf['database']}读取错误");
		mysql_query("SET NAMES '".$dbconf['charset']."'",$conn);
		self::$conns[$key]=$conn;
		return $conn;
	}	
	
	/*关闭数据库*/
	public function closeall(){
		foreach(self::$conns as $key=>$val){
			@mysql_close($val);
			@mysql_free_result();
			self::$conns = array();
		}
	}
	
	/*返回数据库记录集*/
	public function openrs($sql){
		if($sql=='') self::error('OPENRS时SQL语句必须存在');
		$conn = self::getconnG();
		$res = @mysql_query($sql,$conn);
		if(mysql_error($conn)) self::error();
		return $res;
		unset($res,$conn);
	}
	
	/*
	事务方式执行SQL语句
	多条SQL语句以 ; 号切分，语句中不得含有;号
	*/
	public function exesql($sql,$count=false){
		if($sql=='') self::error('EXESQL时SQL语句必须存在');

		global $exesqlCount;
		$errnum = 0; $errorTxt = '';
		$exesqlCount = array();
		$sqlArr = split (';',$sql);
		$conn = self::getconnG();
		mysql_query('BEGIN',$conn); #开启事务
		
		foreach ($sqlArr as $sqltxt) {
			$sqltxt = trim($sqltxt);
			if($sqltxt=='') continue;
			$res = mysql_query($sqltxt,$conn);

			if(mysql_error($conn)){
				$errnum++; #SQL语句有误
				$errorTxt .= mysql_error($conn) . "\r\n";
			}else{
				if(!$count) continue;
				#根据SQL语句，增加记录值
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
			#回滚数据
			mysql_query("ROLLBACK",$conn);
			self::error($errorTxt);
			return false;
		}else{
			mysql_query("COMMIT",$conn);
			return true;
		}
		unset($sqlArr,$conn,$res,$errorTxt,$errnum);
	}
	
	/*记录集转数组*/
	public function getrow($rs,$fmttype=1){
		$conf=array(MYSQL_NUM,MYSQL_ASSOC,MYSQL_BOTH);
		$res = mysql_fetch_array($rs,$conf[$fmttype]);	
		if(is_array($res))$res = array_change_key_case($res); 
		return $res;
		unset($res,$conf);
	}
	
	/*取sql语句第一行第一列的值*/
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
	取SQL语句结果数组
	indexkey为结果数组的key用数据库的哪个列
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
    处理所有出错信息
    $errMsg自定义的出错信息
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
	更新SQL语句
	$tablename 表名
	$param 更新数据，字符串或array
	$where 更新条件
	返回SQL语句
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
	插入SQL语句
	$tablename 表名
	$param 插入数据array
	返回SQL语句
	*/
	public function insert($tablename,$param){
		if(!is_array($param)){
			self::error("INSERT语句错误：{$param} 类型不符");	
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
	删除SQL语句
	$tablename 表名
	$where 删除条件
	返回SQL语句
	*/
	public function delete($tablename,$where=''){
		if (!$where) {
            self::error("删除函数必须指定条件");
            return 0;
        }
        return 'DELETE FROM `' . $tablename . '` WHERE ' . $where;
	}
	
	/*
	查询SQL语句
	$fields 字段
	$tablename 表名
	$where 条件
	$orderby 排序
	$limit 记录条数
	返回SQL语句
	*/
	public function select($fields,$tablename,$where='',$orderby='',$limit=''){
		$sql = 'SELECT ' . $fields . ' FROM `' . $tablename .'`' . ($where ? ' WHERE ' . $where : '') . ($orderby ? ' ORDER BY ' . $orderby . ' ':'') . ($limit ? ' limit ' . $limit : '');
        return $sql;
		unset($sql);
	}
}


##MEMCACHE类
class CACHE{
	public static $conns = array();
	
	#根据配置文件，取连接资源
	function getconnG(){
		global $_CONFIG;
		return self::connCache($_CONFIG['memcache']);	
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
	public static $preKey = 'CiNiAoSeSs';
	
	#初始化session
	public function initSession(){
		if(self::$init==1) return;
		global $_CONFIG; #加载配置文件，将session保存到memecahe
		header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');  
		ini_set("session.save_handler","files");
		session_save_path( "session/");
		ini_set('session.gc_maxlifetime',600); 	
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
	public function delall($key){
		self::initSession();
		session_destroy();
	}
		
}
?>
