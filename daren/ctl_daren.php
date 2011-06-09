<?php
if(!defined('ISWBYL')) exit('Access Denied');

$qtypes=array(1=>"百科知识","文史政治","科技与科学");
$question=array();
$question[0]="";

define("STEP",1);  //每次显示多少题
define("FULLMINUTE",100); //得勋章的分数
define("MINUTEPER",10); //每题多少分

include_once("test_fun.php");
include_once("daren_fun.php");


class daren extends ctl_base
{
	function index() {
		$account=getAccount();		
		if($account){
			//$darenScore=readdarenScore($account["uid"],false);
			//$this->assign("darenScore",$darenScore);
		}
		//$this->set('mingrenlist',$this->mingrenlist());
		$qtype=readqtype();
		$this->set("qtype",$qtype);
		$this->set("qtypelist",readqtypelist());
		$this->set("op","login");
		$this->display("daren_index");
	}

	function ready(){
		ssetcookie('quest_idx',"");
//		ssetcookie('daren_question',"");
		ssetcookie('daren_starttime','');
		ssetcookie('daren_usetime','');
		ssetcookie("daren_darenvalue","");
		ssetcookie("daren_ans","");

		$account=$this->checkLogin();

		$darenScore=readdarenScore($account["uid"],false);
		$this->assign("darenScore",$darenScore);
		$this->set("op","ready");

		$qtype=readqtype();
		$this->set("qtype",$qtype);
		$this->set("qtypelist",readqtypelist());
		$this->display("daren_ican");
	}

	function ican(){
		//$account=$this->checkLogin();
		

		$this->set("op","ican");
		$qtype=readqtype();
		$this->set("qtype",$qtype);
		$this->set("qtypelist",readqtypelist());
		$this->display("daren_ican");		
	}

	function init(){
		$account=getAccount();		
		if(!$account){
			echo '-1';
			return;
		}
		$this->readQuestion();		

		getQuestion(false,1);
		exit;
	}

	//ajax method
	function next() {
		$account=getAccount();		
		if(!$account){
			echo '-1';
			return;
		}
		$this->readQuestion();		
		getQuestion(false);
		exit;
	}

	//ajax method
	function up() {		
		$account=getAccount();		
		if(!$account){
			echo '-1';
			return;
		}

		$this->readQuestion();
		getQuestion(false);
		exit;
	}

	function showscore() {
		global $question;
		$account=getAccount();
		if(!$account){
			$this->set("op","login");
			$this->display("daren_index");
			return;
		}
		$useTime=('0'.sreadcookie('daren_usetime'))*1;
		$darenvalue=('0'.sreadcookie('daren_darenvalue'))*1;
		if($useTime==0){
			$useTime=gettimestamp() - sreadcookie("daren_starttime") * 1;
			ssetcookie('daren_usetime',$useTime);
		
			$this->readQuestion();
			
			$raarr=saveAndReadAnswerToCookie(true);
			$ans=computeScore($raarr);
			$darenvalue=$ans[0];
			$rightcount=0;
			foreach($ans[1] as $aan) {
				if($aan[2] > 0 ) {
					$rightcount++;
				}
			}

			//ssetcookie("daren_ans",$ans[1]);
			ssetcookie('daren_rightcount',$rightcount);
			ssetcookie("daren_darenvalue",$darenvalue);
			$this->savedaren($darenvalue,$useTime);
			$this->clearQuestion();
			saveAndReadAnswerToCookie(0); //清空答题记录
		}
		
		$qtype=readqtype();
		$rightcount=sreadcookie('daren_rightcount');
		$score=readdarenScore($account["uid"],$qtype,true);
		$score["newusetime"] = $useTime;
		$score['rightcount'] =$rightcount;
		$score['nowdaren']=$darenvalue;

		ssetcookie('quest_idx',"");
		ssetcookie('daren_starttime','');
		ssetcookie('daren_starttime2','');
/*		ssetcookie('daren_usetime',$useTime);
		ssetcookie('daren_usetime2',$useTime);
*/
//echo $qtype;
//print_r($score);

		$this->set("score",$score);
		$this->set("op","showscore");

		$this->set("qtype",$qtype);
		if($qtype==1) $this->set('qtypename','综合类');
		else {
			$qa=readqtypelist();
			$this->set("qtypename",$qa[$qtype]);
		}
		$this->display("daren_result");

		exit;
	}
	
	private function readQuestion() {
		global $question,$qtypes;
		$qtype=readqtype();
		$qtypearr=readqtypelist();
		if($qtype==1) {
			$qtypes=array(1=>"百科知识","文史政治","科技与科学");
		}else {
			$qtypes=array(1=>$qtypearr[$qtype]);
		}
		
		$timestamp=gettimestamp();

		$cache=new CACHE();		
		$question=$cache->get("daren_question_".$qtype); 
		if(is_array($question) && 1==1) {
			$questionovertime=$cache->get("daren_question_time_".$qtype);
			
			if(dateDiff("d",$questionovertime , $timestamp)==0) {
				if(!sreadcookie("daren_starttime")) {  //初始化测试，包括开始时间及数据库记录
					ssetcookie("daren_starttime",gettimestamp());
					if(SAVELOG) $this->savedaren(-1,0); 
				}
				return ;
			}
			echo '/*not same day,update question!*/';
		}
		
		$day=strftime("%y%m%d",$timestamp);
		$question=array();
		$question[0]="";
	
		$isDaylog=false;
		$sql="select log.id from ".dbhelper::tname('daren','tmb_daylog') ." log where qtype=$qtype and tmday=$day limit 0,1";
		$rs=dbhelper::getrs($sql);
		if ($row=$rs->next()) {
			$sql="select tmb.* from ".dbhelper::tname('daren','tmb_daylog') ." log inner join ".dbhelper::tname('daren','tmb') ." tmb on log.tmid=tmb.tmxh where log.qtype=$qtype and log.tmday=$day";
			$isDaylog=true;
		}
		else {			
			if($qtype==1) {
				$sql="select * from (select * from ". dbhelper::tname("daren","tmb") ."  where qtype=0 order by rand() limit 0,4 ) a
				union select * from (select * from ". dbhelper::tname("daren","tmb") ."  where qtype=1 order by rand() limit 0,3 ) b
				union select * from (select * from ". dbhelper::tname("daren","tmb") ."  where qtype=2 order by rand() limit 0,6 ) c";
			}else {
				$sql=" select * from ". dbhelper::tname("daren","tmb") ."  where kindid=".$qtype." order by rand() limit 0,15";
				$qtypes=array(1=>$qtype[1]);
			}
		}
		$rs=dbhelper::getrs($sql);
		
		$sql="insert into ".dbhelper::tname('daren','tmb_daylog') ." (qtype,tmday,tmid,regtime) values ";
		$sp="";		$i=0; $timestamp=gettimestamp();

		while($i<10 && $row=$rs->next()) {
			if($qtype==1) $kindid=$row['qtype']+1;
			else  $kindid=$row['kindid']+1;
					
			$zqda=$row['tmzqda'];
			$idx=strpos($zqda,"1");
			if($idx!==false) $idx;
			else continue;

			$arr=array(
					trim($row['tmda1']),MINUTEPER,
					trim($row['tmda2']),0,
					trim($row['tmda3']),0,
					trim($row['tmda4']),0
				);
//			$arr[$idx*2+1]=MINUTEPER;
			$question[] = array(
				0=>$kindid,
				1=>trim($row['tmnr']),
				$arr
			);
			$i++;
			
			if(!$isDaylog) {
				$sql .= $sp . "($qtype,$day,".$row["tmxh"].",$timestamp)";
				$sp=",";
			}
		}
		if(!$isDaylog) dbhelper::execute($sql); //保存当日题目到表中

		$cache->set("daren_question_".$qtype,$question);
		$cache->set("daren_question_time_".$qtype,gettimestamp());
		if(!sreadcookie("daren_starttime")) {
			ssetcookie("daren_starttime",gettimestamp());
				if(SAVELOG)	$this->savedaren(-1,0); 
		}
		//echo "question=";print_r($question);exit;
	}

	//清空试卷，如果是固定的试卷如daren测试，则此函数为空值
	private function clearQuestion(){
		ssetcookie('quest_idx',"");
		//ssetcookie('daren_question',"");
	}


	private function savedaren($darenvalue,$useTime){
		global $timestamp;
		$account=getAccount();
		$qtype=readqtype();


		$ret=envhelper::readRet();
		$sqlu="lasttime=$timestamp,followers=".$account['followers'].",followings=".$account['followings'].",tweets=".$account['tweets'].",retuid='".$ret['retuid'] ."'";
		$lasttime=0;
		$sql="select * from ". dbhelper::tname("daren","win") ." where qtype=$qtype and uid=" . $account["uid"];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$last=$row['lasttime'];
			if($darenvalue == -1 ){
				$testCount=1;
				$filish=0;
			}
			else {
				$testCount=0;
				$filish=1;
			}

			$wins=0;
			if($darenvalue==FULLMINUTE) {
				$sql="select id from ". dbhelper::tname("daren","log") ." where  uid=" . $account["uid"]." and qtype=$qtype and score>=".FULLMINUTE." and  ( lasttime > UNIX_TIMESTAMP(FROM_DAYS(TO_DAYS(now())) ) limit 0,1";
				$rs=dbhelper::getrs($sql);
				if($row=$rs->next()){
					$wins=0;
				}
				else
					$wins=1;
			}
			$sql="update ". dbhelper::tname("daren","win") ." set  testCount=testCount+$testCount,wincount=wincount+$wins,filishcount=filishcount+$filish,lasttime=$timestamp  where  qtype=$qtype and uid=" . $account["uid"];
			$sql .=";;;update ". dbhelper::tname("daren","daren") ." set  testCount=testCount+$testCount,wincount=wincount+$wins,filishcount=filishcount+$filish,lasttime=$timestamp  where  uid=" . $account["uid"];
			
			if(SAVELOG && $darenvalue > -1 ) {
				$sql .=";;;update ". dbhelper::tname("daren","log") ." set score=". $darenvalue. ",useTime=".$useTime.",$sqlu
				where uid=".$account['uid']." and lasttime=".$last;
			}
			else{
				$sql .=";;;insert into ". dbhelper::tname("daren","log") ." set qtype=$qtype,score=". $darenvalue. ",$sqlu,uid=" . $account['uid'];
			}
		}
		else{
			$sql ="insert into ". dbhelper::tname("daren","win") ." set qtype=$qtype,testCount=1,regtime=$timestamp,lasttime=$timestamp,uid=" . $account['uid'];
			$sql .=";;;insert into ". dbhelper::tname("daren","daren") ." set testCount=1,regtime=$timestamp,lasttime=$timestamp,uid=" . $account['uid'];
			$sql .=";;;insert into ". dbhelper::tname("daren","log") ." set qtype=$qtype,score=". $darenvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid'];

		}
//echo $sql;//exit;
		dbhelper::exesqls($sql);
		$this->_makeTop() ;
	}

	private function _makeTop() {		
		global $timestamp;

		$cache=new CACHE();
		$lastmaketop=$cache->get("daren_lastmaketop"); 
		if(1==0 && $lastmaketop && dateDiff("d",$lastmaketop , $timestamp)==1) {
				return ;
		}
		
		if (ISSAE) {
			$queue = new SaeTaskQueue('test');//此处的test队列需要在在线管理平台事先建好
		 
			//添加单个任务
			$queue->addTask("http://ijoy.sinaapp.com/?app=daren&op=maketop");
		 
			//将任务推入队列
			$ret = $queue->push();
			//var_dump($ret);
			return;
		}

		$this->maketop();

	}

	public function maketop() {	
		global $timestamp;
		
		$day=intval(strftime("%y%m%d",$timestamp))-1;

		$sql="delete from daren_tmp_daysort ";
		dbhelper::execute($sql);

		$rs=dbhelper::getrs("select qtype from ". dbhelper::tname("daren","log") ." where lasttime <UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) )  and  lasttime >= UNIX_TIMESTAMP( ADDDATE(now(), INTERVAL -1 DAY) )  group by qtype");
		while($row=$rs->next()) {
			$sql="insert into ". dbhelper::tname("daren","tmp_daysort") ." (uid,score,qtype)  
			select uid,max(score * 1000+990-usetime) as score2," .$row['qtype']. " from ". dbhelper::tname("daren","log") ." where qtype=" . $row["qtype"] . " and lasttime <UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) )  and  lasttime >= UNIX_TIMESTAMP( ADDDATE(now(), INTERVAL -1 DAY) )  group by uid order by score2 desc limit 0,10";
			
			//echo $sql."<br/>";
			dbhelper::execute($sql);
		}
		$sql="delete from ". dbhelper::tname("daren","win_top") ." where winday=$day";
		$sql .=";;;insert into ". dbhelper::tname("daren","win_top") ." (qtype,winday,uid,no,score,usetime,regtime,lasttime) ";
		$sql .="select log.qtype,$day,log.uid,0,log.score,log.usetime,$timestamp,log.lasttime from ". dbhelper::tname("daren","tmp_daysort") ." top ,  ". dbhelper::tname("daren","log") ." log where log.qtype=top.qtype and log.uid=top.uid and log.score=top.score div 1000 and log.usetime=990-top.score % 1000 " ;		
		//echo $sql;
		dbhelper::exesqls($sql);
		$sql="select uid,qtype from ". dbhelper::tname("daren","win_top") ." where winday=$day";
		$rs=dbhelper::getrs($sql);
		$sql="";
		while($row=$rs->next()) {
			$sql.="update ". dbhelper::tname("daren","win") ."  set topcount=topcount+1,lasttime=$timestamp where qtype=".$row['qtype']." and uid =".$row['uid'].";;;";
			$sql.="update ". dbhelper::tname("daren","daren") ."  set topcount=topcount+1,lasttime=$timestamp where uid =".$row['uid'].";;;";
		}
		dbhelper::exesqls($sql);

		$cache=new CACHE();
		$cache->set("daren_lastmaketop",$timestamp);
	}
}	
?>