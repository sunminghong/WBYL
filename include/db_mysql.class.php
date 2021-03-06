<?php

class dbstuff {

	var $version = '';
	var $querynum = 0;
	var $link;

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0, $halt = TRUE) {

		$func = empty($pconnect) ? 'mysql_connect' : 'mysql_pconnect';
		if(!$this->link = @$func($dbhost, $dbuser, $dbpw)) {
			$halt && $this->halt('Can not connect to MySQL server');
		} else {
			if($this->version() > '4.1') {
				global $charset, $dbcharset;
				$dbcharset = !$dbcharset && in_array(strtolower(DEFAULT_CHARTSET), array('gbk', 'big5', 'utf-8')) ? str_replace('-', '', DEFAULT_CHARTSET) : DEFAULT_CHARTSET;
				$serverset = $dbcharset ? 'character_set_connection='.DEFAULT_CHARTSET.', character_set_results='.DEFAULT_CHARTSET.', character_set_client=binary' : '';
				$serverset .= $this->version() > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && mysql_query("SET $serverset", $this->link);
			}
			$dbname && @mysql_select_db($dbname, $this->link);
		}

	}

	function select_db($dbname) {
		return mysql_select_db($dbname, $this->link);
	}

	private function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}

	function getFirst($sql) {
		return $this->fetch_array($this->query($sql));
	}

	function getArray($sql,$result_type=MYSQL_ASSOC) {
		$query=$this->query($sql);
		$result=mysql_fetch_array($query,$result_type);
		$arr=array();
		while($result=mysql_fetch_array($query,$result_type)){
			$arr[]=$result;
		}
		
		return $arr;
	}
	function execute($sql, $type = '') {
		global $debug, $discuz_starttime, $sqldebug, $sqlspenttimes;

		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		$func($sql, $this->link);

		$this->querynum++;
	}
	
	function result_first($sql) {
		return $this->result($this->query($sql), 0);
	}
	private function query($sql, $type = '') {
		global $debug, $discuz_starttime, $sqldebug, $sqlspenttimes;

		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ?
			'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link))) {
//			if(in_array($this->errno(), array(2006, 2013)) && substr($type, 0, 5) != 'RETRY') {
//				$this->close();
//				require './config.inc.php';
//				$this->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
//				$this->query($sql, 'RETRY'.$type);
//			} elseif($type != 'SILENT' && substr($type, 5) != 'SILENT') {
				echo $this->error();
				$this->halt('MySQL Query Error', $sql);
//			}
		}

		$this->querynum++;
		return $query;
	}

	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function free_result($query) {
		return mysql_free_result($query);
	}

	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	function version() {
		if(empty($this->version)) {
			$this->version = mysql_get_server_info($this->link);
		}
		return $this->version;
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($message = '', $sql = '') {
		echo 'SQL Error:<br />'.$message.'<br />'.$sql;
	}
	
	/**
	 * 添加或插入数据
	 * @param $data 键/值对应的数组，其中键为字段名，值为要插入的内容
	 * @param $id int 更新时使用的关键字，如果指定该项，则执行更新操作
	 * @param $table string 表单名，如果不指定，则使用setTable()设置的默认表名
	 * @param $id_name string 字段名，更新时用于查询的字段，默认查询名为'id'的字段
	 * @return int|false 返回最后插入的记录ID或更新记录的ID,失败返回false
	 */
	function update($data, $id = '', $table = '', $id_name = 'id') {

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
				$values[] = '"' .mysql_escape_string($value) . '"';
			}
			if (sizeof($keys) != sizeof($values)) {
				return false;
			}
			$ignore = '';

			$sql = 'INSERT  INTO ' . $table . '(' .implode(',', $keys). ') VALUES('. implode(',', $values) .')';	
			$this->query($sql);

			if ($this->errno() != 0) {
				die( "Error:" . $this->error() );
				return false;
			}
			return $this->insert_id();
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
		if ($this->errno() != 0) {
			die( "Error:" . $mysql->error() );
			return false;
		}

		return true;
	}
}

?>