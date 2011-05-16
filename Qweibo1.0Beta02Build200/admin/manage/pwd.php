<?php
	if(!MB_admin){
		exit();
	}
?>
<form id="dataForm" class="pform"  method="post" action="admin_manage_act.php?a=pwd" id="pwdFrom">
<p><strong>请设置希望使用的密码</strong></p>
<div class="sline"></div><br/><br/>
<ul class="form">
	<li><strong>帐号：</strong><?php echo $_SESSION['user'];?><input type="hidden" value="<?php echo $_SESSION['user'];?>" name="n" /></li>
	<li><strong>老密码：</strong><input type="password" value="" name="oldpwd"/></li>
	<li><strong>新密码：</strong><input type="password" value="" name="newpwd" id="newpwd" /></li>
	<li><strong>确认密码：</strong><input type="password" value="" name="newpwd1" id="newpwd1" /></li>
	<li><strong></strong><input type="submit" value="确定" class="button"/></li>
</ul>
</form>
<script type="text/javascript">
	$('#dataForm').validate({
			rules:{
				oldpwd:{required:true},
				newpwd:{
					required:true
				},
				newpwd1:{
					required:true,
					equalTo:'#newpwd' 
				}
			},
			messages:{
				oldpwd:{
					required:'请填写旧密码！'
				},
				newpwd:{
					required:'请填写新密码！'
				},
				newpwd1:{
					required:'请填写确认密码！',
					equalTo:'两次密码不一致！'
				}
			}
		});
</script>
