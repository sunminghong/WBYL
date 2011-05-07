<?php
if(!defined('ISWBYL')) exit('Access Denied');

importlib("zhengshu");
class zs extends ctl_base {
	function geturl(){
		$type=rq('type','iq');
		$lv=rq('lv',0);
		$uid=rq("uid",0);
		
		$fun='get'.$type;
		$zs=zhengshu::$fun($uid,$lv);
		if(is_array($zs))
			echo $zs['zsurl'];
		else
			echo '';
	}
}
?>
