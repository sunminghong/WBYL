function $(id){return document.getElementById(id);} 
function calculate(){     
var storea=0;
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
var iqvalue=0;     
if(storea=="1"){iqvalue=20;}     
if(storea=="2"){iqvalue=30;}     
if(storea=="3"){iqvalue=40;}     
if(storea=="4"){iqvalue=50;}     
if(storea=="5"){iqvalue=60;}     
if(storea=="6"){iqvalue=65;}     
if(storea=="7"){iqvalue=65;}     
if(storea=="8"){iqvalue=67;}     
if(storea=="9"){iqvalue=67;}     
if(storea=="10"){iqvalue=68;}     
if(storea=="11"){iqvalue=70;}     
if(storea=="12"){iqvalue=72;}     
if(storea=="13"){iqvalue=74;}     
if(storea=="14"){iqvalue=75;}     
if(storea=="15"){iqvalue=80;}     
if(storea=="16"){iqvalue=83;}     
if(storea=="17"){iqvalue=85;}     
if(storea=="18"){iqvalue=88;}     
if(storea=="19"){iqvalue=89;}     
if(storea=="20"){iqvalue=90;}     
if(storea=="21"){iqvalue=93;}     
if(storea=="22"){iqvalue=95;}     
if(storea=="23"){iqvalue=100;}     
if(storea=="24"){iqvalue=105;}     
if(storea=="25"){iqvalue=108;}     
if(storea=="26"){iqvalue=110;}     
if(storea=="27"){iqvalue=111;}     
if(storea=="28"){iqvalue=114;}     
if(storea=="29"){iqvalue=115;}     
if(storea=="30"){iqvalue=120;}     
if(storea=="31"){iqvalue=122;}     
if(storea=="32"){iqvalue=125;}     
if(storea=="33"){iqvalue=127;}     
if(storea=="34"){iqvalue=128;}     
if(storea=="35"){iqvalue=130;}     
if(storea=="36"){iqvalue=135;}     
if(storea=="37"){iqvalue=140;}     
if(storea=="38"){iqvalue=143;}     
if(storea=="39"){iqvalue=145;}     
if(storea=="40"){iqvalue=150;}     
var i=$("face").innerHTML;     
return confirm("谢谢你参加智商测试!\n\n"+     
"你的智商为:"+iqvalue+"\n\n"+     
"您所用的时间：" +i+"\n"+     
"===================================\n70-    --弱智\n"+     
"70-89  --智力低下\n"+     
"90-99  --智力中等\n"+     
"100-109--智力中上\n"+     
"110-119--智力优秀\n"+     
"120-129--智力非常优秀\n"+     
"130-139--智力非常非常优秀\n"+     
"140+   --天才");     
return flash     
}     
     
var timerID = null;     
var timerRunning=false;     
startday=new Date();     
clockStart=startday.getTime();     
     
function initStopwatch() {     
var myTime = new Date();     
return((myTime.getTime() - clockStart)/1000)}     
     
function stopclock (){     
if(timerRunning)     
clearTimeout(timerID);     
timerRunning = false;     
}     
     
function showtime () {     
var tSecs = Math.round(initStopwatch());     
var iSecs = tSecs % 60;     
var iMins = Math.round((tSecs-30)/60);     
var sSecs ="" + ((iSecs > 9) ? iSecs :"0" + iSecs);     
var sMins ="" + ((iMins > 9) ? iMins :"0" + iMins);     
$("face").innerHTML = sMins+":"+sSecs;     
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