<?php
/*
楠岃瘉鐮佽皟鐢ㄦ柟娉曪細
<img src='verifycode.php?a=getimage' />

楠岃瘉鐮侀獙璇佹柟娉曪細
include_once('verifycode.php');
checkCode('鐢ㄦ埛杈撳叆鐨勭爜');
杩斿洖锛歵rue||false
*/


//鐢熸垚楠岃瘉鐮?
function getImage(){
	Header("Content-type:image/png");
	$authnum=''; 
	$str = 'abcdefghijkmnpqrstuvwxyz23456789'; 
	$l = strlen($str); 
	//楠岃瘉鐮侀暱搴?
	for($i=1;$i<=4;$i++)
	{ 
		$num=rand(0,$l-1);
		$authnum.= $str[$num];
	}
	
	//淇濆瓨鑷硈ession
	SESS::set('verify',$authnum);
	
	$im = imagecreate(50,20);//鍥剧墖瀹戒笌楂? 
	//$black = ImageColorAllocate($im, 0,0,0); //濉厖鑳屾櫙鑹?
	$white = ImageColorAllocate($im, 255,255,255);
	$black = ImageColorAllocate($im, 0,0,0);
	$gray = ImageColorAllocate($im, 200,200,200); 
	
	//灏嗗洓浣嶆暣鏁伴獙璇佺爜缁樺叆鍥剧墖
	imagefill($im,68,30,$gray);
	
	$li = ImageColorAllocate($im, 220,220,220);
	for($i=0;$i<2;$i++) {
		//鍔犲叆骞叉壈绾?
		imageline($im,rand(0,30),rand(0,21),rand(20,40),rand(0,21),$li);
	}
	 
	//瀛楃鍦ㄥ浘鐗囩殑浣嶇疆;
	imagestring($im, 5, 8, 2, $authnum, $black);
	for($i=0;$i<90;$i++){
		//鍔犲叆闅忔満棰滆壊鐨勫共鎵扮偣
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
