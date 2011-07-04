<?php
if(!defined('ISWBYL')) exit('Access Denied');
include_once("mq_fun.php");
class mq extends ctl_base
{
	function index(){ // 这里是首页
			//$this->ready();exit;

		$account=getAccount();		
		if($account){
			$mqScore=readMqScore($account["uid"],false);
			$this->assign("mqScore",$mqScore);
		}
		$this->set('mingrenlist',$this->mingrenlist());
		$this->set("op","login");
		$this->display("mq_index");
	}

	function ready(){ // 这里是首页		
		//header("Location: ?app=mq&act=mqtest&op=ready");
		//exit;	
		$account=getAccount();		
		if($account){
			$mqScore=readMqScore($account["uid"],false);
			$this->assign("mqScore",$mqScore);
		}

		$this->set("op","ready");
		$this->display("mq_index");
	}

	function ican(){	
		//header("Location: ?app=mq&act=mqtest&op=ican");
		//exit;
		global $timestamp;
		$account=getAccount();
		if(!$account){
			$this->set("op","login");
			$this->display("q_index");
			return;
		}
		
		ssetcookie('mq_usetime',"");
		ssetcookie("mq_mqvalue","");
	/*	if(!strpos($_SERVER["HTTP_REFERER"],"app=mq"))
		{
			$this->set("op","ready");
			$this->display("q_index");	
			return;
		}*/
		$this->saveMq(-1,0);

		$this->set("op","ican");
		$this->display("mq_ican");
	}
	

	//读取统计数据
	function stats(){	
		$account=getAccount();
		if($account){
			//$this->set("op","login");
			//$this->display("q_index");
			//return;
			$mqScore=readMqScore($account["uid"],false);
			$this->assign("mqScore",$mqScore);
			$this->set('myfriendslist',$this->friendstoplist($account["uid"]));
		}

		$this->set("op","stats");
		//print_r($mqCount);
		$this->set("mqCount",$this->mqCount());
		$this->set('toplist',$this->toplist());
		$this->display("mq_index");
	}

	private function mqCount(){
		$mqCount=array();

		//读取总数据
		$sql="select (select count(*) from ". dbhelper::tname("mq","mq") .") as mq,(select count(*) from ". 
			dbhelper::tname("mq","log") .") as log,(select count(*) from ". dbhelper::tname("mq","mq") .") as co";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$mqCount["mqs"]=$row['mq'];
			$mqCount['logs']=$row['log'];
			$mqCount['totalUser']=$row['co'];

		}else{	
			$mqCount["mqs"]=0;
			$mqCount['logs']=0;
			$mqCount['totalUser']=0;
		}

		//读取排名第一
		$testlist=array();
		$sql="select l.uid,l.name,testCount,mq,useTime from ". dbhelper::tname("mq","mq") . " mq  inner join ".dbhelper::tname("ppt","login").
			" l on mq.uid=l.uid where mq.mq>0  and lasttime > UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) )  order by mq.mq desc,testCount ,lasttime desc limit 0,1";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$mqCount["maxMq"]=$row['mq'];
			$mqCount['maxName']=$row['name'];
			$mqCount['maxTestCount']=$row['testCount'];
			$mqCount['maxUseTime']=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
		}
		return $mqCount;
	}

	function cacl(){
		$account=$this->checkLogin();
		$mqvalue=rq("mqvalue",-1);
		$useTime=rq("usetime",0);
		if($mqvalue==-1) {
			if(!rf("usetime",false)){
				header("Location: ?app=mq");
				exit;
			}
			$useTime=rf("usetime",0);
			$mqvalue=0;     
			$stand=array(5,3,1,0);
			$mqvalue += $stand[intval(rf("s_1",4))-1];
			$mqvalue += $stand[intval(rf("s_2",4))-1];
			$mqvalue += $stand[intval(rf("s_3",4))-1];
			$mqvalue += $stand[intval(rf("s_4",4))-1];
			$mqvalue += $stand[intval(rf("s_5",4))-1];
			$mqvalue += $stand[intval(rf("s_6",4))-1];
			$mqvalue += $stand[intval(rf("s_7",4))-1];
			$mqvalue += $stand[intval(rf("s_8",4))-1];
			$mqvalue += $stand[intval(rf("s_9",4))-1];
			$mqvalue += $stand[intval(rf("s_10",4))-1];
		
		 ////echo '<!--'.$mqvalue.'--'.$useTime.'-->';
			$this->saveMq($mqvalue,$useTime);
			header("Location: ?app=mq&op=cacl&mqvalue=$mqvalue&usetime=$useTime");
			//echo '<script type="text/javascript">location.href="?app=mq&op=cacl&mqvalue='.$mqvalue.'&usetime='.$useTime.'";</script>';
		}

		$score=readMqScore($account["uid"],true);
		
		$score["words"] = getWords($score['mqlv']);
		$score["newusetime"] = strftime('%M:%S',$useTime);
		$score['nowmq']=$mqvalue;
		//echo json_encode($score);
		//exit;
		if($account){
			$mqScore=readMqScore($account["uid"],false);
			$this->assign("mqScore",$mqScore);
		}
		$this->set("score",$score);
		$this->set("op","showscore");
		$this->display("mq_ican");
	}
		
	public function sendStats(){
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}
		$sql="update ".dbhelper::tname("ppt","user")." set posts=posts+1 where uid='".$account['uid']."'";
		dbhelper::execute($sql);

		$mqCount=$this->mqCount();
		$msg="#看看你有多聪明#MQ测试数据统计：总登录人数 ".$mqCount['totalUser']."，成功测试人数 ".$mqCount[mqs]."人， 有效测试 ".$mqCount[logs]. "次。目前@".$mqCount[maxName]. " 以 ". $mqCount[maxMq]. "分的惊人成绩 排名第一！希望聪明的你可以创造奇迹超过他！ ";
	
		$msg .= URLBASE ."mq/?lfrom=".$account["lfrom"]."&retuid=".$account['uid']."&retapp=mq";
		//echo $msg;exit;
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

		$score=readMqScore($account['uid'],false); //得了".$score['mq']."分，

		if($score['lostname'])
			$msg="我刚刚玩#看看你有多聪明#，MQ 得分".$score['mq']."，获得 #".$score['chs']."证书#，全国排名第".$score['top']."，打败了".$score['lostname']."哈哈哈哈！";
		else
			$msg="哈哈哈，刚刚玩#看看你有多聪明#，我目前最高 MQ 得分".$score['mq']."，获得 #".$score['chs']."证书#，全国排名第".$score['top']."，打败了全国 ".$score["win"]. " 的博友! ";

		if($score['retname'])
			$msg.= $score['retname']	. "你们也来试试！";
		else
			$msg.= "";

		$msg .= URLBASE ."mq/?lfrom=".$account["lfrom"]."&retuid=".$account['uid']."&retapp=mq";
		///echo $score['zsurl'].$msg; exit;
		$this->getApi()->upload($msg,$score['zsurl']);
		echo "1";exit;
	}
		
	public function sendstatus2(){
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}

		$type=rf('type','mq');
		$lv=rf('lv',0);
		$uid=rf("uid",0);
		$msg=rf("msg","");	
		if(!$msg) {
			echo "0";exit;
		}

		//echo $msg."&retuid=".$account['uid']."&retapp=" . type; exit;
		
		importlib("zhengshu");
		$fun='get'.$type;
		$zs=zhengshu::$fun($uid,$lv);
		if(is_array($zs)) {
//			echo $zs['zsurl'];
			$this->getApi()->upload($msg,$zs['zsurl']);
		}
		
		echo "1";exit;
	}

	private function friendstoplist($uid){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,mq.testCount,mq.mq,mq.useTime,l.lfrom from ". dbhelper::tname("mq","mq") . " mq  inner join ".dbhelper::tname("ppt","login")." l on mq.uid=l.uid   inner join ".
			dbhelper::tname("ppt","user_sns")." sns on sns.uid2=l.uid and sns.type=1 where sns.uid1=".$uid." order by mq desc,testCount limit 0,10";
echo '<!--'.$sql.'-->';
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$mqlv=0;
			$row["ch"]=mqtoch($row["mq"],&$mqlv);
			$row["mqlv"]=$mqlv;
			$testlist[]=$row;
		}
//		echo json_encode($testlist);
		return $testlist;	
	}

	private function mingrenlist(){
		global $lfrom;
		$ocache=new Cache();
		$minLast=$ocache->get('mq_minlast'.$lfrom);
		if(getTimestamp() - $minLast<3600) {
			$testlist=$ocache->get('mq_mingrenlist'.$lfrom);			
			if( is_array($testlist)) 
				return $testlist;
		}

		$top=10;
		$testlist=array();
		if($lfrom) $ll="lfrom='$lfrom' and   ";
		$sql="select l.uid,l.name,l.followers,testCount,mq,useTime,lfrom,l.avatar,l.verified from (select * from ".
		dbhelper::tname("ppt","user")." where $ll followers>20000 and avatar<>'' order by followers desc limit 0,100) l,". dbhelper::tname("mq","mq") . " mq where l.uid=mq.uid limit 0,10";
		//$sql="select l.uid,l.name,l.followers,testCount,mq,useTime,lfrom,l.avatar,l.verified from ". dbhelper::tname("mq","mq") . " mq  inner join ".
		//	dbhelper::tname("ppt","user")." l on mq.uid=l.uid where l.avatar<>'' order by l.followers desc  limit 0,$top";

		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$mqlv=0;
			$row["ch"]=mqtoch($row["mq"],&$mqlv);
			$row["mqlv"]=$mqlv;
			if($row['lfrom']=='tqq' && right($row['avatar'],3)!="/50") $row['avatar'] .= "/50";

			$testlist[]=$row;
		}
//		print_r($testlist);
		$ocache->set('mq_minlast'.$lfrom,gettimestamp());	
		$ocache->set('mq_mingrenlist'.$lfrom,$testlist);
		return $testlist;
	}

	private function toplist(){
		global $lfrom;
		if($lfrom) $ll="l.lfrom='$lfrom' and ";
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,testCount,mq,useTime,lfrom from ". dbhelper::tname("mq","mq") . " mq  inner join ".
			dbhelper::tname("ppt","login")." l on mq.uid=l.uid where $ll mq>0 AND  lasttime > UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ))  order by mq desc,testCount  limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$mqlv=0;
			$row["ch"]=mqtoch($row["mq"],&$mqlv);
			$row["mqlv"]=$mqlv;
			$testlist[]=$row;
		}
//		echo json_encode($testlist);
		return $testlist;
	}

	private function toplist7(){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,testCount,mq,useTime,lfrom from ". dbhelper::tname("mq","mq") . " mq  inner join ".
			dbhelper::tname("ppt","login")." l on mq.uid=l.uid where mq>0 and lasttime > UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) order by mq desc,testCount  limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$mqlv=0;
			$row["ch"]=mqtoch($row["mq"],&$mqlv);
			$row["mqlv"]=$mqlv;
			$testlist[]=$row;
		}
//		echo json_encode($testlist);
		return $testlist;
	}

	public function testlist(){
		global $lfrom;
		if($lfrom) $ll="l.lfrom='$lfrom' and ";
		$top=rq("mo",0);
		$last=rq("last",0);
		if($last==0)
			$top=25;
		else
			$top=5;

		$testlist=array();
		$sql="select mq.id,l.uid,l.name,mq,lasttime,l.lfrom from ". dbhelper::tname("mq","log") . " mq  inner join ".dbhelper::tname("ppt","login")." l on mq.uid=l.uid  where $ll mq.mq>0 and mq.lasttime>{$last} order by mq.lasttime desc limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row['testtime']= date("m-d H:i:s",$row['lasttime']);
			$mqlv=0;
			$row["ch"]=mqtoch($row["mq"],&$mqlv);
			$row["mqlv"]=$mqlv;
			$testlist[]=$row;			
		}
		echo json_encode($testlist);
	}


	private function saveMq($mqvalue,$useTime){
		global $timestamp;
		$account=getAccount();
				
		$ret=envhelper::readRet();
		$sqlu="lasttime=$timestamp,followers=".$account['followers'].",followings=".$account['followings'].",tweets=".$account['tweets'].",retuid='".$ret['retuid'] ."'";
		$lasttime=0;
		$sql="select testCount,mq,lasttime,useTime from ". dbhelper::tname("mq","mq") ." where uid=" . $account["uid"];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			if($mqvalue == -1 )
				$testCount=$row["testCount"]+1;
			else
				$testCount=$row["testCount"];

			if(intval($row["mq"]) > $mqvalue || (intval($row["mq"]) == $mqvalue && intval($row["useTime"]) < $useTime ))
				$sql="update ". dbhelper::tname("mq","mq") ." set testCount=$testCount,lasttime=$timestamp where uid=" .$account["uid"];
			else
				$sql="update ". dbhelper::tname("mq","mq") ." set testCount=$testCount,mq=". $mqvalue. ",useTime=".$useTime.",$sqlu where uid=" .$account["uid"];
			
			if($mqvalue > -1 )
				$sql .=";;;update ". dbhelper::tname("mq","log") ." set mq=". $mqvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid']. " where uid=".$account['uid']." and lasttime=".$row['lasttime'];
			else
				$sql .=";;;insert into ". dbhelper::tname("mq","log") ." set mq=". $mqvalue. ",$sqlu,uid=" . $account['uid'];
			
		}
		else{
			$sql ="insert into ". dbhelper::tname("mq","mq") ." set testCount=1,mq=". $mqvalue. ",useTime=".$useTime.",regtime=$timestamp,$sqlu,uid=" . $account['uid'];
			$sql .=";;;insert into ". dbhelper::tname("mq","log") ." set mq=". $mqvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid'];			
		}
//echo $sql;
		dbhelper::exesqls($sql);		
	}

}	


?>