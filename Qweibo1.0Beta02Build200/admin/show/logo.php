<?php
session_start();
	if(!MB_admin){
		exit();
	}
require_once('conn.php');
require_once('inc/admin_act.php');
$show = new MBAdminShow($host,$root,$pwd,$db);
$logo = $show->getLogo();
if($logo=="")
{$logo="./style/images/logo.png";}
?>
<script>
	function changesize(obj){
				var _t = 345/200;
				if(obj.width/obj.height>_t && obj.width>345){
					$('#reShow').attr('width',345);
				}else if(obj.width/obj.height<_t && obj.height>200){
					$('#reShow').attr('height',200);
				}
	}
</script>
<form id="dataForm" name="form1" method="post" action="admin_show_act.php?a=l" class="pform" enctype="multipart/form-data">
	<p><strong>请选择希望在网站中使用的iWeibo LOGO图案</strong></p>
	<ul>
		<li>
			<strong>请选择图片：</strong><input type="file" value="" name="logo" id="logo" style="width:260px"  /><br/><strong></strong><cite>支持jpg、png、bmp、gif 格式图片,图片大小不能超过2M</cite>
		</li>
		<li>
		<strong>效果预览：</strong><div class="imgarea"><div><img id="reShow" src="<?php echo($logo=='./style/images/logo.png'?'.'.$logo:('../style/images/admin/upload/'.$logo));?>" /></div></div><br/><strong></strong><cite>logo图案将显示在每个网页的左上角位置</cite>
		</li>
		<li><strong></strong><input type="submit" value="确定" class="button" /></li>
	</ul>
</form>
<script type="text/javascript">
$(function(){
	$('#logo').blur(function(){
		var u = $(this).val();
		var strRegex = "(.jpg|.png|.gif|.jpeg)$";  
		if (new RegExp(strRegex).test(u) ){
			var _img = new Image();
			_img.onload = _func;
			_img.src = u;

		}else{
			return false;
		}		
	});
	$('#dataForm').validate({
		rules:{
			logo:{
				required:true
				//accept:'jpg|png|gif|jpeg'
			}
		},
		messages:{
			logo:{
				required:'请选择logo图片'
				//accept:'图片格式不正确'
			}
		}
	});
});
</script>
