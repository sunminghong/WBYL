var cVersionId=1;
var cookie={
	get:function(name)
	{
		var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
    	if(arr != null) 
    	{return unescape(arr[2]);}
    	else
    	{return null;}
    },
    set:function(name,value)
    {
        var Days = 30; 
    	var exp  = new Date();
    	exp.setTime(exp.getTime() + Days*24*60*60*1000);
    	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
    }
};

function closeUpdateTip()
{
	if($("#noshowAgain").attr("checked"))
	{
		try{cookie.set("noshowAgainVersion",$iweibo_latest_version["id"]);}
		catch(e){}
	}
	$("#updateVersion").fadeOut("slow");
}

function resetWinSize()
{
	var D=document.documentElement||document.body;
	var Dw=D.clientWidth;
	var Dh=D.clientHeight;
	$(".mainA").css({"min-height":Dh-130,"_ height":Dh-130});	
	$(".rms").css({"width":Dw-200});
}

window.onresize=function(){resetWinSize();}

$(function(){
	resetWinSize();
	$.ajax({
		type:"GET",
		url : "http://open.t.qq.com/apps/iweibo/iweibo.manifest",
		cache:false,
		dataType:"script",
		success:function(){
			
			var D=document.documentElement||document.body;
			var Dw=D.clientWidth;
			var Dh=D.clientHeight;
			$(".mainA").css({"min-height":Dh-130,"_ height":Dh-130});
			try
			{var nVersion = $iweibo_latest_version;
			if (nVersion!=undefined&&nVersion["id"]>cVersionId&&nVersion["id"]!=cookie.get("noshowAgainVersion"))
			{
			var str="<h2>iWeibo\u5347\u7ea7\u63d0\u793a</h2><p><label>\u65b0\u7248\u672c "+nVersion["name"]+"</label> <a href=\""+nVersion["href"]+"\" target=\"_blank\">\u67e5\u770b</a></p><div><input type=\"Checkbox\" id=\"noshowAgain\"> <label for=\"noshowAgain\">\u6b64\u7248\u672c\u4e0d\u518d\u63d0\u793a</label> <a href=\"javascript:void(0);\" onclick=\"closeUpdateTip();\">\u5173\u95ed</a></div>"
			var updateObj=$("<div class=\"updateVersion\" id=\"updateVersion\"></div>").appendTo($(".mainA"));
			updateObj.fadeIn(1000).html(str);
			}}catch(e){}
		},
		error:function($1,$2,$3){
		}
	});
	
});
