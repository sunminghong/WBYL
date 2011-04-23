<?php
/*
验证码调用方法：
<img src='verifycode.php?a=getimage' />

验证码验证方法：
include_once('verifycode.php');
checkCode('用户输入的码');
返回：true||false
*/


//生成验证码
function getImage(){
	Header("Content-type:image/png");
	$authnum=''; 
	$str = 'abcdefghijkmnpqrstuvwxyz23456789'; 
	$l = strlen($str); 
	//验证码长度
	for($i=1;$i<=4;$i++)
	{ 
		$num=rand(0,$l-1);
		$authnum.= $str[$num];
	}
	
	//保存至session
	SESS::set('verify',$authnum);
	
	$im = imagecreate(50,20);//图片宽与高; 
	//$black = ImageColorAllocate($im, 0,0,0); //填充背景色
	$white = ImageColorAllocate($im, 255,255,255);
	$black = ImageColorAllocate($im, 0,0,0);
	$gray = ImageColorAllocate($im, 200,200,200); 
	
	//将四位整数验证码绘入图片
	imagefill($im,68,30,$gray);
	
	$li = ImageColorAllocate($im, 220,220,220);
	for($i=0;$i<2;$i++) {
		//加入干扰线
		imageline($im,rand(0,30),rand(0,21),rand(20,40),rand(0,21),$li);
	}
	 
	//字符在图片的位置;
	imagestring($im, 5, 8, 2, $authnum, $black);
	for($i=0;$i<90;$i++){
		//加入随机颜色的干扰点
		$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
		imagesetpixel($im, rand()%70 , rand()%30 , $randcolor);
	}
	ImagePNG($im);
	ImageDestroy($im);
	
	
}

function checkCode($code){
	$sCode = SESS::get('verify');
	SESS::del('verify');	
	if(strtolower((string)$code) == strtolower((string)$sCode)){
		return true;
	}else{
		return false;	
	}
}

if ($_GET['a']=='getimage'){
	getImage();	
}
?>
