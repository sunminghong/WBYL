<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>看看你有多聪明！ -  微博IQ测试 </title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iq_images/iq.css" />
	<link href="http://js.wcdn.cn/t3/style/css/common/card.css" type="text/css" rel="stylesheet" /> 
<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';<? if($account) { ?>var myuid='<?=$account['uid']?>';<? } else { ?>var myuid='';<? } ?></script>
<script type="text/javascript" src="<?=$templatepath?>/test_images/test.js?v=1.0"></script>
<style type="text/css">
a{outline:none;}
ul.test{ margin: 10px 0; line-height: 21px;}
ul.test li{color: #666; list-style: inside disc;}
a.btntest:link{margin-right:10px; padding: 8px 14px; background:#693; color:#FFF; text-decoration:none; font-size:14px; border: 1px solid; border-top-color: #98bc7a; border-right-color: #305810; border-bottom-color: #203A0A; border-left-color: #72a44a; line-height:29px;}
a.btntest:hover{margin-right:10px; padding: 8px 14px; background:#7bdb2e; color:#FFF; text-decoration:none; font-size:14px; border: 1px solid; border-top-color: #B3EA87; border-right-color: #4E8A1D; border-bottom-color: #345C13; border-left-color: #97E35A; line-height:29px;}
a.btntest:visited{margin-right:10px; padding: 8px 14px; background:#693; color:#FFF; text-decoration:none; font-size:14px; border: 1px solid; border-top-color: #98bc7a; border-right-color: #305810; border-bottom-color: #203A0A; border-left-color: #72a44a; line-height:29px;}
dl{ margin: 10px 0 20px 0; line-height:21px; width:680px; overflow:hidden;}
dl dt{ font-size:18px; color:#663; font-family: "微软雅黑", "黑体"; margin-bottom:20px;}
dl dd{ display:block; float:left;}
dl dd a{ display:block;color:#333;  border:#6FC solid 1px;width:79px; background:#CFF; margin-right: 4px; padding:0.3em 0.5em; }
dl dd a:hover{text-decoration:none;background:#ececec;}
dl dd a.sel,dl dd a.sel:visited,dl dd a.sel:hover{background:#ececec;}

.testlist{font-family: 'Book Antiqua'; font-style:italic; margin-right: 0.3em; font-size: 48px; line-height: 1em; color:#FC9;}
.radrank{ color:#960; font-weight: bold; margin-left: 0.5em;}
</style>
</head>
<body>

	<div class="mainFrame">

		<div class="ui-widget">			
			<div class="login">
				<font color="#ff3333"><?=$account['screen_name']?></font>, 微博IQ测试须在30分钟内完成（33题）<br/><br/>
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">--><span id="face">00:00</span>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0" id="logo" wb_screen_name="孙铭鸿"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="看看你有多聪明" /></a>
			</div>
		</div>	
		

		<div class="contentFrame" style="clear:both;text-align:center; ">
			<div class="ui-widget" style="text-align:center; color:#696a62; " id="ui-widget-result">

<form method="post" action="" class="c_form">

<? if($op == '' || $op=='index') { ?>
<table cellspacing="0" cellpadding="0" class="formtable">
<caption id="test_cap">
<h2>手 f1gh十压下 压下 </h2>
<p>中华人民共和国枯叶困惑大跃进斯柯达地斯柯达</p>
<span style="display:block; width:680px; overflow:hidden; margin: 10px 0 0 0;">
<img src="http://img2.pict.com/80/f5/b3/1616048/0/test.jpg">
</span>
</caption>
<tr id="test_tr1">
<th style="width:12em;"><a hiddenFocus="true" href="javascript:void(0);" onclick="javascript:init()" class="btntest">开始测试</a></th>
<td>
未来战士在f1gh压下 压下 压下 压下城下 压下压下压下 
                <ul class="test">
                    <li>压下压下压下压下压下压下压下压下</li>
                    <li> 不求上进下十压下 压下 夺</li>
                    <li>感动天妨功害能 古十城下 压下压下 压下 压下压下 往前 蝇</li>
                    <li>压下入播放感动天妨功害能 古十城下 压下压下 压下 压下压下 往前 蝇</li>
                    <li>感动天妨功害能 古十城下 压下压下 压下 压下压下 往前 蝇真正的广东榕泰 须遥</li>
                </ul>
</td>
</tr>
<tr style="display:none;" id="test_tr2"><td id="test_main" colspan="2"></td></tr>
</table>
<table id="btn_b" style="display:none;" width="100%" height="50px;"><tr><td>
<a hiddenFocus="true" href="javascript:void(0)" id="btn_up" class="btntest">&laquo;上一题</a></td>
<td align="right">
<a hiddenFocus="true" href="javascript:void(0)" id="btn_down" class="btntest">下一题&raquo;</a>
</td></tr></table>

<? } ?>


<? if($op == 'showscore') { ?>
<table cellspacing="0" cellpadding="0" class="formtable">
<caption>
<h2>测试开始</h2>
<p>请注意，答对一题 顶替基本面发生率回顶替顶戴要大法师在</p>
</caption>
<tr>
  <td>是分多少！</td>
  </tr>
<tr>
    <td>
      <span style="display:block; margin:0 20px 0 0; color:#D90005; line-height: 1em; font-size:56px; font-family: Verdana; font-weight: bold; float:left;"><?=$rank?></span>
      <ul>
        <li>第一部分得分：<span class="radrank"><?=$qt1?></span></li>
        <li>第二部分得分：<span class="radrank"><?=$qt2?></span></li>
        <li>第三部分得分：<span class="radrank"><?=$qt3?></span></li>
      </ul>
    </td>
    </tr>
<tr>
  <td>
只因中华人民共和国
  <ul class="test">
    <li><a hiddenFocus="true" href="#" target="_blank">结束&raquo;</a></li>
    <li><a hiddenFocus="true" href="#" target="_blank">结束人&raquo;</a></li>
  </ul></td>
  </tr>
</table>
<? } ?>
</form>
</div></div>
</div>
<? include $this->gettpl('iq_footer');?>
