<?php
if(ISSAE){
	importlib("sae_template.class");
	importlib("sae_memcache_wrapper1");
}else{
	importlib("template.class");
}

class ctl_base extends template {	
	function init(){
//		$this->assign("app",$app);
//		$this->assign("act",$act);
//		$this->assign("op",$op);

//		$this->assign('uid', $uid);
//		$this->assign('username',$username);
//		$this->assign('nickname',$nickname);
//
//		$this->assign("accounts",envhelper::readToken());
	}
	function set($k,$v){
		$this->assign($k,$v);
	}
}

?>