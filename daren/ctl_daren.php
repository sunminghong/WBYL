<?php
if(!defined('ISWBYL')) exit('Access Denied');

$question=array();
$question[0]="";

define("STEP",1);  //每次显示多少题
define("FULLMINUTE",130); //得勋章的分数
define("MINUTEPER",15); //每题多少分
define("TODAYMAXJIFEN",10); //每天没类别最多可获得的智慧币数量
define("HELPERPRICE",2); //在线求助的价格

include_once("test_fun.php");
include_once("daren_fun.php");


class daren extends ctl_base
{
	function _getfooter() {		
		$this->set('qtypenamelist',readqtypelist());

		$this->set('todaytoplist',_gettodaytop());
		$this->set('testlist',_gettestlist());
		$this->set('yesterdayniurenlist',_getyesterdayniuren());
	}

	function index() {
		global $ret;
		$uid=0;
		if($ret) {
			$uid=$ret['retuid'];
			$this->set("isret",1);
		}
		else {
			$account=getAccount();
			if($account) {
				$uid=$account['uid'];
			}
		}

		if( $uid==0)
			$score=false;
		else
			$score=readdarenScore($uid,0,false);//print_r($score);

		$this->set("score",$score);

		$this->set('allcount',_getallcount());

		$this->set("op","index");
		$this->_getfooter();
		$this->set("pagetitle","");
		$this->display("daren_index");
	}

	function top() {
		global $ret;
		$uid=0;
		if($ret) {
			$uid=$ret['retuid'];
			$this->set("isret",1);
		}
		else {
			$account=getAccount();
			if($account) {
				$uid=$account['uid'];
			}
		}

		if( $uid==0)
			$score=false;
		else
			$score=readdarenScore($uid,0,false);//print_r($score);

		$this->set("score",$score);
		$this->set('todaytoplist',_gettodaytop(20));
//		$this->set('filishtoplist',_getfilishtop(20));
		$this->set('darentoplist',_getdarentop(20));
		$this->set('jifentoplist',_getjifentop(20));

		$this->set("op","top");
		$this->set("pagetitle","智慧排行榜");
		$this->display("daren_top");

	}

	function profile(){
		global $ret;
		$account=getAccount();

		$uid=rq("uid",0);
		if(!is_numeric($uid) || !$uid) {
			
			if($account) {
				$uid=$account['uid'];
			}else
			{
				$this->index();
				return;
			}
		}
		
		$sql="select coalesce(jifen,0) as jifen,COALESCE(wincount,0) as wincount,COALESCE(filishcount,0) as filishcount,COALESCE(testcount,0) as testcount,COALESCE(topcount,0) as topcount,coalesce(wenquxingcount,0) as wenquxingcount,coalesce(boshicount,0) as boshicount,coalesce(jifen,0) as jifen,coalesce(alljifen,0) as alljifen,l.uid,l.name,l.lfrom,l.avatar,l.lfromuid from ".dbhelper::tname('ppt','user') ." l left join ".dbhelper::tname('daren','daren') ." d on d.uid=l.uid  where l.uid=$uid limit 0,1"; //echo $sql;
		$rs=dbhelper::getrs($sql);
		if(!($row=$rs->next())) {		
			$row=array(
				"wincount"=>0,
				"topcount"=>0,
				"wenquxingcount"=>0,
				"boshicount"=>0,
				'jifen'=>0,
				'alljifen'=>0
			);
		}
		$this->set('totalcountlist',$row);

		$sql="select qtype,wincount,topcount from ".dbhelper::tname('daren','daren_qtype') ." where uid=$uid and (wincount>0 or topcount>0) order by qtype";
		$row=dbhelper::getrows($sql);
		$this->set('qtypecountlist',$row);


		$sql="select qtype,DATE_FORMAT( FROM_UNIXTIME(lasttime) ,  '%Y-%m-%d' ) as winday,score,usetime from ".dbhelper::tname('daren','top_day') ." where uid=$uid";
		$row=dbhelper::getrows($sql);
		$this->set('qtypetoplist',$row);


		$sql="select zstype,DATE_FORMAT( FROM_UNIXTIME(lasttime) ,  '%Y-%m-%d' ) as winday from ".dbhelper::tname('daren','zslog') ." where uid=$uid";
		$row=dbhelper::getrows($sql);
		$this->set('boshilist',$row);
		
		//todo:还没有读取文曲星和博士的记录
		//$this->set('wenquxinglist',false);



		$uid=0;
		if($ret) {
			$uid=$ret['retuid'];
			$this->set("isret",1);
		}
		else {
			if($account) {
				$uid=$account['uid'];
			}
		}		
		$score=readdarenScore($uid,0,false);//print_r($score);
		$this->set("score",$score);


		$this->set('qtypenamelist',readqtypelist());

		$this->set('todaytoplist',_gettodaytop());
		$this->set('testlist',_gettestlist());

		$this->set("op","profile");
		$this->set("pagetitle",$score["name"]."的成就 - ");
		$this->display("daren_profile");
	}

	function helper() {
		global $timestamp;
		$account=getAccount();		
		if(!$account){
			echo "-1";
			return;
		}
	
		if( decjifen($account['uid'], HELPERPRICE))
			echo "1";
		else
			echo  "-1";
	}

	function ican(){
		$account=getAccount();		
		if(!$account){
			$this->index();
			return;
		}
		
		//$uid=0;
		//if($ret) {
		//	$uid=$ret['retuid'];
		//}
		//else {			
		
				$uid=$account['uid'];
			
		//}
		
		ssetcookie('quest_idx',"");
//		ssetcookie('daren_question',"");
		ssetcookie('daren_starttime','');
		ssetcookie('daren_usetime','');
		ssetcookie("daren_darenvalue","");		
		ssetcookie("daren_givejifen","");
		ssetcookie("daren_ans","");

		$account=$this->checkLogin();

		$this->set("op","ican");		
		$this->set("pagetitle","每日十问 - ");

		$score=readdarenScore($uid,0,false);		//print_r($score);
		$this->set("score",$score);

		$this->_getfooter();
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
		$givejifen = ('0'.sreadcookie("daren_givejifen"))*1;

		if($useTime==0){
			$useTime=gettimestamp() - sreadcookie("daren_starttime") * 1;
			ssetcookie('daren_usetime',$useTime);
		
			$this->readQuestion();
			
			$raarr=saveAndReadAnswerToCookie(true);
			$ans=computeScore($raarr);
			$darenvalue=floor($ans[0]);
			$rightcount=0;
			foreach($ans[1] as $aan) {
				if($aan[2] > 0 ) {
					$rightcount++;
				}
			}

			//ssetcookie("daren_ans",$ans[1]);
			ssetcookie('daren_rightcount',$rightcount);
			ssetcookie("daren_darenvalue",$darenvalue);
			$givejifen = $this->savedaren($darenvalue,$useTime);
			ssetcookie("daren_givejifen",$givejifen);
			$this->clearQuestion();
			saveAndReadAnswerToCookie(0); //清空答题记录
		}
		
		$qtype=readqtype();
		$rightcount=sreadcookie('daren_rightcount');
		$score=readdarenScore($account["uid"],$qtype,true);
		$score["newusetime"] = $useTime;
		$score['rightcount'] =$rightcount;
		$score['nowdaren']=$darenvalue;
		$score['givejifen']=$givejifen;

		if($darenvalue<=10) $score['naotou']='yundao';
		elseif ($darenvalue<40) $score['naotou']='shengqi';
		elseif ($darenvalue<60) $score['naotou']='yanshu';
		elseif ($darenvalue<80) $score['naotou']='weixiao';
		elseif ($darenvalue<90) $score['naotou']='daxiao';
		elseif ($darenvalue<95) $score['naotou']='jinya';
		else $score['naotou']='gaoao';

		if($darenvalue<80) $score['word']='tanwan';
		else  $score['word']='taiyoucaile';

		ssetcookie('quest_idx',"");
		ssetcookie('daren_starttime','');
		ssetcookie('daren_starttime2','');
/*		ssetcookie('daren_usetime',$useTime);
		ssetcookie('daren_usetime2',$useTime);
*/
//echo $qtype;
//print_r($score);
		
		ssetcookie('daren_zhengshu_url_daren','');
		if($darenvalue>=FULLMINUTE){
			$picurl=_makeZhengshu($account["uid"],$account['name'],$darenvalue,"daren",$account['avatar']);
			$this->set('darenzhengshuurl',$picurl);
		}
		
		$this->set("score",$score);

		$this->set("qtype",$qtype);
		if($qtype==1) $this->set('qtypename','综合类');
		else {
			$qa=readqtypelist();
			$this->set("qtypename",$qa[$qtype][0]);
		}

		$this->_getfooter();
		$this->set("op","showscore");
		$this->set("pagetitle","测试成绩 - ");
		$this->display("daren_result");
	}

	public function follow() {
		global $timestamp;
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';return;
		}
		$api=$this->getApi();

		$byuid=rf('byuid',''); 
		if($byuid) {
			$id=rf("id",'0');
			$bylfrom=rf('bylfrom','');
			$bylfromuid=rf('bylfromuid','');

			if($bylfrom==$account['lfrom']) {
				$ret=$api->follow($bylfromuid);			
				echo "1";
			}else {
				echo "-2";
			}
			return ;
		}
		
		$isFollow=0;
		$sql="select isfollow from ". dbhelper::tname("daren","daren") ." where uid=".$account['uid'];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()) {
			if(intval($row['isfollow']) == 0) {
				$sql ="update ". dbhelper::tname("daren","daren") ." set  jifen=jifen+10,alljifen=alljifen+100,isfollow=isfollow+1,lasttime=$timestamp  where  uid=" . $account["uid"];
				dbhelper::execute($sql);
				$isFollow=1;
			}
		}else{
			$sql ="insert into ". dbhelper::tname("daren","daren") ." set  jifen=10,alljifen=100,isfollow=isfollow+1,regtime=$timestamp,lasttime=$timestamp  where  uid=" . $account["uid"];
			dbhelper::execute($sql);
			$isFollow=1;
		}
		//关注官方的处理
		global $apiConfig;
		$byuid=$apiConfig[$account['lfrom']]['orguid'];

		$ret=$api->follow($byuid);

		if($isFollow==1) {
			echo '1';			
		}
		else {
			echo "-3";
		}
	}

	function showzhengshu() {
		$zstype=rq("zstype","");
		if(!$zstype) return;
		$account=getAccount();
		if(!$account) {
			return;
		}

		$picurl=sreadcookie('daren_zhengshu_url_'.$zstype);
		if(!$picurl) {
			$picurl=_makeZhengshu($account["uid"],$account['name'],50,$zstype,$account['avatar']);
//			ssetcookie('daren_zhengshu_url_daren_v',$picurl);
			$picurl=sreadcookie('daren_zhengshu_url_'.$zstype);
		}

		header( "Content-type: image/jpg"); 
		echo file_get_contents($picurl); 
		exit;		
	}

	function getboshizhengshu(){
		global $timestamp;
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';return;
		}

		$is_sendmsg=rf('is_sendmsg','0');
		$msg=trim(rf("msg",""));
		if($is_sendmsg && $msg) {
			$qtype=0;
			$is_follow=0;
			$is_pic=1;
			$this->_sendstatus($qtype,$is_follow,$is_pic,$msg,"boshi");
		}
		$sql="select lastwincount from ".dbhelper::tname("daren","daren")." where uid=".$account['uid'];
		$lastwincount=dbhelper::getvalue($sql);
		if($lastwincount && intval($lastwincount)>=50) {
			$sql="update ".dbhelper::tname("daren","daren")." set lastwincount=lastwincount-50,boshicount=boshicount+1,lastboshicount=lastboshicount+1,jifen=jifen+168,alljifen=alljifen+168 where uid=".$account['uid'];
			$sql.=";;;insert into ".dbhelper::tname("daren","zslog")." (uid,zstype,lasttime) values(".$account['uid'].",1,$timestamp)";
			dbhelper::exesqls($sql);
			echo 1;
			return;
		}
		echo "-2";
	}

	public function sendstatus(){

		$msg=trim(rf("msg",""));	
		$is_follow=rf('is_follow','0');
		$is_pic=rf('is_pic','0');
		$qtype=readqtype();
		$this->_sendstatus($qtype,$is_follow,$is_pic,$msg,"daren");
	}

	private function _sendstatus($qtype,$is_follow,$is_pic,$msg,$zstype) {

		global $timestamp;
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}
		if(!$msg) {
			$msg="#我太有才了#，你们来挑战我吧！";
		}

		$urlb= URLBASE ."?lfrom=".$account["lfrom"]."&retuid=".$account['uid'];
		
		$wbmsg=left($msg,275-strlen($urlb)).$urlb;  
		//echo $msg."picurl=" .$picurl; 
		///echo $wbid . $bylfrom .$account['lfrom'];

		$api=$this->getApi();

		$picurl=sreadcookie('daren_zhengshu_url_'.$zstype);
		
		//echo 'upload'.$wbmsg.$picurl;exit;
		if($is_pic && $picurl)
			$api->upload($wbmsg,$picurl);
		else
			$api->update($wbmsg);
	
		if($is_follow) {
			global $apiConfig;
			$byuid=$apiConfig[$account['lfrom']]['orguid'];

			$ret=$api->follow($byuid);
		}

		if($qtype>0) {
			$day=strftime("%y%m%d",$timestamp);

			$sql2="select todayjifen from ". dbhelper::tname("daren","tmp_day") ." where winday=$day and qtype=$qtype and uid=".$account['uid'];
			$todayjifen=dbhelper::getvalue($sql2);

			if($todayjifen===false) {
				$sql="insert into ". dbhelper::tname("daren","tmp_day") ." set uid=".$account['uid'].",qtype=$qtype,winday=$day,todayjifen=100";
				$sql.=";;;update ". dbhelper::tname("daren","daren") ." set jifen=jifen+2,alljifen=alljifen+2,lasttime=$timestamp where uid=".$account['uid'];
				dbhelper::exesqls($sql);
				echo 102;
				return;
			}
			if(!$todayjifen)$todayjifen=0;				
				
			if(intval($todayjifen) < 100) {
				$sql="update ". dbhelper::tname("daren","tmp_day") ." set todayjifen=todayjifen+100 where  winday=$day and qtype=$qtype and uid=".$account['uid'];
				$sql.=";;;update ". dbhelper::tname("daren","daren") ." set jifen=jifen+2,alljifen=alljifen+2,lasttime=$timestamp where uid=".$account['uid'];
				dbhelper::exesqls($sql);
				echo 102;
				return;
			}
		}

		echo "1";
	}
	
	private function readQuestion() {
		global $question;
		$qtype=readqtype();
		$qtypearr=readqtypelist();
		
		$timestamp=gettimestamp();

		$cache=new CACHE();		
		$question=$cache->get("daren_question_".$qtype); 
		if(1==0 && is_array($question)) {
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
				$sql="select * from (select * from ". dbhelper::tname("daren","tmb") ."  where flag<>3 and qtype=0 order by rand() limit 0,4 ) a
				union select * from (select * from ". dbhelper::tname("daren","tmb") ."  where flag<>3 and qtype=1 order by rand() limit 0,3 ) b
				union select * from (select * from ". dbhelper::tname("daren","tmb") ."  where flag<>3 and qtype=2 order by rand() limit 0,6 ) c";
			}else {//echo $qtype;print_r($qtypearr);print_r( $qtypearr[$qtype]);
				if(strpos($qtypearr[$qtype][1],',')>-1)
					$sql=" select * from ". dbhelper::tname("daren","tmb") ."  where flag<>3 and kindid in (".$qtypearr[$qtype][1].") order by rand() limit 0,15";
				else
					$sql=" select * from ". dbhelper::tname("daren","tmb") ."  where flag<>3 and kindid=".$qtypearr[$qtype][1]." order by rand() limit 0,15";
				///echo 'question sql==='.$sql;
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
					trim($row['tmda1']),0,
					trim($row['tmda2']),0,
					trim($row['tmda3']),0,
					trim($row['tmda4']),0
				);
			$arr[$idx*2+1]=MINUTEPER;
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
		
		$day=strftime("%y%m%d",$timestamp);
		$givejifen=$givejifen2=0;
		$ret=envhelper::readRet();

		$sql2="select todayjifen from ". dbhelper::tname("daren","tmp_day") ." where winday=$day and qtype=$qtype and uid=".$account['uid'];
		$todayjifen=dbhelper::getvalue($sql2);
		if(!$todayjifen) $todayjifen=0;

		$sqlu="lasttime=$timestamp,followers=".$account['followers'].",followings=".$account['followings'].",tweets=".$account['tweets'].",retuid='".$ret['retuid'] ."'";
		$lasttime=0;
		$sql="select * from ". dbhelper::tname("daren","daren_qtype") ." where qtype=$qtype and uid=" . $account["uid"];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$last=$row['lasttime'];
			$wins=0;$jifen=0;
			if($darenvalue == -1 ){
				$testCount=1;
				$filish=0;
			}
			else {
				$testCount=0;
				$filish=1;


				$givejifen=floor($darenvalue / 30);

				if($darenvalue>=FULLMINUTE) {
					$sql="select id from ". dbhelper::tname("daren","log") ." where  uid=" . $account["uid"]." and qtype=$qtype and score>=".FULLMINUTE." and  ( lasttime > UNIX_TIMESTAMP(FROM_DAYS(TO_DAYS(now())))) limit 0,1";
					$rs=dbhelper::getrs($sql);
					if($row=$rs->next()){
						$wins=0;
						if($todayjifen % 100 +$givejifen < TODAYMAXJIFEN) 
							$givejifen2=$givejifen;
						else
							$givejifen2=$givejifen=TODAYMAXJIFEN-($todayjifen % 100);
					}
					else {
						$wins=1;
						$givejifen=30;
					}
				}
				elseif($todayjifen % 100 + $givejifen < TODAYMAXJIFEN) 
					$givejifen2=$givejifen;
				else
					$givejifen2=$givejifen=TODAYMAXJIFEN- ($todayjifen % 100);
			}

			$sql="update ". dbhelper::tname("daren","daren_qtype") ." set  testCount=testCount+$testCount,wincount=wincount+$wins,filishcount=filishcount+$filish,lasttime=$timestamp  where  qtype=$qtype and uid=" . $account["uid"];
			$sql .=";;;update ". dbhelper::tname("daren","daren") ." set  testCount=testCount+$testCount,jifen=jifen+$givejifen,alljifen=alljifen+$givejifen,wincount=wincount+$wins,filishcount=filishcount+$filish,lasttime=$timestamp  where  uid=" . $account["uid"];
			
			if(SAVELOG && $darenvalue > -1 ) {
				$sql .=";;;update ". dbhelper::tname("daren","log") ." set score=". $darenvalue. ",useTime=".$useTime.",$sqlu
				where uid=".$account['uid']." and lasttime=".$last;
			}
			else{
				$sql .=";;;insert into ". dbhelper::tname("daren","log") ." set qtype=$qtype,score=". $darenvalue. ",$sqlu,uid=" . $account['uid'];
			}
		}
		else{
			$sql ="insert into ". dbhelper::tname("daren","daren_qtype") ." set qtype=$qtype,testCount=1,regtime=$timestamp,lasttime=$timestamp,uid=" . $account['uid'];
			
			if(dbhelper::getvalue("select uid from ". dbhelper::tname("daren","daren") ." where uid=" . $account['uid']))
				$sql .=";;;update ". dbhelper::tname("daren","daren") ." set testCount=testCount+1,regtime=$timestamp,lasttime=$timestamp where uid=" . $account['uid'];
			else
				$sql .=";;;insert into ". dbhelper::tname("daren","daren") ." set testCount=testCount+1,regtime=$timestamp,lasttime=$timestamp,uid=" . $account['uid'];

			$sql .=";;;insert into ". dbhelper::tname("daren","log") ." set qtype=$qtype,score=". $darenvalue. ",useTime=".$useTime.",$sqlu,uid=" . $account['uid'];
		}
//echo $sql;//exit;
		dbhelper::exesqls($sql);

		if($darenvalue != -1 ){
			$todayjifen += $givejifen2;

			$sql="delete from ". dbhelper::tname("daren","tmp_day") ." where winday=$day  and qtype=$qtype and uid=".$account['uid'];
			$sql.=";;;insert into ". dbhelper::tname("daren","tmp_day") ." (qtype,winday,uid,score,usetime,todayjifen,lasttime)  
				select qtype,$day,uid,score,usetime,$todayjifen,$timestamp from ". dbhelper::tname("daren","log") ." where qtype=$qtype and uid=".$account['uid']." and lasttime >=UNIX_TIMESTAMP( DATE_FORMAT( NOW( ) ,  '%Y-%m-%d' ) )  order by score desc,usetime limit 0,1";
			

			$sql.=";;;delete from ". dbhelper::tname("daren","tmp_day_total") ." where winday=$day  and uid=".$account['uid'];
			$sql.=";;;insert into ". dbhelper::tname("daren","tmp_day_total") ." (winday,uid,score,usetime,lasttime)  
				select $day,uid,sum(score),sum(usetime),$lasttime from ". dbhelper::tname("daren","tmp_day") ." where uid=".$account['uid']." and  winday=
				$day";
				
			//echo $sql."<br/>";
			dbhelper::exesqls($sql);
			$this->_makeTop() ;

		}
		return $givejifen;
	}

	private function _makeTop() {		
		global $timestamp;

		$cache=new CACHE();
		$lastmaketop=$cache->get("daren_lastmaketop"); 

		if(1==1 && $lastmaketop && dateDiff("d",$lastmaketop , $timestamp)==0) {
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

		$cache=new CACHE();
		$lastmaketop=$cache->get("daren_lastmaketop"); 

		if(1==0 && $lastmaketop && dateDiff("d",$lastmaketop , $timestamp)==0) {
				return ;
		}	
		
		$day=date("ymd",strtotime("-1 day"));

		//计算出每个人每科最高得分

		$qtypelist=readqtypelist();
		$sql="delete from ". dbhelper::tname("daren","top_day") ." where winday=$day";
		foreach($qtypelist as $key=>$r) {
			$sql .=";;;insert into ". dbhelper::tname("daren","top_day") ." (qtype,winday,uid,no,score,usetime,regtime,lasttime)  
			select qtype,$day,uid,0,score,usetime,$timestamp,lasttime from ". dbhelper::tname("daren","tmp_day") ." where score>0 and qtype=" . $key . " and winday=$day  order by score  desc,usetime,lasttime limit 0,1";
			
		}
		dbhelper::exesqls($sql);		


		$sql="select uid,qtype from ". dbhelper::tname("daren","top_day") ." where winday=$day";
		$rs=dbhelper::getrs($sql);
		$sql="";
		while($row=$rs->next()) {
			//echo $row['uid'].$row['qtype'].$day.'<br/>';
			$sql.="update ". dbhelper::tname("daren","daren_qtype") ."  set topcount=topcount+1,lasttime=$timestamp where qtype=".$row['qtype']." and uid =".$row['uid'].";;;";
			$sql.="update ". dbhelper::tname("daren","daren") ."  set jifen=jifen+50,alljifen=alljifen+50,topcount=topcount+1,lasttime=$timestamp where uid =".$row['uid'].";;;";
		}
		dbhelper::exesqls($sql);

		$sql="select l.lfrom,l.lfromuid,l.uid,l.name,l.avatar,l.verified,t.* from (select uid,qtype,winday,score,usetime, DATE_FORMAT(regtime ,  '%Y-%m-%d' ) as regtime from ". dbhelper::tname("daren","top_day") ." where winday=$day order by rand()) as t inner join  ". dbhelper::tname("ppt","user") ." as l on t.uid=l.uid order by rand() limit 0,9";
		$arrs=dbhelper::getrows($sql);

		$cache=new CACHE();
		$cache->set("daren_yesterdayniuren",$arrs);
		$cache->set("daren_lastmaketop",$timestamp);
	}
}	
?>