//测试页逻辑
var MAXTIMEPER=15;
var usetime2=0;var isFilish=false;
function init() {
	next('init');
	$("#btn_b").show();
}

function subm(){
	var an=getAn();
	location.href='?app=daren&act=daren&op=showscore&an='+an;
}

function next(de){
	$(".btntest").unbind();
	//$("#test_main").hide('slow');

	if(isFilish) {
		subm();
		return;
	}
	var an=getAn();
	var url="?app=daren&act=daren&op="+de+'&an='+an+'&t='+Math.random();
	$.get(url,function(res){

		eval("var json="+res);
		clockStart=new Date().getTime();
		usetime2=json[0]*1; 
		usetime=json[1]*1;

		showtime();

		var co=json[2].split("/")[0]*1;
		var lidx=json[2].split("/")[1]*1;
		var lastScore=json[3];
		var score=json[4];
		addScore(lastScore,score);

		var sh=[],jl=json.length;

		sh.push('<table cellspacing="0" cellpadding="0" class="formtable"><caption style="padding-top:0;">');
		for(i=5;i<jl;i++){
			sh.push("<dl>");
			var qu=json[i];//['+qu.t+']
			if(i==jl-1){
				switch(qu.t){				
					case 1:
						$("#test_cap").html('<h2>博士考试：第一部分 &gt; 百科知识（四题）</h2><p>包括旅游,法律,金融,音乐,军事,动漫,地理,常识,体育,其它等</p>');
						break;
					case 2:
						$("#test_cap").html('<h2>博士考试：第二部分 &gt; 文史政治（三题）</h2><p>包括艺术,文学,历史,哲学,政治等</p>');
						break;
					case 3:
						$("#test_cap").html('<h2>博士考试：第三部分 &gt; 科技与科学</h2><p>包括物理,化学,电脑,科技,自然,天文等</p>');
						break;
				}
			}
			sh.push('<dt idx="'+qu.idx+'"><span class="testlist">'+qu.idx+'/'+co+'</span>　'+qu.q+'</dt>');

			var ql=qu.a.length;
			var sel=qu.a[0];
			var ABC=['','A','B','C','D','E','F','G'];
			for(j=1;j<ql;j++){
				if(qu.a[j])
				sh.push('<dd><a hiddenFocus="true" href="javascript:void(0);" '+(sel==j-1?' class="sel"':'')+'>'+ABC[j]+'. '+qu.a[j]+'</a></dd>');
			}
			sh.push("</dl>");
		}

		$("#test_main").html(sh.join(''));
		$("#test_main").show();
		
		if(co<=lidx){
			isFilish=true;
		}
		isLoading=false;
	});
}

function getAn(){
	var an='';
	$("#test_main dl").each(function(){
		var idx=$(this).find("dt").attr("idx");
		$(this).find("a").each(function(ii,v){
			if(this.className=="sel"){
				an=ii;	
			}
		});
	});
	return an;
}

$("#test_main dd a").live("click",function(){
	if(isLoading) return;
	$(this).parent().parent().find("a").removeClass("sel");
	this.className="sel";
	next('next');
	isLoading=true;
});

function initStopwatch2() {
var myTime = new Date();
return((myTime.getTime() - clockStart)/1000)+usetime2;
}  

var iclock=1;
function showtime(isstop) {
	if(timerID)clearTimeout(timerID);
var tSecs = Math.round(initStopwatch());
if(!isstop && tSecs>300){
	alert('很遗憾，时间已到！');
	subm();
	return;
}
var iSecs = tSecs % 60;
var iMins = Math.round((tSecs-30)/60);     
var sSecs ="" + ((iSecs > 9) ? iSecs :"0" + iSecs);     
var sMins ="" + ((iMins > 9) ? iMins :"0" + iMins);     
$("#face").html(sMins+":"+sSecs);
if(isstop) return;

var tSecs2 =MAXTIMEPER - Math.round(initStopwatch2());
if(tSecs2>=0) {
	$("#face2").html(tSecs2);
	if(iclock %2 ==0){
		drawClock( iclock/2 );
	}
	iclock++;
}else {
	iclock=1;$("#face2").html(0);
}
timerID = setTimeout("showtime()",1000);
timerRunning = true;
}

var lastScoreLeft=0;
var lastScoreTop=0;
var lastScoreHeight=300;
var lastScoreWidth=0;
var divScoreLeft=0;
var divScoreTop=0;

function addScore(lastScore,score) {
	if(!lastScore) return;
 //$("#test_main").parent().append('<div id="div_lastScore" style="color:#0f0; line-height:'+lastScoreHeight+'px;height:'+lastScoreHeight+'px;width:'+
//	 lastScoreWidth+'px;font-size:64px;position:absolute;left:'+lastScoreLeft+'px;top:'+lastScoreTop+';background:#ece;">'+lastScore+'</div>');
//return;
	poff=$("#test_main").parent().offset();
	lastScoreLeft=poff.left;
	lastScoreTop=poff.top;
	lastScoreWidth=$("#test_main").parent().width();
	
	poff=$("#div_score").offset();
	divScoreLeft=poff.left;
	divScoreTop=poff.top; //,width:lastScoreWidth,height:lastScoreHeight,lineHeight:lastScoreHeight
	//  $('#div_lastScore');
	var oldmain=$('#test_main');
	var ooo=oldmain.clone().css("position","absolute").css("border","1px solid #666").css("background-color","#CEEFF6").css({left:lastScoreLeft,top:lastScoreTop}).insertAfter(oldmain);
	oldmain.hide();
	ooo.animate({
    opacity: 0.2,
    left: divScoreLeft,
	top:divScoreTop,
    height: 'toggle',
	lineHeight:'0',
	fontSize:'0',
	width:'toggle'
  }, 400, function() {
		ooo.remove();
		$("#div_score").html('得分：'+score);
		
  });

}



function drawClock(num) {
	if(num>7) {return;	}

	var dclock=$('#div__clock');

	var lef=(num-1) * -141;

	if(dclock.length==0) {return;
		//$("#div__clock").append('<div id="clock_div_abc" style="background:url('+urlbase+'templets/default/daren_images/clock_block.png?t=33d)  '
		//+lef+'px 0; width:141px;height:141px;margin:2px;"></div>');
		return ;		
	}

	dclock.css({"background-position":lef+"px 0"});	

}


$(document).ready(function(){
	poff=$("#test_main").parent().offset();
	lastScoreLeft=poff.left;
	lastScoreTop=poff.top;
	lastScoreWidth=$("#test_main").parent().width();
	
	poff=$("#div_score").offset();
	divScoreLeft=poff.left;
	divScoreTop=poff.top;
	
	$("#test_main td").hover(function(){
		$(this).removeClass("answer-box").addClass("answer-box-green");
	},function(){
		$(this).addClass("answer-box").removeClass("answer-box-green");
	});

	//init();

});
