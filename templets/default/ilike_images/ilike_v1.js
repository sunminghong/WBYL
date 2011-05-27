var __url=urlbase+"?app=ilike";
var loginurl=urlbase+"?app=home&act=account&op=tologinmini&param=ilike_ilike_getscore";
var needmask=0;
var isUpload=false;
var MSGLINE=8;
var nologincount=0;
var isClick=false;
var score=0;
var oldhash=location.hash;
var isbusy=true;
var upcore=0;

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

function next(pa) {//alert('2');
	if(!logined && (nologincount++)>3) {
		isbusy=false;
		login();
		return;
	}
	if(!pa) pa='';
	var rateid="";
	if(!isClick) 
		rateid=location.hash.replace('#','');
	else
		rateid =oldhash.replace('#','');
	isClick=false;
	//if(data && data.curr && data.curr.id) rateid=data.curr.id;
	upcore=score;
	var sex=$('#sel_sex').val();
	$.get(__url+"&op=next&rateid="+rateid+'&score='+score+'&sex='+sex+pa+'&t='+Math.random(),function(res){
		eval('data='+res);
		fmtData(data);
	});
	score=0;
}

function fmtData(j){
	//logined = j.logined;
	if(!j.up){
		if(j.logined==false){
			$id('left').className = 'first2';
			$('.loginfalse').show();
			$('.logintrue').hide();
			$('#uploadbtn2').hide();
		}else if(j.logined==true){
			$id('left').className = 'first3';			
			$('.loginfalse').hide();
			$('.logintrue').hide();
			$('#uploadbtn2').show();
		}
		$('#j').show();
	} else {
		$('#prescore').html(j.up.score*1+upcore*1);upcore=0;
		$('#prepnum').html(j.up.byratecount*1+1);

		$id('left').className = 'first4';
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
		$('#photodiv_img').attr('src',j.curr.big_pic).css("left",0).css("top",0);
		
		$('#btn_watchta').attr('href',j.curr.domain);
		
		//setHash('#'+j.curr.id);

	}else {
		$('#photodiv_img').attr('src',"/images/nophoto.jpg");
	}
	//下一个

	if(j.next) {
		$('#nextimg').attr('src',j.next.small_pic);
		$('.scorenum').attr('href','#'+j.next.id);
	}
	isbusy=false;
}

function setHash(a){
	if(location.hash==a) return;
	location.hash=a;
}

if( ('onhashchange' in window) && ((typeof document.documentMode==='undefined') || document.documentMode==8)) {
    window.onhashchange = next; 
} else {
    // 不支持则用定时器检测的办法
    setInterval(function() {//alert(location.hash+'========='+oldhash);
        var ischanged = (location.hash!=oldhash); 
        if(ischanged) {
            next();
			oldhash=location.hash;
        }
    }, 2000);
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
		logined=true;
		//if(!data.curr) {
			$('.loginfalse').hide();
			if($id('left').className == 'first2') {
				$id('left').className = 'first3';
				$('#uploadbtn2').show();
				$('#j').show();
			}
		//}
		var score=account.ilike_ilike_getscore;
			var ph=[];
			ph.push(account.screen_name);
			ph.push('，你现在得分<b id="myscore">');
			ph.push(score.score || 0);
			ph.push('</b>，上传<b id="mypiccount">');
			ph.push(score.piccount || 0);
			ph.push('</b>个范儿，<br/>被评价<b id="mybyratecount">');
			ph.push(score.byratecount || 0);
			ph.push('</b>次，看了<b id="myratecount">');
			ph.push(score.ratecount || 0);
			ph.push('</b>个范儿，共评出 <b id="myratescore">');
			ph.push(score.ratescore || 0);
			ph.push('</b>分。');
			$('#aboutfaner').html(ph.join(''));
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
		$('#photodiv_img').attr('src',data.curr.big_pic).css("left",0).css("top",0);
		setHash('#'+data.curr.id);
		uploadnotice();
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
function uploadnotice() {
	var a="趁别人不知道，快给自己打个高分吧！";
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

	if(mlist[idx]) {
		msg_list.prepend(addMsgLine(idx));
		
		var h = 31; //$("#msg_block_"+mlist[idx].id).height();
		$("#msg_block_"+mlist[idx].id).animate({
		   height: h
		 }, 500);
	}
	startani(idx-1);
}
function addMsgLine(idx){
	var msg=mlist[idx],ph=[];if(!msg) return;
	//<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>上传了照片</p>
	ph.push('<p class="msg_block noid'+maxid+'" id="msg_block_'+msg.id+'"'+(isinit?'':' style="height:0;"')+'><i>'+msg.testtime+'</i> ');
	if(msg.type*1==2){
		ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
		ph.push('@'+msg.name+' 在《看看我的范儿》上发布了TA最新的照片，去看看吧~_~!" target="_blank" title1="点击对TA说话" '+(msg.lfrom=='tsina'?'wb_screen_name="'+msg.name+'"':'')+'>@'+msg.name+'</a> 上传了TA最近的范儿！');
	}
	else{
		ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
		ph.push('在《看看我的范儿》上给XXX的”照片“打了分,你们也去看看照片、打打分吧~_~！" target="_blank" title1="点击对TA说话" '+(msg.lfrom=='tsina'?'wb_screen_name="'+msg.name+'"':'')+'>@'+msg.name+' </a> 给一个范儿打了<b>'+msg.score+'</b>分！');
	}
	ph.push('</p>');
	if(maxid>MSGLINE)
		msg_list.find(".noid"+(maxid-MSGLINE)).remove();
	maxid++;
	return ph.join('');
}
///////////////////////////////////////////////////////////////////////////////////////////////

var lastScoreLeft=0;
var lastScoreTop=0;
var lastScoreHeight=300;
var lastScoreWidth=0;
var divScoreLeft=0;
var divScoreTop=0;

function addScore(score) {
	if(!$id('myratescore')) return;
	poff=$("#photodiv").offset();
	lastScoreLeft=poff.left;
	lastScoreTop=poff.top;
	lastScoreWidth=$("#photodiv").width();

	poff=$("#myratescore").offset();

	divScoreLeft=poff.left;
	divScoreTop=poff.top+4; 

	var oldmain=$('#photodiv');
	var ooo=oldmain.clone().css("position","absolute").css("border","1px solid #666").css("background-color","#CEEFF6").css({left:lastScoreLeft,top:lastScoreTop}).insertAfter($('body'));
	//oldmain.hide();
	ooo.animate({
    opacity: 0.2,
    left: divScoreLeft,
	top:divScoreTop,
    height: 'toggle',
	lineHeight:'0',
	fontSize:'0',
	width:'toggle'
  }, 450, function() {
		ooo.remove();
		var sc=parseInt($("#myratescore").html());
		var byra=parseInt($('#myratecount').html());
		$('#myratescore').html(sc+parseInt(score));
		$('#myratecount').html(byra+1);
  });
}

$(document).ready(function(){
	if($('#main').height()<=639){
		$('#main').css('height',619);
	}else{
		$('#main').css('height','auto');
	}
	fHideFocus('a');

	//if(logined) {
	//	$id('left').className = 'first3';
	//	$('.loginfalse').hide();
	//	$('#j2').show();
	//}

	$('.scorenum').attr('href','javascript:void(0)').click(function(){
		if(isbusy) return false;
		isbusy=true;
		score=$(this).attr('id').replace('s','');		
		addScore(score);		
		oldhash=location.hash;  
		isClick=true;
		//next();
	});
	$('#uploadbtn').click(function(){
		$('#mask').show();		needmask<0?needmask=1:needmask++;
		showdiv('uploaddiv');
	});
	$('#uploadbtn2').click(function(){
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
	$('#btn_closelogin').click(function(){
		if (needmask<=1)
			$('#mask').hide();
		needmask--;

		$('#logindiv').hide();
	});
	next(	'&f=1');
	$('#mask').css({'width':$(document).width(),'height':$(document).height()});

	msg_list=$("#msglist");
	if(msg_list.length>0){
		refreshMsg();
	}

	$('#btn_followta').click(function(){
		if(data.curr && data.curr.uid) 
			_follow(data.curr.uid);			
	});

	$('#btn_follow_this').click(function(){
		_follow('');
		$('#btn_follow_this').hide();
	});
	$('#btn_bury').click(function(){
		$.get(__url+'&op=bury&rateid='+location.hash.replace('#',''),function(res){
			if(res=='-1') {
				login();
				alert('非常感谢您的举报。我们就需要像您这样的热心网友来维护这个舞台，请用微博帐号登录！'); 
			}
			else alert('谢谢你的举报，每个人都是这个舞台的监督者！');
		});
		score=-1;
		location.href=$('#s1').attr('href');
	});
	
	//You can use the methods .dragOff() and .dragOn() to disable and enable the dragging behavior respectively.
	$('#photodiv_img').easydrag();

	$("#photodiv_img").ondrag(function(e, element){ 
		var oo=$("#photodiv_img");
		var w=oo.width();
		var h=oo.height();
		var l=parseInt(oo.css("left")) ;
		var t=parseInt(oo.css("top"));
		if(l>0)  {
			oo.css("left",0);
		}else 	if(l+ w < 512) {
			oo.css("left",512-w);		
		}
		if(t>0) oo.css("top",0);
		else if (t + h < 480)
			oo.css("top",480-h);		
	});
	
	//$(window).hashchange();
	setTimeout(function(){
		$('body').css('height',$('body').height()+1);
	},10);
});
