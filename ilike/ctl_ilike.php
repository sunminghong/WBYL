<?php
if(!defined('ISWBYL')) exit('Access Denied');

class ilike extends ctl_base
{
	function index(){ // 这里是首页
			//$this->ready();exit;
		$account=$this->checkLogin();
		
		$this->set("op","index");
		$this->display("ilike_index");
	}

	function next() {
		global $timestamp;
		
		$this->recRate();print_r($this->getshowpic());
		$json=json_encode($this->getshowpic());
		//$this->set("showdata",$json);
		echo $json;
		exit;
	}
	
	function ijoin() {
		$account=$this->checkLogin();
		$content=rf("content","");
		importlib("upload_class");

//		echo $file;
		$api=$this->getApi();
		//'wbid'=>$rel['wbid'],'small_pic'=>$rel['thumbnail_pic'],'middle_pic'=>$rel['bmiddle_pic'],'big_pic'=>$rel['original_pic']
		$up=$api->upload($content,$file);
		//print_r($up);
		$this->savepic($content,$up);
		exit;
	}

	function savepic($msg,$up) {
		global $timestamp;

		//////$sql="select * from ".dbhelper::tname("ilike","pics") . " where wbid='".$up['wbid']."'";
		//////$rs=dbhelper::getrs($sql);
		//////if ($row=$rs->
		$account=getAccount();
		$up['uid']=$account['uid'];
		$up['msg']=$msg;
		$up['regtime']=$timestamp;
		$up['lasttime']=$timestamp;

		$id=dbhelper::update($up, $id = '', dbhelper::tname("ilike","pics"), 'id');
		return $id;
	}

	private function getshowpic() {
		$id=rq("id",-1);
		$curr=$next=$up=array();
		
		$curr=sreadcookie("ilike_curr");
		$next=sreadcookie("ilike_next");
		if(is_array($curr)) {
			$up=$curr;
			$curr=false;
		}
		$sqlup="";
		if ($id!=-1) {
			$sql="select * from ".dbhelper::tname("ilike","pics") . "  where id=$id";
			$rs=dbhelper::getrs($sql);
			if ($row=$rs->next()) {
				$curr=$row;
			}
		}
		if (!is_array($curr)) {
			if(is_array($next)) {
				$curr=$next;
				$next=false;
			}
			else $top +=1;
		}

		if(!is_array($next)) $top += 1;

		$sql="select * from ".dbhelper::tname("ilike","pics") . " order by rand() limit 0,$top";
		$rs=dbhelper::getrs($sql);
		if ($row=$rs->next()) {
			if($top == 1) 
				$next=$row;
			else 
				$curr=$row;
			if($row=$rs->next()) 
				$next=$row;
		}

		ssetcookie("ilike_curr",$curr);
		ssetcookie("ilike_next",$next);

		//echo 'up=';print_r($up);
		//echo 'curr=';print_r($curr);
		//echo 'next=';print_r($next);

		return array('up'=>$up,'curr'=>$curr,'next'=>$next);
	}

	private function recRate() {		
		$score=rq("score",0);
		$rateid=intval(rq("rateid",0));
		
		if($score && $rateid ) {
			$ratelog=sreadcookie("ilike_ratelog");
			if(!is_array($ratelog)) 
				$ratelog=array();
			
			if(in_array($rateid,$ratelog)) return;
			

			$sql="update ".dbhelper::tname("ilike","pics") . " set score=score+$score,rateCount=rateCount+1,lasttime=$timestamp where id=$rateid";
			dbhelper::execute($sql);echo $sql;

			if (count($ratelog)>=3)
				unset($ratelog[0]);

			$ratelog[]=$rateid;
			ssetcookie("ilike_ratelog",$ratelog);
			//echo 'ratelog=';print_r($ratelog);
		}
	}
}	


?>