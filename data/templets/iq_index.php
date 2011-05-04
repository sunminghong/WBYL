<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博IQ测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iq_images/iq.css" />
	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> 
<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>
<script>var op="<?=$op?>"; var wbisload=false; </script>
<script type="text/javascript" src="<?=$templatepath?>/iq_images/iq_min.js?v=1.2"></script>
</head>
<body>

	<div class="mainFrame">
		
		<? if($op=="login") { ?>
		<div class="ui-widget">
			
			<div class="login">
			<strong>　　IQ：</strong>就是常说的智商，与此对应的还有EQ、AQ、XQ...；
			<strong>微博IQ测试：</strong>就是常说的智商测试。特别提醒，现在是<font color="#33ff33">beta</font>版，本测试准确度有一定的误差！<br/>
				<!--<a href="?act=account&op=tologin" border="0"><img src="<?=$templatepath?>/images/login.png" alt="用微博帐号登录" /></a><br />-->
				<a id="div_follow" href="javascript:follow(0);" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/followiq.png" alt="关注官方微博" ></a> <a href="?app=iq&op=stats"><img src="<?=$templatepath?>/iq_images/btn_stats.png" alt="关注官方微博" ></a>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明"/></a>
			</div>
		</div>
		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>欢迎使用看看你有多聪明</strong>
				</div>
				<div class="welcomeDiv1" id="msg_list">

				</div>
			</div>
		</div>
		<div class="div_login">
			<a href="?act=account&op=tologin" border="0"><img height="24" src="<?=$templatepath?>/images/sign-in-with-sina-32.png" alt="用微博帐号登录" /></a> 
			<a href="?act=account&op=tologin&lfrom=tqq" border="0"><img height="24" src="<?=$templatepath?>/images/t-qq.png" alt="用腾讯微博登录" /></a>
		</div>

<? } elseif($op=="ready") { ?>

		<div class="ui-widget">
			
			<div class="login">
			<? if(is_array($account) ) { ?>
<font color="#ff3333"><?=$account['screen_name']?></font>, 你一共测试<?=$iqScore['testCount']?> 次， 最高IQ值是 <?=$iqScore['iq']?> , 全国排名第<b> <?=$iqScore['top']?> </b>, 打败了全国 <b><?=$iqScore['win']?>%</b> 的人!加油！！！<a href="?act=account&op=logout">退出</a> 
<? } else { ?>
<? } ?>
<br/><br/>
				<a id="div_follow" href="javascript:follow(0);" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/followiq.png" alt="关注官方微博"></a> <a href="?app=iq&op=stats"><img src="<?=$templatepath?>/iq_images/btn_stats.png" alt="关注官方微博" ></a>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>
				<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div class="welcomeDiv1" id="msg_list">

				</div>
			</div>
		</div>
		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>微博IQ测试约需25分钟，你准备好了吗？</strong>
				</div>
				<div class="welcomeDiv1">
					1。吃喝拉撒搞定了吗？（根据本站统计，人在三分饥的时候思维状态最佳）
				</div>
				<div class="welcomeDiv1">
					2。身边没有阿猫阿狗吧？如果有，请给足粮食，让它安静点！
				</div>
				<div class="welcomeDiv1">
					3。如果你测试结果不理想，请不要气馁，可以过一段时间再来一次（测试间隔最好在一个月以上）！
				</div>
				<div class="welcomeDiv1">
					4。如果结果显示你是天才，请不要窃喜，因为前面已有爱因思坦，后面也会有更聪明的人！
				</div>
			</div>
		</div>
		<div class="div_login">
		
			<button onclick="location.href='?app=iq&op=ican'">我 准 备 好 了</button>
		</div>

<? } elseif($op=="stats") { ?>
		<div class="ui-widget">			
			<div class="login">
			<? if(is_array($account) ) { ?>
<font color="#ff3333"><?=$account['screen_name']?></font>, 你一共测试<?=$iqScore['testCount']?> 次， 最高IQ值是 <?=$iqScore['iq']?> , 全国排名第<b> <?=$iqScore['top']?> </b>, 打败了全国 <b><?=$iqScore['win']?>%</b> 的人!加油！！！<a href="?act=account&op=logout">退出</a> 
<? } else { ?>
<? } ?>
<br/><br/>
				<a id="div_follow" href="javascript:follow(0);" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/followiq.png" alt="关注官方微博"></a>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>

		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>《看看你有多聪明》数据统计</strong>
				</div>
				<div class="welcomeDiv1" id="stats_1">
					《看看你有多聪明》数据统计：总登录人数 <b><?=$iqCount['totalUser']?></b>，成功测试人数 <b><?=$iqCount['iqs']?></b>人， 有效测试  <b><?=$iqCount['logs']?></b>次。目前@<?=$iqCount['maxName']?> 以  <b><?=$iqCount['maxIq']?></b>分的惊人成绩 排名第一！希望聪明的你可以创造奇迹超越 TA！ 
				</div>
				<div class="welcomeDiv1">
					<a href="javascript:void();" onclick="javascript:sendStats();">这么NB!发到微博里瞻仰一下！</a>
				</div>				
			</div>
		</div>
		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>《看看你有多聪明》天才榜：</strong>
				</div
				<div class="welcomeDiv1" id="top_list">
					<? foreach((array)$toplist as $top) {?>
						<p>@<?=$top['name']?>，IQ测试<b><?=$top['iq']?></b>分，共测试<?=$top['testCount']?>次。</p>
					<? } ?>
				</div>
			</div>
		</div>
		<div class="div_login">
		<? if($iqScore['iq']>0) { ?>
			<button onclick="location.href='?app=iq'">我 再 去 试 试！</button>
		<? } else { ?>
			<button onclick="location.href='?app=iq'">我 去 试 试！</button>
		<? } ?>
		</div>

<? } ?>
</div>
		<div class="bottom" id="footer1">
			<span>Copy &copy; 2011 看看你有多聪明 版权所有 - Developed By @孙铭鸿
				<a href="http://weibo.com/5d13" target="_blank">5d13</a>
			</span>
			<div><a href="http://www.265g.com" target="_blank">找网页游戏，就上265G！</a>
				<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=对@孙铭鸿 (官方微博)说:" target="_blank">意见反馈</a>
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
	if(op=='stats'){
		WB.widget.atWhere.searchAndAt(EE("stats_1"));
		WB.widget.atWhere.searchAndAt(EE("top_list"));
	}
	WB.widget.atWhere.blogAt(EE("logo"));
	if( op!='ican')
	WB.widget.atWhere.blogAt(EE("div_follow"));
 
	//WB.widget.atWhere.blogAt(("msg_list"), "a");
	
	wbisload=true;
});
</script> 
<div style="display:none;"><script src="http://s20.cnzz.com/stat.php?id=3050823&web_id=3050823" language="JavaScript"></script></div>
</body>
</html>