<?php
	if(!MB_admin){
		exit();
	}
	if($kw!=''){
		$param = array(
			'k'=>$kw,
			'n'=>30,
			'p'=>$kp,
			'type' => 0	
		);
		$srets = $api->getSearch($param);
	}
	$filter = new MBAdminFilter($host,$root,$pwd,$db);
	$rets = $filter->showUser($p,10);
?>
<form id="form1" name="form1" method="get" action="admin_filter.php?cid=3" class="table_header">
<label>搜索用户：</label><input type="hidden" value="3" name="cid" /><input type="text" name="k" style="width:340px;" class="txt"/><input type="submit" value="搜索" class="buttonA"/>
<br/><cite>请输入用户姓名或帐号</cite>
</form>
<div class="sline"></div>
<?if($srets['ret']==0 && $kw != ''):?>
	<div class="table_header"><strong>搜索结果</strong></div>
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
	  <tr>
		<th width="100">帐号</th>
		<th width="100">昵称</th>
		<th>最新微博</th>
		<th width="100">听众数</th>
		<th width="100">操作</th>
	  </tr>
	<?foreach($srets['data']['info'] as $b):?>
	<tr>
		<td><?php echo htmlencode($b['name']);?></td>
		<td><?php echo htmlencode($b['nick']);?></td>
		<td><?php echo htmlencode($b['text']);?></td>
		<td><?php echo $b['fansnum'];?></td>
		<td><a href="http://t.qq.com/<?php echo htmlencode($b['name']);?>" target="blank">查看用户</a> <a href="javascript:void(0)" rel="fel" rev="<?php echo htmlencode($b['name']);?>">屏蔽</a></td>
	</tr>
	<?endforeach?>
	</table>
	<div class="sline"></div>
	<div class="pageinfo">
		<?if($kp>0):?>
			<a href="admin_filter.php?cid=3&k=<?php echo htmlencode($kw);?>&kp=<?php echo $kp-1;?>">上一页</a>
		<?endif?>
		<?if($kp<$allkp):?>
			<a href="admin_filter.php?cid=3&k=<?php echo htmlencode($kw);?>&kp=<?php echo $kp+1;?>">下一页</a>
		<?endif?>
	</div>
<?endif?>

<?if($rets['count']>0):?>
<div class="table_header"><strong>黑名单用户</strong></div>
<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
  <tr>
    <th width="100">账号</th>
    <th>昵称</th>
    <th width="100">添加人员</th>
    <th width="100">添加时间</th>
    <th width="100">操作</th>
  </tr>
	<?foreach($rets['list'] as $key => $b):?>
	<tr>
		<td><?php echo htmlencode($b['name']);?></td>
		<td><?php echo htmlencode($b['nick']);?></td>
		<td><?php echo htmlencode($b['oname']);?></td>
		<td><?php echo date('Y-m-d', $b['at']);?></td>
		<td><a href="http://t.qq.com/<?php echo htmlencode($b['name']);?>" target="blank"> <a href="javascript:void(0);" rel="del" rev="<?php echo $b['id'];?>">删除</a></td>
	</tr>
	<?endforeach?>
</table>
<?else:?>
	<div class="nodata">还没有屏蔽微博</div>
<?endif?>
<?php
	$pages = new MBPage('admin_recomm.php?cid='.$cindex,$rets['count'],$p,10);
	$pages->showpage();
?>
<form id="dataForm" name="form1" method="post" action="admin_filter_act.php?a=u&m=add" class="table_header" style="display:none">
		<input type="text" id="uHead" name="head" /><input type="text" id="uName" name="name" /><input type="text" name="nick" id="uNick" /><input type="hidden" value="" name="id" id="kId" />
</form>
<script type="text/javascript">
function delobj(i){
	$.post('admin_filter_act.php?a=u&m=del',{id:i},function(d){
		eval('r='+d);
		if(r){
			mwin.alert('操作成功！');
		}else{
			mwin.alert('操作成功！');
		}		
	});
}
$(function(){
	var mwin=new moduleObj();
	mwin.autoResize(220,60);
	mwin.hide();	
	<?if($srets['ret']==0 && $kw != ''):?>
		var _sData = [];
		<?foreach($srets['data']['info'] as $key => $b):?>
		_sData['<?php echo htmlencode($b['name']);?>'] = {
			'name':'<?php echo htmlencode($b['name']);?>',
			'nick':'<?php echo htmlencode($b['nick']);?>',
			'head':'<?php echo htmlencode($b['head']);?>'
		};	
		<?endforeach?>
		$('a[rel=fel]').click(function(){
			var no = $(this).attr('rev');	
			$('#uName').val(_sData[no].name);	
			$('#uNick').val(_sData[no].nick);	
			$('#uHead').val(_sData[no].head);
			$('#dataForm').submit();	
		});
	<?endif?>
	$('a[rel=del]').click(function(){
		var no = $(this).attr('rev');		
		cwin.config('确定要删除此项?','delobj('+no+')');			
	});			
});
</script>
