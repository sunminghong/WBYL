<?php
if(!defined('ISWBYL')) exit('Access Denied');

class iq extends ctl_base
{
	function index(){ // 这里是首页
		$account=getAccount();		
		if($account){
			$iqScore=$this->readIqScore($account["uid"],false);
			$this->assign("iqScore",$iqScore);
		}
		$this->set('mingrenlist',$this->mingrenlist());
		$this->set("op","login");
		$this->display("iq_index");
	}

	function ready(){ // 这里是首页
		$account=getAccount();		
		if(!$account){
			$this->set("op","login");
			$this->display("iq_index");
			return;
		}
		$iqScore=$this->readIqScore($account["uid"],false);
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
		$this->display("iq_ican");		
	}
	

	//读取统计数据
	function stats(){	
		$account=getAccount();
		if($account){
			//$this->set("op","login");
			//$this->display("iq_index");
			//return;
			$iqScore=$this->readIqScore($account["uid"],false);
			$this->assign("iqScore",$iqScore);
			$this->set('myfriendslist',$this->friendstoplist($account["uid"]));
		}

		$this->set("op","stats");
		//print_r($iqCount);
		$this->set("iqCount",$this->iqCount());
		$this->set('toplist',$this->toplist());
		$this->display("iq_index");
	}

	private function iqCount(){
		$iqCount=array();

		//读取总数据
		$sql="select (select count(*) from ". dbhelper::tname("iq","iq") .") as iq,(select count(*) from ". 
			dbhelper::tname("iq","log") .") as log,(select count(*) from ". dbhelper::tname("ppt","login") .") as co";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$iqCount["iqs"]=$row['iq'];
			$iqCount['logs']=$row['log'];
			$iqCount['totalUser']=$row['co'];

		}else{	
			$iqCount["iqs"]=0;
			$iqCount['logs']=0;
			$iqCount['totalUser']=0;
		}

		//读取排名第一
		$testlist=array();
		$sql="select l.uid,l.name,testCount,iq,useTime from ". dbhelper::tname("iq","iq") . " iq  inner join ".dbhelper::tname("ppt","login").
			" l on iq.uid=l.uid where iq.iq>0  and (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(lasttime)) <= 0 ) order by iq.iq desc,testCount ,lasttime desc limit 0,1";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$iqCount["maxIq"]=$row['iq'];
			$iqCount['maxName']=$row['name'];
			$iqCount['maxTestCount']=$row['testCount'];
			$iqCount['maxUseTime']=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
		}
		return $iqCount;
	}

	function cacl(){
		$account=getAccount();
		if(!$account){
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
		
		$score=$this->readIqScore($account["uid"],true);

		$score['nowiq']=$iqvalue;
		echo json_encode($score);
		exit;
	}
		
	public function sendStats(){
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}
		$sql="update ".dbhelper::tname("ppt","user")." set posts=posts+1 where uid='".$account['uid']."'";
		dbhelper::execute($sql);

		$iqCount=$this->iqCount();
		$msg="#看看你有多聪明#数据统计：总登录人数 ".$iqCount['totalUser']."，成功测试人数 ".$iqCount[iqs]."人， 有效测试 ".$iqCount[logs]. "次。目前@".$iqCount[maxName]. " 以 ". $iqCount[maxIq]. "分的惊人成绩 排名第一！希望聪明的你可以创造奇迹超过他！ " .URLBASE ."iq/?retuid=".$account['uid']."&retapp=iq";
		$this->getApi()->update($msg);

		echo "1";exit;
	}
	
	public function sendstatus(){
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}
		$sql="update ".dbhelper::tname("ppt","user")." set posts=posts+1 where uid='".$account['uid']."'";
		dbhelper::execute($sql);

		$score=$this->readIqScore($account['uid'],false); //得了".$score['iq']."分，
	
		if($score['lostname'])
			$msg="刚刚玩#看看你有多聪明#，我目前最高 IQ 得分".$score['iq']."，获得 #".$score['chs']."证书#，全国排名第".$score['top']."，打败了".$score['lostname']."哈哈！";
		else
			$msg="刚刚玩#看看你有多聪明#，我目前最高 IQ 得分".$score['iq']."，获得 #".$score['chs']."证书#，全国排名第".$score['top']."，打败了全国 ".$score["win"]. "% 的博友! ";

		if($score['retname'])
			$msg.= $score['retname']	. "你们也来试试！" .URLBASE ."iq/?retuid=".$account['uid']."&retapp=iq";
		else
			$msg.= "你们也来试试吧！" .URLBASE ."iq/?retuid=".$account['uid']."&retapp=iq";

		//echo ($score['zsurl']);exit;
		$this->getApi()->upload($msg,$score['zsurl']);
		echo "1";exit;
	}
		
	public function sendstatus2(){
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}

		$type=rf('type','iq');
		$lv=rf('lv',0);
		$uid=rf("uid",0);
		$msg=rf("msg","");	
		if(!$msg) {
			echo "0";exit;
		}

		//echo $msg."&retuid=".$account['uid']."&retapp=" . type;
		
		importlib("zhengshu");
		$fun='get'.$type;
		$zs=zhengshu::$fun($uid,$lv);
		if(is_array($zs)) {
//			echo $zs['zsurl'];
			print_r($this->getApi()->upload($msg,$zs['zsurl']));
		}
		
		echo "1";exit;
	}

	private function friendstoplist($uid){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,iq.testCount,iq.iq,iq.useTime,l.lfrom from ". dbhelper::tname("iq","iq") . " iq  inner join ".dbhelper::tname("ppt","login")." l on iq.uid=l.uid   inner join ".
			dbhelper::tname("ppt","user_sns")." sns on sns.uid2=l.uid and sns.type=1 where sns.uid1=".$uid." order by iq desc,testCount limit 0,10";

		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$iqlv=0;
			$row["ch"]=$this->iqtoch($row["iq"],&$iqlv);
			$row["iqlv"]=$iqlv;
			$testlist[]=$row;
		}
//		echo json_encode($testlist);
		return $testlist;	
	}

	private function mingrenlist(){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,l.followers,testCount,iq,useTime,lfrom,l.avatar from ". dbhelper::tname("iq","iq") . " iq  inner join ".
			dbhelper::tname("ppt","user")." l on iq.uid=l.uid where l.avatar<>'' order by l.followers desc  limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$iqlv=0;
			$row["ch"]=$this->iqtoch($row["iq"],&$iqlv);
			$row["iqlv"]=$iqlv;
			$testlist[]=$row;
		}
//		print_r($testlist);
		return $testlist;
	}

	private function toplist(){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,testCount,iq,useTime,lfrom from ". dbhelper::tname("iq","iq") . " iq  inner join ".
			dbhelper::tname("ppt","login")." l on iq.uid=l.uid where iq>0 AND  (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(lasttime)) <= 0 ) order by iq desc,testCount  limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$iqlv=0;
			$row["ch"]=$this->iqtoch($row["iq"],&$iqlv);
			$row["iqlv"]=$iqlv;
			$testlist[]=$row;
		}
//		echo json_encode($testlist);
		return $testlist;
	}

	private function toplist7(){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,testCount,iq,useTime,lfrom from ". dbhelper::tname("iq","iq") . " iq  inner join ".
			dbhelper::tname("ppt","login")." l on iq.uid=l.uid where iq>0 and (TO_DAYS(NOW()) - TO_DAYS(FROM_UNIXTIME(lasttime)) <= 7 ) order by iq desc,testCount  limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$iqlv=0;
			$row["ch"]=$this->iqtoch($row["iq"],&$iqlv);
			$row["iqlv"]=$iqlv;
			$testlist[]=$row;
		}
//		echo json_encode($testlist);
		return $testlist;
	}

	public function testlist(){
		$top=rq("mo",0);
		$last=rq("last",0);
		if($last==0)
			$top=25;
		else
			$top=5;

		$testlist=array();
		$sql="select iq.id,l.uid,l.name,iq,lasttime,l.lfrom from ". dbhelper::tname("iq","log") . " iq  inner join ".dbhelper::tname("ppt","login")." l on iq.uid=l.uid  where iq.iq>0 and iq.lasttime>{$last} order by iq.lasttime desc limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row['testtime']= date("m-d H:i:s",$row['lasttime']);
			$iqlv=0;
			$row["ch"]=$this->iqtoch($row["iq"],&$iqlv);
			$row["iqlv"]=$iqlv;
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

		$iqv=$iqScore['iq']*1;
		$iqlv=0;
		$iqScore['chs']=$this->iqtoch($iqv,&$iqlv);
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
				$iqScore['win']=(1- $row['top']/$row['total'])*100;

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

		$iqScore['retname']=$sss2;
		
		importlib("zhengshu");
		$zs=zhengshu::makeIQ(getAccount(),$iqScore);
		$iqScore=array_merge($iqScore,$zs);

		$json=serialize($iqScore);

		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('iq_score', $json,3600*24*100);
		return $iqScore;
	}

	private function iqtoch($iqv,&$iqlv){
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
	private function getWord($iqv){
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

	private function saveIq($iqvalue,$useTime){
		global $timestamp;
		$account=getAccount();
				
		$ret=envhelper::readRet();
		$sqlu="lasttime=$timestamp,followers=".$account['followers'].",followings=".$account['followings'].",tweets=".$account['tweets'].",retuid='".$ret['retuid'] ."'";
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
				$sql .=";;;update ". dbhelper::tname("iq","log") ." set iq=". $iqvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid']. " where uid=".$account['uid']." and lasttime=".$row['lasttime'];
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