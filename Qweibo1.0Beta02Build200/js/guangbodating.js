/******************************************************************************
 * Author: michal
 * Filename: guangbodating.js
 * Description: 广播大厅
******************************************************************************/
/*
//可变高度
wbfuncs.fn.updateUT = function( uthtml ){
	var animateTime = 1 * 1000,
		nextUT = $(uthtml),
		nextUTHeight,
		nowUT = $(".umain"),
		hasImageViewer = nowUT.find(".imageviewer").length > 0 && nowUT.find(".imageviewer").css("display") != "none" ,
		hasSendbox = nowUT.find(".minisendbox").length > 0,
		inOperation = hasImageViewer || hasSendbox,//用户正在操作
		utMain = $("#utbodywrapper"),
		maskLayer = $(".utmask");

		if( inOperation ){return;}
		
		nowUT.css("z-index",1);
		maskLayer.css("z-index",2);
		nextUT.css("z-index",3);
		
		wbfuncs.fn.updateEvents(nextUT);
		wbfuncs.fn.updateFlowEvents(nextUT);
		nextUT.hide().css({ position:"relative","margin-top":"-"+nowUT.height()+"px",left:"0px"}).appendTo(utMain);
		
		maskLayer.css({height:nowUT.height()+"px"}).fadeIn(animateTime,function(){
			nowUT.remove();
			maskLayer.hide();
		});
		
		nextUT.fadeIn(animateTime,function(){
			nextUT.css("margin-top","0px");
		});
};
*/
//固定高度
wbfuncs.fn.updateUT = function( uthtml ){
	var animateTime = 1 * 1000,
		nextUT = $(uthtml),
		nextUTHeight,
		nowUT = $(".umain"),
		hasImageViewer = nowUT.find(".imageviewer").length > 0 && nowUT.find(".imageviewer").css("display") != "none" ,
		hasSendbox = nowUT.find(".minisendbox").length > 0,
		inOperation = hasImageViewer || hasSendbox,
		utMain = $("#utbodywrapper"),
		maskLayer = $(".utmask");
		
		if( inOperation ){return;}
		
		nowUT.css("z-index",1);
		maskLayer.css("z-index",2);
		nextUT.css("z-index",3);
		
		wbfuncs.fn.updateEvents(nextUT);
		wbfuncs.fn.updateFlowEvents(nextUT);
		nextUT.hide().css({position:"absolute",top:"0px",left:"0px"}).appendTo(utMain);
		
		maskLayer.fadeIn(animateTime,function(){
			nowUT.addClass("notvisible");
			var els = $(".umain.notvisible");//清理无用节点
			if(els.length > 1){
				for( var i=els.length-1;i>0;i-- ){
					els[i].parentNode.removeChild(els[i]);
				}
			}
			maskLayer.hide();
		});
		
		nextUT.fadeIn(animateTime);
};
window.onload = function(){
	if( $(".umain").length <= 0 ){return;}
	//图片也加载完毕	
		var tuijian = $("#jintituijian").text();
		if( tuijian && tuijian.length > 0 ){
			setInterval(function(){
				$.ajax({
					url:"./index.php?m=a_recommut",
					data:"format=html&k="+encodeURIComponent(tuijian),
					dataType:"json",
					success:function( result ){
						if( result.code == 0 ){
							wbfuncs.fn.updateUT( result.data );
						}else{
						}
					},
					error:function(){
					}
				});
			},10*1000);
		};
};