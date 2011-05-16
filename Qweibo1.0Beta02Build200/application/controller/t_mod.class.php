<?php
/**
 * 微博广播模块
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */
require_once MB_CTRL_DIR.'/base_mod.class.php';

class TMod extends BaseMod
{	
	/**
	 * 发表广播
	 * 包括:1 发表 2 转播 3 回复 4 点评
	 * @return unknown_type
	 */
	public function add()
	{
		$type = MBValidator::getNumArg($_POST["type"], 1, 4, 1);	//1发表 2 转播 3 回复 4 点评
		if($type > 1)
		{
			$reId = MBValidator::getTidArg($_POST["reid"]);
		}
		
		//消息内容
		$content = $_POST["content"];
		
	
		if(!MBValidator::checkT($content))
		{
			throw new MBArgErrExcep("content");
		}
		
		BaseMod::getAdminFilterPattern($keyWordPattern, $tPattern, $userPattern);//发微博关键字屏蔽
		if(!empty($keyWordPattern) && preg_match($keyWordPattern,$content)){
			throw new MBException("发表消息包含敏感内容，请重新输入");
		}
		//客户端ip
		$clientIp = MBUtil::getClientIp();
		//图片
		if(MBValidator::isUploadFile($_FILES["pic"]))
		{
			$len = intval($_FILES["pic"]["size"]);
			if($len < 2 || $len > 2*1024*1024)		//图片最大2M
			{
				throw new MBArgErrExcep("最大支持2M图片");
			}
		    $fileContent =  file_get_contents($_FILES["pic"]["tmp_name"]);
			$picType = MBUtil::getFileType(substr($fileContent, 0, 2));
			if($picType!="jpg" && $picType!="gif" && $picType!="png")
			{
				throw new MBArgErrExcep("图片仅支持jpg/jpeg/gif/png类型");
			}
			//pic参数是个数组
			$pic = array($_FILES["pic"]["type"], $_FILES["pic"]["name"], $fileContent);
		}
		
		$p = array(
				"type" => $type
				, "c" => $content
				, "ip" => $clientIp
				, "j" => ""				//经度，忽略
				, "w" => ""				//纬度，忽略
				, "r" => $reId			//回复转播id
				, "p" => $pic			//图片参数
 				);
 		$addRet = MBGlobal::getApiClient()->postOne($p);
 		$tId = $addRet["data"]["id"];
 		//获取该条微博的信息
		$tInfo = MBGlobal::getApiClient()->getOne(array("id"=>$tId));
		$data = BaseMod::formatT($tInfo["data"], 50, 160);
		if($_POST["format"] == "html")			//返回html dom
		{
			$data = BaseMod::renderView(
					array("t" => array($data), "user"=>array("name"=>$_SESSION["name"]))
					, "/common/tbody.view.php"
					);
		}
		
		if(MBValidator::checkCallback($_POST["callback"]))		//有回调函数才需要<script>标签
		{
			echo "<script>"
				.BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, "", $data, $_POST["callback"])
				."</script>";
			exit();
		}
		else
		{
			echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, "", $data);
			exit();
		}
	}
	
	/**
	 * 删除微博广播
	 * @return unknown_type
	 */
	public function del()
	{
		$tid = $_GET["tid"];
		if(!isset($tid))
		{
			throw new MBMissArgExcep();	
		}
		if(!MBValidator::isTId($tid))
		{
			throw new MBArgErrExcep();
		}
		$delRet = MBGlobal::getApiClient()->delOne(array("id" => $tid));
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC);
		exit();
	}
	
	/**
	 * 显示对话
	 * @return unknown_type
	 */
	public function showDialogAct()
	{
		$tId = MBValidator::getTidArg($_GET["tid"]);		//目标微博id
		//获取当前用户资料
		$userInfo = MBGlobal::getApiClient()->getUserInfo();
		//获取该条微博的信息
		$tInfo = MBGlobal::getApiClient()->getOne(array("id"=>$tId));
		if($tInfo["data"]["type"]!==MBTType::DIALOG || empty($tInfo["data"]["source"]))
		{
			throw new MBDataNAExcep();
		}
		//模版数据
		$data = array("sitename"=>MB_SITE_NAME);
		$title = $tInfo["data"]["nick"].":".$tInfo["data"]["text"];
		$data["title"] = htmlspecialchars(MBUtil::utfSubstr($title, 10, "..."));
		$userInfo["data"] = BaseMod::formatU($userInfo["data"], 120);
		$data["user"] = $userInfo["data"];
		$data["t"] = BaseMod::formatT($tInfo["data"], 50, 460);
		$data["t"] = array($data["t"]["source"], $data["t"]);
		
		echo BaseMod::renderView($data, "general_show_chat.view.php");
		return;
	}
	/**
	 * 显示微博广播
	 * @return unknown_type
	 */
	public function showTAct()
	{
		$tId = MBValidator::getTidArg($_GET["tid"]);		//目标微博id
		//Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		//每次请求记录的条数（1-20条）
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		//起始id,用于结果查询中的定位,上下翻页时才有用
		$l = MBValidator::getTidArg($_GET['lid'], "0");
		
		//获取当前用户资料
		$userInfo = MBGlobal::getApiClient()->getUserInfo();
		
		//获取该条微博的信息
		$tInfo = MBGlobal::getApiClient()->getOne(array("id"=>$tId));
		
		//转发根微博id
		$rtid = $tInfo["data"]["id"];
		if(!empty($tInfo["data"]["source"]) && $tInfo["data"]["source"]["type"] == MBTType::RETWEET)	//转播
		{
			$rtid = $tInfo["data"]["source"]["id"];
		}
		
		//获取单条微博的转发列表
		$p = array(
				"reid" => $rtid
				, "f" => $f
				, "n" => $num
				, "t" => $t
				, "tid" => $l
				, "flag" => 2					//0 转播列表，1点评列表 2 点评与转播列表
				);
		$reTList = MBGlobal::getApiClient()->getReplay($p);
		
		//上一页下一页
		$frontUrl = $nextUrl = "";
		$reTListCount = count($reTList["data"]["info"]);
		BaseMod::hasFrontNextPage($f, $reTList["data"]["hasnext"]
								, $frontUrl, $nextUrl
								, $reTList["data"]["info"][0]["timestamp"]
								, $reTList["data"]["info"][$reTListCount-1]["timestamp"]
								, $reTList["data"]["info"][0]["id"]
								, $reTList["data"]["info"][$reTListCount-1]["id"]);
		$pageInfo = array("fronturl" => $frontUrl, "nexturl"=>$nextUrl);
		
		//生成视图模版数据
		$data = array("sitename"=>MB_SITE_NAME
					, "pageinfo" => $pageInfo);
		$title = $tInfo["data"]["nick"].":".$tInfo["data"]["text"];
		$data["title"] = htmlspecialchars(MBUtil::utfSubstr($title, 10, "..."));
		$userInfo["data"] = BaseMod::formatU($userInfo["data"], 120);
		$data["user"] = $userInfo["data"];
		BaseMod::getAdminFilterPattern($keyWordPattern, $tPattern, $userPattern);//屏蔽单条微博
		$data["t"] = BaseMod::formatT($tInfo["data"], 120, 160,'',$keyWordPattern,$tPattern,$userPattern);
		$data["t"] = array($data["t"]);
		
		$data["tall"] = $reTList["data"]["info"];
		if(is_array($data["tall"]))
		{
			BaseMod::formatTArr($data["tall"], 120, 160);
			foreach($data["tall"] as &$t) 
			{
				$pos = strpos($t["text"], "||");
				if($pos !== false)
				{
					$t["text"] = substr($t["text"], 0, $pos);
				}
			}
		}
		echo BaseMod::renderView($data, "general_show_t.view.php");
		return;
	}
	
	/**
	 * 获取新消息更新条数
	 * @return unknown_type
	 */
	public function getNewMsgInfo()
	{
		$newMsgCount = MBGlobal::getApiClient()->getUpdate(array("op" => 0));
		//BaseMod::clearNewMsgInfo(5);//拉新消息不清空数据
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, "", $newMsgCount["data"]);
		exit();
	}
	
	/**
	 * 获取转播和点评列表
	 * @return unknown_type
	 */
	public function getReList()
	{	
		//Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		//每次请求记录的条数（1-20条）
		$num = MBValidator::getNumArg($_GET["num"], 1, 10, 10);
		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		//目标微博id
		$tId = MBValidator::getTidArg($_GET['tid']);
		//起始id,用于结果查询中的定位,上下翻页时才有用
		$l = MBValidator::getTidArg($_GET['lid'], "0");
		
		//获取单条微博的转发列表
		$p = array(
				"reid" => $tId
				, "f" => $f
				, "n" => $num						
				, "t" => $t
				, "tid" => $l
				, "flag" => 2					//0 转播列表，1点评列表 2 点评与转播列表
				);
		$reTList = MBGlobal::getApiClient()->getReplay($p);
		$data = array();
		$data["data"] = $reTList["data"];//格式化过的内容
		$data["count"] = $data["data"]["totalnum"];//转播点评总数
		//只显示本人转播的内容
		if(is_array($data["data"]["info"]))
		{
			BaseMod::formatTArr($data["data"]["info"]);
			foreach($data["data"]["info"] as &$t) 
			{
				$pos = strpos($t["text"], "||");
				if($pos !== false)
				{
					$t["text"] = substr($t["text"], 0, $pos);
				}
			}
		}
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, "", $data);
		exit();
	}
}
?>