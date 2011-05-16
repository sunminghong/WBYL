<?php
/**
 * 日志类
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/common/
 */
require_once 'file.class.php';

class MBLog
{
	//日志级别
	const LOG_LEVEL_DEBUG = 1;
	const LOG_LEVEL_INFO = 2;
	const LOG_LEVEL_WARN = 3;
	const LOG_LEVEL_ERROR = 4;
	const LOG_LEVEL_EMERG = 5;
	
	const LOG_CACHE_NUM = 10;		//缓存消息条数
	const NEW_LINE_FLAG = "\n";		//换行符
	
	//日志级别含义
	private $levelInfo = array(
					MBLog::LOG_LEVEL_DEBUG => "debug"
					, MBLog::LOG_LEVEL_INFO => "info"
					, MBLog::LOG_LEVEL_WARN => "warn"
					, MBLog::LOG_LEVEL_ERROR => "error"
					, MBLog::LOG_LEVEL_EMERG => "emerg");
	private $fileName = "";		//日志文件名
	private $handle;			//句柄
	private $stdout = false;		//是否标准输出
	private $useLog = false;		//是否打日志
	
	//构造的时候就确定module
	function __construct($logDir, $module, $useLog=false)
	{
		//不输出日志文件,否则后台header函数无法使用
		//$this->fileName = $logDir.DIRECTORY_SEPARATOR."mb_".$module."_".date('Ymd').".log";
		$this->useLog = $useLog;
	}
	
	/**
	 * 开启关闭日志
	 * @param $flag
	 * @return unknown_type
	 */
	public function setUseLog($useLog=true)
	{
		$this->useLog = (boolean)$useLog;
	}
	
	public function setStdout($stdout=true)
	{
		$this->stdout = $stdout;
	}
	
	public function debug($msg)
	{
		return $this->log($msg, MBLog::LOG_LEVEL_DEBUG);
	}
	
	public function info($msg)
	{
		return $this->log($msg, MBLog::LOG_LEVEL_INFO);
	}
	
	public function warn($msg)
	{
		return $this->log($msg, MBLog::LOG_LEVEL_WARN);
	}
	
	public function error($msg)
	{
		return $this->log($msg, MBLog::LOG_LEVEL_ERROR);
	}
	
	public function emerg($msg)
	{
		return $this->log($msg, MBLog::LOG_LEVEL_EMERG);
	}
	
	private function log($msg, $level=MBLog::LOG_LEVEL_DEBUG)
	{
		if(!$this->useLog)
		{
			return;
		}
		if(!isset($this->handle))
		{
			if($this->fileName!="")
			{
				$this->handle = new MBFile($this->fileName);
				if(!isset($this->handle))
				{
					return;
					//throw new MBException("new log fail|$this->fileName");
				}
			}
			else
			{
				return;
			}
		}
		
		$msg = date("Y-m-d H:i:s")."\t[".$this->getLevelStr($level)."]\t".$msg.MBLog::NEW_LINE_FLAG;
		if($this->stdout)
		{
			echo $msg;
			return;
		}
		else
		{
			return $this->handle->write($msg);
		}
	}
	
	private function getLevelStr($level)
	{
		if(!array_key_exists($level, $this->levelInfo))
		{
			$level = MBLog::LOG_LEVEL_DEBUG;
		}
		return $this->levelInfo[$level];	
	}
}
?>