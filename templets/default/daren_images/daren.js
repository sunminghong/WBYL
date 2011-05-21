var usetime2=0;
var isFilish=false;
var isLoading=false;
var iclock=1;

function init() {
	next('init',0);
	$("#btn_b").show();
}

function subm(an){
	location.href='?app=daren&act=daren&op=showscore&an='+an;
}

function next(de,an){
	//$(".btntest").unbind();
	//$("#test_main").hide('slow');

	if(isFilish) {
		subm(an);
		return;
	}

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
			var qu=json[i];

			if(qu.q.length>60) $('#test_cap').css("font-size","18px").css("line-height","25px;");
			else $('#test_cap').css("font-size","24px").css("line-height","33px;");

			$('#test_cap').html('<span class="testlist">'+qu.idx+'/'+co+'</span>'+qu.q);
			var ql=qu.a.length;
			var sel=qu.a[0];
			var ABC=['','A','B','C','D','E','F','G'];
			for(j=1;j<4;j++){
				if(qu.a[j]) {
					$('#answer'+j+'').html(ABC[j]+'. '+qu.a[j]);
				}else {
					$('#answer'+j+'').html('');
				}
			}
		}

		$("#test_main").show();
			
		var ttt=Math.round(initStopwatch2());
		drawClock(parseInt(ttt/2));

		iclock=1;
		if(co<=lidx){
			isFilish=true;
		}
		isLoading=false;
	});
}

function initStopwatch2() {
	var myTime = new Date();
	return((myTime.getTime() - clockStart)/1000)+usetime2;
}  

function showtime(isstop) {
	if(timerID)clearTimeout(timerID);
	var tSecs = Math.round(initStopwatch());
	if(!isstop && tSecs>300){
		alert('很遗憾，时间已到！');
		subm(-1);
		return;
	}
	var iSecs = tSecs % 60;
	var iMins = Math.round((tSecs-30)/60);     
	var sSecs ="" + ((iSecs > 9) ? iSecs :"0" + iSecs);     
	var sMins ="" + ((iMins > 9) ? iMins :"0" + iMins);     
	$("#face").html(sMins+":"+sSecs);
	if(isstop) return;

	var ttt=Math.round(initStopwatch2());
	drawClock( parseInt(ttt/2 ));

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
	poff=$("#test_main").parent().offset();
	lastScoreLeft=poff.left;
	lastScoreTop=poff.top;
	lastScoreWidth=$("#test_main").parent().width();
	if(lastScore) 
		poff=$("#div_score").offset();
	else 
		poff=$("#test_cap").offset();

	divScoreLeft=poff.left;
	divScoreTop=poff.top+4; 
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
		$("#div_score").html(score);
		
  });

}



function drawClock(num) {
	if(num>7) {num=7;}
	else if(num==0) num=1;

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
	
	$("#test_main td").find("div").hover(function(){
		if(isLoading) return;
		$(this).removeClass("answer-box").addClass("answer-box-green");
	},function(){
		if(isLoading) return;
		$(this).addClass("answer-box").removeClass("answer-box-green");
	}).click(function(){
		if(isLoading) return;
		isLoading=true;
		//$(this).removeClass("answer-box").addClass("answer-box-green");
		var idx=$(this).attr("id").replace("answer","")*1-1;

		next('next',idx);
	});

	init();

});
