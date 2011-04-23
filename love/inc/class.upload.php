<?php
/*
功能：上传文件
返回：array(true|false,msg); false时，返回失败原因，true时，返回文件名

demo:

inputName：Form表单中file控件名
savePath：保存的目录
maxSize：允许上传的文件大小 单位KB
allowType：允许上传的文件后缀名数组
up('file1','') 控件名，新文件名（可选，不填则表示使用原文件名）

$upload = new UPLOAD(ROOT.'upload',200,array('gif','jpg','png','bmp','lua'));
print_r($upload->up('file1'));
*/
class UPLOAD{
	var $savePath,$maxSize,$allowType;
	function UPLOAD($savePath,$maxSize,$allowType) { 
		$this->savePath=$savePath; 
		$this->maxSize=$maxSize; 
		$this->allowType=$allowType;
	}
	
	function up($inputName,$saveName=''){
		if($_FILES[$inputName]['tmp_name']=='' || $_FILES[$inputName]['name']=='' || $_FILES[$inputName]['size']==0){
			return array(false,'上传数据读取错误');
		}
		
		$fileNameArray=explode('.',$_FILES[$inputName]['name']);   
		$fileType=strtolower($fileNameArray[count($fileNameArray)-1]);
		
		if(!in_array($fileType,$this->allowType)){
			return array(false,'上传文件类型错误，仅允许上传后缀名为'.implode(' .',$this->allowType).'的文件');  
		}
		
		if($_FILES[$inputName]['size']>$this->maxSize*1024){ 
			return array(false,'上传文件限制在'. $this->maxSize .'KB以内'); 
		}
		
		if(!file_exists($this->savePath)){   
			if(!mkdir($this->savePath,0777)){
				return array(false,'创建上传文件保存文件目录失败');
			}   
		}
		
		$save_name=$saveName&&$saveName!=''?$saveName.'.'.$fileType:$_FILES[$inputName]['name'];
		
		if(!move_uploaded_file($_FILES[$inputName]['tmp_name'],$this->savePath.DIRECTORY_SEPARATOR.$save_name)){
			return array(false,'文件上传过程中发生错误');
		}
		
		switch($_FILES[$formname]['error']){   
			case 0:   
				return array(true,$save_name);   
				break;   
			case 1:
				return array(false,'上传的文件超过了upload_max_filesize选项限制的值'); 
				break;   
			case 2:
				return array(false,'上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值');  
				break;   
			case 3:
				return array(false,'文件只有部分被上传'); 
				break;   
			case 4:
				return array(false,'没有文件被上传'); 
				break;   
			default:   
				return array(false,'未知错误'); 
				break;   
		}
	}
}
?>