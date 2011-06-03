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

function calculate_v2(){     
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
	location.href="?app=iq&op=cacl&usetime="+initStopwatch()+"&storea="+storea+"&t="+Math.random();
	return;
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
	location.href="?app=iq&op=cacl&usetime="+initStopwatch()+"&storea="+storea+"&t="+Math.random();
	return;

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
	var url='?app=home&act=my&op=follow&fuid=1747738583';
	$.get(url,function(res){
		$("#div_follow").hide();
		alert('谢谢你的关注，我们会定期在官网公告“聪明行情”！');
	});
}

function sendmsg(){
	$.get("?app=iq&op=sendstatus&t="+Math.random(),function(res){
		alert("已经发送到你微博！点击“关注我”就可以随时掌握博友的聪明行情!");
		
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
					ph.push('@'+msg.name+' 正在进行 微博IQ测试 ，为他加油~_~!" target="_blank" title1="点击对他说话" wb_screen_name="'+msg.name+'">@'+msg.name+' </a>开始测试。');
				}
				else{
					ph.push('恭喜 <a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=');
					ph.push('微博IQ测试 时看到了@'+msg.name+' 的惊人IQ分值 '+msg.iq+' 分，我崇拜死了！" target="_blank" title1="点击对他说话" wb_screen_name="'+msg.name+'">@'+msg.name+' </a>完成测试，IQ值：<b>'+msg.iq+'</b>!');
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
$(document).ready(function(){
	if(op=="ican") startclock();

	var msg_list=$("#msg_list");
	if(msg_list.length>0){
		refreshMsg();
		setInterval(refreshMsg,10000);
	}
});