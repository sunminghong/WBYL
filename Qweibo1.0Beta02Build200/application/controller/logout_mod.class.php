<?php
/**
 * 退出
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */
require_once MB_CTRL_DIR.'/base_mod.class.php';

class LogoutMod extends BaseMod
{
	/**
	 * 覆写基类的检查登录,不做检查
	 * @see application/controller/BaseMod#checkLogin()
	 */
	public function checkLogin()
	{
		return;
	}
	
	/**
	 * 注销
	 * @return unknown_type
	 */
	public function logoutAct()
	{
		//销毁session
		$_SESSION = array();
		if (isset($_COOKIE[session_name()]))
		{
		    setcookie(session_name(), '', time()-42000, '/');
		}
		session_destroy();
		//重定向到首页
		MBUtil::location("./index.php");
		exit();
	}
}

?>