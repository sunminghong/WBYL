<?php
if(!defined('ISWBYL')) exit('Access Denied');

	function readEqScore($uid,$nocache=false){
		if(1==0 && !$nocache){
			$json=sreadcookie('eq_score');		
			if ($json){
				$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
				$eqScore=unserialize($json);
				if(is_array($eqScore)) {
					return $eqScore;
				}	
			}
		}

		$eqScore=array();
		$sql="select testCount,eq,useTime from ". dbhelper::tname("eq","eq") ." where uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$eqScore["eq"]=$row['eq'];
			$eqScore['testCount']=$row['testCount'];
			$eqScore['useTime']=$row['useTime'];

		}else{	
			$eqScore["eq"]=0;
			$eqScore['testCount']=0;
			$eqScore['useTime']=100000;
		}

		$eqv=$eqScore['eq']*1;
		$eqlv=0;
		$eqScore['chs']=eqtoch($eqv,&$eqlv);
		$eqScore['eqlv']=$eqlv;

		$top=1;
		$sql="select count(*) as top from ". dbhelper::tname("eq","eq") ." where eq>" . $eqScore['eq']."";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		$top=$row['top'];

		$sql="select count(*) as top from ". dbhelper::tname("eq","eq") ." where eq=". $eqScore['eq'] ." and testCount<".$eqScore['testCount'] ."";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		$top += $row['top'];
//echo $top;
		//$sql="select (select count(*) from ". dbhelper::tname("eq","eq") ." where eq>" . $eqScore['eq'].") + (select count(*) from ". 
		//	dbhelper::tname("eq","eq") ." where eq=". $eqScore['eq'] ." and testCount<".$eqScore['testCount'] .")  as top ,";
		//$sql.="(select count(*) from ". dbhelper::tname("eq","eq") .") as total ";
		$sql="select count(*) as total from ". dbhelper::tname("eq","eq") ." ";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$eqScore["top"]=$top;
			if(intval($row['total'])==0)
				$eqScore['win']=100;
			else
				$eqScore['win']=$row['total'] - $top; //(1- $row['top']/$row['total'])*100;

		}else{
			$eqScore["top"]=1;
			$eqScore['win']=0;
		}
		
		//获取打败的好友的名单
		$sql="select l.name as screen_name,l.lfrom,l.lfromuid from ". dbhelper::tname("eq","eq") . " eq  inner join ".dbhelper::tname("ppt","login")." l on eq.uid=l.uid   inner join ".dbhelper::tname("ppt","user_sns")." sns on sns.uid2=l.uid and type=2 where sns.uid1=".$uid." and eq.eq<".$eqScore['eq']." order by rand() limit 0,3";
		$rs=dbhelper::getrs($sql);
		$sss="";
		while($row=$rs->next()){
			if($row['lfrom']=='tqq')
				$sss.="@".$row['lfromuid']." ，";
			else
				$sss.="@".$row['screen_name']." ，";
		}
		$eqScore['lostname']=$sss;
		

		//获取邀请人名单

		$api=getApi();
		importlib("ppt_class");
		$ppt=new ppt();
		$sss2=$ppt->getMeetSNS($api,2,$uid,3,$eqScore['lostname']);
		$eqScore['retname']=$sss2;

		importlib("zhengshu");
		$zs=zhengshu::makeEQ(getAccount(),$eqScore);
		$eqScore=array_merge($eqScore,$zs);

		$json=serialize($eqScore);

		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('eq_score', $json,3600*24*100);
		return $eqScore;
	}

	 function eqtoch($eqv,&$eqlv){
		$eqv=$eqv*1;
		$eqlv=0;
		if($eqv<90)
			$eqlv=0;
		elseif($eqv>=150)
			$eqlv=6;
		else{
			$eqlv=intval( ($eqv - 90) /10 ) +1;
		}
		$chs=array("上善若水","左右逢源","洞察秋毫","大道中庸","曲高和寡","左支右绌","笨鸟先飞");
		return $chs[6-$eqlv];
	}
	 function getWords($eqv){
		$words=array(
		"上善若水，水善利万物而不争。处深潭惊涛之中，表面清澈而平静，处事为人之道也！",
			"资之深，则取之左右逢其原。赏识广博，应付裕如，做事可得心应手，无往而不利！",
			"明，足以察秋毫之末。洞察事理明辨是非，有时让自己笨一点也许会更好！",
			"不偏不倚，犹言中材，中人也！生活是一种态度，有点颜色岂不是更好？",
			"其曲弥高，其和弥寡。如果改变不了环境，就努力去适应周围的环境！",
			"始则移东补西，继则左支右绌。认识情绪，并学会怎样合适地表达自己的情绪！",
			"纵那灵禽在后，只需坌鸟先飞！天道酬勤，相信长风破浪会有时，直挂云帆济沧海！	"
		);

		return $words[6-$eqv];
	}

?>