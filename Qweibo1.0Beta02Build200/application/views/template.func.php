<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-11-21 03:36
 * Filename: template.func.php
 * Description: 模板系统通用函数库
******************************************************************************/
/**
 *声明变量为可选的
 *@param {} 变量名
 *@param {} 变量默认值
 *@throw 无
 *@return 变量值或变量默认值
 */
function optional(&$check,$default){
	$check = (isset($check)) ? $check : $default;
	return $check;
}
/**
 *声明变量为自动型的
 *说明:预留接口
 *
 */
function auto(&$var){

}
/**
 *声明变量为模板所必需的
 *@param {} 变量名
 *@param {} 变量名字符串,为提高性能,需要手工输入变量名,抛出异常时返回给用户异常信息
 *@param {} 应该为__FILE__,抛出异常时提供给用户debug信息
 *@throw TemplateException
 *@return 无
 */
function required(&$check,$varname,$file){
	if( !isset($check) ){
		throw new TemplateException($varname." is declared required in file ".$file." Template ".Template::$templatePath);
	}
}
/**
 *连接路径
 *@param {} 参数列表,部分路径
 *@return 完整路径
 */
function pathJoin(){
	$func_args = func_get_args();
	return join(DIRECTORY_SEPARATOR,$func_args);
}
/**
 * 判定机构、性别
 * @param{Array} "isent"代表企业用户  "sex" 0 未知  1 男  2 女
 * @return 性别描述
 */
function getSexDesc( $arr ){
	if(($arr["isent"])){
		return "它";
	}else{
		if($arr["sex"])
		{return ($arr["sex"] == 2) ? "她":"他";}
		else
		{return "它";}
	}
	return "";
}
/*
 * 截断超长字符串，本方法计算字符串长度中文算1 英文算0.5
 * @param {String} 要截断的字符串
 * @param {Number} 字符串最大长度
 * @param {String} 字符串结尾
 * @return {String} 格式化后的字符串
 */
function limit( $str, $maxlen, $suff = "..." ){
	$i = 0;
	$c = 0;
	$strlen = strlen($str);
	while ($i < $strlen) {
		$istep = 0;
		if( ord( $str[$i] ) > 127 ){
			$istep = 3;
			$i+=$istep;
			$c++;
		}else{
			$istep = 1;
			$i+=$istep;
			$c+=0.5;
		}
	
		if( $c > $maxlen ){
			return substr($str,0,$i-$istep).$suff;
		}
	}
	return $str;
}
/*
 * 截断超长HTML字符串
 * TODO:恢复HTML标签
 * @param $html 把字符串当作HTML处理
 * @param $maxlen	最大长度
 * @param $fill	是否用空白填充至最大长度
 * @param $suff	字符串末尾添加的字符串
 * @return {String} 格式化后的html字符串
 */
function limitHTML( $html, $maxlen, $fill=True, $suff="..."){
	$str = strip_tags($html,$allowed_tags="<span>");
	$i = 0;
	$c = 0;
	$strlen = strlen($str);
	while ($i < $strlen) {
		$istep = 0;
		if( ord( $str[$i] ) > 127 ){
			$istep = 3;
			$i+=$istep;
			$c++;
		}else{
			$istep = 1;
			$i+=$istep;
			$c+=0.5;
		}
		if( $c > $maxlen ){
			return substr($str,0,$i-$istep).$suff;
		}
	}
	//$maxlen > $c
	if($fill){//填充一个汉字的位置
		$str .= "<span style=\"visibility:hidden\">";
		for( $j=0;$j<($maxlen-$c);$j++ ){
			$str .= "空";
		}
		$str .= "</span>";
	}
	return $str;
}
?>
