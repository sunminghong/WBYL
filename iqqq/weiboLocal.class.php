<?php
/**
 * @file			weiboLocal.class.php
 * @CopyRight		(C)1996-2099 SINA Inc.
 * @Project			Xweibo
 * @Author			Liujz <jianzhou@staff.sina.com.cn>
 * @Create Date:	2011-03-23
 * @Brief			用户关系本地化api操作类,重构一些方法。目的是让xwb类可以加载不同的文件来继承xwbParent类
 */

include_once P_CLASS.'/weibo.class.php';

 class xwbParentClass extends weibo
 {
 	/// API list不存在时返回的错误码
 	static $NOT_EXIST_ERRNO = '1021002';
 	
 	/// 首页List的类型
 	static $LIST_MODE		= 'system';
 	
 	
 	/**
	 * 构造函数
	 *
	 * @param @oauth_token
	 * @param @oauth_token_secret
	 * @return
	 */
	function xwbParentClass($oauth_token = NULL, $oauth_token_secret = NULL)
	{
		parent::weibo($oauth_token, $oauth_token_secret);
	}
	
	
	
 	/**
	 * 获取当前用户所关注用户的最新微博信息
	 *
	 * @param int $count 获取条数
	 * @param int $page 页码数
	 * @param int|string $since_id 返回比since_id大的微博数据
	 * @param int|string $max_id 返回不大于max_id的微博数据
	 * @param int|string $pub_type 返回某一发布类型结果的微博  类型有全部-0, 原创-1, 转发-2默认返回全部
	 * @param int|string $base_app: 选填参数，是否基于当前应用来获取数据。1为限制本应用微博，0为不做限制
	 * @param int|stirng $content_type 返回某一内容类型结果的微博 类型有全部-0, 图片-1, 音乐-2, 视频-3, 纯文本-4,
	 * 默认返回全部
	 * @return array
	 */
	 function getFriendsTimeline($count=null, $page=null, $since_id=null, $max_id=null, $base_app='0', $feature=0)
	 {
	 	// 获取数据
	 	$curUid	  = USER::uid();
	 	$listId   = $this->_getIndexUserListId($curUid);
	 	$response = $this->getUserListIdStatuses($curUid, $listId, $count, $page, $since_id, $max_id, $base_app, $feature);
	 	$errno	  = isset($response['errno']) ? $response['errno'] : FALSE;
	 	
	 	// ListId 不存在， 增加List, 并重新获取数据
	 	if ( self::$NOT_EXIST_ERRNO==$errno && ($listId=$this->_getIndexUserListId($curUid, FALSE)) )
	 	{
	 		$response = $this->getUserListIdStatuses($curUid, $listId, $count, $page, $since_id, $max_id, $base_app, $feature);
	 	}
	 	
	 	// 确保当前用户到List 里面
	 	$hasAdd_Key = 'Done0#Add_Myself5#IndexList';
	 	if ( !USER::v($hasAdd_Key) ) {
	 		$rspTmp = $this->createUserListsMember($curUid, $listId, $curUid);
	 		USER::v($hasAdd_Key, TRUE);
	 	}
	 	
	 	return $response;
	 }
	 
	 
	 /**
	 * 创建新的订阅分类
	 *
	 * @param int|string $id 用户id
	 * @param string $name 分类名称
	 * @return array
	 */
	 function _getIndexUserListId($id, $needCofig=TRUE)
	 {
	 	// 从userconfig获取当前用户的首页List ID
	 	if ( $needCofig && ($listId=V('-:userConfig/index_listId', 0)) )
	 	{
		 	return $listId;
	 	}
	 	
	 	
	 	// 创建用户首页List
	 	$name		 = $this->_getIndexListName();
	 	$description = 'Xweibo用户关系本地化的首页list, 必须存在';
	 	$response	 = $this->createUserLists($id, $name, self::$LIST_MODE, $description);
	 	$listId		 = isset($response['rst']['id']) ? $response['rst']['id'] : FALSE;
	 	
	 	// 已存在的List名字处理
	 	$errNo 	= isset($response['errno']) ? trim($response['errno']) : '';
	 	if ( '1050000' == $errNo ) {
	 		$listId = $this->_findListIdByName($id, $name);
	 	}
	 	
	 	
	 	// 增加自己进list里，并写进配置文件
	 	if ( $listId ) 
	 	{
	 		DS('common/userConfig.set', FALSE, 'index_listId', $listId);
	 		$this->createUserListsMember($id, $listId, $id);
	 		return $listId;
	 	}
	 	
	 	return FALSE;
	 }
	 
	 
	 /**
	  * 首页List的名称
	  */
	 function _getIndexListName()
	 {
	 	return md5(USER::uid().'XweiboLocalIndexList'.WB_AKEY);
	 }
	 
	 
	 /**
	  * 获取已有首页名字的List ID
	  * 
	  * @param bigint $id
	  * @param string $name
	  */
	 function _findListIdByName($id, $name)
	 {
	 	$systemModeNo 	= 2;
	 	$rspTmp 		= $this->getUserLists($id, null, $systemModeNo);
	 	$list			= isset($rspTmp['rst']['lists']) ? $rspTmp['rst']['lists'] : array();
	 	
	 	if ( is_array($list) )
	 	{
	 		foreach ($list as $aList)
	 		{
	 			if ( $name == $aList['name'] ) {
	 				return $aList['id'];
	 			}
	 		}
	 	}
	 	
	 	return FALSE;
	 }
	 
	 
 	/**
	 * 获取当前用户未读消息数
	 *
	 * @param int|string $with_new_status 默认为0。1表示结果包含是否有新微博，0表示结果不包含是否有新微博
	 * @param int|string $since_id 微博id，返回此条id之后，是否有新微博产生，有返回1，没有返回0
	 * @return array
	 */
	 function getUnread($with_new_status=null, $since_id=null)
	 {
	 	// 先从api获取未读数据, 在获取本地的关注数和新微博
		$response 	= parent::getUnread();
		
		// 本地获取关注数和新微博
		if ( isset($response['rst']['followers']) )
		{
			// 关注数
			$response['rst']['followers'] = V('-:userConfig/new_followers', 0);
			
			// 是否有新微博
			if ( $with_new_status ) 
			{
				$since_id						= is_numeric($since_id) ? $since_id : 0;
				$response['rst']['new_status'] 	= $this->_UnreadGetNewStatus($since_id);
			}
		}

		return $response;
	 }
	 
	 
	/**
	 * 判断是否有新微博
	 * 
	 * @param bigint $since_id
	 */
	function _UnreadGetNewStatus($since_id)
	{
		// Cache Compare
		$maxWbId_Key = 'unread#max_wbId';
		$cacheMid 	 = USER::get($maxWbId_Key);
		if ( $cacheMid && $since_id<$cacheMid )
		{
			return 1;
		}
		
		// Get From List Status
		$curUid	  = USER::uid();
		$listId   = $this->_getIndexUserListId($curUid);
		$response = $this->getUserListIdStatuses($curUid, $listId, 1, null, $since_id);
		$errno	  = isset($response['errno']) ? $response['errno'] : FALSE;
		
		// ListId 不存在， 增加List, 并重新获取数据
	 	if ( self::$NOT_EXIST_ERRNO==$errno && ($listId=$this->_getIndexUserListId($curUid, FALSE)) ) {
	 		$response = $this->getUserListIdStatuses($curUid, $listId, 1, null, $since_id);
	 	}
		
	 	// 处理结果
		if ( is_array($response['rst']) )
		{
			$aWb = array_shift($response['rst']);
			if ( isset($aWb['id']) && $since_id<$aWb['id'] )
			{
				USER::set($maxWbId_Key, $aWb['id']);
				return 1;
			}
		}
		
		return 0;
	}
	 
	
	 
 	/**
	 * 根据用户ID获取用户资料（授权用户）
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param bool $oauth 是否用户oauth方式请求api
	 * @return array
	 */
	function getUserShow($id=null, $user_id=null, $name=null, $oauth=true)
	{
		$response = parent::getUserShow($id, $user_id, $name, $oauth);
		
		// 替换本地关注数和粉丝数
		if ( isset($response['rst']['id']) && $response['rst']['id'] )
		{
			$response['rst']['friends_count']	= DR('UserFollow.getFriendCount', 'u0/'.CACHE_HOME_TIMELINE, $response['rst']['id']);
			$response['rst']['followers_count'] = DR('UserFollow.getFollowCount', 'u0/'.CACHE_HOME_TIMELINE, $response['rst']['id']);
		}
		
		return $response;
	}
	
	
	
 	/**
	 * 获取当前用户关注对象列表及最新一条微博信息
	 *
	 * @param int|string $id 用户id
	 * @parma int $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $cursor 分页位置
	 * @param int $count 获取条数
	 * @return array
	 */
	 function getFriends($id=null, $user_id=null, $name=null, $cursor=null, $count=null)
	 {
	 	// 获取用户的Sina UID
	 	$id					= $this->_getUserId($id, $user_id, $name);
	 	$response['users'] 	= array();
	 	
	 	if ( $id )
	 	{
		 	// 获取本地Friends 的id列表
		 	list($cursor, $count, $offset) 	= $this->_fixCursorAndCount($cursor, $count, 20);
		 	$uCnt							= DR('UserFollow.getFriendCount', 'u0/'.CACHE_HOME_TIMELINE, $id);
		 	list($prev, $next)				= $this->_getPreAndNextCursor($cursor, $count, $uCnt);
		 	$response['previous_cursor']	= $prev;
		 	$response['next_cursor']		= $next;
		 	
		 	$uIdList 	= DR('UserFollow.getFriendList', 'u0/'.CACHE_HOME_TIMELINE, $id, $offset, $count);
		 	$uids	 	= implode(',', $uIdList);
		 	
		 	// 批量获取用户信息
		 	if ($uids)
		 	{
		 		$rspTmp 			= $this->getUsersBatchShow($uids);
		 		$response['users'] 	= $rspTmp['rst'];
		 	}
	 	}
	 	
		return RST($response);
	 }
	 
	 
	 /**
	  * 获取用户的sina uid
	  * 
	  * @param bigint $id
	  * @param bigint $user_id
	  * @param string $name
	  */
	 function _getUserId($id, $user_id, $name)
	 {
	 	// 返回ID
	 	if ($id) 
	 	{
	 		return $id;
	 	}
	 	
	 	// 返回UID
	 	if ($user_id)
	 	{
	 		return $user_id;
	 	}
	 	
	 	// 根据screen_name返回ID
	 	return DR('mgr/userCom.getSinaUidByName', FALSE, $name);
	 }
	 
	 
	 /**
	  * 检查cursor offset 和count的值的合法性
	  * 
	  * @param int $cursor
	  * @param int $count
	  * @param int $max
	  */
	 function _fixCursorAndCount($cursor, $count, $max=FALSE)
	 {
	 	$cursor = ($cursor<0) 			? 0 	: $cursor;
	 	$count  = ($count<0)  			? 0		: $count;
	 	$count  = ($max && $count>$max) ? $max	: $count;
	 	$offset = $cursor * $count;
	 	
	 	return array($cursor, $count, $offset);
	 }
	 
	 
	 /**
	  * 获取本地返回的previous_cursor和next_cursor
	  * 
	  * @param int $cursor
	  * @param int $count
	  * @param int $uCnt
	  */
	 function _getPreAndNextCursor($cursor, $count, $uCnt)
	 {
	 	$prev = ($cursor-1 > 0) ? $cursor-1 : 0;
	 	$next = $cursor+1;
	 	
	 	if ( ($cursor+1)*$count >= $uCnt )
	 	{
	 		$next = '';
	 	}
	 	return array($prev, $next);
	 }
	 
	 
 	/**
	 * 获取当前用户粉丝列表及最新一条微博信息
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $cursos 分页位置
	 * @param int $count 获取条数
	 * @return array
	 */
	 function getFollowers($id=null, $user_id=null, $name=null, $cursor=null, $count=null)
	 {
	 	// 获取用户的Sina UID
	 	$id					= $this->_getUserId($id, $user_id, $name);
	 	$response['users'] 	= array();
	 	
	 	if ( $id )
	 	{
		 	// 获取本地Friends 的id列表
		 	list($cursor, $count, $offset) 	= $this->_fixCursorAndCount($cursor, $count, 20);
		 	$uCnt							= DR('UserFollow.getFollowCount', 'u0/'.CACHE_HOME_TIMELINE, $id);
		 	list($prev, $next)				= $this->_getPreAndNextCursor($cursor, $count, $uCnt);
		 	$response['previous_cursor']	= $prev;
		 	$response['next_cursor']		= $next;
		 	
		 	$uIdList 	= DR('UserFollow.getFollowList', 'u0/'.CACHE_HOME_TIMELINE, $id, $offset, $count);
		 	$uids	 	= implode(',', $uIdList);
		 	
		 	// 批量获取用户信息
		 	if ($uids)
		 	{
		 		$rspTmp 			= $this->getUsersBatchShow($uids);
		 		$response['users'] 	= $rspTmp['rst'];
		 	}
	 	}
	 	
		return RST($response);
	 }
	 
	 
	 
 	/**
	 * 关注某用户
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $follow
	 * @return array
	 */
	 function createFriendship($id=null, $user_id=null, $name=null, $follow=null)
	 {
		$response  = parent::getUserShow($id, $user_id, $name);
		$followUid = isset($response['rst']['id']) ? $response['rst']['id'] : FALSE;
		$curUid	   = USER::uid();
		
		if ( $followUid && $followUid!=$curUid )
		{
			// 增加粉丝
			if ( $follow ) 
			{ 
				$dbUser	= DR('mgr/userCom.getByUid', FALSE, $uid);
				$dbUser = $dbUser['rst'];
				
				// token 为空，增加失败
				if ($dbUser['access_token'] && $dbUser['token_secret']) {
					return RST('', '1020804', '关注失败');
				}
				
				
				// 使用粉丝用户的token增加关注
				$this->setToken(3, $dbUser['access_token'], $dbUser['token_secret']);
				
				$result = $this->_localFriendShip($curUid, $followUid);
				if ( TRUE!==$result ) {
					return $result;
				}
				
				// 返回当前用户token
				$this->setToken(1);
			} 
			else   // 增加关注
			{ 
				$result = $this->_localFriendShip($followUid, $curUid);
				if ( TRUE!==$result ) {
					return $result;
				}
			}
			
			// API增加
			parent::createFriendship($id, $user_id, $name, $follow);
		}
		else {
			$response = RST('', '1020800', '40028:添加关注操作失败 关注的用户id为空或本人');
		}

		return $response;
	 }
	 
	 
	 /**
	  * 添加本地关系
	  * 
	  * @param bigint $uid
	  * @param bigint $fanUid
	  */
	 function _localFriendShip($uid, $fanUid)
	 {
	 	// 尝试添加到List里面
 		$listId = $this->_getIndexUserListId($fanUid);
		$rsp	= $this->createUserListsMember($fanUid, $listId, $uid);
		$errNo	= isset($rsp['errno']) ? $rsp['errno'] : '';
		
		// 重试
		if ( self::$NOT_EXIST_ERRNO==$errNo && ($listId=$this->_getIndexUserListId($fanUid, FALSE)) ) 
		{
			$rsp	= $this->createUserListsMember($fanUid, $listId, $uid);
			$errNo	= isset($rsp['errno']) ? $rsp['errno'] : '';
		}
			
		// 成功入库或已存在List里面的结果处理
		$fanUid	= ( isset($rsp['rst']['id']) || '1050000'==$errNo )	? $fanUid : FALSE;	
		if ( $fanUid ) 
		{
			// 增加成功
			if ( DR('UserFollow.addFollow', FALSE, $uid, $fanUid) ) 
			{
				$newFansCnt = DR('common/userConfig.get', FALSE, 'new_followers', $uid);
				DR('common/userConfig.set', FALSE, 'new_followers', intval($newFansCnt['rst'])+1, $uid);
				return TRUE;
			} 
			
			return RST('', '1020805', '40303:Error: already followed');
		} 
		
		// 关注失败
		return RST('', '1020804', '关注失败');
	 }

	 
	 /**
	  * 批量添加关注
	  *
	  * @param string $ids 用户id, 多个用逗号隔开(最多20个)
	  * @return array
	  */
//	 function createFriendshipBatch($ids)
//	 {
//	 	$response = RST('');
////		$response = parent::createFriendshipBatch($ids);
////		$userList = isset($response['rst']) ? $response['rst'] : FALSE;
////		$uidList  = $this->_getRspIdList($userList);
////		$uids 	  = implode(',', $uidList);
//		
//
//		if ( $ids )
//		{
//			parent::createFriendshipBatch($ids);
//			
//			// Add List
//			$curUid		= USER::uid();
//			$listId 	= $this->_getIndexUserListId( $curUid );
////			$response 	= $this->createUserListsMemberBatch($curUid, $listId, $ids);
//			$response 	= $this->createUserListsMemberBatch(USER::v('screen_name'), $listId, $ids);
//			$errNo		= isset($response['errno']) ? $response['errno'] : '';
//			
//			// 重试
//			if ( self::$NOT_EXIST_ERRNO==$errNo && ($listId=$this->_getIndexUserListId($curUid, FALSE)) ) 
//			{
//				$response = $this->createUserListsMemberBatch(USER::v('screen_name'), $listId, $ids);
//			}
//			
//			$userList 	= isset($response['rst']['users']) ? $response['rst']['users'] : FALSE;
//			$uidList  	= $this->_getRspIdList($userList);
//			
//			// 批量插入数据库
//			DR('UserFollow.addFollowBatch', FALSE, USER::uid(), $uidList);
//		}
//
//		return $response;
//	 }
	 
	 
 	 /**
	  * 批量添加关注, 循环添加。
	  *
	  * @param string $ids 用户id, 多个用逗号隔开(最多20个)
	  * @return array
	  */
	 function createFriendshipBatch($ids)
	 {
	 	// Ids处理
	 	$ids = is_array($ids) ? implode(',', $ids) : $ids;
		if ( empty($ids) ) 
		{
			return RST('');
		}
		
		
		// 构建用户数据
		$userInfoRst 	= $this->getUsersBatchShow($ids);
		$userInfoList	= array();
		if ( is_array($userInfoRst['rst']) )
		{
			foreach ($userInfoRst['rst'] as $aUser) 
			{
				$key				= $aUser['id'];
				$userInfoList[$key] = $aUser;
			}
		}
		
		
		// Add List And Local
		$curUid	= USER::uid();
		foreach ($userInfoList as $friendId => $aUser)
		{
			if ( TRUE!==$this->_localFriendShip($friendId, $curUid) ) 
			{
				unset($userInfoList[$friendId]);
			}
		}
		
		// 批量添加到API
		parent::createFriendshipBatch( array_keys($userInfoList) );
		return RST( array_values($userInfoList) );
	 }
	 

	/**
	 * 取消关注或移除粉丝
	 *
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param int $is_follower 默认为0。1表示为移除粉丝，0表示为取消关注
	 * @return array
	 */
	 function deleteFriendship($user_id = null, $name = null, $is_follower = 0)
	 {
		$response = parent::deleteFriendship($user_id, $name, $is_follower);
		$uid	  = isset($response['rst']['id']) ? $response['rst']['id'] : FALSE;
		
		if ( $uid )
		{
			$curUid	= USER::uid();
			
			if ( $is_follower )  // 取消粉丝
			{ 
				$dbUser	= DR('mgr/userCom.getByUid', FALSE, $uid);
				$dbUser = $dbUser['rst'];
				
				// token为空，取消失败
				if ( empty($dbUser['access_token']) && empty($dbUser['token_secret']) ) {
					return RST('', '1020802', '40028:fuid错误 取消关注的用户id为空或不存在。');
				}
				
				
				// 使用粉丝用户的token取消关注
				$this->setToken(3, $dbUser['access_token'], $dbUser['token_secret']);
				$listId 	= $this->_getIndexUserListId( $uid );
				$response	= $this->deleteUserListsMember($uid, $listId, $curUid);
				$errNo		= isset($response['errno']) ? $response['errno'] : '';
			
				// 重试
				if ( self::$NOT_EXIST_ERRNO==$errNo && ($listId=$this->_getIndexUserListId($uid, FALSE)) ) {
					$response	= $this->deleteUserListsMember($uid, $listId, $curUid);
				}
				
				// 数据库删除
				if ( isset($response['rst']['id']) ) {
					DR('UserFollow.delFollow', FALSE, $curUid, $uid);
				}
				
				// 返回当前用户token
				$this->setToken(1);
			} 
			else   // 取消关注
			{ 
				$listId 	= $this->_getIndexUserListId( $curUid );
				$response	= $this->deleteUserListsMember($curUid, $listId, $uid);
				$errNo		= isset($response['errno']) ? $response['errno'] : '';
				
				// 重试
				if ( self::$NOT_EXIST_ERRNO==$errNo && ($listId=$this->_getIndexUserListId($uid, FALSE)) ) {
					$response	= $this->deleteUserListsMember($curUid, $listId, $uid);
				}
				
				// 数据库删除
				if ( isset($response['rst']['id']) ) {
					DR('UserFollow.delFollow', FALSE, $uid, $curUid);
				}
			}	
		}

		return $response;
	 }


	/**
	 * 判断两个用户是否有关注关系
	 *
	 * @param int|string $user_a 要判断的用户UID
	 * @param int|string $user_b 要判断的被关注人用户UID
	 * @return array
	 */
	 function existsFriendship($user_a, $user_b)
	 {
	 	$response['friends'] = false;
	 	
	 	if ($user_a && $user_b) {
	 		$response['friends'] = DR('UserFollow.isFriendShip', FALSE, $user_b, $user_a);
	 	}
	 	
		return RST($response);
	 }


	/**
	 * 获取两个用户关系的详细情况
	 *
	 * @param int|string $target_id 要判断的目的用户UID
	 * @param string $target_screen_name 要判断的目的微博昵称
	 * @param int $source_id 源用户UID
	 * @param string $source_screen_name 源微博昵称
	 * @return array
	 */
	 function getFriendship($target_id=null, $target_screen_name=null, $source_id=null, $source_screen_name=null)
	 {
	 	// 因为用 blocking 关系，所以要请求api
	 	$response = parent::getFriendship($target_id, $target_screen_name, $source_id, $source_screen_name);
	 	$sUid	  = isset($response['rst']['source']['id']) ? $response['rst']['source']['id'] : FALSE;
	 	$tUid	  = isset($response['rst']['target']['id']) ? $response['rst']['target']['id'] : FALSE;
	 	
	 	// 替换关注和粉丝关系
	 	if ( $sUid && $tUid )
	 	{
	 		$isFriend = DR('UserFollow.isFriendShip', FALSE, $tUid, $sUid);
	 		$isFollow = DR('UserFollow.isFriendShip', FALSE, $sUid, $tUid);
	 		
	 		$response['rst']['source']['following'] 	= $isFriend;
	 		$response['rst']['source']['followed_by'] 	= $isFollow;
	 		
	 		$response['rst']['target']['following'] 	= $isFollow;
	 		$response['rst']['target']['followed_by'] 	= $isFriend;
	 	}
	 	
		return $response;
	 }
	 
	 
 	/**
	 * 获取用户关注对象uid列表
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $cursor 分页的位置
	 * @param int 获取条数
	 * @return array
	 */
	 function getFriendIds($id=null, $user_id=null, $name=null, $cursor=null, $count=null)
	 {
	 	// 获取用户的Sina UID
	 	$id				 = $this->_getUserId($id, $user_id, $name);
	 	$response['ids'] = array();
	 	
	 	if ( $id )
	 	{
		 	// 获取本地Friends 的id列表
		 	list($cursor, $count, $offset) 	= $this->_fixCursorAndCount($cursor, $count);
		 	$uCnt							= DR('UserFollow.getFriendCount', 'u0/'.CACHE_HOME_TIMELINE, $id);
		 	list($prev, $next)				= $this->_getPreAndNextCursor($cursor, $count, $uCnt);
		 	$response['previous_cursor']	= $prev;
		 	$response['next_cursor']		= $next;
		 	$response['ids'] 				= DR('UserFollow.getFriendList', 'u0/'.CACHE_HOME_TIMELINE, $id, $offset, $count);
	 	}
	 	
		return RST($response);
	 }


	/**
	 * 获取用户粉丝对象uid列表
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $cursor 分页的位置
	 * @param int $count 获取条数
	 * @return array
	 */
	 function getFollowerIds($id=null, $user_id=null, $name=null, $cursor=null, $count=null)
	 {
		// 获取用户的Sina UID
	 	$id				 = $this->_getUserId($id, $user_id, $name);
	 	$response['ids'] = array();
	 	
	 	if ( $id )
	 	{
		 	// 获取本地Friends 的id列表
		 	list($cursor, $count, $offset) 	= $this->_fixCursorAndCount($cursor, $count);
		 	$uCnt							= DR('UserFollow.getFollowCount', 'u0/'.CACHE_HOME_TIMELINE, $id);
		 	list($prev, $next)				= $this->_getPreAndNextCursor($cursor, $count, $uCnt);
		 	$response['previous_cursor']	= $prev;
		 	$response['next_cursor']		= $next;
		 	$response['ids'] 				= DR('UserFollow.getFollowList', 'u0/'.CACHE_HOME_TIMELINE, $id, $offset, $count);
	 	}
	 	
		return RST($response);
	 }
	 
	 
    /**
	* 获取用户优质粉丝列表，每次最多返回20条，包括用户的最新的微博
	*
	* @param int|string $user_id 用户user id
	* @param int $count 获取条数
	* @param bool $oauth 是否要用身份认证 默认为true需要, false为不需要
	* @return array
	*/
	function getMagicFollowers($user_id, $count=null, $oauth=true)
	{
	 	$response['users'] 	= array();
	 	if ( $user_id )
	 	{
		 	// 获取本地Friends 的id列表
		 	$count		= ($count<0) ? 0  : $count;
		 	$count		= ($count>20)? 20 : $count;
		 	$uIdList 	= DR('UserFollow.getFollowList', 'u0/'.CACHE_HOME_TIMELINE, $user_id, 0, $count);
		 	$uids	 	= implode(',', $uIdList);
		 	
		 	// 批量获取用户信息
		 	if ($uids)
		 	{
		 		$rspTmp 			= $this->getUsersBatchShow($uids);
		 		$response['users'] 	= $rspTmp['rst'];
		 	}
	 	}
	 	
		return RST($response);
	}
	
	
 	/**
	 * 验证当前用户身份是否合法
	 *
	 * @return array
	 */
	 function verifyCredentials()
	 {
	 	$response = parent::verifyCredentials();
	 	
	 	// 替换本地关注数和粉丝数
		if ( isset($response['rst']['id']) && $response['rst']['id'] )
		{
			$response['rst']['friends_count']	 = DR('UserFollow.getFriendCount', 'u0/'.CACHE_HOME_TIMELINE, $response['rst']['id']);
			$response['rst']['followers_count']  = DR('UserFollow.getFollowCount', 'u0/'.CACHE_HOME_TIMELINE, $response['rst']['id']);
		}

		return $response;
	 }
	 
	 
	 
 	/**
	 * 设置某个用户某个新消息的未读数为0
	 *
	 * @param string $type 1--评论数，2--@数，3--私信数，4--关注我的数,本地数据
	 * @return array
	 */
	function resetCount($type=1)
	{
		// 关注我的数，本地操作
		if ( 4 == $type ) {
			$rstTmp 			= DS('common/userConfig.set', FALSE, 'new_followers', 0);
			$result['result'] 	= empty($rstTmp);
			return RST($rstTmp);
		}
		
		
		// 除关注我的数外，都去api操作
		return parent::resetCount($type);
	}
	
	
	/**
	 * 初始化用户首页List
	 * @param bigint $uid
	 */
	function initUserIndexList($uid)
	{
		$listName = $this->_getIndexListName();
		
		// Delete The List
		if ( $listId=$this->_findListIdByName($uid, $listName) ) 
		{
			$this->deleteUserListId($uid, $listId);
		}
		
		// Delete The Friends
		DR('UserFollow.delAllFriend', FALSE, $uid);
		
		// Create List;
		$this->_getIndexUserListId($uid, FALSE);
	}
 }