var moduleObj=function(){
	var init = function(){
        var d = jQuery.extend({
        	width : 450,
			height : 320,
			title:"",
			text : "",
			closeBtn:''
    	}, arguments[0]);
    	var D=document.documentElement||document.body;
		var mob=$("<div class=\"modulebg\"></div>").height(D.clientHeight).appendTo("body").html("<iframe frameborder=\"0\" src=\"\" style=\"width:100%;height:100%;filter:alpha(opacity=0);opacity:0;\"></iframe>");
		var mo=$("<div class=\"cwindow\"></div>").appendTo("body").append("<div class=\"cwindow_inner\"><h2 class=\"cwindowA\"></h2><div class=\"cwindowB\">"+d.text+"</div></div>");
		mo.find(".cwindowA").html("<strong>"+d.title+"</strong><span title=\"关闭\" class=\"closeBtn\"></span>");
		if(arguments[0]&&arguments[0].closeBtn){
		}else{
			d.closeBtn=".cwindow .closeBtn";
		};
		$(d.closeBtn).live("click",closeModuleBox);
			function closeModuleBox(){
				mo.hide();mob.hide();
				if(_mlength<=1){
					try{
						var _t = window.location;
						_t = _t.toString();
						var _cid = _t.substring(_t.indexOf('cid=')+4,_t.indexOf('cid=')+5);
						var _url = _t.substring(0,_t.indexOf('?'));
						window.location = _url+'?cid='+_cid+'&p=0';
					}catch(e){
					}
				}else{
					window.location.reload();
				}
			}
		moduleObj={
			"box":mo,
			"boxbg":mob
		};
		moduleObj.show=function(o){
			this.box.show();this.boxbg.show();
			if (o){
				if (o.title){this.showTitle(o.title);}
				if (o.text){this.showContent(o.text);}
			}	
		}
		moduleObj.showTitle=function(str){
			this.box.find(".cwindowA").find("strong").html(str);
		}
		moduleObj.showContent=function(str){
			this.box.find(".cwindowB").html(str);
		}
		moduleObj.hide=function(){
			this.box.hide();
			this.boxbg.hide();
			//window.location.reload();
		}
		moduleObj.alert=function(str){
			this.showTitle("消息提示");
			this.showContent("<center><br/><br/>"+str+"</center><center><br/><input type=\"button\" value=\"确定\" class=\"closeBtn button\"/><center>");
			this.show();
			$('input.closeBtn[value=确定]').focus();		
			if(arguments.length>1&&arguments[1]&&arguments[2]){
				this.autoResize(arguments[1],arguments[2]);
			}else{
				this.autoResize(250,100);
			}
			window.setTimeout(function(){
				moduleObj.box.hide();
				moduleObj.boxbg.hide();
			},2000);
		}
		moduleObj.config=function(str,fun){
			this.showTitle("消息提示");
			this.showContent("<center><br/><br/>"+str+"</center><center><br/><input type=\"button\" value=\"确定\" class=\"configBtn button\" onclick='"+fun+"' /> <input type=\"button\" value=\"取消\" class=\"closeBtn button\"/><center>");
			this.show();
			$('input.configBtn').focus();		
			if (arguments.length>1&&arguments[1]&&arguments[2]){
				this.autoResize(arguments[1],arguments[2]);
			}else{
				this.autoResize(300,120);
			}
		}		
		moduleObj.autoResize=function(){
			var o=this.box.find(".cwindowB");
			var oj=o[0];
			var w=this.box.find(".cwindow_inner");
			o.removeAttr("style");w.removeAttr("style");
			w.height(o+30);
			w.width(arguments[0]?arguments[0]:(oj.scrollWidth>800?800:oj.scrollWidth));//兼容ie6,ie7
			if (arguments[1]){
				o.height(arguments[1]);
			}
			this.box.css({"left":(D.clientWidth-this.box.width())/2,"top":(D.clientHeight-this.box.height())/2});
		}
		return moduleObj;
	};
	return init();
};
