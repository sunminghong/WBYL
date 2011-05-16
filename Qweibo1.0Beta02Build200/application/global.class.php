<?php
/**
 * 动态全局变量类
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/
 */
require_once MB_COMM_DIR.'/log.class.php';
require_once MB_MODEL_DIR.'/api_client.class.php';
require_once MB_ADMIN_DIR.'/inc/admin_act.php';
require_once MB_ADMIN_DIR.'/conn.php';

class MBGlobal
{
	static private $logger;
	static private $apiClient;
	
	static public function getLogger()
	{
		if(!isset(self::$logger))
		{
			throw new MBException("logger is null");
		}
		return self::$logger;	
	}
	
	static public function setLogger($logger)
	{
		self::$logger = $logger;
	}
	
	/**
	 * 获取API调用的客户端
	 * @return unknown_type
	 */
	static public function getApiClient()
	{
		if(isset(self::$apiClient))
		{
			return self::$apiClient;
		}
		//必须要获取访问授权先
		if(!isset($_SESSION["access_token"]))
		{
			throw new MBOAuthExcep();
		}
		self::$apiClient = new MBApiClient(MB_AKEY, MB_SKEY, $_SESSION["access_token"], $_SESSION["access_token_secret"]);
		return self::$apiClient;
	}
	
	/**
	 * 获取管理模块过滤功能对象
	 * @return unknown_type
	 */
	static public function getAdminFilter()
	{
		static $filter = NULL;
		if(!isset($filter))
		{
			$filter = new MBAdminFilter(MB_DATABASE_URL, MB_DATABASE_USER
									, MB_DATABASE_PASW, MB_DATABASE_NAME);
		}
		return $filter;
	}
	
	/**
	 * 获取管理模块推荐功能对象
	 * @return unknown_type
	 */
	static public function getAdminRecomm()
	{
		static $recomm = NULL;
		if(!isset($recomm))
		{
			$recomm = new MBAdminRecomm(MB_DATABASE_URL, MB_DATABASE_USER
									, MB_DATABASE_PASW, MB_DATABASE_NAME);
		}
		return $recomm;
	}
	
	/**
	 * 获取管理模版显示对象
	 * @return unknown_type
	 */
	static public function getAdminShow()
	{
		static $show = NULL;
		if(!isset($show))
		{
			$show = new MBAdminShow(MB_DATABASE_URL, MB_DATABASE_USER
									, MB_DATABASE_PASW, MB_DATABASE_NAME);
		}
		return $show;
	}
}
?>