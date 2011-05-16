<?php
/**
 * 公共异常类
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/common/
 */
require_once MB_COMM_DIR.'/base_mod_retcode.class.php';

/**
 * 微博异常
 * @author luckyxiang
 *
 */
class MBException extends Exception
{
	public function __construct($msg="unknown err", $code=-1)
	{
		parent::__construct($msg, $code);
	}
	
	public function __toString()
    {
        ob_start();
        var_dump($this);
        $trace = ob_get_contents();
        ob_end_clean();
        return $trace;
    }
}

/**
 * 缺少参数异常
 * @author luckyxiang
 *
 */
class MBMissArgExcep extends MBException
{
	public function __construct($msg='')
	{
		//$msg = BaseModRetCode::getMsg(BaseModRetCode::RET_MISS_ARG).(empty($msg) ? "" : "($msg)");
		parent::__construct($msg, BaseModRetCode::RET_MISS_ARG);
	}
}

/**
 * API调用参数错误异常
 * @author luckyxiang
 *
 */
class MBAPIArgErrExcep extends MBException
{
	public function __construct($msg='')
	{
		$msg = BaseModRetCode::getMsg(BaseModRetCode::RET_API_ARG_ERR).(empty($msg) ? "" : "($msg)");
		parent::__construct($msg, BaseModRetCode::RET_API_ARG_ERR);
	}
}

/**
 * 参数错误异常
 * @author luckyxiang
 *
 */
class MBArgErrExcep extends MBException
{
	public function __construct($msg='')
	{
		//$msg = BaseModRetCode::getMsg(BaseModRetCode::RET_ARG_ERR).(empty($msg) ? "" : "($msg)");
		parent::__construct($msg, BaseModRetCode::RET_ARG_ERR);
	}
}

/**
 * 系统鉴权错误异常
 * @author luckyxiang
 *
 */
class MBOAuthExcep extends MBException
{
	public function __construct()
	{
		parent::__construct(BaseModRetCode::getMsg(BaseModRetCode::RET_OAUTH_ERR)
		, BaseModRetCode::RET_OAUTH_ERR);
	}
}

/**
 * API接口调用频率限制异常
 * @author luckyxiang
 *
 */
class MBAPIFreqDenyExcep extends MBException
{
	public function __construct()
	{
		parent::__construct(BaseModRetCode::getMsg(BaseModRetCode::RET_API_FREQ_DENY)
		, BaseModRetCode::RET_API_FREQ_DENY);
	}
}

/**
 * API接口授权异常
 * @author luckyxiang
 *
 */
class MBAPIOAuthExcep extends MBException 
{ 
	public function __construct()
	{
		parent::__construct(BaseModRetCode::getMsg(BaseModRetCode::RET_API_OAUTH_ERR)
		, BaseModRetCode::RET_API_OAUTH_ERR);
	}
}

/**
 * API接口调用异常(json解码失败)
 * @author luckyxiang
 *
 */
class MBAPIExcep extends MBException 
{ 
	public function __construct()
	{
		parent::__construct(BaseModRetCode::getMsg(BaseModRetCode::RET_API_ERR)
		, BaseModRetCode::RET_API_ERR);
	}
}

/**
 * API接口内部异常
 * @author luckyxiang
 *
 */
class MBAPIInnerExcep extends MBException 
{ 
	public function __construct($msg='')
	{
		parent::__construct(BaseModRetCode::getMsg(BaseModRetCode::RET_API_INNER_ERR).(empty($msg) ? "" : "($msg)")
		, BaseModRetCode::RET_API_INNER_ERR);
	}
}

/**
 * 数据无效异常
 * @author luckyxiang
 *
 */
class MBDataNAExcep extends MBException
{
	public function __construct()
	{
		parent::__construct(BaseModRetCode::getMsg(BaseModRetCode::RET_DATA_NA)
		, BaseModRetCode::RET_DATA_NA);
	}
}
?>