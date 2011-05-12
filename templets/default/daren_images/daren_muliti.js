//测试页逻辑

var usetime2=0;
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
	$("#test_main").hide('slow');
	//|idx_sel;
	var an=getAn();
	var url="?app=daren&act=daren&op="+de+'&an='+an+'&t='+Math.random();
	$.get(url,function(res){
		$("#test_tr1").hide();
		$("#test_tr2").show();

		eval("var json="+res);
		clockStart=new Date().getTime();
		usetime2=json[0]*1; 
		usetime=json[1]*1;

		showtime();

		var co=json[2].split("/")[0]*1;
		var lidx=json[2].split("/")[1]*1;
		
		var sh=[],jl=json.length;

		sh.push('<table cellspacing="0" cellpadding="0" class="formtable"><caption style="padding-top:0;">');
		for(i=3;i<jl;i++){
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
		$("#test_main").show('fast');
		
		if(co<=lidx){
			$("#btn_down").html("确定完成测试&raquo;").click(function(){subm()});
		}
		else{
			$("#btn_up").show();
			$("#btn_down").html("下一题&raquo;").click(function(){next('next')});
		}
		$("#btn_up").click(function(){next('up')});
	});
}

function getAn(){
	var an='';
	$("#test_main dl").each(function(){
		var idx=$(this).find("dt").attr("idx");
		$(this).find("a").each(function(ii,v){
			if(this.className=="sel"){
				an+="|"+idx+"_;";	
			}
		});
	});
	return an;
}

$("#test_main dd a").live("click",function(){
	$(this).parent().parent().find("a").removeClass("sel");
	this.className="sel";
});

function initStopwatch2() {
var myTime = new Date();
return((myTime.getTime() - clockStart)/1000)+usetime2;
}  

function showtime(isstop) {
	if(timerID)clearTimeout(timerID);
var tSecs = Math.round(initStopwatch());
if(!isstop && tSecs>300){
	alert('很遗憾，时间已到！');
	calculate();
	return;
}
var iSecs = tSecs % 60;
var iMins = Math.round((tSecs-30)/60);     
var sSecs ="" + ((iSecs > 9) ? iSecs :"0" + iSecs);     
var sMins ="" + ((iMins > 9) ? iMins :"0" + iMins);     
$("#face").html(sMins+":"+sSecs);
if(isstop) return;

var tSecs2 =20 - Math.round(initStopwatch2());
if(tSecs2<0) tSecs2=0;
$("#face2").html(tSecs2);

timerID = setTimeout("showtime()",1000);
timerRunning = true;
}   

$(document).ready(function(){ init();});