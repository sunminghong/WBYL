<?php
if(!defined('ISWBYL')) exit('Access Denied');
include_once("eq_fun.php");
class eq extends ctl_base
{
	function index(){ // 这里是首页
			//$this->ready();exit;

		$account=getAccount();		
		if($account){
			$eqScore=readEqScore($account["uid"],false);
			$this->assign("eqScore",$eqScore);
		}
		$this->set('mingrenlist',$this->mingrenlist());
		$this->set("op","login");
		$this->display("q_index");
	}

	function ready(){ // 这里是首页		
		//header("Location: ?app=eq&act=eqtest&op=ready");
		//exit;	
		$account=getAccount();		
		if($account){
			$eqScore=readEqScore($account["uid"],false);
			$this->assign("eqScore",$eqScore);
		}

		$this->set("op","ready");
		$this->display("q_index");
	}

	function ican(){	
		//header("Location: ?app=eq&act=eqtest&op=ican");
		//exit;
		global $timestamp;
		$account=getAccount();
		if(!$account){
			$this->set("op","login");
			$this->display("q_index");
			return;
		}
		
		ssetcookie('eq_usetime',"");
		ssetcookie("eq_eqvalue","");
	/*	if(!strpos($_SERVER["HTTP_REFERER"],"app=eq"))
		{
			$this->set("op","ready");
			$this->display("q_index");	
			return;
		}*/
		$this->saveEq(-1,0);

		$this->set("op","ican");
		$this->display("q_ican");
	}
	

	//读取统计数据
	function stats(){	
		$account=getAccount();
		if($account){
			//$this->set("op","login");
			//$this->display("q_index");
			//return;
			$eqScore=readEqScore($account["uid"],false);
			$this->assign("eqScore",$eqScore);
			$this->set('myfriendslist',$this->friendstoplist($account["uid"]));
		}

		$this->set("op","stats");
		//print_r($eqCount);
		$this->set("eqCount",$this->eqCount());
		$this->set('toplist',$this->toplist());
		$this->display("q_index");
	}

	private function eqCount(){
		$eqCount=array();

		//读取总数据
		$sql="select (select count(*) from ". dbhelper::tname("eq","eq") .") as eq,(select count(*) from ". 
			dbhelper::tname("eq","log") .") as log,(select count(*) from ". dbhelper::tname("eq","eq") .") as co";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$eqCount["eqs"]=$row['eq'];
			$eqCount['logs']=$row['log'];
			$eqCount['totalUser']=$row['co'];

		}else{	
			$eqCount["eqs"]=0;
			$eqCount['logs']=0;
			$eqCount['totalUser']=0;
		}

		//读取排名第一
		$testlist=array();
		$sql="select l.uid,l.name,testCount,eq,useTime from ". dbhelper::tname("eq","eq") . " eq  inner join ".dbhelper::tname("ppt","login").
			" l on eq.uid=l.uid where eq.eq>0  and lasttime > UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) )  order by eq.eq desc,testCount ,lasttime desc limit 0,1";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$eqCount["maxEq"]=$row['eq'];
			$eqCount['maxName']=$row['name'];
			$eqCount['maxTestCount']=$row['testCount'];
			$eqCount['maxUseTime']=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
		}
		return $eqCount;
	}

	function cacl(){
		$account=$this->checkLogin();
		$eqvalue=rq("eqvalue",-1);
		$useTime=rq("usetime",0);
		if($eqvalue==-1) {
			if(!rf("usetime",false)){
				header("Location: ?app=eq");
				exit;
			}
			$useTime=rf("usetime",0);
			$eqvalue=0;     
	/*1~9  6,3,0  10~16 5,2,0   17~25 5,2,0   26~29 yes=0,no=5   30~33 1,2,3,4,5*/
			$stand=array(6,3,0);
			$eqvalue += $stand[intval(rf("s_1",3))-1];
			$eqvalue += $stand[intval(rf("s_2",3))-1];
			$eqvalue += $stand[intval(rf("s_3",3))-1];
			$eqvalue += $stand[intval(rf("s_4",3))-1];
			$eqvalue += $stand[intval(rf("s_5",3))-1];
			$eqvalue += $stand[intval(rf("s_6",3))-1];
			$eqvalue += $stand[intval(rf("s_7",3))-1];
			$eqvalue += $stand[intval(rf("s_8",3))-1];
			$eqvalue += $stand[intval(rf("s_9",3))-1];

			$stand=array(5,2,0);
			$eqvalue += $stand[intval(rf("s_10",3))-1];
			$eqvalue += $stand[intval(rf("s_11",3))-1];
			$eqvalue += $stand[intval(rf("s_12",3))-1];
			$eqvalue += $stand[intval(rf("s_13",3))-1];
			$eqvalue += $stand[intval(rf("s_14",3))-1];
			$eqvalue += $stand[intval(rf("s_15",3))-1];
			$eqvalue += $stand[intval(rf("s_16",3))-1];

			$stand=array(5,2,0);
			$eqvalue += $stand[intval(rf("s_17",3))-1];
			$eqvalue += $stand[intval(rf("s_18",3))-1];
			$eqvalue += $stand[intval(rf("s_19",3))-1];
			$eqvalue += $stand[intval(rf("s_20",3))-1];
			$eqvalue += $stand[intval(rf("s_21",3))-1];
			$eqvalue += $stand[intval(rf("s_22",3))-1];
			$eqvalue += $stand[intval(rf("s_23",3))-1];
			$eqvalue += $stand[intval(rf("s_24",3))-1];
			$eqvalue += $stand[intval(rf("s_25",3))-1];
			
			$stand=array(0,5);
			$eqvalue += $stand[intval(rf("s_26",1))-1];
			$eqvalue += $stand[intval(rf("s_27",1))-1];
			$eqvalue += $stand[intval(rf("s_28",1))-1];
			$eqvalue += $stand[intval(rf("s_29",1))-1];
			
			$stand=array(1,2,3,4,5,0);
			$eqvalue += $stand[intval(rf("s_30",6))-1];
			$eqvalue += $stand[intval(rf("s_31",6))-1];
			$eqvalue += $stand[intval(rf("s_32",6))-1];
			$eqvalue += $stand[intval(rf("s_33",6))-1];
		
		 ////echo '<!--'.$eqvalue.'--'.$useTime.'-->';
			$this->saveEq($eqvalue,$useTime);
			header("Location: ?app=eq&op=cacl&eqvalue=$eqvalue&usetime=$useTime");
			//echo '<script type="text/javascript">location.href="?app=eq&op=cacl&eqvalue='.$eqvalue.'&usetime='.$useTime.'";</script>';
		}

		$score=readEqScore($account["uid"],true);
		
		$score["words"] = getWords($score['eqlv']);
		$score["newusetime"] = strftime('%M:%S',$useTime);
		$score['noweq']=$eqvalue;
		//echo json_encode($score);
		//exit;
		if($account){
			$eqScore=readEqScore($account["uid"],false);
			$this->assign("eqScore",$eqScore);
		}
		$this->set("score",$score);
		$this->set("op","showscore");
		$this->display("q_ican");
	}
		
	public function sendStats(){
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}
		$sql="update ".dbhelper::tname("ppt","user")." set posts=posts+1 where uid='".$account['uid']."'";
		dbhelper::execute($sql);

		$eqCount=$this->eqCount();
		$msg="#看看你有多聪明#数据统计：总登录人数 ".$eqCount['totalUser']."，成功测试人数 ".$eqCount[eqs]."人， 有效测试 ".$eqCount[logs]. "次。目前@".$eqCount[maxName]. " 以 ". $eqCount[maxEq]. "分的惊人成绩 排名第一！希望聪明的你可以创造奇迹超过他！ ";
	
		$msg .= URLBASE ."eq/?lfrom=".$account["lfrom"]."&retuid=".$account['uid']."&retapp=eq";
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

		$score=readEqScore($account['uid'],false); //得了".$score['eq']."分，

		if($score['lostname'])
			$msg="刚刚玩#看看你有多聪明#，我目前最高 EQ 得分".$score['eq']."，获得 #".$score['chs']."证书#，全国排名第".$score['top']."，打败了".$score['lostname']."哈哈！";
		else
			$msg="刚刚玩#看看你有多聪明#，我目前最高 EQ 得分".$score['eq']."，获得 #".$score['chs']."证书#，全国排名第".$score['top']."，打败了全国 ".$score["win"]. " 的博友! ";

		if($score['retname'])
			$msg.= $score['retname']	. "你们也来试试！";
		else
			$msg.= "你们也来试试吧！";

		$msg .= URLBASE ."eq/?lfrom=".$account["lfrom"]."&retuid=".$account['uid']."&retapp=eq";
		///echo $score['zsurl'].$msg; exit;
		$this->getApi()->upload($msg,$score['zsurl']);
		echo "1";exit;
	}
		
	public function sendstatus2(){
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}

		$type=rf('type','eq');
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
		$sql="select l.uid,l.name,eq.testCount,eq.eq,eq.useTime,l.lfrom from ". dbhelper::tname("eq","eq") . " eq  inner join ".dbhelper::tname("ppt","login")." l on eq.uid=l.uid   inner join ".
			dbhelper::tname("ppt","user_sns")." sns on sns.uid2=l.uid and sns.type=1 where sns.uid1=".$uid." order by eq desc,testCount limit 0,10";

		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$eqlv=0;
			$row["ch"]=eqtoch($row["eq"],&$eqlv);
			$row["eqlv"]=$eqlv;
			$testlist[]=$row;
		}
//		echo json_encode($testlist);
		return $testlist;	
	}

	private function mingrenlist(){
		$ocache=new Cache();
		$minLast=$ocache->get('eq_minlast');
		if(getTimestamp() - $minLast<3600) {
			$testlist=$ocache->get('eq_mingrenlist');			
			if( is_array($testlist)) 
				return $testlist;
		}

		$top=10;
		$testlist=array();

		$sql="select l.uid,l.name,l.followers,testCount,eq,useTime,lfrom,l.avatar,l.verified from (select * from ".
		dbhelper::tname("ppt","user")." where followers>70000 and avatar<>'' order by followers desc limit 0,100) l,". dbhelper::tname("eq","eq") . " eq where l.uid=eq.uid limit 0,10";

		//$sql="select l.uid,l.name,l.followers,testCount,eq,useTime,lfrom,l.avatar,l.verified from ". dbhelper::tname("eq","eq") . " eq  inner join ".
		//	dbhelper::tname("ppt","user")." l on eq.uid=l.uid where l.avatar<>'' order by l.followers desc  limit 0,$top";

		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$eqlv=0;
			$row["ch"]=eqtoch($row["eq"],&$eqlv);
			$row["eqlv"]=$eqlv;
			if($row['lfrom']=='tqq' && right($row['avatar'],3)!="/50") $row['avatar'] .= "/50";

			$testlist[]=$row;
		}
//		print_r($testlist);
		$ocache->set('eq_minlast',gettimestamp());	
		$ocache->set('eq_mingrenlist',$testlist);
		return $testlist;
	}

	private function toplist(){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,testCount,eq,useTime,lfrom from ". dbhelper::tname("eq","eq") . " eq  inner join ".
			dbhelper::tname("ppt","login")." l on eq.uid=l.uid where eq>0 AND  lasttime > UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ))  order by eq desc,testCount  limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$eqlv=0;
			$row["ch"]=eqtoch($row["eq"],&$eqlv);
			$row["eqlv"]=$eqlv;
			$testlist[]=$row;
		}
//		echo json_encode($testlist);
		return $testlist;
	}

	private function toplist7(){
		$top=10;
		$testlist=array();
		$sql="select l.uid,l.name,testCount,eq,useTime,lfrom from ". dbhelper::tname("eq","eq") . " eq  inner join ".
			dbhelper::tname("ppt","login")." l on eq.uid=l.uid where eq>0 and lasttime > UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) ) order by eq desc,testCount  limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row["useTime"]=intval($row["useTime"]/60) ."分".$row["useTime"] % 60 ."秒";
			$eqlv=0;
			$row["ch"]=eqtoch($row["eq"],&$eqlv);
			$row["eqlv"]=$eqlv;
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
		$sql="select eq.id,l.uid,l.name,eq,lasttime,l.lfrom from ". dbhelper::tname("eq","log") . " eq  inner join ".dbhelper::tname("ppt","login")." l on eq.uid=l.uid  where eq.eq>0 and eq.lasttime>{$last} order by eq.lasttime desc limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row['testtime']= date("m-d H:i:s",$row['lasttime']);
			$eqlv=0;
			$row["ch"]=eqtoch($row["eq"],&$eqlv);
			$row["eqlv"]=$eqlv;
			$testlist[]=$row;			
		}
		echo json_encode($testlist);
	}


	private function saveEq($eqvalue,$useTime){
		global $timestamp;
		$account=getAccount();
				
		$ret=envhelper::readRet();
		$sqlu="lasttime=$timestamp,followers=".$account['followers'].",followings=".$account['followings'].",tweets=".$account['tweets'].",retuid='".$ret['retuid'] ."'";
		$lasttime=0;
		$sql="select testCount,eq,lasttime,useTime from ". dbhelper::tname("eq","eq") ." where uid=" . $account["uid"];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			if($eqvalue == -1 )
				$testCount=$row["testCount"]+1;
			else
				$testCount=$row["testCount"];

			if(intval($row["eq"]) > $eqvalue || (intval($row["eq"]) == $eqvalue && intval($row["useTime"]) < $useTime ))
				$sql="update ". dbhelper::tname("eq","eq") ." set testCount=$testCount,lasttime=$timestamp where uid=" .$account["uid"];
			else
				$sql="update ". dbhelper::tname("eq","eq") ." set testCount=$testCount,eq=". $eqvalue. ",useTime=".$useTime.",$sqlu where uid=" .$account["uid"];
			
			if($eqvalue > -1 )
				$sql .=";;;update ". dbhelper::tname("eq","log") ." set eq=". $eqvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid']. " where uid=".$account['uid']." and lasttime=".$row['lasttime'];
			else
				$sql .=";;;insert into ". dbhelper::tname("eq","log") ." set eq=". $eqvalue. ",$sqlu,uid=" . $account['uid'];
			
		}
		else{
			$sql ="insert into ". dbhelper::tname("eq","eq") ." set testCount=1,eq=". $eqvalue. ",useTime=".$useTime.",regtime=$timestamp,$sqlu,uid=" . $account['uid'];
			$sql .=";;;insert into ". dbhelper::tname("eq","log") ." set eq=". $eqvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid'];			
		}

		dbhelper::exesqls($sql);		
	}

}	


?>