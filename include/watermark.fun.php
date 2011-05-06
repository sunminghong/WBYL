

<?php
/*
imageWaterText($backImage,$text,$color='#000000',$posX,$posY,$fontSize,$fontFile,$newName='')
在images上增加text水印

$backImage 要处理的图片，只支持gif，jpg，png格式
$test 水印文字内容
$color 水印文字颜色
$posX,$posY 水印文字坐标
$fontSize 水印文字大小
$fontFile 水印文字字体

$newName 可选，生成新图片的名字，为空则覆盖原图片
=====================================================================================================
imageWaterPic($backImage,$waterPic,$posX,$posY,$newName='')
在image上增加图片水印

$backImage 要处理的图片
$waterPic 水印文件
$posX,$posY 水印坐标
$newName 可选，生成新图片的名字，为空则覆盖原图片
*/
function imageWaterText($backImage,$text,$color='#000000',$posX,$posY,$fontSize,$fontFile,$newName=''){
	#判断字体文件是否存在
	if(empty($fontFile) || !file_exists($fontFile)){
		die("字体文件读取失败"); 
	}
	#判断颜色数据
	if(!empty($color) && (strlen($color)==7)){ 
		$R = hexdec(substr($color,1,2)); 
		$G = hexdec(substr($color,3,2)); 
		$B = hexdec(substr($color,5)); 
	}else{ 
		die("水印文字颜色格式不正确！"); 
	}		
	#读取背景图片 
	if(!empty($backImage) && file_exists($backImage)){ 
		$ground_info = getimagesize($backImage); 
			
		switch($ground_info[2])//取得背景图片的格式 
		{ 
			case 1:$ground_im = imagecreatefromgif($backImage);break; 
			case 2:$ground_im = imagecreatefromjpeg($backImage);break; 
			case 3:$ground_im = imagecreatefrompng($backImage);break; 
			default:die('需要增加水印的图片格式不支持'); 
		} 
	}else{ 
		die("需要加水印的图片不存在"); 
	}
	
	imagealphablending($ground_im, true);
	imagettftext($ground_im,$fontSize,0,$posX,$posY,imagecolorallocate($ground_im,$R,$G,$B),$fontFile,$text);
	 
	//@unlink($backImage); 
	$saveName = (empty($newName)?$backImage:$newName);
	
	switch($ground_info[2])//取得背景图片的格式 
	{ 
		case 1:imagegif($ground_im,$saveName);break; 
		case 2:imagejpeg($ground_im,$saveName);break; 
		case 3:imagepng($ground_im,$saveName);break; 
		default:die('生成图片失败'); 
	} 
	//释放内存
	unset($ground_info); 
	imagedestroy($ground_im);
}

function downUrlImage($waterPic){
	$i=0;
	while(($i++)<4) {
		if($tmp_img=@file_get_contents($waterPic)){
			if(ISSAE)
				$waterPic = tempnam(SAE_TMP_PATH, "SAE_IMAGE");
			else
				$waterPic= tempnam(ROOT."data/tmp", "SAE_IMAGE");
			
			if(@file_put_contents($waterPic, $tmp_img)) { 
				return $waterPic;
			}
			
		}
		sleep(1);  //延迟执行1s
	}
	return false;
}

function imageWaterPic($backImage,$waterPic,$posX,$posY,$newName=''){	
	#读取水印文件 
	$isUrl=false;
	if(strpos($waterPic,"http://")==0){
		if(!($waterPic=downUrlImage($waterPic))) return false;		
		$isUrl=true;		
	}
	if(file_exists($waterPic)){ 
		$water_info = getimagesize($waterPic); 
	}else{
		return false;
	}
	$water_w = $water_info[0];//取得水印图片的宽 
	$water_h = $water_info[1];//取得水印图片的高 

	switch($water_info[2])//取得水印图片的格式 
	{ 
		case 1:$water_im = imagecreatefromgif($waterPic);break; 
		case 2:$water_im = imagecreatefromjpeg($waterPic);break; 
		case 3:$water_im = imagecreatefrompng($waterPic);break; 
		default:die('水印文件格式错误'); 
	} 
	#读取背景图片 
	if(file_exists($backImage)){ 
		$ground_info = getimagesize($backImage); 
			
		switch($ground_info[2])//取得背景图片的格式 
		{ 
			case 1:$ground_im = imagecreatefromgif($backImage);break; 
			case 2:$ground_im = imagecreatefromjpeg($backImage);break; 
			case 3:$ground_im = imagecreatefrompng($backImage);break; 
			default:die('需要增加水印的图片格式不支持'); 
		} 
	}else{ 
		die("需要加水印的图片不存在"); 
	}
	imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h);#拷贝水印到目标文件
	
	//@unlink($backImage); 
	$saveName = (empty($newName)?$backImage:$newName);
	
	switch($ground_info[2])//取得背景图片的格式 
	{ 
		case 1:imagegif($ground_im,$saveName);break; 
		case 2:imagejpeg($ground_im,$saveName);break; 
		case 3:imagepng($ground_im,$saveName);break; 
		default:die('生成图片失败'); 
	} 
	//释放内存
	unset($ground_info); 
	imagedestroy($ground_im); 
	if($isUrl) unlink($waterPic);
	return true;
}
?>