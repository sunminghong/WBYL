var __url="?app=ilike";
var loginurl="?app=home&act=account&op=tologinmini";
var needmask=0;
var isUpload=false;
var MSGLINE=10;

function fHideFocus(tName){
	var aTag=document.getElementsByTagName(tName);
	var arrlen = aTag.length;
	for(var i=0;i<arrlen;i++){
		aTag[i].hideFocus=true;
	}
}

var $id = function(id){
	return document.getElementById(id);
}


var data ={};

function next(score,pa) {
	if(!pa) pa='';
	var rateid='';
	if(data && data.curr && data.curr.id) {
		rateid=data.curr.id;
	}else {
		 rateid=location.hash.replace('#','');
	}
	var sex=$('#sel_sex').val();
	$.get(__url+"&op=next&rateid="+rateid+'&score='+score+'&sex='+sex+pa+'&t='+Math.random(),function(res){
		eval('data='+res);
		fmtData(data);
	});
}

function fmtData(j){
	//logined = j.logined;
	if(!j.up){
		if(j.logined==false){
			//$id('left').className = 'first2';
			login();
			return;
		}else if(j.logined==true){
			$id('left').className = 'first3';	
			$('#loginbtn').hide();
		}
		$('#j').show();
	} else {
		$('#prescore').html(j.up.score);
		$id('left').className = '';
		$('#j').hide();
		$('.loginfalse').hide();
		$('.logintrue').show();
		
		//上一个
		$('#prepnum').html(j.up.rateCount);
		$('#preimg').attr('src',j.up.small_pic);
	}

	//当前
	if(j.curr) {
		if(j.curr.sex==1) $('#shuai').removeClass("f").addClass("m");
		else  $('#shuai').removeClass("m").addClass("f");

		//$('#photodiv').css({'background':'url('+ j.curr.big_pic +') #fff center no-repeat'});
		$('#photodiv').html('<img src="'+j.curr.big_pic+'" />');
	
		$('#btn_watchta').attr('href',j.curr.domain);

	}else {
		$('#photodiv').html('<img src="/images/nophoto.jpg" />');
	}
	//下一个

	if(j.next) {
		$('#nextimg').attr('src',j.next.small_pic);
		$('.scorenum').attr('href','#'+j.next.id);
	}
}

function _follow(uid){
	var url=__url+"?app=home&act=my&op=follow&uid="+(uid?uid:'');
	$.get(url,function(res){
		alert('已经帮你关注TA了，以后就可以实时看到他的动态了！');
	})
}

function login(){
	$('#mask').show();		needmask<0?needmask=1:needmask++;
	showdiv('logindiv');

	document.getElementById('loginiframe').src=loginurl;
}
function loginback(account) {
	if (needmask<=1)
		$('#mask').hide();
	needmask--;

	$('#logindiv').hide();

	if(account && account.uid) {
		if(!data.curr) {
			logined=true;
			$('.loginfalse').hide();
			$id('left').className = 'first3';
		}
	}

	if(isUpload) {
		$('#submitdo').click();
		isUpload=false;
	}

}
function showdiv(id) {

	ooo=$('#'+id);
	ooo.show();
	var h=ooo.height();
	var w=ooo.width();
	var t=($(window).height()- h) /2 +  $(document).scrollTop();
	var l=($(window).width() - w) /2+  $(document).scrollLeft();

	if(t<5) t=5;
	if(l<5) l=5;  //alert(t+'//'+l);
	ooo.css("top",t).css("left",l);
}

function upload_return(rel){
	switch(rel.success) {
		case 1:
		$('#btn_follow_this').hide();
		data.curr=rel.curr;
		$('#photodiv').css({'background':'url('+ data.curr.middle_pic +') #fff center no-repeat'});
		if(data.next)
			$('.scorenum').attr('href','#'+data.next.id);

		if (needmask<=1)
			$('#mask').hide();
		needmask--;

		$('#uploaddiv').hide();
		return;
		case -1:
			alert(rel.msg);
			isUpload=true;
			login();
			return;
		case -2:
			alert('微博太火了，服务器忙不过来了，你再传看看吧！');
			return;
	}
}

////////////////////////////////////////////////////////////////////////////

var mlist=[];
var isinit=true;
var lasttime=0;
var maxid=1;
var msg_list=null;


function refreshMsg(){
	var url=__url+"&op=timelist&last="+lasttime+"&t="+Math.random();
	if(op=="login") url+="&mo=1"
	$.get(url,function(res){
			eval("mlist="+res);
			if(mlist.length == 0) {
				setTimeout(refreshMsg,10000);
				return;
			}
			lasttime=mlist[0].lasttime;
			
			if(!isinit) {
				 startani(mlist.length-1);
				return;
			}
			
			var ph=[];
			for(var i=mlist.length-1;i>=mlist.length-MSGLINE;i--){
				ph.unshift(addMsgLine(i));
			}
			msg_list.html(ph.join(''));
			isinit=false;
			
			startani(5);		
		});
}
function startani(idx){
			setTimeout(function(){
				aniAppend(idx);
			},parseInt(3 * Math.random())*1000+1000);
}
function aniAppend(idx){
	if(idx==-1){
		refreshMsg();
		return;
	}

	msg_list.prepend(addMsgLine(idx));

	$("#msg_block_"+mlist[idx].id).animate({
	   height: 25
	 }, 500);
	
	startani(idx-1);
}
function addMsgLine(idx){
	var msg=mlist[idx],ph=[];
	//<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>上传了照片</p>
	ph.push('<p class="msg_block noid'+maxid+'" id="msg_block_'+msg.id+'"'+(isinit?'':' style="height:0;"')+'><i>'+msg.testtime+'</i> ');
	if(msg.type*1==2){
		ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
		ph.push('@'+msg.name+' 在《爱你》上发布了TA最新的照片，去看看吧~_~!" target="_blank" title1="点击对TA说话" '+(msg.lfrom=='tsina'?'wb_screen_name="'+msg.name+'"':'')+'>@'+msg.name+'</a> 上传了照片！');
	}
	else{
		ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
		ph.push('在《爱你》上给XXX的”照片“打了分,你们也去看看照片、打打分吧~_~！" target="_blank" title1="点击对TA说话" '+(msg.lfrom=='tsina'?'wb_screen_name="'+msg.name+'"':'')+'>@'+msg.name+' </a> 打分一次！');
	}
	ph.push('</p>');
	if(maxid>MSGLINE)
		msg_list.find(".noid"+(maxid-MSGLINE)).remove();
	maxid++;
	return ph.join('');
}
///////////////////////////////////////////////////////////////////////////////////////////////

$(document).ready(function(){
	if($('#main').height()<=639){
		$('#main').css('height',619);
	}else{
		$('#main').css('height','auto');
	}
	fHideFocus('a');

	if(logined) {
		$id('left').className = 'first3';
		$('.loginfalse').hide();
		$('.logintrue').show();
	}

	$('.scorenum').click(function(){
		next($(this).attr('id').replace('s',''));
	});
	$('#uploadbtn').click(function(){
		$('#mask').show();		needmask<0?needmask=1:needmask++;
		showdiv('uploaddiv');
	});
	$('#colseupload').click(function(){
		if (needmask<=1)
			$('#mask').hide();
		needmask--;

		$('#uploaddiv').hide();
	});
	$('#submitdo').click(function(){
		$('#uploadform').submit();
	});
	$('#loginbtn').click(function(){
		login();
	});
	$('#colselogin').click(function(){
		if (needmask<=1)
			$('#mask').hide();
		needmask--;

		$('#logindiv').hide();
	});
	//fmtData(data);

	next('','&f=1');
	$('#mask').css({'width':$(document).width(),'height':$(document).height()});

	msg_list=$("#msglist");
	if(msg_list.length>0){
		refreshMsg();
	}

	$('#btn_followta').click(function(){alert(data.curr.uid);
		if(data.curr && data.curr.uid) 
			_follow(data.curr.uid);			
	});

	$('#btn_follow_this').click(function(){
		_follow('');
		$('#btn_follow_this').hide();
	});

});
