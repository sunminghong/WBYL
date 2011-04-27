<?php
if(!defined('ISWBYL')) exit('Access Denied');

class iq extends ctl_base
{
	function index(){ // 这里是首页
		$account=getAccount();
		if(!$account){
			$this->set("op","login");
			$this->display("iq_index");
			return;
		}
		$iqScore=$this->readIqScore($account["uid"],true);
		$this->assign("iqScore",$iqScore);
		$this->set("op","ready");
		$this->display("iq_index");
	}

	function ican(){
		global $timestamp;
		$account=getAccount();
		if(!$account){
			$this->set("op","login");
			$this->display("iq_index");
			return;
		}
	/*	if(!strpos($_SERVER["HTTP_REFERER"],"app=iq"))
		{
			$this->set("op","ready");
			$this->display("iq_index");	
			return;
		}*/
		$this->saveIq(-1,0);

		$this->set("op","ican");
		$this->display("iq_index");		
	}
	
	function cacl(){
		if(!getAccount()){
			echo "-1";
			return;
		}
		$storea=rq("storea",0);
		$useTime=rq("usetime",0);
		$iqvalue=0;     
		if($storea=="1"){$iqvalue=20;}     
		else if($storea=="2"){$iqvalue=30;}     
		else if($storea=="3"){$iqvalue=40;}     
		else if($storea=="4"){$iqvalue=50;}     
		else if($storea=="5"){$iqvalue=60;}     
		else if($storea=="6"){$iqvalue=65;}     
		else if($storea=="7"){$iqvalue=65;}     
		else if($storea=="8"){$iqvalue=67;}     
		else if($storea=="9"){$iqvalue=67;}     
		else if($storea=="10"){$iqvalue=68;}     
		else if($storea=="11"){$iqvalue=70;}     
		else if($storea=="12"){$iqvalue=72;}     
		else if($storea=="13"){$iqvalue=74;}     
		else if($storea=="14"){$iqvalue=75;}     
		else if($storea=="15"){$iqvalue=80;}     
		else if($storea=="16"){$iqvalue=83;}     
		else if($storea=="17"){$iqvalue=85;}     
		else if($storea=="18"){$iqvalue=88;}     
		else if($storea=="19"){$iqvalue=89;}     
		else if($storea=="20"){$iqvalue=90;}     
		else if($storea=="21"){$iqvalue=93;}     
		else if($storea=="22"){$iqvalue=95;}     
		else if($storea=="23"){$iqvalue=100;}     
		else if($storea=="24"){$iqvalue=105;}     
		else if($storea=="25"){$iqvalue=108;}     
		else if($storea=="26"){$iqvalue=110;}     
		else if($storea=="27"){$iqvalue=111;}     
		else if($storea=="28"){$iqvalue=114;}     
		else if($storea=="29"){$iqvalue=115;}     
		else if($storea=="30"){$iqvalue=120;}     
		else if($storea=="31"){$iqvalue=122;}     
		else if($storea=="32"){$iqvalue=125;}     
		else if($storea=="33"){$iqvalue=127;}     
		else if($storea=="34"){$iqvalue=128;}     
		else if($storea=="35"){$iqvalue=130;}     
		else if($storea=="36"){$iqvalue=135;}     
		else if($storea=="37"){$iqvalue=140;}     
		else if($storea=="38"){$iqvalue=143;}     
		else if($storea=="39"){$iqvalue=145;}     
		else if($storea=="40"){$iqvalue=150;}     
		
		$this->saveIq($iqvalue,$useTime);
		
		$score=array(
				"iq"=>$iqvalue,
				"top"=> $usetime,
				"lastnum" =>$lastnum
		);

		echo $iqvalue;exit;
	}

	public function sendstatus(){
		$account=getAccount();
		
		$score=$this->readIqScore($account['uid'],true);
		$msg="刚刚上进行了#微博IQ测试#，我的IQ是".$score['iq']."分，排行第".$score['top'].",打败了全国 ".$score["win"]. "% 的博友! 早点去可以排个好名次吧！" .URLBASE ."iq/?retuid=".$account['uid']."&retapp=iq";
		
		$api="openapi_".$account['lfrom'];
			importlib($api);
			$api=new $api(); 

			$client=$api->getClient($account['kuid']);	

			$ms  = $client->update( $msg );

		echo "1";exit;
	}
	
	public function toplist(){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,testCount,iq,useTime from ". dbhelper::tname("iq","iq") . " iq  inner join ".dbhelper::tname("ppt","login")." l on iq.uid=l.uid where iq>0 order by iq desc,useTime,testCount desc limit 0,$top";
		$rs=dbhelper::getrs($sql);
		while($row=$rs->next()){
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$testlist[]=$row;
		}
		echo json_encode($testlist);
	}
	public function testlist(){
		$top=rq("mo",0);
		if($top==0) 
			$top=10;
		else 
			$top=30;

		$testlist=array();
		$sql="select l.uid,l.name,iq,lasttime from ". dbhelper::tname("iq","log") . " iq  inner join ".dbhelper::tname("ppt","login")." l on iq.uid=l.uid  order by id desc limit 0,$top";
		$rs=dbhelper::getrs($sql);
		while($row=$rs->next()){
			$row['testtime']= date("m-d H:i:s",$row['lasttime']);
			$testlist[]=$row;			
		}
		echo json_encode($testlist);
	}

	private function readIqScore($uid,$nocache=false){
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
		$sql="select (select count(*) from ". dbhelper::tname("iq","iq") ." where iq>" . $iqScore['iq']." or ( iq=". $iqScore['iq'] ." and useTime<".$iqScore['useTime'] ."))+1 as top ,";
		$sql.="(select count(*) from ". dbhelper::tname("iq","iq") .")+1 as total ";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$iqScore["top"]=$row['top'];
			$iqScore['win']=(1- $row['top']/$row['total'])*100;
		}else{
			$iqScore["top"]=1;
			$iqScore['win']=100;
		}
		$json=serialize($iqScore);

		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('iq_score', $json,3600*24*100);
		return $iqScore;
	}
	private function saveIq($iqvalue,$useTime){
		global $timestamp;
		$account=getAccount();
				
		$ret=envhelper::readRet();
		$sqlu="lasttime=$timestamp,followers=".$account['followers'].",followings=".$account['followings'].",tweets=".$account['tweets'].",retuid=".$ret['retuid'];
		$lasttime=0;
		$sql="select testCount,iq,lasttime,useTime from ". dbhelper::tname("iq","iq") ." where uid=" . $account["uid"];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			if($iqvalue == -1 )
				$testCount=$row["testCount"]+1;
			else
				$testCount=$row["testCount"];

			if(intval($row["iq"]) > $iqvalue || (intval($row["iq"]) == $iqvalue && intval($row["useTime"]) < $useTime ))
				$sql="update ". dbhelper::tname("iq","iq") ." set testCount=$testCount,lasttime=$timestamp where uid=" .$account["uid"];
			else
				$sql="update ". dbhelper::tname("iq","iq") ." set testCount=$testCount,iq=". $iqvalue. ",useTime=".$useTime.",$sqlu where uid=" .$account["uid"];
			
			if($iqvalue > -1 )
				$sql .=";;;update ". dbhelper::tname("iq","log") ." set iq=". $iqvalue. ",$sqlu,uid=" . $account['uid']. " where uid=".$account['uid']." and lasttime=".$row['lasttime'];
			else
				$sql .=";;;insert into ". dbhelper::tname("iq","log") ." set iq=". $iqvalue. ",$sqlu,uid=" . $account['uid'];
			
		}
		else{
			$sql ="insert into ". dbhelper::tname("iq","iq") ." set testCount=1,iq=". $iqvalue. ",useTime=".$useTime.",regtime=$timestamp,$sqlu,uid=" . $account['uid'];
			$sql .=";;;insert into ". dbhelper::tname("iq","log") ." set iq=". $iqvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid'];			
		}

		dbhelper::exesqls($sql);		
	}
}	

?>