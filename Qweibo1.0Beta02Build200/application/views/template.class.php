<?php
/****************************************************************************
 * Author: michal
 * Last modified: 2010-11-19 13:59
 * Filename: template.class.php
 * Description: 模板读取及渲染,异常处理模块 
 *****************************************************************************/

if(!defined('TEMPLATE_DIR')) { 
    define("TEMPLATE_DIR", MB_VIEWS_DIR."/templates"); 
}

require_once MB_VIEWS_DIR.'/template.func.php';
require_once MB_VIEWS_DIR.'/template_exception.class.php';

class Template{
	//当前模板物理路径
	public static $templatePath="";
	//有些模板不应该被直接渲染，比如基础模板，或测试模板
	public static $templateBlacklist=array(
		"base_1col.view.php",
		"base_2col.view.php",
		"base_index.view.php",
		"base_index_4pages.view.php",
		"base_search.view.php",
		);
	/**
	 *渲染模板
	 *@param {} 模板所需的数据表
	 *@param {} 模板路径
	 *@param {} 可选参数
	 *@return 模板渲染结果
	 */
	public static function renderTemplate( $template_vars, $template_path, $options=null ){
		self::$templatePath = pathJoin( TEMPLATE_DIR, $template_path );
		if( !file_exists( self::$templatePath ) ){
			throw new TemplateException( "模板不存在".self::$templatePath );	
		}
		/*if( in_array($template_path,self::$templateBlacklist) ){
			throw new TemplateException( "禁止直接渲染该模板 ".self::$templatePath);
		}*/
		if( !is_array( $template_vars )){
			throw new TemplateException( "模板参数应该是一个数组" );
		}
		if( !count( $template_vars ) > 0 ){
			trigger_error( "模板参数为空", E_USER_NOTICE);
		}
		//render the template in context of template_vars
		extract($template_vars,EXTR_OVERWRITE);
		ob_start();
		require self::$templatePath;
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

}
?>
