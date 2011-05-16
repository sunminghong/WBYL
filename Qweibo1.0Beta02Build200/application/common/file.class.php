<?php
/**
 * 文件处理类
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/common/
 */
require_once 'exception.class.php';

class MBFile
{
	private $fileName = "";		//文件全路径
	private $isOpen = false;	//文件是否打开
	private $handle = null;		//文件句柄
	
	function __construct($fileName)
	{
		$this->fileName = $fileName;
	}
	
	function __destruct()
	{
		$this->close();
	}
	
	function open($mode='a+')
	{
		if($this->isOpen)
		{
			return true;
		}
		$this->handle = fopen($this->fileName, $mode);
		if($this->handle === false)
		{
			$this->handle = null;
			return false;
		}
		return true;
	}
	
	function close()
	{
		if($this->isOpen && isset($this->handle))
		{
			$ret = fclose($this->handle);
			if($ret)
			{
				$this->isOpen = false;
			}
			return $ret;
		}
		return true;
	}
	
	function read()
	{
		//TODO
	}
	
	function write($string)
	{
		if(!$this->isOpen && !$this->open())
		{
			throw new MBException("file is not open");
		}
		return fwrite($this->handle, $string);
	}
}
?>