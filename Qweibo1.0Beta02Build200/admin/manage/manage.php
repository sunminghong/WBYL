<?php
	if(!MB_admin){
		exit();
	}
	if($pro <2){
		echo '<meta http-equiv="refresh" content="0;URL=error.php?e=10" />';
		exit();
	}
	$admin = new MBAdminManage($host,$root,$pwd,$db);
	$rets = $admin->showUser($p,10);
?>
<div class="table_header"><strong>已有管理帐号</strong><span><input type="submit" value="添加" class="buttonA" id="newModule"/></span></div>
<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
  <tr>
    <th>帐号</th>
    <th>权限</th>
    <th>状态</th>
    <th>添加时间</th>
    <th>操作</th>
  </tr>
  <?foreach($rets['list'] as $key => $b):?>
  <tr>
    <td><?php echo htmlencode($b['u']);?></td>
	<td>
		<?php
		switch($b['pr']){
			case 1:
				echo '普通管理员';	
				break;
			case 2:
			case 3:
			case 4:
				echo '系统管理员';	
				break;
			default:
				echo '普通管理员';
				break;
		}
		?>
	</td>
	<td>
		<?if($b['s']==1):?>
			激活
		<?elseif($b['s']==0):?>
			未激活
		<?elseif($b['s']==-1):?>
			已删除
		<?endif?>
	</td>
    <td><?php echo date('Y-m-d', $b['at']);?></td>
	<td><a href="javascript:void(0);" rel="edit" rev="<?php echo $key;?>">编辑</a> <?php if($b['id'] != $_SESSION['userid'] ){?><a href="javascript:void(0);" rel="del" rev="<?php echo $b['id'];?>">删除</a><?php }?></td>
  </tr>
  <?endforeach?>
</table>
<?php
	$pages = new MBPage('admin_manage.php?cid='.$cindex,$rets['count'],$p,10);
	$pages->showpage();
?>
<script type="text/javascript">
	var _mlength = <?php echo count($rets['list']);?>;
	function delobj(i){
		$.post('admin_manage_act.php?a=m&m=del',{id:i},function(d){
			eval('r='+d);
			if(r==1){
				cwin.alert('操作成功！');
			}else if(r == 2){
				cwin.alert('不可删除当前帐号！');
			}else if(r == 3){
				cwin.alert('此帐号不可删除！');
			}else{
				cwin.alert('操作失败！');
			}
		});
	}	

	var _data = [];	
	<?foreach($rets['list'] as $key => $b):?>
	_data.push({
		'id': <?php echo $b['id'];?>,
		'uname': '<?php echo htmlencode($b['u']);?>',
		'pwd': '<?php echo htmlencode($b['pwd']);?>',
		'status': '<?php echo $b['s'];?>',
		'pr': '<?php echo $b['pr'];?>'
	});		
  <?endforeach?>
	var _html = [
		'<form id="dataForm" name="form1" method="post" action="admin_manage_act.php?a=m&m=add">',
		'	<ul class="form">',
		'		<li><strong>账    号：</strong><input type="text" name="n" maxlength="20" id="uName"><input type="hidden" value="" name="uid" id="uId" /></li>',
		'		<li id="pwds"><strong>密    码：</strong><input type="password" name="p" maxlength="40" id="uPwd"></li>',
		'		<li><strong></strong>密码长度不能超过40字，区分大小写</li>',
		'		<li><strong>状    态：</strong><select name="s" id="uStatus"><option value="1">激活</option><option value="0">未激活</option></select></li>',
		'		<li><strong>权    限：</strong><select name="pr" id="uPr"><option value="1">普通管理员</option><option value="2">系统管理员</option></select></li>',
		'		<li><strong></strong><input type="submit" value="确定" class="button"/> <input type="reset" value="取消" class="button closeBtn"/></li>',		 '	</ul>',
		'</form>'
	];
	var cwin=new moduleObj();
	cwin.autoResize(420,300);
	cwin.hide();	
	$("#newModule").click(function(){
		if($('#dataForm').length == 0){
			cwin.show({title:"添加管理员",text:_html.join('')});
			$('#pwds').show();
			$('#dataForm').validate({
					rules:{
						n:{required:true},
						p:{required:true}
					},
					messages:{
						n:{
							required:'请填写管理员账号！'	
						},
						p:{
							required:'请填写管理员密码！'	
						}
					}
				});			
		}else{
			$("#dataForm").attr('action','admin_manage_act.php?a=m&m=add');
			$('#pwds').show();
			$("#uName").val('');	
			$('#uUrl').val('');
			$('#uInfo').val('');
			$('#uId').val('');			
			cwin.show();
		}
	});
	$('a[rel=edit]').click(function(){
		var no = $(this).attr('rev');
		if($('#dataForm').length == 0){
			cwin.show({title:"设置管理员",text:_html.join('')});
			$("#dataForm").attr('action','admin_manage_act.php?a=m&m=edit');
			$('#pwds').hide();
			$('#uName').val(_data[no].uname);	
			$('#uStatus').val(_data[no].status);
			$('#uPr').val(_data[no].pr);
			$('#uId').val(_data[no].id);			
			$('#dataForm').validate({
					rules:{
						n:{required:true}
					},
					messages:{
						n:{
							required:'请填写管理员账号！'	
						}
					}
				});			
		}else{
			cwin.show({title:"设置管理员"});
			$("#dataForm").attr('action','admin_manage_act.php?a=m&m=edit');
			$('#pwds').hide();
			$('#uName').val(_data[no].uname);	
			$('#uStatus').val(_data[no].status);
			$('#uPr').val(_data[no].pr);
			$('#uId').val(_data[no].id);			
			cwin.show();
		}
	});
	$('a[rel=del]').click(function(){
		var no = $(this).attr('rev');		
		cwin.config('确定要删除此项?','delobj('+no+')');			

	});			
</script>
