
function checkChecked(name,num){
	$('input:radio[name='+name+']').each(function(i,n){
		//if(num==i)
			alert(this.checked);
	});
}

function calculate(){     
var storea=0;
checkChecked("q1",1);

if(K.q1[1].checked){storea+=1;}     
if(K.q2[2].checked){storea+=1;}     
if(K.q3[4].checked){storea+=1;}     
if(K.q4[1].checked){storea+=1;}     
if(K.q5[2].checked){storea+=1;}     
if(K.T6.value=="26"){storea+=2;}     
if(K.q7[0].checked){storea+=1;}     
if(K.q8[0].checked){storea+=1;}     
if(K.q9[2].checked){storea+=1;}     
if(K.q10[2].checked){storea+=1;}     
if(K.T11.value=="9"){storea+=1;}     
if(K.q12[3].checked){storea+=1;}     
if(K.q13[2].checked){storea+=1;}     
if(K.T14.value=="6"){storea+=1;}     
if(K.q15[2].checked){storea+=1;}     
if(K.q16[2].checked){storea+=1;}     
if(K.T17.value=="36"){storea+=1;}     
if(K.q18[1].checked){storea+=1;}     
if(K.q19[0].checked){storea+=1;}     
if(K.T20.value=="美国"){storea+=2;}     
if(K.q21[3].checked){storea+=2;}     
if(K.T22.value=="64"){storea+=2;}     
if(K.T23.value=="科学"){storea+=2;}     
if(K.T24.value=="式"){storea+=1;}     
if(K.T25.value=="X"||K.T25.value=="x"){storea+=1;}     
if(K.T26.value=="75"){storea+=1;}     
if(K.q27[4].checked){storea+=1;}     
if(K.T28.value=="奠"){storea+=1;}     
if(K.T29.value=="颠"){storea+=1;}     
if(K.T30.value=="5"){storea+=1;}     
if(K.T31.value=="36"){storea+=2;}     
if(K.q32[2].checked){storea+=2;}     
if(K.T33.value=="O"||K.T33.value=="o"){storea+=1;}     
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