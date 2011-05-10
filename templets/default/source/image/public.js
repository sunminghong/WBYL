//瀛楃涓叉墿灞?
String.prototype.left = function(num){
	var str = this.substr(0,num);
	return str
};
String.prototype.right = function(num){
	var str = this.substr(this.length-num);
	return str;
};
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
};
var ajax = function(url,fn,asy){
	url+=(url.indexOf("?")==-1?"?":"&")+'calltype=ajax';
	$.ajax({
		type: "GET",url: url,cache :false,async :(asy==null || asy==true?true:false),
		success: function(result){
			if (!fn){
				eval(result);
			}else{
				fn(result);
			}
		},
		error:function(x){}
	});	
};

function myurl(){
	return location.href.replace(/#/ig,"");	
}

function stopBubble(e){
	if(e){
		e.stopPropagation();
	}else{
		window.event.cancelBubble = true;
	}
} 

//鎵撳紑濂藉弸鍒楄〃锛岀被鍨嬶紝楂樺害锛屽搴︼紝top锛宭eft
function getFans(type,width,height,top,left){
	var divo = $('#fansDiv');
	if(divo.length==0){
		$("body").prepend('<div id="fansDiv"><iframe frameborder="0" src="" scrolling="no" name="FansIframe" id="FansIframe"></iframe></div>');	
		divo = $('#fansDiv');
	}
	$('#FansIframe').attr('src','index.php?app=getfans&t='+type);
	$(divo).css({'top':top,'left':left,'width':width,'height':height}).show();
}

//getFnas.php
function setpos(){
	var winw = $(window).width(),winh=$(window).height();
	$('#fans_page').css('top',winh-36);	
	$('#fans_list').css('height',winh-36-35);
}

var startIndex = 0,startType=0,hasnext=0;

function fmtFansList(d){
	var html=[],head='';
	var info = d.info;
	hasnext = d.hasnext;
	for(var i=0,l=d.info.length-1;i<l;i++){
		head = info[i].head;
		if(head==''){
			head='http://mat1.gtimg.com/www/mb/images/head_100.jpg';
		}else{
			head+='/30';	
		}
		html.push('<a href="javascript:;" onclick="parent.clickFriend(\''+ info[i].nick +'\',\''+ info[i].name +'\');"><img src="'+ head +'" class="head" />'+ info[i].nick +'@'+ info[i].name +'</a>');	
	}
	$('#fans_list').html(html.join(''));
}

function setfans(t){
	$('#fans_menu a').removeClass('fmenuon');
	$('#a'+t).addClass('fmenuon');
	startIndex = 0;
	startType = t;
	hasnext = 0;
	loadfans();
}

function fanspage(n){
	if(startIndex==0 && n<0){
		alert('宸插埌杈炬渶鍓嶉〉');
		return;	
	}
	if(hasnext==1 && n>0){
		alert('宸插埌杈炬渶鍚庨〉');
		return;		
	}
	startIndex+=n;
	loadfans();
}

function loadfans(){
	var url = myurl() + '&act=load&t='+startType+'&s='+startIndex;
	ajax(url);
}
