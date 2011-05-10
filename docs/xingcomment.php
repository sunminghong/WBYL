<?php
header("Content-type: text/html;charset=gb2312");
require_once("../include/common.inc.php");
if(!empty($_POST["op"]))
{
	$aid=intval($aid);
	$ip=GetIP();
	$dtime=time();
	$face='http://www.265g.com/templets/common/images/pnuser.gif';
	if($op=="addcomment")
	{
		$msg=inputFilter(iconv('utf-8','gbk',$msg));
		$chk=checkComment($msg);
		if(!empty($chk))
		{
			echo $chk;
			exit;
		}
		$username=inputFilter(iconv('utf-8','gbk',$username));
		if(empty($username))
		{
			$username="265G玩家";
		}
		$arctitle=inputFilter(iconv('utf-8','gbk',$arctitle));		if(empty($pic))		{			$sql="insert into #@__feedback(aid,username,arctitle,msg,face,ip,dtime) values($aid,'$username','$arctitle','$msg','$face','$ip','$dtime')";		}		else		{			$pic=inputFilter(iconv('utf-8','gbk',$pic));			$sql="insert into #@__feedback(aid,username,arctitle,msg,face,ip,dtime,pic) values($aid,'$username','$arctitle','$msg','$face','$ip','$dtime','$pic')";		}				$dsql->ExecuteNoneQuery($sql);
		$sql="update #@__archives set postnum=postnum+1 where id=$aid";
		$dsql->ExecuteNoneQuery($sql);
		echo getXingCommentList($aid);
		exit;
	}
	if($op=="support")
	{
		$id=intval($id);
		$sql="update #@__feedback set support=support+1 where id=$id";
		$dsql->ExecuteNoneQuery($sql);
		echo getXingCommentList($aid);
		exit;
	}
	if($op=="recomment")
	{
		$id=intval($id);
		$msg=inputFilter(iconv('utf-8','gbk',$msg));
		$username="265G玩家";
		$arctitle=inputFilter(iconv('utf-8','gbk',$arctitle));
		$sql="select username,msg from #@__feedback where id=$id";
		$row=$dsql->getOne($sql);
		$remsg=$row["msg"];
		$reusername=$row["username"];
if(!empty($floor))
		{
			$reusername='引用'.intval($floor).'楼 ('.$reusername.')';
		}
		$sql="insert into #@__feedback(aid,reusername,username,arctitle,remsg,msg,face,ip,dtime) values($aid,'$reusername','$username','$arctitle','$remsg','$msg','$face','$ip','$dtime')";
		$dsql->ExecuteNoneQuery($sql);
		$sql="update #@__archives set postnum=postnum+1 where id=$aid";
		$dsql->ExecuteNoneQuery($sql);
		echo getXingCommentList($aid);
		exit;
	}
	if($op=="showlist")
	{
		echo getXingCommentList($aid,$page);
		exit;
	}
	if($op=="xq")
	{
		$xqnum=intval($xqnum);
		$sql="select xqnum from #@__archives where id=$aid";
		$row=$dsql->getOne($sql);
		$arr=explode(',',$row["xqnum"]);
		for($i=0;$i<count($arr);$i++)
		{
			if($i==$xqnum)
			{
				$arr[$i]=intval($arr[$i])+1;
			}
		}
		$str='';
		for($i=0;$i<count($arr);$i++)
		{
			$str.=$arr[$i].',';
		}
		$str=substr($str,0,strlen($str)-1);
		$sql="update #@__archives set xqnum='$str' where id=$aid";
		$dsql->ExecuteNoneQuery($sql);
		echo getArticleXq($aid);
		exit;
	}
}

function getXingCommentList($id,$page=1,$type=0)
{
	global $dsql;
	$page=intval($page);
	$html='';
	$ids='';
	/*if($page==1)
	{
		$sql="select * from #@__feedback where aid=$id and support>1000 and type=$type order by support desc limit 0,3";
		$dsql->Execute('me',$sql);
		while($row=$dsql->GetArray())
		{
			if(!empty($ids))
			{
				$ids.=',';
			}
			$ids.=$row["id"];
			$hotlist[]=$row;
		}
		if($hotlist)
		{
			$html.='<dl class="hot_comm"><dt>热门评论</dt>';
			foreach($hotlist as $value)
			{
				$html.='<dd>';
				$html.='<div class="userface"><a href="javascript:void(0)"><img src="'.$value["face"].'" width="48" height="48" />'.$value					
					["username"].'</a></div>';
				$html.='<div class="commcont">'.$value["msg"].'</div>';
				$html.='<div class="commcont_oth"><span>'.date('m-d H:i',$value["dtime"]).'</span><span><a href="javascript:support('.$value["id"].')">支持</a>						
						['.$value["support"].']</span><span><a href="javascript:void(0)" onclick="back(this,'.$value["id"].')">回复</a></span></div>';
				$html.='</dd>';
			}
			$html.='</dl>';
		}
	}*/
	//if($hotlist)
	//{
	//$sql="select * from #@__feedback where aid=$id and id not in($ids) and type=$type order by id desc limit ".(($page-1)*10).",10";	
	//}
	//else
	//{
	$sql="select * from #@__feedback where aid=$id and type=$type order by id desc limit ".(($page-1)*10).",10";	
	//}
	$dsql->Execute('me',$sql);
	while($row=$dsql->GetArray())
	{
		$list[]=$row;
	}
	if($list)
	{
		$ip=GetIP();
		$html.='<dl><dt>最新评论</dt>';
		$sql="select count(*) as count from #@__feedback where aid=$id and type=$type";
		$row=$dsql->getOne($sql);
		$count=$row["count"];
		foreach($list as $key=>$value)
		{
			$floor=$count-($key+($page-1)*10)-1;
			$html.='<dd><div class="userface"><a href="javascript:void(0)"><img src="'.$value["face"].'" width="48" height="48" />'.$value["username"].'</a><i 					
					class="gray">'.($floor+1).'楼</i></div>';
			$html.='<div class="commcont">';
			if(!empty($value["remsg"]))
			{
				$html.='<div class="re_comm"><div><a href="javascript:void(0)">'.$value["reusername"].'</a></div><div>'.$value["remsg"].'</div></div>';
			}
			if(!empty($value["pic"]))
			{
				if($value["ischeck"]||$ip==$value["ip"])
				{
					$html.='<img src="'.$value["pic"].'"><br/>';
				}
				else
				{
					$html.='<font color="red">图片正在审核中</font><br/>';
				}
			}
			$html.=$value["msg"].'</div><div class="commcont_oth"><span>'.date('m-d H:i',$value["dtime"]).'</span><span><a href="javascript:support('.$value				
				["id"].')">支持</a>['.$value["support"].']</span><span><a href="javascript:void(0)" onclick="back(this,'.$value["id"].','.($floor+1).')">回复</a></span>';
			$html.='</div></dd>';
		}
		$html.='</dl>'.getPageNav($page,$count,10);
	}
	return $html;
}

function getArticleXq($id)
{
	global $dsql;
	$sql="select xqnum from #@__archives where id=$id";
	$row = $dsql->GetOne($sql);
	$xqnum=explode(',',$row["xqnum"]);
	$totalnum=0;
	$temp=0;
	$max=0;
	for($i=0;$i<count($xqnum);$i++)
	{
		if($xqnum[$i]>$temp)
		{
			$temp=$xqnum[$i];
			$max=$i;
		}
		$totalnum=$totalnum+floatval($xqnum[$i]);
	}
	$html='<table border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
	for($i=0;$i<count($xqnum);$i++)
	{
		$height=round(floatval($xqnum[$i])/$totalnum*60);
		$html.='<td width="60" height="80" align="center" valign="bottom" scope="col">'.$xqnum[$i].'<br />';
		if($i==$max)
		{
			$html.='<img src="/templets/images/100.gif" width="20" height="'.$height.'" /></td>';
		}
		else
		{
			$html.='<img src="/templets/images/101.gif" width="20" height="'.$height.'" /></td>';
		}
	}
	$html.='</tr><tr>';
	for($i=0;$i<count($xqnum);$i++)
	{
		$html.='<td height="50" align="center">';
		$html.='<img src="/templets/images/'.($i+1).'.gif" width="40" height="40" />';
		$html.='</td>';
	}
	$html.='</tr><tr>';
	$arr=array('雷囧','强赞','软文','悲剧','愤怒','搞笑','无聊','期待');
	for($i=0;$i<count($xqnum);$i++)
	{
		$html.='<td align="center">'.$arr[$i].'</td>';
	}
	$html.='</tr><tr>';
	for($i=0;$i<count($xqnum);$i++)
	{
		$html.='<td align="center"><input type="radio" name="mood" value="'.$i.'"/></td>';
	}
	return $html.='</tr></table>';
}
?>