<?php
/**
 * API发表接口错误字段errcode
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */

class ApiErrCode
{
	const RET_SUCC = 0;
	const RET_BAD_LANGUAGE = 4;
	const RET_FORBID = 5;
	const RET_DELETED = 6;
	const RET_TOO_LONG = 8;
	const RET_BAD_MESSAGE = 9;
	const RET_FREQ_DENY = 10;
	const RET_SRC_DELETED = 11;
	const RET_CHECKING = 12;
	const RET_REPEAT = 13;
	
	//msg
	static public $ARR_MSG 
		= array(self::RET_SUCC => "成功"
				, self::RET_BAD_LANGUAGE => "发表内容中含敏感词，请重新输入"
				, self::RET_FORBID => "禁止访问"
				, self::RET_DELETED => "此消息不存在"
				, self::RET_TOO_LONG => "内容超过最大长度"
				, self::RET_BAD_MESSAGE => "所在网络有安全问题，请稍候重试"
				, self::RET_FREQ_DENY => "发表过于频繁，请稍候再发"
				, self::RET_SRC_DELETED => "原文已被删除"
				, self::RET_CHECKING => "原文已被删除"
				, self::RET_REPEAT => "请不要连续发送重复的内容");
				
	static public function getMsg($code)
	{
		if(key_exists($code, self::$ARR_MSG))
		{
			return self::$ARR_MSG[$code];
		}
		return "发表失败";
	}
}
?>