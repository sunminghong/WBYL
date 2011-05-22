var urlbase="?app=ilike";
var loginurl="?app=home&act=account&op=tologin";

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
	//$('#mask').show();
	//$('#uploaddiv').show();
	//$('#uploaddiv_content').hide();
//var ifr=	document.getElementById('loginiframe');
	//ifr.src=loginurl;
	//$('#loginiframe').show();
	$('#mask').show();
	$('#logindiv').show();
	document.getElementById('loginform').src=loginurl;
	$('#form_btn_login').click();
}
function upload_return(rel){
	switch(rel.success) {
		case 1:

		data.curr=rel.curr;
		$('#photodiv').css({'background':'url('+ data.curr.middle_pic +') #fff center no-repeat'});
		if(data.next)
			$('.scorenum').attr('href','#'+data.next.id);

		$('#mask').hide();
		$('#uploaddiv').hide();
		return;
		case -1:
			alert(rel.msg);
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
	}

	$('.scorenum').click(function(){
		next($(this).attr('id').replace('s',''));
	});
	$('#uploadbtn').click(function(){
		$('#mask').show();
		$('#uploaddiv').show();
	});
	$('#colseupload').click(function(){
		$('#mask').hide();
		$('#uploaddiv').hide();
	});
	$('#submitdo').click(function(){
		$('#uploadform').submit();
	});
	//fmtData(data);

	next('');
	$('#mask').css({'width':$(document).width(),'height':$(document).height()});	

	
});
