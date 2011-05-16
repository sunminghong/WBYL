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

function showresult(usetime,res){
	eval("var score="+res);

		$("#iq_val").html(score.nowiq);

		$("#use_time").html(usetime);
		$("#ui-widget-result").show();
		$("#ui-widget-result-send").show();
		$("#ui-widget-content").hide();
		
		var msg='你共测试'+score.testCount+' 次， 最高IQ值是 '+score.iq+' , 排名第<b> '+score.top+' </b>, 打败了 <b>'+score.win+'%</b> 的人'+(score.lostname?'(包括'+score.lostname+'~_~）':'')+'，加油！！！你获得证书：<img src="'+urlbase+'images/zhengshu_iq_ico_'+score.iqlv+'.png" />';
		$("#div_result").html(msg);
		
}


function calculate(){     
	var storea=0;

	if($("input[name=q1]").val()=="m"||$("input[name=q1]").val()=="M"){storea+=6;}
	if($("input[name=q2]").val()=="15"){storea+=6;}
	if($("input[name=q3]").val()=="8"){storea+=6;}
	if($("input[name=q4]").val()=="6"){storea+=6;}
	if($("input[name=q5]").val()=="5"){storea+=6;}
	if($("input[name=q6]").val()=="4"){storea+=6;}
	if($("input[name=q7]").val()=="1"){storea+=6;}
	if($("input[name=q8]").val()=="2"){storea+=6;}
	if(chC('q9','1')){storea+=6;}
	if(chC('q10','3')){storea+=5;}
	if(chC('q11','2')){storea+=5;}
	if(chC('q12','0')){storea+=5;}
	if(chC('q13','2')){storea+=5;}
	if(chC('q14','3')){storea+=5;}
	if(chC('q15','2')){storea+=5;}
	if(chC('q16','2')){storea+=5;}
	if(chC('q17','1')){storea+=5;}
	if(chC('q18','3')){storea+=5;}
	if(chC('q19','3')){storea+=5;}
	if(chC('q20','3')){storea+=5;}
	if(chC('q21','2')){storea+=5;}
	if(chC('q22','2')){storea+=5;}
	if(chC('q23','3')){storea+=5;}
	if(chC('q24','1')){storea+=5;}
	if(chC('q25','0')){storea+=5;}
	if(chC('q26','0')  && chC('q26','3')){storea+=5;}
	if(chC('q27','1') && chC('q27','2')){storea+=5;}
	if(chC('q28','0') && chC('q28','3')){storea+=5;}
	if(chC('q29','1') && chC('q29','3')){storea+=5;}
	if(chC('q30','3')){storea+=5;}
	if(chC('q31','2')){storea+=5;}
	if(chC('q32','1')){storea+=5;}
	if(chC('q33','2')){storea+=5;}

    if(timerID)clearTimeout(timerID);
	var i=$("#face").html();
	$.get("?app=iq&op=cacl&usetime="+initStopwatch()+"&storea="+storea+"&t="+Math.random(),function(res){
		eval("var score="+res);
		alert(score);
		$("#iq_val").html(score.nowiq);

		$("#use_time").html(i);
		$("#ui-widget-result").show();
		$("#ui-widget-result-send").show();
		$("#ui-widget-content").hide();

		
		var msg='你共测试'+score.testCount+' 次， 最高IQ值是 '+score.iq+' , 排名第<b> '+score.top+' </b>, 打败了 <b>'+score.win+'%</b> 的人(包括'+score.lostname+'~_~）加油！！！';
		$("#div_result").html(msg);
		
	});
}     


var timerID = null;     
var timerRunning=false;     
startday=new Date();     
clockStart=startday.getTime();     
     
function initStopwatch() {     
var myTime = new Date();     
return((myTime.getTime() - clockStart)/1000)
}     
     
function stopclock (){     
if(timerRunning)     
clearTimeout(timerID);     
timerRunning = false;     
}     
     
function showtime () {
var tSecs = Math.round(initStopwatch());
if(tSecs>60*30){
	alert('很遗憾，时间已到！');
	calculate();
	return;
}
var iSecs = tSecs % 60;
var iMins = Math.round((tSecs-30)/60);     
var sSecs ="" + ((iSecs > 9) ? iSecs :"0" + iSecs);     
var sMins ="" + ((iMins > 9) ? iMins :"0" + iMins);     
$("#face").html(sMins+":"+sSecs);
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
		$("#div_follow").hide();
		alert('谢谢你的关注，我们会定期在官网“聪明行情”！');
	});
}

function sendStats(){
	$.get("?app=iq&op=sendstats&t="+Math.random(),function(res){
		alert("已经发送到你微博可以随时去瞻仰 TA 了！“关注我”就可以随时掌握博友的聪明行情!");
		
	});
}

function sendmsg(){
	$.get("?app=iq&op=sendstatus&t="+Math.random(),function(res){
		alert("已经发送到你微博！点击“关注我”就可以随时掌握博友的聪明行情!");
		
	});
}
function sendmsg2(uid,lv){
	$.get("?app=iq&op=sendstatus2&uid="+uid+"&lv="+lv+"&t="+Math.random(),function(res){
		alert("已经发送到你微博！");
		
	});	
}

function refreshMsg(){
	var msg_list=$("#msg_list");
	var url="?app=iq&op=testlist&t="+Math.random();
	if(op=="login") url+="&mo=1"
	$.get(url,function(res){
			eval("var mlist="+res);
			if(mlist.length == 0) return;
			
			var ph=[];
			for(var i=0;i<mlist.length;i++){
				var msg=mlist[i];
				ph.push('<span class="msg_block"><span class="testtime">'+msg.testtime+'</span> ');
				if(msg.iq*1==-1){
					ph.push('<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
					ph.push('@'+msg.name+' 正在玩微博游戏《看看你有多聪明》，为他加油~_~!" target="_blank" title1="点击对他说话" '+(msg.lfrom=='tsina'?'wb_screen_name="'+msg.name+'"':'')+'>@'+msg.name+' </a>开始测试。');
				}
				else{
					ph.push('恭喜 <a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
					ph.push('在玩微博游戏《看看你有多聪明》时看到了@'+msg.name+' IQ 为'+msg.iq+'分，经鉴定为 '+msg.ch+'，我都不敢测试了！" target="_blank" title1="点击对他说话" '+(msg.lfrom=='tsina'?'wb_screen_name="'+msg.name+'"':'')+'>@'+msg.name+' </a>完成测试， IQ 为<b>'+msg.iq+'</b>分，经鉴定为 <b> '+msg.ch+'</b>!');
				}
				ph.push('</span>');
			}
			msg_list.html(ph.join(''));
			$("#msg_list .msg_block").hover(
				function(){$(this).addClass("msgHigh");},
				function(){$(this).removeClass("msgHigh");}
			);
			if(wbisload)	WB.widget.atWhere.blogAt(EE("msg_list"), "a");
		});
}

function closeView(){
	$("#zhengshupreview").hide();
	//$(document).unbind("click");
}
$(document).ready(function(){
	if(op=="ican") {
		startclock();
	}
	var msg_list=$("#msg_list");
	if(msg_list.length>0){
		refreshMsg();
	}
	if(op=="ready"){
		$.get("?act=my&op=syncfriends",function(){
			if(msg_list.length>0) setInterval(refreshMsg,10000);			
		});
	}else{
		if(msg_list.length>0) setInterval(refreshMsg,10000);		
	}
	
	var odivView=$("#zhengshupreview");
	var ww=$(window).width();
	var hh=$(window).height();

	$('.icoview').attr('alt','点击显示证书').css("cursor","pointer").click(function(event){			
			var pic=$(this).attr('zsurl');
			if(pic) {
				$('#zhengshuPic').attr('src',pic);
				$('#btn_send_2').unbind("click").click(function(){
					sendmsg();closeView();
				});
				odivView.show();
				var le=(ww-odivView.width()) /2;
				var to=(hh-odivView.height()) /2 + $(document).scrollTop();

				odivView.css({left:le,top:to});

				event.stopPropagation();
				return;
			}
			return;
			var pa=$(this).attr('params').split(':');
			var url="?act=zs&op=geturl&type="+pa[0]+"&lv="+pa[1]+"&uid="+pa[2]+"&t="+Math.random();
			$.get(url,function(pic){
				if(url) {
					$('#zhengshuPic').attr('src',pic);
					$('#btn_send_2').unbind("click").click(function(){
						sendmsg2(pa[2]);closeView();
					});
					odivView.show();
					var le=(ww-odivView.width()) /2;
					var to=(hh-odivView.height()) /2  + $(document).scrollTop();
					odivView.css({left:le,top:to});	
				}
			})
			event.stopPropagation();

	});

}).click(function(){closeView();});
