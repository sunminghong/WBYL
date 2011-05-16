/******************************************************************************
 * Author: michal
 * Filename: layer.js
 * Description: 弹出层
******************************************************************************/
//success warning info error
$.richAlertBox = function( content,type ){
	
	var windowHeight = document.body.offsetHeight,
		currentScroll = document.documentElement.scrollTop || document.body.scrollTop,
		visableHeight = document.documentElement.clientHeight,
		isIE6 = !'0'[0] && !window.XMLHttpRequest,
		contentLayerBg="",
		timeOut = 2000;
		
	var box = $("<div></div>"),
		bgLayer = $("<div>"+(!isIE6?"":"<iframe style=\"width:100%;height:100%;filter:Alpha(opacity=0);\" border=\"0\" frameborder=\"0\"></iframe>")+"</div>"),
		contentLayer = $("<div></div>"),
		contentContainer = $("<div></div>"),
		dummyDiv = $("<div></div>");
	
	/* 计算字符串实际宽度 ，提示框自适应此宽度，使提示文字保持一行显示完全 */
	dummyDiv.html(content);
	dummyDiv.css({
		"position":"absolute",
		"top":"0",
		"left":"0",
		"font-size":"16px",
		"font-family":"MicroSoft YaHei",
		"visibility":"hidden"
	});
	
	$("body").append(dummyDiv);
	stringWidth = dummyDiv.width();
	dummyDiv.remove();
	
	var contentWidth = stringWidth+20/*iconwidth*/+15/*icon left gap*/+10/*gap between text containter and icon*/+20/* other gap */,
		contentHeight = 60;
	
	/* 弹出框 */
	box.css({
		position:"absolute",
		width:"100%",
		height:windowHeight+"px",
		top:"0px",
		left:"0px",
		"z-index":"99"
	});
	
	bgLayer.css({
		opacity:"0.3",
		width:"100%",
		height:"100%",
		background:"#000"
	});
	
	if( type === "success" ){
		contentLayerBg = "./style/images/success.gif";
	}else if( type === "warning" || type === "error" ){
		contentLayerBg = "./style/images/warning.gif";
	}
	
	contentLayer.css({
		position:"absolute",
		left:"50%",
		top:(visableHeight - contentHeight)/2 + currentScroll +"px",
		width:contentWidth+"px",
		height:contentHeight+"px",
		"margin-left":-contentWidth/2+"px",
		"border":"5px solid #eeeeee",
		"border-radius":"5px",
		"background":"#ffffff"+(contentLayerBg==""?"":" url("+contentLayerBg+") no-repeat 15px 50%")
	});
	contentContainer.css({
		"font-size":"16px",
		"padding-left":"35px",//20px left 20px icon width 5px gap
		"text-align":"center",
		"font-family":"MicroSoft YaHei",
		"line-height":contentHeight+"px"
	});
	
	contentContainer.html(content);
	contentLayer.append(contentContainer);
	box.append(bgLayer);
	box.append(contentLayer);
	
	box.hide();
	$("body").append(box);
	
	setTimeout(function(){
		box.remove();
	},timeOut);
	
	return box;
};