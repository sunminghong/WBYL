<?php
if(!defined('ISWBYL')) exit('Access Denied');

	function readMqScore($uid,$nocache=false){
		if(1==0 && !$nocache){
			$json=sreadcookie('mq_score');		
			if ($json){
				$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
				$mqScore=unserialize($json);
				if(is_array($mqScore)) {
					return $mqScore;
				}	
			}
		}

		$mqScore=array();
		$sql="select testCount,mq,useTime from ". dbhelper::tname("mq","mq") ." where uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$mqScore["mq"]=$row['mq'];
			$mqScore['testCount']=$row['testCount'];
			$mqScore['useTime']=$row['useTime'];

		}else{	
			$mqScore["mq"]=0;
			$mqScore['testCount']=0;
			$mqScore['useTime']=100000;
		}

		$mqv=$mqScore['mq']*1;
		$mqlv=0;
		$mqScore['chs']=mqtoch($mqv,&$mqlv);
		$mqScore['mqlv']=$mqlv;

		$top=1;
		$sql="select count(*) as top from ". dbhelper::tname("mq","mq") ." where mq>" . $mqScore['mq']."";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		$top=$row['top'];

		$sql="select count(*) as top from ". dbhelper::tname("mq","mq") ." where mq=". $mqScore['mq'] ." and testCount<".$mqScore['testCount'] ."";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		$top += $row['top'];
//echo $top;
		//$sql="select (select count(*) from ". dbhelper::tname("mq","mq") ." where mq>" . $mqScore['mq'].") + (select count(*) from ". 
		//	dbhelper::tname("mq","mq") ." where mq=". $mqScore['mq'] ." and testCount<".$mqScore['testCount'] .")  as top ,";
		//$sql.="(select count(*) from ". dbhelper::tname("mq","mq") .") as total ";
		$sql="select count(*) as total from ". dbhelper::tname("mq","mq") ." ";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$mqScore["top"]=$top+1;
			if(intval($row['total'])==0)
				$mqScore['win']='所有';
			else
				$mqScore['win']=$row['total'] - $top; //(1- $row['top']/$row['total'])*100;

		}else{
			$mqScore["top"]=1;
			$mqScore['win']='所有';
		}
		
		if(SENTATNAME) {
			//获取打败的好友的名单
			$sql="select l.name as screen_name,l.lfrom,l.lfromuid from ". dbhelper::tname("mq","mq") . " mq  inner join ".dbhelper::tname("ppt","login")." l on mq.uid=l.uid   inner join ".dbhelper::tname("ppt","user_sns")." sns on sns.uid2=l.uid and type=2 where sns.uid1=".$uid." and mq.mq<".$mqScore['mq']." order by rand() limit 0,3";
			$rs=dbhelper::getrs($sql);
			$sss="";
			while($row=$rs->next()){
				if($row['lfrom']=='tqq')
					$sss.="@".$row['lfromuid']." ，";
				else
					$sss.="@".$row['screen_name']." ，";
			}
			$mqScore['lostname']=$sss;
		

			//获取邀请人名单

			$api=getApi();
			importlib("ppt_class");
			$ppt=new ppt();
			$sss2=$ppt->getMeetSNS($api,2,$uid,3,$mqScore['lostname']);
			$mqScore['retname']=$sss2;
		}

		importlib("zhengshu");
		$zs=zhengshu::makeMQ(getAccount(),$mqScore);
		$mqScore=array_merge($mqScore,$zs);

		$json=serialize($mqScore);

		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('mq_score', $json,3600*24*100);
		return $mqScore;
	}

	 function mqtoch($mqv,&$mqlv){
		$mqv=$mqv*1;
		$mqlv=0;
		if($mqv<19)
			$mqlv=0;
		elseif($mqv>=38)
			$mqlv=2;
		else{
			$mqlv=1;
		}
		$chs=array("德高望重","独善其身","狐鼠之徒");
		return $chs[2-$mqlv];
	}
	 function getWords($mqv){
		$words=array(
			"你的MQ优秀，只需在保持现有道德品质的基础上，多交一些高德商的朋友，以求与他们互补!	",
			"你的MQ一般，需要有针对性地提高自己的德商，使你的品格更进一步.",
			"你的MQ已经严重影响到你的工作和生活，建议立即去看心理医生，或者考虑对现状来一次根本性的改变。 "
		);

		return $words[6-$mqv];
	}

?>