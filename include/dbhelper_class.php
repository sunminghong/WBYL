<?php
class dbhelper {
	static function tname($app,$tname){
		if($GLOBALS["app_dbpre_".$app])
			return $GLOBALS["app_dbpre_".$app].$tname;
		else{echo 'sss'.$app.$tname.'sss';
			die('表名前缀不符合要求！');	}
	}
	
	public static $conns=array();

	/**
	 * 取得主数据库服务连接资源 
	 * @return mix  返回连接符
	 * @access public
	 */
	static function getConnM(){
		return self::getConn($GLOBALS['DBCONFIG'][0]);
	}

	/**
	 * 取从数据库服务连接资源 
	 * @return mix  返回连接符
	 * @access public
	 */
	static function getConnS(){
		$len=count($GLOBALS['DBCONFIG']);
		if ($len>1) {
			list($usec, $sec) = explode(" ", microtime());			
			$dbid=$sec % $len;
			if ($dbid==0)$dbid=1;
		}
		else
			$dbid=0;

		return self::getConn($GLOBALS['DBCONFIG'][$dbid]);
	}
	
	/**
	 * 取得数据库服务连接资源 ，内部私有属性
	 * @return mix  返回连接符
	 * @access private
	 */	
	static private function getConn($serv){
		$key=$serv['dbhost']."_".$serv['dbuser']."_".$serv['dbpwd'];
		if(in_array($key,array_keys(self::$conns)))
			return self::$conns[$key];

		$conn=mysql_connect($serv['dbhost'],$serv['dbuser'],$serv['dbpwd']);
		if($conn){
			mysql_select_db(DEFAULT_DB,$conn);
			mysql_query("SET NAMES '".DEFAULT_CHARTSET."'",$conn);
			self::$conns[$key]=$conn;
			return $conn;
		}
		else{
			echo '与数据库连接时出现错误！' . mysql_error();exit;
			return false;		
		}
	}
	
	/**
	 * 执行SQL语句
	 * @param string $sql sql语句
	 * @access public
	 */
	static function execute($sql,$newid=0){
		if(preg_match("/replace|insert|update|delete/i",$sql)){
			$res=mysql_query($sql,self::getConnM());
			if($newid)
				return mysql_insert_id(self::getConnM());
			else
				return $res;
		}else {
			$res=mysql_query($sql,self::getConnS());
			//if($newid)
			//	return mysql_insert_id(self::getConnM());
			//else
				return $res;
		}
	}
	
	//事务执行SQL语句
	static function exesqls($sql,$count=false){
		$conn=self::getConnM();
		$sqlArr = explode (';;;', $sql);
		mysql_query('BEGIN',$conn);
		$errnum = 0;
		$exesqlCount = array();
		foreach ($sqlArr as $Sqltxt) {
			if($Sqltxt!=''){
				$Sqltxt = trim($Sqltxt);
				$res = mysql_query($Sqltxt,$conn);
				if(mysql_error($conn)){
					$errnum++;
				}else{
					if($count){
						if(preg_match("/select/i",$sql)){
							array_push($exesqlCount,mysql_num_rows($res));
						}else{
							array_push($exesqlCount,mysql_affected_rows()); 	
						}
					}
				}
			}
		}
		if($errnum){
			mysql_query("ROLLBACK",$conn);
			return $exesqlCount;
		}else{
			mysql_query("COMMIT",$conn);
			return $exesqlCount;
		}
	}
	
	
	/**
	 * 执行SQL语句并返回recordset类记录集对象
	 * @param string $sql sql语句
	 * @return recordset 返回一个recordset对象，有next()方法
	 * @access public
	 */	
	static function getrs($sql){
		$conn=null;
		if(preg_match("/replace|insert|update|delete/i",$sql)){
			$conn=self::getConnM();
		}else {
			$conn=self::getConnS();
		}

		return new recordset($sql,$conn);
	}
	
	/**
	 * 执行SQL语句并返回第一行第一列的值
	 * @param string $sql sql语句
	 * @return string 返回第一行第一列的值
	 * @access public
	 */	
	static function getvalue($sql){
		if($row=self::getrs($sql)->next()){
			foreach($row as $val)
				return $val;	
		}else
			return "";		
	}
	
	/**
	 * 关闭数据库连接
	 * @access public
	 */		
	static function close(){
		foreach(self::$conns as $k=>$v){			
			mysql_free_result($v);
			mysql_close($v);
		}
	}
		
		
	/**
	 * 拼接分页html代码
	 * @access public
	 */	
	static function smulti($start, $perpage, $count, $url, $ajaxdiv='') {
		$multi = array('last'=>-1, 'next'=>-1, 'begin'=>-1, 'end'=>-1, 'html'=>'');
		if($start > 0) {
			if(empty($count)) {
				showmessage('no_data_pages');
			} else {
				$multi['last'] = $start - $perpage;
			}
		}
	
		$showhtml = 0;
		if($count == $perpage) {
			$multi['next'] = $start + $perpage;
		}
		$multi['begin'] = $start + 1;
		$multi['end'] = $start + $count;
	
		if($multi['begin'] >= 0) {
			if($multi['last'] >= 0) {
				$showhtml = 1;
				//if($_SGLOBALS[['inajax']) {
				//	$multi['html'] .= "<a href=\"javascript:;\" onclick=\"ajaxget('$url&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">|&lt;</a> <a href=\"javascript:;\" onclick=\"ajaxget('$url&start=$multi[last]&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">&lt;</a> ";
				//} else {
					$multi['html'] .= "<a href=\"$url\">|&lt;</a> <a href=\"$url&start=$multi[last]\">&lt;</a> ";
				//}
			} else {
				$multi['html'] .= "&lt;";
			}
			$multi['html'] .= " $multi[begin]~$multi[end] ";
			if($multi['next'] >= 0) {
				$showhtml = 1;
				//if($_SGLOBALS[['inajax']) {
				//	$multi['html'] .= " <a href=\"javascript:;\" onclick=\"ajaxget('$url&start=$multi[next]&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">&gt;</a> ";
				//} else {
					$multi['html'] .= " <a href=\"$url&start=$multi[next]\">&gt;</a>";
				//}
			} else {
				$multi['html'] .= " &gt;";
			}
		}
	
		return $showhtml?$multi['html']:'';
	}

	/**
	 * 添加或插入数据
	 * @param $data 键/值对应的数组，其中键为字段名，值为要插入的内容
	 * @param $id int 更新时使用的关键字，如果指定该项，则执行更新操作
	 * @param $table string 表单名，如果不指定，则使用setTable()设置的默认表名
	 * @param $id_name string 字段名，更新时用于查询的字段，默认查询名为'id'的字段
	 * @return int|false 返回最后插入的记录ID或更新记录的ID,失败返回false
	 */
	static function update($data, $id = '', $table = '', $id_name = 'id') {
		if ($id) {
			$type = 'update';
		} else {
			$type = 'insert';
		}

		if ($type == 'insert') {
			$keys = array();
			$values = array();
			foreach ($data as $key => $value) {
				$keys[] = '`' .$key . '`';
				$values[] = "'" .mysql_escape_string($value) . "'";
			}
			if (sizeof($keys) != sizeof($values)) {
				return false;
			}
			$ignore = '';

			$sql = 'INSERT  INTO ' . $table . '(' .implode(',', $keys). ') VALUES('. implode(',', $values) .')';			
			return self::execute($sql,true);
			return;
		}

		$values = array();
		foreach ($data as $key=>$value) {
			$values[] = '`' .trim($key) . '`="' . mysql_escape_string($value) . '"';
		}
		if (!sizeof($values)) {
			return false;
		}
		$sql = 'UPDATE ' . $table . ' SET ' . implode(',', $values) . ' WHERE ' . $id_name . '=' . $id;

		$this->query($sql);
		return self::execute($sql);
	}

}

/**
 * 数据记录集对像
 * @access public
 */
class recordset{
	public $res=null;
	
	/**数据记录集构造函数
	 * @param string $sql  sql语句
	 * @param mix  $conn  数据库连接符
	 * @access public
	 */	
	function __construct($sql,$conn){
		if (!$sql) return;		
		$res=mysql_query($sql,$conn);
		if (!$res)return;

		$this->res=$res;
	}
	
	/**返回下一条记录数组
	 * @return array  记录数组
	 * @access public
	 */		
	public function next(){
		if($this->res){						
		try{ 
				$arr=mysql_fetch_array($this->res, MYSQL_ASSOC) ; 
				return $arr;
			}catch(MyException $e){ 
				echo $e;exit;
			}
		}
		else
			return false;
	}
}
?>