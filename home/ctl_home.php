<?php
if(!defined('ISWBYL')) exit('Access Denied');
class home extends ctl_base
{
	function index(){ // 这里是首页		
		$this->display("home_index");
	}
}	

?>
