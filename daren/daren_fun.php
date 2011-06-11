<?php
if(!defined('ISWBYL')) exit('Access Denied');

	function readdarenScore($uid,$qtype=0,$nocache=false){
		if(!$nocache){
			$darenScore=sreadcookie('daren_score'.$qtype);		
			if(is_array($darenScore)) {
				return $darenScore;
			}	
		}
		if(!is_array($darenScore)) 
				$darenScore=array();

		//总成绩
		$sql="select wincount,filishcount,testcount,topcount,l.uid,l.name from ". dbhelper::tname("daren","daren") ." d inner join ". dbhelper::tname("ppt","login") ." l on d.uid=l.uid where uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$darenScore=$row;
		}else{	
			$darenScore["wincount"]=0;
			$darenScore['filishcount']=0;
			$darenScore['topcount']=0;
			$darenScore['testcount']=100000;
		}
		
		//今天成绩			
		$darenScore["todaytotalscore"]=0; 
		$darenScore['todaytotalusetime']=0;

		$sql="select qtype,max(score * 1000+990-usetime) as score2 from ". dbhelper::tname("daren","log") ." where  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and uid=$uid group by qtype order by score2";
		$rs=dbhelper::getrs($sql);
		while($row=$rs->next()){
			$darenScore["todaytotalscore"] += intval($row['score2']  / 1000);
			$darenScore['todaytotalusetime'] +=  990-  $row['score2'] % 1000 ;
		}

		//今日某科考试成绩
		if($qtype) {
			$darenScore["todayscore".$qtype]=0; 
			$darenScore['todayusetime'.$qtype]=0;
	
			$sql="select max(score * 1000+990-usetime) as score2 from ". dbhelper::tname("daren","log") ." where  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and uid=$uid and qtype=$qtype order by score2";
			$rs=dbhelper::getrs($sql);
			while($row=$rs->next()){
				$darenScore["todayscore".$qtype] = intval($row['score2'] / 1000);
				$darenScore['todayusetime'.$qtype] =  990-  $row['score2'] % 1000 ;
			}

			$sql="select (select count(*) from ". dbhelper::tname("daren","log") ." where  qytpe=$qtype and  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and score>" . $darenScore["todayscore".$qtype].") + (select count(*) from ". 
				dbhelper::tname("daren","log") ." where  qytpe=$qtype and  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and score=". $darenScore["todayscore".$qtype] ." and usetime<".$darenScore['todayusetime'.$qtype] .")  as top ,";
			$sql.="(select count(*) from ". dbhelper::tname("daren","daren") ." where qytpe=$qtype and  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) )) as total ";
			$rs=dbhelper::getrs($sql);
			if($row=$rs->next()){
				$darenScore["todaytop$qtype"]=$row['top'];
				if(intval($row["total"])==0)
					$darenScore["todaywin$qtype"]='所有';
				else
					$darenScore["todaywin$qtype"]=$row["total"]-$row['top'];   // (1- $row["todaytop$qtype"]/$row["todaytotal$qtype"])*100;

			}else{
				$darenScore["todaytop$qtype"]=1;
				$darenScore["todaywin$qtype"]='100%';
			}
		}

		//获取邀请人名单
		$sql="select l.screen_name from ".dbhelper::tname("ppt","userlib")." l  inner join ".dbhelper::tname("ppt","userlib_sns")." sns on sns.uid2=l.id and type=2 where sns.uid1=".$uid." order by rand() limit 0,6";
		$rs=dbhelper::getrs($sql);
		$sss2="";$ii=0;
		while($row=$rs->next()){
			if($ii<3 && !strpos($sss,$row['screen_name'])){
				$sss2.="@".$row['screen_name']." ，";
				$ii++;
			}
		}

		$darenScore['retname']=$sss2;
		
		//importlib("zhengshu");
		//$zs=zhengshu::makeIQ(getAccount(),$darenScore);
		//$darenScore=array_merge($darenScore,$zs);

		ssetcookie('daren_score'.$qtype, $darenScore,3600*24*100);
		return $darenScore;
	}

		function _gettodaytop() {		
			global $timestamp;
			$day=strftime("%y%m%d",$timestamp);
			$sql="select l.lfrom,l.lfromuid,l.uid,l.name,l.avatar,l.verified,t.* from (select uid,score,usetime from ". dbhelper::tname("daren","tmp_day_total") ." where winday=$day order by score desc,usetime limit 0,10) as t inner join  ". dbhelper::tname("ppt","user") ." as l on t.uid=l.uid";

			$arrs=dbhelper::getrows($sql);

			return $arrs;
		}

		function _gettestlist() {
			//global $lfrom;
			//if($lfrom) $ll="l.lfrom='$lfrom' and ";
			$top=rq("mo",0);
			$last=rq("last",0);
			if($last==0)
				$top=5;
			else
				$top=5;

			$testlist=array();
			$sql="select log.*,l.name,l.lfrom from (select * from ". dbhelper::tname("daren","log") . "   where score>0 and lasttime>{$last} order by lasttime desc limit 0,$top) as log  inner join ".dbhelper::tname("ppt","login")." l on log.uid=l.uid ";
			$rs=dbhelper::getrs($sql);
			$i=0;
			$qtypenames=readqtypelist();
			while($row=$rs->next()){
				$i++;
				$row["i"]=$i;
				$row['testtime']= date("m-d H:i:s",$row['lasttime']);
				$row['qtypename']=$qtypenames[intval($row['qtype'])][0];
				$testlist[]=$row;			
			}
			//echo json_encode($testlist);
			return $testlist;
		}

?>