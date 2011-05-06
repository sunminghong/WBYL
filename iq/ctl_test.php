<?php
if(!defined('ISWBYL')) exit('Access Denied');

class test extends ctl_base
{
	function testzhengshu(){
		include_once(ROOT.'home/zhengshu.php');
		$account=array("uid"=>8888888,"screen_name"=>"孙铭鸿铭鸿铭鸿","avatar"=>'http://tp4.sinaimg.cn/1747738583/50/1279900646/1');
		$iqscore=array("iqlv"=>6,"top"=>175);
		$zh=zhengshu::makeIQ($account,$iqscore,true);
		echo $zh['isold']."<br/>".$zh["zsurl"].'<br/><img src="'.$zh["zsurl"].'" />';
		exit;
	}
}	


?>