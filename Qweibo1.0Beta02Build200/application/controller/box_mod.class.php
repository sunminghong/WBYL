<?php
/**
 * 信箱模块控制器
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */

require_once MB_CTRL_DIR.'/base_mod.class.php';

class BoxMod extends BaseMod
{
	const BOX_TYPE_SENDBOX = 0;
	const BOX_TYPE_INBOX = 1;
	
	/**
	 * 发私信
	 * @return unknown_type
	 */
	public function add()
	{
		$name = $_POST["name"];				//收信人微博帐号
		$content = trim($_POST["content"]);		//微博内容
		if(!isset($content, $name))
		{
			throw new MBMissArgExcep();
		}
		if(!MBValidator::checkT($content))
		{
			throw new MBArgErrExcep();
		}
		if(!MBValidator::isUserAccount($name))
		{
			throw new MBException("此用户不存在，请输入你好友当前使用的微博帐号", BaseModRetCode::RET_DEFAULT_ERR);
		}
		$clientIp = MBUtil::getClientIp();
		
		//检查目标用户是否存在
		try 
		{
			$userInfo = MBGlobal::getApiClient()->getUserInfo(array("n" => $name));
			$userNameNick = $userInfo["data"]["nick"];
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
		$p = array(
				"c" => $content
				, "n" => $name
				, "ip" => $clientIp
				, "j" => ""				//经度，忽略
				, "w" => ""				//纬度，忽略
				);
		try
		{
			$postRet = MBGlobal::getApiClient()->postOneMail($p);
		}
		catch(MBException $e)
		{
			throw new MBException("私信发送失败", BaseModRetCode::RET_DEFAULT_ERR);
		}
		$data = $postRet["data"];
		//获取目标用户资料
		//$myInfo = MBGlobal::getApiClient()->getUserInfo(array("n" => $name));
		$data["nick"] = $userNameNick;
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, '', $data);
		exit();
	}
	
	/**
	 * 删除私信
	 * @return unknown_type
	 */
	public function del()
	{
		$tid = $_POST["tid"];
		if(!isset($tid))
		{
			throw new MBMissArgExcep();
		}
		if(!MBValidator::isTId($tid))
		{
			throw new MBArgErrExcep();
		}
		MBGlobal::getApiClient()->delOneMail(array("id" => $tid));
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC);
		exit();
	}
	
	/**
	 * 收件箱
	 * @return unknown_type
	 */
	public function inboxAct()
	{
		return $this->boxAct(self::BOX_TYPE_INBOX);
	}
	
	/**
	 * 发件箱
	 * @return unknown_type
	 */
	public function sendboxAct()
	{
		return $this->boxAct(self::BOX_TYPE_SENDBOX);
	}
	
	/**
	 * 信箱逻辑
	 * @param $type 0 发件箱 1 收件箱
	 * @return unknown_type
	 */
	protected function boxAct($type)
	{
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		$l = MBValidator::getTidArg($_GET['lid'], "0");
		$p = array(
			'type' => $type, //0 发件箱 1 收件箱
			'f' => $f, 		//分页标识（0：第一页，1：向下翻页，2向上翻页）
			'n' => $num, 	//每次请求记录的条数（1-20条）
			't' => $t, 		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
			'l' => $l		//lastid
		);
		
		//获取当前用户资料
		$userInfo = MBGlobal::getApiClient()->getUserInfo();
		
		//热门话题(默认取10条)
		$hotTopic = BaseMod::getHotTopic();
		
		//我订阅的话题(默认取10条)
		$myTopic = BaseMod::getMyTopic();
		
		//信箱内容
		$boxInfo = MBGlobal::getApiClient()->getMailBox($p);
		if(is_array($boxInfo["data"]["info"]))		//设置头像大小
		{
			BaseMod::formatTArr($boxInfo["data"]["info"]);
		}
		
		//上一页下一页
		$frontUrl = $nextUrl = "";
		BaseMod::hasFrontNextPage($f, $boxInfo["data"]["hasnext"], $frontUrl, $nextUrl
						, $boxInfo["data"]["info"][0]["timestamp"]
						, $boxInfo["data"]["info"][(count($boxInfo["data"]["info"])-1)]["timestamp"]
						, $boxInfo["data"]["info"][0]["id"]
						, $boxInfo["data"]["info"][(count($boxInfo["data"]["info"])-1)]["id"]);
		$pageInfo = array("fronturl" => $frontUrl, "nexturl"=>$nextUrl);
		//生成视图模版数据
		$title = ($type==0 ? "我发出的私信" : "我收到的私信");
		$sitename = MB_SITE_NAME;
		$template = ($type==0 ? "user_mail_sendbox.view.php" : "user_mail_inbox.view.php");
		$data = array(
					"title" => $title			//标题,可选,默认值"iWeibo"
					, "sitename" => $sitename	//站点名字,可选
					, "pageinfo" => $pageInfo	//上一页下一页信息
					);
		$userInfo["data"] = BaseMod::formatU($userInfo["data"], 100);
		$data["user"] = $userInfo["data"];
		$data["screenuser"] = $userInfo["data"];
		if(isset($hotTopic))
		{
			$data["moduleconfig"]["remenhuati"] = $hotTopic;
		}
		$data["dingyue"] = $myTopic;
		$data["box"] = $boxInfo["data"]["info"];
		$data["boxnum"] = $boxInfo["data"]["totalnum"];
		BaseMod::clearNewMsgInfo(7);
		try {
			$updateInfo = MBGlobal::getApiClient()->getUpdate(array("op" => 0));
			$data["updateinfo"] = $updateInfo["data"];
		} catch (Exception $e) {
			//pass
		}
		echo BaseMod::renderView($data, $template);
		return;
	}
}
?>