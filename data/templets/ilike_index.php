<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>看看我的范儿</title>

<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.EasyDrag.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="images/pngfix.js"></script>
<style type="text/css">.pngfix{behavior: url("images/iepngfix.htc");}</style>
<![endif]--> 
<link href="<?=$templatepath?>/ilike_images/css.css" rel="stylesheet" type="text/css" />
<script>var op="<?=$op?>"; var wbisload=false; var templateurl='<?=$templatepath?>'; var urlbase='<?=$urlbase?>';<? if($account) { ?>var logined=true; var myuid='<?=$account['uid']?>';<? } else { ?>var logined=false;  var myuid='';<? } ?></script><script type="text/javascript" src="<?=$templatepath?>/ilike_images/ilike.js"></script>
</head>
<body>

	<div id="uploaddiv" style="display:none;">
		<form id="uploadform" action="index.php?app=ilike&op=ijoin" method="post" name="form1" target="uploadiframe" enctype="multipart/form-data" style="margin:0px;padding:0px;">
			<a href="javascript:void(0);" id="colseupload"><img src="<?=$templatepath?>/ilike_images/closebtn.gif" /></a>
			<p style="margin-top:15px;">选择范儿：<input name="uploadfile" type="file" /></p>
			<p>拉票宣言：(发布后会将范儿及宣言自动同步到微博，你懂的！)</p>
			<p><textarea name="content" id="xuanyan" cols="" rows="">测试发篇微博，打扰大家，不好意思，马上删除！</textarea></p>
			<p><input name="iffollow" type="checkbox" value="1" checked="checked" /> 关注#看看我的范儿#官方微博  <i>（可以即时接收我们的动态）</i></p>
			<p><a href="#" id="submitdo"></a></p>
		</form>	
	<iframe name="uploadiframe" style="display:none;"></iframe>
    </div>
	<div id="logindiv" style="display:none;">
	<a href="javascript:void(0);" id="colselogin"><img src="<?=$templatepath?>/ilike_images/closebtn.gif" /></a>
		<iframe id="loginiframe" name="loginiframe" style="width: 100%; height:100%;" border="0"></iframe>
	</div>

	<div id="mask" class="transparent_class" style="display:none;"></div>
    
	<div id="topbg">
	<div id="logo"><b style="color:#f00;">看</b><b style="color:#f90;">看</b><b style="color:#ff0;">我</b><b style="color:#090;">的</b><b style="color:#f0c;">范</b><b style="color:#f0f;">儿</b> <sup style="font-size:12px;color:#666;">V1.0</sup></div>
	<div id="aboutfaner">北京话"范儿"就是"劲头""派头"的意思，就是指在外貌、行为、或是在某种风格中特别不错的意思，包含了气质、有情调、有品味、有型、有个性等多种含义，具体意思只可意会不可言传。看看你的范儿，评评TA的范儿！</div>
	</div>
    <div id="main">
    	<div id="left" class="">
            <div id="j" style="display:none;"></div> 
        	<div class="loginfalse" style=" display:none">
                
                <a href="#" id="loginbtn"><img src="<?=$templatepath?>/ilike_images/sina_login_32.png" /></a>
            </div>
            <div class="logintrue" style=" display:none">
                <div id="preimgdiv" >
                    <div id="preflag" class="pngfix"><span id="prescore"></span><font face="黑体">分</font></div> 
                    <img id="preimg"/>
                </div>            
                <div id="preintr">共有 <font id="prepnum" color="#ffffff">343</font> 次投票</div>
                <a id="uploadbtn" href="javascript:void(0);"></a>
            </div>
			<a id="uploadbtn2" href="javascript:void(0);" style="display:none;"></a>
        </div>
        <div id="content">
        	<div id="scorediv">
            	<a href="###" id="jiong"></a>
                <a href="#" id="s1" class="scorenum" title="给这张范儿打 1 分，并看下一张"></a>
                <a href="###" id="s2" class="scorenum" title="给这张范儿打 2 分，并看下一张"></a>
                <a href="###" id="s3" class="scorenum" title="给这张范儿打 3 分，并看下一张"></a>
                <a href="###" id="s4" class="scorenum" title="给这张范儿打 4 分，并看下一张"></a>
                <a href="###" id="s5" class="scorenum" title="给这张范儿打 5 分，并看下一张"></a>
                <a href="###" id="s6" class="scorenum" title="给这张范儿打 6 分，并看下一张"></a>
                <a href="###" id="s7" class="scorenum" title="给这张范儿打 7 分，并看下一张"></a>
                <a href="###" id="s8" class="scorenum" title="给这张范儿打 8 分，并看下一张"></a>
                <a href="###" id="s9" class="scorenum" title="给这张范儿打 9 分，并看下一张"></a>
                <a href="###" id="s10" class="scorenum" title="给这张范儿打 10 分，并看下一张"></a>
				<a href="###" id="shuai" class="f"></a>
            </div>
			<div id="filterdiv">范儿这么多，我要筛选：<select id="sel_sex"style="width:100px;"><option value="0">靓女、帅哥</option><option value="2">靓女</option><option value="1">帅哥</option></select>　　*点击上面打分看下一个范儿</div>
            <div id="photodiv"><img src="images/nophoto.jpg" id="photodiv_img"/></div>
            
            <div id="photobtn"><a href="javascript:void(0);" target="_blank" id="btn_watchta">查看TA的微博 » </a>　　<a href="javascript:void(0);" id="btn_followta">喜欢TA的范儿，我关注TA » </a></div>
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
		<div style1="clear:both;"></div>
    </div>
    
	<div id="footer"></div>
	<div id="copyright">
		<span style="font-size:24px;"><b style="color:#f00;">看</b><b style="color:#f90;">看</b><b style="color:#ff0;">我</b><b style="color:#090;">的</b><b style="color:#f0c;">范</b><b style="color:#f0f;">儿</b>
		</span>
			Copy © 2011  版权所有 - Developed By <a target="_blank" style="white-space:nowrap" wb_screen_name="孙铭鸿" href="http://t.sina.com.cn/n/孙铭鸿">@孙铭鸿</a>
		<a href="http://weibo.com/5d13" target="_blank">5d13</a>　
		<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&amp;title=对@孙铭鸿 (官方微博)说:" target="_blank">意见反馈</a>　
		<a href="http://www.cnzz.com/stat/website.php?web_id=3050823" target="_blank">cnzz流量统计</a>
    </div>
</body>
</html>
