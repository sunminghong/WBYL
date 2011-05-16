<?php
/**
 * 工具类(业务无关)
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/common/
 */

class MBUtil
{
	/**
	 * 字符串转关联数组
	 * @param $str
	 * @param $s
	 * @param $g
	 * @return unknown_type
	 */
	static public function str2map($str, $s='&', $g='=')
	{
		$map = array();
		$arr = explode($s, $str);
		foreach($arr as $var) 
		{
			$pos = strpos($var, $g);
			if($pos == false)	//0 or false
			{
				continue;
			}
			$key = substr($var, 0, $pos);
			$value = substr($var, $pos + strlen($g));
			$map[$key] = $value;
		}
		return $map;
	}
	
	/**
	 * 关联数组转字符串
	 * @param $map
	 * @param $s
	 * @param $g
	 * @return string
	 */
	static public function map2str($map, $s='&', $g='=')
	{
		//var_dump($map);
		$str = "";
		foreach($map as $key => $value) 
		{
			if(!empty($str))
			{
				$str .= $s;
			}
			$str .= $key.$g.urlencode($value);
		}
		return $str;
	}
	
	/**
	 * 获取当前URL
	 * $addArgv 添加(覆盖)的参数
	 * $addArgv的key也需要是string
	 * @return unknown_type
	 */
	static public function getCurUrl($addArgv=NULL)
	{
		$url = 'http://'.$_SERVER['SERVER_NAME'];
		if($_SERVER["SERVER_PORT"] != 80)
		{
			$url .= ':'.$_SERVER["SERVER_PORT"];
		}
		if(empty($addArgv) || !is_array($addArgv))
		{
			$url .= $_SERVER["REQUEST_URI"];
			return $url;
		}
		else
		{
			$url .= $_SERVER["SCRIPT_NAME"];
			$url .= "?".self::map2str(array_merge($_GET, $addArgv));
			return $url;
		}
	}
	
	/**
	 * 302跳转
	 * @param $url
	 * @return unknown_type
	 */
	static public function location($url, $return=false)
	{
		$url = str_replace(array("%0d%0a","%0d","%0a"),"",$url);
		$url = str_replace(array("%0D%0A","%0D","%0A"),"",$url);
		header("Location: {$url}");
		if($return)
		{
			return;
		}
		else
		{
			exit();
		}
	}
	
	/**
	 * 获取二进制文件类型
	 * @param $bin	二进制文件的前两个字节
	 * @return string
	 */
	public static function getFileType($bin)
    {
        $strInfo  = @unpack("C2chars", $bin);
        
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        
        $fileType = 'unknown';
        switch ($typeCode)
        {
            case 7790:
                $fileType = 'exe';
                break;
            case 7784:
                $fileType = 'midi';
                break;
            case 8297:
                $fileType = 'rar';
                break;
            case 255216:
                $fileType = 'jpg';
                break;
            case 7173:
                $fileType = 'gif';
                break;
            case 6677:
                $fileType = 'bmp';
                break;
            case 13780:
                $fileType = 'png';
                break;
            default:
                $fileType = 'unknown';
        }
        return $fileType;
    }
    
    /**
     * 获取用户访问ip
     * @return unknown_type
     */
    public static function getClientIp()
    {
	    $clientIp = "";
	    if(isset($_SERVER))
	    {
	        if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
	        {
	            $clientIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
	        } 
	        elseif(isset($_SERVER["HTTP_CLIENT_IP"]))
	        {
	            $clientIp = $_SERVER["HTTP_CLIENT_IP"];
	        }
	        else
	        {
	            $clientIp = $_SERVER["REMOTE_ADDR"];
	        }
	    }
	    return $clientIp;
    }
    
    /**
     * url编码
     * @param $str
     * @return unknown_type
     */
    public static function urlEncode($str)
    {
    	return urlencode($str);
    }
	/**
     * trim 不支持字符串，这里实现了字符串
     * @param string $str 源字符串
     * @param string $sub 要去掉的字符串
     * @return string
     */
    public static function trim($str, $sub)
    {
        $len = strlen($str);
        $sublen = strlen($sub);
        $start = 0;
        while ($start + $sublen < $len &&  substr($str, $start,$sublen) == $sub)
        {
            $start += $sublen;
        }
        $end = $len;
        while($end - $sublen >= $start && substr($str, $end - $sublen,$sublen) == $sub)
        {
            $end -= $sublen;
        }
        return substr($str, $start,$end - $start);
    }
    
    /**
     * 定宽截取utf-8字符串
     * @param $string
     * @param $len		字节宽度
     * @return string
     */
    public static function widthUtfSubstr($string, $len = 0)
	{
		$str = $string;
		if ($len == 0)
		{
		    return $str;
		}
		
		if(strlen($str) <= $len)
		{
		    return $str;
		}
		
		for($i=0;$i<$len;$i++)
		{
		    $temp_str=substr($str,0,1);
		    if(ord($temp_str) > 127)
		    {
		        if(++$i<$len)
		        {
		            $new_str[]=substr($str,0,3);
		            $str=substr($str,3);
		        }
		    }
		    else
		    {
		        $new_str[]=substr($str,0,1);
		        $str=substr($str,1);
		    }
		}
		
		$result = join($new_str);
		return $result;
	}
	
	/**
     * 中文截字方法 utf-8
     * @param string $content
     * @param number $length 
     * @param string $add
     */
	public static function utfSubstr($string, $len=0, $etc='')
    {
        $str = $string;
        if ($len == 0)
        {
            return $str;
        }
        $len *= 2;
        if(strlen($str) <= $len)
        {
            return $str;
        }

        for($i=0;$i<$len;$i++)
        {
            $temp_str=substr($str,0,1);
            if(ord($temp_str) > 127)
            {
                $i++;
                if($i<$len)
                {
                    $new_str[]=substr($str,0,3);
                    $str=substr($str,3);
                }
            }
            else
            {
                $new_str[]=substr($str,0,1);
                $str=substr($str,1);
            }
        }

        $result = join($new_str);
        $result .= (strlen($result) == strlen($string)? '':$etc);
        return $result;
    }
}
?>