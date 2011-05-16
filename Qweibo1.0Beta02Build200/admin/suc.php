<?php
require_once '../config/sys_config.php';
require_once('inc/admin_head.php');
require_once('inc/admin_headhtml.php');
$url = $_SESSION['bkurl'];
$_SESSION['bkurl'] = '';
?>

	<div class="mainB">
		<div class="suc">
			<img src="../style/images/admin/space.gif" align="absmiddle" />
			<?php
				echo '操作成功！';
			?>
		</div>
		<div class="tc">
			<a href="<?php echo $url;?>">返回</a>
		</div>
	</div>
</div>
<?php
	require_once('inc/admin_footer.php');
?>

