<?php
/**
 * 搜索模块控制器
 * @param 
 * @return
 * @author sheeppu,luckyxiang
 * @package /application/controller/
 */

require_once MB_CTRL_DIR.'/base_mod.class.php';

class SearchMod extends BaseMod
{
	private $pageSize = 15;			//每页15条
	private $pageInfo = array();	//上一页下一页信息
	
	/**
	 * 搜索功能是否开启 
	 * @return boolean
	 */
	private function isSearchEnabled(){
		@include_once MB_ADMIN_DIR.'/data/search.php';
		if( isset($search) && $search == "0" ){
			return false;
		}
		return true;
	}
	/**
	 * 用户搜索
	 * @return unknown_type
	 */
	public function searchUserAct()
	{
		if(!self::isSearchEnabled()){
			echo TemplateView::http404();
			exit();
		}
		$searchKey = trim(htmlspecialchars_decode($_GET["k"]));
		if(!isset($searchKey))
		{
			throw new MBMissArgExcep("k");
		}
		if(!MBValidator::checkSearchKey($searchKey))
		{
			throw new MBArgErrExcep("k");
		}
		$pageNum = MBValidator::getNumArg($_GET["pagenum"], 1, PHP_INT_MAX, 1);
		$p = array(
				"k" => $searchKey		//搜索关键字
				, "n" => $this->pageSize	//每页显示信息的条数
				, "p" => $pageNum			//页码,从1开始
				, "type" => 0				//0 用户  1 微博  2话题 
				);
				
		//获取当前用户资料
		$userInfo = MBGlobal::getApiClient()->getUserInfo();
		//搜索用户
		$userRet = MBGlobal::getApiClient()->getSearch($p);
		//分页信息
		$this->setPageInfo($userRet["data"]["totalnum"], $pageNum);
		//热门话题(默认取10条)
		$hotTopic = BaseMod::getHotTopic();
		//模版数据
		$data = array(
					"title" => "用户搜索结果"
					, "sitename"=>MB_SITE_NAME
					, "searchkey" => htmlspecialchars($searchKey)
					, "pageinfo" => $this->pageInfo
					);
		$userInfo["data"] = BaseMod::formatU($userInfo["data"], 120);
		$data["user"] = $userInfo["data"];
		if(isset($hotTopic))
		{
			$data["moduleconfig"]["remenhuati"] = $hotTopic;
		}
		$data["unum"] = $userRet["data"]["totalnum"];
		$data["u"] = $userRet["data"]["info"];
		if(is_array($data["u"]))
		{
			foreach($data["u"] as &$u)
			{
				$u = BaseMod::formatU($u, 50,$highlight=$searchKey);
			}
		}
		else
		{
			$data["u"] = NULL;
			$data["unum"] = 0;
		}
		echo BaseMod::renderView($data,'search_user_result.view.php');
		return;
	}
	
	/**
	 * 微博广播搜索
	 * @return unknown_type
	 */
	public function searchTAct()
	{
		if(!self::isSearchEnabled()){
			echo TemplateView::http404();
			exit();
		}
		$searchKey = trim(htmlspecialchars_decode($_GET["k"]));
		if(!isset($searchKey))
		{
			throw new MBMissArgExcep("k");
		}
		if(!MBValidator::checkSearchKey($searchKey))
		{
			throw new MBArgErrExcep("k");
		}
		$pageNum = MBValidator::getNumArg($_GET["pagenum"], 1, PHP_INT_MAX, 1);
		$p = array(
				"k" => $searchKey		//搜索关键字
				, "n" => $this->pageSize	//每页显示信息的条数
				, "p" => $pageNum			//页码,从1开始
				, "type" => 1				//0 用户  1 微博  2话题 
				);
				
		//获取当前用户资料
		$userInfo = MBGlobal::getApiClient()->getUserInfo();
		//搜索广播
		$tRet = MBGlobal::getApiClient()->getSearch($p);
		//分页信息
		$this->setPageInfo($tRet["data"]["totalnum"], $pageNum);
		//热门话题(默认取10条)
		$hotTopic = BaseMod::getHotTopic();
		//模版数据
		$data = array(
					"title" => "广播搜索结果"
					, "sitename"=>MB_SITE_NAME
					, "searchkey" => htmlspecialchars($searchKey)
					, "pageinfo" => $this->pageInfo
					);
		$userInfo["data"] = BaseMod::formatU($userInfo["data"], 120);
		$data["user"] = $userInfo["data"];
		if(isset($hotTopic))
		{
			$data["moduleconfig"]["remenhuati"] = $hotTopic;
		}
		$data["tnum"] = $tRet["data"]["totalnum"];
		$data["t"] = $tRet["data"]["info"];
		if(is_array($data["t"]))
		{
			BaseMod::formatTArr($data["t"], 50, 160, $searchKey, true );
		}
		
		echo BaseMod::renderView($data,'search_t_result.view.php');
		return;
	}
	
	/**
	 * 综合搜索
	 * @return unknown_type
	 */
	public function searchAllAct()
	{
		if(!self::isSearchEnabled()){
			echo TemplateView::http404();
			exit();
		}
		$searchKey = trim(htmlspecialchars_decode($_GET["k"]));
		if(!isset($searchKey))
		{
			throw new MBMissArgExcep("k");
		}
		if(!MBValidator::checkSearchKey($searchKey))
		{
			throw new MBArgErrExcep("k");
		}
		$pageNum = MBValidator::getNumArg($_GET["pagenum"], 1, PHP_INT_MAX, 1);
		$p = array(
				"k" => $searchKey			//搜索关键字
				, "n" => 2					//每页显示信息的条数
				, "p" => $pageNum			//页码,从1开始
				, "type" => 0				//0 用户  1 微博  2话题 
				);
				
		//获取当前用户资料
		$userInfo = MBGlobal::getApiClient()->getUserInfo();
		//搜索用户
		$userRet = MBGlobal::getApiClient()->getSearch($p);
		//搜索微博
		$p["n"] = $this->pageSize;
		$p["type"] = 1;
		$tRet = MBGlobal::getApiClient()->getSearch($p);
		//分页信息
		$this->setPageInfo($tRet["data"]["totalnum"], $pageNum);
		//热门话题(默认取10条)
		$hotTopic = BaseMod::getHotTopic();
		//模版数据
		$data = array(
					"title" => "综合搜索结果"
					, "sitename" => MB_SITE_NAME
					, "searchkey" => htmlspecialchars($searchKey)
					, "pageinfo" => $this->pageInfo
					);
		$userInfo["data"] = BaseMod::formatU($userInfo["data"], 120);
		$data["user"] = $userInfo["data"];
		if(isset($hotTopic))
		{
			$data["moduleconfig"]["remenhuati"] = $hotTopic;
		}
		$data["unum"] = $userRet["data"]["totalnum"];
		$data["u"] = $userRet["data"]["info"];
		if(is_array($data["u"]))
		{
			foreach($data["u"] as &$u) 
			{
				$u = BaseMod::formatU($u, 50, $highlight=$searchKey);
			}
		}
		else
		{
			$data["u"] = NULL;
			$data["unum"] = 0;
		}
		$data["tnum"] = $tRet["data"]["totalnum"];
		$data["t"] = $tRet["data"]["info"];
		if(is_array($data["t"]))
		{
			BaseMod::formatTArr($data["t"], 50, 160, $searchKey,true );
		}
		
		echo BaseMod::renderView($data,'search_all_result.view.php');
		return;
	}
	
	/**
	 * 设置上一页下一页信息
	 * @return unknown_type
	 */
	private function setPageInfo($totalNum, $curPageNum)
	{
		//上一页下一页
		$frontUrl = $nextUrl = "";
		$totalnum = intval($totalNum);
		$allPageNum = intval($totalnum/$this->pageSize);
		if($totalnum%$this->pageSize > 0)
		{
			$allPageNum++;
		}
		if($curPageNum < $allPageNum)
		{
			$nextPageNum = $curPageNum+1;
			$nextUrl = MBUtil::getCurUrl(array("pagenum" => $curPageNum+1));
		}
		if($curPageNum > 1)
		{
			$frontUrl = MBUtil::getCurUrl(array("pagenum" => $curPageNum-1));
		}
		$this->pageInfo = array("fronturl" => $frontUrl, "nexturl"=>$nextUrl);
	}
}
?>
