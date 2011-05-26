<? if(!defined('ROOT')) exit('Access Denied');?>
<div class="bottom" id="footer1">
	<span>Copy &copy; 2011 看看你有多聪明 版权所有    鄂ICP备10012123号 - Developed By @孙铭鸿 
		<a href="http://weibo.com/5d13" target="_blank">5d13</a>　
		<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=对@孙铭鸿 (官方微博)说:" target="_blank">意见反馈</a>　
		<a href="http://www.cnzz.com/stat/website.php?web_id=3050823" target="_blank">cnzz流量统计</a>
	</span>
	<div>特别感谢（排名不分先后）： 
	<a title="“看看你有多聪明”最早最热心的玩家之一，特在此感谢她！" namecard="true" href="http://weibo.com/wwwxhzyc" target="_blank">@Sophia薇vivi</a>，
<a title="“看看你有多聪明”最早最热心的玩家之一，本应用现用名离不开他的建议，特在此感谢她！" namecard="true" href="http://weibo.com/hzj9" target="_blank">@HZJ9</a>，
	<a title="国内最早的网页游戏策划师之一，详情去他微博吧！" namecard="true" href="http://weibo.com/dengdx" target="_blank">@邓定鑫</a>，
	<a title="游戏美术师，专攻原画，除了告诉你她非主流外，其他真不能再透露了！" namecard="true" href="http://weibo.com/1270593213" target="_blank">@吱吱呀呀嘎嘎</a>，
	<a title="游戏UI设计师，美女的情况不能多说！" namecard="true" href="http://weibo.com/1752679312" target="_blank">@悟灬语</a>，
	<a title="国内最早的网页游戏开发工程师，NB得一塌糊涂，除此啥也不说了" namecard="true" href="http://weibo.com/iciniao" target="_blank">@邓冰_刺鸟<img class="small_icon vip" src="http://img.t.sinajs.cn/t3/style/images/common/transparent.gif" title="新浪认证" alt=""></a>，<a  title="网页设计师，看她的blog能静心！"href="http://weibo.com/1911473942" target="_blank">@小予文</a>，<a title="国内网页游戏界，第一个吃螃蟹的人！" namecard="true" href="http://weibo.com/ddkun" target="_blank">@邓定坤<img class="small_icon vip" src="http://img.t.sinajs.cn/t3/style/images/common/transparent.gif" title="新浪认证" alt=""></a> 
	</div>
	<div>
		<a href="http://sae.sina.com.cn" target="_blank"><img src="http://static.sae.sina.com.cn/image/poweredby/poweredby.png" title="Powered by Sina App Engine" /></a>
	</div>
</div>

<div id="zhengshupreview" style="display:none;position:absolute;left:100px;top:100px;width:400px;padding:10px 0 15px;text-align:center; background:#fff; border:10px solid #888;" >
	<img id="zhengshuPic" src="<?=$urlbase?>images/zhengshu_iq_1.png"  style="margin-bottom:10px;"/>
		<a href="javascript:void(0);" id="btn_send_2"><img src="<?=$templatepath?>/iq_images/btn_tweet.gif"/></a> 
</div>

<? $coo=intval(mt_rand()*20); ?>

<? if(!NOADS) { ?>
<? if($coo<10) { ?>
<script type="text/javascript">
	$('#ad_800_60').html('<a href="http://ciniao.me/wbapp/?tindex=app.80.index&app=app.80.intr&from=sina" target="_blank" title="我是80后!"><img src="<?=$urlbase?>images/ads/ad_80.gif"  width="800" height="60"/></a>'); </script>
<? } else { ?>
<script type="text/javascript">
	$('#ad_800_60').html('<a href="http://www.265g.com/?from=5d13" target="_blank" title="中国网页游戏第一门户，找网页游戏，就上265G!"><img src="http://img.265g.com/images/gg/265g0525.gif"  width="800" height="60"/></a>'); 
</script>
<? } ?>
<? } ?>
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
 	
	WB.widget.atWhere.searchAndAt(EE("mingrenlist"));
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