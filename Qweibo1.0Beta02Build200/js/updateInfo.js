/******************************************************************************
 * Author: michal
 * Filename: updateInfo.js
 * Description: 更新听众数等
******************************************************************************/
if(!window.wbfuncs){
	wbfuncs = function(){};
}
if(!wbfuncs.fn){
	wbfuncs.fn = function(){};
}
wbfuncs.fn.updateInfo = {
		update : function( ){
			$.ajax({
				url:"./index.php?m=a_newmsginfo",
				dataType:"json",
				success:function( data ){
					if(data.code == 0){
						var response = data.data,
							new_home = response["home"],
							new_private = response["private"],
							new_fans = response["fans"],
							new_mentions = response["mentions"];
						if(new_home != 0){
						//新首页广播数
							var newmessage = $("#newmessage");
							if( window.pagename && window.pagename == "zhuye" && newmessage.length > 0 ){
								$("#newmessagecount").text(new_home);
								if( !newmessage.is(":visible") ){
									newmessage.fadeIn(500);
								}	
							}
						}
						if(new_private != 0){
						//新私信数
							var newmailcount = $("#newmailcount");
							newmailcount.text(new_private);
							if( !newmailcount.parent().is(":visible") ){
								newmailcount.parent().show();
							}
						}
						if(new_fans != 0){
						//新听众数
							$("#newfanscount").text(new_fans);
							var newfans = $("#newfans");
							if(!newfans.is(":visible")){
								newfans.show();
							}
						}
						if(new_mentions != 0){
						//新提到数
							var newmentioncount = $("#newmentioncount");
							newmentioncount.text(new_mentions);
							if( !newmentioncount.parent().is(":visible") ){
								newmentioncount.parent().show();
							}
						}
					}
				},
				error:function(){
					
				}
			});
		}
};
//查看更多新消息
if($("#newmessage").length > 0){
		$("#newmessage").click(function(){
			var newmessages = $(this),
				newmessagecount = parseInt(newmessages.find("#newmessagecount").text(),10),
				newmessageloading = newmessages.find("#newmessageloading");
			newmessageloading.show();
			$.ajax({
				url:wbfuncs.config.urlconfig.tbody+"&num="+newmessagecount,
				dataType:"json",
				success:function( data ){
					newmessages.fadeOut(500,function(){
						newmessageloading.hide();
						$(".needremove").remove();
						var resultData = $(data.data),
							resultDataElements = resultData.find("li");
							resultDataElements2 = resultData.find(".tyinyong");
						resultDataElements.css("background","#fff4d8");//默认背景
						resultDataElements2.css("background","#fff4d8");//引用的帖子
						$("ul.tmain").first().before(resultData);
						setTimeout(function(){
							resultDataElements.animate({ backgroundColor: "white" }, 1000);
							resultDataElements2.animate({ backgroundColor: "#f8f8f8" }, 1000);
							wbfuncs.fn.updateEvents(resultData);
							wbfuncs.fn.updateTime.update(data.timestamp);
						},500);
					});
					//提到我的页面新消息计数重置
					if(window.pagename && window.pagename == "tidao"){
						$("#newmentioncount").parent().hide();
					}
				},
				error:function(){
					newmessageloading.hide();
				}
			})
		});
}
//setTimeout(wbfuncs.fn.updateInfo.update,0);
setInterval(wbfuncs.fn.updateInfo.update,1000*60);