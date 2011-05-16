<?php
/**
 * 模块控制器公共应答码
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */

class BaseModRetCode
{
	//code(错误码100-499为公共错误码预留)
	const RET_SUCC = 0;
	const RET_DEFAULT_ERR = -1;
	const RET_MISS_ARG = 100;
	const RET_ARG_ERR = 101;
	const RET_OAUTH_ERR = 102;
	const RET_API_ERR = 103;
	const RET_API_FREQ_DENY = 104;
	const RET_API_OAUTH_ERR = 105;
	const RET_API_ARG_ERR = 106;
	const RET_API_INNER_ERR = 107;
	const RET_DATA_NA = 108;
	//msg:h_开头的表示异步调用时不展示给用户
	static public $ARR_MSG 
		= array(self::RET_SUCC => "成功"
				, self::RET_DEFAULT_ERR => "h_系统内部错误"
				, self::RET_MISS_ARG => "缺少参数"
				, self::RET_ARG_ERR => "参数错误"
				, self::RET_OAUTH_ERR => "h_系统鉴权错误"
				, self::RET_API_ERR => "接口调用失败"
				, self::RET_API_FREQ_DENY => "操作过于频繁，请稍后再试"
				, self::RET_API_OAUTH_ERR => "授权错误"
				, self::RET_API_ARG_ERR => "操作失败"
				, self::RET_API_INNER_ERR => "内部错误"
				, self::RET_DATA_NA => "对不起，您的页面暂时无法找到！");
				
	static public function getMsg($code)
	{
		return self::$ARR_MSG[$code];
	}
	
	//get json
	static public function getRetJson($code, $msg='', $data=NULL, $callback=NULL)
	{
		if(empty($msg))
		{
			$msg = "".self::$ARR_MSG[$code];
		}
		$jsonPrototype = array(
			"code" => $code,
			"msg" => $msg,
		 	"timestamp" => time()//返回接口调用的服务器时间戳 michal
		);
		
		if(isset($data))
		{
			$jsonPrototype["data"] = $data;
		}
		
		$json = json_encode($jsonPrototype);
		//验证回调函数合法性
		if(MBValidator::checkCallback($callback))
		{
			$json = $callback."(".$json.")";
		}
		return $json;
	}
}
?>