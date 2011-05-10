<?php 
abstract class openapiAbstract{
	public $name="新浪微博";
	public $lfrom="tsina";  
	public $tokenOrlfromuid="";
	
	public function saveToken($lfromuid,$data){
		
		$json=serialize($data);//echo $json;exit;
		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('api_'.envhelper::packKUID($this->lfrom,$lfromuid), $json,3600*24*100);
	}
	public function readToken($lfromuid){
		$json=sreadcookie('api_'.envhelper::packKUID($this->lfrom,$lfromuid));//str_replace("\\","",);

		if (!$json)
			return null;

		$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
		$session=unserialize($json);
		return $session;
	}
	
	abstract public function getLoginUrl($callbackurl);
	abstract public function callback();

	abstract public function getUserInfo();

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
	abstract public function update( $status, $reply_id = NULL, $lat = NULL, $long = NULL, $annotations = NULL );

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
	abstract public function upload( $status , $pic_path, $lat = NULL, $long = NULL );


	/**
	 * 删除一条微博
	 * 删除微博。注意：只能删除自己发布的信息。
	 * 
	 * @access public
	 * @param int64 $sid 要删除的微博ID
	 * @return array
	 */
	abstract public function del( $sid );

	/**
	 * 根据用户UID或昵称获取用户资料
	 * 按用户UID或昵称返回用户资料，同时也将返回用户的最新发布的微博。
	 * 
	 * @access public
	 * @param mixed $uid_or_name 用户UID或微博昵称。
	 * @return array
	 */
	abstract public function show_user( $uid_or_name );

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
	abstract public function friends( $cursor = NULL , $count = 20 , $uid_or_name = NULL );

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
	abstract public function followers( $cursor = NULL , $count = NULL , $uid_or_name = NULL );

	/**
	 * 关注一个用户
	 * 关注一个用户。成功则返回关注人的资料，目前的最多关注2000人，失败则返回一条字符串的说明。如果已经关注了此人，则返回http 403的状态。关注不存在的ID将返回400。
	 * 
	 * @access public
	 * @param mixed $uid_or_name 要关注的用户UID或微博昵称
	 * @return array
	 */
	abstract public function follow( $uid_or_name );

	/**
	 * 取消关注某用户
	 * 取消关注某用户。成功则返回被取消关注人的资料，失败则返回一条字符串的说明。
	 * 
	 * @access public
	 * @param mixed $uid_or_name 要取消关注的用户UID或微博昵称
	 * @return array
	 */
	abstract public function unfollow( $uid_or_name );

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
	abstract public function hot_users( $category = "default" );

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
	abstract public function is_followed( $target, $source = NULL );


	/**
	 * 当前用户退出登录
	 * 清除已验证用户的session，退出登录，并将cookie设为NULL。主要用于widget等web应用场合。
	 * 
	 * @access public
	 * @return array
	 */
	abstract public function end_session();
	/**
	 * 更改头像
	 * 
	 * @access public
	 * @param string $image_path 要发布的图片路径,支持url。[只支持png/jpg/gif三种格式,增加格式请修改get_image_mime方法]
	 * @return array
	 */
	abstract public function update_profile_image($image_path);

}

?>