<?php
header("Content-type: text/html; charset=utf-8"); 
function htmlencode($str){
	$s = htmlspecialchars($str, ENT_QUOTES);
	$s = str_replace('&amp;quot;','&quot;',$s);
	$s = str_replace('&amp;apos;','&apos;',$s);
	$order   = array("\r\n", "\n", "\r");
	$s = str_replace($order,'',$s);
	return $s;
}

function rhtmlencode($str){
	$s = str_replace('&amp;quot;','&quot;',$str);
	$s = str_replace('&amp;apos;','&apos;',$s);
	$s = str_replace('&lt;','<',$s);
	$s = str_replace('&gt;','>',$s);
	$s = str_replace('&quot;','"',$s);
	$s = str_replace('&apos;',"'",$s);
	$order   = array("\r\n", "\n", "\r");
	$s = str_replace($order,'',$s);
	return $s;
}


class MBAdmin{
	public $link;
	public $host;
	public static $dbName = MB_DATABASE_PREFIX;  //数据库前缀
	public static $recommTopic='recomm_ht';  //话题推荐
	public static $blackList='black_user'; //用户黑名单
	public static $hotTopic='hot_ht';     //热门话题
	public static $commentFilter='comment_filter'; //评论屏蔽 
	public static $hotRepost='hot_repost';     //热门转播
	public static $hotUser='hot_user';       //热门用户
	public static $keyWords='keywords';       //关键字屏蔽
	public static $mod='mod';            //模块管理
	public static $recommNewUser='recomm_newuser'; //新用户推荐
	public static $sysConfig='system_config';  //系统配置
	public static $userList='user';           //用户表
	public static $verifyUser='verify_user';    //用户认证表
	public static $weiboFilter='weibo_filter';   //微博屏蔽
	public static $recommUser='recomm_user';    //推荐用户

	function __construct($host,$name,$pwd,$db,$link=null){
		if(!$link){
			$this->link = mysql_connect($host, $name, $pwd) or die("Could not connect: " . mysql_error());
		}else{
			$this->link = $link;	
		}
		mysql_select_db($db, $this->link) or die ('Can\'t use foo : ' . mysql_error());
		mysql_query('set names utf8');
	}
	
	public static function check($str){
		return mysql_real_escape_string($str);
	}
	/*
	 *@str
	 **/
	public function checkpr($u,$p){
		$tn = MBAdmin::$dbName.'user';
		$sql = 'select u_status,u_priority from '.$tn.' where u_name="'.mysql_real_escape_string(u).'"';
		$result = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			$row = mysql_fetch_assoc($result);
			return array('ret'=>0,'s'=>$row['u_status'],'pr'=>$row['u_priority']);
		}else{
			return array('ret'=>1);
		}
	}
	public function add($name,$p,$check=null){
		$tn = MBAdmin::$dbName.$name;
		$v = array();
		$v1 = array();
		if($check){
			$sql = 'select * from '.$tn.' where '.$check.'="'.mysql_real_escape_string($p[$check]).'"';
			$ret = mysql_query($sql,$this->link);
			if(mysql_affected_rows()>0){
				return 104;
			}
		}
		foreach($p as $key => $b){
			array_push($v,$key);	
			array_push($v1,$this->check($b));	
		}
		$sql = 'insert into '.$tn.' ('.implode(',',$v).') values ("'.implode('","',$v1).'")';
		$ret = mysql_query($sql,$this->link);		
		return $ret;
	}

	public function crfile($name){
		switch($name){
			//模块
			case 'mod':
				$tn = MBAdmin::$dbName.MBAdmin::$mod;
				$sql = 'select mod_id,mod_name,mod_sort_id,mod_param1,mod_param2,mod_status from '.$tn.' order by mod_sort_id asc';
				$result = mysql_query($sql,$this->link);
				if(mysql_affected_rows()>0){
					$str = "<?php\r";
					$str .= '$mod=array("count" => '.mysql_affected_rows().',"list" => array(';
					while($row = mysql_fetch_assoc($result)){
						$str .= $row['mod_param1'].'=>array(';
						$str .= '"id"=>'.$row['mod_id'].',';
						$str .= '"n"=>\''.$row['mod_name'].'\',';
						$str .= '"sort"=>'.$row['mod_sort_id'].',';
						$str .= '"p2"=>'.$row['mod_param2'].',';
						$str .= '"status" =>'. $row['mod_status'].',';
						$str .= '),';						
					}
					$str .= ")\r);?>";
					$file = MB_ADMIN_DIR.'/data/mod.php';
					$hd = fopen($file,'wb');
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
					fclose($hd);
				}
				break;	
			//推荐话题
			case 'rtopic':
				$tn = MBAdmin::$dbName.MBAdmin::$recommTopic;
				$nowtime = time();
				@include(MB_ADMIN_DIR.'/data/mod.php');
				if($mod['list'][1]['status']){
					$sql = 'select ht_text as tit,ht_enable_time as time from '.$tn.' where ht_add_time<'.$nowtime.' and ht_enable_time>'.$nowtime.' order by ht_add_time desc limit 0,'.(int) $mod['list'][1]['p2'];
					$result = mysql_query($sql,$this->link);
					$file = MB_ADMIN_DIR.'/data/recommtopic.php';
					$hd = fopen($file,'w+');
					$acount = mysql_affected_rows();
					if($acount>0){
						$time = 0;
						$tmplist = array('count'=>$acount,'list'=>array());
						$str = "<?php\r";
						$str .= '$recommtopic=array("count" => '.$acount.',"list" => array(';
						while($row = mysql_fetch_assoc($result)){
							$str .= '\''.$row['tit'].'\',';
							$tmplist['list'][] = $row['tit'];
							if($time==0){
								$time = $row['time'];
							}
						}
						$str .= ")\r);\r";
						$str .= "\$time = $time;\r?>";
						if(fwrite($hd, $str) === FALSE) {
							exit;
						}
					}else{
						$str = "<?php\r\$recommtopic=array('count'=>0,'list'=>null);\r?>";	
						if(fwrite($hd, $str) === FALSE) {
							exit;
						}
						$tmplist = array('count'=>0,'list'=>null);
					}
					fclose($hd);
					return $tmplist;	
				}			
				break;
			//热门话题
			case 'htopic':
				$tn = MBAdmin::$dbName.MBAdmin::$hotTopic;
				@include_once(MB_ADMIN_DIR.'/data/mod.php');
				if($mod['list'][2]['status']){
					$sql = 'select * from '.$tn.' where hot_status=1 order by hot_add_time desc limit 0,'.(int) $mod['list'][2]['p2'];
					$result = mysql_query($sql,$this->link);
					$file = MB_ADMIN_DIR.'/data/hottopic.php';
					$hd = fopen($file,'w+');
					if(mysql_affected_rows()>0){
						$str = "<?php\r";
						$str .= '$hottopic = array("count" => '.mysql_affected_rows().',"list" => array(';
						while($row = mysql_fetch_assoc($result)){
							$str .= "array(";
							$str .= '"text"=>\''.$row['hot_ht_text'].'\',';
							$str .= '"id"=>\''.$row['hot_ht_id'].'\'';
							$str .="),";
						}
						$str .= ")\r);?>";
						if(fwrite($hd, $str) === FALSE) {
							exit;
						}
					}else{
						$str = "<?php\r\$hottopic=array('count'=>0,'list'=>null);\r?>";	
						if(fwrite($hd, $str) === FALSE) {
							exit;
						}
					}
					fclose($hd);
				}				
				break;
			//推荐用户
			case 'ruser':
				$tn = MBAdmin::$dbName.MBAdmin::$recommUser;
				@include_once(MB_ADMIN_DIR.'/data/mod.php');
				if($mod['list'][3]['status']){
					$sql = 'select recomm_weibo_url as url,recomm_user_introduction as info,recomm_weibo_name as uname from '.$tn.' order by recomm_id desc limit 0,'.(int) $mod['list'][3]['p2'];
					$result = mysql_query($sql,$this->link);
					$file = MB_ADMIN_DIR.'/data/recommuser.php';
					$hd = fopen($file,'w+');
					if(mysql_affected_rows()>0){
						$str = "<?php\r";
						$str .= '$recommuser=array("count" => '.mysql_affected_rows().',"list" => array(';
						while($row = mysql_fetch_assoc($result)){
							$str .= 'array("uname" => \''.$row['uname'].'\',"info" => \''.$row['info'].'\'),';
						}
						$str .= ")\r);?>";
						if(fwrite($hd, $str) === FALSE) {
							exit;
						}
						fclose($hd);
					}else{
						$str = "<?php\r\$recommuser=array('count'=>0,'list'=>null);\r?>";	
						if(fwrite($hd, $str) === FALSE) {
							exit;
						}
					}
				}			
				break;
			case 'keyword':
				$tn = MBAdmin::$dbName.MBAdmin::$keyWords;
				$sql = 'select key_words as word from '.$tn;
				$result = mysql_query($sql,$this->link);
				$file = MB_ADMIN_DIR.'/data/filterkeyword.php';
				$hd = fopen($file,'w+');
				if(mysql_affected_rows()>0){
					$str = "<?php\r";
					$str .= '$keyword=array("count" => '.mysql_affected_rows().',"list" => array(';
					while($row = mysql_fetch_assoc($result)){
						$str .= '\''.$row['word'].'\',';
					}
					$str .= ")\r);?>";
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}else{
					$str = "<?php\r\$keyword=array('count'=>0,'list'=>null);\r?>";	
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}
				fclose($hd);
				break;
			case 'tweet':
				$tn = MBAdmin::$dbName.MBAdmin::$weiboFilter;
				$sql = 'select filter_tweet_id as tid from '.$tn;	
				$result = mysql_query($sql,$this->link);
				$file = MB_ADMIN_DIR.'/data/filtertweet.php';
				$hd = fopen($file,'w+');
				if(mysql_affected_rows()>0){
					$str = "<?php\r";
					$str .= '$filtertweet=array("count" => '.mysql_affected_rows().',"list" => array(';
					while($row = mysql_fetch_assoc($result)){
						$str .=  '\'a'.$row['tid'].'a\',';
					}
					$str .= ")\r);?>";
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}else{
					$str = "<?php\r\$filtertweet=array('count'=>0,'list'=>null);\r?>";	
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}
				fclose($hd);
				break;
			case 'repost':
				$tn = MBAdmin::$dbName.MBAdmin::$commentFilter;
				$sql = 'select filter_tweet_id as tid from '.$tn;
				$result = mysql_query($sql,$this->link);
				$file = MB_ADMIN_DIR.'/data/filterrepost.php';
				$hd = fopen($file,'w+');
				if(mysql_affected_rows()>0){
					$str = "<?php\r";
					$str .= '$filterrepost=array("count" => '.mysql_affected_rows().',"list" => array(';
					while($row = mysql_fetch_assoc($result)){
						$str .=  '\'a'.$row['tid'].'a\',';
					}
					$str .= ")\r);?>";
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}else{
					$str = "<?php\r\$filterrepost=array('count'=>0,'list'=>null);\r?>";	
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}
				fclose($hd);
				break;
			case 'fuser':
				$tn = MBAdmin::$dbName.MBAdmin::$blackList;
				$sql = 'select black_name as uid from '.$tn;
				$result = mysql_query($sql,$this->link);
				$file = MB_ADMIN_DIR.'/data/filteruser.php';
				$hd = fopen($file,'w+');
				if(mysql_affected_rows()>0){
					$str = "<?php\r";
					$str .= '$blackuser=array("count" => '.mysql_affected_rows().',"list" => array(';
					while($row = mysql_fetch_assoc($result)){
						$str .= $row['uid'].',';
					}
					$str .= ")\r);?>";
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}else{
					$str = "<?php\r\$blackuser=array('count'=>0,'list'=>null);\r?>";	
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}
				fclose($hd);
				break;
			case 'logo':
				$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
				$sql = 'select config_value from '.$tn.' where config_name="logo_url"'; 
				$result = mysql_query($sql,$this->link);
				$file = MB_ADMIN_DIR.'/data/logo.php';
				$hd = fopen($file,'wb');
				if(mysql_affected_rows()>0){
					$row = mysql_fetch_assoc($result);
					$str = "<?php\r";
					$str .= '$logostr=\''.$row['config_value'].'\';';
					$str .= "\r?>";
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}					
				}else{
					$str = "<?php\r\$logostr='';\r?>";		
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}
				}
				fclose($hd);
				break;
			case 'foot':
				$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
				$sql = 'select config_value from '.$tn.' where config_name="page_tail_text"'; 
				$result = mysql_query($sql,$this->link);
				$file = MB_ADMIN_DIR.'/data/footer.php';
				if(mysql_affected_rows()>0){
					$row = mysql_fetch_assoc($result);
					$hd = fopen($file,'w+');
					if(fwrite($hd, $row['config_value']) === FALSE) {
						exit;
					}					
					fclose($hd);
				}
				break;
			case 'search':
				$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
				$sql = 'select config_value from '.$tn.' where config_name="search"'; 
				$result = mysql_query($sql,$this->link);
				$file = MB_ADMIN_DIR.'/data/search.php';
				if(mysql_affected_rows()>0){
					$row = mysql_fetch_assoc($result);
					$str = "<?php\r";
					$str .= '$search=\''.$row['config_value'].'\';';
					$str .= "\r?>";					
					$hd = fopen($file,'w+');
					if(fwrite($hd, $str) === FALSE) {
						exit;
					}					
					fclose($hd);
				}
				break;						
		}
	}		
}

class MBAdminRecomm extends MBAdmin{
	/**
	 * 显示模块
	 */
	public function showMod($s=0){
		if($s){
			@include(MB_ADMIN_DIR.'/data/mod.php');
			return $mod;
		}else{
			$tn = MBAdmin::$dbName.MBAdmin::$mod;
			$sql = 'select mod_id,mod_name,mod_sort_id,mod_status from '.$tn.' order by mod_sort_id asc';
			$result = mysql_query($sql,$this->link);
			if(mysql_affected_rows()>0){
				while($row = mysql_fetch_assoc($result)){
					$tmp[] = array(
						'id'=> $row['mod_id'],
						'n' => $row['mod_name'],
						'sort' => $row['mod_sort_id'],
						'status' => $row['mod_status']
					);
				}
				$ret = array(
					'count' => $tmp[0][count],
					'list' => $tmp	
				);			
			}else{
				$ret = array(
					'count' => 0,
					'list' => null	
				);			
			}
			return $ret;
		}
	}

	public function changeModStatus($id,$s){
		$tn = MBAdmin::$dbName.MBAdmin::$mod;
		$sql = 'update '.$tn.' set mod_status='.(int) $s.' where mod_id='.(int) $id;
		$result = mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('mod');
		echo mysql_affected_rows();
	}

	/**
	 * 获取话题列表
	 * @num 一次获取多少条
	 * @return array
	 */
	public function showTopic($p=0,$num = 10,$s=0){
		if($s){
			@include(MB_ADMIN_DIR.'/data/recommtopic.php');	
			if($time < time()){
				$recommtopic = $this->crfile('rtopic');	
				return $recommtopic; 
			}else{
				return $recommtopic;
			}
		}else{
			$start = $p*$num;
			$tn1 = MBAdmin::$dbName.MBAdmin::$recommTopic;
			$tn2 = MBAdmin::$dbName.MBAdmin::$userList;
			$sql = 'select o.ht_id as id,o.ht_text as tit,o.ht_add_time as st,o.ht_enable_time as et,u.u_name as uname,c.count from '.$tn1.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn1.') as c where o.ht_operator_id = u.u_id order by o.ht_add_time desc limit '.(int) $start.','.(int) $num;
			$result = mysql_query($sql,$this->link);
			$ret = array();
			$tmp = array();
			if(mysql_affected_rows()>0){
				while($row = mysql_fetch_assoc($result)){
					$tmp[] = array(
						'id' => $row['id'],
						'tit' => $row['tit'],
						'st' => $row['st'],
						'et' => $row['et'],
						'uname' => $row['uname'],
						'count' => $row['count']
					);
				};
				$ret = array(
					'count' => $tmp[0][count],
					'list' => $tmp	
				);
			}else{
				$ret = array(
					'count' => 0,
					'list' => null
				);		
			}
			return $ret;
		}
	} 
	/*插入话题*/
	public function addHT($name,$p,$check=null){
		$tn = MBAdmin::$dbName.$name;
		$v = array();
		$v1 = array();
		if($check){
			$sql = 'select * from '.$tn.' where '.$check.'="'.mysql_real_escape_string($p[$check]).'"';
			$ret = mysql_query($sql,$this->link);
			if(mysql_affected_rows()>0){
				return 104;
			}
		}
		$sql = 'select * from '.$tn.' where ht_enable_time>='.(int) $p['ht_add_time'].' and ht_add_time <= '.(int) $p['ht_add_time'] ;
		mysql_query($sql);
		if(mysql_affected_rows()>0){
			return 109;
			exit();
		}
		$sql = 'select * from '.$tn.' where ht_add_time<='.(int) $p['ht_enable_time'].' and ht_endable_time >='.(int) $p['ht_enable_time'];
		mysql_query($sql);
		if(mysql_affected_rows()>0){
			return 109;
			exit();
		}
		$sql = 'select * from '.$tn.' where ht_add_time<='.(int) $p['ht_add_time'].' and ht_endable_time >='.(int) $p['ht_enable_time'];
		mysql_query($sql);
		if(mysql_affected_rows()>0){
			return 109;
			exit();
		}		
		foreach($p as $key => $b){
			array_push($v,$key);	
			array_push($v1,$this->check($b));	
		}
		$sql = 'insert into '.$tn.' ('.implode(',',$v).') values ("'.implode('","',$v1).'")';
		$ret = mysql_query($sql,$this->link);		
		return $ret;
	}	

	public function delTopic($id){
		$tn = MBAdmin::$dbName.MBAdmin::$recommTopic;
		$sql = 'delete from '.$tn.' where ht_id='.(int) $id;	
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('rtopic');
		echo mysql_affected_rows();
	}

	public function editTopic($id,$p){
		$tn = MBAdmin::$dbName.'recomm_ht';
		$sql = 'select ht_id as id from '.$tn.' where ht_enable_time>='.(int) $p['ht_add_time'].' and ht_add_time <= '.(int) $p['ht_add_time'] ;
		$result = mysql_query($sql,$this->link);
		if(mysql_num_rows($result)>1){
			return 109;
			exit();
		}elseif(mysql_num_rows($result)==1){
			$row = mysql_fetch_assoc($result);	
			if($row['id'] != $id){
				return 109;
				exit();
			}
		}
		$sql = 'select ht_id as id  from '.$tn.' where ht_add_time<='.(int) $p['ht_enable_time'].' and ht_enable_time >='.(int) $p['ht_enable_time'];
		$result = mysql_query($sql,$this->link);
		if(mysql_num_rows($result)>1){
			return 109;
			exit();
		}elseif(mysql_num_rows($result)==1){
			$row = mysql_fetch_assoc($result);	
			if($row['id'] != $id){
				return 109;
				exit();
			}
		}
		$sql = 'select ht_id as id  from '.$tn.' where ht_add_time<='.(int) $p['ht_add_time'].' and ht_enable_time >='.(int) $p['ht_enable_time'];
		$result = mysql_query($sql,$this->link);
		if(mysql_num_rows($result)>1){
			return 109;
			exit();
		}elseif(mysql_num_rows($result)==1){
			$row = mysql_fetch_assoc($result);	
			if($row['id'] != $id){
				return 109;
				exit();
			}
		}
		$sql = 'update '.$tn.' set ht_text="'.mysql_real_escape_string($p['ht_text']).'",ht_add_time='.mysql_real_escape_string($p['ht_add_time']).',ht_enable_time='.(int) $p['ht_enable_time'].',ht_operator_id='.(int) $p['ht_operator_id'].' where ht_id='.(int) $id;
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('rtopic');
		return $ret;
	}

	/**
	 * 获取推荐用户列表
	 * @p 第几页
	 * @num 一次获取多少条
	 * @return array
	 * 
	 */
	public function showrUser($p=0,$num = 10,$s=0){
		if($s){
			@include_once(MB_ADMIN_DIR.'/data/recommuser.php');	
			return $recommuser; 
		}else{
			$start = $p*$num;
			$tn1 = MBAdmin::$dbName.'recomm_user';
			$tn2 = MBAdmin::$dbName.'user';
			$sql = 'select o.recomm_id as id,o.recomm_weibo_url as url,o.recomm_user_introduction as info,o.recomm_sort_id as sort,o.recomm_add_time as at,o.recomm_weibo_name as uname,u.u_name as oname,c.count from '.$tn1.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn1.') as c where o.recomm_operator_id = u.u_id order by o.recomm_id desc limit '.(int) $start.','.(int) $num;
			$result = mysql_query($sql,$this->link);
			$ret = array();
			$tmp = array();
			if(mysql_affected_rows()>0){
				while($row = mysql_fetch_assoc($result)){
					$tmp[] = array(
						'id' => $row['id'],
						'url' => $row['url'],
						'sort' => $row['sort'],
						'at' => $row['at'],
						'info' => $row['info'],
						'uname' => $row['uname'],
						'oname' => $row['oname'],
						'count' => $row['count']
					);
				};
				$ret = array(
					'count' => $tmp[0][count],
					'list' => $tmp	
				);
			}else{
				$ret = array(
					'count' => 0,
					'list' => null
				);	
			}
			return $ret;		
		}
	}
	/*
	 *删除推荐用户
	 * */
	public function delrUser($id){
		$tn = MBAdmin::$dbName.'recomm_user';
		$sql = 'delete from '.$tn.' where recomm_id='.(int) $id;
		mysql_query($sql,$this->link);
		$this->crfile('ruser');
		echo mysql_affected_rows();
	}

	/*
	 *¡修改推荐用户
	 * */
	public function editrUser($id,$p){
		$tn = MBAdmin::$dbName.'recomm_user';
		$sql = 'update '.$tn.' set recomm_weibo_url="'.mysql_real_escape_string($p['url']).'",recomm_user_introduction="'.mysql_real_escape_string($p['info']).'",recomm_sort_id='.(int) $p['sort'].',recomm_weibo_name="'.mysql_real_escape_string($p['uname']).'",recomm_operator_id='.(int) $p['oname'].' where recomm_id='.(int) $id;
		mysql_query($sql,$this->link);
		$this->crfile('ruser');
		return mysql_affected_rows();
	}

	/*
	 * 查看热门话题
	 */
	public function showHotTopic($p=0,$num=10,$s=0){
		if($s){
			@include_once(MB_ADMIN_DIR.'/data/hottopic.php');
			return $hottopic;
		}else{
			$start = $p*$num;
			$tn1 = MBAdmin::$dbName.'hot_ht';
			$tn2 = MBAdmin::$dbName.'user';
			$sql = 'select o.hot_id as id,o.hot_ht_text as tit,o.hot_sort_id as sort,o.hot_add_time as at,o.hot_status as status,u.u_name as oname,c.count from '.$tn1.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn1.') as c where o.hot_operator_id = u.u_id order by o.hot_id desc limit '.(int) $start.','.(int) $num;
			$result = mysql_query($sql,$this->link);
			$ret = array();
			$tmp = array();
			if(mysql_affected_rows()>0){
				while($row = mysql_fetch_assoc($result)){
					$tmp[] = array(
						'id' => $row['id'],
						'tit' => $row['tit'],
						'sort' => $row['sort'],
						'status' => $row['status'],
						'at' => $row['at'],
						'oname' => $row['oname'],
						'count' => $row['count']
					);
				};
				$ret = array(
					'count' => $tmp[0][count],
					'list' => $tmp	
				);
			}else{
				$ret = array(
					'count' => 0,
					'list' => null
				);	
			}
			return $ret;		
		}
	}

	//删除热门话题	
	public function delHotTopic($id){
		$tn = MBAdmin::$dbName.'hot_ht';
		$sql = 'delete from '.$tn.' where hot_id='.(int) $id;
		mysql_query($sql,$this->link);
		$this->crfile('htopic');
		echo mysql_affected_rows();		
	}

	/*
	 *¡修改热门话题
	 * */
	public function editHotTopic($id,$p){
		$tn = MBAdmin::$dbName.'hot_ht';
		$sql = 'update '.$tn.' set hot_ht_text="'.mysql_real_escape_string($p['hot_ht_text']).'",hot_sort_id="'.(int) $p['hot_sort_id'].'",hot_status='.(int) $p['hot_status'].',hot_operator_id='.(int) $p['hot_operator_id'].' where hot_id='.(int) $id;
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('htopic');
		return $ret;
	}

	public function getMod($i=0){
		$tn = MBAdmin::$dbName.MBAdmin::$mod;
		if($i){
			$sql = 'select * from '.$tn.' where mod_param1='.mysql_real_escape_string($i);
		}else{
			$sql = 'select * from '.$tn;
		}
		$result = mysql_query($sql,$this->link);
		if(mysql_affected_rows()){
			$ret = array();
			$ret['list'] = array();
			$ret['count'] = mysql_affected_rows();
			while($row = mysql_fetch_assoc($result)){
				$ret['list'][] = array(
					'name' => $row['mod_name'],
					'status' => $row['mod_status'],
					'p2' => $row['mod_param2'],
					'p3' => $row['mod_param3'],
					'p4' => $row['mod_param4']
				);
			}
			return $ret;			
		}else{
			return array('count'=>0,'list'=>null);	
		}
	}

	public function changeMod($c,$n,$p=0){
		$tn = MBAdmin::$dbName.MBAdmin::$mod;
		if($p){
			$sql = 'update '.$tn.' set mod_name="'.mysql_real_escape_string($n).'",mod_param2='.mysql_real_escape_string($p).' where mod_param1='.mysql_real_escape_string($c);
		}else{
			$sql = 'update '.$tn.' set mod_name="'.mysql_real_escape_string($n).'" where mod_param1='.mysql_real_escape_string($c);
		}
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('mod');
		if($c==3){
			$this->crfile('ruser');
		}elseif($c==2){
			$this->crfile('htopic');
		}
		return $ret;
	}
}

class MBAdminManage extends MBAdmin{
	public function changepwd($u,$p,$np){
		$tn = MBAdmin::$dbName.'user';
		$sql = 'update '.$tn.' set u_password="'.md5($np).'" where u_name="'.mysql_real_escape_string($u).'" and u_password="'.md5($p).'"';	
		mysql_query($sql,$this->link);
		return mysql_affected_rows();
	}
	
	public function add($name,$p,$check=null){
		$tn = MBAdmin::$dbName.$name;
		$sql = 'select * from '.$tn.' where u_name="'.mysql_real_escape_string($p['n']).'"';
		$ret = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			return 105;
		}
		$sql = 'insert into '.$tn.' (u_name,u_add_time,u_status,u_priority,u_password) values ("'.$this->check($p['n']).'",'.(int) $p['at'].','.(int) $p['s'].','.(int) $p['pr'].',"'.md5($p['p']).'")';
		$ret = mysql_query($sql,$this->link);		
		if(mysql_affected_rows()>0){
			return 1;
		}else{
			return 0;
		}
	}

	public function showUser($p=0,$num = 10){
		$start = $p*$num;
		$tn = MBAdmin::$dbName.'user';
		$sql = 'select o.u_id as id,o.u_name as uname,o.u_add_time as at,o.u_status as status,o.u_priority as pr,c.count from '.$tn.' as o,(select count(*) as count from '.$tn.') as c order by o.u_id desc limit '.(int) $start.','.(int) $num;
		$result = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			while($row = mysql_fetch_assoc($result)){
				$tmp[] = array(
					'id' => $row['id'],
					'u' => $row['uname'],
					'at' => $row['at'],
					's' => $row['status'],
					'pr' => $row['pr'],
					'count' => $row['count']
				);
			};
			$ret = array(
				'count' => $tmp[0][count],
				'list' => $tmp	
			);
		}else{
			$ret = array(
				'count' => 0,
				'list' => null
			);	
		}
		return $ret;				
	}

	public function delUser($id){
		$tn = MBAdmin::$dbName.'user';
		$sql = 'select u_id from '.$tn.' where 	u_priority =2';
		$result = mysql_query($sql,$this->link);
		if(mysql_affected_rows()<=1){
			$row = mysql_fetch_assoc($result);
			if($row['u_id'] == $id){
				echo 3;
				return;
			}
		}
		$sql = 'update '.$tn.' set u_status=-1 where u_id='.(int) $id;
		mysql_query($sql,$this->link);
		if( mysql_affected_rows()>=0){
			echo 1;
		}else{
			echo 0;
		}
	}	

	public function editUser($id,$p){
		if($_SESSION["pro"]<2){
			return 0;
		}
		$tn = MBAdmin::$dbName.'user';
		if($p['s']==0 && $id == $_SESSION['userid']){
			return 31;
		}
		if($p['pr'] == 1){
			if($id==$_SESSION['userid']){
				return 32;
			}
			$sql = 'select u_id from '.$tn.' where u_priority =2';
			$result = mysql_query($sql,$this->link);
			if(mysql_num_rows($result)<2){
				//return 32;
			}	
		}
		$sql = 'update '.$tn.' set u_name="'.mysql_real_escape_string($p['n']).'",u_status='.mysql_real_escape_string($p['s']).',u_priority="'.mysql_real_escape_string($p['pr']).'" where u_id='.(int) $id;
		mysql_query($sql,$this->link);
		return mysql_affected_rows();	
	}
}

class MBAdminFilter extends MBAdmin{
	/*
	 * 屏蔽字
	 */
	public function showKeyword($p=0,$num=10,$s=0,$k=null){
		if($s){
			@include(MB_ADMIN_DIR.'/data/filterkeyword.php');
			return $keyword;
		}else{
			$start = $p*$num;
			$tn = MBAdmin::$dbName.'keywords';
			$tn2 = MBAdmin::$dbName.'user';
			if(is_null($k)){
				$sql = 'select o.key_id as id,o.key_words as word,o.key_add_time as at,o.key_type_id as type,u.u_name as oname,c.count from '.$tn.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn.') as c where o.key_operator_id = u.u_id order by o.key_id desc limit '.(int) $start.','.(int) $num;
			}else{
				$sql = 'select o.key_id as id,o.key_words as word,o.key_add_time as at,o.key_type_id as type,u.u_name as oname,c.count from '.$tn.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn.' where o.key_words like "'.$k.'") as c where o.key_operator_id = u.u_id and o.key_words like "'.$k.'" order by o.key_id desc limit '.(int) $start.','.(int) $num;
			}
			$result = mysql_query($sql,$this->link);
			if(mysql_affected_rows()>0){
				while($row = mysql_fetch_assoc($result)){
					$tmp[] = array(
						'id' => $row['id'],
						'word' => $row['word'],
						'at' => $row['at'],
						'type' => $row['type'],
						'oname' => $row['oname'],
						'count' => $row['count']
					);
				};
				$ret = array(
					'count' => $tmp[0][count],
					'list' => $tmp	
				);
			}else{
				$ret = array(
					'count' => 0,
					'list' => null
				);		
			}
			return $ret;		
		}
	}
	/*
	 * 修改关键字
	 */
	public function editKeyword($id,$p){
		$tn = MBAdmin::$dbName.'keywords';
		$sql = 'update '.$tn.' set key_words="'.mysql_real_escape_string($p['n']).'",key_type_id='.(int) $p['type'].',key_operator_id="'.(int) $p['oid'].'" where key_id='.(int) $id;
		mysql_query($sql,$this->link);
		$ret =  mysql_affected_rows();
		$this->crfile('keyword');
		return $ret;	
	}

	/*
	 *删除关键字
	*/
	public function delKeyword($id){
		$tn = MBAdmin::$dbName.'keywords';
		$sql = 'delete from '.$tn.' where key_id='.(int) $id;
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('keyword');
		return $ret;
	}

	/*
	 * 屏蔽微博
	 */
	public function showT($p=0,$num=10,$s=0,$k=null){
		if($s){
			@include(MB_ADMIN_DIR.'/data/filtertweet.php');
			return $filtertweet;
		}else{
			$start = $p*$num;
			$tn = MBAdmin::$dbName.'weibo_filter';
			$tn2 = MBAdmin::$dbName.'user';
			if(is_null($k)){
				$sql = 'select o.filter_id as id,o.filter_tweet_id as tid,o.filter_tweet_text as text,o.filter_add_time as at,u.u_name as oname,c.count from '.$tn.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn.') as c where o.filter_operator_id = u.u_id order by o.filter_id desc limit '.(int) $start.','.(int) $num;
			}else{
				$sql = 'select o.filter_id as id,o.filter_tweet_id as tid,o.filter_tweet_text as text,o.filter_add_time as at,u.u_name as oname,c.count from '.$tn.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn.' where o.filter_operator_id = u.u_id o.filter_tweet_text like "'.$k.'") as c where o.filter_operator_id = u.u_id and o.filter_tweet_text like "'.$k.'" order by o.filter_id desc limit '.(int) $start.','.(int) $num;
			}
			$result = mysql_query($sql,$this->link);
			if(mysql_affected_rows()>0){
				while($row = mysql_fetch_assoc($result)){
					$tmp[] = array(
						'id' => $row['id'],
						'tid' => $row['tid'],
						'at' => $row['at'],
						'text' => $row['text'],
						'oname' => $row['oname'],
						'count' => $row['count']
					);
				};
				$ret = array(
					'count' => $tmp[0][count],
					'list' => $tmp	
				);
			}else{
				$ret = array(
					'count' => 0,
					'list' => null
				);		
			}
			return $ret;	
		}
	}
	/*
	 *取消屏蔽的微博
	*/
	public function delT($id){
		$tn = MBAdmin::$dbName.'weibo_filter';
		$sql = 'delete from '.$tn.' where filter_id='.(int) $id;
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('tweet');
		echo $ret;
	}

	/*
	 * 屏蔽回复
	 */
	public function showRepost($p=0,$num=10,$s=0,$k=null){
		$start = $p*$num;
		$tn = MBAdmin::$dbName.'comment_filter';
		$tn2 = MBAdmin::$dbName.'user';
		if($s){
			@include(MB_ADMIN_DIR.'/data/filterrepost.php');
			return $filterrepost;
		}else{
			if(is_null($k)){
				$sql = 'select o.filter_id as id,o.filter_tweet_id as tid,o.filter_tweet_text as text,o.filter_add_time as at,u.u_name as oname,c.count from '.$tn.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn.') as c where o.filter_operator_id = u.u_id order by o.filter_id desc limit '.(int) $start.','.(int) $num;
			}else{
				$sql = 'select o.filter_id as id,o.filter_tweet_id as tid,o.filter_tweet_text as text,o.filter_add_time as at,u.u_name as oname,c.count from '.$tn.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn.' where o.filter_tweet_text like "'.$k.'") as c where o.filter_operator_id = u.u_id o.filter_tweet_text like "'.$k.'" order by o.filter_id desc limit '.(int) $start.','.(int) $num;
			}
			$result = mysql_query($sql,$this->link);
			if(mysql_affected_rows()>0){
				while($row = mysql_fetch_assoc($result)){
					$tmp[] = array(
						'id' => $row['id'],
						'tid' => $row['tid'],
						'text' => $row['text'],
						'at' => $row['at'],
						'oname' => $row['oname'],
						'count' => $row['count']
					);
				};
				$ret = array(
					'count' => $tmp[0][count],
					'list' => $tmp	
				);
			}else{
				$ret = array(
					'count' => 0,
					'list' => null
				);		
			}
		}
		return $ret;	
	}

	/*
	 *取消屏蔽的点评
	*/
	public function delR($id){
		$tn = MBAdmin::$dbName.'comment_filter';
		$sql = 'delete from '.$tn.' where filter_id='.(int) $id;
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('repost');
		echo mysql_affected_rows();	
	}

	/*
	 *黑名单用户 
	 */
	public function showUser($p=0,$num=10,$s=0,$k=null){
		$start = $p*$num;
		$tn1 = MBAdmin::$dbName.'black_user';
		$tn2 = MBAdmin::$dbName.'user';
		if($s){
			@include_once(MB_ADMIN_DIR.'/data/filteruser.php');
			return $blackuser;
		}else{
			if(is_null($k)){
				$sql = 'select o.black_id as id,o.black_name as name,o.black_nick as nick,o.black_head_url as url,o.black_add_time as at,u.u_name as oname,c.count from '.$tn1.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn1.') as c where o.black_operator_id = u.u_id order by o.black_id desc limit '.(int) $start.','.(int) $num;
			}else{
				$sql = 'select o.black_id as id,o.black_name as name,o.black_nick as nick,o.black_head_url as url,o.black_add_time as at,u.u_name as oname,c.count from '.$tn1.' as o,'.$tn2.' as u,(select count(*) as count from '.$tn1.' where o.black_operator_id = u.u_id and o.key_words like "'.$k.'") as c where o.key_words like "'.$k.'" order by o.black_id desc limit '.(int) $start.','.(int) $num;
			}
			$result = mysql_query($sql,$this->link);
			if(mysql_affected_rows()>0){
				while($row = mysql_fetch_assoc($result)){
					$tmp[] = array(
						'id' => $row['id'],
						'name' => $row['name'],
						'at' => $row['at'],
						'nick' => $row['nick'],
						'url' => $row['url'],
						'oname' => $row['oname'],
						'count' => $row['count']
					);
				};
				$ret = array(
					'count' => $tmp[0][count],
					'list' => $tmp	
				);
			}else{
				$ret = array(
					'count' => 0,
					'list' => null
				);		
			}
		}
		return $ret;	
	}

	public function delUser($id){
		$tn = MBAdmin::$dbName.'black_user';
		$sql = 'delete from '.$tn.' where black_id='.(int) $id;
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		$this->crfile('fuser');
		return $ret;
	}	
}
class MBAdminVerify extends MBAdmin{
	/*添加认证用户*/
	public function add($para,$check=null){
		$tn = MBAdmin::$dbName.'verify_user';
		$sql = 'select * from '.$tn.' where v_name="'.mysql_real_escape_string($para['name']).'"';
		$ret = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			return 104;/*用户已存在*/
		}
		$sql = 'insert into '.$tn.' (v_name,v_desc,v_time_add) values ("'.$this->check($para['name']).'","'.$para['desc'].'",'.time().')';
		$ret = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			return 1;
		}else{
			return 0;
		}
	}
	public function getIconName(){
		@include(MB_ADMIN_DIR.'/data/verify_icon.php');	
		if(isset($verify_icon_file_name)){
			return $verify_icon_file_name;
		}
		return "";
	}
	/*更新用户资料*/
	public function update($id,$para){
		$tn = MBAdmin::$dbName.'verify_user';
		$id = (int)trim($id);
		$name = $this->check($para["name"]);
		$desc = $this->check($para["desc"]);
		$sql = 'update '.$tn.' set v_name='.'"'.$name.'",v_desc='.'"'.$desc.'" where v_id='.$id;
		$ret = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			return 1;
		}else{
			return 0;
		}
	}
	/*取消认证用户*/
	public function del($id){
		$tn = MBAdmin::$dbName.'verify_user';
		$sql = 'delete from '.$tn.' where v_id='.(int) $id;
		mysql_query($sql,$this->link);
		$ret = mysql_affected_rows();
		return $ret;
	}
	/*认证用户总数，用户分页*/
	public function count(){
		$tn = MBAdmin::$dbName.'verify_user';
		$sql = 'select count(*) as count from '.$tn;
		$result = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			while($row = mysql_fetch_array($result)){
				return (int)$row["count"];
			}
		}
		return 0;
	}
	/*查询认证用户*/
	public function get($p=0,$num = 10){
		$start = $p*$num;
		$tn = MBAdmin::$dbName.'verify_user';
		$sql = 'select v_id,v_name,v_desc,v_time_add from '.$tn.' order by v_id desc limit '.(int)$start.','.(int) $num;
		$sql2 = 'select count(*) as count from '.$tn;
		$ret = array("count"=>0,"list"=>array());
		$result = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			while($row = mysql_fetch_assoc($result)){
				$ret["list"][] = array(
					'id' => $row['v_id'],
					'name' => $row['v_name'],
					'desc' => $row['v_desc'],
					'time_add' => $row['v_time_add'],
				);
			};
		}
		$result2 = mysql_query($sql2,$this->link);
		if(mysql_affected_rows()>0){
			$row2 = mysql_fetch_array($result2);
			$ret["count"]=(int)$row2["count"];
		}
		return $ret;
	}
	
	public function search($key,$p,$num = 10){
		$start = $p*$num;
		$tn = MBAdmin::$dbName.'verify_user';
		$sql = 'select v_id,v_name,v_desc,v_time_add from '.$tn.' where v_name like "'.$key.'" order by v_id desc limit '.(int)$start.','.(int)$num;
		$sql2 = 'select count(*) as count from '.$tn.' where v_name like "'.$key.'"';
		$ret = array("count"=>0,"list"=>array());
		$result = mysql_query($sql,$this->link);
		if(mysql_affected_rows()>0){
			while($row = mysql_fetch_assoc($result)){
				$ret["list"][] = array(
					'id' => $row['v_id'],
					'name' => $row['v_name'],
					'desc' => $row['v_desc'],
					'time_add' => $row['v_time_add'],
				);
			};
		}
		$result2 = mysql_query($sql2,$this->link);
		if(mysql_affected_rows()>0){
			$row2 = mysql_fetch_array($result2);
			$ret["count"]=(int)$row2["count"];
		}
		return $ret;
	}
}

class MBAdminShow extends MBAdmin{
	public function getLogo(){
		@include_once(MB_ADMIN_DIR.'/data/logo.php');
		return $logostr;
		/*
		$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
		$sql = 'select config_value from '.$tn.' where config_name="logo_url"';  
		$result = mysql_query($sql,$this->link);
		$row = mysql_fetch_assoc($result);
		return $row['config_value'];
		 */
	}

	public function changeLogo($l){
		$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
		$sql = 'update '.$tn.' set config_value="'.mysql_real_escape_string($l).'" where config_name="logo_url"';
		mysql_query($sql,$this->link);
		return mysql_affected_rows();
	}

	public function getRewrite(){
		$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
		$sql = 'select config_value from '.$tn.' where config_name="url_rewrite"';  
		$result = mysql_query($sql,$this->link);
		$row = mysql_fetch_assoc($result);
		return (int) $row['config_value'];
	}

	public function changeRewrite($l){
		$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
		$sql = 'update '.$tn.' set config_value="'.mysql_real_escape_string($l).'" where config_name="url_rewrite"';
		mysql_query($sql,$this->link);
		echo mysql_affected_rows();
	}

	public function getPage(){
		$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
		$sql = 'select config_name,config_value from '.$tn.' where config_name like "%page%"'; 
		$resule = mysql_query($sql,$this->link);	
		$tmp = array();
		while($row = mysql_fetch_assoc($resule)){
			if($row['config_name'] == 'page_head_display'){
				$tmp[$row['config_name']] = (int) $row['config_value'];	
			}else{
				$tmp[$row['config_name']] = $row['config_value'];	
			}
		}
	 	return $tmp;
	}	

	public function changePage($p){
		$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
		$sql = 'update '.$tn.' set config_value="'.(int) $p['v'].'" where config_name="page_head_display"';
		$sql1 = 'update '.$tn.' set config_value="'.mysql_real_escape_string($p['h']).'" where config_name="page_head_text"';
		$sql2 = 'update '.$tn.' set config_value="'.mysql_real_escape_string($p['f']).'" where config_name="page_tail_text"';
		mysql_query($sql,$this->link);
		mysql_query($sql1,$this->link);
		mysql_query($sql2,$this->link);
		return mysql_affected_rows();
	}

	public function changeSearch($s){
		$tn = MBAdmin::$dbName.MBAdmin::$sysConfig;
		$sql = 'update '.$tn.' set config_value="'.(int) $s['v'].'" where config_name="search"';
		mysql_query($sql,$this->link);
		return mysql_affected_rows();
	}
}
?>
