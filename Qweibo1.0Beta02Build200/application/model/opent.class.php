<?php
/**
 * 开放平台鉴权类
 * @param 
 * @return
 * @author hordeliu
 * @package /application/common/
 */
require_once MB_MODEL_DIR.'/oauth.class.php';

class MBOpenTOAuth 
{
	public $host = 'http://open.t.qq.com/';
	public $timeout = 30; 
	public $connectTimeout = 30;
	public $sslVerifypeer = FALSE; 
	public $format = MB_RETURN_FORMAT;
	public $decodeJson = TRUE; 
	public $httpInfo; 
	public $userAgent = ''; 
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
     * Get a request_token from Weibo 
     * oauth授权之后的回调页面 
	 * 返回包含 oauth_token 和oauth_token_secret的key/value数组
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
     * 获取授权url
     * 
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
	* 交换授权
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

	function jsonDecode($response, $assoc=true)
	{
		$response = preg_replace('/[^\x20-\xff]*/', "", $response);			//清除不可见字符
		$jsonArr = json_decode($response, $assoc);
		if(!is_array($jsonArr))
		{
			throw new MBAPIExcep();
		}
		$ret = $jsonArr["ret"];
		$msg = $jsonArr["msg"];
		/**
		 *Ret=0 成功返回
		 *Ret=1 参数错误
		 *Ret=2 频率受限
		 *Ret=3 鉴权失败
		 *Ret=4 服务器内部错误
		 */
		switch ($ret) 
		{
			case 0:
				return $jsonArr;;
				break;
			case 1:
				throw new MBAPIArgErrExcep($msg);
				break;
			case 2:
				throw new MBAPIFreqDenyExcep();
				break;
			case 3:
				throw new MBAPIOAuthExcep();
				break;
			default:
				$errcode = $jsonArr["errcode"];
				if(isset($errcode))			//发表失败
				{
					require_once MB_COMM_DIR.'/api_errcode.class.php';
					$msg = ApiErrCode::getMsg($errcode);
					throw new MBException($msg);
					break;
				}
				throw new MBAPIInnerExcep("$msg");
				break;
		}
	}
	
    /** 
     * 重新封装的get请求. 
     * 
     * @return mixed 
     */ 
    function get($url, $parameters) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		//echo $response;
		if (MB_RETURN_FORMAT === 'json') { 
            return $this->jsonDecode($response, true);
		}
        return $response; 
	}

	 /** 
     * 重新封装的post请求. 
     * 
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
     * 
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
     * 发送请求的具体类
     * 
     * @return string 
     */ 
    function oAuthRequest($url, $method, $parameters , $multi = false) { 
		//echo '<hr>'.$url.'<hr>';
        if (strrpos($url, 'http://') !== 0 && strrpos($url, 'https://') !== 0) { 
            $url = "{$this->host}{$url}.{$this->format}"; 
		}
		/*
		if($multi){
			$tmpValue = $parameters['pic'];
			unset($parameters['pic']);
		}
		var_dump($parameters);
		 */
	   	//unset($parameters['pic']);	
        $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters); 
	   //var_dump($request->parameters);	
		$request->sign_request($this->sha1_method, $this->consumer, $this->token);
		/*
		if($multi){
			$request->parameters['pic'] = $tmpValue;	
		}
		 */
	    //var_dump($request->parameters);	
		//echo $request->to_url().'<br>';
        switch ($method) { 
        case 'GET': 
            return $this->http($request->to_url(), 'GET'); 
        default: 
            return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata($multi) , $multi ); 
        } 
	}     

	function http($url, $method, $postfields = NULL , $multi = false){
		$tmp = '<hr>'.$url.'<hr>'.$method.'<hr>'.$postfields.'<hr>';
		MBGlobal::getLogger()->debug("req:$tmp");
		MBGlobal::getLogger()->debug("----------------------------------------------");
		//$https = 0;
		//判断是否是https请求
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
			//$ua = split('\?',$url);
			$header .= "Content-Length: ".strlen($postfields)."\r\n";
			//$header .= "Cache-Control: no-cache\r\n";
			$header .= "Connection: Close\r\n\r\n";  
			//$header .= "Connection: Keep-Alive\r\n\r\n";
			$header .= $postfields;
		}else{
			$header .= "Connection: Close\r\n\r\n";  
		}
		//$header .= $ua[1];
		//echo '<hr>'.$header.'<hr>';

		$ret = '';
		
		//echo "url:".$host.":".$port.'<hr>';
		$fp = fsockopen($host,$port,$errno,$errstr,30);

		if(!$fp){
			$error = '建立sock连接失败';
			throw new Exception($error);
		}else{
			fwrite ($fp, $header);  
			while (!feof($fp)) {
				$ret .= fgets($fp, 4096);
			}
			fclose($fp);
           //$response=split("\r\n\r\n",$ret);
			if(strrpos($ret,'Transfer-Encoding: chunked')){
				$info = split("\r\n\r\n",$ret);
				$response = split("\r\n",$info[1]);
				$t = array_slice($response,1,-1);

				//print_r($t).'<hr>';
				$returnInfo = implode('',$t);
				//print_r($returnInfo);
				//echo base_convert($response[0],16,10) .' +++ '. strlen(str_replace("\r\n",'',$info[1]));
			 	//$returnInfo = substr($info[1],strlen($response[0])+2,-1);
				//$len =  base_convert($response[0],16,10);
				//$returnInfo =  substr($info[1],strpos($info[1],"\r\n")+2,strrpos($info[1],"\r\n")-2).'<hr>';
				//echo $len.$returnInfo.'<hr>';
			}else{
				$response = split("\r\n\r\n",$ret);
				$returnInfo = $response[1];
			}
			//echo '<hr>';
			//print_r($ret);
			$tmp = print_r(iconv("utf-8","utf-8//ignore",$returnInfo), true);
			MBGlobal::getLogger()->debug("resp:$tmp");
			MBGlobal::getLogger()->debug("*******************************************");
			/********************跟踪调试**********************/
			/*
			date_default_timezone_set('Asia/Shanghai');
			echo "<pre>";
			echo date("Y-m-d H:i:s")."\r\n";
			echo "REQUEST URL:".$url."\r\n";
			echo "REQUEST METHOD:".$method."\r\n";
			echo "WITH POSTFIEDS:".$postfields."\r\n";
			echo "THE RESULT:".$returnInfo."\r\n";
			echo "\r\n";
			echo "</pre>";
			*/
			/*************************************************/
			return iconv("utf-8","utf-8//ignore",$returnInfo);
			//return $returnInfo;	
		}
		
	}
 

	/*function http($url, $method, $postfields = NULL , $multi = false){


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

       	//echo $url."<hr/>"; 

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
