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

		$sqlu="sex='".$uidarr['sex']."',name='". addslashes($uidarr['name'])."',screen_name='". addslashes($uidarr['screen_name'])."',domain='".$domain."',lasttime=$timestamp,logintime=$timestamp,followers=".$uidarr['followers'].",followings=".$uidarr['followings'].",tweets=".$uidarr['tweets'].",location='" .$uidarr['location'] ."',verified='".$uidarr['verified'] . "',avatar='".$uidarr['avatar']."'";
////////,tk='".$uidarr['tk']."',sk='".$uidarr['sk']."'
		if($uid){
			$sql="update ".dbhelper::tname("ppt",'user')." set logins=logins+1,".$sqlu." where uid=$uid";			
		}
		else{ //新用户则要添加相应的记录
			$ret=envhelper::readRet();
			$sql="insert into ".dbhelper::tname("ppt",'login')." set lfrom='".$uidarr['lfrom']."',lfromuid='$lfromuid',name='". addslashes($uidarr['name'])."'";	
			$uid=dbhelper::execute($sql,1);

			$sql="insert into ".dbhelper::tname("ppt",'user')." set uid=$uid,lfrom='".$uidarr['lfrom']."',lfromuid='$lfromuid',logins=1,regtime=$timestamp,".$sqlu.",retuid=".intval($ret['retuid']).",retapp='".addslashes($ret['retapp'])."'";
		}
//echo $sql;exit;
		dbhelper::execute($sql);

		return $uid;
	}
	
	//是否需要同步好友、粉丝信息
	public function snsNeedSync($uid1){
		global $lfrom;
		if($lfrom=="tsina") $lf_pre="";
		else $lf_pre=$lfrom."_";

		$sql="select uid2 from ".dbhelper::tname("ppt",lf_pre.'userlib_sns')." where uid1=".$uid1." limit 0,1";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			if(getTimestamp() % 5==0) {
				return true;
			}
			return true;
		}
		else {
			return true;
		}
	}

	public function syncSNS($api,$type,$uid1=0,$count=100,$cursor=-1){
		global $apiConfig;
		$lfrom=$api->lfrom;
		if($lfrom=='tqq') {
			$count=60;
			if($cursor==-1) $cursor=1;
			$cur2=($cursor-1) * 30; 
		}elseif($lfrom=='tsina') {
			$cur2=$cursor;
		}
	
		if($type==2)
			$f_list=$api->followers($cur2,30);//,$lfromuid);
		else
			$f_list=$api->friends($cur2,30);//,$lfromuid);

		$list=$f_list["users"];
//print_r($list);

		$sqlups="";
		$sqlins="";
		$sqlins2="";
		
		if($lfrom=="tsina") $lf_pre="";
		else $lf_pre=$lfrom."_";

		foreach($list as $uidarr) {
			$lfromuid=$uidarr['lfromuid'];

			$timestamp=getTimestamp();
			//如果已经有此帐号，则修改相应的值
			$domain=$apiConfig[$uidarr["lfrom"]]["domain_format"];
			$domain=str_replace("{domain}",$uidarr["domain"],$domain);

			$sqlu="sex='".$uidarr['sex']."',name='". addslashes($uidarr['name'])."',screen_name='". addslashes($uidarr['screen_name'])."',domain='".$domain."',lasttime=$timestamp,followers='".$uidarr['followers']."',followings='".$uidarr['followings']."',tweets='".$uidarr['tweets']."',location='" .$uidarr['location'] ."',verified='".$uidarr['verified'] . "',avatar='".$uidarr['avatar']."'";

			$sql="select uid,name from ".dbhelper::tname("ppt",'login')." where lfromuid='".$lfromuid."' and lfrom='".$uidarr['lfrom']."'";
			$rs=dbhelper::getrs($sql);
			if($row=$rs->next()){
				$uid=$row['uid'];
				$name=$row['name'];	

				$sqlups .="update ".dbhelper::tname("ppt",'user')." set ".$sqlu." where uid=$uid;;;";
				
				$sqlins .="insert into ".dbhelper::tname("ppt",'user_sns')." set uid1=".$uid1.", uid2=$uid,type=$type;;;";
			}

			$sql="select id from ".dbhelper::tname("ppt",$lf_pre.'userlib')." where lfromuid='".$lfromuid."' and lfrom='".$uidarr['lfrom']."'";
			$rs=dbhelper::getrs($sql);
			if($row=$rs->next()){
				$libid=$row['id'];
				$sql ="update ".dbhelper::tname("ppt",$lf_pre.'userlib')." set ".$sqlu." where id=$libid";
				dbhelper::execute($sql);
			}else{
				$sql="insert into ".dbhelper::tname("ppt",$lf_pre.'userlib')." set  lfromuid='".$lfromuid."' , lfrom='".$uidarr['lfrom']."',".$sqlu;
				$libid=dbhelper::execute($sql,1);
			}
			$sqlins2 .="insert into ".dbhelper::tname("ppt",$lf_pre.'userlib_sns')." set uid1=".$uid1.", uid2=$libid,type=$type;;;";
		}
		//echo 'sqlups='.$sqlups.'\n';
		//echo 'sqlins='.$sqlins.'\n';
		//echo 'sqlins2='.$sqlins2.'\n';

		dbhelper::exesqls($sqlups);
		
		if($cursor==-1) dbhelper::execute("delete from  ".dbhelper::tname("ppt",'user_sns')." where uid1=$uid1 and type=$type");
		dbhelper::exesqls($sqlins);

		if($cursor==-1) dbhelper::execute("delete from  ".dbhelper::tname("ppt",$lf_pre.'userlib_sns')." where uid1=$uid1 and type=$type");
		dbhelper::exesqls($sqlins2);

		if ($f_list["next_cursor"]){
			$cursor=$f_list["next_cursor"]*1;
			if( $cursor!=0 ) {
				if($lfrom=='tqq') {
					$cursor++;
				}elseif($lfrom=='tsina') {
					
				}
				if($cursor<$count){
					//)会循环调用本方法
					$this->syncSNS($api,$type,$uid1,$count,$cursor,$lfromuid);
				}
			}
		}
		
	}
}
