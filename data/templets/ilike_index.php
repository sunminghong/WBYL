<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Hot Or Not</title>

<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<!--[if IE 6]>
<script type="text/javascript" src="images/pngfix.js"></script>
<style type="text/css">.pngfix{behavior: url("images/iepngfix.htc");}</style>
<![endif]--> 
<link href="<?=$templatepath?>/ilike_images/css.css" rel="stylesheet" type="text/css" />
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';<? if($account) { ?>var logined=true; var myuid='<?=$account['uid']?>';<? } else { ?>var logined=false;  var myuid='';<? } ?></script><script type="text/javascript" src="<?=$templatepath?>/ilike_images/ilike.js"></script>
</head>
<body>
<!--　北京话"范儿"就是"劲头""派头"的意思，就是指在外貌、行为、或是在某种风格中特别不错的意思，有点相近于“气质”、“有情调”的意思，用别的词来形容还真有点费劲，只可意会不可言传。-->
	<div id="uploaddiv" style="display:none;">
		<form id="uploadform" action="index.php?app=ilike&op=ijoin" method="post" name="form1" target="uploadiframe" enctype="multipart/form-data" style="margin:0px;padding:0px;">
			<a href="javascript:void(0);" id="colseupload"><img src="<?=$templatepath?>/ilike_images/closebtn.gif" /></a>
			<p style="margin-top:15px;">选择照片：<input name="uploadfile" type="file" /></p>
			<p>拉票宣言：(发布后会将照片及宣言自动同步到微博，你懂的！)</p>
			<p><textarea name="content" id="xuanyan" cols="" rows="">测试发篇微博，打扰大家，不好意思，马上删除！</textarea></p>
			<p><input name="iffollow" type="checkbox" value="1" checked="checked" /> 关注#爱人#官方微博  <i>（可以即时接收我们的动态）</i></p>
			<p><a href="#" id="submitdo"></a></p>
		</form>	
	<iframe name="uploadiframe" style="display:none;"></iframe>
    </div>
	<div id="logindiv" style="display:none;">
	<a href="javascript:void(0);" id="colselogin"><img src="<?=$templatepath?>/ilike_images/closebtn.gif" /></a>
		<iframe id="loginiframe" name="loginiframe" style="width: 100%; height:100%;" border="0"></iframe>
	</div>

	<div id="mask" class="transparent_class" style="display:none;"></div>
    
	<div id="topbg"></div>
    <div id="main">
    	<div id="left" class="first">
        	<div class="loginfalse">
                <div id="j"></div> 
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
        </div>
        <div id="content">
        	<div id="scorediv">
            	<a href="###" id="jiong"></a>
                <a href="#" id="s1" class="scorenum"></a>
                <a href="###" id="s2" class="scorenum"></a>
                <a href="###" id="s3" class="scorenum"></a>
                <a href="###" id="s4" class="scorenum"></a>
                <a href="###" id="s5" class="scorenum"></a>
                <a href="###" id="s6" class="scorenum"></a>
                <a href="###" id="s7" class="scorenum"></a>
                <a href="###" id="s8" class="scorenum"></a>
                <a href="###" id="s9" class="scorenum"></a>
                <a href="###" id="s10" class="scorenum"></a>
				<a href="###" id="shuai" class="m"></a>
            </div>
            <div id="photodiv"></div>
            
            <div id="photobtn">查看资料　　关注他　　其他的一些按钮</div>            
            <div id="blackdiv"></div>
        </div>
        <div id="right">
        	<div id="nextimgdiv">
            	<div id="nextflag" class="pngfix"></div>
            	<img id="nextimg"/>
            </div>
        	
            <div id="loglist">
            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>上传了照片</p>
            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>打分完成</p>
                            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>上传了照片</p>
            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>打分完成</p>

            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>上传了照片</p>
            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>打分完成</p>

            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>上传了照片</p>
            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>打分完成</p>
            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>上传了照片</p>
            	<p><i>5-12 15:30</i> <a href="#">@刺鸟</a>打分完成</p>

            </div>
        </div>
    </div>
    
    <div id="bottom"></div>
</body>
</html>
