<?php
/**
 * main模块控制器
 * 包括timeline相关
 * @param 
 * @return
 * @author luckyxiang,sheeppu
 * @package /application/controller/
 */

require_once MB_CTRL_DIR.'/base_mod.class.php';

class MainMod extends BaseMod
{
	const MORE_TYPE_INDEX = 0;
	const MORE_TYPE_MINE = 1;
	const MORE_TYPE_AT = 2;
	const MORE_TYPE_GUEST = 3;
	const MORE_TYPE_FAV = 4;
	
	const TIMELINE_TYPE_TOPIC = 1;
	const TIMELINE_TYPE_PUBLIC = 2;
	const TIMELINE_TYPE_GUEST = 3;
	const TIMELINE_TYPE_INDEX = 4;
	const TIMELINE_TYPE_MINE = 5;
	const TIMELINE_TYPE_AT = 6;
	const TIMELINE_TYPE_FAV = 7;
	
	private $topicName = "";			//话题名
	private $pageInfo = array();		//上一页下一页信息
	private $recommToday = array();		//广播大厅今日推荐话题
	private $recommUser = array();		//广播大厅推荐用户
	private $updateInfo = array();		//数据更新条数
	
	/**
	 * 话题timeline
	 * @return unknown_type
	 */
	public function topicAct()
	{
		$f = MBValidator::getNumArg($_GET["f"], 0, 4, 0);
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		$pageInfo = "".$_REQUEST['pageinfo'];
		$topicName = "".$_REQUEST['k'];
		if(!MBValidator::isTopicName($topicName))
		{
			throw new MBArgErrExcep();
		}
		
		$p = array(
			't' => $topicName, 	//话题名
			'f' => $f, 			//分页标识（0：第一页，1：向下翻页，2向上翻页，3最后一页，4最前一页）
			'num' => $num, 		//每次请求记录的条数（1-20条）
			'p' => $pageInfo 	//分页标识（第一页 填空，继续翻页：根据返回的 pageinfo决定）
		);
		
		$msg = MBGlobal::getApiClient()->getTopic($p);
		if(!isset($msg["data"]))					//没有广播
		{
			$msg["data"] = array("totalnum"=>0, "hasnext"=>-1, "pageinfo"=>""
						, "timestamp"=>0, "id"=>0, "info"=>NULL);
		}
		
		$this->topicName = $topicName;
		
		//上一页下一页
		$frontUrl = $nextUrl = "";
		$hasNext = $msg["data"]["hasnext"];		//2表示不能往上翻 1 表示不能往下翻，0表示两边都可以翻
		if($hasNext === 2)
		{
			$nextUrl = MBUtil::getCurUrl(array("f"=>1, "pageinfo"=>$msg["data"]["pageinfo"]));
		}
		elseif($hasNext === 1)
		{
			$frontUrl = MBUtil::getCurUrl(array("f"=>2, "pageinfo"=>$msg["data"]["pageinfo"]));
		}
		elseif($hasNext === 0)
		{
			$nextUrl = MBUtil::getCurUrl(array("f"=>1, "pageinfo"=>$msg["data"]["pageinfo"]));
			$frontUrl = MBUtil::getCurUrl(array("f"=>2, "pageinfo"=>$msg["data"]["pageinfo"]));
		}
		$this->pageInfo = array("fronturl" => $frontUrl, "nexturl"=>$nextUrl);
		
		$this->process(self::TIMELINE_TYPE_TOPIC, "和#".$topicName."#相关的广播", MB_SITE_NAME, $msg, "general_show_topic.view.php");
		return;
	}
	
	/**
	 * 今日推荐用户
	 * @return unknown_type
	 */
	public function getRecommUT()
	{
		$searchKey = $_GET["k"];
		if(!isset($searchKey))
		{
			throw new MBMissArgExcep("k");
		}
		if(!MBValidator::checkSearchKey($searchKey))
		{
			throw new MBArgErrExcep("k");
		}
		
		//获取该话题的第一页时间线
		$p = array(
			't' => $searchKey, 	//话题名
			'f' => 0, 			//分页标识（0：第一页，1：向下翻页，2向上翻页，3最后一页，4最前一页）
			'num' => 10, 		//每次请求记录的条数（1-20条）
			'p' => '' 			//分页标识（第一页 填空，继续翻页：根据返回的 pageinfo决定）
			);

		$tRet = MBGlobal::getApiClient()->getTopic($p);
		$tInfo = $tRet["data"]["info"];
		$tInfoRet = array();			//原创的
		if(is_array($tInfo))
		{
			foreach($tInfo as $t) 
			{
				if($t["type"] == 1)		//原创
				{
					$tInfoRet[] = $t;
				}
			}
		}
		$recommUT = array();
		if(!empty($tInfoRet))
		{
			$idx = array_rand($tInfoRet);
			$name = $tInfoRet[$idx]["name"];
			$userInfo = MBGlobal::getApiClient()->getUserInfo(array("n" => $name));
			$userInfo = $userInfo["data"];
			$userInfo["t"] = BaseMod::formatT($tInfoRet[$idx], 50, 160);
			$recommUT = array($userInfo);
		}
		
		if(empty($recommUT))
		{
			echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, "", "");
			exit();
		}
		
		if($_GET["format"] == "html" && !empty($recommUT))			//返回html dom
		{
			$data = BaseMod::renderView(array("ut"=>$recommUT), "/common/utbody.view.php");
		}
		else
		{
			$data = $recommUT;
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
	 * 广播大厅
	 * @return unknown_type
	 */
	public function publicAct()
	{
		$pos = MBValidator::getNumArg($_GET["pos"], 0, PHP_INT_MAX, 0);
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		$p = array(
			'p' => $pos, 	//记录的起始位置（第一次请求是填0，继续请求进填上次返回的Pos）
			'n' => $num, 	//每次请求记录的条数（1-20条）
		);
		$msg = MBGlobal::getApiClient()->getPublic($p);
		//上一页下一页
		$frontUrl = $nextUrl = "";
		if($msg["data"]["hasnext"] === 0)
		{
			$nextUrl = MBUtil::getCurUrl(array("pos" => $msg["data"]["pos"]));
		}
		if($pos > 0)
		{
			$pos = $pos - $num;
			if($pos < 0)
			{
				$pos = 0;
			}
			$frontUrl = MBUtil::getCurUrl(array("pos" => $pos));
		}
		$this->pageInfo = array("fronturl" => $frontUrl, "nexturl"=>$nextUrl);
		
		try
		{
			//管理平台mod名字
			$mod = MBGlobal::getAdminRecomm()->showMod(1);
			if($mod["list"][1]["status"] == 1)
			{
				$this->recommToday["name"] = htmlspecialchars((string)$mod["list"][1]["n"]);
				//管理平台今日推荐
				$recommToday = MBGlobal::getAdminRecomm()->showTopic(0, 1, 1);
				$recommToday = (string)$recommToday["list"][0];
				$this->recommToday["value"] = $recommToday;
				if(!empty($recommToday))			//有今日推荐,需要显示今日推荐下的某个用户
				{
					//获取该话题的第一页时间线
					$p = array(
						't' => $recommToday, 	//话题名
						'f' => 0, 			//分页标识（0：第一页，1：向下翻页，2向上翻页，3最后一页，4最前一页）
						'num' => 10, 		//每次请求记录的条数（1-20条）
						'p' => '' 			//分页标识（第一页 填空，继续翻页：根据返回的 pageinfo决定）
						);
		
					$tRet = MBGlobal::getApiClient()->getTopic($p);
					$tInfo = $tRet["data"]["info"];
					$tInfoRet = array();			//原创的
					if(is_array($tInfo))
					{
						foreach($tInfo as $t) 
						{
							if($t["type"] == 1)		//原创
							{
								$tInfoRet[] = $t;
							}
						}
					}
					if(!empty($tInfoRet))
					{
						$idx = array_rand($tInfoRet);
						$name = $tInfoRet[$idx]["name"];
						$userInfo = MBGlobal::getApiClient()->getUserInfo(array("n" => $name));
						$userInfo = $userInfo["data"];
						$userInfo["t"] = BaseMod::formatT($tInfoRet[$idx], 50, 160);
						$this->recommToday["data"] = array($userInfo);
					}
				}
			}
			if($mod["list"][3]["status"] == 1)
			{
				$this->recommUser["name"] = htmlspecialchars((string)$mod["list"][3]["n"]);
				//管理平台推荐用户
				$recommUser = MBGlobal::getAdminRecomm()->showrUser(1, 27, 1);
				if((int)$recommUser["count"] > 0)
				{
					$recommUser = (array)$recommUser["list"];
					foreach($recommUser as $user)
					{
						$userInfo = MBGlobal::getApiClient()->getUserInfo(array("n" => $user["uname"]));
						if($user["info"] != "")
						{
							$userInfo["data"]["introduction"] = $user["info"];
						}
						$this->recommUser["data"][] = BaseMod::formatU($userInfo["data"], 50);
					}
				}
			}
		}
		catch(MBException $e)
		{
			//do nothing
		}
		$this->setUpdateInfo();
		$this->process(self::TIMELINE_TYPE_PUBLIC, "广播大厅", MB_SITE_NAME, $msg, "general_public_timeline.view.php");
		return;
	}
	
	/**
	 * 客人页更多
	 * @return unknown_type
	 */
	public function getGuestMore()
	{
		$name = $_REQUEST["u"];		//客人微博帐号
		
		if(!MBValidator::isUserAccount($name))
		{
			throw new MBArgErrExcep();
		}
		
		$this->getMore(self::MORE_TYPE_GUEST, $name);
		exit();
	}
	
	/**
	 * 查看他人
	 * @return unknown_type
	 */
	public function guestAct()
	{
		$name = $_REQUEST["u"];		//客人微博帐号
		
		if(!isset($name) || $name==$_SESSION["name"])			//跳我的主页
		{
			MBUtil::location("./index.php?m=index");
			
			exit();
		}
		
		if(!MBValidator::isUserAccount($name))
		{
			throw new MBArgErrExcep();
		}
		//Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		//每次请求记录的条数（1-20条）
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		$p = array(
			'name' => $name, 
			'f' => $f, 	//分页标识（0：第一页，1：向下翻页，2向上翻页）
			'n' => $num, 	//每次请求记录的条数（1-20条）
			't' => $t 	//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		);
		$msg = MBGlobal::getApiClient()->getTimeline($p);
		$this->process(self::TIMELINE_TYPE_GUEST, "的广播", MB_SITE_NAME, $msg, "guest_timeline.view.php", $name);
		return;
	}
	
	/**
	 * 获取更多页
	 * @param $type
	 * @param $namn
	 * @return unknown_type
	 */
	protected function getMore($type, $name = '')
	{
		//Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		//每次请求记录的条数（1-20条）
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		//当前页最后一条记录，用用精确翻页用
		$l = MBValidator::getTidArg($_GET['lid'], "0");
		
		if($type == self::MORE_TYPE_MINE)		//我的广播
		{
			//type:0 提及我的, other 我发表的
			$p = array("type"=>1, "f"=>$f, "n"=>$num, "t"=>$t, "l"=>$l);
			$msg = MBGlobal::getApiClient()->getMyTweet($p);
		}
		elseif($type == self::MORE_TYPE_AT)		//提到我的
		{
			//type:0 提及我的, other 我发表的
			$p = array("type"=>0, "f"=>$f, "n"=>$num, "t"=>$t, "l"=>$l);
			$msg = MBGlobal::getApiClient()->getMyTweet($p);
		}
		elseif($type == self::MORE_TYPE_GUEST)		//客人页
		{
			$p = array("name"=>$name, "f"=>$f, "n"=>$num, "t"=>$t, "l"=>$l);
			$msg = MBGlobal::getApiClient()->getTimeline($p);
		}
		elseif($type == self::MORE_TYPE_FAV)		//我的收藏
		{
			$p = array("type"=>0, "f"=>$f, "n"=>$num, "t"=>$t, "l"=>$l);
			$msg = MBGlobal::getApiClient()->getFav($p);
			//添加收藏标志
			if(is_array($msg["data"]["info"]))
			{
				foreach($msg["data"]["info"] as &$t) 
				{
					$t["isfav"] = true;
				}
			}
		}
		else			//我的主页
		{
			$p = array("f"=>$f, "n"=>$num, "t"=>$t);
			$msg = MBGlobal::getApiClient()->getTimeline($p);
		}
		
		$data = $msg['data']['info'];
		if(is_array($data))
		{
			BaseMod::formatTArr($data);
		}
		//前台需要dom格式
		//hasfront hasnext:0 表示还有微博可拉取 1 已拉取完毕
		$data = BaseMod::renderView(
					array("t" => $data, "user"=>array("name"=>$_SESSION["name"]), "hasfront"=>1, "hasnext"=>$msg["data"]["hasnext"])
					, "/common/tbody.view.php"
					);
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, "", $data);
		exit();
	}
	/**
	 * 我的主页更多
	 * @return unknown_type
	 */
	public function getIndexMore()
	{
		BaseMod::clearNewMsgInfo(5);
		$this->getMore(self::MORE_TYPE_INDEX);
		exit();
	}
	
	/**
	 * 我的主页
	 * @return unknown_type
	 */
	public function indexAct()
	{	
		if(array_key_exists("u",$_GET)){
			$name = $_GET["u"];//主人微博帐号
		}else{
			$name = '';
		}
		
		if(empty($name))
		{
			unset($_GET["oauth_token"]);
			unset($_GET["oauth_verifier"]);
			MBUtil::location(MBUtil::getCurUrl( array( "u" => $_SESSION['name'] )));
		}
		
		if($name !=$_SESSION["name"])			//跳客人页
		{
			MBUtil::location("./index.php?m=guest&u=$name");
			exit();
		}
		
		//Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		//每次请求记录的条数（1-20条）
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		$p = array("f"=>$f, "n"=>$num, "t"=>$t);
		$msg = MBGlobal::getApiClient()->getTimeline($p);
		BaseMod::clearNewMsgInfo(5);
		$this->setUpdateInfo();
		$this->process(self::TIMELINE_TYPE_INDEX, "我的首页", MB_SITE_NAME, $msg, "user_home_timeline.view.php");
		return;
	}
	
	/**
	 * 我的广播更多
	 * @return unknown_type
	 */
	public function getMineMore()
	{
		$this->getMore(self::MORE_TYPE_MINE);
		exit();
	}
	
	/**
	 * 我的广播
	 * @return unknown_type
	 */
	public function mineAct()
	{
		//Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		//每次请求记录的条数（1-20条）
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		
		//type:0 提及我的, other 我发表的
		$p = array("type"=>1, "f"=>$f, "n"=>$num, "t"=>$t);
		
		$msg = MBGlobal::getApiClient()->getMyTweet($p);
		$this->setUpdateInfo();
		$this->process(self::TIMELINE_TYPE_MINE, "我的广播", MB_SITE_NAME, $msg, "user_public_timeline.view.php");
		return;
	}
	
	/**
	 * 提到我的更多
	 * @return unknown_type
	 */
	public function getAtMore()
	{
		BaseMod::clearNewMsgInfo(6);
		$this->getMore(self::MORE_TYPE_AT);
		exit();
	}
	
	/**
	 * 提到我的
	 * @return unknown_type
	 */
	public function atAct()
	{
		//Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		//每次请求记录的条数（1-20条）
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		//当前页最后一条记录，用用精确翻页用
		$l = MBValidator::getTidArg($_GET['lid'], "0");
		
		//type:0 提及我的, other 我发表的
		$p = array("type"=>0, "f"=>$f, "n"=>$num, "t"=>$t, "l"=>$l);
		$msg = MBGlobal::getApiClient()->getMyTweet($p);
		BaseMod::clearNewMsgInfo(6);
		$this->setUpdateInfo();
		$this->process(self::TIMELINE_TYPE_AT, "提到我的", MB_SITE_NAME, $msg, "user_mentions.view.php");
		return;
	}
	
	public function getFavMore()
	{
		$this->getMore(self::MORE_TYPE_FAV);
		exit();
	}
	
	/**
	 * 我的收藏
	 * @return unknown_type
	 */
	public function favorAct()
	{
		//Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
		$f = MBValidator::getNumArg($_GET["f"], 0, 2, 0);
		//每次请求记录的条数（1-20条）
		$num = MBValidator::getNumArg($_GET["num"], 1, 20, 20);
		//本页起始时间（第一页 0，继续：根据返回记录时间决定）
		$t = MBValidator::getNumArg($_GET["t"], 0, PHP_INT_MAX, 0);
		//当前页最后一条记录，用用精确翻页用
		$l = MBValidator::getTidArg($_GET['lid'], "0");
		$p = array(
			'type' => 0, //0 收藏的消息  1 收藏的话题
			'f' => $f, 	//分页标识（0：第一页，1：向下翻页，2向上翻页）
			'n' => $num, 	//每次请求记录的条数（1-20条）
			't' => $t, 	//本页起始时间（第一页 0，继续：根据返回记录时间决定）
			'l' => $l 	//当前页最后一条记录，用用精确翻页用
		);
		$msg = MBGlobal::getApiClient()->getFav($p);
		//添加收藏标志
		if(is_array($msg["data"]["info"]))
		{
			foreach($msg["data"]["info"] as &$t) 
			{
				$t["isfav"] = true;
			}
		}
		$this->setUpdateInfo();
		$this->process(self::TIMELINE_TYPE_FAV, "我的收藏", MB_SITE_NAME, $msg, "user_fav_timeline.view.php");
		return;
	}
	
	/**
	 * 公共主体逻辑
	 * @param $type
	 * @param $title
	 * @param $sitename
	 * @param $msg
	 * @param $template
	 * @param $name 目标用户的微博帐号
	 * @return unknown_type
	 */
	protected function process($type, $title, $sitename, $msg, $template, $name='')
	{
		//获取当前用户资料
		$myInfo = MBGlobal::getApiClient()->getUserInfo();
		//获取显示用户资料
		$userInfo = ($name ? MBGlobal::getApiClient()->getUserInfo(array("n" => $name)) : $myInfo);
		if(!empty($name))			//他人页
		{
			$title = $userInfo["data"]["nick"].$title;		//标题加上昵称
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
				$u = BaseMod::formatU($u, 50);
			}
		}
		
		//热门话题(默认取10条)
		$hotTopic = BaseMod::getHotTopic();
		
		//我订阅的话题(默认取15条)
		$myTopic = BaseMod::getMyTopic();
		
		//生成视图模版数据
		$data = array(
					"title"=>$title			//标题,可选,默认值"iWeibo"
					, "sitename"=>$sitename	//站点名字,可选
					);
		if(!empty($this->pageInfo))
		{
			$data["pageinfo"] = $this->pageInfo;
		}
		$myInfo["data"] = BaseMod::formatU($myInfo["data"], 120);
		$userInfo["data"] = BaseMod::formatU($userInfo["data"], 120);
		$data["user"] = $myInfo["data"];
		$data["screenuser"] = $userInfo["data"];
		$data["screenuser"]["idollist"] = $idollist;
		//$data["screenuser"]["isidol"] = $isidol;
		if(!empty($this->topicName))			//某个话题的信息
		{
			try 
			{
				$htRet = MBGlobal::getApiClient()->getTopicId(array("list" => $this->topicName));
				$htId = $htRet["data"]["info"][0]["id"];
				$htRet = MBGlobal::getApiClient()->getTopicList(array("list" => $htId));
				$htInfo = $htRet["data"]["info"][0];
				$htInfo["isfav"] = (boolean)$msg["data"]["isfav"];		//收听关系
				$data["ht"] = $htInfo;
			}
			catch(Exception $e)			//没有这个话题
			{
				$data["ht"] = array("id"=>0, "favnum"=>0, "tweetnum"=>0, "text"=>$this->topicName);
			}
		}
		if(!empty($this->recommUser))
		{
			$data["moduleconfig"]["tuijianyonghu"] = $this->recommUser;
		}
		if(!empty($this->recommToday))
		{
			$data["moduleconfig"]["jinrituijian"] = $this->recommToday;
		}
		if(!empty($this->updateInfo))
		{
			$data["updateinfo"] = $this->updateInfo;
		}
		if(isset($hotTopic))
		{
			$data["moduleconfig"]["remenhuati"] = $hotTopic;
		}
		
		$data["dingyue"] = $myTopic;
		$data["hasnext"] = $msg["data"]["hasnext"];
		$data["t"] = $msg["data"]["info"];
		if(is_array($data["t"]))
		{
			BaseMod::formatTArr($data["t"]);
			//我的收藏要特别处理
			if($type == self::TIMELINE_TYPE_FAV)
			{
				$count = count($msg["data"]["info"]);
				if(isset($msg["data"]["nexttime"]) && $count > 1)		//最后一条的收藏时间
				{
					$msg["data"]["info"][$count-1]["favtimestamp"] = $msg["data"]["nexttime"];
				}
			}
		}
		echo BaseMod::renderView($data, $template);
		return;
	}
	
	/**
	 * 设置数据更新条数
	 * @return unknown_type
	 */
	protected function setUpdateInfo()
	{
		try
		{
			$updateInfo = MBGlobal::getApiClient()->getUpdate(array("op" => 0));
			$this->updateInfo = $updateInfo["data"];
		}
		catch(MBException $e)
		{
			//do nothing
		}
	}
}
?>
