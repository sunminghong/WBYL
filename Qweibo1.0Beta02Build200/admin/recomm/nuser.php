<?php
	if(!MB_admin){
		exit();
	}
	$recomm = new MBAdminRecomm($host,$root,$pwd,$db);
	$rets = $recomm->showNewUser($p,10);
?>
<div class="mainB">
	<form id="form1" name="form1" method="post" action="" class="table_header">
	<label>模块名称：</label><input type="text" style="width:340px;" class="txt"/> 
		每屏显示人数：<select><option>6</option></select>
	<input type="submit" value="确定" class="buttonA"/>
	</form>
	<div class="sline"></div>
	<div class="table_header"><strong>新用户推荐</strong><span><input type="submit" value="添加推荐" class="buttonA" id="newModule"/></span></div>
<?if($rets['count']>0):?>	
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
	  <tr>
		<th>姓名</th>
		<th>微博地址</th>
		<th>用户介绍</th>
		<th>排序</th>
		<th>添加人员</th>
		<th>添加时间</th>
		<th>操作</th>
	  </tr>
	  <?foreach($rets['list'] as $key => $b):?>
	  <tr>
		<td><?php echo htmlencode($b['uname']);?></td>
		<td><a href="<?php echo $b['url'];?>" target="_blank"><?php echo $b['url'];?></a></td>
		<td><?php echo htmlencode($b['info']);?></td>
		<td><?php echo $b['soft'];?></td>
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
			'url': '<?php echo htmlencode($b['url']);?>',
			'uname': '<?php echo htmlencode($b['uname']);?>',
			'info': '<?php echo htmlencode($b['info']);?>'
		});
		<?endforeach?>		
	<?endif?>	
	var _html = [
		'<form id="dataForm" name="form1" method="post" action="admin_recomm_act.php?a=nu&m=add" class="table_header">',
		'	<ul class="form">',
		'		<li><strong>姓    名：</strong><input type="text" name="uname" id="uName"><input type="hidden" value="" name="uid" id="uId" /></li>',
		'		<li><strong>微博地址：</strong><input type="text" name="url" id="uUrl"></li>',
		'		<li><p><strong style="vertical-align:top;">用户介绍：</strong><textarea rows="5" name="info" id="uInfo"></textarea></p><p><strong>        </strong><cite style="margin:0">用户介绍最多可输入20个汉字</cite></p></li>',		
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
			$("#dataForm").attr('action','admin_recomm_act.php?a=nu&m=add');
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
			cwin.show({title:"添加推荐",text:_html.join('')});
			$("#dataForm").attr('action','admin_recomm_act.php?a=nu&m=edit');
			$("#uName").val(_data[no].uname);	
			$('#uUrl').val(_data[no].url);
			$('#uInfo').val(_data[no].info);
			$('#uId').val(_data[no].id);			
			$('#dataForm').validate({
					rules:{
						uname:{required:true},
						url:{required:true}
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
		$.post('admin_recomm_act.php?a=nu&m=del',{id:no},function(d){
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

