<?php
class envhelper{
	static public function parseKUID($kuid=""){
		if(!$kuid){
			$kuid=rq("kuid","");
			if(!$kuid) return array('lfrom'=>"",'lfromuid'=>"");
		}
		$k=explode('_',$kuid);
				
		return array('lfrom'=>$k[0],'lfromuid'=>$k[1]);		
	}
		
	static public function packKUID($lfrom,$uid){
		$lfrom=strtolower($lfrom);
		if(substr($uid,0,strlen($lfrom))!=$lfrom)
			return $lfrom."_".$uid;	
		else
			return $uid;
	}
	
	static public function clearAccounts(){
		ssetcookie('sess',"");
	}
	static public function saveAccounts($uid,$userinfo){
		$json=sreadcookie('sess');
		if(!$json) $session=array();
		else{
			$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
			
			$session=unserialize($json);
			
			if (is_array($session) && isset($session[$userinfo['lfrom']."_0"])){
				unset($session[$lfrom."_0"]);	
			}
		}
		
		$userinfo['lfromname']=$GLOBALS['apiConfig'][$userinfo['lfrom']]['name'];
		
		$session[$uid]=$userinfo;
	
		$json=serialize($session);//echo $json;exit;
		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('sess', $json,3600*24*100);
	}
	
	/*
		param $uid string 包含lfrom，UID的一个组合键，由envhelper::packUID算出
	*/
	static public function readAccounts($uid=""){
		$json=sreadcookie('sess');//str_replace("\\","",);
		
		if (!$json)
			return null;

		$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
		$session=unserialize($json);

		if($uid=='')return $session;
		
		return $session[$uid];
	}

	//读取推荐数据
	static public function readRet(){
		$retuid=rq("retuid","0");
		if($retuid!=0)
			ssetcookie('retuid',$retuid,3600*24*3);
		else
			$retuid=sreadcookie('retuid');

		$retapp=rq("retapp","");
		if($retapp!="")
			ssetcookie('retapp',$retapp,3600*24*3);
		else
			$retapp=sreadcookie('retapp');

		$ret=array(
			'retuid'=>$retuid,
			'retapp'=>$retapp
			);
		return $ret;
	}
}
