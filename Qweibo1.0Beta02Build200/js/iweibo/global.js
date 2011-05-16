$(function (){
	//搜索
	var searchKey = $(".searchKey");
	
	if( searchKey.length > 0 ){
			
		searchKey.blur();//浏览器后退按钮有时候会激活此输入框document.activeElement == searchKey.get(0)用于重置输入框状态
		
		if( $.trim(searchKey.val()).length > 0 /*|| document.activeElement == searchKey.get(0)*/){
			searchKey.css( 'background-position', '0 -25px' );
		}
		
		searchKey.focusin(function(){
			var emptyInput = $.trim($(this).val()).length <= 0;
			if(emptyInput){
				$( this ).css( 'background-position', '0 -25px' );//无文字的背景
			}
		}).focusout(function(){
			var emptyInput = $.trim($(this).val()).length <= 0;
			if(emptyInput){
				$( this ).css( 'background-position', '0 0px' );//有文字的背景
			}
		});
		
		$("#searchBtn").click(function(){
			if( $.trim($(".searchKey").val()).length </*=*/ 0 ){
				$(".searchKey").focus();
				$(".searchKey").trigger("focusin");//当前版本jQuery需要手动触发
				return false;
			}else{
				$("#searchForm").submit();
				return true;
			}
		});
	}
	
	//热门话题
	$("#togglehuati").click(function(){
		var huatilist = $("#huati");
		huatilist.toggle();
		var arrow = $(this).find("a:last-child");
		arrow.get(0).className = "icon "+( huatilist.css("display")=="none"?"down":"up");
		if(arrow.hasClass("down")){
			arrow.attr("title","展开");
		}else{
			arrow.attr("title","收起");
		}
	});
});
