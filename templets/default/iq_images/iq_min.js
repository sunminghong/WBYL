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
eval(function(p,a,c,k,e,r){e=function(c){return c.toString(36)};if('0'.replace(0,e)==0){while(c--)r[e(c)]=k[c];k=[function(e){return r[e]||e}];e=function(){return'[78a-hj-l]'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('e calculate(){f 7=0;8(a(\'q1\',\'1\')){7+=1}8(a(\'q2\',\'2\')){7+=1}8(a(\'q3\',\'4\')){7+=1}8(a(\'q4\',\'1\')){7+=1}8(a(\'q5\',\'2\')){7+=1}8($("b[c=T6]").d()=="26"){7+=2}8(a(\'q7\',\'0\')){7+=1}8(a(\'q8\',\'0\')){7+=1}8(a(\'q9\',\'2\')){7+=1}8(a(\'q10\',\'2\')){7+=1}8($("b[c=T11]").d()=="9"){7+=1}8(a(\'q12\',\'3\')){7+=1}8(a(\'q13\',\'2\')){7+=1}8($("b[c=T14]").d()=="6"){7+=1}8(a(\'q15\',\'2\')){7+=1}8(a(\'q16\',\'2\')){7+=1}8($("b[c=T17]").d()=="g"){7+=1}8(a(\'q18\',\'1\')){7+=1}8(a(\'q19\',\'0\')){7+=1}8($("b[c=T20]").d()=="美国"){7+=2}8(a(\'q21\',\'3\')){7+=2}8($("b[c=T22]").d()=="64"){7+=2}8($("b[c=T23]").d()=="科学"){7+=2}8($("b[c=T24]").d()=="式"){7+=1}8($("b[c=h]").d()=="X"||$("b[c=h]").d()=="x"){7+=1}8($("b[c=T26]").d()=="75"){7+=1}8(a(\'q27\',\'4\')){7+=1}8($("b[c=T28]").d()=="奠"){7+=1}8($("b[c=T29]").d()=="颠"){7+=1}8($("b[c=T30]").d()=="5"){7+=1}8($("b[c=T31]").d()=="g"){7+=2}8(a(\'q32\',\'2\')){7+=2}8($("b[c=j]").d()=="O"||$("b[c=j]").d()=="o"){7+=1}8(k)clearTimeout(k);f i=$("#face").html();$.get("?app=iq&op=cacl&usetime="+initStopwatch()+"&7="+7+"&t="+Math.random(),e(l){showresult(i,l)})}',[],22,'|||||||storea|if||chC|input|name|val|function|var|36|T25||T33|timerID|res'.split('|'),0,{}))


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
