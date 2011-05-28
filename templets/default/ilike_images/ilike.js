var __url=urlbase+"?app=ilike";
var loginurl=urlbase+"?app=home&act=account&op=tologinmini&param=ilike_ilike_getscore";
var isUpload=false;
var MSGLINE=8;
var nologincount=0;
var isClick=false;
var score=0;
var oldhash=location.hash;
var isbusy=true;
var upcore=0;

var isie6=false;
var bFix=true;

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

var data =[];

function next(pa,lfrom) { //alert(lfrom+':data.len='+data.length); 
	if(!logined && (nologincount++)>3) {
		isbusy=false;
		login();
		return;
	}
	if(!pa) {
		pa=''; 
	}else {
		data=[];
	}
	var rateid="";
	if(!isClick) {
		rateid=location.hash.replace('#','');
		if(!pa) {
			$.get(__url+"&op=next&rateid="+rateid+'&ishistory=1&req=1',function(res){
				if(!res) return;
				eval('var datanew='+res);
				if(!datanew)
					alert('此范儿不存在了哦！');
				else
					data.unshift(datanew);

				_showcurr(datanew);
			});
			oldhash =location.hash;
			return; 
		}
	}
	else {
		rateid =oldhash.replace('#','');
	}
	isClick=false;

	upcore=score;
	var sex=$('#sel_sex').val();
	var req='';
	if(data.length<=2) req="&req=1";
	_showpic(data);

	$.get(__url+"&op=next&rateid="+rateid+'&score='+score+'&sex='+sex+pa+req+'&t='+Math.random(),function(res){
		if(!res) return;
		eval('var datanew='+res);
		if(data.length>1)	datanew.unshift(data[1]);
		if(data.length>0)			{
			datanew.unshift(data[0]);
			data=datanew;
		}
		else{
			datanew.unshift(false);
			data=datanew;
			_showpic(datanew);
		}
	});
	
	score=0;
}
function _showcurr(da) {
	if(da.sex==1) 
		$('#shuai').removeClass("f").addClass("m");
	else  
		$('#shuai').removeClass("m").addClass("f");
	$('#photodiv_img').css("left",0).css("top",0).attr('src',da.big_pic);		
	$('#btn_watchta').attr('href',da.domain);	

	$('#btn_watchta').attr('href',da.domain).html('查看@'+da.name+' 的微博');
	$('#btn_followta').html('关注@'+da.name+' 的微博');
	$('#notice_share').attr('title','分享@'+da.name+' 的这张范儿到微博');

	setHash('#'+da.id);
}
function _showpic(j){
	var dlen=data.length;
	if(dlen==0) return;
	
	if(dlen>0 && data[0]){
		var da=data[0];
		$('#prescore').html(da.score*1+upcore*1);
		upcore=0;
		$('#prepnum').html(da.byratecount*1+1);

		$id('left').className = 'first4';
		$('#j').hide();
		$('.loginfalse').hide();
		$('.logintrue').show();
		
		if(isie6 && bFix) {
			setTimeout(function(){
				$('body').css('height',$('body').height()+1);
				bFix=false;
			},10);
		}
		$('#uploadbtn2').hide();
		//上一个
		$('#prepnum').html(da.rateCount);
		$('#preimg').attr('src',da.big_pic);
	}

	//当前
	if(dlen>1 && data[1]) {
		var da=data[1];			
		_showcurr(da);
	}else {
		$('#photodiv_img').attr('src',"images/nophoto.jpg");
	}
	//下一个
	if(dlen>2 && data[2]) {
		var da=data[2];
		$('#nextimg').attr('src',da.middle_pic);
		//$('.scorenum').attr('href','#'+da.id);

		var img=new Image();
		img.src=data[2].big_pic;
	}
	if(dlen>3 && data[3]) {

		var img=new Image();
		img.src=data[3].middle_pic;
	}	
	data.shift();
	isbusy=false;
}

function setHash(a){
	oldhash=a;  
	if(location.hash==a) return;
	location.hash=a;
}

$(window).hashchange( function(){
		var ischanged = (location.hash!=oldhash); 
        if(ischanged) {
			next('','onhashchange');	
		}
});

function _follow(uid){
	var param={};
	if(uid) {
		var curr=data[0];
		param={byuid:curr.uid,bylfrom:curr.lfrom,bylfromuid:curr.lfromuid,id:curr.id	};
	}
	else 
		param= null;

	var url=__url+'&op=follow';
	$.post(url,param,function(res){
		switch(res) {
			case "1":
				alert('已经帮你关注TA了，以后就可以实时看到他的动态了！');
				break;
			case "-1":
				alert('请先登录吧，否则他怎么知道你关注了 ta ?');
				login();
				break;
			case "-2":
				alert('你知道吗？世界上最远的距离不是生与死的距离，也不是心和心的距离，而是你们一个在新浪微博一个在腾讯微博里！');
				break;
		}
	});
}

function sendshare(){
	var url=__url+'&op=sendshare';
	var is_comment=0,is_follow=0;
	if($('#is_comment').attr('checked')) is_comment=1;
	if($('#iffollow').attr('checked')) is_follow=1;
	
	var curr=data[0];
	$.post(url,{byuid:curr.uid,bylfrom:curr.lfrom,bylfromuid:curr.lfromuid,
			wbid:curr.wbid,is_follow:is_follow,is_comment:is_comment,
			id:curr.id,
			picurl:curr.big_pic,msg:$('#div_share_msg').val()			
		},function(res){
		if(res=="-1"){alert('请先登录！');login();return;}

		$('#mask').hide();
		$('#div_share').hide();
		alert("已经将这张”范儿“分享到你的微博！");		
	});	
}

function login(){
	$('#mask').show();
	showdiv('logindiv');

	document.getElementById('loginiframe').src=loginurl;
}

function loginback(account) {
	if ($('#uploaddiv').css('display')=='none' && $('#div_share').css('display')=='none')
		$('#mask').hide();

	$('#logindiv').hide();

	if(account && account.uid) {
		logined=true;
		$('.loginfalse').hide();
		if($id('left').className == 'first2') {
			$id('left').className = 'first3';
			$('#uploadbtn2').show();
			$('#j').show();
		}

		var score=account.ilike_ilike_getscore;
			var ph=[];
			ph.push(account.screen_name);
			ph.push('，你现在得<b id="myscore">分');
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
	$('#mask').show();

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

function  submitupload() {
		var fi=$('#in_uploadfile').val();
		if(!fi) {
			$('#div_upload_file').css("border","1px solid #f00");
			return false;
		}
		return true;
}
function upload_return(rel){
	switch(rel.success) {
		case 1:
		$('#btn_follow_this').hide();
		data.unshift(rel.curr);
		_showcurr(rel.curr);

		uploadnotice();
		$('#in_uploadfile').val('');
		$('#mask').hide();
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
function _formatmsg(content) {
	return encodeURIComponent(content+urlbase+'?retapp=fanshare');
}
function addMsgLine(idx){
	var msg=mlist[idx],ph=[];if(!msg) return;
	//<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>上传了照片</p>
	ph.push('<p class="msg_block noid'+maxid+'" id="msg_block_'+msg.id+'"'+(isinit?'':' style="height:0;"')+'><i>'+msg.testtime+'</i> ');
	switch(msg.type*1){
		case 2:
			ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
			ph.push(_formatmsg('@'+msg.name+' 在#看看我的范儿#上发布了TA最新的照片，去看看吧~_~!')+'" target="_blank" title="点击对TA说话">@'+msg.name+'</a> 上传了TA最近的范儿！');
			break;
		case 1:
			ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
			ph.push(_formatmsg('@'+msg.name+' 在#看看我的范儿#上给一张”照片“打了分,你们也去看看照片、打打分吧~_~！')+'" target="_blank" title="点击对TA说话">@'+msg.name+' </a> 给一个范儿打了<b>'+msg.score+'</b>分！');
			break;
		case 4:
			ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
			ph.push(_formatmsg('#看看我的范儿#上有好多照片，@'+msg.name+'还分享了一张到他的微博哦！')+'" target="_blank" title="点击对TA说话">@'+msg.name+' </a> 分享一张范儿到微博！');
			break;
		case 5:
			ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
			ph.push(_formatmsg('@'+msg.name+' 在#看看我的范儿#上关注了一张范儿，想增加粉丝数的快去玩这个应用！')+'" target="_blank" title1="点击对TA说话">@'+msg.name+' </a> 关注了一张范儿！');
			break;
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
	if(isie6) return;
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
var timer=null;	
	var flashc=0;
function _notice() {	
	$('#notice_rate').show();
	$('#notice_share').hide();		
	flashc=0;	
	timer=setInterval(function(){
		if(flashc<10) {
			if($id('notice_rate').className=="pngfix")
				$id('notice_rate').className='pngfix notice_rate_over';
			else
				$id('notice_rate').className='pngfix';
			flashc++;
		}else {
			clearInterval(timer);
			$('#notice_rate').hide();
			$('#notice_share').show();				
		}
	},500);
}

$(document).ready(function(){
	isie6=$.browser.msie&&($.browser.version == "6.0")&&!$.support.style;

	if($('#main').height()<=639){
		$('#main').css('height',619);
	}else{
		$('#main').css('height','auto');
	}
	fHideFocus('a');

	if(logined) {
		$id('left').className = 'first3';
		$('.loginfalse').hide();
		$('#uploadbtn2').show();
		$('#j').show();
	}else {
		$id('left').className = 'first2';
		$('.loginfalse').show();
		$('#uploadbtn2').hide();
		$('#j').show();
	}

	$('.scorenum').attr('href','javascript:void(0)').click(function(){
		if(isbusy) return false;
		isbusy=true;
		score=$(this).attr('id').replace('s','');
		addScore(score);		

		isClick=true;
		//if(oldhash==$(this).attr('href')) 
			next('','click');
	});
	$('#nextflag').click(function(){
		_notice();
	});

	$('#uploadbtn').click(function(){
		showdiv('uploaddiv');
	});
	$('#uploadbtn2').click(function(){
		showdiv('uploaddiv');
	});
	$('#colseupload').click(function(){
		$('#mask').hide();
		$('#uploaddiv').hide();
	});
	$('#submitdo').click(function(){		
		$('#uploadform').submit();
		//return submitupload();
	});
	$('#loginbtn').click(function(){
		login();
	});
	$('#btn_closelogin').click(function(){
		if ($('#uploaddiv').css('display')=='none' && $('#div_share').css('display')=='none')
			$('#mask').hide();

		$('#logindiv').hide();
	});
	next(	'&f=1','init');
	$('#mask').css({'width':$(document).width(),'height':$(document).height()});

	msg_list=$("#msglist");
	if(msg_list.length>0){
		refreshMsg();
	}

	$('#btn_followta').click(function(){
		if(data[0] && data[0].uid) 
			_follow(data[0].uid);			
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
		score=0;
		isClick=true;
		next('','bury');
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

	$('#sel_sex').change(function(){
		next('&f=1','sexchange');
	});
	
	$('#btn_share').click(function(){
		sendshare();
	});
	$('#colseshare').click(function(){
		$('#mask').hide();
		$('#div_share').hide();
	});
	
	$('#in_uploadfile').change(function() {
		$('#div_upload_file').css('border','#0f0 solid 1px;')
	});

	$('#notice_share').click(function(){
		var words=['',
			'作为一个男人，帅不帅不重要，重要的是有没有#范儿#！在#看看我的范儿#发现了 @'+data[0].name+' 的这个#范儿#，你给他评个分吧！',
			'哪有那么多歌星？电视台的舞台也有限！但我在#看看我的范儿#发现了 @'+data[0].name+' ，让你们也瞧瞧她的#范儿#！你也给她评分支持下吧！'
			];
		$('#div_share_byname').html('同时评论给 <i>'+data[0].name+'</i>');
		$('#div_share_msg').val(words[data[0].sex]);
		 showdiv('div_share');
	});

	_notice();
});
