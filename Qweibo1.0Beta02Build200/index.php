<?php 
/**
 * 主入口
 * @param m[必选] 模块名称
 * @return	html页面
 * @author luckyxiang
 * @package /
 */
require_once './config/sys_config.php';
require_once MB_APP_DIR.'/app.class.php';
require_once MB_VIEW_DIR.'/template_view.class.php';

try
{
	MBApp::init();			//初始化
	MBApp::router();		//模块路由
	exit();
}
catch(MBOAuthExcep $e)
{
	//模块自身没控制好权限,跳首页
	MBUtil::location("/index.php");
	exit();
}
catch(MBException $e)
{
	if(strpos($_REQUEST["m"], "a_") === 0)		//异步调用
	{
		$json = BaseModRetCode::getRetJson($e->getCode(), $e->getMessage());
		//如果有回调函数则是伪ajax调用，需要加上script标签
		if(MBValidator::checkCallback($_REQUEST["callback"]))
		{
			$json = "<script>".$_REQUEST["callback"]."(".$json.")</script>";
		}
		echo $json;
		exit();
	}
	else			//跳转错误页面
	{
		//$msg = $e->getMessage();
		//$code = $e->getCode();
		$arr["error"] = array(
				"message" => "访问页面出错"
				, "code" => -1
				, "showcode" => false);
		echo TemplateView::error($arr);
		exit();
	}
}
catch(Exception $e)
{
	echo TemplateView::error();
	exit();
}
?>
