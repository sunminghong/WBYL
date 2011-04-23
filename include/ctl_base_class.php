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
}

?>