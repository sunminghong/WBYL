<?php 
function getshowpic() {
		$last="";
		$lastpicid=0;
		$lastbyratecount=0;

		$id=intval(rq("rateid",0));
		$sex=intval(rq("sex",0));
		$score=intval(rq("score",0));
		$ishistory=intval(rq("ishistory",0));

		if($sex>2)$sex=0;

		$isfirst=rq('f','');
		if(!$id && !$isfirst) {
			$id=getTuijian();
		}

		$curr=$next=$up=$top=0;
		
		$swhere="where  p.bury<4 and p.lasttime<>0 ";
		if($sex!=0) $swhere=" and l.sex=$sex ";
		
		$picarr=array();
		if ($id && ($ishistory || $isfirst)) {
			if($sex!=0) $sex=" and l.sex=$sex ";
			else $sex='';
			$sql="select p.*,l.sex,l.name,l.lfrom,l.lfromuid,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid  where p.id=$id ".$sex; //echo "/*1=$sql*/";
			$rs=dbhelper::getrs($sql);
			if ($row=$rs->next()) {
				$lastpicid=$row["id"];
				$lastbyratecount=$row['byratecount'];
				
				if($ishistory) {
					return json_encode($row);
				}
				$row['avg']= $row['byratecount']?round($row['score'] / $row['byratecount'],2): 0;
				$picarr[]=$row;
			}else {
				if($ishistory) {
					return 0;
				}

			}

		}

		if($lastpicid==0) {
			$last=sreadcookie('ilike_lastpic');
			if($last) {
				$last=explode(':',$last);
				$lastpicid=$last[0];
				$lastbyratecount=$last[1];
			}
		} 
		else
			$swhere .=" and id<>" . $lastpicid;

		if (count($picarr)==0) 
			$top=10;
		else 
			$top=9;

		$sql="select p.*,l.sex,l.name,l.lfrom,l.lfromuid,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid $swhere and p.byratecount>$lastbyratecount  order by p.byratecount,p.id desc limit 0,$top"; 		//echo "/*1=$sql*/";
		$rs=dbhelper::getrs($sql); //echo "/*2=$sql*/";
		$row=$rs->next();
		$co=0;$looco=0;
		while ($looco<1 && $co<2) {
			if(!$row) {
				$sql="select p.*,l.sex,l.name,l.lfrom,l.lfromuid,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid $swhere and p.byratecount>=0  order by p.byratecount limit 0,".($top-$co);  //echo "\n/*2=$sql*/\n";
				$rs=dbhelper::getrs($sql); //echo "/*3=$sql*/";
				$row=$rs->next();
				$looco++;
			}
			
			$row['avg']= $row['byratecount']?round($row['score'] / $row['byratecount'],2): 0;
			$picarr[]=$row;$co++;

			while($row=$rs->next()) {				
				$row['avg']= $row['byratecount']?round($row['score'] / $row['byratecount'],2): 0;
				$picarr[]=$row;
				$co++;
			}
			$row=false;
		}
		//echo 'last=';echo($lastpicid.":".$lastbyratecount);
		$len=count($picarr);
		$lastpicid=$picarr[$len-1]["id"];
		$lastbyratecount=$picarr[$len-1]['byratecount'];

		ssetcookie("ilike_lastpic",$lastpicid.":".$lastbyratecount);

		//echo 'last=';echo($lastpicid.":".$lastbyratecount);
		//echo 'picarr=';print_r($picarr);

		return json_encode($picarr);
	}


	function tuijian() {
		global $timestamp;
		$picid=rq('rateid',0);
		if(!$picid) {
			echo "-2";
			return;
		}
		//推荐三天
		$lefttime=$timestamp+3600*24*1;
		$sql="replace into set picid=$picid,$regtime=$timestamp,$lefttime=$lefttime";
		dbhelper::execute($sql);

		$cache=new Cache();
		$cache->set("ilike_idarray",false);

		echo '1';
	}

	function getTuijian() {
		$cache=new Cache();

		$idarr=$cache->get("ilike_idarray");
		if(!is_array($idarr)) {
			$sql="select picid from ".dbhelper::tname("ilike","tujian")." where lefttime>lasttime order by lefttime limit 0,10";
			$rs=dbhelper::getrs($sql);
			$idarr=array();
			while($row=$rs->next()) {
				$idarr[]=$row['picid'];
			}
			if(count($idarr)==0) {
				return 0;
			}
			else {
				$cache->set("ilike_idarray",$idarr);
			}
		}

		$randid= $timestamp % count($idarr);
		return $idarr[$randid];
	}



	?>