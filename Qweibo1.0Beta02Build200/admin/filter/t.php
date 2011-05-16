<?php
	if(!MB_admin){
		exit();
	}
	if($kw!=''){
		$param = array(
			'k'=>$kw,
			'n'=>30,
			'p'=>$kp+1,
			'type' => 1	
		);
		try{
			$srets = $api->getSearch($param);
			$total = $srets['data']['totalnum'];
			$allkp = ceil($total/30);
		}catch(MBException $e){
			$srets = array('ret'=>4);	
		}
	}
	$filter = new MBAdminFilter($host,$root,$pwd,$db);
	$rets = $filter->showT($p,10);
	$thishost = $_SERVER['HTTP_HOST'];
?>
<form id="kForm" name="form1" method="GET" action="admin_filter.php?cid=1" class="table_header">
<label>搜索微博：</label><input type="hidden" value="1" name="cid" /><input type="text" name="k" value="<?php echo htmlencode($kw);?>" style="width:340px;" class="txt"/><input type="submit" value="搜索" class="buttonA"/>
<br/><label></label><cite>请输入微博内容查询</cite>
</form>
<div class="sline"></div>
<?if($srets['ret']==0 && is_array($srets['data']) && $kw != ''):?>
<div class="table_header"><strong>搜索结果</strong></div>
<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
  <tr>
    <th width="100">微博ID</th>
    <th>微博内容</th>
    <th width="100">转播次数</th>
    <th width="100">发表时间</th>
    <th width="100">操作</th>
  </tr>
  <?foreach($srets['data']['info'] as $b):?>
  <tr>
    <td><?php echo $b['id'];?></td>
	<td><?php echo htmlencode($b['text']);?></td>
	<td><?php echo $b['count'];?></td>
    <td><?php echo date('Y-m-d', $b['timestamp']);?></td>
	<td><a href="http://<?php echo $thishost;?>/index.php?m=showt&tid=<?php echo $b['id'];?>" target="blank">查看微博</a> <a href="javascript:void(0)" rel="fel" rev="<?php echo $b['id'];?>">屏蔽</a></td>
  </tr>
  <?endforeach?>	
</table>
<div class="sline"></div>
<div class="pageinfo">
	<?if($kp>0):?>
		<a href="admin_filter.php?cid=1&k=<?php echo htmlencode($kw);?>&kp=<?php echo $kp-1;?>">上一页</a>
	<?endif?>
	<?if($kp<$allkp-1):?>
		<a href="admin_filter.php?cid=1&k=<?php echo htmlencode($kw);?>&kp=<?php echo $kp+1;?>">下一页</a>
	<?endif?>
</div>
<?elseif($kw!=''):?>
	<div class="nodata">未搜索到对应的微博</div>
<?endif?>
<?if($rets['count']>0):?>
	<div class="table_header"><strong>已屏蔽的微博</strong></div>
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
	  <tr>
		<th width="120">ID</th>
		<th>微博内容</th>
		<th width="100">添加人</th>
		<th width="100">添加时间</th>
		<th width="100">操作</th>
	  </tr>
	<?foreach($rets['list'] as $key => $b):?>
	  <tr>
	  <td><?php echo $b['tid'];?></td>
		<td><?php echo htmlencode($b['text']);?></td>
		<td><?php echo htmlencode($b['oname']);?></td>
		<td><?php echo date('Y-m-d', $b['at']);?></td>
		<td><a href="/index.php?m=showt&tid=<?php echo $b['tid'];?>" target="_blank">查看微博</a> <a href="javascript:void(0);" rel="del" rev="<?php echo $b['id'];?>">删除</a></td>
	  </tr>
	<?endforeach?>	
	</table>
<?else:?>
	<div class="nodata">尚未屏蔽任何微博</div>
<?endif?>
<?php
	$pages = new MBPage('admin_filter.php?cid='.$cindex,$rets['count'],$p,10);
	$pages->showpage();
?>
<form id="dataForm" name="form1" method="post" action="admin_filter_act.php?a=t&m=add" class="table_header" style="display:none">
	<ul class="form">
		<li><strong>微博ID：</strong><input type="text" id="tid" name="tid" /></li>
		<li><strong>微博内容：</strong><input type="text" name="info" id="tInfo" /><br/><input type="hidden" value="" name="id" id="kId" /><strong></strong></li>
	</ul>
</form>
<script type="text/javascript">
	var _mlength = <?php echo count($rets['list']);?>;
	function delobj(i){
		$.post('admin_filter_act.php?a=t&m=del',{id:i},function(d){
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
							required:'请输入要搜索的内容'
						}
					}
				});	

	var mwin=new moduleObj();
	mwin.autoResize(220,60);
	mwin.hide();	
	<?if($srets['ret']==0 && is_array($srets['data']) && $kw != ''):?>
		var _sData = [];
		<?foreach($srets['data']['info'] as $key => $b):?>
		_sData[<?php echo $b['id'];?>] = {
			'id':<?php echo $b['id'];?>,
			'text':'<?php echo htmlencode($b['text']);?>'
		};	
		<?endforeach?>
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
			'info': '<?php echo htmlencode($b['info']);?>'
		};
		<?endforeach?>	
	<?endif?>	

	$('a[rel=del]').click(function(){
		var no = $(this).attr('rev');		
		mwin.config('确定要解除屏蔽此项？','delobj('+no+')');			
	});			
</script>
