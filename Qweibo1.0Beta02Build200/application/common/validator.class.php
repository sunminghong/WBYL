<?php
/**
 * 验证类
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/common/
 */

class MBValidator
{
	
	/**
	 * 验证json回调函数名
	 * @param $callback
	 * @return unknown_type
	 */
	public static function checkCallback($callback)
	{
		if(empty($callback))
		{
			return false;
		}
		if(preg_match("/^[a-zA-Z_][a-zA-Z0-9_\.]*$/", $callback))
		{
			return true;
		}
		return false;
	}
	
	/**
	 * 验证话题id(64位无符号整数)
	 * @param $tid
	 * @return unknown_type
	 */
	public static function isTopicId($tid)
	{
		if(preg_match('/^[1-9][0-9]{0,19}$/',$tid))
        {
            return true;
        }
        return false;
	}
	
	/**
	 * 验证微博id(64位无符号整数)
	 * @param $tid
	 * @return unknown_type
	 */
	public static function isTId($tid)
    {
    	if(preg_match('/^[1-9][0-9]{0,19}$/',$tid))
        {
            return true;
        }
        return false;
    }
    
    /**
     * 是否是微博帐号
     * @param $account
     * @return unknown_type
     */
 	public static function isUserAccount($account)
    {
        if(preg_match('/^[A-Za-z][A-Za-z0-9_\-]{0,19}$/',$account))
        {
            return true;
        }
        return false;
    }

    /**
     * 是否是微博昵称
     * @param $nick
     * @return unknown_type
     */
    public static function isUserNick($nick)
    {
        $len = strlen($nick);
        if($len == 0 || $len > 36 || preg_match("/[^\x{4e00}-\x{9fa5}\w\-\&]/u",$nick) == 1)
        {
            return false;
        }
        return true;
    }
    
    /**
     * 是否是话题名称
     * @param $topic
     * @return unknown_type
     */
    public static function isTopicName($topic)
    {
    	$len = strlen($topic);
    	if($len <= 0 || $len > 280)
    	{
    		return false;
    	}
    	return true;
    }
    
    /**
     * 检查搜索关键字的合法性
     * @return unknown_type
     */
    public static function checkSearchKey($key)
    {
    	$len = strlen($key);
    	if($len > 420)
    	{
    		return false;
    	}
    	return true;
    }
    
    /**
     * 检查微博广播的合法性
     * @param $t
     * @return unknown_type
     */
    public static function checkT($t)
    {
    	$len = strlen($t);
    	if($len < 0 || $len > 420)
    	{
    		return false;
    	}
    	return true;
    }
    
    /**
     * 检查上传文件的合法性
     * @param $file
     * @return unknown_type
     */
	public static function isUploadFile(&$file) 
    {
    	if(empty($file))
    	{
    		return false;
    	}
        $fileSize = intval($file['size']);
        $tmpFile = $file['tmp_name'];
    
        if ($fileSize < 0 || $file['error'] > 0 || empty ($tmpFile)) 
        {
            return false;
        }
        if (!is_uploaded_file($tmpFile)) 
        {
            return false;
        }
        return true;
    }
    
    /**
     * 获取数值参数
     * @param $num 输入数值
     * @param $min 最小值
     * @param $max 最大值
     * @param $default 默认值，为空表示必须有
     * @return unknown_type
     */
    public static function getNumArg($num, $min, $max, $default=NULL)
    {
    	$n = intval($num);
    	if(!isset($num) || ($n < $min || $n > $max))
    	{
    		if(isset($default))
    		{
    			return $default;
    		}
    		throw new MBMissArgExcep();
    	}
    	
    	return $n;
    }
    
    /**
     * 获取微博id参数
     * @param $tId
     * @return unknown_type
     */
    public static function getTidArg($tId, $default=NULL)
    {
		if(!isset($tId))
		{
			if(isset($default))
			{
				return $default;
			}
			else
			{
				throw new MBMissArgExcep();
			}
		}
		if(!MBValidator::isTId($tId))
		{
			throw new MBArgErrExcep();
		}
		return $tId;
    }
    
    /**
     * 获取微博帐号参数
     * @param $name 微博帐号
     * @return unknown_type
     */
    public static function getUserAccount($name)
    {
    	if(!isset($name))
		{
			throw new MBMissArgExcep();
		}
		if(!MBValidator::isUserAccount($name))
		{
			throw new MBArgErrExcep();
		}
		return $name;
    }
}
?>