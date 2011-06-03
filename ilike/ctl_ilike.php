<?php
if(!defined('ISWBYL')) exit('Access Denied');

include_once('fun_common.php');
class ilike extends ctl_base
{
	function index(){ // 这里是首页
			//$this->ready();exit;
		//$account=$this->checkLogin();
		
		$this->set("score",_getScore());
		$this->set("op","index");
		$this->display("ilike_index");
	}
	
	function getscore() {
		return _getScore();
	}

	function next() {
		$this->recRate();
		$req=rq("req",0);
		if($req) { 
			include('fun_getpics.php');
			$json=getshowpic();
			echo $json;
		}
		exit;
	}

	function bury() {
		global $timestamp;

		$id=intval(rq("rateid",0));
		$sql="update ".dbhelper::tname('ilike','pics')." set bury=bury+1,lasttime=$timestamp where id=$id";
		dbhelper::execute($sql);	
		
		$account=$this->checkLogin(false);
		if(!$account) {
				echo "-1";
				exit;
		}
		$sql="update ".dbhelper::tname('ilike','ilike')." set burycount=burycount+1,lasttime=$timestamp where uid=".$account['id'];
		$sql=";;;insert into ".dbhelper::tname("ilike","log") . " (uid,type,picid,score,lasttime) select ".$account['uid'].",3,$id,0,$timestamp ";
		dbhelper::exesqls($sql);	
		echo '1';
	}
	
	function ijoin() {
			global $apiConfig;
		$account=$this->checkLogin(false);
		if(!$account) {
				echo "<script>parent.upload_return({'success':-1,msg:'请先登录！'});</script>";
				exit;
		}
		$uid=$account['uid'];

		$api=$this->getApi();

		$iffollow=rf("iffollow",0);
		if($iffollow) {		
			//关注官方的处理
			$byuid=$apiConfig[$account['lfrom']]['orguid'];
		
			$ret=$api->follow($byuid); 

		}

		$msg=rf("content","");
		if(strlen($msg)<10) {
			$msg = "我刚上传了一张近照，欢迎你来#看看我的范儿#！";
		}
		
		$up=array();
		$up['uid']=$uid;
		$up['msg']=$msg;

		$id=$this->getnewid($up);

		$wbmsg = $msg . URLBASE ."?lfrom=".$account["lfrom"]."&retuid=".$account['uid']."&retapp=faner#{$id}";
		//echo $wbmsg;

		importlib("upload_class");
		$upload=new upload();
		$file=$upload->do_upload();
//		$file=$upload->make_thumb($srcfile, $tofile, $toWidth = 100, $toHeight = 100, $stretch = false);
		//echo 'file='.$file;
		if(strlen($file)>5) {
			$upl=$api->upload($wbmsg,$file);
			if(is_array($upl) && $upl['big_pic']) { 
				$up=array_merge($up,$upl);
				$id=$this->savepic($id,$up);
				$sql="select p.*,l.sex,l.name,l.lfrom,l.lfromuid,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid  where p.id=$id ";
				$rs=dbhelper::getrs($sql);
				if ($row=$rs->next()) {

					$row['avg']= $row['byratecount']?round($row['score'] / $row['byratecount'],2): 0;
					echo "<script>parent.upload_return({'success':1,curr:" .json_encode($row). "})</script>";
				}
				else {
					echo "<script>parent.upload_return({'success':-2,msg:'微博太火了，服务器忙不过来了，你再传看看吧！'})</script>";
				}
			}else {
				echo "<script>parent.upload_return({'success':-2,msg:'微博太火了，服务器忙不过来了，你再传看看吧！'})</script>";
			}
			exit;
		}
		else {
			$api->update($wbmsg);
			$this->delnewid($id);
			echo "<script>parent.upload_return({'success':-3,msg:'已经同步到你的微博，但你没有上传图片哟！'})</script>";
			exit;
		}

		exit;
	}

	public function timelist(){
		$top=rq("mo",0);
		$last=rq("last",0);
		if($last==0)
			$top=15;
		else
			$top=5;

		$testlist=array();
		$sql="select iq.id,iq.type,iq.score,iq.lasttime,l.uid,l.name,l.lfrom from ". dbhelper::tname("ilike","log") . " iq  inner join ".dbhelper::tname("ppt","login")." l on iq.uid=l.uid  where  iq.lasttime>{$last} order by iq.lasttime desc limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$i=0;
		while($row=$rs->next()){
			$i++;
			$row["i"]=$i;
			$row['testtime']= date("H:i",$row['lasttime']);

			$testlist[]=$row;			
		}
		echo json_encode($testlist);
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
				$sql ="select id from ".dbhelper::tname("ilike","log") . "  where picid=$id and type=5 and uid=".$account['uid'];
				$rs=dbhelper::getrs($sql);
				if(!($row=$rs->next())) {
					$sql="update ".dbhelper::tname("ilike","ilike")." set followcount=followcount+1,lasttime=$timestamp where uid=".$account['uid'];
					$sql.=";;;update ".dbhelper::tname("ilike","pics")." set byfollowcount=byfollowcount+1,lasttime=$timestamp where id='$id'";
					$sql.=";;;update ".dbhelper::tname("ilike","ilike")."  set byfollowcount=byfollowcount+1,lasttime=$timestamp where uid='$byuid'";
					$sql.=";;;insert into ".dbhelper::tname("ilike","log") . " (uid,type,picid,score,lasttime) select ".$account['uid'].",5,$id,0,$timestamp ";
					dbhelper::exesqls($sql);
				}
				echo "1";
			}else {
				echo "-2";
			}
			return ;
		}

		//关注官方的处理
		global $apiConfig;
		$byuid=$apiConfig[$account['lfrom']]['orguid'];

		$ret=$api->follow($byuid);
		echo '1';

	}
	public function sendshare(){
		global $timestamp;
		$account=getAccount();
		if(!is_array($account)){
			echo '-1';exit;
		}

		$id=rf("id",'0');
		$msg=rf("msg","");	
		$wbid=rf("wbid","0");
		$is_follow=rf('is_follow','0');

		$is_comment=rf("is_comment","0");
		$picurl=rf("picurl","");
		
		$byuid=rf('byuid','');
		$bylfrom=rf('bylfrom','');
		$bylfromuid=rf('bylfromuid','');

		if(!$msg) {
			$msg="咱啥也不说了，上图！想看看更多图，请#看看我的范儿#。";
		}

		if($id)  $msg.= URLBASE ."?lfrom=".$account["lfrom"]."&retapp=faner&retuid=".$account['uid']."#{$id}";
		//echo $msg."picurl=" .$picurl; 
		///echo $wbid . $bylfrom .$account['lfrom'];

		$api=$this->getApi();

		if($bylfrom==$account['lfrom'] && $wbid) {
			echo '2=repost'.$is_comment;
			$rel=$api->repost($wbid,$msg,$is_comment);
			echo $rel;
			if(!$rel) {
				$api->upload($msg,$picurl);				
			}
		}
		elseif (strlen($picurl)>5) {
			echo '3=upload';
			$api->upload($msg,$picurl);
		}
		else{
			echo '4=update';
			$api->update($msg);
		}
		$sql="update ".dbhelper::tname("ilike","ilike")." set sharecount=sharecount+1,lasttime=$timestamp where uid=".$account['uid'];
		$sql.=";;;update ".dbhelper::tname("ilike","pics")." set bysharecount=bysharecount+1,lasttime=$timestamp where id='$id'";
		$sql.=";;;update ".dbhelper::tname("ilike","ilike")."  set bysharecount=bysharecount+1,lasttime=$timestamp where uid='$byuid'";
		$sql.=";;;insert into ".dbhelper::tname("ilike","log") . " (uid,type,picid,score,lasttime) select ".$account['uid'].",4,$id,0,$timestamp ";

		dbhelper::exesqls($sql);

		if($bylfrom==$account['lfrom'] && $is_follow) {//echo '5='.$bylfromuid;
			$ret=$api->follow($bylfromuid); // done	
		}
		echo "1";
	}

	private function savepic($id,$up) {
		global $timestamp;

		$account=getAccount();
		$uid=$account['uid'];
		$type=2;	
		if(!$up['big_pic']) return 0;

		$up['regtime']=$timestamp;
		$up['lasttime']=$timestamp;
		$up['sortid'] =$sortid;

		dbhelper::update($up, $id, dbhelper::tname("ilike","pics"), 'id');

		$sql="select * from ".dbhelper::tname("ilike","ilike")." where uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next())
			$sql="update ".dbhelper::tname("ilike","ilike")." set piccount=piccount+1,lasttime=$timestamp where uid=$uid";
		else
			$sql="insert into ".dbhelper::tname("ilike","ilike")." set piccount=piccount+1,lasttime=$timestamp,regtime=$timestamp,uid=$uid";
		dbhelper::execute($sql);

		$sql="insert into ".dbhelper::tname("ilike","log") . " (uid,type,picid,score,lasttime) select $uid,$type,$id,0,$timestamp ";
		dbhelper::execute($sql);
		return $id;
	}
	
	private function getnewid($up) {
		$id=dbhelper::update($up, $id = '', dbhelper::tname("ilike","pics"), 'id');		
		return $id;
	}	
	private function delnewid($id) {
		dbhelper::execute("delete from ". dbhelper::tname("ilike","pics") ." where id=$id");		
	}

	private function recRate() {
		global $timestamp;
		
		$account=getAccount();
		if(!$account) return;
		
		$uid=$account['uid'];

		$score=rq("score",0);
		$rateid=intval(rq("rateid",0));
		
		if(!$score || !$rateid) 
			return ;


			$ratelog=sreadcookie("ilike_ratelog");
			if(!is_array($ratelog)) 
				$ratelog=array();
			
			if(in_array($rateid,$ratelog)) return false;	

			$type=1;
			
			$sql="update ".dbhelper::tname("ilike","ilike")." set ratecount=ratecount+1,ratescore=ratescore+$score,lasttime=$timestamp where uid=$uid";
	
			$sql .= ";;;update ".dbhelper::tname("ilike","ilike")." set byratecount=byratecount+1,score=score+$score,lasttime=$timestamp where uid=(select uid from ".dbhelper::tname("ilike","pics") . " where id=$rateid)";

			$sql .=";;;update ".dbhelper::tname("ilike","pics") . " set score=score+$score,byratecount=byratecount+1,lasttime=$timestamp where id=$rateid and regtime>0 and big_pic<>''";
			
			$sql .=";;;insert into ".dbhelper::tname("ilike","log") . " (uid,type,picid,score,lasttime) select $uid,$type,$rateid,$score,$timestamp ";

			dbhelper::exesqls($sql);  // echo $sql;

			if (count($ratelog)>=10)
				unset($ratelog[0]);

			$ratelog[]=$rateid;
			ssetcookie("ilike_ratelog",$ratelog);		
			return true;

	}
}	


?>