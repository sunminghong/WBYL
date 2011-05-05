<? if(!defined('ROOT')) exit('Access Denied');?>
<div class="bottom" id="footer1">
	<span>Copy &copy; 2011 看看你有多聪明 版权所有 - Developed By @孙铭鸿
		<a href="http://weibo.com/5d13" target="_blank">5d13</a>　
		<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=对@孙铭鸿 (官方微博)说:" target="_blank">意见反馈</a>
	</span>
	<div>特别感谢 <a title="邓冰_刺鸟" namecard="true" href="http://t.sina.com.cn/iciniao" target="_blank">@邓冰_刺鸟<img class="small_icon vip" src="http://img.t.sinajs.cn/t3/style/images/common/transparent.gif" title="新浪认证" alt=""></a>，<a href="http://t.sina.com.cn/1911473942" target="_blank">@小予文</a>，<a title="邓定坤" namecard="true" href="http://t.sina.com.cn/ddkun" target="_blank">@邓定坤<img class="small_icon vip" src="http://img.t.sinajs.cn/t3/style/images/common/transparent.gif" title="新浪认证" alt=""></a> 
	</div>
	<div>
		<a href="http://sae.sina.com.cn" target="_blank"><img src="http://static.sae.sina.com.cn/image/poweredby/poweredby.png" title="Powered by Sina App Engine" /></a>
	</div>
</div>

<script type="text/javascript" src="http://js.wcdn.cn/t3/platform/js/api/wb.js"></script> 
<script type="text/javascript"> 

if(location.href.indexOf('5d13.sinaapp.com')!=-1)
WB.core.load(['connect', 'client', 'widget.base', 'widget.atWhere'], function() {
	var cfg = {
		key: '4106323544',
		xdpath: 'http://5d13.sinaapp.com/_rights/xd.html'
	};
	WB.connect.init(cfg);
	WB.client.init(cfg);
 
	WB.widget.atWhere.searchAndAt(EE("footer1"));
	
	WB.widget.atWhere.blogAt(EE("logo"));
	if(op=='stats'){
		WB.widget.atWhere.searchAndAt(EE("stats_1"));
		WB.widget.atWhere.searchAndAt(EE("top_list1"));
		WB.widget.atWhere.searchAndAt(EE("top_list2"));
	}
	if( op!='ican')
		WB.widget.atWhere.blogAt(EE("div_follow"));
 
	//WB.widget.atWhere.blogAt(("msg_list"), "a");
	
	wbisload=true;
});
</script> 
<div style="display:none;"><script src="http://s20.cnzz.com/stat.php?id=3050823&web_id=3050823" language="JavaScript"></script></div>
</body>
</html>