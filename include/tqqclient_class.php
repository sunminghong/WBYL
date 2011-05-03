<?php
include_once('oauth_class.php');


class tqqClient {
	
	public $host='';
	public $oauth=null;
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



	/**
	 * 发表微博
	 * 发布一条微博信息。请求必须用POST方式提交。为防止重复，发布的信息与当前最新信息一样话，将会被忽略。<br />
	 * 注意：lat和long参数需配合使用，用于标记发表微博消息时所在的地理位置，只有用户设置中geo_enabled=true时候地理位置信息才有效。
	 * 
	 * @access public
	 * @param string $status 要更新的微博信息。信息内容不超过140个汉字,为空返回400错误。
	 * @param int64 $reply_id @ 需要回复的微博信息ID, 这个参数只有在微博内容以 @username 开头才有意义。（即将推出）。可选
	 * @param float $lat 纬度，发表当前微博所在的地理位置，有效范围 -90.0到+90.0, +表示北纬。可选。
	 * @param float $long 经度。有效范围-180.0到+180.0, +表示东经。可选。
	 * @param mixed $annotations 可选参数。元数据，主要是为了方便第三方应用记录一些适合于自己使用的信息。每条微博可以包含一个或者多个元数据。请以json字串的形式提交，字串长度不超过512个字符，或者数组方式，要求json_encode后字串长度不超过512个字符。具体内容可以自定。例如：'[{"type2":123},{"a":"b","c":"d"}]'或array(array("type2"=>123), array("a"=>"b", "c"=>"d"))。
	 * @return array
	 */
	function update( $status, $reply_id = NULL, $lat = NULL, $long = NULL, $annotations = NULL )
	{
		//  http://api.t.sina.com.cn/statuses/update.json
		$params = array();
		$params['status'] = $status;
		if ($reply_id) {
			$this->id_format($reply_id);
			$params['in_reply_to_status_id'] = $reply_id;
		}
		if ($lat) {
			$params['lat'] = floatval($lat);
		}
		if ($long) {
			$params['long'] = floatval($long);
		}
		if (is_string($annotations)) {
			$params['annotations'] = $annotations;
		} elseif (is_array($annotations)) {
			$params['annotations'] = json_encode($annotations);
		}

		return $this->oauth->post( $this->host.'statuses/update.json' , $params );
	}

	/**
	 * 发表图片微博
	 * 上传图片及发布微博信息。请求必须用POST方式提交。为防止重复，发布的信息与当前最新信息一样话，将会被忽略。目前上传图片大小限制为<5M。<br />
	 * 注意：lat和long参数需配合使用，用于标记发表微博消息时所在的地理位置，只有用户设置中geo_enabled=true时候地理位置信息才有效。
	 * 
	 * @access public
	 * @param string $status 要更新的微博信息。信息内容不超过140个汉字,为空返回400错误。
	 * @param string $pic_path 要发布的图片路径,支持url。[只支持png/jpg/gif三种格式,增加格式请修改get_image_mime方法]
	 * @param float $lat 纬度，发表当前微博所在的地理位置，有效范围 -90.0到+90.0, +表示北纬。可选。
	 * @param float $long 可选参数，经度。有效范围-180.0到+180.0, +表示东经。可选。
	 * @return array
	 */
	function upload( $status , $pic_path, $lat = NULL, $long = NULL )
	{
		//  http://api.t.sina.com.cn/statuses/update.json
		$params = array();
		$params['status'] = $status;
		$params['pic'] = '@'.$pic_path;
		if ($lat) {
			$params['lat'] = floatval($lat);
		}
		if ($long) {
			$params['long'] = floatval($long);
		}

		return $this->oauth->post( $this->host.'statuses/upload.json' , $params , true );
	}


	/**
	 * 删除一条微博
	 * 删除微博。注意：只能删除自己发布的信息。
	 * 
	 * @access public
	 * @param int64 $sid 要删除的微博ID
	 * @return array
	 */
	function delete( $sid )
	{
		$this->id_format($sid);
		return $this->destroy( $sid );
	}

	/**
	 * 删除一条微博
	 * 删除微博。注意：只能删除自己发布的信息。
	 * 
	 * @access public
	 * @param int64 $sid 要删除的微博ID
	 * @return array
	 */
	function destroy( $sid )
	{
		$this->id_format($sid);
		return $this->oauth->post( $this->host.'statuses/destroy/' . $sid . '.json' );
	}

	/**
	 * 根据用户UID或昵称获取用户资料
	 * 按用户UID或昵称返回用户资料，同时也将返回用户的最新发布的微博。
	 * 
	 * @access public
	 * @param mixed $uid_or_name 用户UID或微博昵称。
	 * @return array
	 */
	function show_user( $uid_or_name )
	{
		return $this->request_with_uid( $this->host.'users/show.json' ,  $uid_or_name );
	}

	/**
	 * 获取用户关注对象列表及最新一条微博信息
	 * 获取用户关注列表及每个关注用户最新一条微博，返回结果按关注时间倒序排列，最新关注的用户在最前面。
	 * 
	 * @access public
	 * @param int $cursor 单页只能包含100个关注列表，为了获取更多则cursor默认从-1开始，通过增加或减少cursor来获取更多的关注列表。可选。
	 * @param int $count 每次返回的最大记录数（即页面大小），不大于200,默认返回20。可选。
	 * @param mixed $uid_or_name 用户UID或微博昵称。不提供时默认返回当前用户的关注列表。可选。
	 * @return array
	 */
	function friends( $cursor = NULL , $count = 20 , $uid_or_name = NULL )
	{
		return $this->request_with_uid( $this->host.'statuses/friends.json' ,  $uid_or_name , NULL , $count , $cursor );
	}

	/**
	 * 获取用户粉丝列表及及每个粉丝用户最新一条微博
	 * 返回用户的粉丝列表，并返回粉丝的最新微博。按粉丝的关注时间倒序返回，每次返回100个。注意目前接口最多只返回5000个粉丝。
	 * 
	 * @access public
	 * @param int $cursor 单页只能包含100个粉丝列表，为了获取更多则cursor默认从-1开始，通过增加或减少cursor来获取更多的，如果没有下一页，则next_cursor返回0。可选。
	 * @param int $count 每次返回的最大记录数（即页面大小），不大于200,默认返回20。可选。
	 * @param mixed $uid_or_name 要获取粉丝的 UID或微博昵称。不提供时默认返回当前用户的关注列表。可选。
	 * @return array
	 */
	function followers( $cursor = NULL , $count = NULL , $uid_or_name = NULL )
	{
		return $this->request_with_uid( $this->host.'statuses/followers.json' ,  $uid_or_name , NULL , $count , $cursor );
	}

	/**
	 * 关注一个用户
	 * 关注一个用户。成功则返回关注人的资料，目前的最多关注2000人，失败则返回一条字符串的说明。如果已经关注了此人，则返回http 403的状态。关注不存在的ID将返回400。
	 * 
	 * @access public
	 * @param mixed $uid_or_name 要关注的用户UID或微博昵称
	 * @return array
	 */
	function follow( $uid_or_name )
	{
		return $this->request_with_uid( $this->host.'friendships/create.json' ,  $uid_or_name ,  NULL , NULL , NULL , true  );
	}

	/**
	 * 取消关注某用户
	 * 取消关注某用户。成功则返回被取消关注人的资料，失败则返回一条字符串的说明。
	 * 
	 * @access public
	 * @param mixed $uid_or_name 要取消关注的用户UID或微博昵称
	 * @return array
	 */
	function unfollow( $uid_or_name )
	{
		return $this->request_with_uid( $this->host.'friendships/destroy.json' ,  $uid_or_name ,  NULL , NULL , NULL , true);
	}

	/**
	 * 获取系统推荐用户
	 * 返回系统推荐的用户列表。
	 * 
	 * @access public
	 * @param string $category 分类，可选参数，返回某一类别的推荐用户，默认为 default。如果不在以下分类中，返回空列表：<br />
	 *  - default:人气关注
	 *  - ent:影视名星
	 *  - hk_famous:港台名人
	 *  - model:模特
	 *  - cooking:美食&健康
	 *  - sport:体育名人
	 *  - finance:商界名人
	 *  - tech:IT互联网
	 *  - singer:歌手
	 *  - writer：作家
	 *  - moderator:主持人
	 *  - medium:媒体总编
	 *  - stockplayer:炒股高手
	 * @return array
	 */
	function hot_users( $category = "default" )
	{
		$params = array();
		$params['category'] = $category;

		return $this->oauth->get( $this->host.'users/hot.json' , $params );
	}

	/**
	 * 返回两个用户关系的详细情况
	 * 如果用户已登录，此接口将自动使用当前用户ID作为source_id。但是可强制指定source_id来查询关系
	 * 如果源用户或目的用户不存在，将返回http的400错误
	 * 
	 * @access public
	 * @param mixed $target 要查询的用户UID或微博昵称
	 * @param mixed $source 源用户UID或源微博昵称，可选
	 * @return array
	 */
	function is_followed( $target, $source = NULL )
	{
		$this->id_format($target);
		$params = array();
		if( is_numeric( $target ) ) $params['target_id'] = $target;
		else $params['target_screen_name'] = $target;

		if ( $source != NULL ) {
			$this->id_format($source);
			if( is_numeric( $source ) ) $params['source_id'] = $source;
			else $params['source_screen_name'] = $source;
		}

		return $this->oauth->get( $this->host.'friendships/show.json' , $params );
	}

    /** 
     * 当前登录的用户的信息。 
     *  
     * @access public 
     * @return array 当前登录的用户的信息
     */ 
	function getUserInfo(){
		$url=$this->host.'account/verify_credentials.json';
   		return $this->oauth->get($url );
	}
	/**
	 * 验证当前用户身份是否合法
	 * 如果用户新浪通行证身份验证成功且用户已经开通微博则返回 http状态为 200；如果是不则返回401的状态和错误信息。此方法用了判断用户身份是否合法且已经开通微博。
	 * 
	 * @access public
	 * @return array
	 */
	function verify_credentials()
	{
		return $this->oauth->get($this->host.'account/verify_credentials.json');
	}


	/**
	 * 当前用户退出登录
	 * 清除已验证用户的session，退出登录，并将cookie设为NULL。主要用于widget等web应用场合。
	 * 
	 * @access public
	 * @return array
	 */
	function end_session()
	{
		return $this->oauth->post($this->host.'account/end_session.json');
	}

	/**
	 * 更改头像
	 * 
	 * @access public
	 * @param string $image_path 要发布的图片路径,支持url。[只支持png/jpg/gif三种格式,增加格式请修改get_image_mime方法]
	 * @return array
	 */
	function update_profile_image($image_path)
	{
		$params = array();
		$params['image'] = "@{$image_path}";

		return $this->oauth->post($this->host.'account/update_profile_image.json', $params, true);
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
		
		$this->host="http://open.t.qq.com/";
		
		//parent::__construct($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
    } 

} 

?>