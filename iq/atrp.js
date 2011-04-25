function init(){
	$("#atrp_tr1").hide();
	$("#atrp_tr2").show();
	next('init');
	$("#btn_b").show();
}
function subm(){
	var an=getAn();
	location.href='cp.php?ac=atrp&op=showscore&an='+an;
}

function next(de){
	$(".btnatrp").unbind();
	$("#atrp_cap").html('djfasd;fjasdf');
	//|idx_sel;
	var an=getAn();
	var url="cp.php?ac=atrp&op="+de+'&an='+an;
	jq.get(url,function(res){
		eval("var json="+res);
		var co=json[0].split("/")[0]*1;
		var lidx=json[0].split("/")[1]*1;
		
		var sh=[],jl=json.length;
		sh.push('<table cellspacing="0" cellpadding="0" class="formtable"><caption>');
		for(i=1;i<jl;i++){
			sh.push("<dl>");
			var qu=json[i];//['+qu.t+']
	
			switch(qu.t){				
				case 1:
					$("#atrp_cap").html('<h2>ATRP水平级别测试：第一部分 &gt; 训练经历。</h2><p>通过了解您的年龄、场上活动时间、运动系统性和装备消耗等方面的问题，对您的网球水平进行评判；</p>');
					break;
				case 2:
					$("#atrp_cap").html('<h2>ATRP水平级别测试：第二部分 &gt; 网球基础技术。</h2><p>通过正、反手、上旋、截击等一系列网球基础技术环节的考察，评判您的网球基础技术水平；</p>');
					break;
				case 3:
					$("#atrp_cap").html('<h2>ATRP水平级别测试：第三部分 &gt; 比赛与实战表现。</h2><p>通过正式或者非正式的比赛、对练等实战表现得结果，对您的技术发挥、经验和稳定性等方面进行评判；</p>');
					break;
			}
			sh.push('<dt idx="'+qu.idx+'"><span class="atrplist">'+qu.idx+'/'+co+'</span>：'+qu.q+'</dt>');

			var ql=qu.a.length;
			var sel=qu.a[0];
			for(j=1;j<ql;j++){
				sh.push('<dd><a hiddenFocus="true" href="javascript:void(0);" '+(sel==j-1?' class="sel"':'')+'>'+qu.a[j]+'</a></dd>');
			}
			sh.push("</dl>");
		}
			$("#atrp_cap").html('<h2>ATRP水平级别测试：第一部分 &gt; 训练经历。</h2><p>通过了解您的年龄、场上活动时间、运动系统性和装备消耗等方面的问题，对您的网球水平进行评判；</p>');
						
		$("#atrp_main").html(sh.join(''));
		
		if(co<=lidx){
			$("#btn_down").html("确定完成测试").click(function(){subm()});
		}
		else{
			$("#btn_down").html("下一页").click(function(){next('next')});
		}
		$("#btn_up").click(function(){next('up')});
	});
}

function getAn(){
	var an='';
	$("#atrp_main dl").each(function(){
		var idx=$(this).find("dt").attr("idx");
		$(this).find("a").each(function(ii,v){
			if(this.className=="sel"){
				an+="|"+idx+"_"+ii+";";	
			}
		});
	});
	return an;
}

$("#atrp_main dd a").live("click",function(){
	$(this).parent().parent().find("a").removeClass("sel");
	this.className="sel";
});