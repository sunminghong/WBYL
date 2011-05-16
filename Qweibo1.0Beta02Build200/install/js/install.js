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
}

var stepi=parseInt((location.href.match(/step(\d+)\.[php|html]/)||[0,0])[1]);
if (stepi>0)
{	var currentStep="step"+stepi;
	if (cookie.get("step"+stepi)==null){location.href=(stepi==2?"step1.php":"step"+(stepi-1)+".html");}
}