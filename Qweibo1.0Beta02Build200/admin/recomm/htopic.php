<?php
	if(!MB_admin){
		exit();
	}
	$recomm = new MBAdminRecomm($host,$root,$pwd,$db);
	$rets = $recomm->showHotTopic($p,10);
	$mods = $recomm->getMod(2);
	$modname = $mods['list'][0]['name'];
	$pnum = $mods['list'][0]['p2'];
?>
	<form id="modForm" name="modform" method="post" action="admin_recomm_act.php?a=mod" class="table_header">
		<div class="mods">
			<label>模块名称：</label><input type="hidden" name="mp" value="2" /><input id="modName" maxlength="20" type="text" value="<?php echo htmlencode($modname);?>" name="mn" style="width:280px;" class="txt"/>	
			显示话题数：<select name="pnum">
				<option value="1" <?if($pnum==1):?>selected="selected"<?endif?>>1</option>
				<option value="2" <?if($pnum==2):?>selected="selected"<?endif?>>2</option>
				<option value="3" <?if($pnum==3):?>selected="selected"<?endif?>>3</option>
				<option value="4" <?if($pnum==4):?>selected="selected"<?endif?>>4</option>
				<option value="5" <?if($pnum==5):?>selected="selected"<?endif?>>5</option>
				<option value="6" <?if($pnum==6):?>selected="selected"<?endif?>>6</option>
				<option value="7" <?if($pnum==7):?>selected="selected"<?endif?>>7</option>
				<option value="8" <?if($pnum==8):?>selected="selected"<?endif?>>8</option>
				<option value="9" <?if($pnum==9):?>selected="selected"<?endif?>>9</option>
				<option value="10" <?if($pnum==10):?>selected="selected"<?endif?>>10</option>
				<option value="11" <?if($pnum==11):?>selected="selected"<?endif?>>11</option>
				<option value="12" <?if($pnum==12):?>selected="selected"<?endif?>>12</option>
				<option value="13" <?if($pnum==13):?>selected="selected"<?endif?>>13</option>
				<option value="14" <?if($pnum==14):?>selected="selected"<?endif?>>14</option>
				<option value="15" <?if($pnum==15):?>selected="selected"<?endif?>>15</option>
				<option value="16" <?if($pnum==16):?>selected="selected"<?endif?>>16</option>
				<option value="17" <?if($pnum==17):?>selected="selected"<?endif?>>17</option>
				<option value="18" <?if($pnum==18):?>selected="selected"<?endif?>>18</option>
				<option value="19" <?if($pnum==19):?>selected="selected"<?endif?>>19</option>
				<option value="20" <?if($pnum==20):?>selected="selected"<?endif?>>20</option>
			</select>
			<input type="submit" value="确定" class="buttonA"/>
		</div>
		<div><cite>请设置希望在广播大厅中显示的模块名称，及热门话题数量，将按照已设置话题列表顺序选择。</cite></div>
	</form>
	<div class="table_header">
		<strong>已设置话题</strong>
		<span><input type="button" value="新建话题" class="buttonA" id="newModule"/></span>
	</div>
	<?if($rets['count']>0):?>
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
	  <tr>
		<th>话题</th>
		<th width="100">状态</th>
		<th width="100">添加人员</th>
		<th width="100">操作</th>
	  </tr>
	  <?foreach($rets['list'] as $key => $b):?>
	  <tr>
		<td><?php echo htmlencode($b['tit']);?></td>
		<td>
			<?if($b['status']):?>
				激活
			<?else:?>
				未激活
			<?endif?>
		</td>
		<td><?php echo htmlencode($b['oname']);?></td>
		<td><a href="javascript:void(0);" rel="edit" rev="<?php echo $key;?>">编辑</a> <a href="javascript:void(0);" rel="del" rev="<?php echo $b['id'];?>">删除</a></td>
	  </tr>
	  <?endforeach?>
	</table>
	<?else:?>
		<div class="nodata">还没有设置热门话题</div>
	<?endif?>
<?php
	$pages = new MBPage('admin_recomm.php?cid='.$cindex,$rets['count'],$p,10);
    $pages->showpage();
?>
<script type="text/javascript" >
	var _mlength = <?php echo count($rets['list']);?>;
	$('#modForm').validate({
			errorPlacement: function(label, element) {
				label.appendTo(element.parent());
			},
			rules:{
				mn:{
					required:true,
					maxlength:20
				}
			},
			messages:{
				mn:{
					required:'请填写模块名称',
					maxlength:'话题名不能超过20个字'
				}
			}
	});	

	$('#modName').keyup(function(){
		var _t = $(this).val();	
		if(_t.length>20){
			$(this).val(_t.substring(0,20));	
		}
	});

	<?if($rets['count']>0):?>
		var _data = [];	
		<?foreach($rets['list'] as $key => $b):?>
		_data.push({
			'id': <?php echo $b['id'];?>,
			'tit': '<?php echo htmlencode($b['tit']);?>',
			'sort': <?php echo $b['sort'];?>,
			'status': <?php echo $b['status'];?>
		});
		<?endforeach?>
	<?endif?>
	var _html = [
		'<form id="dataForm" name="form1" method="post" action="admin_recomm_act.php?a=hht&m=add">',
		'	<ul class="form">',
		'		<li><strong>话题名：</strong><input type="text" name="t" maxlength="20" id="hName" /><br/><input type="hidden" value="" name="id" id="hId" /><input type="hidden" name="sort" value="0" id="Sort" /></li>',
		'		<li><strong>状态：</strong><select name="status" id="Status"><option value="0">未激活</option><option value="1">激活</option></select></li>',
		'		<li><strong></strong><input type="submit" value="确定" class="button"/> <input type="reset" value="取消" class="button closeBtn"/></li>',		
		'</form>'
	];

	var cwin=new moduleObj();
	cwin.autoResize(420,200);
	cwin.hide();
	$("#newModule").click(function(){
		if($('#dataForm').length == 0){
			cwin.show({title:"添加话题",text:_html.join('')});
			$('#dataForm').validate({
					rules:{
						t:{
							required:true,
							maxlength:20
						}
					},
					messages:{
						t:{
							required:'请填写话题名',
							maxlength:'话题名不能超过20个字'
						}
					}
				});			
		}else{
			$("#dataForm").attr('action','admin_recomm_act.php?a=hht&m=add');
			$('#dataForm label.error').hide();
			$("#hName").val('');	
			$('#Sort').val('');
			$('#Status').val('');
			cwin.show();
		}
	});

	$('a[rel=edit]').click(function(){
		var no = $(this).attr('rev');
		if($('#dataForm').length == 0){
			cwin.show({title:"修改话题",text:_html.join('')});
			$("#dataForm").attr('action','admin_recomm_act.php?a=hht&m=edit');
			$('#hId').val(_data[no].id);
			$("#hName").val(_data[no].tit);	
			$('#Sort').val(_data[no].sort);
			$('#Status').val(_data[no].status);			
			$('#dataForm').validate({
					rules:{
						t:{
							required:true,
							maxlength:20
						}
					},
					messages:{
						t:{
							required:'请填写话题名',
							maxlength:'话题名不能超过20个字'
						}
					}
				});			
		}else{
			cwin.show({title:"修改话题"});
			$('#dataForm label.error').hide();
			$("#dataForm").attr('action','admin_recomm_act.php?a=hht&m=edit');
			$('#hId').val(_data[no].id);
			$("#hName").val(_data[no].tit);	
			$('#Sort').val(_data[no].sort);
			$('#Status').val(_data[no].status);					
		}
	});

	function delobj(i){
		$.post('admin_recomm_act.php?a=hht&m=del',{id:i},function(d){
			eval('r='+d);
			if(r){
				cwin.alert('操作成功！');
			}else{
				cwin.alert('操作成功！');
			}
		});	
	}

	$('a[rel=del]').click(function(){
		var no = $(this).attr('rev');		
		cwin.config('确定要删除此项?','delobj('+no+')');			
	});		
</script>
