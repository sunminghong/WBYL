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
<script>var op="<?=$op?>"; var wbisload=false; var urlbase='<?=$urlbase?>';<? if($account) { ?>var myuid='<?=$account['uid']?>';<? } else { ?>var myuid='';<? } ?></script><script type="text/javascript" src="<?=$templatepath?>/ilike_images/ilike.js"></script>
</head>
<body>
	<div id="topbg"></div>
    <div id="main">
    	<div id="left" class="first">
        	<div id="j" ></div> 
        	<a href="#"><img src="<?=$templatepath?>/ilike_images/sign-in-with-sina-32.png" /></a>
            
            <!--div id="preimgdiv">
            	<div id="preflag" class="pngfix">9.4<font face="黑体">分</font></div> 
            	<img id="preimg" src="http://asset3-cdn.hotornot.com/photos/017/868/345/17868345/large_2011-04-10_09-57-08_328.jpg?1305787423" />
            </div>
            
            <div id="preintr">共有 <font color="#ffffff">343</font> 次投票</div-->
        </div>
        <div id="content">
        	<div id="scorediv">
            	<a href="###" id="jiong"></a>
                <a href="###" id="s1" class="scorenum"></a>
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
            <div id="photodiv" style="background-image:url(http://hotornot.com/photos/017/868/166/17868166/large_190270_601767266736_32501001_33982732_3173825.jpg?1305779379)"></div>
            
            <div id="photobtn">查看资料　　关注他　　其他的一些按钮</div>            
            <div id="blackdiv"></div>
        </div>
        <div id="right">
        	<div id="nextimgdiv">
            	<div id="nextflag" class="pngfix"></div>
            	<img id="nextimg" src="http://asset3-cdn.hotornot.com/photos/017/868/345/17868345/large_2011-04-10_09-57-08_328.jpg?1305787423" />
            </div>
        	
        </div>
    </div>
    
    <div id="bottom"></div>
</body>
<script>
if($('#main').height()<=639){
	$('#main').css('height',619);
}else{
	$('#main').css('height','auto');
}
</script>
</html>
