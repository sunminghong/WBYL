<?php
	if(!MB_admin){
		exit();
	}
	$recomm = new MBAdminRecomm($host,$root,$pwd,$db);
	$rets = $recomm->showMod();
?>
<div class="table_header"><strong>可设置显示模块</strong></div>
<table width="450" border="0" cellspacing="0" cellpadding="0" align="left" class="tableA" style="margin-left:20px;">
  <tr>
    <th>模块</th>
    <th width="60">状态</th>
    <th width="60">操作</th>
  </tr>
  <?foreach($rets['list'] as $b):?>
  <tr>
	<td><?php echo htmlencode($b['n']);?></td>
	<td>
		<?if($b['status']):?>
			启用
		<?else:?>
			已禁用
		<?endif?>
	</td>
	<td><a href="javascript:void(0);" rel="<?if($b['status']):?>cancel<?else:?>use<?endif?>" rev="<?php echo $b['id'];?>"><?if($b['status']):?>禁用<?else:?>启用<?endif?></a></td>
  </tr>
  <?endforeach?>
</table>
<script type="text/javascript">
	var _mlength = 1;
	var cwin=new moduleObj();
	cwin.autoResize(220,60);
	cwin.hide();	
	$('a[rel=use]').each(function(){
		$(this).click(function(){
			var _id = $(this).attr('rev');
			var url = 'admin_recomm_act.php?a=mod&m=c'
			var param = {
				'id': _id,
				'status': 1
			};
			$.post(url,param,function(d){
				if(d>=0){
					cwin.alert('操作成功！');			
				}else{
					cwin.alert('操作失败！');			
				}
			});
		});
	});
	$('a[rel=cancel]').each(function(){
		$(this).click(function(){
			var url = 'admin_recomm_act.php?a=mod&m=c'
			var _id = $(this).attr('rev');	
			var param = {
				'id': _id,
				'status': 0
			};
			$.post(url,param,function(d){
				if(d>=0){
					cwin.alert('操作成功！');			
				}else{
					cwin.alert('操作失败！');			
				}
			});			
		});
	});
</script>
