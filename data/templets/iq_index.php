<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博IQ测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iq_images/iq.css?v=5.34" />
	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> 
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';<? if($account) { ?>var myuid='<?=$account['uid']?>';<? } else { ?>var myuid='';<? } ?></script>
<script type="text/javascript" src="<?=$templatepath?>/iq_images/iq_min.js?v=5.34"></script>
<!--<script type="text/javascript" src="http://cbjs.baidu.com/js/m.js"></script>-->
<script type="text/javascript">
//BAIDU_CLB_addSlot("158633");
//BAIDU_CLB_enableAllSlots();
//BAIDU_CLB_preloadSlots("158633");
</script>
</head>
<body>
<? include $this->gettpl('top');?>
<div class="mainFrame">
<div class="ui-widget">
			<div class="login">
<? if(is_array($account) ) { ?>
			<img class="zhengshuico icoview" zsurl="<?=$iqScore['zsurl']?>" src="<?=$urlbase?>images/zhengshu_iq_ico_<?=$iqScore['iqlv']?>.png"/>
<font color="#ff3333"><?=$account['screen_name']?></font>, 你共测试<?=$iqScore['testCount']?> 次， 最高IQ值是 <?=$iqScore['iq']?> , 排名第<b> <?=$iqScore['top']?> </b>, 打败了 <b><?=$iqScore['win']?></b>人<? if($iqScore['lostname'] ) { ?>(包括<?=$iqScore['lostname']?>~_~）<? } ?>，加油！<a href="javascript:void(0);" onclick="if(typeof sendmsg =='function')sendmsg();">记录到微博</a>　<a href="?app=home&act=account&op=logout">切换帐号</a>
<? } else { ?>
<? global $canLogin;?>
			<? foreach((array)$canLogin as $login) {?>
				<a href="?app=home&act=account&op=tologin&lfrom=<?=$login?>" border="0"><img height="24" src="<?=$templatepath?>/images/btn_login_<?=$login?>.png" alt="用微博帐号登录" /></a> 
			<? } ?>
		<? } ?>
		<br/><br/>

<div class="menunav">
<a class="home" href="?app=<?=$app?>">&nbsp;</a>
<a class="iq" href="?app=iq&op=ready&lfrom=<?=$lfrom?>">&nbsp;</a>
<a class="eq" href="?app=eq&op=ready&lfrom=<?=$lfrom?>">&nbsp;</a>
<a class="mq" href="?app=mq&op=ready&lfrom=<?=$lfrom?>">&nbsp;</a>
</div>

	 <a href="?app=iq&op=stats"><img src="<?=$templatepath?>/q_images/btn_stats.gif" alt="聪明排行榜" ></a>

			</div>			
			<div class="logo" style="position:relative;">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/q_images/logo_q.gif" alt="看看你有多聪明LOGO"/></a>
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
							<div style="width:155px;float:right;line-height:21px;">@<?=$top['name']?><? if($top['verified']==1) { ?><img src="<?=$templatepath?>/images/vip.gif" title="新浪认证" alt=""><? } ?><br/>粉丝数<b><?=$top['followers']?></b></div>
							<img style="height:45px;" src="<?=$top['avatar']?>"/><img style="height:45px;" src="<?=$urlbase?>images/zhengshu_iq_ico_<?=$top['iqlv']?>.png" class="icoview" params="iq:<?=$top['iqlv']?>:<?=$top['uid']?>" alt="点击查看证书"/>
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
			<a href="?app=iq&op=ready"><img src="<?=$templatepath?>/iq_images/btn_test_green.gif" alt="我来测试下"/></a>

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
				
				<div class="welcomeDiv1">
					*本应用现在有两套测试题， V1是国内版，V2是欧洲版; V1总分（150分），V2总分（174分），您随意选择。
				</div>
			</div>
		</div>
		<div class="div_login">	
			<a href="?app=iq&op=icanv1"><img src="<?=$templatepath?>/iq_images/btn_ready_v1.gif" alt="我准备好了，开始" /></a>	
			<a href="?app=iq&op=icanv2"><img src="<?=$templatepath?>/iq_images/btn_ready_v2.gif" alt="我准备好了，开始" /></a>			
		</div>

<? } elseif($op=="stats") { ?>
<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div class="welcomeDiv1" id="top_list1" style="text-align:left;">				
					<b><? echo strtoupper($app);?>数据统计</b>：总登录人数 <b><?=$iqCount['totalUser']?></b>，成功测试人数 <b><?=$iqCount['iqs']?></b>人， 有效测试  <b><?=$iqCount['logs']?></b>次。目前@<?=$iqCount['maxName']?> 以  <b><?=$iqCount['maxIq']?></b>分的惊人成绩 排名第一！希望聪明的你可以创造奇迹超越 TA！ 
<br/><br/>
		<a href="javascript:void();" onclick="javascript:sendStats();"><img src="<?=$templatepath?>/iq_images/btn_tweettop.gif" alt="发到微博瞻仰一下"/></a>
					<a id="div_follow" href="javascript:void(0)" onclick="follow(0);" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/btn_follow_blue.gif" alt="关注官方微博"></a>
					<a href="?app=iq&op=ready"><img src="<?=$templatepath?>/iq_images/btn_testagain.gif" alt="再测试一次"/></a>
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
						<div style="line-height:45px;height:45px;float:left;">第<b><?=$top['i']?></b>名，@<?=$top['name']?>，最高IQ<b><?=$top['iq']?></b>，共测试<b><?=$top['testCount']?></b>次</div>
						<img style="float:left;margin-left:15px;" src="<?=$urlbase?>images/zhengshu_iq_ico_<?=$top['iqlv']?>.png" class="icoview" params="iq:<?=$top['iqlv']?>:<?=$top['uid']?>"/>
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
						<div style="line-height:45px;height:45px;float:left;">第<b><?=$top['i']?></b>名，@<?=$top['name']?>，最高IQ<b><?=$top['iq']?></b>，共测试<b><?=$top['testCount']?></b>次</div>
						<img style="float:left;margin-left:15px;" src="<?=$urlbase?>images/zhengshu_iq_ico_<?=$top['iqlv']?>.png" class="icoview" params="iq:<?=$top['iqlv']?>:<?=$top['uid']?>"/>
						</div>
					<? } ?>
					<div  style="clear:both;font-size:0;height:0;"></div>			
				</div>
			</div>
		</div>
		<div class="div_login">
		<a href="?app=iq&op=ready"><img src="<?=$templatepath?>/iq_images/btn_testagain.gif" alt="再测试一次"/></a>
		</div>

<? } ?>
</div>
<? include $this->gettpl('iq_footer');?>