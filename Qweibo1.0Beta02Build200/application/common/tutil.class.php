<?php
/**
 * 微博广播工具类
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/common/
 */

class MBTUtil
{
	private static $replacePattern = "/(#\s*[^#\s]{1}[^#]{0,59}?\s*#)|(@[A-Za-z][A-Za-z0-9_-]{0,19}(?![A-Za-z0-9_-]))|(http:\/\/url\.cn\/[A-Za-z0-9]{6}\b)/";
	
	/**
	 * 格式化微博广播
	 * @param $content
	 * @return unknown_type
	 */
	public static function tContentFormat($content)
	{
		$content = htmlspecialchars($content);
		$content = preg_replace_callback(self::$replacePattern, array("MBTUtil", "replaceCallback"), $content);
		return $content;
	}
	
	/**
	 * 微博内容替换回调函数
	 * @param $matches
	 * @return unknown_type
	 */
	private static function replaceCallback($matches)
    {
        $match = $matches[0];
        if($match[0] == '@')		//@xxx
        {
        	$account = substr($match, 1);
        	return '<em rel="'.$match.'"><a href="./index.php?m=guest&u='
        		.$account.'" title="'.$match.'">'.$match.'</a></em>';
        }
        elseif($match[0] == '#')	//#这个话题#
        {

            //话题
            $topic = trim(substr($match, 1,-1));
            $topic = trim(MBUtil::trim($topic, "　"));
            
            if(strlen($topic) == 0 || $topic == "." || mb_strlen($topic,'UTF-8') > 20)
            {
                return $match;
            }
            $key = self::keywordEncode($topic);
            return '<a href="./index.php?m=topic&k='.$key.'">#'.htmlspecialchars($topic).'#</a>';
        }
        elseif(substr($match, 0, 4) == "http")		//短URL
        {
        	return "<a href=\"$match\" target=\"_blank\">$match</a>";
        }
        return $match;
    }
	
	/**
	 * timestamp转换成显示时间格式
	 * @param $timestamp
	 * @return unknown_type
	 */
	public static function tTimeFormat($timestamp)
    {
        $curTime = time();
        $space = $curTime - $timestamp;
        //1分钟
        if ($space < 60)
        {
            $string = "刚刚";
            return $string;
        }
        elseif ($space < 3600)//一小时前
        {
            $string = floor($space / 60) . "分钟前";
            return $string;
        }
        $curtimeArray = getdate($curTime);
        $timeArray = getDate($timestamp);
        if($curtimeArray['year'] == $timeArray['year'])
        {
            if ($curtimeArray['yday'] == $timeArray['yday'])
            {
                $format = "%H:%M";
                $string = strftime($format, $timestamp);
                return "今天 {$string}";
            }
            elseif(($curtimeArray['yday'] - 1) == $timeArray['yday'])
            {
                $format = "%H:%M";
                $string = strftime($format, $timestamp);
                return "昨天 {$string}";
            } 
            else
            {
                $string = sprintf("%d月%d日 %02d:%02d", $timeArray['mon'], $timeArray['mday'],
                                $timeArray['hours'], $timeArray['minutes']);
                return $string;
            }
        }
        $string = sprintf("%d年%d月%d日 %02d:%02d", $timeArray['year'], $timeArray['mon'], $timeArray['mday'],
                        $timeArray['hours'], $timeArray['minutes']);
        return $string;
    }
    
    /**
     * 关键字编码
     * @param $key
     * @return unknown_type
     */
	public static function keywordEncode($key)
    {
        return rawurlencode(str_replace(array('/','?','&','#'), array('%2F','%3F','%26','%23'), $key));
    }
}
?>