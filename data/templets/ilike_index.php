<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>看看我的范儿</title>

<script type="text/javascript" src="js/jquery.min.js"></script>
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="js/jquery.easydrag.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="js/pngfix.js"></script>
<style type="text/css">.pngfix{behavior: url("js/iepngfix.htc");}</style>
<![endif]--> 
<link href="<?=$templatepath?>/ilike_images/css.css" rel="stylesheet" type="text/css" />
<script>var op="<?=$op?>"; var wbisload=false; var templateurl='<?=$templatepath?>'; var urlbase='<?=$urlbase?>';<? if($account) { ?>var logined=true; var myuid='<?=$account['uid']?>';<? } else { ?>var logined=false;  var myuid='';<? } ?></script><script type="text/javascript" src="<?=$templatepath?>/ilike_images/ilike.js"></script>
</head>
<body>
	<div id="uploaddiv" style="display:none;">
    			<a href="javascript:void(0);" id="colseupload"><img src="<?=$templatepath?>/ilike_images/closebtn.gif" /></a>

		<form id="uploadform" action="index.php?app=ilike&op=ijoin" method="post" name="form1" target="uploadiframe" enctype="multipart/form-data" style="margin:0px;padding:0px;">
			<p style="margin-top:15px;">选择范儿：<input name="uploadfile" type="file" /></p>
			<p>拉票宣言：(发布后会将范儿及宣言将同步到微博让粉丝来支持你！)</p>
			<p><textarea name="content" id="xuanyan" cols="" rows="">测试发篇微博，打扰大家，不好意思，马上删除！</textarea></p>
			<p><input name="iffollow" type="checkbox" value="1" checked="checked" /> 关注#看看我的范儿#官方微博  <i>（可以即时接收我们的动态）</i></p>
			<p><a href="#" id="submitdo"></a></p>
		</form>	
	<iframe name="uploadiframe" style="display:none;"></iframe>
    </div>
	<div id="logindiv" style="display:none;">
		<iframe id="loginiframe" frameborder="0" name="loginiframe" style="width: 100%; height:395px;" border="0"></iframe>
		<div class="logindiv_bottom"><a href="javascript:void(0);" id="btn_closelogin"> 关 闭 </a></div>
	</div>

	<div id="mask" class="transparent_class" style="display:none;"></div>
    
	<div id="topbg">    
    <div id="topcon">    
	<!--<div id="logo"><b style="color:#f00;">看</b><b style="color:#f90;">看</b><b style="color:#ff0;">我</b><b style="color:#090;">的</b><b style="color:#f0c;">范</b><b style="color:#f0f;">儿</b> <sup style="font-size:12px;color:#666;">V1.0</sup></div>-->
	<div id="logo"><img src="<?=$templatepath?>/ilike_images/logo_55.gif" title="看看我的范儿的LOGO"/></div>
	<? if($account) { ?>
		<? if($score) { ?>
		<div id="aboutfaner"><img style="height:45px;"  src="<?=$account['avatar']?>" align="right"/><?=$account['screen_name']?>，你现在得分<b id="myscore"><?=$score['score']?></b>，上传<b id="mypiccount"><?=$score['piccount']?></b>个范儿，<br/>被评价<b id="mybyratecount"><?=$score['byratecount']?></b>次，看了<b id="myratecount"><?=$score['ratecount']?></b>个范儿，共评出 <b id="myratescore"><?=$score['ratescore']?></b>分。</div>
		<? } else { ?>
		<div id="aboutfaner"><img style="height:45px;"  src="<?=$account['avatar']?>" align="right"/><?=$account['screen_name']?>，你现在得分<b id="myscore">0</b>，被评价<b id="mybyratecount">0</b>次，上传<b id="mypiccount">0</b>个范儿，<br/>看了<b id="myratecount">0</b>个范儿，共评出 <b id="myratescore">0</b>分。</div>		
		<? } ?>
	<? } else { ?>
	<div id="aboutfaner" style="color:#ccc;">北京话"范儿"就是"劲头""派头"的意思，就是指在外貌、行为、或是在某种风格中特别不错的意思，包含了气质、有情调、有品味、有型、有个性等多种含义，具体意思只可意会不可言传。看看你的范儿，评评TA的范儿！</div>
	<? } ?>
    <div style="clear:both;font-size:0;"></div>
    </div>
	</div>
    <div id="main">
    	<div id="left" class="">
            <div id="j" style="display:none;" class="pngfix"></div> 
        	<div class="loginfalse" style=" display:none">
                
                <a href="#" id="loginbtn"><img src="<?=$templatepath?>/ilike_images/sina_login_32.png" /></a>
            </div>
            <div class="logintrue" style=" display:none">
                <div id="preimgdiv" >
                    <div id="preflag" class="pngfix"><span id="prescore"></span><font face="黑体">分</font></div> 
                    <img id="preimg"/>
                </div>            
                <div id="preintr">共有 <font id="prepnum" color="#ffffff">0</font> 次投票</div>
                <a id="uploadbtn" href="javascript:void(0);"></a>
            </div>
			<a id="uploadbtn2" href="javascript:void(0);" style="display:none;"></a>
        </div>
        <div id="content">
        	<div id="scorediv">
            	<a href="#" id="jiong"></a>
                <a href="#" id="s1" class="scorenum" title="给这张范儿评 1 分，并看下一张"></a>
                <a href="#" id="s2" class="scorenum" title="给这张范儿评 2 分，并看下一张"></a>
                <a href="#" id="s3" class="scorenum" title="给这张范儿评 3 分，并看下一张"></a>
                <a href="#" id="s4" class="scorenum" title="给这张范儿评 4 分，并看下一张"></a>
                <a href="#" id="s5" class="scorenum" title="给这张范儿评 5 分，并看下一张"></a>
                <a href="#" id="s6" class="scorenum" title="给这张范儿评 6 分，并看下一张"></a>
                <a href="#" id="s7" class="scorenum" title="给这张范儿评 7 分，并看下一张"></a>
                <a href="#" id="s8" class="scorenum" title="给这张范儿评 8 分，并看下一张"></a>
                <a href="#" id="s9" class="scorenum" title="给这张范儿评 9 分，并看下一张"></a>
                <a href="#" id="s10" class="scorenum" title="给这张范儿评 10 分，并看下一张"></a>
				<a href="#" id="shuai" class="f"></a>
            </div>
			<div id="filterdiv">范儿这么多，我要筛选：<select id="sel_sex"style="width:100px;"><option value="0">靓女、帅哥</option><option value="2">靓女</option><option value="1">帅哥</option></select>　　*点击上面打分看下一个范儿</div>
            <div id="photodiv"><img src="images/nophoto.jpg" id="photodiv_img"/></div>
            
            <div id="photobtn"><a href="javascript:void(0);" target="_blank" id="btn_watchta">查看TA的微博 » </a>　　<a href="javascript:void(0);" id="btn_followta">喜欢TA的范儿，我关注TA » </a>　　<a href="javascript:void(0);" id="btn_bury" title="这是三俗、非人的范儿">我要埋葬它 » </a></div>
            <div id="blackdiv"></div>
        </div>
        <div id="right">
		    <!--<div class="followthis" style="margin-left:20px;">
                 <a href="javascript:void(0);" id="btn_follow_this"><img src="<?=$templatepath?>/ilike_images/sina_login_32.png" /></a>
            </div>-->
        	<div id="nextimgdiv">
            	<div id="nextflag" class="pngfix"></div>
            	<img id="nextimg"/>
            </div>
        	
            <div id="msglist">
            	

            </div>
        </div>
		<div style="clear:both;"></div>
    </div>
    
	<div id="footer">
		<span class="toplink">您还可以玩：
<a href="http://ciniao.me/wbapp/?tindex=app.80.index&app=app.80.intr&from=sina" target="_blank">我的80后</a>
<a href="http://q.5d13.cn/eq" target="_blank">情商EQ测试</a>
<a href="http://q.5d13.cn/iq" target="_blank">智商IQ测试</a>
<a href="http://ciniao.me/wbapp/?a=xm&from=sina" target="_blank">羡慕嫉妒恨</a>
</span>

	</div>
	<div id="copyright">
		<img style="margin-top:12px;" src="<?=$templatepath?>/ilike_images/logo_55.gif" title="看看我的范儿的LOGO" border="0"/>
			Copy © 2011  版权所有 - Developed By <a target="_blank" style="white-space:nowrap" wb_screen_name="孙铭鸿" href="http://weibo.com/5d13">@孙铭鸿</a>
		<a href="http://weibo.com/5d13" target="_blank">5d13</a>　
		<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&amp;title=对@孙铭鸿 (官方微博)说:" target="_blank">意见反馈</a>　
		<a href="http://www.cnzz.com/stat/website.php?web_id=3130104" target="_blank">cnzz流量统计</a>
    </div>
	<div style="display:none;"><script src="http://s22.cnzz.com/stat.php?id=3130104&web_id=3130104" language="JavaScript"></script></div>
</body>
</html>
<!--
新近开发的新的小应用#看看我的范儿#开始封测，我首先上传一张近照，让朋友们#看看我的范儿#！

有幸成为#看看我的范儿#的开始封测，我首先上传一张近照，让朋友们#看看我的范儿#！-->