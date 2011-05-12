<?php
if(!defined('ISWBYL')) exit('Access Denied');

$qtype=array(1=>"百科知识","文史政治","科技与科学");
$question=array();
$question[0]="";

define("STEP",1);  //每次显示多少题
define("FULLMINUTE",150); //得勋章的分数
define("MINUTEPER",10); //每题多少分

include_once("test_fun.php");
include_once("daren_fun.php");


class daren extends ctl_base
{
	function index() {
		$account=getAccount();		
		if($account){
			//$iqScore=readIqScore($account["uid"],false);
			//$this->assign("iqScore",$iqScore);
		}
		//$this->set('mingrenlist',$this->mingrenlist());
		$this->set("op","login");
		$this->display("daren_index");
	}

	function ready(){
		$qtype=rq("qtype",0);
		if($qtype==0) {
			header("Location: ?app=daren");
			exit ;
		}
		ssetcookie('quest_idx',"");
//		ssetcookie('daren_question',"");
		ssetcookie('daren_starttime','');
		ssetcookie('daren_usetime','');
		ssetcookie("daren_iqvalue","");
		ssetcookie("daren_ans","");

		$account=$this->checkLogin();

		$iqScore=readIqScore($account["uid"],false);
		$this->assign("iqScore",$iqScore);
		$this->set("op","ready");
		$this->display("daren_ican");
	}

	function ican(){
		$account=$this->checkLogin();

		$this->set("op","ican");
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
			$this->display("iq_index");
			return;
		}
		$useTime=('0'.sreadcookie('daren_usetime'))*1;
		$iqvalue=('0'.sreadcookie('daren_iqvalue'))*1;
		if($useTime==0){ echo 'sdfsdfs';
			$useTime=gettimestamp() - sreadcookie("daren_starttime") * 1;
			ssetcookie('daren_usetime',$useTime);
		
			$this->readQuestion();
			
			$raarr=saveAndReadAnswerToCookie(true);
			$ans=computeScore($raarr);
			//print_r($ans);
			$iqvalue=$ans[0];

			ssetcookie("daren_ans",$ans[1]);
			ssetcookie("daren_iqvalue",$iqvalue);
			$this->savedaren(readqtype(),$iqvalue,$useTime);
			$this->clearQuestion();
			saveAndReadAnswerToCookie(0); //清空答题记录
		}

		$score=readIqScore($account["uid"],true);
		$score["words"] = getWords($score['iqlv']);
		$score["newusetime"] = $useTime;

		$score['nowiq']=$iqvalue;
		//echo json_encode($score);

		ssetcookie('quest_idx',"");
		/////ssetcookie('daren_question',"");
		ssetcookie('daren_starttime','');
		ssetcookie('daren_starttime2','');
/*		ssetcookie('daren_usetime',$useTime);
		ssetcookie('daren_usetime2',$useTime);
*/
		$this->set('daren_ans',$ans);
		$this->set("score",$score);
		$this->set("op","showscore");
		$this->display("daren_ican");

		exit;
	}
	
	private function readQuestion() {
		global $question,$qtype;
		$qtype=array(1=>"百科知识","文史政治","科技与科学");

		$cache=new CACHE();
		
		$question=$cache->get("daren_question"); 
		if(is_array($question)) {
			$questionovertime=$cache->get("daren_question_time");
			
			if(dateDiff("d",$questionovertime , $timestamp)==0) {
				if(!sreadcookie("daren_starttime")) {  //初始化测试，包括开始时间及数据库记录
					ssetcookie("daren_starttime",gettimestamp());
					$this->savedaren(readqtype(),-1,0);
				}
				return ;
			}
			echo '/*not same day,update question!*/';
		}
		
		$question=array();
		$question[0]="";

		$sql="select * from (select * from data_tmb where qtype=0 order by rand() limit 0,5 ) a
			union select * from (select * from data_tmb where qtype=1 order by rand() limit 0,5 ) b
			union select * from (select * from data_tmb where qtype=2 order by rand() limit 0,5 ) c";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($i<15 && $row=$rs->next()) {
			
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
				0=>$row['qtype']+1,
				1=>trim($row['tmnr']),
				$arr
			);
			$i++;
		}
		$cache->set("daren_question",$question);
		if(!sreadcookie("daren_starttime"))
			ssetcookie("daren_starttime",gettimestamp());
		//print_r($question);exit;
	}

	//清空试卷，如果是固定的试卷如IQ测试，则此函数为空值
	private function clearQuestion(){
		ssetcookie('quest_idx',"");
		//ssetcookie('daren_question',"");
	}


	private function savedaren($qtype,$iqvalue,$useTime){
		global $timestamp;
		$account=getAccount();
		
		$day=strftime('%y%m%d',$timestamp);
		$ret=envhelper::readRet();
		$sqlu="lasttime=$timestamp,followers=".$account['followers'].",followings=".$account['followings'].",tweets=".$account['tweets'].",retuid='".$ret['retuid'] ."'";
		$lasttime=0;
		$sql="select * from ". dbhelper::tname("daren","win") ." where qtype=$qtype and uid=" . $account["uid"];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			if($iqvalue == -1 ){
				$testCount=1;
				$filish=0;
			}
			else {
				$testCount=0;
				$filish=1;
			}

			$wins=0;
			if($iqvalue==FULLMINUTE) {
				$sql="select id from ". dbhelper::tname("daren","log") ." where  qtype=$qtype and uid=" . $account["uid"]." and score>=".FULLMINUTE." and  ( lasttime > UNIX_TIMESTAMP(FROM_DAYS(TO_DAYS(now())) ) limit 0,1";
				$rs=dbhelper::getrs($sql);
				if($row=$rs.next()){
					$wins=0;
				}
				else
					$wins=1;
			}			
			$sql="update ". dbhelper::tname("daren","win") ." set  testCount=testCount+$testCount,wincount=wincount+$wins,filishcount=filishcount+$filish,lasttime=$timestamp  where  qtype=$qtype and uid=" . $account["uid"];
			
			if($iqvalue > -1 ) {
				$sql .=";;;update ". dbhelper::tname("daren","log") ." set score=". $iqvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid']. " where uid=".$account['uid']." and lasttime=".$row['lasttime'];
			}
			else{
				$sql .=";;;insert into ". dbhelper::tname("daren","log") ." set qtype=$qtype,score=". $iqvalue. ",$sqlu,uid=" . $account['uid'];
			}
		}
		else{
			$sql ="insert into ". dbhelper::tname("daren","win") ." set qtype=$qtype,testCount=1,regtime=$timestamp,uid=" . $account['uid'];
			$sql .=";;;insert into ". dbhelper::tname("daren","log") ." set qtype=$qtype,score=". $iqvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid'];

		}
//echo $sql;exit;
		dbhelper::exesqls($sql);		
	}

}	


?>