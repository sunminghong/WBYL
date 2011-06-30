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
  <p>android.exitapp() 退出应用软件</p>
<p>android.capture('capturecallback',480,320,90) 打开相机</p>
<p>android.getimage('capturecallback',480,320,90) 打开相册</p>
<p>android.notify("abc.ico","nofify test!") 手机提醒</p>
<p>

	     
    	//这是设置通知是否同时播放声音或振动，声音为Notification.DEFAULT_SOUND  ==1
    	//振动为Notification.DEFAULT_VIBRATE  == 2;
    	//Light为Notification.DEFAULT_LIGHTS=4，在我的Milestone上好像没什么反应
    	//全部为Notification.DEFAULT_ALL==0xffffffff;
</p>
<script type="text/javascript">
function alertcallback(msg) {
	document.getElementById('div_msg_output').innerHTML+=msg+'<br/>';
}

function exitcallback() {
	if(confirm('确认退出吗')) {
		exitapp();
	}
}

function exitapp() {
	if(typeof android=="undefined") {
		alertcallback('android is undefined!');
		return;
	}
	if(typeof android.exitapp=="underfined") {
		alertcallback('android.exitapp is undefined!');
		return;
	}
	android.exitapp();
}

function capture() {
	if(typeof android=="undefined") {
		alertcallback('android is undefined!');
		return;
	}
	if(typeof android.capture=="underfined") {
		alertcallback('android.capture is undefined!');
		return;
	}
	android.capture('capturecallback',480,320,90);
}

function capturecallback(isSuccess) {
		alertcallback('capturecallback is'+isSuccess);
		if(isSuccess) {
			document.getElementById('img_show').src = "data:image/jpeg;base64,"+
			android.getimgbase64();
		}
			
}

function get_image() {
	if(typeof android=="undefined") {
		alertcallback('android is undefined!');
		return;
	}
	if(typeof android.getimage=="underfined") {
		alertcallback('android.getimage is undefined!');
		return;
	}
	android.getimage('capturecallback',480,320,90);
}

function notify() {
	if(typeof android=="undefined") {
		alertcallback('android is undefined!');
		return;
	}
	if(typeof android.notify=="underfined") {
		alertcallback('android.notify is undefined!');
		return;
	}
	android.notify("is tips","is title","is msg",1+2+4);
	alertcallback("nofiy is success!");
}

function browserLoadCallback(status) {
	alertcallback("browserLoadCallback:"+status);
}

</script>


<a href="#" onclick="exitapp()">退出应用</a><br/>
<a href="#" onclick="capture()">打开相机</a><br/>
<a href="#" onclick="get_image()">打开相册</a><br/>
<a href="" onclick="notify()">手机提示</a>

<img id="img_show"/>

<div id="div_msg_output">00</div>
 </body>
</html>
