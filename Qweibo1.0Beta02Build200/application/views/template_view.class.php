<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-06 03:06
 * Filename: template_view.class.php
 * Description: 
******************************************************************************/
if( !defined("MB_VIEWS_DIR") or !constant("MB_VIEWS_DIR") ){//确保模版路径正常
	define(MB_VIEWS_DIR,dirname(__FILE__));
}
require_once MB_VIEWS_DIR.'/template.class.php';

class TemplateView{
	/**
	 * 渲染普通模板
	 * @param {} 模板可以接受的数据结构
	 * @param {} 模板路径 
	 */
	public static function renderView( $template_vars, $template ){
		return Template::renderTemplate( $template_vars, $template );		
	}
	/**
	 *渲染网页没找到信息
	 *@param {} error模板参数,会覆盖默认值
	 *@return 模板渲染结果
	 */
	public static function http404( $template_vars=array() ){
		$default_template_vars = array(
			"title" => "页面不存在",
			"error"=>array(
				"message"=>"页面不存在",
				"code"=>"Http404",
				"showcode"=>false
			)
		);
		return Template::renderTemplate(array_merge($default_template_vars,$template_vars),"error.view.php");
	}
	/**
	 *渲染出错信息
	 *@param {} error模板参数,会覆盖默认值
	 *@return 模板渲染结果
	 */
	public static function error( $template_vars=array() ){
		$default_template_vars = array(
			"title" => "出错了",
		);
		return Template::renderTemplate(array_merge($default_template_vars,$template_vars),"error.view.php");
	}
	/**
	 *渲染网页跳转模板
	 *@param {} 404模板参数,会覆盖默认值
	 *@return 模板渲染结果
	 */
	public static function redirect( $template_vars=array() ){
		$default_template_vars = array(
			"title" => "自动跳转中",
		);
		return Template::renderTemplate(array_merge($default_template_vars,$template_vars),"redirect.view.php");
	}
}
?>
