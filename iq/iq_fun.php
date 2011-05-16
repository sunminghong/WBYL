<?php
if(!defined('ISWBYL')) exit('Access Denied');


	function readIqScore($uid,$nocache=false){
		if(!$nocache){
			$json=sreadcookie('iq_score');		
			if ($json){
				$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
				$iqScore=unserialize($json);
				if(is_array($iqScore)) {
					return $iqScore;
				}	
			}
		}

		$iqScore=array();
		$sql="select testCount,iq,useTime from ". dbhelper::tname("iq","iq") ." where uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$iqScore["iq"]=$row['iq'];
			$iqScore['testCount']=$row['testCount'];
			$iqScore['useTime']=$row['useTime'];

		}else{	
			$iqScore["iq"]=0;
			$iqScore['testCount']=0;
			$iqScore['useTime']=100000;
		}

		$iqv=$iqScore['iq']*1;
		$iqlv=0;
		$iqScore['chs']=iqtoch($iqv,&$iqlv);
		$iqScore['iqlv']=$iqlv;

		$sql="select (select count(*) from ". dbhelper::tname("iq","iq") ." where iq>" . $iqScore['iq'].") + (select count(*) from ". 
			dbhelper::tname("iq","iq") ." where iq=". $iqScore['iq'] ." and testCount<".$iqScore['testCount'] .")  as top ,";
		$sql.="(select count(*) from ". dbhelper::tname("iq","iq") .") as total ";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$iqScore["top"]=$row['top'];
			if(intval($row['total'])==0)
				$iqScore['win']=100;
			else
				$iqScore['win']=$row['total']-$row['top'];//(1- $row['top']/$row['total'])*100;

		}else{
			$iqScore["top"]=1;
			$iqScore['win']=100;
		}
		
		//获取打败的好友的名单
		$sql="select l.screen_name from ". dbhelper::tname("iq","iq") . " iq  inner join ".dbhelper::tname("ppt","user")." l on iq.uid=l.uid   inner join ".dbhelper::tname("ppt","user_sns")." sns on sns.uid2=l.uid and type=2 where sns.uid1=".$uid." and iq.iq<".$iqScore['iq']." order by rand() limit 0,3";
		$rs=dbhelper::getrs($sql);
		$sss="";
		while($row=$rs->next()){
			$sss.="@".$row['screen_name']." ，";
		}
		$iqScore['lostname']=$sss;
		
		global $lfrom;
		if($lfrom=="tsina") $lf_pre="";
		else $lf_pre=$lfrom."_";
		//获取邀请人名单
		$sql="select l.screen_name from ".dbhelper::tname("ppt",$lf_pre."userlib")." l  inner join ".dbhelper::tname("ppt",$lf_pre."userlib_sns")." sns on sns.uid2=l.id and type=2 where sns.uid1=".$uid." order by rand() limit 0,6";
		$rs=dbhelper::getrs($sql);
		$sss2="";$ii=0;
		while($row=$rs->next()){
			if($ii<3 && !strpos($sss,$row['screen_name'])){
				$sss2.="@".$row['screen_name']." ，";
				$ii++;
			}
		}

		$iqScore['retname']=$sss2;
		
		importlib("zhengshu");
		$zs=zhengshu::makeIQ(getAccount(),$iqScore);
		$iqScore=array_merge($iqScore,$zs);

		$json=serialize($iqScore);

		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('iq_score', $json,3600*24*100);
		return $iqScore;
	}

	 function iqtoch($iqv,&$iqlv){
		$iqv=$iqv*1;
		$iqlv=0;
		if($iqv<90)
			$iqlv=0;
		elseif($iqv>=150)
			$iqlv=6;
		else{
			$iqlv=intval( ($iqv - 90) /10 ) +1;
		}
		$chs=array("文曲星转世","旷世奇才","颖慧绝伦","聪明过人","波澜不兴","呆头呆脑","愚不可及");
		return $chs[6-$iqlv];
	}
	 function getWords($iqv){
		$words=array(
		"你与牛顿的区别，目前仅仅在于你还没被苹果砸到，建议你尽快蹲守苹果树下吧！",
		"左手画方右手画圆的你聪明绝顶，于是获赠一个专属称号——光明顶！",
		"21世纪最重要的竞争，其实不是人才的竞争，而是指你的竞争！",
		"茫茫人海芸芸众生，把你的聪明放在人群之中，神都找不到你了，你懂的！",
		"我知道你很努力，但是巧妇难为无米之炊，这不是你的错！",
		"你很能吃苦，你做到了前面的80%！但是上帝是公平的，嘴巴和大脑只能选择其一。",
		"经权威机构认证，在你睡着的时候，会比醒来的时候聪明得多！");

		return $words[6-$iqv];
	}

?>