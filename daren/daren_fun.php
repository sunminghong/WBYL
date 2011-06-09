<?php
if(!defined('ISWBYL')) exit('Access Denied');

	function readdarenScore($uid,$qtype=0,$nocache=false){
		if(!$nocache){
			$darenScore=sreadcookie('daren_score');		
			if(is_array($darenScore)) {
				return $darenScore;
			}	
		}
		if(!is_array($darenScore)) 
				$darenScore=array();

		//总成绩
		$sql="select wincount,filishcount,testcount,l.uid,l.name from ". dbhelper::tname("daren","daren") ." d inner join ". dbhelper::tname("ppt","login") ." l on d.uid=l.uid where uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$darenScore=$row;
		}else{	
			$darenScore["wincount"]=0;
			$darenScore['filishcount']=0;
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
				if(intval($row["todaytotal$qtype"])==0)
					$darenScore["todaywin$qtype"]=100;
				else
					$darenScore["todaywin$qtype"]=(1- $row["todaytop$qtype"]/$row["todaytotal$qtype"])*100;

			}else{
				$darenScore["todaytop$qtype"]=1;
				$darenScore["todaywin$qtype"]=100;
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

		ssetcookie('daren_score', $darenScore,3600*24*100);
		return $darenScore;
	}

?>