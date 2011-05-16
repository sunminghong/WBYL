/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-15 12:06
 * Filename: wbfuncs.js
 * Description: 
******************************************************************************/
//该字段由模板提供
//pagename = "";
var wbfuncs = function(){};
//配置信息命名空间
wbfuncs.config = {};
//
wbfuncs.config.urlconfig = {};
wbfuncs.config.autoloadcount = 0;//
wbfuncs.config.maxautoload = 3;//最多自动滚三屏的数据
wbfuncs.config.debug = false;//是否调试模式,alert输出更多信息

//jQ旋转图片插件
jQuery.fn.rotate = function(angle,absolute/* 绝对角度 */){
	var image = this.get(0),//原始图片
		supportCanvas = "getContext" in document.createElement("canvas"),//浏览器是否支持canvas标签
		boundaryMaxWidth = this.parent().parent().innerWidth()-8;//imageviewer的宽度-padding
	//保存图片原始宽高信息
	if(!image.naturalWidth){
		image.naturalWidth = image.width;
	}
	if(!image.naturalHeight){
		image.naturalHeight = image.height;
	}
	//旋转角度计算
	if(absolute){
		image.angle = angle;
	}else{
		if(!image.angle){
			image.angle = 0;
		}
		image.angle = image.angle + angle;
	}
	//当前旋转
	var rotation;
	if( image.angle >= 0){
		rotation = Math.PI * image.angle / 180;
	}else{
		rotation = Math.PI * ( 360 + image.angle ) / 180;
	}
	//盒子旋转后所占用的空间计算
	var getBoundary = function( rw,rh,radians ){
        var x1 = -rw/2,
            x2 = rw/2,
            x3 = rw/2,
            x4 = -rw/2,
            y1 = rh/2,
            y2 = rh/2,
            y3 = -rh/2,
            y4 = -rh/2;
            
        var x11 = x1 * Math.cos(radians) + y1 * Math.sin(radians),
            y11 = -x1 * Math.sin(radians) + y1 * Math.cos(radians),
            x21 = x2 * Math.cos(radians) + y2 * Math.sin(radians),
            y21 = -x2 * Math.sin(radians) + y2 * Math.cos(radians), 
            x31 = x3 * Math.cos(radians) + y3 * Math.sin(radians),
            y31 = -x3 * Math.sin(radians) + y3 * Math.cos(radians),
            x41 = x4 * Math.cos(radians) + y4 * Math.sin(radians),
            y41 = -x4 * Math.sin(radians) + y4 * Math.cos(radians);
        
        var x_min = Math.min(x11,x21,x31,x41),
            x_max = Math.max(x11,x21,x31,x41);
        
        var y_min = Math.min(y11,y21,y31,y41);
            y_max = Math.max(y11,y21,y31,y41);
        
        return [x_max-x_min,y_max-y_min];
	};
	var boundary = getBoundary(image.naturalWidth,image.naturalHeight,rotation);
	//最大宽度校验
	var boundaryScale = 1;
	if( boundary[0] > boundaryMaxWidth ){
		boundaryScale =  boundaryMaxWidth / boundary[0];
	}
	boundary[0] *= boundaryScale;
	boundary[1] *= boundaryScale;
	//旋转参数
	var cosrotation = Math.cos(rotation),
		sinrotation = Math.sin(rotation);
	//canvas way
	var paintImage = function( canvas  ){
		image.parentNode.style.width = boundary[0]+"px";
		image.parentNode.style.height = boundary[1]+"px";
		canvas.width = boundary[0];
		canvas.height = boundary[1];

		var ctx = canvas.getContext("2d");
		ctx.clearRect(0,0,boundary[0],boundary[1]);
		ctx.save();
		ctx.translate(boundary[0]/2,boundary[1]/2);
		ctx.rotate(rotation);
		ctx.drawImage(image,-image.naturalWidth*boundaryScale/2,-image.naturalHeight*boundaryScale/2,image.naturalWidth*boundaryScale,image.naturalHeight*boundaryScale);
		ctx.restore();
	};
	if(image.canvas){
		paintImage(image.canvas);/* 绝对角度 */
	}else if(!image.canvas && supportCanvas){
		image.canvas = document.createElement("canvas");
		this.before(image.canvas);
		paintImage(image.canvas);
		this.hide();
	}else{//不支持canvas的浏览器  如IE
		image.parentNode.style.width = boundary[0]+"px";
		image.parentNode.style.height = boundary[1]+"px";
		image.style.filter = "progid:DXImageTransform.Microsoft.Matrix(M11="+cosrotation*boundaryScale+",M12="+(-sinrotation)*boundaryScale+",M21="+sinrotation*boundaryScale+",M22="+cosrotation*boundaryScale+",SizingMethod='auto expand')";
	}
};
//效果命名空间
wbfuncs.fn = {};
//主页时间线URL配置
if( window.pagename && window.pagename=="zhuye" ){
	wbfuncs.config.urlconfig.tbody = "./index.php?m=a_indexmore";
}else if(window.pagename && window.pagename=="guangbo"){
	wbfuncs.config.urlconfig.tbody = "./index.php?m=a_minemore";
}else if(window.pagename && window.pagename=="tidao"){
	wbfuncs.config.urlconfig.tbody = "./index.php?m=a_atmore";
}else if(window.pagename && window.pagename=="shoucang"){
	wbfuncs.config.urlconfig.tbody = "./index.php?m=a_favmore";
}else if(window.pagename && window.pagename=="tarenguangbo"){
	wbfuncs.config.urlconfig.tbody = "./index.php?m=a_guestmore"+(window.screenusername?("&u="+screenusername):"");
}
//拉取帖子列表的url
wbfuncs.config.urlconfig.trelist = "./index.php?m=a_relist";
//收藏，取消收藏
wbfuncs.config.urlconfig.taddfav = "./index.php?m=a_addfavt";
wbfuncs.config.urlconfig.tdelfav = "./index.php?m=a_delfavt";
//删除帖子
wbfuncs.config.urlconfig.tdel = "./index.php?m=a_delt";
//单条微博地址
wbfuncs.config.urlconfig.showt = "./index.php?m=showt";
//添加帖子
// 1 发表 2 转播 3 回复 4 点评
// index.php?m=a_addt&type=1&content=aaa&reid=1234567&format=html
wbfuncs.config.urlconfig.addt = "./index.php?m=a_addt";
//转播后立刻显示帖子的页面白名单
wbfuncs.config.pagenameAllowToUpdate = {"zhuye":"","guangbo":""};
//禁止自动切换颜色的页面
wbfuncs.config.pagenameDisableColorSwitch = {"dantiaoweibo":"","duihua":""};
//迷你输入框
wbfuncs.fn.minisendbox = function(){};

//错误处理
wbfuncs.fn.handleError = function( errobj ){
	var undefine;
	var doBlinkCycle = 0;//闪烁计数器
	var doBlink = function( jObj,duration,callback ){
		duration = duration || 500;
		if( doBlinkCycle >  doBlink.maxCycle - 1 ){
			if(callback){
				callback();
			}
			return;
		}
		jObj.animate({
			opacity:0
		},duration,function(){
			jObj.animate({
				opacity:1
			},duration,function(){
				doBlinkCycle++;
				doBlink(jObj,duration,callback);
			});
		});
	};
	doBlink.maxCycle = 2;

	if( errobj ){
		//默认值
		if( errobj.canHandle === undefine){
			errobj.canHandle = true;
		}
		if( errobj.theme === undefine){
			errobj.theme = "system";
		}
		if( errobj.type === undefine){
			errobj.type = "error";
		}
		if( errobj.showcode === undefine){
			errobj.showcode = wbfuncs.config.debug;
		}
		if( errobj.error === undefine){
			errobj.error = {
					code : -1,
					text : "未知错误"
			};
		}
		
		if( errobj.canHandle ){
			
			switch(errobj.theme){
				case "system":
					var suff="";
					if(errobj.showcode){
						suff = "\ncode:"+errobj.error.code
					}
					alert( errobj.error.text + suff );
					break;
				case "blink":
					if( errobj.blinkObj ){
						var savedHTML = errobj.blinkObj.html();//保存之前的文字
						errobj.blinkObj.html( "<span style=\"color:#E56C0A\">" + errobj.error.text + "</span>");
						doBlink(errobj.blinkObj,300,function(){
							setTimeout(function(){
								errobj.blinkObj.html( savedHTML );
							},600);
							
						});
					}
					break;
				case "layer":
					//TODO弹出层错误
					break;
				default:
					break;
			}
		}
	}
};

//确认框
wbfuncs.fn.confirm = function(msg,x,y,okclick,cacelclick){
	msg = msg || "确定删除这条广播?";//信息提示
	x = x || 500;//X坐标
	y = y || 500;//Y坐标
	var html = '<div class="delChose" id="confirmbox"><div class=\"tiptext\">'+msg+'</div><input type="button" value="确定" class="ok" id="ok">&nbsp;&nbsp;<input type="button" value="取消" class="cancel" id="cancel"></div>';
	var htmlobj = $(html);
	var animationtime = 0;
	if(!$.browser.msie){
		animationtime = 300;
	}
	htmlobj.css({ 'left':x, 'top':y, 'display':'block' });
	htmlobj.find("#ok").bind('click',function(){
		if(okclick){okclick();}
		htmlobj.fadeOut(animationtime,function(){
			htmlobj.remove();
		});
	});
	htmlobj.find("#cancel").bind('click',function(){
		if(cacelclick){cacelclick();}
		htmlobj.fadeOut(animationtime,function(){
			htmlobj.remove();
		});
	});
	htmlobj.hide().appendTo("body").fadeIn(animationtime,function(){
		if(!$.browser.msie){
			htmlobj.find("#ok").focus();
		}else{
			setTimeout(function(){
				htmlobj.find("#ok").focus();
			},0);
		}
	});
};
wbfuncs.fn.minisendbox.create = function( id,html ){
	var box = document.createElement("div");
		box.id = id;
		box.className = "minisendbox";
		box.innerHTML = html;
	return box;
};
//
wbfuncs.fn.parseTime = function (nowtime,timestamp) {
	var diff = nowtime - timestamp;
	if(diff < 60){
		return "刚刚";
	}else if(diff < 3600){
		return Math.floor(diff/60)+"分钟前";
	}
	var nowtimeDate = new Date(nowtime*1000),
		previousDayDate = new Date( (nowtime - 24 * 3600) * 1000 ),
		timestampDate = new Date(timestamp*1000),
		timestampYear = timestampDate.getFullYear(),
		timestampMonth = timestampDate.getMonth(),
		timestampDay = timestampDate.getDate(),
		timestampHours = timestampDate.getHours()<10?"0"+timestampDate.getHours():timestampDate.getHours(),
		timestampMinutes = timestampDate.getMinutes()<10?"0"+timestampDate.getMinutes():timestampDate.getMinutes();

	if(nowtimeDate.getFullYear() == timestampYear){
		//同一年
		if(nowtimeDate.getMonth() == timestampMonth){
		//同一月
			if(nowtimeDate.getDate() == timestampDay){
			//同一天
				return "今天"+timestampHours+":"+timestampMinutes;
			}else if( previousDayDate.getDate() == timestampDay ){
			//昨天
				return "昨天"+timestampHours+":"+timestampMinutes;
			}else{
				return [timestampMonth+1,"月",timestampDay,"日"," ",timestampHours,":",timestampMinutes].join("");
			}
		}else{
			return [timestampMonth+1,"月",timestampDay,"日"," ",timestampHours,":",timestampMinutes].join("");
		}
	}else{
		return [timestampYear,"年",timestampMonth+1,"月",timestampDay,"日"," ",timestampHours,":",timestampMinutes].join("");
	}
};
wbfuncs.fn.updateFlowEvents = function( els,flowsuccess,unflowsuccess ){
	var actions;
	if( els ){
		actions = $(els).find(".flowactionsmall");
	}else{
		actions = $(".flowactionsmall");
	}
	actions.each(function(){
		var _this =$(this),
			eventslist = _this.data("events"),
			hasClickEvent = eventslist && eventslist["click"];
			if(!hasClickEvent){
				_this.click(function(){
					//type 1 执行收听 0 执行取消收听
					var type = _this.hasClass("shouting") ? 1:0,
						username = _this.attr("data-username");
					
					$.ajax({
						type:"POST",
						url : "./index.php?m=a_follow",
						data:"name="+username+"&type="+type,
						dataType : "json",
						success : function( result ){
							if(result.code == 0){
								if( type == 1 ){
								//收听成功
									_this.animate({'background-position': '-146px -536px'},function(){
										_this.removeClass( 'shouting' ).addClass( 'quxiao' );
										_this.attr("title","取消");
									});
									if(flowsuccess){
										flowsuccess(_this);
									}
								}else{
								//取消收听成功
									_this.animate({'background-position': '-146px -519px'},function(){
										_this.removeClass( 'quxiao' ).addClass( 'shouting' );
										_this.attr("title","收听");
									});
									if(unflowsuccess){
										unflowsuccess(_this);
									}
								}
							}else{
								wbfuncs.fn.handleError({
									error : {
										code : result.code,
										text : result.msg
									}
								});
							}
						},
						error : function(){
							wbfuncs.fn.handleError({
								error : {
									code : -3,
									text :"由于服务器错误"+(type==0?"取消收听":"收听"+"失败")
								}
							});
						}
					});//end ajax
				});//end click
			}// end if
	});//end each
}
wbfuncs.fn.minisendbox.parseData = function( data ){
	var str = "<ul class=\"tlist\">",
		infos = data.data.data.info,
		tempty = [],//空转播
		currentTime = parseInt(data.timestamp);
	for(var i=0,l=infos.length;i<l;i++){
		var info = infos[i];
		if( info.text.length > 0 ){
			str+="<li>";
			str+="<div class=\"tlistcontent\">"
			str=str+"<a class=\"nameinfo\" id=\""+info.name+"\" href=\"./index.php?u="+info.name+"\">"+info.nick+"</a>";
			if(info.isvip){
				str+="<span class=\"vip\"></span>";
			}
			str+=":&nbsp;&nbsp;";
			str=str+"<span class=\"ttext\">"+info.text+"</span>&nbsp;&nbsp;";
			str=str+"<span class=\"gray\">"+"<a class=\"gray\" href=\"./index.php?m=showt&tid="+info.id+"\">"+wbfuncs.fn.parseTime(currentTime,parseInt(info.timestamp))+"</a>&nbsp;来自 "+info.from+"</span>";
			str+="</div>"
			str+="<div class=\"tlistactions\">";
				str+="<a href=\"javascript:void(0);\" class=\"zhuanbobtn\">转播</a>";
			str+="</div>";
			str+="</li>";
		}else{
			tempty.push("<a href=\"./index.php?u="+info.name+"\">"+info.nick+"</a>");
		}
	}
	if(tempty.length > 0){
		if( tempty.length > 3 ){
			str= str + "<li>"+tempty.slice(0, 3).join("、")+"...进行了转播</li>"
		}else{
			str= str + "<li>"+tempty.join("、")+"进行了转播</li>"
		}
	}
	str+="</ul>";
	return str;
}
//
wbfuncs.fn.minisendbox.getHTML = function( id ){
	var tophtml="",
		bottomlefthtml="",
		linktext="",
		extrahtml = "";
	if( id == "zhuanbo" ){
		tophtml = "转播原文，转播内容会发送给你的听众<br/>顺便说两句:&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:;\" class=\"hide\" id=\"delreason\" title=\"你可以通过“删除之前的转播理由”来去掉前面人的转播理由\">［删除之前的转播理由］</a>";
		//bottomlefthtml = "<a href=\"#\" id=\"chakanall\" style=\"font-family:Simsun;\">查看所有转播>></a>";
		bottomlefthtml = "";
		linktext="转&nbsp;播";
	}else if( id == "dianping" ){
		tophtml = "点评原文，点评内容不会发送给你的听众";
		bottomlefthtml = "<label><input name=\"moreinfo\" id=\"replay\" class=\"checkbox\" type=\"checkbox\">同时转播</label>";
		linktext="点&nbsp;评";
	}else if( id == "duihua" ){
		tophtml = "对&nbsp;<b class=\"sendto\"></b>&nbsp;说:";
		bottomlefthtml = "";
		linktext="对&nbsp;话";
	}else if( id == "chakan" ){
		tophtml = "<div>&nbsp;</div>";
		extrahtml = "<div class=\"loading\"></div>";
		linktext="转&nbsp;播";;
	}
	var html = "<div class=\"minisendboxmain\">"+
				"<div class=\"arrow\"></div>"+
				"<a class=\"close\" href=\"javascript:wbfuncs.fn.removeExistDialog();\"></a>"+
				"<div class=\"tophtml\">"+
				tophtml+
				"</div>"+
				"<textarea cols=\"60\" rows=\"1\">"+
				"</textarea>"+
				"<div class=\"bottomhtml\">"+
					"<div class=\"bottomlefthtml\">"+
					bottomlefthtml+
					"</div><div class=\"errormessage\" id=\"errormessage\"></div>"+
					"<div class=\"bottomrighthtml\">"+
						"<a class=\"submitbtn\" href=\"javascript:;\">"+
						linktext+
						"</a>"+
						"<div class=\"textcount\" id=\"textcount\">"+
						"还能输入<span class=\"text\">140</span>字"+
						"</div>"+
					"</div>"+
				"</div>"+
				extrahtml+
			"</div>";
	return html;
};
//智能计算字数
wbfuncs.fn.smartLen = function( str ){
	str = str.replace(new RegExp("((news|telnet|nttp|file|http|ftp|https)://){1}(([-A-Za-z0-9]+(\\.[-A-Za-z0-9]+)*(\\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\\.[0-9]{1,3}){3}))(:[0-9]*)?(/[-A-Za-z0-9_\\$\\.\\+\\!\\*\\(\\),;:@&=\\?/~\\#\\%]*)*","gi"),'填充填充填充填充填充填');
	return Math.ceil(($.trim(str.replace(/[^\u0000-\u00ff]/g,"aa")).length)/2);
};
//移除已存在的对话框
wbfuncs.fn.removeExistDialog = function(){
	if(wbfuncs.fn.removeExistDialog.existDialogId){
		$("#"+wbfuncs.fn.removeExistDialog.existDialogId).find(".minisendbox").remove();
	}
};
//监控已存在的对话框中的textarea字数
wbfuncs.fn.observeTextInput = function(){
	if(wbfuncs.fn.removeExistDialog.existDialogId){
		var minisendbox = $("#"+wbfuncs.fn.removeExistDialog.existDialogId).find(".minisendbox"),
			textarea = minisendbox.find("textarea"),
		    textcount = minisendbox.find(".textcount");
		
		textarea.elastic();
		textarea.keyup(function( e ){
			var str = $(this).val(),
				strlen = wbfuncs.fn.smartLen(str),
				diff = 140 - strlen;
			if(diff<0){
				textcount.html("超出<span class=\"text\" style=\"color:#E56C0A;\">"+(strlen-140)+"</span>字");
			}else{
				textcount.html("还能输入<span class=\"text\">"+diff+"</span>字");
			}
		});
		textarea.trigger("keyup");
	}
};
wbfuncs.fn.createImageView = function( image ){
	var imageNaturalWidth = image.naturalWidth || image.width,
		imageNaturalHeight = image.naturalHeight || image.height,
		imageBaseURL = image.src.substring(0,image.src.lastIndexOf("/")),
		imageBaseURL = imageBaseURL.substring(0,imageBaseURL.lastIndexOf("/"));
	
	var imageViewer = "<div class=\"imageviewer\">"+
							"<div class=\"toolbar\">"+
								"<div class=\"toleft\">"+
									"<a class=\"usebtns rotateleft\" href=\"javascript:;\">向左转</a>"+
									"<span class=\"deepgray sp\">|</span>"+
									"<a class=\"usebtns rotateright\" href=\"javascript:;\">向右转</a>"+
								"</div>"+
								"<div class=\"toright\">"+
									"<a target=\"_blank\" class=\"usebtns vieworiginal\" href=\""+imageBaseURL+"/2000\">查看原图</a>"+
								"</div>"+
							"</div>"+
							"<div class=\"imagecontainer\" style=\"width:"+imageNaturalWidth+"px;height:"+imageNaturalHeight+"px;margin:0 auto;\">"+
							"<img class=\"rotateableimg\" src=\""+image.src+"\" />"+
							"</div>"+
					  "</div>";
	var imageViewerObj = $(imageViewer);
	imageViewerObj.find(".imagecontainer").click(function(){
		imageViewerObj.parent().find(".ttupian").show();
		imageViewerObj.hide();
		if(updateGototopBtn){
			updateGototopBtn();//返回顶部按钮在最底部时应触发scroll事件
		}
	});
	imageViewerObj.find(".rotateleft").click(function(){
		imageViewerObj.find(".rotateableimg").rotate(-90);
	});
	imageViewerObj.find(".rotateright").click(function(){
		imageViewerObj.find(".rotateableimg").rotate(90);
	});
	return imageViewerObj;
};
//更新
wbfuncs.fn.updateEvents = function( els ){
	var switchcolor,tactions,ttupians;
	if( els ){
		switchcolor = $(els).find("li.tmessage");
		tactions = $(els).find("a.taction");
		ttupians = $(els).find("div.ttupian > img");
	}else{
		switchcolor = $("ul.tmain > li.tmessage");
		tactions = $("a.taction");
		ttupians = $("div.ttupian > img");
	}
	ttupians.each(function(){//图片放大与旋转
		var _this = $(this),
			eventslist = _this.data("events"),
			hasClickEvent = eventslist && eventslist["click"];
		if(!hasClickEvent){
			var imageDimension = {width:_this.width(),height:_this.height()};
			_this.bind("load",function(){//非缓存图片
				imageDimension.width = _this.width();
				imageDimension.height = _this.height();
				_this.parent().find(".loading").css({width:imageDimension.width+"px",height:imageDimension.height+"px"});
			});
			_this.parent().find(".loading").css({width:imageDimension.width+"px",height:imageDimension.height+"px"});
			_this.click(function(){
				var imageViewer = _this.parent().parent().find(".imageviewer");
				if(	imageViewer.length > 0 ){//have image viewer
					_this.parent().hide();
					imageViewer.show();
					if(updateGototopBtn){//返回顶部按钮在最底部时应触发scroll事件
						updateGototopBtn();
					}
					return;
				}else{
					_this.parent().find(".loading").show();
					var newimage = new Image();
					newimage.onload = function(){
						imageViewer = wbfuncs.fn.createImageView(newimage);
						_this.parent().after(imageViewer);
						_this.parent().hide();
						_this.parent().find(".loading").hide();
						if(window.updateGototopBtn){//返回顶部按钮在最底部时应触发scroll事件
							updateGototopBtn();
						}
					};
					newimage.onerror = function(d){
						_this.parent().find(".loading").hide();
						wbfuncs.fn.handleError({
							error : {
								code : -5,
								text : "图片加载失败，请检查网络连接后重试"
							}
						});
					};
					newimage.src = _this.attr("src")+"/460";
				}
			});
		}
	});
	//消息列表颜色切换
	switchcolor.each(function(){
		if( window.pagename && (window.pagename in wbfuncs.config.pagenameDisableColorSwitch) ){
			return;
		}
		var eventslist = $(this).data("events"),
			hasHoverEvent = eventslist && eventslist["hover"];
		if(!hasHoverEvent){
			$(this).hover(function(){
				$(this).css("background-color","#f5f5f5");
				//$(this).animate({ backgroundColor: "#f5f5f5" }, 200);
			},function(){
				$(this).css("background-color","#ffffff");
				//$(this).animate({ backgroundColor: "#ffffff" }, 200);
			});
		}
	});
	//对话框
	tactions.each(function(){
		var eventslist = $(this).data("events"),
			hasClickEvent = eventslist && eventslist["click"];
		if(!hasClickEvent){
			$(this).click(function(){
				var _this = $(this),
					buttonid = _this.attr("id"),//按钮id
					tbody = _this.parent().parent().parent(),//帖子内容
					sendbox = tbody.find(".minisendbox"),//试图寻找对话框
					hassendbox = sendbox.length > 0,
					tid = tbody.parent().attr("id"),//主贴ID
					hassource = tbody.find(".tyinyong").length > 0,//是否有转播贴
					reid = (hassource)?tbody.find(".tyinyong").attr("data-innerid"):tid;//引用贴id
					
				wbfuncs.fn.removeExistDialog();
				_this.blur();//
				if(buttonid == "zhuanbo"){//转播
					var showzhuanbodialog = function(){
						var textareaval="";
						if(hassource){//有转播内容,拷贝到输入框
							var text = tbody.find("#tbodytext").text(),
								name = tbody.find(".tname").attr("id");
							textareaval = "||@"+name+":"+text;
						}
						var minisendbox = $(wbfuncs.fn.minisendbox.create("zhuanbo",wbfuncs.fn.minisendbox.getHTML("zhuanbo")));
						tbody.append(minisendbox);
						//minisendbox.find("#chakanall").attr("href",wbfuncs.config.urlconfig.showt+"&tid="+tid);//查看所有广播的链接
						
						var textarea = minisendbox.find("textarea");
						textarea.val(textareaval);
						textarea.focus();
						textarea.selectRange(0,0);
						//
						wbfuncs.fn.removeExistDialog.existDialogId = tid;//记录当前有对话框的tbodyid
						wbfuncs.fn.observeTextInput();//对已激活的输入框进行字数检查
						//
						var delreason = minisendbox.find("#delreason"); 
						if(textareaval.length > 0){//有内容时出现删除转播理由按钮
							delreason.show();
						}
						
						delreason.click(function(){
							textarea.val("");
							textarea.focus();
							delreason.hide();//删除后隐藏自身
						});
						//
						var submitbtn = minisendbox.find(".submitbtn");
						submitbtn.click(function(){
							var textcount = minisendbox.find(".textcount"),
								strlen = wbfuncs.fn.smartLen(textarea.val());
							
							/*
							if(strlen<=0){
								textcount.hide().html("<span style=\"color:#E56C0A;display:inline-block;height:30px;padding-top:12px;-padding-top:15px;\">请输入内容</span>").fadeIn(200);
								return;
							}
							*/
							
							if(strlen <= 140 ){//字数符合要求,发送转播数据
								$.ajax({
									type:"POST",
									dataType : "json",
									data:"type=2&content="+textarea.val()+"&reid="+reid+"&format=html",
									url:wbfuncs.config.urlconfig.addt,
									success : function(data){
										var result = data;
										if(result.code == 0){
											minisendbox.find(".minisendboxmain").html("转播成功");
											setTimeout(function(){
												wbfuncs.fn.removeExistDialog();
												if(window.pagename && window.pagename in  wbfuncs.config.pagenameAllowToUpdate ){
													var resultdata = $(result.data);
													resultdata.addClass("needremove");
													resultdata.hide().fadeIn("slow");
													$(".tmain").first().before(resultdata);
													wbfuncs.fn.updateEvents(resultdata);
												}
											},500);
										}else{
											wbfuncs.fn.handleError({
												theme : "blink",
												blinkObj : minisendbox.find("#errormessage"),
												error : {
													code : result.code,
													text : result.msg
												}
											});
										}
									},
									error : function(reqst, status, error){
										wbfuncs.fn.handleError({
											error : {
												code : -3,
												text : "由于服务器错误，转播失败"
											}
										});
									}
								});
							}
						});
					};
					if(!hassendbox){//没有对话框
						showzhuanbodialog();
					}else if(sendbox.attr("id")=="zhuanbo"){//是自己的对话框则关闭之
						sendbox.remove();
					}else{//不是自己的对话框，移除之，启用自己的对话框
						sendbox.remove();
						showzhuanbodialog();
					}
				}else if(buttonid == "dianping"){
					var showdianpingdialog = function(){
						var minisendbox = $(wbfuncs.fn.minisendbox.create("dianping",wbfuncs.fn.minisendbox.getHTML("dianping")));
						tbody.append(minisendbox);
						var textarea = minisendbox.find("textarea");
						textarea.focus();
						
						wbfuncs.fn.removeExistDialog.existDialogId = tid;//记录当前有对话框的tbodyid
						wbfuncs.fn.observeTextInput();//对已激活的输入框进行字数检查
						
						var submitbtn = minisendbox.find(".submitbtn");
						submitbtn.click(function(){
								var textcount = minisendbox.find(".textcount"),
								strlen = wbfuncs.fn.smartLen(textarea.val());
								if(strlen<=0){
									textcount.hide().html("<span style=\"color:#E56C0A;display:inline-block;height:30px;padding-top:12px;-padding-top:15px;\">请输入内容</span>").fadeIn(200);
									return;
								}
								if(strlen <= 140 ){//字数符合要求,发送点评数据
									if(minisendbox.find("#replay[type=checkbox]").attr("checked")){//同时转播，只转播
										$.ajax({
											type:"POST",
											dataType:"json",
											url:wbfuncs.config.urlconfig.addt,
											data:"type=2&content="+textarea.val()+"&reid="+reid+"&format=html",
											success : function(data){
												var result = data;
												if(result.code == 0){
													minisendbox.find(".minisendboxmain").html("转播成功");
													setTimeout(function(){
														wbfuncs.fn.removeExistDialog();
														if(window.pagename && window.pagename in  wbfuncs.config.pagenameAllowToUpdate ){
															var resultdata = $(result.data);
															resultdata.addClass("needremove");
															resultdata.hide().prependTo($(".tmain").first()).fadeIn("slow");
															wbfuncs.fn.updateEvents(resultdata);
														}
													},500)
												}else{
													wbfuncs.fn.handleError({
														theme : "blink",
														blinkObj : minisendbox.find("#errormessage"),
														error : {
															code : result.code,
															text : result.msg
														}
													});
												}
											},
											error : function(reqst, status, error){
												wbfuncs.fn.handleError({
													error : {
														code : -3,
														text : "由于服务器错误，转播失败"
													}
												});
											}
										});
									}else{//不同时转播，只点评
										$.ajax({
											type:"POST",
											url : wbfuncs.config.urlconfig.addt,
											dataType : "json",
											data : "type=4&content="+textarea.val()+"&reid="+reid+"&format=html",
											success : function(data){
												var result = data;
												if(result.code == 0){
													minisendbox.find(".minisendboxmain").html("点评成功");
													setTimeout(function(){
														wbfuncs.fn.removeExistDialog();
														if(window.pagename && window.pagename in  wbfuncs.config.pagenameAllowToUpdate ){
															var resultdata = $(result.data);
															resultdata.addClass("needremove");
															resultdata.hide().prependTo($(".tmain").first()).fadeIn("slow");
															wbfuncs.fn.updateEvents(resultdata);
														}
													},500);
												}else{
													wbfuncs.fn.handleError({
														theme : "blink",
														blinkObj : minisendbox.find("#errormessage"),
														error : {
															code : result.code,
															text : result.msg
														}
													});
												}
											},
											error : function(reqst, status, error){
												wbfuncs.fn.handleError({
													error : {
														code : -3,
														text : "由于服务器错误，点评失败"
													}
												});
											}
										});
									}
								}
						});
					};
					if(!hassendbox){//没有对话框
						showdianpingdialog();
					}else if(sendbox.attr("id")=="dianping"){//是自己的对话框则关闭之
						sendbox.remove();
					}else{//不是自己的对话框，改变其内容
						sendbox.remove();
						showdianpingdialog();
					}
				}else if(buttonid == "duihua"){//对话即回复
					var showduihuadialog = function(){
						var minisendbox = $(wbfuncs.fn.minisendbox.create("duihua",wbfuncs.fn.minisendbox.getHTML("duihua")));
						tbody.append(minisendbox);
						minisendbox.find(".sendto").html(tbody.find(".tname").first().text());//查看所有广播的链接
						var textarea = minisendbox.find("textarea");
						textarea.focus();
						
						wbfuncs.fn.removeExistDialog.existDialogId = tid;//记录当前有对话框的tbodyid
						wbfuncs.fn.observeTextInput();//对已激活的输入框进行字数检查
						
						var submitbtn = minisendbox.find(".submitbtn");
						submitbtn.click(function(){
							var textcount = minisendbox.find(".textcount"),
								strlen = wbfuncs.fn.smartLen(textarea.val());
							if(strlen<=0){
								textcount.hide().html("<span style=\"color:#E56C0A;display:inline-block;height:30px;padding-top:12px;-padding-top:15px;\">请输入内容</span>").fadeIn(200);
								return;
							}
							if(strlen <= 140 ){//字数符合要求,发送转播数据
								$.ajax({
									type:"POST",
									url : wbfuncs.config.urlconfig.addt,
									dataType : "json",
									data : "type=3&content="+textarea.val()+"&reid="+tid+"&format=html",					
									success : function(data){
										var result = data;
										if(result.code == 0){
											minisendbox.find(".minisendboxmain").html("发表成功");
											setTimeout(function(){
												wbfuncs.fn.removeExistDialog();
												if(window.pagename && window.pagename in  wbfuncs.config.pagenameAllowToUpdate ){
													var resultdata = $(result.data);
													resultdata.addClass("needremove");
													resultdata.hide().prependTo($(".tmain").first()).fadeIn("slow");
													wbfuncs.fn.updateEvents(resultdata);
												}
											},500);
										}else{
											wbfuncs.fn.handleError({
												theme : "blink",
												blinkObj : minisendbox.find("#errormessage"),
												error : {
													code : result.code,
													text : result.msg
												}
											});
										}
									},
									error : function(reqst, status, error){
										wbfuncs.fn.handleError({
											error : {
												code : -3,
												text : "由于服务器错误，点评失败"
											}
										});
									}
								});
							}
						});
					};
					if(!hassendbox){//没有对话框
						showduihuadialog();
					}else if(sendbox.attr("id")=="duihua"){//是自己的对话框则关闭之
						sendbox.remove();
					}else{//不是自己的对话框，改变其内容
						sendbox.remove();
						showduihuadialog();
					}
				}else if(buttonid == "chakan"){//查看转播与点评
					var showchakandialog = function(){
						var textareaval="";
						if(hassource){//有转播内容,拷贝到输入框
							var text = tbody.find("#tbodytext").text(),
								name = tbody.find(".tname").attr("id");
							textareaval = "||@"+name+":"+text;
						}
						
						var minisendbox = $(wbfuncs.fn.minisendbox.create("chakan",wbfuncs.fn.minisendbox.getHTML("chakan")));
						tbody.append(minisendbox);
						
						var textarea = minisendbox.find("textarea");
						textarea.val(textareaval);
						textarea.focus();
						

						wbfuncs.fn.removeExistDialog.existDialogId = tid;//记录当前有对话框的tbodyid
						wbfuncs.fn.observeTextInput();//对已激活的输入框进行字数检查
						
						var submitbtn = minisendbox.find(".submitbtn");
						submitbtn.click(function(){
							var textcount = minisendbox.find(".textcount"),
								strlen = wbfuncs.fn.smartLen(textarea.val());
							/*
							if(strlen<=0){
								textcount.hide().html("<span style=\"color:#E56C0A;display:inline-block;height:30px;padding-top:12px;-padding-top:15px;\">请输入内容</span>").fadeIn(200);
								return;
							}
							*/
							if(strlen <= 140 ){//字数符合要求,发送转播数据
								$.ajax({
									type:"POST",
									url:wbfuncs.config.urlconfig.addt,
									dataType:"json",
									data : "type=2&content="+textarea.val()+"&reid="+reid+"&format=html",
									success : function(data){
										var result = data;
										if(result.code == 0){
											minisendbox.find(".minisendboxmain").html("转播成功");
											setTimeout(function(){
												wbfuncs.fn.removeExistDialog();
												if(window.pagename && window.pagename in  wbfuncs.config.pagenameAllowToUpdate ){
													var resultdata = $(result.data);
													resultdata.addClass("needremove");
													resultdata.hide().prependTo($(".tmain").first()).fadeIn("slow");
													wbfuncs.fn.updateEvents(resultdata);
												}
											},500);
										}else{
											wbfuncs.fn.handleError({
												theme : "blink",
												blinkObj : minisendbox.find("#errormessage"),
												error : {
													code : result.code,
													text : result.msg
												}
											});
										}
									},
									error : function(reqst, status, error){
										wbfuncs.fn.handleError({
											error : {
												code : -3,
												text : "由于服务器错误，转播失败"
											}
										});
									}
								});
							}
						});
						
						//拉取转播列表
						$.ajax({
							url : wbfuncs.config.urlconfig.trelist,
							dataType:"json",
							data : "tid="+reid,
							success : function( data ){
								var sendbox = tbody.find(".minisendbox"),
									result = data;
								
								if(result.code==0){
									sendbox.find(".loading").remove();
									_this.find("b").first().text(data.data.count);
									var data = $(wbfuncs.fn.minisendbox.parseData(result));
									data.find(".zhuanbobtn").each(function(){
										var _this = $(this);
										_this.click(function(){
											var name = _this.parent().parent().find(".nameinfo").first().attr("id"),
												text = _this.parent().parent().find(".ttext").first().text();
											textareaval = "||@"+name+": "+text;
											textarea.val(textareaval);
											textarea.selectRange(0,0);
											textarea.trigger("keyup");
										});
									});
									sendbox.find(".minisendboxmain").append(data);
									sendbox.find(".minisendboxmain").append("<a href=\""+wbfuncs.config.urlconfig.showt+"&tid="+reid+"\" style=\"line-height:30px;font-family:Simsun;\">查看全部转播和点评>></a>")
								}else{
									wbfuncs.fn.handleError({
										error : {
											code : -3,
											text : "读取转播列表失败"
										}
									});
								}
							},
							error : function(reqst, status, error){
								wbfuncs.fn.handleError({
									error : {
										code : -3,
										text : "由于服务器错误，读取转播列表失败"
									}
								});
							}
						});
					};
					if(!hassendbox){//没有对话框
						showchakandialog();
					}else if(sendbox.attr("id")=="chakan"){//是自己的对话框则关闭之
						sendbox.remove();
					}else{//不是自己的对话框，改变其内容
						sendbox.remove();
						showchakandialog();
					}
				}else if(buttonid == "shanchu"){//删除按钮
					var offset = _this.offset();
					wbfuncs.fn.confirm(null,offset.left-15,offset.top-35,function(){
						$.ajax({
							url : wbfuncs.config.urlconfig.tdel,
							dataType:"json",
							data : "tid="+tid,
							success : function(data){
								var result = data;
								if(result.code==0){
									//$("#"+tid).fadeOut(300);
									$(".tmessage").each(function(){
										var message = $(this);
										if(message.attr("id")==tid){
											message.fadeOut(300);
										}
									});
								}else{
									wbfuncs.fn.handleError({
										error : {
											code : result.code,
											text : result.msg
										}
									});
								}
							},
							error : function(reqst, status, error){
								wbfuncs.fn.handleError({
									error : {
										code : -3,
										text : "由于服务器错误，删除失败"
									}
								});
							}
						});
					});
				}else if(buttonid == "shoucang"){//收藏
					if( _this.hasClass("nohave") ){
						$.ajax({
							type:"get",
							url : wbfuncs.config.urlconfig.taddfav,
							dataType:"json",
							data : "tid="+tid,
							success : function(data){
								var result = data;
								if(result.code==0){
									_this.get(0).className = "taction shoucang have";
									_this.attr("title","取消收藏");
								}else{
									wbfuncs.fn.handleError({
										error : {
											code : result.code,
											text : result.msg
										}
									});
								}
							},
							error : function(reqst, status, error){
								wbfuncs.fn.handleError({
									error : {
										code : -3,
										text : "由于服务器错误，收藏失败"
									}
								});
							}
						});
					}else{
						var offset = _this.offset();
						wbfuncs.fn.confirm("确定取消收藏此微博?",offset.left-20,offset.top,function(){
							$.ajax({//取消收藏
								type: "get",
								url : wbfuncs.config.urlconfig.tdelfav,
								dataType:"json",
								data : "tid="+tid,
								success : function(data){
									var result = data;
									if(result.code==0){
										if(window.pagename&&pagename=="shoucang"){//收藏页面特殊处理
											$("#"+tid).fadeOut(300);
										}else{
											_this.get(0).className = "taction shoucang nohave";
											_this.attr("title","收藏");
										}
									}else{
										wbfuncs.fn.handleError({
											error : {
												code : result.code,
												text : result.msg
											}
										});
									}
								},
								error : function(reqst, status, error){
									wbfuncs.fn.handleError({
										error : {
											code : -3,
											text : "由于服务器错误，取消收藏失败"
										}
									});
								}
							});
						});
					}
				}//end shoucang
			});
		}
	});
};
//初始化
$(function(){
	var undefine;
	//是否有输入框
	wbfuncs.config.hasSendBox = $(".sendbox > form > .holder > textarea").get(0) !== undefine;
	wbfuncs.config.hasTBody = $("ul.tmain > li.tmessage").get(0) !== undefine;
	
	if(wbfuncs.config.hasSendBox){
		$(".sendbox > form > .holder > textarea").focusin(function(){
			$(this).parent().find(".holderhighlight").show();
			//$(".sendbox > form > .holder > .holderhighlight").show();
			//$(".sendbox > form > .holder > .holderhighlight").fadeIn(200);
		}).focusout(function(){
			$(this).parent().find(".holderhighlight").hide();
			//$(".sendbox > form > .holder > .holderhighlight").hide();
			//$(".sendbox > form > .holder > .holderhighlight").fadeOut(200);
		});
	}
	
	$(".nextscreen").find("#more").click(function(){
		if(!wbfuncs.config.urlconfig.tbody){return;}//拉取DOM的接口
		var lastmessage = $("ul.tmain").last().find("li.tmessage").last();
		var lastmessagetime = lastmessage.find(".time").last().attr("data-favtime") || lastmessage.find(".time").last().attr("id");//收藏页以最后一条帖子收藏时间为准，因为收藏页以收藏时间排序
		var lastmessageid = lastmessage.attr("id");
		var loading = $(".nextscreen > .loading");
		loading.show();
		$.ajax({
			url:wbfuncs.config.urlconfig.tbody,
			data:"f=1&t="+lastmessagetime+"&lid="+lastmessageid,
			dataType:"json",
			success : function( result ){
				if(result.code == 0){
					var resultData = $(result.data);
					var hasnext = resultData.attr("id") === "0";
					setTimeout(function(){
						loading.hide();
						$("ul.tmain").last().after(resultData);//IE下会触发scroll事件,导致更多按钮重复拉取数据，已做时间限制
						wbfuncs.fn.updateEvents(resultData);
						wbfuncs.config.autoloadcount++;
						if(!hasnext){//没有下一屏了
							$(".nextscreen").remove();
						}
						//复位返回顶部按钮
						var gotoWrapper = document.getElementById("gotopwrapper");
						if( !(!'0'[0] && !window.XMLHttpRequest) ){//非ie6等支持fixed的浏览器
							gotoWrapper.style.bottom = "0px";
						}else{//ie6不支持fixed
							gotoWrapper.style.top = document.documentElement.clientHeight + document.documentElement.scrollTop - gotoWrapper.clientHeight + "px";
						}
					},500);
				}else{
					loading.hide();
					wbfuncs.fn.handleError({
						error : {
							code : result.code,
							text : result.msg
						}
					});
				}
			},
			error : function(reqst, status, error){
				loading.hide();
				wbfuncs.fn.handleError({
					error : {
						code : -3,
						text : "由于服务器错误，读取失败"
					}
				});
			}
		});
	});
	if(wbfuncs.config.hasTBody){
		wbfuncs.fn.updateEvents();
		wbfuncs.fn.scrollToEnd = function(){
			if(wbfuncs.config.autoloadcount < wbfuncs.config.maxautoload){
				$(".nextscreen").find("#more").trigger("click");
			}
		};
	}
	
	//滚动条到底部
	var onscroll = function(e){
	    var windowHeight = document.body.offsetHeight,
	        currentScroll = document.documentElement.scrollTop || document.body.scrollTop,
	        visableHeight = document.documentElement.clientHeight;
	    if( (visableHeight + currentScroll) >= windowHeight ){
	    	//有消息体的页面滚动到底部
	    	if(wbfuncs.fn.scrollToEnd){
	    		if( !wbfuncs.fn.scrollToEnd.lastCallTime || ( new Date().getTime() - wbfuncs.fn.scrollToEnd.lastCallTime ) > 500/*两次接口调用的时间差至少500毫秒*/ ){
	    			wbfuncs.fn.scrollToEnd();
	    			wbfuncs.fn.scrollToEnd.lastCallTime = new Date().getTime();//上次调用接口的时间戳
	    		}
	    	}
	    }
	};

	if( window.attachEvent ){
		window.attachEvent("onscroll",onscroll,false);
	}else{
		window.addEventListener('scroll',onscroll,false);
	};

	//单条微博页主动展开转播框
	if(window.pagename && pagename =="dantiaoweibo" ){
		$(".tmain > li:first-child").find("a.taction#zhuanbo").trigger("click");
		$(".tmain > li:first-child").find(".ttouxiang").find("img").each(function(){
			if( $(this).attr("src") == "/style/images/default_head_small.png" ){
				$(this).attr("src","/style/images/default_head_120.jpg");
			};
		});
	}
	//广播大厅/搜索/推荐用户收听/取消收听
	wbfuncs.fn.updateFlowEvents(undefine,function( el ){
		var fanscount = el.parent().parent().find("#fanscount");
		if(fanscount.length>0){
			fanscount.html(Math.max(parseInt(fanscount.text(),10)+1,0));
		}
	},function( el ){
		var fanscount = el.parent().parent().find("#fanscount");
		if(fanscount.length>0){
			fanscount.html(Math.max(parseInt(fanscount.text(),10)-1,0));
		}
	});
	//话题页收听取消收听话题
	$(".htflowaction").click(function(){
		//type 1 执行收听 0 执行取消收听
		var _this = $(this),
			id = _this.attr("id"),
			shouting = _this.hasClass("shouting"),
			htfavcount = _this.parent().find("#htflowcount");
		
		if( id == "0" ){//话题ID为0 绝对收听失败
			wbfuncs.fn.handleError({
				error : {
					code : -3,
					text : "收听失败！：\n没有与此话题相关的广播"
				}
			});
			return;
		}
		
		$.ajax({
			type:"GET",
			url : "./index.php?m="+(shouting?"a_addfavtopic":"a_delfavtopic"),
			data:"tid="+id,
			dataType : "json",
			success : function( result ){
				if(result.code == 0){
					if( shouting ){
					//收听成功
						_this.animate({'background-position': '-59px -55px'},function(){
							_this.removeClass( 'shouting' ).addClass( 'quxiao' ).attr("title","取消收听");
						});
						htfavcount.html( parseInt(htfavcount.text(),10)+1 );
					}else{
					//取消收听成功
						_this.animate({'background-position': '-59px -32px'},function(){
							_this.removeClass( 'quxiao' ).addClass( 'shouting' ).attr("title","立即收听");
							htfavcount.html( Math.max(parseInt(htfavcount.text(),10)-1),0);
						});
						
					}
				}else{
					wbfuncs.fn.handleError({
						error : {
							code : result.code,
							text : result.msg
						}
					});
				}
			},
			error : function(){
				wbfuncs.fn.handleError({
					error : {
						code : -3,
						text :"由于服务器错误"+(type==0?"取消收听":"收听"+"失败")
					}
				});
			}
		});//end ajax
	});//end click
});