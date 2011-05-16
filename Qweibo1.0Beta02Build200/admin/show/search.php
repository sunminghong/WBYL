<?php
session_start();
	if(!MB_admin){
		exit();
	}
require_once('conn.php');
require_once('inc/admin_act.php');
@include_once('data/search.php');
if(!isset($search)){
	$search = 1;
}

$show = new MBAdminShow($host,$root,$pwd,$db);

$ret = $show->getPage();
?>
<form id="dataForm" name="form1" method="post" action="admin_show_act.php?a=s" class="pform" enctype="multipart/form-data">
	<p><strong>请选择是否希望在网站中使用的搜索功能</strong></p>
	<ul>
		<li>关闭搜索功能，页面右上角将不显示搜索框</li>
		<li>
			<input type="radio" value="1" name="s" <?php if($search){echo 'checked="checked"';}?> /> 开启搜索功能
		</li>
		<li>
			<input type="radio" value="0" name="s" <?php if(!$search){echo 'checked="checked"';}?> /> 关闭搜索功能
		</li>
		<li><strong></strong><input type="submit" value="确定" class="button" /></li>
	</ul>
</form>
