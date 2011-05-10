function init(){
	$("#test_tr1").hide();
	$("#test_tr2").show();
	next('init');
	$("#btn_b").show();
}
function subm(){
	var an=getAn();
	location.href='?app=iq&act=test&op=showscore&an='+an;
}

function next(de){
	$(".btntest").unbind();
	$("#test_cap").html('开始测试');
	//|idx_sel;
	var an=getAn();
	var url="?app=iq&act=test&op="+de+'&an='+an;
	$.get(url,function(res){
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
					$("#test_cap").html('<h2>题 型 一 &gt; 中华人民共和国</h2><p>利比里亚只</p>');
					break;
				case 2:
					$("#test_cap").html('<h2>题 型 二 &gt; 中华人民共和国</h2><p>顶替枯无可奈何花落去 顶替模压 棋</p>');
					break;
				case 3:
					$("#test_cap").html('<h2>题 型 三 &gt; 中华人民共和国</h2><p>sdf asdf asdf asdfasfsa</p>');
					break;
			}
			sh.push('<dt idx="'+qu.idx+'"><span class="testlist">'+qu.idx+'/'+co+'</span>下列'+qu.q+'</dt>');

			var ql=qu.a.length;
			var sel=qu.a[0];
			for(j=1;j<ql;j++){
				sh.push('<dd><a hiddenFocus="true" href="javascript:void(0);" '+(sel==j-1?' class="sel"':'')+'>'+qu.a[j]+'</a></dd>');
			}
			sh.push("</dl>");
		}
			$("#test_cap").html('<h2>中华人民共和国 &gt; 中华人民</h2><p>中华人民共和国中华人民共和国中华人民共和国中华人民共和国</p>');
						
		$("#test_main").html(sh.join(''));
		
		if(co<=lidx){
			$("#btn_down").html("提交").click(function(){subm()});
		}
		else{
			$("#btn_down").html("下一题").click(function(){next('next')});
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
				an+="|"+idx+"_"+ii+";";	
			}
		});
	});
	return an;
}

$("#test_main dd a").live("click",function(){
	$(this).parent().parent().find("a").removeClass("sel");
	this.className="sel";
});