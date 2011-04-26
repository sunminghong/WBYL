
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
	$.get("?app=iq&op=cacl&usetime="+initStopwatch()+"&storea="+storea+"&t="+Math.random(),function(res){
		var iqvalue=parseInt(res);
		
		$("#iq_val").html(res);
		$("#use_time").html(i);
		$("#ui-widget-result").show();
		$("#ui-widget-result-send").show();
		$("#ui-widget-content").hide();
		
	});
}     
function sendmsg(){
	$.get("?app=iq&op=sendstatus&t="+Math.random(),function(res){
		alert("已经发送到你微博！IQ是随着你的经历、学识的增长而进步的，我们也将收集更科学的题目，常回来看看!");
		
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

startclock();