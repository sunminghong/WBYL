<?php


private function getshowpicv1() {

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