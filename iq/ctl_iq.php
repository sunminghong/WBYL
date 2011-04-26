<?php
if(!defined('ISWBYL')) exit('Access Denied');

class iq extends ctl_base
{
	function index(){ // 这里是首页
		if(!getAccount()){
			$this->set("op","login");
			$this->display("iq_index");
			return;
		}
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
		
		$this->saveIq(-1);

		$this->set("op","ican");
		$this->display("iq_index");		
	}
	
	function cacl(){
		if(!getAccount()){
			echo "-1";
			return;
		}
		$storea=rq("storea",0);
		$usetime=rq("usetime",0);
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
		
		$this->saveIq($iqvalue);
		echo $iqvalue;exit;

	}

	private function newTest(){
		
	}

	private function saveIq($iqvalue){
		global $timestamp;
		$account=getAccount();
				
		$ret=envhelper::readRet();
		$sqlu="lasttime=$timestamp,followers=".$account['followers'].",followings=".$account['followings'].",tweets=".$account['tweets'].",retuid=".$ret['retuid'];
		$lasttime=0;
		$sql="select testCount,iq,lasttime from ". dbhelper::tname("iq","iq") ." where uid=" . $account["uid"];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			if($iqvalue == -1 )
				$testCount=$row["testCount"]+1;
			else
				$testCount=$row["testCount"];

			if(intval($row["iq"]) > $iqvalue)
				$sql="update ". dbhelper::tname("iq","iq") ." set testCount=$testCount,lasttime=$timestamp where uid=" .$account["uid"];
			else
				$sql="update ". dbhelper::tname("iq","iq") ." set testCount=$testCount,iq=". $iqvalue. ",$sqlu where uid=" .$account["uid"];
			
			if($iqvalue > -1 )
				$sql .=";;;update ". dbhelper::tname("iq","log") ." set iq=". $iqvalue. ",$sqlu,uid=" . $account['uid']. " where uid=".$account['uid']." and lasttime=".$row['lasttime'];
			else
				$sql .=";;;insert into ". dbhelper::tname("iq","log") ." set iq=". $iqvalue. ",$sqlu,uid=" . $account['uid'];
			
		}
		else{
			$sql ="insert into ". dbhelper::tname("iq","iq") ." set testCount=1,iq=". $iqvalue. ",regtime=$timestamp,$sqlu,uid=" . $account['uid'];
			$sql .=";;;insert into ". dbhelper::tname("iq","log") ." set iq=". $iqvalue. ",$sqlu,uid=" . $account['uid'];
			
		}

		dbhelper::exesqls($sql);
	}
}	

?>