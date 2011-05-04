<?php
if(ISSAE){
	importlib("sae_template.class");
	importlib("sae_memcache_wrapper1");
}else{
	importlib("template.class");
}

class ctl_base extends template {	
	function init(){

	}
	function set($k,$v){
		$this->assign($k,$v);
	}

	function getApi(){
		$account=getAccount();
		$api="openapi_".$account['lfrom'];
		importlib($api);
		$api=new $api($account['kuid']); 
		
		return $api;
	}
}

?>