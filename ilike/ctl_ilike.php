<?php
if(!defined('ISWBYL')) exit('Access Denied');

class ilike extends ctl_base
{
	function index(){ // 这里是首页
			//$this->ready();exit;
		//$account=$this->checkLogin();
		
		$this->set("score",$this->_getScore());
		$this->set("op","index");
		$this->display("ilike_index");
	}
	
	function getscore() {
		return $this->_getScore();
	}

	function next() {
		$this->recRate();
		$json=$this->getshowpic();
		
		echo $json;
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
		$account=$this->checkLogin(false);
		if(!$account) {
				echo "<script>parent.upload_return({'success':-1,msg:'请先登录！'});</script>";
				exit;
		}
		$uid=$account['uid'];

		$api=$this->getApi();

		$iffollow=rf("iffollow",0);
		if($iffollow) {
			$ret=$api->follow($uid); 
		}

		$msg=rf("content","");
		if(strlen($msg)<10) {
			$msg = "我刚上传了一张近照，欢迎你们#看看我的范儿#！";
		}
		
		$up=array();
		$up['uid']=$uid;
		$up['msg']=$msg;

		$id=$this->getnewid($up);

		$wbmsg = $msg . URLBASE ."?retid={$id}&lfrom=".$account["lfrom"]."&retuid=".$account['uid']."&retapp=photo";
		//echo $wbmsg;

		importlib("upload_class");
		$upload=new upload();
		$file=$upload->do_upload();

		//echo 'file='.$file;
		if(strlen($file)>5) {
			$upl=$api->upload($wbmsg,$file);
			if(is_array($upl) && !$upl['big_pic']) { 
				$up=array_merge($up,$upl);
				$id=$this->savepic($id,$up);
				
				echo "<script>parent.upload_return({'success':1,curr:" .json_encode($up). "})</script>";
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

		//echo '<script>parent.upload_return({"success":1,curr:{"uid":null,"msg":"\u6d4b\u8bd5\u53d1\u7bc7\u5fae\u535a\uff0c\u6253\u6270\u5927\u5bb6\uff0c\u4e0d\u597d\u610f\u601d\uff0c\u9a6c\u4e0a\u5220\u9664\uff01","wbid":10983444359,"small_pic":"http:\/\/ww2.sinaimg.cn\/thumbnail\/682c5fd7jw1dhezuni6tsj.jpg","middle_pic":"http:\/\/ww2.sinaimg.cn\/bmiddle\/682c5fd7jw1dhezuni6tsj.jpg","big_pic":"http:\/\/ww2.sinaimg.cn\/large\/682c5fd7jw1dhezuni6tsj.jpg"}});</script>';
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
		$sql="replace into ".dbhelper::tname("ilike","ilike")." set piccount=piccount+1,lasttime=$timestamp,uid=$uid";
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

	public function tuijian() {
		global $timestamp;
		$picid=rq('rateid',0);
		if(!$picid) {
			echo "-2";
			return;
		}
		//推荐三天
		$lefttime=$timestamp+3600*24*1;
		$sql="replace into set picid=$picid,$regtime=$timestamp,$lefttime=$lefttime";
		dbhelper::execute($sql);

		$cache=new Cache();
		$cache->set("ilike_idarray",false);

		echo '1';
	}

	private function getTuijian() {
		$cache=new Cache();

		$idarr=$cache->get("ilike_idarray");
		if(!is_array($idarr)) {
			$sql="select picid from ".dbhelper::tname("ilike","tujian")." where lefttime>lasttime order by lefttime limit 0,10";
			$rs=dbhelper::getrs($sql);
			$idarr=array();
			while($row=$rs->next()) {
				$idarr[]=$row['picid'];
			}
			if(count($idarr)==0) {
				return 0;
			}
			else {
				$cache->set("ilike_idarray",$idarr);
			}
		}

		$randid= $timestamp % count($idarr);
		return $idarr[$randid];
	}
	private function getshowpic() {

		$sortid=0;
		$score=intval(rq("score",0));
		$id=intval(rq("rateid",0));
		$sex=intval(rq("sex",0));
		if($sex>2)$sex=0;

		$isfirst=rq('f','');
		if(!$id && !$isfirst) {
			$id=$this->getTuijian();
		}

		$curr=$next=$up=$top=0;
		
		$swhere="where  p.bury<4 and p.lasttime<>0 ";
		if($sex!=0) $swhere=" and l.sex=$sex ";
		$up=sreadcookie('ilike_up');
		$curr=sreadcookie("ilike_curr");
		$next=sreadcookie("ilike_next");
		if(is_array($up)) $sortid=$up['sortid'];

		if ($id && !$score) {
			$sql="select p.*,l.sex,l.name,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid  where p.id=$id";
			$rs=dbhelper::getrs($sql);
			if ($row=$rs->next()) {
				$curr=$row;
				$sortid=$curr['byratecount'];
				$swhere .=" and id<>" . $curr["id"];
			}
		}else {
			if(is_array($curr)) {
				$up=$curr;
				$sortid=$curr['byratecount'];
				$curr=false;
				$swhere .=" and p.id<>" . $up["id"];
			}
		}  

		if (!is_array($curr)) {
			if(is_array($next)) {
				$curr=$next;
				$sortid=$curr['byratecount'];
				$next=false;
				$swhere .=" and p.id<>" . $curr["id"];
			}
			else $top +=1;
		}
		else {
			if(is_array($next) && $next['id']==$curr['id']){				
				$sortid=$curr['byratecount'];
				$next=false;
			}
		}

		if(!is_array($next)) $top += 1;
		if($sortid==0) { //第一张显示最新上传的
			$sql="select p.*,l.sex,l.name,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid $swhere order by id desc limit 0,$top";
		} else {//然后中一张比一张的评价次数多
			$sql="select p.*,l.sex,l.name,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid $swhere and p.byratecount>$sortid  order by p.byratecount limit 0,$top"; 
		} echo "/*1=$sql*/";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		if (!$row) {
			$sortid=0;
			$sql="select p.*,l.sex,l.name,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid $swhere and p.byratecount>=$sortid  order by p.byratecount limit 0,$top"; echo "/*2=$sql*/";
			$rs=dbhelper::getrs($sql);
			$row=$rs->next();
		}
		
		if($top == 1) 
			$next=$row;
		elseif($top==2) {
			$curr=$row;
			if($row=$rs->next()) 
				$next=$row;
		}

		////echo $sql;print_r($next);
		ssetcookie("ilike_up",$up);
		ssetcookie("ilike_curr",$curr);
		ssetcookie("ilike_next",$next);

		//echo 'up=';print_r($up);
		//echo 'curr=';print_r($curr);
		//echo 'next=';print_r($next);

		$account=getAccount();
		if(!is_array($account))
			$login=false;
		else $login=true;
//$up=0;
		return json_encode(array('logined'=>$login,'up'=>$up?$up:0,'curr'=>$curr,'next'=>$next));
	}

	private function _getScore() {
		$account=getAccount();		
		if(!is_array($account)) 
			return false;

		$sql="select score,byratecount,ratecount,piccount,ratescore from ".dbhelper::tname("ilike","ilike")." where uid=".$account['uid'];
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()) {
			return $row;
		}
		return false;
	}

	private function recRate() {
		global $timestamp;

		$score=rq("score",0);
		$rateid=intval(rq("rateid",0));
		
		if($score && $rateid) {
			$ratelog=sreadcookie("ilike_ratelog");
			if(!is_array($ratelog)) 
				$ratelog=array();
			
			if(in_array($rateid,$ratelog)) return false;	

			$account=getAccount();
			$uid=$account['uid'];
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
}	


?>