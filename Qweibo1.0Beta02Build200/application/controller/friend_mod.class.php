<?php
/**
 * 关系链模块控制器
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */

require_once MB_CTRL_DIR.'/base_mod.class.php';

class FriendMod extends BaseMod
{
	const FRIEND_TYPE_FANS = 0;
	const FRIEND_TYPE_IDOL = 1;
	
	/**
	 * 获取关系链
	 * @param $type	0 听众 1 偶像
	 * @param $name	用户名 空表示本人
	 * @param $title
	 * @param $template
	 * @return unknown_type
	 */
	protected function getFriend($type, $title, $template, $name='')
	{
		$pos = MBValidator::getNumArg($_GET["startindex"], 0, PHP_INT_MAX, 0);
		$num = MBValidator::getNumArg($_GET["num"], 1, 30, 15);
		
		//获取当前用户资料
		$myInfo = MBGlobal::getApiClient()->getUserInfo();
		//获取显示用户资料
		$userInfo = ($name ? MBGlobal::getApiClient()->getUserInfo(array("n" => $name)) : $myInfo);
		//热门话题(默认取10条)
		$hotTopic = BaseMod::getHotTopic();
		//获取听众信息
		$p = array(
				"n"=> $name				//用户名 空表示本人
				, "num" => $num			//请求个数(1-30)
				, "start" => $pos		//起始位置
				, "type" => $type		//0 听众 1 偶像
				);
		$userRet = MBGlobal::getApiClient()->getMyfans($p);
		//print_r($userRet);
		
		//上一页下一页
		$frontUrl = $nextUrl = "";
		if($userRet["data"]["hasnext"] === 0)
		{
			$nextUrl = MBUtil::getCurUrl(array("startindex" => $pos+$num));
		}
		if($pos > 0)
		{
			$pos = $pos - $num;
			if($pos < 0)
			{
				$pos = 0;
			}
			$frontUrl = MBUtil::getCurUrl(array("startindex" => $pos));
		}
		$pageInfo = array("fronturl" => $frontUrl, "nexturl"=>$nextUrl);
		
		//访问用户跟当前用户的关系
		//$isidol = false;
		//$isfans = false;		//暂不关心
		//$isblack = false;		//暂不关心
		$idollist = NULL;		//偶像列表
		if($name!='' && $_SESSION["name"]!=$name)		//他人
		{
			//title加上名字
			$title = htmlspecialchars($userInfo["data"]["nick"]).$title;
			//$checkRet = MBGlobal::getApiClient()->checkFriend(array("n"=>$name, "type" =>1));
			//$isidol = (bool)$checkRet["data"][$name];
			//拉取偶像列表
			$p = array(
					"n" => $name
					, "num" => 13		//只拉13个
					, "start" => 0
					, "type" => 1		//偶像
					);
			$idols = MBGlobal::getApiClient()->getMyfans($p);
			$idollist = (array)$idols["data"]["info"];
			foreach($idollist as &$u) 
			{
				//定宽截取
				$u["nick"] = MBUtil::widthUtfSubstr($u["nick"], 8);
				$u = BaseMod::formatU($u, 50);
			}
		}
		
		//模版数据
		$data = array(
					"title" => $title
					, "sitename" => MB_SITE_NAME
					, "pageinfo" => $pageInfo
					);
		$myInfo["data"] = BaseMod::formatU($myInfo["data"], 120);
		$userInfo["data"] = BaseMod::formatU($userInfo["data"], 120);
		$data["user"] = $myInfo["data"];
		$data["screenuser"] = $userInfo["data"];
		$data["screenuser"]["idollist"] = $idollist;
		if(isset($hotTopic))
		{
			$data["moduleconfig"]["remenhuati"] = $hotTopic;
		}
		$data["huati"] = BaseMod::getHotTopic();
		$data["dingyue"] = BaseMod::getMyTopic();
		$data["unum"] = ($type==0?$userInfo["data"]["fansnum"]:$userInfo["data"]["idolnum"]);
		$data["u"] = (array)$userRet["data"]["info"];
		foreach($data["u"] as &$u) 
		{
			$u = BaseMod::formatU($u, 50);
		}
		//
		try {
			$updateInfo = MBGlobal::getApiClient()->getUpdate(array("op" => 0));
			$data["updateinfo"] = $updateInfo["data"];
		} catch (Exception $e) {
			//pass
		}
		echo BaseMod::renderView($data, $template);
		return;
	}
	
	/**
	 * 我的听众
	 * @return unknown_type
	 */
	protected function myFollowerAct()
	{
		$title = "我的听众";
		$template = "user_fanslist.view.php";
		BaseMod::clearNewMsgInfo(8);
		return $this->getFriend(self::FRIEND_TYPE_FANS, $title, $template);
	}
	
	/**
	 * 我的收听
	 * @return unknown_type
	 */
	protected function myFollowingAct()
	{
		$title = "我的收听";
		$template = "user_idollist.view.php";
		return $this->getFriend(self::FRIEND_TYPE_IDOL, $title, $template);
	}
	
	/**
	 * 他的听众
	 * @return unknown_type
	 */
	protected function hisFollowerAct()
	{
		$name = "".$_GET["u"];
		if(!MBValidator::isUserAccount($name))
		{
			throw new MBArgErrExcep();
		}
		$title = "的听众";
		$template = "guest_fanslist.view.php";
		return $this->getFriend(self::FRIEND_TYPE_FANS, $title, $template, $name);
	}
	
	/**
	 * 他的收听
	 * @return unknown_type
	 */
	protected function hisFollowingAct()
	{
		$name = "".$_GET["u"];
		if(!MBValidator::isUserAccount($name))
		{
			throw new MBArgErrExcep();
		}
		$title = "的收听";
		$template = "guest_idollist.view.php";
		return $this->getFriend(self::FRIEND_TYPE_IDOL, $title, $template, $name);
	}
	
	/**
	 * 我的/他的听众
	 * @return unknown_type
	 */
	public function followerAct()
	{
		if(!isset($_GET["u"]) || $_GET["u"]==$_SESSION["name"])		//my
		{
			$this->myFollowerAct();
		}
		else					//his
		{
			$this->hisFollowerAct();
		}
		return;
	}
	
	/**
	 * 我的/他的收听
	 * @return unknown_type
	 */
	public function followingAct()
	{
		if(!isset($_GET["u"]) || $_GET["u"]==$_SESSION["name"])		//my
		{
			$this->myFollowingAct();
		}
		else					//his
		{
			$this->hisFollowingAct();
		}
		return;
	}
	

	/**
	 * 收听/取消收听某个用户
	 * @return unknown_type
	 */
	public function follow()
	{
		$name = MBValidator::getUserAccount($_POST["name"]);		//目标用户微博帐号
		//type: 0 取消收听,1 收听 
		$type = MBValidator::getNumArg($_POST["type"], 0, 1);
		try{//没有抛出异常即为成功
			MBGlobal::getApiClient()->setMyidol(array("type" => $type, "n" => $name));
			echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC);
		}catch(MBAPIInnerExcep $err){
			echo BaseModRetCode::getRetJson(BaseModRetCode::RET_API_INNER_ERR,"由于对方的设置，不能收听此用户 ");
		}
		return;
	}
	
	/**
	 * 检测是否我粉丝或偶像
	 * @return unknown_type
	 */
	public function check()
	{
		//type: 0 检测粉丝，1检测偶像
		$type = MBValidator::getNumArg($_GET["type"], 0, 1);
		$name = $_GET["name"];
		if(!isset($name))
		{
			echo "aaaa";
			throw new MBMissArgExcep();
		}
		if(!MBValidator::isUserAccount($name))		//目标用户微博帐号
		{
			throw new MBException("此用户不存在，请输入你好友当前使用的微博帐号", BaseModRetCode::RET_DEFAULT_ERR);
		}
		
		try 
		{
			//检查目标用户是否存在
			$userName = MBGlobal::getApiClient()->getUserInfo(array("n" => $name));
		}
		catch(MBAPIInnerExcep $e)			//帐号不存在api也返回内部错误
		{
			throw new MBException("此用户不存在，请输入你好友当前使用的微博帐号", BaseModRetCode::RET_DEFAULT_ERR);
		}
		//检查对方是否是我的粉丝
		$checkRet = MBGlobal::getApiClient()->checkFriend(array("n"=>$name, "type" =>0));
		$isfans = (bool)$checkRet["data"][$name];
		if($isfans == false)
		{
			throw new MBException("他/她还没有收听你，暂时不能发私信", BaseModRetCode::RET_DEFAULT_ERR);
		}
		$checkRet = MBGlobal::getApiClient()->checkFriend(array("n"=>$name, "type"=>$type)); 
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, "", (int)$checkRet["data"][$name]);
		exit;
	}
}

?>