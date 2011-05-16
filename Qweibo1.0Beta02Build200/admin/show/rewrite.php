<?php
session_start();
	if(!MB_admin){
		exit();
	}
require_once('conn.php');
require_once('inc/admin_act.php');
$show = new MBAdminShow($host,$root,$pwd,$db);
$o = $show->getRewrite();
?>

<form id="dataForm" name="form1" method="post" action="admin_show_act.php?a=r" class="pform">
	<p><strong>请选择是否为你网站中的iWeibo提供Rewrite功能：</strong></p>
	<p>Rewrite功能将URL 静态化可以提高搜索引擎抓取<br/>
开启本功能需要对 Web 服务器增加相应的 Rewrite 规则，且会轻微增加服务器负担。</p>
	<p>
		<input type="radio" name="open" value="1" <?if($o):?>checked<?endif?> /><label>开启</label><br/>
		<input type="radio" name="open" value="0" <?if(!$o):?>checked<?endif?> /><label>关闭</label>
	</p>
	<p><input type="submit" value="确定" class="button"/></p>
</form>
<script type="text/javascript">
$(function(){
	$('#dataForm').validate({
		rules:{
			open:{
				required:true
			}
		},
		messages:{
			logo:{
				required:'请选择是否开启Rewrite功能'
			}
		}
	});
});
</script>
