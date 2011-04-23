<?php
include_once('oauth_class.php');
include_once('weiboclient_class.php');

class TSinaClient extends weiboclient {
    /** 
     * 构造函数 
     *  
     * @access public 
     * @param mixed $akey 微博开放平台应用APP KEY 
     * @param mixed $skey 微博开放平台应用APP SECRET 
     * @param mixed $accecss_token OAuth认证返回的token 
     * @param mixed $accecss_token_secret OAuth认证返回的token secret 
     * @return void 
     */ 
    function __construct( $akey , $skey , $accecss_token , $accecss_token_secret ) 
    {
        $this->host='http://api.t.sina.com.cn/';
		$this->oauth = new TSinaoAuth( $akey , $skey , $accecss_token , $accecss_token_secret ); 
    } 
}

/** 
 * 新浪微博 OAuth 认证类 
 * 
 * @package sae 
 * @author Easy Chen 
 * @version 1.0 
 */ 
class TSinaoAuth extends oAuth { 
    /** 
     * construct TSina object 
     */ 
    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) { 
        $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1(); 
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret); 
        if (!empty($oauth_token) && !empty($oauth_token_secret)) { 
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret); 
        } else { 
            $this->token = NULL; 
        } 
		
		$this->host="http://api.t.sina.com.cn/";
		
		//parent::__construct($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
    } 

} 





?>