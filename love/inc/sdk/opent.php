<?php
/**
 * 寮€鏀惧钩鍙伴壌鏉冪被
 * @param 
 * @return
 * @author tuguska
 */
require_once ROOT."./inc/sdk/oauth.php";

class MBOpenTOAuth {
	public $host = 'http://open.t.qq.com/';
	public $timeout = 30; 
	public $connectTimeout = 30;
	public $sslVerifypeer = FALSE; 
	public $format = MB_RETURN_FORMAT;
	public $decodeJson = TRUE; 
	public $httpInfo; 
	public $userAgent = 'oauth test'; 
	public $decode_json = FALSE; 

    function accessTokenURL()  { return 'https://open.t.qq.com/cgi-bin/access_token'; } 
    function authenticateURL() { return 'http://open.t.qq.com/cgi-bin/authenticate'; } 
    function authorizeURL()    { return 'http://open.t.qq.com/cgi-bin/authorize'; } 
	function requestTokenURL() { return 'https://open.t.qq.com/cgi-bin/request_token'; } 

	function lastStatusCode() { return $this->http_status; } 

    function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) { 
        $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1(); 
        $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret); 
        if (!empty($oauth_token) && !empty($oauth_token_secret)) { 
            $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret); 
        } else { 
            $this->token = NULL; 
        } 
	}

    /** 
     * oauth鎺堟潈涔嬪悗鐨勫洖璋冮〉闈?
	 * 杩斿洖鍖呭惈 oauth_token 鍜宱auth_token_secret鐨刱ey/value鏁扮粍
     */ 
    function getRequestToken($oauth_callback = NULL) { 
        $parameters = array(); 
        if (!empty($oauth_callback)) { 
            $parameters['oauth_callback'] = $oauth_callback; 
        }  

        $request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters); 
		$token = OAuthUtil::parse_parameters($request); 
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']); 
        return $token; 
    } 

    /** 
     * 鑾峰彇鎺堟潈url
     * @return string 
     */ 
    function getAuthorizeURL($token, $signInWithWeibo = TRUE , $url='') { 
        if (is_array($token)) { 
            $token = $token['oauth_token']; 
        } 
        if (empty($signInWithWeibo)) { 
            return $this->authorizeURL() . "?oauth_token={$token}"; 
        } else { 
            return $this->authenticateURL() . "?oauth_token={$token}"; 
        } 
	} 	

    /** 
	* 浜ゆ崲鎺堟潈
	* Exchange the request token and secret for an access token and 
     * secret, to sign API calls. 
     * 
     * @return array array("oauth_token" => the access token, 
     *                "oauth_token_secret" => the access secret) 
     */ 
    function getAccessToken($oauth_verifier = FALSE, $oauth_token = false) { 
        $parameters = array(); 
        if (!empty($oauth_verifier)) { 
            $parameters['oauth_verifier'] = $oauth_verifier; 
        } 
		$request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request); 
        $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']); 
        return $token; 
	} 

	function jsonDecode($response, $assoc=true)	{
		$response = preg_replace('/[^\x20-\xff]*/', "", $response);	
		$jsonArr = json_decode($response, $assoc);
		if(!is_array($jsonArr))
		{
			throw new Exception('鏍煎紡閿欒!');
		}
		$ret = $jsonArr["ret"];
		$msg = $jsonArr["msg"];
		/**
		 *Ret=0 鎴愬姛杩斿洖
		 *Ret=1 鍙傛暟閿欒
		 *Ret=2 棰戠巼鍙楅檺
		 *Ret=3 閴存潈澶辫触
		 *Ret=4 鏈嶅姟鍣ㄥ唴閮ㄩ敊璇?
		 */
		switch ($ret) {
			case 0:
				return $jsonArr;;
				break;
			case 1:
				throw new Exception('鍙傛暟閿欒!');
				break;
			case 2:
				throw new Exception('棰戠巼鍙楅檺!');
				break;
			case 3:
				throw new Exception('閴存潈澶辫触!');
				break;
			default:
				$errcode = $jsonArr["errcode"];
				if(isset($errcode))			//缁熶竴鎻愮ず鍙戣〃澶辫触
				{
					throw new Exception("鍙戣〃澶辫触");
					break;
					//require_once MB_COMM_DIR.'/api_errcode.class.php';
					//$msg = ApiErrCode::getMsg($errcode);
				}
				throw new Exception('鏈嶅姟鍣ㄥ唴閮ㄩ敊璇?');
				break;
		}
	}
	
    /** 
     * 閲嶆柊灏佽鐨刧et璇锋眰. 
     * @return mixed 
     */ 
    function get($url, $parameters) { 
		$response = $this->oAuthRequest($url, 'GET', $parameters); 
		if (MB_RETURN_FORMAT === 'json') { 
            return $this->jsonDecode($response, true);
		}
        return $response; 
	}

	 /** 
     * 閲嶆柊灏佽鐨刾ost璇锋眰. 
     * @return mixed 
     */ 
    function post($url, $parameters = array() , $multi = false) { 
        $response = $this->oAuthRequest($url, 'POST', $parameters , $multi ); 
		if (MB_RETURN_FORMAT === 'json') { 
            return $this->jsonDecode($response, true); 
        } 
        return $response; 
	}

	 /** 
     * DELTE wrapper for oAuthReqeust. 
     * @return mixed 
     */ 
    function delete($url, $parameters = array()) { 
        $response = $this->oAuthRequest($url, 'DELETE', $parameters); 
		if (MB_RETURN_FORMAT === 'json') { 
            return $this->jsonDecode($response, true); 
        } 
        return $response; 
    } 

    /** 
     * 鍙戦€佽姹傜殑鍏蜂綋绫?
     * @return string 
     */ 
    function oAuthRequest($url, $method, $parameters , $multi = false) { 
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) { 
            $url = "{$this->host}{$url}.{$this->format}"; 
		}
        $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters); 
		$request->sign_request($this->sha1_method, $this->consumer, $this->token);
        switch ($method) { 
        case 'GET': 
            return $this->http($request->to_url(), 'GET'); 
        default: 
            return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata($multi) , $multi ); 
        } 
	}     

	function http($url, $method, $postfields = NULL , $multi = false){
		//$https = 0;
		//鍒ゆ柇鏄惁鏄痟ttps璇锋眰
		if(strrpos($url, 'https://')===0){
			$port = 443;
			$version = '1.1';
			$host = 'ssl://'.MB_API_HOST;	
			
		}else{
			$port = 80;	
			$version = '1.0';
			$host = MB_API_HOST;
		}

		$header = "$method $url HTTP/$version\r\n";	
		$header .= "Host: ".MB_API_HOST."\r\n";
		if($multi){
			$header .= "Content-Type: multipart/form-data; boundary=" . OAuthUtil::$boundary . "\r\n";	
		}else{	
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";  
		}
		if(strtolower($method) == 'post' ){
			$header .= "Content-Length: ".strlen($postfields)."\r\n";
			$header .= "Connection: Close\r\n\r\n";  
			$header .= $postfields;
		}else{
			$header .= "Connection: Close\r\n\r\n";  
		}

		$ret = '';
		
		$fp = fsockopen($host,$port,$errno,$errstr,30);

		if(!$fp){
			$error = '寤虹珛sock杩炴帴澶辫触';
			throw new Exception($error);
		}else{
			fwrite ($fp, $header);  
			while (!feof($fp)) {
				$ret .= fgets($fp, 4096);
			}
			fclose($fp);
			if(strrpos($ret,'Transfer-Encoding: chunked')){
				$info = split("\r\n\r\n",$ret);
				$response = split("\r\n",$info[1]);
				$t = array_slice($response,1,-1);

				$returnInfo = implode('',$t);
			}else{
				$response = split("\r\n\r\n",$ret);
				$returnInfo = $response[1];
			}
			//杞垚utf-8缂栫爜
			return iconv("utf-8","utf-8//ignore",$returnInfo);
		}
		
	}
 

	/*
	浣跨敤curl搴撶殑璇锋眰鍑芥暟,鍙互鏍规嵁瀹為檯鎯呭喌浣跨敤
	function http($url, $method, $postfields = NULL , $multi = false){
        $this->http_info = array(); 
        $ci = curl_init(); 
        curl_setopt($ci, CURLOPT_USERAGENT, $this->userAgent); 
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout); 
        curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout); 
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->sslVerifypeer); 
        curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader')); 
        curl_setopt($ci, CURLOPT_HEADER, FALSE); 

        switch ($method) { 
        case 'POST': 
            curl_setopt($ci, CURLOPT_POST, TRUE); 
            if (!empty($postfields)) { 
                curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields); 
            } 
            break; 
        case 'DELETE': 
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
            if (!empty($postfields)) { 
                $url = "{$url}?{$postfields}"; 
            } 
        } 

        $header_array = array(); 
        $header_array2=array(); 
        if( $multi ) 
        	$header_array2 = array("Content-Type: multipart/form-data; boundary=" . OAuthUtil::$boundary , "Expect: ");
        foreach($header_array as $k => $v) 
            array_push($header_array2,$k.': '.$v); 

        curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array2 ); 
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE ); 

        curl_setopt($ci, CURLOPT_URL, $url); 

        $response = curl_exec($ci); 
        $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE); 
        $this->http_info = array_merge($this->http_info, curl_getinfo($ci)); 
        $this->url = $url; 
		print_r($response);	
        curl_close ($ci); 
        return $response; 

	}*/
	
    function getHeader($ch, $header) { 
        $i = strpos($header, ':'); 
        if (!empty($i)) { 
            $key = str_replace('-', '_', strtolower(substr($header, 0, $i))); 
            $value = trim(substr($header, $i + 2)); 
            $this->http_header[$key] = $value; 
        } 
        return strlen($header); 
	} 
}
?>
