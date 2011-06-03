<?php 
if(!defined('ISWBYL')) exit('Access Denied');


	function _getScore() {
		$account=getAccount();		
		if(!is_array($account)) 
			return false;

		$sql="select * from ".dbhelper::tname("ilike","ilike")." where uid=".$account['uid'];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()) {
			$row['avg']= $row['byratecount']?round($row['score'] / $row['byratecount'],2): 0;
			return $row;
		}
		return false;
	}

	function _getdaytoplist() {
		global $timestamp;
		$cache=new Cache();
		$daytoplist=$cache->get("ilike_daytoplist"); 
		if(1==0 && is_array($daytoplist)) {
			$daytoplistovertime=$cache->get("ilike_daytoplist_time");
			
			if(dateDiff("d",$daytoplistovertime , $timestamp)==0) {
				return $daytoplist;
			}
			echo '/*not same day,update daytoplist!*/';
		}

		$day=strftime("%y%m%d",$timestamp);
		$sql="select topday from  where topday=$day";
		$rs=dbhelper::getrs($sql);
		if(!($row=$rs->next())) {
			$sql="insert into ".dbhelper::tname("ilike","daytop")." (topday,picid,uid,score,byratecount,bysharecount,byfollowcount,bury,wbid,big_pic,small_pic,middle_pic,regtime) select DATE_FORMAT( date_add(now(), interval -1 day) ,  '%y%m%d' ),id,uid,score,byratecount,bysharecount,byfollowcount,bury,wbid,big_pic,small_pic,middle_pic,$timestamp from ".dbhelper::tname("ilike","pics")." where regtime< UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and regtime>= UNIX_TIMESTAMP( DATE_FORMAT( date_add(now(), interval -1 day) ,  '%Y-%m-%d' ) ) and  bury<4 order by  score/byratecount desc,byratecount,id desc limit 0,1";
			//echo $sql;
			dbhelper::execute($sql);
		}
		$sql="select t.*,l.* from ".dbhelper::tname("ilike","daytop")." t inner join ".dbhelper::tname("ppt","login")." l on t.uid=l.uid order by topday desc limit 0,9";
		$rs=dbhelper::getrs($sql);
		$daytoplist=array();
		while($row=$rs->next()) {		
			$row['avg']= $row['byratecount']?round($row['score'] / $row['byratecount'],2): 0;
			$daytoplist[]=$row;
		}
		
		$cache->set("ilike_daytoplist",$daytoplist);
		$cache->set("ilike_daytoplist_time",$timestamp);
		return $daytoplist;
	}
?>