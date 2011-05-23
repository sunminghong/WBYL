<?php
if(!defined('ISWBYL')) exit('Access Denied');

class ilike extends ctl_base
{
	function index(){ // 这里是首页
			//$this->ready();exit;
		//$account=$this->checkLogin();
		
		$this->set("op","index");
		$this->display("ilike_index");
	}

	function next() {
		$this->recRate();
		$json=$this->getshowpic();
		
		echo $json;
		exit;
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
			$msg = "刚在#爱人#上发了一张照片，这个微博是向你们拉票的！";
		}
		
		$up=array();
		$up['uid']=$uid;
		$up['msg']=$msg;

		$id=$this->getnewid($up);

		$wbmsg = $msg . URLBASE ."?retid={$id}&lfrom=".$account["lfrom"]."&retuid=".$account['uid']."&retapp=ilike";
		//echo $wbmsg;

		importlib("upload_class");
		$upload=new upload();
		$file=$upload->do_upload();
		//$file=$upload->make_thumb($file, $file, 1024 , 768, false)

		//echo 'file='.$file;
		if(strlen($file)>5) {
			$upl=$api->upload($wbmsg,$file);
			$up=array_merge($up,$upl);
			if(is_array($upl)) { 
				$this->savepic($id,$up);
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

		$up['regtime']=$timestamp;
		$up['lasttime']=$timestamp;

		dbhelper::update($up, $id, dbhelper::tname("ilike","pics"), 'id');
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

	private function getshowpic() {
		$isfirst=rq('f','');
		if(!$isfirst) {
			$account=getAccount();
			if(!is_array($account)) {
				return '{"logined":false}';
			}
		}

		$score=intval(rq("score",0));
		$id=intval(rq("rateid",0));
		$sex=intval(rq("sex",0));

		$curr=$next=$up=0;
		
		$swhere="";
		if($sex!=0) $swhere=" and l.sex=$sex ";
		$up=sreadcookie('ilike_up');
		$curr=sreadcookie("ilike_curr");
		$next=sreadcookie("ilike_next");

		if ($id && !$score) {
			$sql="select p.*,l.sex,l.name,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid  where p.big_pic<>'' and p.id=$id";
			$rs=dbhelper::getrs($sql);
			if ($row=$rs->next()) {
				$curr=$row;
				$swhere .=" and id<>" . $curr["id"];
			}
		}else {
			if(is_array($curr)) {
				$up=$curr;
				$curr=false;
				$swhere .=" and p.id<>" . $up["id"];
			}
		}  
		////echo $sql;print_r($next);
		if (!is_array($curr)) {
			if(is_array($next)) {
				$curr=$next;
				$next=false;
				$swhere .=" and p.id<>" . $curr["id"];
			}
			else $top +=1;
		}

		if(!is_array($next)) $top += 1;
		if($swhere != '')$swhere = "where  p.big_pic<>''  ". $swhere;
		$sql="select p.*,l.sex,l.name,l.domain,l.followers,l.followings from ".dbhelper::tname("ilike","pics") . " as p inner join ".dbhelper::tname("ppt","user") . "  as l on p.uid=l.uid $swhere order by rand() limit 0,$top"; 
		$rs=dbhelper::getrs($sql);
		if ($row=$rs->next()) {
			if($top == 1) 
				$next=$row;
			else 
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

		return json_encode(array('logined'=>true,'up'=>$up?$up:0,'curr'=>$curr,'next'=>$next));
	}

	private function recRate() {
		global $timestamp;

		$score=rq("score",0);
		$rateid=intval(rq("rateid",0));
		
		if($score && $rateid) {
			//$ratelog=sreadcookie("ilike_ratelog");
			if(!is_array($ratelog)) 
				$ratelog=array();
			
			if(in_array($rateid,$ratelog)) return;	
			
			$account=getAccount();
			$uid=$account['uid'];
			$type=1;	

			$sql="update ".dbhelper::tname("ilike","pics") . " set score=score+$score,rateCount=rateCount+1,lasttime=$timestamp where id=$rateid";
			
			$sql .=";;;insert into ".dbhelper::tname("ilike","log") . " (uid,type,picid,score,lasttime) select $uid,$type,$rateid,$score,$timestamp ";

			dbhelper::exesqls($sql);

			if (count($ratelog)>=3)
				unset($ratelog[0]);

			$ratelog[]=$rateid;
			ssetcookie("ilike_ratelog",$ratelog);			
		}
	}
}	


?>