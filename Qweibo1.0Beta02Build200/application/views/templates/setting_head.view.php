<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-14 16:24
 * Filename: setting_head.view.php
 * Description: 头像设置 
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 新声明的变量:
 * 
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 
 * 已使用的变量:
 * 
 * 子模板可用变量: 
 * 
******************************************************************************/
ob_start();
?>
<script src="./js/settinghead.js"></script>
<script src="./js/richMsgBox.js"></script>
<script type="text/javascript">
<?php if( isset($message) && !empty( $message["text"] ) ){ ?>
		$(function(){
			$.richAlertBox("<?php echo $message["text"];?>","<?php echo $message["type"];?>").show();
		});
<?php } ?>
</script>
<?
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();
?>
<style type="text/css">
	.content{_ height:900px;}
	.tabformbar{text-align:center;clear:both;*display:inline;zoom:1;width:100%;}
</style>
<div class="shezhimain">
	<div class="tabbar">
		<ul class="tabs">
			<li class="tab tab88x33"><a href="./index.php?m=userinfo">个人资料</a></li>
			<li class="tab tab88x33 active">修改头像</li>
		</ul>
	</div>
	<?php 
		/*
		if( isset($message) ){ 
			echo "<div class=\"settingheadmsg ".$message["type"]."message\">".$message["text"]."</div>";
		}
		*/
	$userhead_src=array_key_exists("head",$user) && !empty($user["head"])?$user["head"]:"./style/images/default_head_120.jpg";	
	?>
	
	<div class="shezhitouxiang" style="display:none;" id="userhead_settingA">
		<div class="toleft touxiang"><img src="<?php echo($userhead_src); ?>" alt="" id="userhead_img"></div>
		<div class="toleft shangchuan" style="_ height:110px;">
			<form action="./index.php?m=userhead" method="post" enctype="multipart/form-data" name="form_upload" id="form_upload" target="_self"> 
				<input type="file" id="pic" name="pic" class="upload" onchange="checkFile(this);"> 
				<p class="gray">支持jpg、jpeg、gif、png格式的图片，不超过2M<br>建议图片尺寸大于120×120</p> 
				<!--<input type="submit" class="usebtns saveBtn hide" id="submitbtn" value="">  -->
				<a class="saveBtn" style="display:none;" id="submitbtn" onclick="return saveform();">保&nbsp;存</a> 
			</form>
		</div>
		<div class="tabformbar"><br/>
		如果想要编辑头像，请尝试使用<a href="javascript:void(0)" onclick="simpleUpload(0)">编辑上传模式</a><br/><br/>
		</div>
	</div>
	<div class="shezhitouxiang" id="userhead_settingB">
		<div style="width:530px;height:400px;">
			<embed type="application/x-shockwave-flash" src="style/images/saveHead.swf?imgurl=<?php echo($userhead_src);?>&t=<? echo time();?>" width="100%" height="100%" id="qqminiblog" name="qqminiblog" bgcolor="#FFFFFF" quality="high" allownetworking="all" allowscriptaccess="always" allowfullscreen="true" scale="noscale" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer"/>
		</div>
		<div class="tabformbar"><br/>
			如果无法上传头像，请尝试使用<a href="javascript:void(0)" onclick="simpleUpload(1)">普通上传模式</a>
		</div>
	</div>
	<script type="text/javascript">
		function simpleUpload(k)
		{if(k==0)
		 {
		 $("#userhead_settingB").show();
		 $("#userhead_settingA").hide();
		 }else if(k==1)
		 {
		 $("#userhead_settingA").show();
		 $("#userhead_settingB").hide();
		 }
		 //$("#userhead_img").attr("src","<?php echo($userhead_src);?>"+"?t="+new Date().getTime());
		}
		function saveCutSuccess()
		{
			alert("保存成功！");location.reload();
			//setTimeout("location.reload();",1000);
		}
		function cancelCutHead()
		{
			window.location.href="./";
		}
	</script>
</div>
<?php
	$content = ob_get_contents();
	ob_end_clean();
	require_once pathJoin( TEMPLATE_DIR,'base_1col.view.php' );
?>

