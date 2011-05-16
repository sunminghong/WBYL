<?php
	if(!MB_admin){
		exit();
	}
	$filter = new MBAdminFilter($host,$root,$pwd,$db);
	$rets = $filter->showKeyWord($p,10);
/*
<form id="form1" name="form1" method="post" action="admin_filter.php?cid=0" class="table_header">
<label>搜索关键字：</label><input type="text" name="k" style="width:340px;" class="txt"/><input type="submit" value="搜索" class="buttonA"/>
</form>
 */
?>
<div class="table_header"><strong>已屏蔽关键字</strong><span><input type="submit" value="添加" class="buttonA" id="newModule"/></span></div>
<?if($rets['count']>0):?>
<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
  <tr>
    <th>关键字</th>
    <th width="100">添加人员</th>
    <th width="100">添加时间</th>
    <th width="100">操作</th>
  </tr>
 <?foreach($rets['list'] as $key => $b):?>
  <tr>
    <td><?php echo htmlspecialchars($b['word'],ENT_QUOTES);?></td>
	<td><?php echo htmlencode($b['oname']);?></td>
    <td><?php echo date('Y-m-d', $b['at']);?></td>
    <td><a href="javascript:void(0);" rel="edit" rev="<?php echo $key;?>">编辑</a> <a href="javascript:void(0);" rel="del" rev="<?php echo $b['id'];?>">删除</a></td>
  </tr>
 <?endforeach?>
</table>
<?else:?>
	<div class="nodata">还没有屏蔽微博</div>
<?endif?>
<?php
	$pages = new MBPage('admin_filter.php?cid='.$cindex,$rets['count'],$p,10);
	$pages->showpage();
?>
<script type="text/javascript">
	var _mlength = <?php echo count($rets['list']);?>;
	var cwin=new moduleObj();
	cwin.autoResize(420,120);
	cwin.hide();
	function delobj(i){
		$.post('admin_filter_act.php?a=k&m=del',{id:i},function(d){
			eval('r='+d);
			if(r){
				cwin.alert('操作成功！');
			}else{
				cwin.alert('操作成功！');
			}
		});
	}	

	function rehtml(str){
		return str.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');
	}

	_data = [];
	<?if($rets['count']>0):?>	
		<?foreach($rets['list'] as $key => $b):?>
		_data.push({
			'id': <?php echo $b['id'];?>,
			'n': '<?php echo htmlencode($b['word']);?>',
			'type': '<?php echo $b['type'];?>'
		});
		<?endforeach?>	
	<?endif?>	
	var _html = [
		'<form id="dataForm" name="form1" method="post" action="admin_filter_act.php?a=k&m=add">',
		'	<ul class="form">',
		'		<li><strong>关键字：</strong><input type="hidden" name="type" value="0" /><input type="text" maxlength="140" name="n" id="kName" /><input type="hidden" value="" name="id" id="kId" /></li>',
		'		<li><strong></strong><input type="submit" value="确定" class="button"/> <input type="reset" value="取消" class="button closeBtn"/></li>',		
		'</form>'
	];
	$("#newModule").click(function(){
		if($('#dataForm').length == 0){
			cwin.show({title:"添加关键字",text:_html.join('')});
			$('#dataForm').validate({
					rules:{
						n:{required:true}
					},
					messages:{
						n:{
							required:'请填写关键字'
						}
					}
				});			
		}else{
			$("#dataForm").attr('action','admin_filter_act.php?a=k&m=add');
			$("#kType").val('');	
			$('#kName').val('');
			cwin.show();
		}
	});

	$('a[rel=edit]').click(function(){
		var no = $(this).attr('rev');
		if($('#dataForm').length == 0){
			cwin.show({title:"修改关键字",text:_html.join('')});
			$("#dataForm").attr('action','admin_filter_act.php?a=k&m=edit');
			$("#kType").val(_data[no].type);	
			$('#kName').val(rehtml(_data[no].n));
			$('#kId').val(_data[no].id);
			$('#dataForm').validate({
					rules:{
						type:{required:true},
						n:{
							required:true						}
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
		cwin.config('确定要删除此项?','delobj('+no+')');			
	});			
</script>
