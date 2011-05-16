<?php
/**
 * 收藏模块
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */
require_once MB_CTRL_DIR.'/base_mod.class.php';
require_once MB_COMM_DIR.'/validator.class.php';

class FavMod extends BaseMod
{
	const FAV_TYPE_ADDT = 1;
	const FAV_TYPE_DELT = 0;
	const FAV_TYPE_ADDTOPIC = 1;
	const FAV_TYPE_DELTOPIC = 0;
	
	protected function addDelT($type)
	{
		$tid = $_GET["tid"];		//微博id
		if(!MBValidator::isTId($tid))
		{
			echo BaseModRetCode::getRetJson(BaseModRetCode::RET_ARG_ERR);
			exit();
		}
		
		MBGlobal::getApiClient()->postFavMsg(array("type"=>$type, "id"=>$tid));
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC);
		exit();
	}
	
	/**
	 * 收藏一条微博
	 * @return unknown_type
	 */
	public function addT()
	{
		$this->addDelT(self::FAV_TYPE_ADDT);
		exit();
	}
	
	/**
	 * 删除一条微博收藏
	 * @return unknown_type
	 */
	public function delT()
	{
		$this->addDelT(self::FAV_TYPE_DELT);
		exit();
	}
	
	protected function addDelTopic($type)
	{
		$tid = $_GET["tid"];		//话题id
		if(!MBValidator::isTopicId($tid))
		{
			echo BaseModRetCode::getRetJson(BaseModRetCode::RET_ARG_ERR);
			exit();
		}
		
		MBGlobal::getApiClient()->postFavTopic(array("type"=>$type, "id"=>$tid));
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC);
		exit();
	}
	
	/**
	 * 收藏(订阅)话题
	 * @return unknown_type
	 */
	public function addTopic()
	{
		$this->addDelTopic(self::FAV_TYPE_ADDTOPIC);
		exit();
	}
	
	/**
	 * 删除(退订)话题
	 * @return unknown_type
	 */
	public function delTopic()
	{
		$this->addDelTopic(self::FAV_TYPE_DELTOPIC);
		exit();
	}
	
	/**
	 * 加载更多订阅的话题
	 * @return unknown_type
	 */
	public function getMoreFavTopic(){
		//从第一条开始拉取15条数据
		$default_paras = array(
			'type' => 1, //0 收藏的消息  1 收藏的话题
			'f' => 1, 	//向下翻页
			'n' => 11, 	//每次请求11条数据，实际显示每次显示10条，如第11条存在即可翻页
			't' => 0, 	//本页起始时间（第一页 0，继续：根据返回记录时间决定）
			'l' => 0 	//当前页最后一条记录，用用精确翻页用
		);
		
		$lasttime = trim($_GET["lasttime"]);
		$lastid = trim($_GET["lid"]);
		
		if(empty($lasttime) || empty($lastid))
		{
			throw new MBMissArgExcep();
		}
		
		$default_paras["t"] = $lasttime;
		$default_paras["l"] = $lastid;
		
		$myTopic = MBGlobal::getApiClient()->getFav($default_paras);
		$myTopicOk = array();
		if(is_array($myTopic["data"]["info"]))
		{
			foreach($myTopic["data"]["info"] as $var) 
			{
				array_push($myTopicOk, 
						array("name"=>$var["text"], "id"=>$var["id"], "count"=>$var["tweetnum"],"timestamp"=>$var["timestamp"]));
			}
		}
		$data = BaseMod::renderView(
				array(
				  "dingyue" => $myTopicOk
				)
				,"/common/fav_topic_list.view.php");
		echo BaseModRetCode::getRetJson(BaseModRetCode::RET_SUCC, "", $data);
		exit();
	}
}
?>