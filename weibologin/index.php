<?php

$submit_vars=array();
$submit_vars["callback"] = "parent.sinaSSOController.loginCallBack";
$submit_vars["client"] = "ssologin.js(v1.3.7)";
$submit_vars["encoding"] = "GB2312";
$submit_vars["entry"] = "sso";
$submit_vars["from"] = "null";
$submit_vars["gateway"] = "1";
$submit_vars["password"] = "allen2520"; //你的密码
$submit_vars["returntype"] = "IFRAME";
$submit_vars["savestate"] = "0";
$submit_vars["setdomain"] = "1";
$submit_vars["username"] = "allen.fantasy@gmail.com";//你的用户名
$submit_vars["useticket"] = "0";

$submit_url = "http://login.sina.com.cn/sso/login.php?client=ssologin.js(v1.3.7)";


include 'snoop_class.php';
include 'simple_html_dom.php';

$snoopy = new Snoopy();
$snoopy->referer = "http://login.sina.com.cn/signup/signin.php";
$snoopy->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 4.0.20506)";
$snoopy->referer = "http://t.sina.com.cn/";

$snoopy->submit($submit_url,$submit_vars);
$snoopy->setcookies();
$cookies = $snoopy->cookies;
//$snoopy->fetch('http://i.sso.sina.com.cn/js/ssologin.js');
//$snoopy->submittext();
$snoopy->fetch('http://weibo.com/app');
$doc=str_get_html($snoopy->results);

$sidenav=$doc->find("ul[class='sidenav']",0);
foreach($sidenav->find('li') as $li){
	if($a=$li->find('span a',0)) {
		$text=trim($a->plaintext);
		$href=trim($a->href);
echo $text.$href;
		if($text!='首页'){
			fetchClassPage($text,$href);
		}
	}
}

//提取首页相关应用数据、
$topapp=$doc->find("table[class='btable']",0);
foreach($topapp->find("tr[class='MIB_linedot2']") as $tr) {
	$apparr=array();
	$apparr['appico']=$tr->find("td[class='td2']",0);
	$app=$tr->find("td[class='td3'] a",0);
	$apparr['appname']=$app->plaintext;
	$apparr['href']=$app->href;

	$apparr['users']=$tr->find("td[class='td4'] a",0);
	print_r($apparr);
}


function fetchClassPage($type,$href) {
	
}
//echo $doc;



/*
$cookie_file=tempnam('./temp','cookie');
echo gethttppage($submit_url,$submit_vars,array(CURLOPT_COOKIEJAR=>$cookie_file));
//echo file_get_contents($cookie_file);
$res = gethttppage("http://weibo.com/app","",array(CURLOPT_COOKIEFILE=>$cookie_file));
//echo $res;


@unlink($cookie_file);

function getHttpPage($url,$poststr='',$con=''){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5); //超时时间（秒）
	if($poststr){
		if(is_array($poststr)) {
			$abc='';
			foreach($poststr as $k=>$v) {
				$abc.="$k=$v&";
			}
			$poststr=$abc.'aaaaaa=1';
		}
		echo $poststr;
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$poststr); 	
	}
	if(is_array($con)){
		foreach($con as $key => $value){
			curl_setopt($ch,$key,$value); 
		}
	}
	$html = curl_exec($ch);
	curl_close($ch);
	return $html;
}
*/


?>
