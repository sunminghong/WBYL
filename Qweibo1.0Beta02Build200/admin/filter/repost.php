<?php
	if(!MB_admin){
		exit();
	}
	if($kw!=''){
		$param = array(
			'id' =>  $kw	
		);
		try{
			$srets = $api->getOne($param);
			$da = $srets['data'];
		}catch(MBException $e){
			$srets = array('ret'=>4);	
		}
	}
	$filter = new MBAdminFilter($host,$root,$pwd,$db);
	$rets = $filter->showRepost($p,10);
	$thishost = $_SERVER['HTTP_HOST'];
?>
<form id="kForm" name="form1" method="get" action="admin_filter.php?cid=2" class="table_header">
	<label>搜索微博对应点评：</label><input type="hidden" value="2" name="cid" /><input type="text" value="<?php echo htmlencode($kw);?>" name="k" style="width:340px;" class="txt"/><input type="submit" value="搜索" class="buttonA"/>
	<br/><cite style="margin-left:110px;">请输入要屏蔽的点评id <a href="javascript:void(0);" id="getRepost">如何获取点评ID？</a></cite>
	<div id="repostShow" style="display:none">
		<p>打开查看微博转播页面，点击转播发布时间，查看转播页面url末尾ID，或鼠标停留在转播理由/点评消息发布时间显示区域，页面左下角信息可见点评ID。</p>
		<img src="../style/images/admin/ex.png" />
	</div>
</form>
<?if($kw!='' && $srets['ret']==0 && $da['type'] == 7):?>
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
		<tr>
			<th width="100">点评ID</th>
			<th>点评内容</th>
			<th width="100">发表时间</th>
			<th width="100">操作</th>
		</tr>
  		
		  <tr>
			<td><?php echo $da['id'];?></td>
			<td><?php echo $da['origtext'];?></td>
			<td><?php echo date('Y-m-d', $da['timestamp']);?></td>
			<td><a href="http://<?php echo $thishost;?>/index.php?m=showt&tid=<?php echo $da['id'];?>" target="_blank">查看微博</a> <a href="javascript:void(0)" rel="fel" rev="<?php echo $da['id'];?>">屏蔽</a></td>
		  </tr>
	</table>
<?elseif($kw!=''):?>
	<div class="nodata">未搜索到对应的点评</div>
<?endif?>
<div class="sline"></div>
<div class="table_header"><strong>已屏蔽的点评</strong></div>
<?if($rets['count']>0):?>
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
	  <tr>
		<th width="100">点评ID</th>
		<th>点评内容</th>
		<th width="100">添加人员</th>
		<th width="100">添加时间</th>
		<th width="100">操作</th>
	  </tr>
		<?foreach($rets['list'] as $key => $b):?>
		<tr>
			<td><?php echo $b['tid'];?></td>
			<td><?php echo $b['text'];?></td>
			<td><?php echo htmlencode($b['oname']);?></td>
			<td><?php echo date('Y-m-d', $b['at']);?></td>
			<td><a href="javascript:void(0);" rel="del" rev="<?php echo $b['id'];?>">删除</a></td>
		</tr>
		<?endforeach?>
	</table>
<?else:?>
	<div class="nodata">还没有屏蔽点评</div>
<?endif?>
<?php
	$pages = new MBPage('admin_filter.php?cid='.$cindex,$rets['count'],$p,10);
	$pages->showpage();
?>
<form id="dataForm" name="form1" method="post" action="admin_filter_act.php?a=r&m=add" class="table_header" style="display:none">
	<ul class="form">
		<li><strong>微博ID：</strong><input type="text" id="tid" name="tid" /></li>
		<li><strong>微博内容：</strong><input type="text" name="info" id="tInfo" /><br/><input type="hidden" value="" name="id" id="kId" /><strong></strong></li>
	</ul>
</form>
<script type="text/javascript">
	var _mlength = <?php echo count($rets['list']);?>;
function delobj(i){
	$.post('admin_filter_act.php?a=r&m=del',{id:i},function(d){
		eval('r='+d);
		if(r){
			mwin.alert('操作成功！');
		}else{
			mwin.alert('操作成功！');
		}			
	});
}	
			$('#kForm').validate({
					errorPlacement: function(label, element) {
						label.insertAfter(element.next('input'))
					},
					rules:{
						k:{required:true}
					},
					messages:{
						k:{
							required:'请输入要搜索的点评ID'
						}
					}
				});	


	$('#getRepost').toggle(function(){
		$('#repostShow').slideDown(200);
	},function(){
		$('#repostShow').slideUp(200);
	});

	var mwin=new moduleObj();
	mwin.autoResize(220,60);
	mwin.hide();		
	<?if($srets['ret']==0 && $kw != ''):?>
		var _sData = [];

		_sData[<?php echo $da['id'];?>] = {
			'id':<?php echo $da['id'];?>,
			'text':'<?php echo htmlencode($da['text']);?>'
		};	
		$('a[rel=fel]').click(function(){
			var no = $(this).attr('rev');	
			$('#tid').val(_sData[no].id);	
			$('#tInfo').val(_sData[no].text);
			$('#dataForm').submit();	
		});
	<?endif?>		
	_data = [];
	<?if($rets['count']>0):?>	
		<?foreach($rets['list'] as $key => $b):?>
		_data[<?php echo $b['id'];?>] = {
			'id': <?php echo $b['id'];?>,
			'info': '<?php echo $b['info'];?>'
		};
		<?endforeach?>	
	<?endif?>	

	$('a[rel=del]').click(function(){
		var no = $(this).attr('rev');		
		mwin.config('确定要解除屏蔽此项？','delobj('+no+')');			
	});
</script>
