<?php
/**
 * @file			weiboSina.class.php
 * @CopyRight		(C)1996-2099 SINA Inc.
 * @Project			Xweibo
 * @Author			Liujz <jianzhou@staff.sina.com.cn>
 * @Create Date:	2011-03-23
 * @Brief			继承weibo的class类， 不增加任何属性，目的是让xwb类可以加载不同的文件来继承xwbParent类
 */

include_once P_CLASS.'/weibo.class.php';

 class xwbParentClass extends weibo
 {
 	
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
	 * 关注某用户, 本地保存用户关系
	 *
	 * @param int|string $id 用户id
	 * @param int|string $user_id 用户user id
	 * @param string $name 用户昵称
	 * @param string $follow
	 * @return array
	 */
	 function createFriendship($id=null, $user_id=null, $name=null, $follow=null)
	 {
		$response = parent::createFriendship($id, $user_id, $name, $follow);
		
		
		// 本地备份
		if ( isset($response['rst']['id']) && $this->_needLocalCopy() )
		{
			$uid  	= USER::uid();
			$fanUid = $response['rst']['id'];
			
			// 增加粉丝
			if ( $follow ) 
			{ 
				DR('UserFollow.addFollow', FALSE, $uid, $fanUid);
			} 
			else   // 增加关注
			{ 
				DR('UserFollow.addFollow', FALSE, $fanUid, $uid);
			}
		}
		
		return $response;
	 }
	 
	 
  	/**
	  * 批量添加关注, 本地保存用户关心
	  *
	  * @param string $ids 用户id, 多个用逗号隔开(最多20个)
	  * @return array
	  */
	 function createFriendshipBatch($ids)
	 {
	 	$response = parent::createFriendshipBatch($ids);
	 	$idList   = $this->_getRspIdList($response['rst']);
	 	
	 	// 本地备份
	 	if ( !empty($idList) && $this->_needLocalCopy() )
	 	{
	 		DR('UserFollow.addFollowBatch', FALSE, USER::uid(), $idList);
	 	}
	 	
	 	return $response;
	 }
	 
	 
 	/**
	 * 取消关注或移除粉丝, 增加本地数据操作
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
		
		// 本地备份
		if ( $uid && $this->_needLocalCopy() )
		{
			$curUid	= USER::uid();
			
			if ( $is_follower )  // 取消粉丝
			{ 
				DR('UserFollow.delFollow', FALSE, $curUid, $uid);
			} 
			else   // 取消关注
			{ 
				DR('UserFollow.delFollow', FALSE, $uid, $curUid);
			}	
		}

		return $response;
	 }
 }