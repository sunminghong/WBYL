
var EE = function(s){
	return document.getElementById(s);		
};

function chC(name,num){
	var rel=false;
	$('input:radio[name='+name+']').each(function(i,n){
		if(num==i)
			rel=this.checked;
	});
	return rel;
}


function calculate(){     
var storea=0;

if(chC('q1','1')){storea+=1;}     
if(chC('q2','2')){storea+=1;}     
if(chC('q3','4')){storea+=1;}     
if(chC('q4','1')){storea+=1;}     
if(chC('q5','2')){storea+=1;}     
if($("input[name=T6]").val()=="26"){storea+=2;}     
if(chC('q7','0')){storea+=1;}     
if(chC('q8','0')){storea+=1;}     
if(chC('q9','2')){storea+=1;}     
if(chC('q10','2')){storea+=1;}     
if($("input[name=T11]").val()=="9"){storea+=1;}     
if(chC('q12','3')){storea+=1;}     
if(chC('q13','2')){storea+=1;}     
if($("input[name=T14]").val()=="6"){storea+=1;}     
if(chC('q15','2')){storea+=1;}     
if(chC('q16','2')){storea+=1;}     
if($("input[name=T17]").val()=="36"){storea+=1;}     
if(chC('q18','1')){storea+=1;}     
if(chC('q19','0')){storea+=1;}     
if($("input[name=T20]").val()=="美国"){storea+=2;}     
if(chC('q21','3')){storea+=2;}     
if($("input[name=T22]").val()=="64"){storea+=2;}     
if($("input[name=T23]").val()=="科学"){storea+=2;}     
if($("input[name=T24]").val()=="式"){storea+=1;}     
if($("input[name=T25]").val()=="X"||$("input[name=T25]").val()=="x"){storea+=1;}     
if($("input[name=T26]").val()=="75"){storea+=1;}     
if(chC('q27','4')){storea+=1;}     
if($("input[name=T28]").val()=="奠"){storea+=1;}     
if($("input[name=T29]").val()=="颠"){storea+=1;}     
if($("input[name=T30]").val()=="5"){storea+=1;}     
if($("input[name=T31]").val()=="36"){storea+=2;}     
if(chC('q32','2')){storea+=2;}     
if($("input[name=T33]").val()=="O"||$("input[name=T33]").val()=="o"){storea+=1;}     
    if(timerID)clearTimeout(timerID);
	var i=$("#face").html();
	location.href="?app="+app+"&op=cacl&usetime="+initStopwatch()+"&storea="+storea+"&t="+Math.random();
	return;	
}     


var timerID = null;     
var timerRunning=false;     
var startday=new Date();     
var clockStart=startday.getTime();    
var usetime=usetime || 0;

function initStopwatch() {
var myTime = new Date();     
return((myTime.getTime() - clockStart)/1000)+usetime;
}     
     
function stopclock (){     
if(timerRunning)     
clearTimeout(timerID);     
timerRunning = false;     
}     
     
function showtime (isstop) {
var tSecs = Math.round(initStopwatch());
if(!isstop && tSecs>60*30){
	alert('很遗憾，时间已到！');
	calculate();
	return;
}
var iSecs = tSecs % 60;
var iMins = Math.round((tSecs-30)/60);     
var sSecs ="" + ((iSecs > 9) ? iSecs :"0" + iSecs);     
var sMins ="" + ((iMins > 9) ? iMins :"0" + iMins);     
$("#face").html(sMins+":"+sSecs);

$("#in_usetime").val(tSecs);

if(isstop) return;
timerID = setTimeout("showtime()",1000);     
timerRunning = true;     
}     

function startclock () {     
// Make sure the clock is stopped     
stopclock();     
startday = new Date();     
clockStart = startday.getTime();     
showtime();     
}


function follow(){
	var url='?act=my&op=follow';
	$.get(url,function(res){
			if(res=="-1"){alert('请先登录！');return;}
		$("#div_follow").hide();
		alert('谢谢你的关注，我们会定期在官网“聪明行情”！');
	});
}

function sendStats(){
	$.get("?app="+app+"&op=sendstats&t="+Math.random(),function(res){
				if(res=="-1"){alert('请先登录！');return;}
		alert("已经发送到你微博可以随时去瞻仰 TA 了！“关注我”就可以随时掌握博友的聪明行情!");
		
	});
}

function sendmsg(){
	$.get("?app="+app+"&op=sendstatus&t="+Math.random(),function(res){
				if(res=="-1"){alert('请先登录！');return;}
		alert("已经发送到你微博！点击“关注我”就可以随时掌握博友的聪明行情!");
		
	});
}
function sendmsg2(type,uid,lv,msg){
	$.post("?app="+app+"&op=sendstatus2",{uid:uid,lv:lv,type:type,msg:msg},function(res){
		if(res=="-1"){alert('请先登录！');return;}
		alert("已经发送到你微博！");
		
	});	
}
var mlist=[];
var isinit=true;
var lasttime=0;
var maxid=1;

function refreshMsg(){
	var msg_list=$("#msg_list");

	var url="?app="+app+"&op=testlist&last="+lasttime+"&t="+Math.random();
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
			for(var i=mlist.length-1; i>=0 && i>=mlist.length-19;i--){
				ph.unshift(addMsgLine(i));
			}
			msg_list.html(ph.join(''));
			isinit=false;
			
			startani(5);

			/////if(wbisload)	WB.widget.atWhere.blogAt(EE("msg_list"), "a");
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
	

	$("#msg_list").prepend(addMsgLine(idx));

	$("#msg_block_"+mlist[idx].id).animate({
	   height: 25
	 }, 500);
	
	startani(idx-1);
}
function addMsgLine(idx){
	var msg=mlist[idx],ph=[];
	var name=msg.name+'<img src="'+urlbase+'images/weiboicon16_'+msg.lfrom+'.png" height="16" />';
	if(msg.verified*1==1) name+='<img src="'+urlbase+'images/vip_{$top[lfrom]}.gif" title="认证用户" />';

	var shareurl='http://v.t.qq.com/share/share.php?source=bookmark&title=';
	if(msg.lfrom=='tsina') shareurl='http://v.t.sina.com.cn/share/share.php?source=bookmark&title=';
	ph.push('<div class="msg_block noid'+maxid+'" id="msg_block_'+msg.id+'"'+(isinit?'':' style="height:0;"')+'><span class="testtime">'+msg.testtime+'</span> ');
	if(msg[app]*1==-1){
		ph.push('<a href="'+shareurl+'');
		ph.push('@'+msg.name+' 正在玩微博游戏《看看你有多聪明》，为他加油~_~!" target="_blank" title1="点击对他说话" '+(msg.lfrom=='tsina'?'wb_screen_name="'+msg.name+'"':'')+'>@'+name+' </a>开始测试。');
	}
	else{
		ph.push('恭喜 <a href="'+shareurl+'');
		ph.push('在玩微博游戏《看看你有多聪明》时看到了@'+msg.name+' EQ 为'+msg[app]+'分，经鉴定为 '+msg.ch+'，我都不敢测试了！" target="_blank" title1="点击对他说话" '+(msg.lfrom=='tsina'?'wb_screen_name="'+msg.name+'"':'')+'>@'+name+' </a>完成测试， EQ 为<b>'+msg[app]+'</b>分，经鉴定为 <b> '+msg.ch+'</b>!');
	}
	ph.push('</div>');
	if(maxid>19)
		$("#msg_list").find(".noid"+(maxid-19)).remove();
	maxid++;
	return ph.join('');
}


function closeView(){
	$("#zhengshupreview").hide();
	//$(document).unbind("click");
}
$(document).ready(function(){
	/*var snotice='<div class="contentFrame" style="clear:both;text-align:center; ">\
<div class="ui-widget" style="text-align:center; color:#f00;" id="ui-widget-content">\
友情提示：本站于今天 9:00~9:05 将进行一个小更新，在此期间将暂停5 分钟，请朋友们谅解！\
</div>\
</div>';

	if($('#div_notice1').length==0)
		$(".contentFrame:first").before(snotice);
	else
		$('#div_notice').html(snotice);*/

	$("#msg_list .msg_block").live("mouseover",	function(){$(this).addClass("msgHigh");});
	$("#msg_list .msg_block").live("mouseout",	function(){$(this).removeClass("msgHigh");});

	if(op=="ican") {
		startclock();
	}
	var msg_list=$("#msg_list");
	if(msg_list.length>0){
		refreshMsg();
	}
	
	var odivView=$("#zhengshupreview");
	var ww=$(window).width();
	var hh=$(window).height();

	$('.icoview').attr('alt','点击显示证书').css("cursor","pointer").click(function(event){			
			var pic=$(this).attr('zsurl');
			if(pic) {
				$('#zhengshuPic').attr('src',pic);
				odivView.show();
				$('#btn_send_2').unbind("click").click(function(){
					sendmsg();closeView();
				});
				var le=(ww-420) /2;
				var to=(hh-580) /2 + $(document).scrollTop();

				odivView.css({left:le,top:to});

				event.stopPropagation();
				return;
			}
			//return;
			var srcPic=$(this);
			var pa=$(this).attr('params').split(':');
			var url="?act=zs&op=geturl&type="+pa[0]+"&lv="+pa[1]+"&uid="+pa[2]+"&t="+Math.random();
			$.get(url,function(pic){
				if(url) {
					$('#zhengshuPic').attr('src',pic);
					odivView.show();
					$('#btn_send_2').unbind("click").click(function(){
						if(!myuid) {
							alert('请先登录！');return;
						}
						var msg='刚才在#看看你有多聪明#的排行榜里看到：“'+srcPic.parent().text()+'”，特发此微博表达我滔滔江水般的敬仰，呵呵！'+location.href;
						if(pa.length==4) msg=pa[3]+location.href;
						sendmsg2(pa[0],pa[2],pa[1],msg);closeView();
					});
					var le=(ww-420) /2;
					var to=(hh-580) /2  + $(document).scrollTop();
					odivView.css({left:le,top:to});	
				}
			})
			event.stopPropagation();

	});

	if(op=="login"){
		$.get("?act=my&op=syncfriends",function(){});
	}
}).click(function(){closeView();});
