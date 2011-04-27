<?php
class ppt{
	function login($uidarr){
		global $apiConfig;
		$lfromuid=$uidarr['lfromuid'];
		$sql="select uid,name from ".dbhelper::tname("ppt",'login')." where lfromuid='".$lfromuid."' and lfrom='".$uidarr['lfrom']."'";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$uid=$row['uid'];
			$name=$row['name'];	
		}
		
		$timestamp=getTimestamp();
		//如果已经有此帐号，则修改相应的值
		$domain=$apiConfig[$uidarr["lfrom"]]["domain_format"];
		$domain=str_replace("{domain}",$uidarr["domain"],$domain);

		$sqlu="name='". addslashes($uidarr['name'])."',screen_name='". addslashes($uidarr['screen_name'])."',domain='".$domain."',lasttime=$timestamp,logintime=$timestamp,followers=".$uidarr['followers'].",followings=".$uidarr['followings'].",tweets=".$uidarr['tweets'].",tk='".$uidarr['tk']."',sk='".$uidarr['sk']."'";

		if($uid){
			$sql="update ".dbhelper::tname("ppt",'user')." set logins=logins+1,".$sqlu." where uid=$uid";			
		}
		else{ //新用户则要添加相应的记录
			$ret=envhelper::readRet();
			$sql="insert into ".dbhelper::tname("ppt",'login')." set lfrom='".$uidarr['lfrom']."',lfromuid=$lfromuid,name='". addslashes($uidarr['name'])."'";	
			$uid=dbhelper::execute($sql,1);

			$sql="insert into ".dbhelper::tname("ppt",'user')." set uid=$uid,lfrom='".$uidarr['lfrom']."',lfromuid=$lfromuid,logins=1,regtime=$timestamp,".$sqlu.",retuid=".intval($ret['retuid']).",retapp='".addslashes($ret['retapp'])."'";
		}
//echo $sql;exit;
		dbhelper::execute($sql);
		//$uidarr=array('uid'=>$uid,'name'=>($name?$name:$uidarr['name']));
		//$json=serialize($uidarr);//echo $json;exit;
		//$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		//ssetcookie("account",$json,3600*24*30);
		//echo $uid.$sql;
		//exit;
		return $uid;
	}
	
	/*function readUser(){
		$json=sreadcookie('account');//str_replace("\\","",);
		if (!$json)
			return null;

		$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
		$session=unserialize($json);
		return $session;
	}*/

}
