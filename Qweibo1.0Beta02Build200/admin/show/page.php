<?php
session_start();
	if(!MB_admin){
		exit();
	}
require_once('conn.php');
require_once('inc/admin_act.php');
$show = new MBAdminShow($host,$root,$pwd,$db);

$ret = $show->getPage();
?>
<form id="dataForm" name="form1" method="post" action="admin_show_act.php?a=p" class="pform">
	<p class="t">
		<input type="hidden" value="1" name="visible" />
		<input type="hidden" value="" name="head" />
		请设置在每个iWeibo页面底部显示的网站版权等信息。	
	</p>
	<p style="color:#f00">在页脚使用html代码可能导致安全风险，请注意管理员帐号的安全</p>
	<textarea cols="70" name="foot" rows=8><?php echo htmlencode($ret['page_tail_text']);?></textarea>
	<p>
		<input type="submit" value="确定" class="button"/>
	</p>
</form>
<script type="text/javascript">
	$('#dataForm').validate({
		rules:{
			foot:{
				required:true
			}
		},
		messages:{
			foot:{
				required:'页脚内容不能为空'
			}
		}
	});
</script>
