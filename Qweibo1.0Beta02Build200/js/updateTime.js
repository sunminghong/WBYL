/******************************************************************************
 * Author: michal
 * Last modified: 2010-1-6
 * Filename: updatetime.js
 * Description: 发送广播后更新显示的时间
******************************************************************************/
wbfuncs.fn.updateTime = {
		update : function( servertime ){
			var nowtime = servertime || Math.floor(new Date().getTime()/1000);//取服务器时间否则为客户机时间
			$(".time").each(function(){
				var timestr = wbfuncs.fn.parseTime(nowtime,$(this).attr("id"));
				$(this).html(timestr);
			});
		}
};