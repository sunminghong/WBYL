<?php
if(ISSAE){
	importlib("sae/sae_memcache_wrapper1");
	importlib("sae/sae_template.class");
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
		$api="sdk_".$account['lfrom']."\openapi_class";
		importlib($api);
		$api=new openapi($account['kuid']); 
		
		return $api;
	}
}

?>