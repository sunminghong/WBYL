var usetime2=0;
var isFilish=false;
var isLoading=false;
var iclock=1;
var pr1=null;
var MAXTIME=3000;
var isinit=false;
var idx=0;

function init() {//return;
	$('#mask').show();
	$('#div_test').show();
	isinit=true;
	pr1.start({val:0,maxval:MAXTIME,timeout:400,step:4});	

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
		//alert(usetime);
		pr1.set(usetime,'');

		var co=json[2].split("/")[0]*1;
		var lidx=json[2].split("/")[1]*1;
		var lastScore=json[3];
		var score=json[4];
		addScore(lastScore,score);

		var jl=json.length;

		for(i=5;i<jl;i++){
			var qu=json[i];

//			if(qu.q.length>60) $('#test_cap').css("font-size","18px").css("line-height","25px;");
	//		else $('#test_cap').css("font-size","24px").css("line-height","33px;");
			idx=qu.idx;
			$('#question_index').html('问题'+qu.idx+'/'+co+'：');
			$('#question').html(qu.q);
			var ql=qu.a.length;
			var sel=qu.a[0];
			var ABC=['','A','B','C','D','E','F','G'];
			var ph=[];
			for(j=1;j<4;j++){
				
				if(qu.a[j]) {
					ph.push('<a class="answer" href="javascript:void(0);"><span id="answer'+j+'">'+ABC[j]+'. '+qu.a[j]+'</span></a>');
					//$('#answer'+j+'').html(ABC[j]+'. '+qu.a[j]);
				}else {
					//$('#answer'+j+'').html('');
				}
			}
			$('#div_answer').html(ph.join(''));
		}

		$("#test_main").show();

		iclock=1;
		if(co<=lidx){
			isFilish=true;
		}
		isLoading=false;
	});
}

var lastScoreLeft=0;
var lastScoreTop=0;
var lastScoreHeight=300;
var lastScoreWidth=0;
var divScoreLeft=0;
var divScoreTop=0;

function addScore(lastScore,score) {	if(isinit) {isinit=false; return; }
	if(idx<=0) return;
	poff=$("#div_icos b:eq("+(idx-1)+")").offset();
	divScoreLeft=poff.left;
	divScoreTop=poff.top+4; 

alert(lastScoreLeft);

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
		if(score>0) 
			poff.addClass('ico_right');
		else
			poff.addClass('ico_wrong');
		
  });

}


$(document).ready(function(){	
	pr1=new Progress({rand:'',conid:'progress',left:240,top:400,titclass:'',fn:function(){
		alert('很遗憾，时间已到！');
		subm(-1);
	}});
	
	poff=$("#test_main").offset();
	lastScoreLeft=poff.left;
	lastScoreTop=poff.top;
	lastScoreWidth=$("#test_main").width();
	
/*	
	$("#test_main td").find("div").hover(function(){
		if(isLoading) return;
		$(this).removeClass("answer-box").addClass("answer-box-green");
	},function(){
		if(isLoading) return;
		$(this).addClass("answer-box").removeClass("answer-box-green");
	}).
*/

	$('#div_answer').find('.answer').live('click',function(){
		if(isLoading) return;
		isLoading=true;
		//$(this).removeClass("answer-box").addClass("answer-box-green");
		var idx2=$(this).attr("id").replace("answer","")*1-1;

		next('next',idx2);
	});

	$('#btn_starttest').click(function() {
		init();
	});
	init();
	$('#mask').css({'width':$(document).width(),'height':$(document).height()});
});

function Progress(conf){
	this.pre="progress_";
	this.rand=conf.rand || parseInt(Math.random()*10000);
		
	this.bgid=this.pre+"bg_"+this.rand;
	this.fgid=this.pre+"fg_"+this.rand;
	if(conf.conid) 
		this.conid=conf.conid;
	else
		this.conid=this.pre+"con_"+this.rand;
	this.titid=this.pre+"tit_"+this.rand;
	
	this.val=conf.val || 0;
	this.maxval=conf.maxval || 100;
	this.fn=conf.fn || null;
	
	this.timer=null;
	this.timeout=0;
	this.step=1;
	this.isShow=false;
	
	conclass=conf.conclass || this.pre+"con";
	bgclass=conf.bgclass || this.pre+"bg";
	fgclass=conf.fgclass || this.pre+"fg";
	titclass=conf.titclass || this.pre+'tit';
	
	var htmlTitle='';
	if (typeof(conf.titclass)!="undefined" && conf.titclass=="") htmlTitle='';
	else
		htmlTitle='<div id="'+this.titid+'" class="'+titclass+'"></div>';
	if(document.getElementById(this.conid)) {
		$('#'+this.conid).css('display','block').css('class',conclass).html(htmlTitle+'<div id="'+this.bgid+'" class="'+bgclass+'"></div><div class="'+fgclass+'" id="'+this.fgid+'"></div>');
	}else {
		var shtm='<div style="display:block;top:'+conf.top+'px;left:'+conf.left+'px" id="'+this.conid+'" class="'+conclass+'">'
		+htmlTitle+'<div id="'+this.bgid+'" class="'+bgclass+'"></div><div class="'+fgclass+'" id="'+this.fgid+'"></div></div>';
		$('body').append(shtm);
	}

	this.ocon=this.geto(this.conid);
	this.obg=this.geto(this.bgid);
	this.ofg=this.geto(this.fgid);
	this.otit=this.geto(this.titid);
	
	this.width=$(this.obg).width();
	$(this.ocon).hide();
};

//{timeout,tit,per,step,val,maxval,fn}
Progress.prototype.start=function(conf){
	this.timeout=conf.timeout;
	this.tit=conf.tit;
	this.step=conf.per?this.maxval*conf.per:conf.step;
	this.val=(typeof conf.val!='undefined')?conf.val:this.val;
	this.maxval=conf.maxval || this.maxval;
	this.fn=((typeof conf.fn!='undefined')?conf.fn : this.fn);	
	
	var self=this;
	self.set(self.val,self.tit);
	if(!this.isShow){
		$(this.ocon).show();
		this.isShow=true;
	}
	
	this.timer=setInterval(function(){
			self.set(self.val+self.step,self.tit);
			if (self.val>=self.maxval) {
					//alert(self.val+'||' + conf.val +'||'+self.maxval);
				self.stop();
				if(typeof self.fn=='function'){self.fn(self);}
				return;
			}					  
		},self.timeout
	);
};
Progress.prototype.stop=function(){
	if(this.timer){clearInterval(this.timer);this.timer=null;}
};
Progress.prototype.geto=function(id){
	return document.getElementById(id);
};
Progress.prototype.set=function(val,tit,maxval){
	this.val=val;
	this.maxval=maxval || this.maxval;
	
	if(this.otit)	{
		this.tit=tit;
		this.otit.innerHTML=tit.replace('%v%',this.val).replace("%m%",this.maxval);
	}
	var le=parseInt(this.val/this.maxval*this.width);
	$(this.ofg).css('left',(le)).css('width',this.width-le-5);
};
Progress.prototype.hide=function(){
	if(this.isShow){
		this.ocon.style.display='none';
		this.isShow=false;
	}
	this.stop();
}
