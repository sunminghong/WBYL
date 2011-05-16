<?php
/**
 * 系统配置
 * @param 
 * @return
 * @author luckyxiang
 * @package /sys_config/
 */

//全局宏
define( "MB_ROOT_DIR", str_replace("\\","/",dirname(realpath(dirname(__FILE__)))) );
define( "MB_CONF_DIR", MB_ROOT_DIR."/config" );
define( "MB_INSTALL_DIR", MB_ROOT_DIR."/install" );
define( "MB_APP_DIR", MB_ROOT_DIR."/application" );
define( "MB_VIEWS_DIR", MB_ROOT_DIR."/application/views" );
define( "MB_COMM_DIR", MB_ROOT_DIR."/application/common" );
define( "MB_CTRL_DIR", MB_ROOT_DIR."/application/controller" );
define( "MB_MODEL_DIR", MB_ROOT_DIR."/application/model" );
define( "MB_VIEW_DIR", MB_ROOT_DIR."/application/views" );
define( "MB_ADMIN_DIR", MB_ROOT_DIR."/admin" );

define( "MB_RETURN_FORMAT" , 'json' );
define( "MB_API_HOST" , 'open.t.qq.com' );

//配置数组
$mbConfig = array();
//系统日志路径
$mbConfig["log_path"] = str_replace('/', DIRECTORY_SEPARATOR, MB_ROOT_DIR."/../logs");
error_reporting(0);//禁用错误报告
?>
