<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博<? echo strtoupper($app);?>测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/q_images/main.css?v=1.63" />
<!--	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
<script>var app='<?=$app?>';var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';<? if($account) { ?>var myuid='<?=$account['uid']?>';<? } else { ?>var myuid='';<? } ?></script>
<script type="text/javascript" src="<?=$templatepath?>/q_images/main.js?v=1.63"></script>
</head>
<body>
<? include $this->gettpl('top');?>
	<div class="mainFrame">
	<div class="ui-widget">
			
			<div class="login">
						<? if(is_array($account) ) { ?>
			<img class="zhengshuico icoview" zsurl="<?=$eqScore['zsurl']?>" src="<?=$urlbase?>images/zhengshu_eq_ico_<?=$eqScore['eqlv']?>.png"/>
<font color="#ff3333"><?=$account['screen_name']?></font> <img src="<?=$urlbase?>images/weiboicon16_<?=$account['lfrom']?>.png" height=16 /><? if($account['verified']==1) { ?><img src="<?=$urlbase?>images/vip_<?=$account['lfrom']?>.gif" title="认证用户" alt=""><? } ?>, 你共测试<?=$eqScore['testCount']?> 次， 最高<? echo strtoupper($app);?>值是 <?=$eqScore['eq']?> , 排名第<b> <?=$eqScore['top']?> </b>, 打败了 <b><?=$eqScore['win']?></b>人<? if($eqScore['lostname'] ) { ?>(包括<?=$eqScore['lostname']?>~_~）<? } ?>，加油！<a href="javascript:void(0);" onclick="if(typeof sendmsg =='function')sendmsg();">记录到微博</a>　<a href="?app=home&act=account&op=logout">切换帐号</a>
<? } else { ?>
<? global $canLogin;?>
			<? foreach((array)$canLogin as $login) {?>
				<a href="?app=home&act=account&op=tologin&lfrom=<?=$login?>" border="0"><img height="24" src="<?=$templatepath?>/images/btn_login_<?=$login?>.png" alt="用微博帐号登录" /></a> 
			<? } ?>
<? } ?>
<br/><br/>

<? if(($app=='eq')) { ?>
<a href="?app=eq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_eq.gif" alt="我来测试下EQ"/></a>
<a href="?app=iq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_iq.gif" alt="我来测试下IQ"/></a>
<? } else { ?>
<a href="?app=iq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_iq.gif" alt="我来测试下IQ"/></a>
<a href="?app=eq&op=ready&lfrom=<?=$lfrom?>"><img src="<?=$templatepath?>/q_images/btn_test_blue_eq.gif" alt="我来测试下EQ"/></a>
<? } ?>
	 <a href="?app=eq&op=stats"><img src="<?=$templatepath?>/q_images/btn_stats.gif" alt="聪明排行榜" ></a>
			</div>
			
			<div class="logo" style="position:relative;">
				<a href="?app=eq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/logo_q.gif" alt="看看你有多聪明LOGO"/></a>
				<a href="<?=$orgwbsite?>" target="_blank" style="display:block;position:absolute;left:94px;top:50px;"><img src="<?=$templatepath?>/q_images/btn_5d13site_logo.gif" alt="我的一生官方微博孙铭鸿"/></a>
			</div>
		</div>

<div id="div_notice" style="clear:both;"></div>
		<? if($op=="login") { ?>
		<div class="contentFrame" style="width:260px;float:right;padding:0px;">
			<div class="ui-widget" style="text-align:center; color:#696a62;padding:0px;">
				<div style="background:#ceeff6;line-height:27px;height:27px;">
					<strong>名  人  榜</strong>
				</div>
				<div class="welcomeDiv1" id="mingrenlist" style="font-size:12px;font-family:georgia;background1:#eee;">
<? foreach((array)$mingrenlist as $top) {?>
						<div style="height:45px; overflow:hidden; margin:5px 5px;">
							<div style="width:155px;float:right;line-height:21px;">@<?=$top['name']?><img src="<?=$urlbase?>images/weiboicon16_<?=$top['lfrom']?>.png" height=16 /><? if($top['verified']==1) { ?><img src="<?=$urlbase?>images/vip_<?=$top['lfrom']?>.gif" title="认证用户" alt=""><? } ?><br/>粉丝数<b><?=$top['followers']?></b></div>
							<img style="height:45px;" src="<?=$top['avatar']?>"/><img style="height:45px;" src="<?=$urlbase?>images/zhengshu_eq_ico_<?=$top['eqlv']?>.png" class="icoview" params="eq:<?=$top['eqlv']?>:<?=$top['uid']?>:太NB了，#看看你有多聪明#的明星排行榜里的@<?=$top['name']?>拥有<?=$top['followers']?>个粉丝，还获得了#<?=$top['ch']?>证书#！" alt="点击查看证书"/>
						</div>

					<? } ?>
				</div>
			</div>
		</div>
		<div class="contentFrame" style="width:505px;float:left;clear:none;padding:10px;height:517px;overflow:hidden">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div class="welcomeDiv1" id="msg_list" style="font-size:12px;font-family:georgia;">

				</div>
			</div>
		</div>
<div style="clear:both;"></div>
		<div class="div_login">
		<? if(is_array($account) ) { ?>
			<a href="?app=eq&op=ready"><img src="<?=$templatepath?>/q_images/btn_test_green.gif" alt="我来测试下"/></a>

		<? } else { ?>	
		<? global $canLogin;?>
			<? foreach((array)$canLogin as $login) {?>
				<a href="?app=home&act=account&op=tologin&lfrom=<?=$login?>" border="0"><img height="24" src="<?=$templatepath?>/images/btn_login_<?=$login?>.png" alt="用微博帐号登录" /></a> 
			<? } ?>
		<? } ?>
		</div>

<? } elseif($op=="ready") { ?>
		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>微博<? echo strtoupper($app);?>测试约需25分钟，你准备好了吗？</strong>
				</div>
				<div class="welcomeDiv1">
					1。这是欧洲流行的测试题，可口可乐公司、麦当劳公司、诺基亚公司等世界500强众多企业，曾以此作为员工EQ测试的模板，以帮助员工了解自己的EQ状况。
				</div>
				<div class="welcomeDiv1">
					2。如果你测试结果不理想，请不要气馁，可以过一段时间再来一次（测试间隔最好在一个月以上）！
				</div>
				<div class="welcomeDiv1">
					3。吃喝拉撒搞定了吗？（根据本站统计，人在三分饥的时候思维状态最佳）
				</div>
				<div class="welcomeDiv1">
					4。身边没有阿猫阿狗吧？如果有，请给足粮食，让它安静点！
				</div>
			</div>
		</div>
		<div class="div_login">
		
		<? if(is_array($account) ) { ?>
			<a href="?app=eq&op=ican"><img src="<?=$templatepath?>/q_images/btn_ready.gif" alt="我准备好了，开始" /></a>
		<? } else { ?>
<? global $canLogin;?>
	<? foreach((array)$canLogin as $login) {?>
		<a href="?app=home&act=account&op=tologin&lfrom=<?=$login?>" border="0"><img height="24" src="<?=$templatepath?>/images/btn_login_<?=$login?>.png" alt="用微博帐号登录" /></a> 
	<? } ?>
<? } ?>

		</div>

<? } elseif($op=="stats") { ?>
<div id="ad_800_60" style="width:800px;height:60px;magin-top:8px;margin-bottom:3px;"></div>		
<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div class="welcomeDiv1" id="top_list1" style="text-align:left;">				
					<b>数据统计</b>：总登录人数 <b><?=$eqCount['totalUser']?></b>，成功测试人数 <b><?=$eqCount['eqs']?></b>人， 有效测试  <b><?=$eqCount['logs']?></b>次。目前@<?=$eqCount['maxName']?> 以  <b><?=$eqCount['maxEq']?></b>分的惊人成绩 排名第一！希望聪明的你可以创造奇迹超越 TA！ 
<br/><br/>
		<a href="javascript:void();" onclick="javascript:sendStats();"><img src="<?=$templatepath?>/q_images/btn_tweettop.gif" alt="发到微博瞻仰一下"/></a>
					<a id="div_follow" href="javascript:void(0)" onclick="follow(0);" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/btn_follow_blue_<?=$app?>.gif" alt="关注官方微博"></a>
					<a href="?app=eq&op=ready"><img src="<?=$templatepath?>/q_images/btn_testagain.gif" alt="再测试一次"/></a>
				</div>
			</div>
		</div>
		<? if($myfriendslist) { ?>
		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>《看看你有多聪明》好友天才榜：</strong>
				</div>
				<div class="welcomeDiv1" id="top_list1" style="text-align:left;">				
					<? foreach((array)$myfriendslist as $top) {?>
						<div style="clear:both;">
						<div style="line-height:45px;height:45px;float:left;">第<b><?=$top['i']?></b>名，@<?=$top['name']?>，最高<? echo strtoupper($app);?><b><?=$top['eq']?></b>，共测试<b><?=$top['testCount']?></b>次</div>
						<img style="float:left;margin-left:15px;" src="<?=$urlbase?>images/zhengshu_eq_ico_<?=$top['eqlv']?>.png" class="icoview" params="eq:<?=$top['eqlv']?>:<?=$top['uid']?>"/>
						</div>
					<? } ?>
					<div  style="clear:both;font-size:0;height:0;"></div>
				</div>
			</div>
		</div>
		<? } ?>
		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>《看看你有多聪明》全国天才榜：</strong>
				</div>
				<div class="welcomeDiv1" id="top_list2" style="text-align:left;">				
					<? foreach((array)$toplist as $top) {?>
						<div style="clear:both;">
						<div style="line-height:45px;height:45px;float:left;">第<b><?=$top['i']?></b>名，@<?=$top['name']?>，最高<? echo strtoupper($app);?><b><?=$top['eq']?></b>，共测试<b><?=$top['testCount']?></b>次</div>
						<img style="float:left;margin-left:15px;" src="<?=$urlbase?>images/zhengshu_eq_ico_<?=$top['eqlv']?>.png" class="icoview" params="eq:<?=$top['eqlv']?>:<?=$top['uid']?>"/>
						</div>
					<? } ?>
					<div  style="clear:both;font-size:0;height:0;"></div>			
				</div>
			</div>
		</div>
		<div class="div_login">
		<a href="?app=eq&op=ready"><img src="<?=$templatepath?>/q_images/btn_testagain.gif" alt="再测试一次"/></a>
		</div>

<? } ?>
</div>
<? include $this->gettpl('iq_footer');?>