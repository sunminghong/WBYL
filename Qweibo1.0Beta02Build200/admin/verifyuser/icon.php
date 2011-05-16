<?php 
	@include(MB_ADMIN_DIR.'/data/verify_icon.php');
	$verify_bg = '';
	$vipicon="background:url(../style/images/vip.gif);";//原始认证图标
	if(isset($verify_icon_file_name)){
		$verify_bg = "background:url('../style/images/admin/upload/".$verify_icon_file_name."');";
		if (!file_exists('../style/images/admin/upload/'.$verify_icon_file_name))
		{$verify_bg=$vipicon;}
	}else{$verify_bg=$vipicon;}
?>
<form id="dataForm" name="form1" method="post" action="admin_verify_act.php?act=upload" class="pform" enctype="multipart/form-data">
	<ul>
		<li>
			<strong  style="text-align:left;width:80px;">请选择图片：</strong><input type="file" value="" name="logo" id="logo" style="width:260px"  /><br/><strong></strong><cite>支持jpg、png、bmp、gif 格式图片,图片大小不能超过2M</cite>
		</li>
		<li>
			<strong  style="text-align:left;width:80px;">效果预览：</strong><div class="imgarea" style="position:relative;background:url('../style/images/admin/v_preview_bg.gif');"><div style="position:absolute;top:4px;left:74px;width:16px;overflow:hidden;height:16px;<?php echo $verify_bg;?>"></div>
		<li><strong></strong><input type="submit" value="确定" class="button" /></li>
	</ul>
</form>