/******************************************************************************
 * Author: michal
 * Filename: gototop.js
 * Description: 返回顶部按钮效果
******************************************************************************/
$(function(){
	var isIE876 = !+'\v1',
	 	isIE76 = !'0'[0],
	 	isIE6 = isIE76 && !window.XMLHttpRequest,
	 	supportFixedPosition = !isIE6,
	 	footerHeight = $("#footer").get(0).clientHeight;
	
	var updateGototopBtn = function(){
		var windowHeight = document.body.offsetHeight,
	 		currentScroll = document.documentElement.scrollTop || document.body.scrollTop,
	 		visableHeight = document.documentElement.clientHeight,
	 		gotoWrapper = $("#gotopwrapper");
	 		
		
		if( currentScroll <= 0 && gotoWrapper.length > 0 && gotoWrapper.is(":visible")){//不需要显示返回顶部按钮
	    	gotoWrapper.fadeOut(500);
	    }else if( currentScroll > 0 && gotoWrapper.length > 0 && !gotoWrapper.is(":visible") ){//需要显示返回顶部按钮
	    	gotoWrapper.fadeIn(500);
	    }
		
		if(supportFixedPosition){
			gotoWrapper.css({bottom:Math.max(0,(visableHeight + currentScroll - (windowHeight - footerHeight))) +"px"});
		}
	};
	
	if( window.attachEvent ){
		window.attachEvent("onscroll",updateGototopBtn,false);
	}else{
		window.addEventListener('scroll',updateGototopBtn,false);
	};
	
	window.updateGototopBtn = updateGototopBtn;
});