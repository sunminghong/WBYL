<?php
require_once '../config/sys_config.php';
require_once('inc/admin_head.php');
require_once('inc/admin_headhtml.php');
$code = (int) $_GET['e'];
$url = $_SESSION['bkurl'];
$_SESSION['bkurl'] = '';
?>

	<div class="mainB">
		<div class="error">
			<img src="../style/images/admin/space.gif" align="absmiddle" />
			<?php
				switch($code){
					case 1:
						echo '帐号或密码错误，请重新输入！';
						break;
					case 10:
						echo '您没有权限访问该页!';
						break;
					case 11:
						echo '您没有权限执行该操作!';
						break;
					case 31:
						echo '您不能把自己设置为未激活状态!';
						break;
					case 32:
						echo '您不能修改该帐号!';
						break;
					case 101:
						echo '写入数据库失败!';
						break;
					case 102:
						echo '请输入相关参数！';
						break;
					case 103:
						echo '没有指定相关ID！';
						break;
					case 104:
						echo '已有同名记录！';
						break;
					case 105:
						echo '该帐号已经存在！';
						break;
					case 106:
						echo '密码输入错误，请修改后重试！';
						break;
					case 107:
						echo '此微博已被屏蔽！';
						break;
					case 108:
						echo '此点评已被屏蔽！';
						break;
					case 109:
						echo '此时段已有设置话题！';
						break;
					case 111:
						echo '新旧密码不能一样！';
						break;
					case 112:
						echo '新密码和确认密码不一致！';
						break;
					case 113:
						echo '请输入推荐用户介绍！';
						break;
					case 114:
						echo '上传文件失败！';
						break;
					case 115:
						echo '文件大小超过限制！';
						break;
					case 116:
						echo '文件类型错误！';
						break;
					case 117:
						echo '数据不能为空！';
						break;
					case 118:
						echo '删除记录失败！';
						break;
					case 119:
						echo '更新记录失败或无需更新！';
						break;
					case 10000:
						echo 'oauth授权失败！请确认app key和app secret正确！';
						break;
					default:
						echo '管理功能操作失败，请重试！';
						break;
				}	
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
