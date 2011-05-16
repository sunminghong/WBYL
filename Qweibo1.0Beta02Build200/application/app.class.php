<?php
/**
 * 应用类
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/
 */

class MBApp
{
	//用户自定义的初始化函数
	static private $userAppInitFunc = array();
	//模块信息
	//[{模块名称=>[模块类,动作函数,需要包含php文件(仅限于controller目录下,多个用;分隔)]},...]
	static private $moduleInfo = array(
		//*************************同步接口************************
		//默认是我的主页
		"default"=>array("MainMod", "indexAct", "main_mod.class.php")
		//我的主页
		, "index"=>array("MainMod", "indexAct", "main_mod.class.php")
		//我的广播
		, "mine" => array("MainMod", "mineAct", "main_mod.class.php")
		//提到我的
		, "at" => array("MainMod", "atAct", "main_mod.class.php")
		//我的收藏
		, "favor" => array("MainMod", "favorAct", "main_mod.class.php")
		//话题广播
		, "topic" => array("MainMod", "topicAct", "main_mod.class.php")
		//广播大厅
		, "public" => array("MainMod", "publicAct", "main_mod.class.php")
		//查看他人
		, "guest" => array("MainMod", "guestAct", "main_mod.class.php")
		//私信收件箱
		, "inbox" => array("BoxMod", "inboxAct", "box_mod.class.php")
		//私信发件箱
		, "sendbox" => array("BoxMod", "sendboxAct", "box_mod.class.php")
		//显示单条微博
		, "showt"=>array("TMod", "showTAct", "t_mod.class.php")
		//显示对话
		, "showdialog"=>array("TMod", "showDialogAct", "t_mod.class.php")
		//我/ta的听众
		, "follower"=>array("FriendMod", "followerAct", "friend_mod.class.php")
		//我/ta的收听
		, "following"=>array("FriendMod", "followingAct", "friend_mod.class.php")
		//综合搜索
		, "searchall" => array("SearchMod", "searchAllAct", "search_mod.class.php")
		//用户搜索
		, "searchuser" => array("SearchMod", "searchUserAct", "search_mod.class.php")
		//广播搜索
		, "searcht" => array("SearchMod", "searchTAct", "search_mod.class.php")
		//用户资料
		, "userinfo"=>array("UserMod", "userInfoAct", "user_mod.class.php")
		//用户头像
		, "userhead"=>array("UserMod", "userHeadAct", "user_mod.class.php")
		//退出
		, "logout"=>array("LogoutMod", "logoutAct", "logout_mod.class.php")
		//*************************异步接口************************
		//我的主页更多
		, "a_indexmore"=>array("MainMod", "getIndexMore", "main_mod.class.php")
		//我的广播更多
		, "a_minemore"=>array("MainMod", "getMineMore", "main_mod.class.php")
		//提到我的更多
		, "a_atmore"=>array("MainMod", "getAtMore", "main_mod.class.php")
		//我的收藏更多
		, "a_favmore"=>array("MainMod", "getFavMore", "main_mod.class.php")
		//客人页的更多
		, "a_guestmore"=>array("MainMod", "getGuestMore", "main_mod.class.php")
		//今日推荐用户
		, "a_recommut"=>array("MainMod", "getRecommUT", "main_mod.class.php")
		//发表/对话/转播/评论微博
		, "a_addt"=>array("TMod", "add", "t_mod.class.php")
		//删除微博
		, "a_delt"=>array("TMod", "del", "t_mod.class.php")
		//单条微博转发列表
		, "a_relist"=>array("TMod", "getReList", "t_mod.class.php")
		//查看新消息更新条数
		, "a_newmsginfo"=>array("TMod", "getNewMsgInfo", "t_mod.class.php")
		//增加微博收藏
		, "a_addfavt"=>array("FavMod", "addT", "fav_mod.class.php")
		//删除微博收藏
		, "a_delfavt"=>array("FavMod", "delT", "fav_mod.class.php")
		//增加话题收藏
		, "a_addfavtopic"=>array("FavMod", "addTopic", "fav_mod.class.php")
		//删除话题收藏
		, "a_delfavtopic"=>array("FavMod", "delTopic", "fav_mod.class.php")
		//订阅话题更多
		, "a_favtopicmore"=>array("FavMod", "getMoreFavTopic", "fav_mod.class.php")
		//发私信
		, "a_boxadd" => array("BoxMod", "add", "box_mod.class.php")
		//删除私信
		, "a_boxdel" => array("BoxMod", "del", "box_mod.class.php")
		//收听/取消收听某个用户
		, "a_follow" => array("FriendMod", "follow", "friend_mod.class.php")
		//检测是否我粉丝或偶像
		, "a_checkfriend" => array("FriendMod", "check", "friend_mod.class.php")
		);
		
		
	static private $isInit = false;			//是否初始化
	static private $module;				//模块名
	
	/**
	 * 系统初始化
	 * @return unknown_type
	 */
	static public function init()
	{
		@include_once MB_CONF_DIR.'/user_config.php';
		if(MB_INSTALLED !== true)
		{
			header("Location: ./install/index.php");
			exit();
		}
		session_start();
		//时区定义
		date_default_timezone_set('Asia/Chongqing');
		
		//包含必须头文件(配置在index.php已经包含)
		require_once MB_COMM_DIR.'/log.class.php';
		require_once MB_COMM_DIR.'/util.class.php';
		require_once MB_APP_DIR.'/global.class.php';
		
		//获取模块信息
		if(!array_key_exists("m",$_REQUEST)){//没有指定mod
			if(!array_key_exists("u",$_GET)){//没有指定user
				$urlKeys = array_keys($_GET);
				$firstKey = $urlKeys[0];
				if($firstKey != "u" || $firstKey != "m"){
					$host  = $_SERVER['HTTP_HOST'];
					$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
					$extra = 'index.php?u='.$firstKey;
					$newlocation = "http://$host$uri/$extra";
					MBUtil::location($newlocation);
				}
			}else{
				self::$module = "";
			}
		}else{
			self::$module = "".$_REQUEST["m"];
		}
		
		if(!array_key_exists(self::$module, self::$moduleInfo))
		{
			self::$module = "default";
			$_REQUEST["m"] = "default";			//处理异常时有用
		}
		
		//设置logger
		global $mbConfig;
		$logger = new MBLog($mbConfig["log_path"], self::$module);
		MBGlobal::setLogger($logger);
		
		self::$isInit = true;
		return;
	}
	
	/**
	 * 模块路由
	 * @return unknown_type
	 */
	static public function router()
	{
		$className = self::$moduleInfo[self::$module][0];		//处理类名
		$funcName = self::$moduleInfo[self::$module][1];		//处理函数名
		$incFileNames = self::$moduleInfo[self::$module][2];	//需要包含的php文件
		
		$incFileArr = explode(";", $incFileNames);
		foreach($incFileArr as $incFile) 
		{
			//固定包含controller目录下的文件
			require_once MB_CTRL_DIR."/".$incFile;
		}
		//先默认检查是否登录
		$classObj = new $className;
		call_user_func(array($classObj, "checkLogin"));
		//再执行业务逻辑
		call_user_func(array($classObj, $funcName));
	}
}
?>
