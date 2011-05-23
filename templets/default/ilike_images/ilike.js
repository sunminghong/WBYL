var urlbase="?app=ilike";
var loginurl="?app=home&act=account&op=tologin";
var needmask=0;
var isUpload=false;

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

function next(score) {
	var rateid='';
	if(data && data.curr && data.curr.id) {
		rateid=data.curr.id;
	}else {
		 rateid=location.hash.replace('#','');
	}
	$.get(urlbase+"&op=next&rateid="+rateid+'&score='+score,function(res){
		eval('data='+res);
		fmtData(data);
	});
}

function fmtData(j){
	if(!j.up){
		if(j.logined==false){
			$id('left').className = 'first';	
			$('#left').css({'background':'url(images/leftbg2.png) no-repeat left 35px'});
		}else if(j.logined==true){
			$id('left').className = 'first';	
			$('#left').css({'background':'url(images/leftbg3.png) no-repeat left 35px'});
			$('#loginbtn').hide();
		}
		logined = j.logined;
		$('#prescore').html(j.up.score);
	}

	$id('left').className = '';
	//$('.loginfalse').hide();
	$('.logintrue').show();
	
	//上一个
	$('#prepnum').html(j.up.rateCount);
	$('#preimg').attr('src',j.up.small_pic);
	//当前

	$('#photodiv').css({'background':'url('+ j.curr.middle_pic +') #fff center no-repeat'});
	//下一个
	$('#nextimg').attr('src',j.next.small_pic);

	if(j.next)
		$('.scorenum').attr('href','#'+j.next.id);
}
function login(){
	$('#mask').show();		needmask<0?needmask=1:needmask++;
	showdiv('logindiv');

	document.getElementById('loginiframe').src="?app=home&act=account&op=tologinmini";
}
function showdiv(id) {

	ooo=$('#'+id);
	ooo.show();
	var h=ooo.height();
	var w=ooo.width();
	var t=($(window).height()- h) /2 +  $(document).scrollTop();
	var l=($(window).width() - w) /2+  $(document).scrollLeft();

	if(t<5) t=5;
	if(l<5) l=5;alert(t+'//'+l);
	ooo.css("top",t).css("left",l);
}

function loginback(account) {
	if (needmask<=1)
		$('#mask').hide();
	needmask--;

	$('#logindiv').hide();

	if(account && account.uid) {
		logined=true;
		$('#loginfalse').hide();
	}

	if(isUpload) {
		$('#submitdo').click();
		isUpload=false;
	}

}
function upload_return(rel){
	switch(rel.success) {
		case 1:

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

$(document).ready(function(){
	if($('#main').height()<=639){
		$('#main').css('height',619);
	}else{
		$('#main').css('height','auto');
	}
	fHideFocus('a');

	if(!logined) {
		$('#loginfalse').show();
	}else {
		$('#loginfalse').hide();
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
	$('#colseuplogin').click(function(){
		if (needmask<=1)
			$('#mask').hide();
		needmask--;

		$('#logindiv').hide();
	});
	//fmtData(data);

	next('');
	$('#mask').css({'width':$(document).width(),'height':$(document).height()});	

	
});
