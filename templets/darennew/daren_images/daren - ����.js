var __url=urlbase+"?app=daren";

var usetime2=0;
var isFilish=false;
var isLoading=false;
var iclock=1;
var pr1=null;
var MAXTIME=3000;
var isinit=false;
var idx=0;
var isfollow=false;

var qtype=1,qtypename="综合类";

function init(iscontinue) {//return;
	$('#mask').show();
	var w=$('#div_test').width();
	var h=$('#div_test').height();
	var l=(ww-w)/2;
	var t=(hh-h)/2;
	t=t>0?t:110;
	t+=$(window).scrollTop();
	$('#div_test').css('left',l).css('top',t).show();
	isinit=true;
	pr1.start({val:0,maxval:MAXTIME,timeout:400,step:4});	

	$("#btn_b").show();
	if(iscontinue)
		next('init',0);
	else {
		next('init&qtype='+qtype,0);
		location.hash="#continue";
	}
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
		if(res==-1) {
			alert('请先登录哟！');
			return;
		}
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


function _follow(uid,lfrom,lfromuid){
	var param={};
	if(uid) {
		var curr=data[0];
		param={byuid:uid,bylfrom:lfrom,bylfromuid:lfromuid	};
	}
	else 
		param= null;

	var url=__url+'&op=follow';
	$.post(url,param,function(res){
		switch(res) {
			case "1":
				alert('已经帮你关注TA了，以后就可以实时看到他的动态了！');
				break;
			case "-1":
				alert('请先登录吧，否则他怎么知道你关注了 ta ?');
				login();
				break;
			case "-2":
				alert('你知道吗？世界上最远的距离不是生与死的距离，也不是心和心的距离，而是你们一个在新浪微博一个在腾讯微博里！');
				break;
		}
	});
}

function _sendstatus(){
	var url=__url+'&op=sendstatus';
	var is_follow=0;
	if($('#iffollow').attr('checked')) is_follow=1;
	if(isfollow) {
		is_follow=0;
	}
	else if(!is_follow &&  confirm("是否关注开发者孙铭鸿？")){
		is_follow=1;
	}

	$.post(url,{is_follow:is_follow,msg:$('#result_sendstatus').val()},function(res){
		if(res=="-1"){alert('请先登录！');login();return;}

		isfollow=true;
		alert("已经将这张”范儿“分享到你的微博！");		
	});	
}

var lastScoreLeft=0;
var lastScoreTop=0;
var lastScoreHeight=300;
var lastScoreWidth=0;
var divScoreLeft=0;
var divScoreTop=0;

function addScore(lastScore,score,obj) {	if(isinit) {isinit=false; return; }
	if(idx<=0) return;
	var oo=$('.timeline').position();
	var o=$("#div_icos").position();
	var poff=$("#div_icos b:eq("+(idx-1)+")");
	var po=poff.position();
	divScoreLeft=po.left+o.left+oo.left;
	divScoreTop=po.top+4+o.top+oo.top; 

	var oldmain=obj || $('#test_main');
	var lastScoreLeft=oldmain.position().left;
	var lastScoreTop=oldmain.position().top;
	//alert(lastScoreLeft+'/'+lastScoreTop+'==>'+divScoreLeft+'/'+divScoreTop);
	var ooo=oldmain.clone().css("position","absolute").css("border","1px solid #666")
		.css("background-color","#CEEFF6")
		.css({left:lastScoreLeft,top:lastScoreTop}).insertAfter(oldmain);
	//$('body').append(ooo);

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
		$("#div_score").html(lastScore);
		if(lastScore>0) 
			poff.addClass('ico_right');
		else
			poff.addClass('ico_wrong');
		
  });
}


var ww=0;
var hh=0;

$(document).ready(function(){	
	pr1=new Progress({rand:'',conid:'progress',left:240,top:400,titclass:'',fn:function(){
		alert('很遗憾，时间已到！');
		subm(-1);
	}});
	
	ww=$(window).width();
	hh=$(window).height();
	
	$('#div_answer').find('.answer').live('click',function(){
		if(isLoading) return;
		isLoading=true;
		//$(this).removeClass("answer-box").addClass("answer-box-green");
		var idx2=$(this).find('span').attr("id").replace("answer","")*1-1;

		next('next',idx2);
	});

	$('#btn_starttest').click(function() {
		init();
	});
	//init();
	$('#mask').css({'width':$(document).width(),'height':$(document).height()});

	var oldico=null;
	if(op="ican") {		
		if(location.hash.indexOf('continue')>0)
			init(true);
		else {
				$('#div_qtypeicos .ico').hover(
					function(e) {
						oldico=$(this);
						var a=oldico.offset();
						qtypename=oldico.attr('qtypename');
						qtype=oldico.attr('qtype');
						var cn=this.className.replace('ico ','');				
						cn+='_big';
						$('#icohover').css('top',a.top).css('left',a.left-15).show().attr('qtypename',qtypename).attr('qtype',qtype);
						$('#icohover .ico_big').attr('class','ico_big '+cn);
						$('#icohover .ico_tips').html(qtypename);
						oldico.css('visibility','hidden');
					},
					function(e){}
				);
				$('#icohover').hover(
					function(e){},
					function(e) {
						$('#icohover').hide();
						if(oldico) oldico.css('visibility','visible');
					}
				).click(function(){
					init();
				});	
		}
	}
	if (op=='showscore')
	{
		$('#sendstatus').click(function(){
			 _sendstatus();
		});
	}

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
