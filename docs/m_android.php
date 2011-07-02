<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> android jsinterface demo! </title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
 </head>

 <body>
  <p>android.exitApp() 退出应用软件</p>
<p>android.capture('capturecallback',480,320,90) 打开相机</p>
<p>android.getImage('capturecallback',480,320,90) 打开相册</p>
<p>android.notify("abc.ico","nofify test!") 手机提醒</p>
<p>

	<!--     
    	//这是设置通知是否同时播放声音或振动，声音为Notification.DEFAULT_SOUND  ==1
    	//振动为Notification.DEFAULT_VIBRATE  == 2;
    	//Light为Notification.DEFAULT_LIGHTS=4，在我的Milestone上好像没什么反应
    	//全部为Notification.DEFAULT_ALL==0xffffffff;
		-->
</p>
<script type="text/javascript">
function alertCallback(msg) {
	document.getElementById('div_msg_output').innerHTML+=msg+'<br/>';
}

function exitCallback() {
	if(confirm('确认退出吗')) {
		exitApp();
	}
}

function exitApp() {
	if(typeof android=="undefined") {
		alertCallback('android is undefined!');
		return;
	}
	if(typeof android.exitApp=="underfined") {
		alertCallback('android.exitApp is undefined!');
		return;
	}
	android.exitApp();
}

function capture() {
	if(typeof android=="undefined") {
		alertCallback('android is undefined!');
		return;
	}
	if(typeof android.capture=="underfined") {
		alertCallback('android.capture is undefined!');
		return;
	}
	android.capture('capturecallback',480,320,90);
}

function capturecallback(isSuccess) {
		alertCallback('capturecallback is'+isSuccess);
		if(isSuccess) {
			document.getElementById('img_show').src = "data:image/jpeg;base64,"+
			android.getimgbase64();
		}
			
}

function get_image() {
	if(typeof android=="undefined") {
		alertCallback('android is undefined!');
		return;
	}
	if(typeof android.getImage=="underfined") {
		alertCallback('android.getImage is undefined!');
		return;
	}
	android.getImage('capturecallback',480,320,90);
}

function notify(isnew) {
	if(typeof android=="undefined") {
		alertCallback('android is undefined!');
		return;
	}
	if(typeof android.notify=="underfined") {
		alertCallback('android.notify is undefined!');
		return;
	}
	android.notify("is tips","is title","is msg",1+2+4,isnew);
	alertCallback("nofiy is success!");
}

function browserLoadCallback(status) {
	alertCallback("browserLoadCallback:"+status);
}

function readPhone() {
	if(typeof android=="undefined") {
		alertCallback('android is undefined!');
		return;
	}
	if(typeof android.readTelIMSI=="underfined") {
		alertCallback('android.readTelIMSI is undefined!');
		return;
	}
	/*
IMSI由MCC、MNC、MSIN组成，其中MCC为移动国家号码，由3位数字组成， * 唯一地识别移动客户所属的国家，我国为460；MNC为网络id，由2位数字组成， * 用于识别移动客户所归属的移动网络，中国移动为00,02,07，中国联通为01,中国电信为03；
MSIN为移动客户识别码，采用等长11位数字构成。 *
唯一地识别国内GSM移动通信网中移动客户
	*/

	var tm=android.readTelIMSI();
	alert('readPhone:'+tm);
}
function readPhoneNumber() {
	if(typeof android=="undefined") {
		alertCallback('android is undefined!');
		return;
	}
	if(typeof android.readTelNumber=="underfined") {
		alertCallback('android.readTelNumber is undefined!');
		return;
	}
	
	var ges=android.readTelNumber();
	alertCallback('readTelNumber:'+ges);	 
}

function getGPS() {
	if(typeof android=="undefined") {
		alertCallback('android is undefined!');
		return;
	}
	if(typeof android.getLocationInfo=="underfined") {
		alertCallback('android.getLocationInfo is undefined!');
		return;
	}
	
	var ges=android.getLocationInfo();
	alertCallback('getGPS:'+ges);
}	

function initGPS() {
	if(typeof android=="undefined") {
		alertCallback('android is undefined!');
		return;
	}
	if(typeof android.startLocationUpdate=="underfined") {
		alertCallback('android.startLocationUpdate is undefined!');
		return;
	}
	
	android.startLocationUpdate(10000,0,'loc_updatecallback','loc_enablecallback','loc_statusChanged');
}
function loc_updatecallback(ges) {
	alertCallback('loc_updatecallback:'+'经纬改变：'+ges);
}
function loc_enablecallback(method,provider) {
	alertCallback('loc_enablecallback:'+provider+' is '+method);
}
function loc_statusChanged(provider,status) {
	alertCallback('loc_statusChanged:'+provider+' is '+status);
}



</script>

<a href="#" onclick="android.switchDebug(false)">屏蔽调试信息</a>
<a href="#" onclick="android.switchDebug(true)">显示调试信息</a>

<a href="#" onclick="android.alertExitApp()">退出应用</a>
<a href="#" onclick="exitApp()">强制退出应用</a><br/>
<a href="#" onclick="capture()">打开相机</a><br/>
<a href="#" onclick="get_image()">打开相册</a><br/>
<a href="#" onclick="notify(false)">手机提示</a>
<a href="#" onclick="notify(true)">手机提示(打开新胡)</a>
<br/>

<a href="#" onclick="initGPS()">初始化经纬度</a>
<a href="#" onclick="getGPS()">获取经纬度</a>
<a href="#" onclick="readPhoneNumber()">获取本机号码</a>
<a href="#" onclick="readPhone()">获取本机IMSI</a>

<img id="img_show"/>

<div id="div_msg_output">00</div>

-------------------<BR/>
-------------------<BR/>
-------------------<BR/>
-------------------<BR/>
-------------------<BR/>

-------------------<BR/>
-------------------<BR/>
-------------------<BR/>
 </body>
</html>
