<?php
	if(!MB_admin){
		exit();
	}
	$recomm = new MBAdminRecomm($host,$root,$pwd,$db);
	$rets = $recomm->showHotUser($p,10);
?>
<div class="mainB">
	<form id="form1" name="form1" method="post" action="" class="table_header">
	<label>模块名称：</label><input type="text" style="width:340px;" class="txt"/> 
		每屏显示人数：<select><option>6</option></select>
	<input type="submit" value="确定" class="buttonA"/>
	</form>
	<div class="sline"></div>
	<div class="table_header"><strong>热门用户</strong><span><input type="submit" value="添加推荐" class="buttonA" id="newModule"/></span></div>
<?if($rets['count']>0):?>	
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
	  <tr>
		<th>微博帐号</th>
		<th>微博昵称</th>
		<th>状态</th>
		<th>排序</th>
		<th>添加人员</th>
		<th>添加时间</th>
		<th>操作</th>
	  </tr>
	  <?foreach($rets['list'] as $key => $b):?>
	  <tr>
		<td><?php echo htmlencode($b['name']);?></td>
		<td><?php echo htmlencode($b['nick']);?></td>
		<td><?php echo $b['status'];?></td>
		<td><?php echo $b['sort'];?></td>
		<td><?php echo htmlencode($b['oname']);?></td>
		<td><?php echo date('Y-m-d', $b['at']);?></td>
		<td><a href="javascript:void(0);" rel="edit" rev="<?php echo $key;?>">编辑</a> <a href="javascript:void(0);" rel="del" rev="<?php echo $b['id'];?>">删除</a></td>
	  </tr>
	  <?endforeach?>
	</table>
	<?php
		$pages = new MBPage('admin_recomm.php?cid='.$cindex,$rets['count'],$p,10);
		$pages->showpage();
	?>
<?else:?>
	<div class="nodata">还没有屏蔽微博</div>
<?endif?>
</div>
<script type="text/javascript" >
$(function(){
	var _data = [];
	<?if($rets['count']>0):?>	
		<?foreach($rets['list'] as $key => $b):?>
		_data.push({
			'id': <?php echo $b['id'];?>,
			'name': '<?php echo htmlencode($b['name']);?>',
			'nick': '<?php echo htmlencode($b['nick']);?>',
			'status': '<?php echo $b['status'];?>',
			'sort': '<?php echo $b['sort'];?>'
		});
		<?endforeach?>		
	<?endif?>	
	var _html = [
		'<form id="dataForm" name="form1" method="post" action="admin_recomm_act.php?a=hu&m=add" class="table_header">',
		'	<ul class="form">',
		'		<li><strong>微博帐号：</strong><input type="text" name="name" id="uName"><input type="hidden" value="" name="uid" id="uId" /></li>',
		'		<li><strong>微博昵称：</strong><input type="text" name="nick" id="uNick"></li>',
		'		<li><strong>状态：</strong><select name="status" id="uStatus"><option value="0">未激活</option><option value="1">激活</option></select></li>',		
		'		<li><strong>排序：</strong><select name="sort" id="uSort"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select></li>',		
		'		<li><strong>排    序：</strong><select name="sort"><option value="1">1</option></select></li>',
		'		<li><strong></strong><input type="submit" value="确定" class="button"/> <input type="reset" value="取消" class="button closeBtn"/></li>',		 '	</ul>',
		'</form>'
	];
	var cwin=new moduleObj();
	cwin.autoResize(420,300);
	cwin.hide();	
	$("#newModule").click(function(){
		if($('#dataForm').length == 0){
			cwin.show({title:"添加推荐",text:_html.join('')});
			$('#dataForm').validate({
					rules:{
						uname:{required:true},
						url:{required:true}
					},
					messages:{

					}
				});			
		}else{
			$("#dataForm").attr('action','admin_recomm_act.php?a=hu&m=add');
			$("#uName").val('');	
			$('#uNick').val('');
			$('#uStatus').val('');
			$('#uSort').val('');			
			$('#uId').val('');			
			cwin.show();
		}
	});

	$('a[rel=edit]').click(function(){
		var no = $(this).attr('rev');
		if($('#dataForm').length == 0){
			cwin.show({title:"添加推荐",text:_html.join('')});
			$("#dataForm").attr('action','admin_recomm_act.php?a=hu&m=edit');
			$("#uName").val(_data[no].name);	
			$('#uNick').val(_data[no].nick);
			$('#uStatus').val(_data[no].status);
			$('#uSort').val(_data[no].sort);
			$('#uId').val(_data[no].id);			
			$('#dataForm').validate({
					rules:{
						name:{required:true},
						nick:{required:true}
					},
					messages:{

					}
				});			
		}else{
			cwin.show();
		}
	});
	$('a[rel=del]').click(function(){
		var no = $(this).attr('rev');		
		$.post('admin_recomm_act.php?a=hu&m=del',{id:no},function(d){
			eval('r='+d);
			if(r){
				alert('删除成功');
				window.location.reload();
			}else{
				alert('删除失败');
			}
		});
	});			
});
</script>
