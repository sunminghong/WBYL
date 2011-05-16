/******************************************************************************
 * Author: michal
 * Filename: favTopic.js
 * Description: 收藏的话题
******************************************************************************/
$(function(){
	//折叠与反折叠订阅话题
	$("#toggledingyue").click(function(){
		var huatilist = $(".dingyue");
		huatilist.toggle();
		$("#viewmorefavtopicwrapper").toggle();//查看更多||收起按钮
		var arrow = $(this).find("a:last-child");
		arrow.get(0).className = "icon "+( huatilist.css("display")=="none"?"down":"up");
		if(arrow.hasClass("down")){
			arrow.attr("title","展开");
		}else{
			arrow.attr("title","收起");
		}
	});
	//订阅的话题事件
	var favTopic = {};
	favTopic.updateEvents = function( obj ){
		
		obj = obj || $("#dingyue");
		//隐藏、显示删除按钮
		obj.find("li").mouseover(function(){
			$(this).find(".huatidel").show();
		}).mouseout(function(){
			$(this).find(".huatidel").hide();
		});
		//删除按钮动作
		obj.find(".huatidel").click(function(){
				var _this = $(this),
				id = _this.attr("id");
			$.ajax({
				type:"GET",
				url : "index.php?m=a_delfavtopic",
				dataType:"json",
				data: "tid="+id,
				success : function(data){
					var result = data;
					if(result.code == 0){
						_this.parent().fadeOut(300);
					}else{
						alert("取消订阅失败");
					}
				},
				error : function(reqst, status, error){
					alert("由于服务器错误，取消订阅失败");
				}
			});
		});
	};
	favTopic.updateEvents();
	//查看更多订阅
	$("#viewmorefavtopic").click(function(){
		var _this = $(this),
			lastht = $(".dingyue").last().find("li").last(),
			ltime = lastht.attr("data-timestamp"),
			lid = lastht.attr("data-id");
		
		//加载中动画
		_this.addClass("showloading");
		
		$.ajax({
			type:"GET",
			url:"index.php?m=a_favtopicmore",
			dataType:"json",
			data:"lasttime="+ltime+"&lid="+lid,
			success:function( data ){
				var html = $(data.data);
				favTopic.updateEvents(html);
				$(".dingyue").last().after(html);
				_this.removeClass("showloading");
				var hasnext = html.attr("data-hasnext") === "1";
				if(!hasnext){
					_this.hide();
					$("#collapsefavtopic").show();
				}
			},
			error : function(reqst, status, error){
				alert("由于服务器错误，读取订阅列表失败");
				_this.removeClass("showloading");
			}
		});
	});
	//收起更多订阅
	$("#collapsefavtopic").click(function(){
		var dingyuelist = $(".dingyue");
		for(var i=dingyuelist.length;i>1;i--){
			dingyuelist.get(i-1).parentNode.removeChild(dingyuelist.get(i-1));
		}
		$(this).hide();//出现了收起按钮，一定有查看更多按钮
		$("#viewmorefavtopic").show();
	});
});