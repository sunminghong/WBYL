<?php
/**
 * 模块控制器基类
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */
require_once MB_COMM_DIR.'/util.class.php';
require_once MB_MODEL_DIR.'/opent.class.php';
require_once MB_COMM_DIR.'/base_mod_retcode.class.php';
require_once MB_VIEW_DIR.'/template_view.class.php';
require_once MB_COMM_DIR.'/tutil.class.php';
require_once MB_COMM_DIR.'/validator.class.php';
require_once MB_COMM_DIR.'/ttype.class.php';

class BaseMod
{
	protected static $hotTopicNum = 10;			//热门话题数量
	
	/**
	 * 检查是否登录
	 * 默认需要登录,子类可以覆写
	 * @return unknown_type
	 */
	public function checkLogin()
	{
		//只检查是否有access_token
		if(!isset($_SESSION["access_token"]))			//无访问授权
		{
			if(isset($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']))	//第二步回调
			{
				if(!isset($_SESSION["request_token"]))		//理论上不大可能
				{
					throw new MBException();
				}
				$requestToken = $_REQUEST['oauth_token'];
				//TODO 检查token合法性
				if($_SESSION["request_token"] != $requestToken)	//本次回调已经失效
				{
					throw new MBOAuthExcep();
				}
				$oauthVerifier = $_REQUEST['oauth_verifier'];
				//TODO 检查verifier合法性
				$_SESSION["oauth_verifier"] = $oauthVerifier;
				//授权第三步
				$oauth = new MBOpenTOAuth(MB_AKEY, MB_SKEY, $_SESSION["request_token"], $_SESSION["request_token_secret"]);
				$lastKeys = $oauth->getAccessToken($oauthVerifier) ;
				$_SESSION['access_token'] = $lastKeys["oauth_token"];
				$_SESSION['access_token_secret'] = $lastKeys["oauth_token_secret"];
				$_SESSION['name'] = $lastKeys["name"];			//用户帐号
				return;
			}
			else		//启动授权第一、二步
			{
				$oauth = new MBOpenTOAuth(MB_AKEY , MB_SKEY);
				$callbackUrl = MBUtil::getCurUrl();
				$keys = $oauth->getRequestToken($callbackUrl);

				if(empty($keys['oauth_token']) || empty($keys['oauth_token_secret']))
				{
					throw new MBException("授权失败");
				}
				//保存session
				$_SESSION["request_token"] = $keys['oauth_token'];
				$_SESSION["request_token_secret"] = $keys['oauth_token_secret'];
				//跳转获取用户授权
				$url = $oauth->getAuthorizeURL($keys['oauth_token'], false);
				MBUtil::location($oauth->getAuthorizeURL($keys['oauth_token'] ,false));
				exit();
			}
		}
		return;
	}
	
	/**
	 * 格式化单个用户信息(from api)
	 * @param $t
	 * @param $headSize		50/100/120
	 * @return unknown_type
	 */
	protected static function formatU($u, $headSize, $highlight='')
	{
		if(!empty($u["head"]))
		{
			$u["head"] .= "/$headSize";
		}
		
		if(!empty($highlight)){
			$highlightKeywords = preg_split("/[\s,]+/",$highlight);
		}
		
		//高亮含有关键字的昵称,只针对原创
		if(isset($highlightKeywords)){
				foreach($highlightKeywords as &$kw){
					$kw = preg_quote($kw);
				}
				$highlightPattern =  "-(".join("|",$highlightKeywords).")-i";
				$highlightReplacement = "<span style=\"color:#e56c0a;\">$1</span>";
				$u["nick"] = preg_replace($highlightPattern,$highlightReplacement,$u["nick"]);
				$u["name"] = preg_replace($highlightPattern,$highlightReplacement,$u["name"]);
		}
		
		$u["timestring"] = MBTUtil::tTimeFormat($u["timestamp"]);
		
		//最近广播
		if(is_array($u["tweet"]))
		{
			foreach($u["tweet"] as &$t) 
			{
				$t["text"] = MBTUtil::tContentFormat($t["text"]);
				$t["timestring"] = MBTUtil::tTimeFormat($t["timestamp"]);
			}
		}
		if(isset($u["Ismyidol"]))		//模版要求
		{
			$u["ismyidol"] = (boolean)$u["Ismyidol"];
		}
		if(isset($u["isidol"]))		//模版要求
		{
			$u["ismyidol"] = (boolean)$u["isidol"];
		}
		if(isset($u["Ismyfans"]))		//模版要求
		{
			$u["ismyfans"] = (boolean)$u["Ismyfans"];
		}
		if(isset($u["Ismyblack"]))		//模版要求
		{
			$u["ismyblack"] = (boolean)$u["Ismyblack"];
		}
		if(isset($u["introduction"]))
		{
			$u["introduction"] = htmlspecialchars($u["introduction"]);
		}
		return $u;
	}
	
	
	/**
	 * 获取管理平台屏蔽信息的正则模式
	 * @param $keyWordPattern
	 * @param $tPattern
	 * @param $userPattern
	 * @return unknown_type
	 */
	protected static function getAdminFilterPattern(&$keyWordPattern, &$tPattern, &$userPattern)
	{
		try
		{
			//需要过滤的关键字
			$keyWordPattern = "";
			$filterKeyWord = MBGlobal::getAdminFilter()->showKeyWord(0, 100, 1);//showKeyWord不能使用include_once否则第二次取不到数据，其它同
			if(!empty($filterKeyWord["list"]))
			{
				foreach($filterKeyWord["list"] as &$fkw){
					$fkw = preg_quote($fkw);
				}
				$keyWordPattern = '/'.implode('|', $filterKeyWord["list"]).'/i';
			}

			$tPattern = "";
			//需要过滤的微博id
			$filterT1 = MBGlobal::getAdminFilter()->showT(0, 100, 1);
			//需要过滤的微博评论
			$filterT2 = MBGlobal::getAdminFilter()->showRepost(0, 100, 1);
			$filterT = array_merge((array)$filterT1["list"], (array)$filterT2["list"]);
			if(!empty($filterT))
			{
				$tPattern = '/'.implode('|', $filterT).'/';
			}
			//需要过滤的用户(暂时不支持)
			$userPattern = "";
			/*
			$filterUser = MBGlobal::getAdminFilter()->showUser(0, 100, 1);
			if(!empty($filterUser["list"]))
			{
				$userPattern = '/'.implode('|', $filterUser["list"]).'/';
			}*/
		}
		catch(Exception $e)
		{
			$keyWordPattern = "";
			$tPattern = "";
			$userPattern = "";
		}
		return;
	}
	/**
	 * 批量格式化微博广播
	 * @param $tArr
	 * @param $headSize
	 * @param $imageSize
	 * @param $blackKeyArr
	 * @return unknown_type
	 */
	protected static function formatTArr(&$tArr, $headSize=50, $imageSize=160, $highlight='', $filterDeleted = false)
	{
		BaseMod::getAdminFilterPattern($keyWordPattern, $tPattern, $userPattern);//屏蔽
		//遍历
		foreach($tArr as &$t) 
		{
			if( $filterDeleted && self::isDeletedTweet($t) ){//过滤已删除的广播，如搜索页
				$t = array();
				continue;
			}
			$t = self::formatT($t, $headSize, $imageSize, $highlight
							, $keyWordPattern, $tPattern, $userPattern);
		}
		return;
	}
	
	/**
	 * 是否是已删除的广播
	 * @param $taArr
	 * @return unknown_type
	 */
	private static function isDeletedTweet( $tArr ){
		$deletedFlag = !empty($tArr["status"]) && $tArr["status"] != 0;
		$trimText = trim($tArr["text"]);
		$hasSource = array_key_exists("source",$tArr);
		$emptyContent = empty($trimText) && !$hasSource;//转播内容允许为空
		return $deletedFlag || $emptyContent;
	}
	/**
	 * 格式化单条微博广播(from api)
	 * @param $t
	 * @param $headSize		默认timeline的50
	 * @param $imageSize	默认timeline的160
	 * @param $keyWordPattern	要屏蔽的关键字正则匹配串
	 * @param $tPattern		要屏蔽的微博id正则匹配串
	 * @param $userPattern	要屏蔽的微博用户帐号正则匹配串
	 * @return unknown_type
	 */
	protected static function formatT($t, $headSize=50, $imageSize=160, $highlight=''
								, $keyWordPattern='', $tPattern='', $userPattern='')
	{//TODO:可合并代码
		if(!empty($highlight)){
			$highlightKeywords = preg_split("/[\s,]+/",$highlight);
		}
		
		$needReplace = false;			//该原创广播是否需要被屏蔽
		$referedNeedReplace = false;    //引用的广播是否需要屏蔽
		//转播内容处理
		if(!empty($t["source"]))
		{
			if(self::isDeletedTweet($t["source"]))//转播的消息已在微博平台上被删除
			{
				$t["source"]["text"] = "<font color=\"#999999\">此消息已被删除</font>";
			}else{//正常原文
				if(!empty($t["source"]["text"]))
				{//有原文
					$referedNeedReplace = (!empty($keyWordPattern) && preg_match($keyWordPattern, $t["source"]["text"])) || ( !empty($tPattern) && preg_match($tPattern, "a".$t["source"]["id"]."a") );
					if(!$referedNeedReplace){
						$t["source"]["text"] = MBTUtil::tContentFormat($t["source"]["origtext"]);//前台使用text字段，后台使用origtext字段
					}else{
						$t["source"]["text"] = "<font color=\"#999999\">此消息已被网站管理员屏蔽</font>";
						$t["source"]["image"] = "";
					}
				}
			}	
			if(!empty($t["source"]["origtext"]))
			{
				$t["source"]["origtext"] = "";			//无用
			}
			if(!empty($t["source"]["head"]))
			{
				$t["source"]["head"] .= "/$headSize";
			}
			if(!empty($t["source"]["image"]))
			{
				$t["source"]["image"] = $t["source"]["image"][0]."/$imageSize";
			}
			$t["source"]["frommobile"] = ($t["source"]["from"]=="手机");
			$t["source"]["timestring"] = MBTUtil::tTimeFormat($t["source"]["timestamp"]);	
		}
		//原创内容处理
		if(self::isDeletedTweet($t)){
				$t["text"] = "<font color=\"#999999\">此消息已被删除</font>";
		}else{
			$needReplace = (!empty($keyWordPattern) && preg_match($keyWordPattern, $t["text"])) || (!empty($tPattern) && preg_match($tPattern, "a".$t["id"]."a"));//加a做精确匹配
			if(!$needReplace){
				$t["text"] = MBTUtil::tContentFormat($t["origtext"]);
				//高亮关键字,只针对原创
				if(isset($highlightKeywords)){
					$highlightPattern =  "-(".join("|",$highlightKeywords).")-i";
					$highlightReplacement = "<span style=\"color:#e56c0a;\">$1</span>";
					$t["text"] = preg_replace($highlightPattern,$highlightReplacement,$t["text"]);
				}
			}else{//需要屏蔽
				$t["text"] = "<font color=\"#999999\">此消息已被网站管理员屏蔽</font>";
				$t["image"] = "";
			}
		}
		if(!empty($t["origtext"]))
		{
			$t["origtext"] = "";		//无用
		}
		if(!empty($t["head"]))
		{
			$t["head"] .= "/$headSize";
		}
		if(!empty($t["image"]))
		{
			$t["image"] = $t["image"][0]."/$imageSize";
		}
		$t["frommobile"] = ($t["from"]=="手机");
		$t["timestring"] = MBTUtil::tTimeFormat($t["timestamp"]);
		//管理平台用户过滤(精确匹配)
		/*
		if(!empty($userPattern))
		{
			if(preg_match($userPattern, $t["name"]))
			{
				$needReplace = true;
			}
		}*/
		return $t;
	}
	
	/**
	 * 获取热门话题列表
	 * @return unknown_type
	 */
	protected static function getHotTopic()
	{
		$hotTopicOk = NULL;
		//热门话题title
		$mod = MBGlobal::getAdminRecomm()->showMod(1);
		if($mod["list"][2]["status"] == 1)
		{
			$hotTopicOk = array();
			$hotTopicOk["name"] = htmlspecialchars((string)$mod["list"][2]["n"]);
			$hotTopicOk["data"] = array(); 
			//只看管理平台有无定制
			$hotTopic = MBGlobal::getAdminRecomm()->showHotTopic(0, self::$hotTopicNum, 1);
			
			$hotTopicIdList = array(
				"list"=>""
			);
			
			if(is_array($hotTopic["list"]) && count($hotTopic["list"] > 0))
			{
				foreach($hotTopic["list"] as $var) 
				{
					$hotTopicIdList["list"].=(string)$var["id"].",";
					array_push($hotTopicOk["data"], array("name"=>htmlspecialchars($var["text"]),"id"=>(string)$var["id"],"count"=>""));		//话题微博数量暂不支持
				}
			}
			
			
			if(count($hotTopicIdList["list"]) > 0){
				try{//根据话题id取话题下广播数量
					$hotTopicCount = MBGlobal::getApiClient()->getTopicList($hotTopicIdList);
					$hotTopicCountMap = array();//存储id与收听人数的对应关系				
					if( !empty($hotTopicCount["data"]["info"]) && count($hotTopicCount["data"]["info"])>0 ){
						foreach($hotTopicCount["data"]["info"] as $htCount){
							$hotTopicCountMap[(string)$htCount["id"]] = $htCount["tweetnum"];
						}
						//重新设置热门话题收听数量值
						foreach( $hotTopicOk["data"] as &$htopic){
							$htopic["count"]=$hotTopicCountMap[$htopic["id"]];
						}
					}
				}catch(Exception $e){//取热门话题订阅人数失败,静默失败
					
				}
			}
		}
		return $hotTopicOk;
	}
	
	/**
	 * 获取我订阅的话题
	 * @return unknown_type
	 */
	protected static function getMyTopic($f=0, $num=11, $t=0, $l='')
	{
		$p = array(
			'type' => 1, //0 收藏的消息  1 收藏的话题
			'f' => $f, 	//分页标识（0：第一页，1：向下翻页，2向上翻页）
			'n' => $num, 	//每次请求记录的条数（1-20条）
			't' => $t, 	//本页起始时间（第一页 0，继续：根据返回记录时间决定）
			'l' => $l 	//当前页最后一条记录，用用精确翻页用
		);
		$myTopic = MBGlobal::getApiClient()->getFav($p);
		$myTopicOk = array();
		if(is_array($myTopic["data"]["info"]))
		{
			foreach($myTopic["data"]["info"] as $var) 
			{
				array_push($myTopicOk, 
						array("name"=>$var["text"], "id"=>$var["id"], "count"=>$var["tweetnum"],"timestamp"=>$var["timestamp"]));
			}
		}
		return $myTopicOk;
	}
	
	/**
	 * 拉取当前用户和访问用户的信息
	 * @param $name
	 * @return array[当前用户信息,访问用户信息]
	 */
	protected static function getUserInfo($name)
	{
		if(empty($name))
		{
			$userInfo = MBGlobal::getApiClient()->getUserInfo();
			return array($userInfo["data"]["info"], $userInfo["data"]["info"]);
		}
		else
		{
			$userInfo = MBGlobal::getApiClient()->getUserInfo();
			$p = array("n" => $name);
			$userInfo2 = MBGlobal::getApiClient()->getUserInfo($p);
			return array($userInfo["data"]["info"], $userInfo2["data"]["info"]);
		}
	}
	
/**
     * 是否有前一页后一页
     * @param $pageFlag 分页标识（0：第一页，1：向下翻页，2向上翻页）
     * @param $hasNext	api返回的是否有后一页 0 表示还可拉取 1 已拉取完毕
     * @param $preUrl	html页面上一页的url（空为不显示）
     * @param $nextUrl html页面下一页的url（空为不显示）
     * @param $frontPageTime 上一页起始时间戳
     * @param $nextPageTime 下一页起始时间戳
     * @param $frontLid
     * @param $nextLid
     * @return unknown_type
     */
    public static function hasFrontNextPage($pageFlag, $hasNext, &$frontUrl
    									, &$nextUrl, $frontPageTime='', $nextPageTime=''
    									, $frontLid='', $nextLid='')
    {
	    $frontUrl = "";
		$nextUrl = "";
		if ($pageFlag == 0 && $hasNext === 0)
		{
			$frontUrl = "";
			$addArgv = array("f"=>1);
			if(!empty($nextPageTime))
			{
				$addArgv["t"] = $nextPageTime;
			}
			if(!empty($nextLid))
			{
				$addArgv["lid"] = $nextLid;
			}
			$nextUrl = MBUtil::getCurUrl($addArgv);
		}
		elseif ($pageFlag == 1)		//下翻
		{
			$addArgv = array("f"=>2);
			if(!empty($frontPageTime))
			{
				$addArgv["t"] = $frontPageTime;
			}
			if(!empty($frontLid))
			{
				$addArgv["lid"] = $frontLid;
			}
			$frontUrl = MBUtil::getCurUrl($addArgv);
			if ($hasNext === 0)
			{
				$addArgv = array("f"=>1);
				if(!empty($nextPageTime))
				{
					$addArgv["t"] = $nextPageTime;
				}
				if(!empty($nextLid))
				{
					$addArgv["lid"] = $nextLid;
				}
				$nextUrl = MBUtil::getCurUrl($addArgv);
			}
		}
		elseif ($pageFlag == 2)		//上翻
		{
			$addArgv = array("f"=>1);
			if(!empty($nextPageTime))
			{
				$addArgv["t"] = $nextPageTime;
			}
			if(!empty($nextLid))
			{
				$addArgv["lid"] = $nextLid;
			}
			$nextUrl = MBUtil::getCurUrl($addArgv);
			if ($hasNext === 0)
			{
				$addArgv = array("f"=>2);
				if(!empty($frontPageTime))
				{
					$addArgv["t"] = $frontPageTime;
				}
				if(!empty($frontLid))
				{
					$addArgv["lid"] = $frontLid;
				}
				$frontUrl = MBUtil::getCurUrl($addArgv);
			}
		}
    }
    
    /**
     * 视图接口的封装
     * @param $templateVars
     * @param $template
     * @return unknown_type
     */
	public static function renderView($templateVars, $template)
	{
		//logo
		$logoUrl = MBGlobal::getAdminShow()->getLogo();
		if(!empty($logoUrl))
		{
			$templateVars["logourl"] = $logoUrl;
		}
		//页尾
		ob_start();
		@include_once MB_ADMIN_DIR.'/data/footer.php';
		$footstring = ob_get_contents();
		ob_end_clean();
		if(!empty($footstring))
		{
			$templateVars["footstring"] = $footstring;
		}
		//搜索是否开启
		@include_once MB_ADMIN_DIR.'/data/search.php';
		$templateVars["enablesearch"] = true;
		if( isset($search) && $search == "0" ){
			$templateVars["enablesearch"] = false;
		}
		/*
		$page = MBGlobal::getAdminShow()->getPage();
		if(!empty($page["page_tail_text"]))
		{
			$templateVars["footstring"] = $page["page_tail_text"];
		}*/
		return TemplateView::renderView($templateVars, $template);
	}
	
	/**
	 * 清除新消息提示
	 * @param $type 5 首页未读消息记数，6 @页消息记数 7 私信页消息计数 8 新增粉丝数 9 首页广播数（原创的）
	 * @return unknown_type
	 */
	public function clearNewMsgInfo($type)
	{
		if($type < 5 || $type > 9)
		{
			return;
		}
		try
		{
			MBGlobal::getApiClient()->getUpdate(array("op" => 1, "type" => $type));
		}
		catch(MBException $e)
		{
			return;
		}
	}
}
?>