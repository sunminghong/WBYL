<?php
if(!defined('ISWBYL')) exit('Access Denied');

include_once('fun_common.php');
class daytop extends ctl_base
{
	function index(){ // 这里是首页
		$account=$this->checkLogin();
		
		$toplist=_getdaytoplist();
		$tt=$toplist[0]['topday'];
		$tmonth=left($tt,4);
		$tmonth=intval(right($tmonth,2));
		$tday=intval(right($tt,2));
		
		$monname=array('o月','一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月');

		$this->set("score",_getScore());
		$this->set('daytoplist',$toplist);
		$this->set('tmonth',$monname[$tmonth]);
		$this->set('tday',$tday);
		$this->set("op","index");
		$this->display("ilike_daytop");
	}	
}	


?>