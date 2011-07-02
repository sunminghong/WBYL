<?php
if(!defined('ISWBYL')) exit('Access Denied');

	function readdarenScore($uid,$qtype=0,$nocache=false){
		global $timestamp;
		if(1==0 && !$nocache){
			$darenScore=sreadcookie('daren_score'.$qtype);		
			if(is_array($darenScore)) {
				return $darenScore;
			}	
		}

		//总成绩
		$sql="select coalesce(jifen,0) as jifen,coalesce(alljifen,0) as alljifen,COALESCE(wincount,0) as wincount,COALESCE(lastwincount,0) as lastwincount,COALESCE(filishcount,0) as filishcount,COALESCE(testcount,0) as testcount,COALESCE(topcount,0) as topcount,COALESCE(lasttopcount,0) as lasttopcount,COALESCE(boshicount,0) as boshicount,COALESCE(lastboshicount,0) as lastboshicount,l.uid,l.name,l.lfrom,l.avatar,l.lfromuid from ".dbhelper::tname('ppt','user') ." l left join ".dbhelper::tname('daren','daren') ." d on d.uid=l.uid  where l.uid=$uid";//echo $sql;
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$darenScore=$row;
		}else{
			return false;
		}
		
		//今天成绩			
		$darenScore["todaytotalscore"]=0; 
		$darenScore['todaytotalusetime']=0;

		//$sql="select qtype,max(score * 1000+990-usetime) as score2 from ". dbhelper::tname("daren","log") ." where  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and uid=$uid group by qtype order by score2";
		//$rs=dbhelper::getrs($sql);
		//while($row=$rs->next()){
		//	$darenScore["todaytotalscore"] += intval($row['score2']  / 1000);
		//	$darenScore['todaytotalusetime'] +=  990-  $row['score2'] % 1000 ;
		//}

		$day=strftime("%y%m%d",$timestamp);
		$sql="select uid,score,usetime from ". dbhelper::tname("daren","tmp_day_total") ." where winday=$day and uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()) {
			$darenScore["todaytotalscore"] = $row['score'];
			$darenScore['todaytotalusetime'] = $row['usetime'];
		}

		//今日某科考试成绩
		if($qtype) {
			$darenScore["todayscore".$qtype]=0; 
			$darenScore['todayusetime'.$qtype]=0;
	
	/*
			$sql="select max(score * 1000+990-usetime) as score2 from ". dbhelper::tname("daren","log") ." where  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and uid=$uid and qtype=$qtype order by score2";
			$rs=dbhelper::getrs($sql);
			while($row=$rs->next()){
				$darenScore["todayscore".$qtype] = intval($row['score2'] / 1000);
				$darenScore['todayusetime'.$qtype] =  990-  $row['score2'] % 1000 ;
			}

			$sql="select (select count(*) from ". dbhelper::tname("daren","log") ." where  qtype=$qtype and  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and score>" . $darenScore["todayscore".$qtype].") + (select count(*) from ". 
				dbhelper::tname("daren","log") ." where  qtype=$qtype and  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) and score=". $darenScore["todayscore".$qtype] ." and usetime<".$darenScore['todayusetime'.$qtype] .")  as top ,";
			$sql.="(select count(*) from ". dbhelper::tname("daren","daren") ." where qtype=$qtype and  lasttime >UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) )) as total ";
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
			*/

			$sql="select score,usetime from ".dbhelper::tname("daren","tmp_day")." where winday=$day and qtype=$qtype and uid=$uid";
			$rs=dbhelper::getrs($sql);
			if($row=$rs->next()) {
				$darenScore["todayscore".$qtype] = $row['score'];
				$darenScore['todayusetime'.$qtype] = $row['usetime'];
			}
	
			$sql="select (select count(*) from ". dbhelper::tname("daren","tmp_day") ." where  winday=$day and qtype=$qtype and score>" . $darenScore["todayscore".$qtype].") + (select count(*) from ". dbhelper::tname("daren","tmp_day") ." where winday=$day and qtype=$qtype and score=". $darenScore["todayscore".$qtype] ." and usetime<".$darenScore['todayusetime'.$qtype] .")  as top ,";
			$sql.="(select count(*) from ". dbhelper::tname("daren","tmp_day") ." where  winday=$day and qtype=$qtype) as total ";
			$rs=dbhelper::getrs($sql);
			if($row=$rs->next()){
				$darenScore["todaytop$qtype"]=$row['top']+1;
				if(intval($row["total"])==0)
					$darenScore["todaywin$qtype"]='所有';
				else
					$darenScore["todaywin$qtype"]=$row["total"]-$row['top'];   // (1- $row["todaytop$qtype"]/$row["todaytotal$qtype"])*100;

			}else{
				$darenScore["todaytop$qtype"]=1;
				$darenScore["todaywin$qtype"]='所有';
			}

			//print_r($darenScore);

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

	function _gettodaytop($top=10) {		
		global $timestamp;
		$day=strftime("%y%m%d",$timestamp);
		$sql="select l.lfrom,l.lfromuid,l.uid,l.name,l.avatar,l.verified,t.* from (select uid,score,usetime from ". dbhelper::tname("daren","tmp_day_total") ." where winday=$day order by score desc,usetime limit 0,$top) as t inner join  ". dbhelper::tname("ppt","user") ." as l on t.uid=l.uid";

		$arrs=dbhelper::getrows($sql);

		return $arrs;
	}
	
	function _getjifentop($top=10) {
		$sql="select l.lfrom,l.lfromuid,l.uid,l.name,l.avatar,l.verified,t.* from (select uid,jifen,alljifen from ". dbhelper::tname("daren","daren") ." order by jifen desc limit 0,$top) as t inner join  ". dbhelper::tname("ppt","user") ." as l on t.uid=l.uid";

		$arrs=dbhelper::getrows($sql);

		return $arrs;
	}
		
	function _getfilishtop($top=10) {
		$sql="select l.lfrom,l.lfromuid,l.uid,l.name,l.avatar,l.verified,t.* from (select uid,filishcount,wincount from ". dbhelper::tname("daren","daren") ." order by filishcount desc,wincount desc limit 0,$top) as t inner join  ". dbhelper::tname("ppt","user") ." as l on t.uid=l.uid";

		$arrs=dbhelper::getrows($sql);

		return $arrs;
	}
			
	function _getdarentop($top=10) {
		$sql="select l.lfrom,l.lfromuid,l.uid,l.name,l.avatar,l.verified,t.* from (select uid,filishcount,wincount from ". dbhelper::tname("daren","daren") ." order by wincount desc,filishcount desc limit 0,$top) as t inner join  ". dbhelper::tname("ppt","user") ." as l on t.uid=l.uid";

		$arrs=dbhelper::getrows($sql);

		return $arrs;
	}

	function _getyesterdayniuren() {
		$cache=new CACHE();
		$arrs=$cache->get("daren_yesterdayniuren");
		
		if(is_array($arrs)) return $arrs;
		$day=date("ymd",strtotime("-1 day"));
		$sql="select l.lfrom,l.lfromuid,l.uid,l.name,l.avatar,l.verified,t.* from (select uid,qtype,winday,score,usetime, DATE_FORMAT(regtime ,  '%Y-%m-%d' ) as regtime from ". dbhelper::tname("daren","top_day") ." where winday=$day order by rand()) as t inner join  ". dbhelper::tname("ppt","user") ." as l on t.uid=l.uid order by rand() limit 0,9";
		$arrs=dbhelper::getrows($sql);

		$cache->set("daren_yesterdayniuren",$arrs);

		return $arrs;
	}

	function _gettestlist() {
		//global $lfrom;
		//if($lfrom) $ll="l.lfrom='$lfrom' and ";
		$top=rq("mo",0);
		$last=rq("last",0);
		if($last==0)
			$top=10;
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

	function _getallcount() {

		$testlist=array();
		$sql="select (select max(id) from ". dbhelper::tname("daren","log") . ") as alltestcount, (select max(uid) from ". dbhelper::tname("daren","daren") . ") as allusercount";
		$rs=dbhelper::getrs($sql);
		if(!($row=$rs->next())){
			$row=array('alltestcount'=>0,'allusercount'=>0);
		}
		//echo json_encode($testlist);
		return $row;
	}

	function _makeZhengshu($uid,$name,$score,$zhengshutype,$avatar,$date=false) {
		global $currTemplate,$timestamp;
		
		importlib("watermark.fun");
		$sql="insert into ". dbhelper::tname("ppt","zhengshu") ." set uid=$uid,type='$zhengshutype',lasttime=$timestamp";
		$zid=dbhelper::execute($sql,1);
		$no="Q". right("0000000".$zid,7);

		if(!$date)
			$date= date("y-m-d",$timestamp);

		if(ISSAE){
			$newName=tempnam(SAE_TMP_PATH, "SAE_IMAGE");
		}
		else
			$newName=ROOT."data/zhengshu/{$date}-{$zhengshutype}-{$uid}.jpg";

		$backImage=ROOT."templets/$currTemplate/daren_images/zhengshu/zhengshu_{$zhengshutype}.gif";

		
		$text=$no;
		$color="#ffffff";
		$fontFile=ROOT."images/fonts/arial.ttf";
		 imageWaterText($backImage,$text,$color,180,24,12,$fontFile,$newName);

		$backImage=$newName;
		$text=$name;
		$color="#000000";
		$fontFile=ROOT."images/fonts/YGY20070701.ttf";	
		if(len($text)<6) $posx=178;
		else $posx=154;
		 imageWaterText($backImage,$text,$color,$posx,100,14,$fontFile,$newName);

		$text= $score;
		$color="#000000";
		$fontFile=ROOT."images/fonts/arial.ttf";
		if($score*1 > 99) 
			$left=327;
		else
			$left=332;
		 imageWaterText($backImage,$text,$color,$left,125,12,$fontFile,$newName);


		$text= $date;		
		$color="#000000";
		$fontFile=ROOT."images/fonts/arial.ttf";
		 imageWaterText($backImage,$text,$color,287,146,12,$fontFile,$newName);

		$waterPic=$avatar;

		$rel= imageWaterPic($backImage,$waterPic,47,97);
		//echo 'backImage='.$backImage."<br/>";
		//echo 'newname='.$newName;
		if(ISSAE) {
			 $s = new SaeStorage();
			 $s->write( "zhengshu2", "{$date}-{$zhengshutype}-{$uid}.jpg",@file_get_contents($newName)  );			 
			 $url=$s->getUrl( "zhengshu2" , "{$date}-{$zhengshutype}-{$uid}.jpg");
			 @unlink($newName);
			ssetcookie('daren_zhengshu_url_'.$zhengshutype,$url);
		}else{
			$url=URLBASE."data/zhengshu/{$date}-{$zhengshutype}-{$uid}.jpg";
			ssetcookie('daren_zhengshu_url_'.$zhengshutype,$newName);
		}
		return $url;
	}

	function decjifen($_uid,$jifen) {
		global $timestamp;

		$sql="select jifen from ". dbhelper::tname("daren","daren") ." where uid=".$_uid;
		$val=dbhelper::getvalue($sql);
		if($val && is_numeric($val)) {
			if(intval($val)>= $jifen) {
				$sql ="update ". dbhelper::tname("daren","daren") ." set  jifen=jifen - {$jifen},lasttime=$timestamp  where  uid=" . $_uid;
				dbhelper::execute($sql);				
				return true;
			}
		}
		return false;
	}


	function crawlBaidu($key) {
		$url = "http://www.baidu.com/s?wd=".$key;echo $url;exit;
		$html = getHttpPage($url);echo $html;
		//匹配结果列表
		preg_match_all("/(<table(?:.(?<!<table))*class=\"result\".*?<\/table>)/sm",$html,$result);
		if(count($result[0])>1){
			$msg = '';
		   foreach($result[0] as $k){
			  $msg .= $k;
		   }
		}
		echo $msg."\n\n\n\n\n\n";
		//匹配结果分页
		preg_match("/<p id=\"page\">(.*)<\/p>/sm",$html,$pages);
		$pages = preg_replace("/<span (.*)<\/span>/sm",'',$pages);
		echo $pages;
	}

?>