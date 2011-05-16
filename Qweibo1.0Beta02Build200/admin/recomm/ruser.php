<?php
	if(!MB_admin){
		exit();
	}
	$kw= $_GET['k'];
	if($kw!=''){
		$param = array(
			'n'=>$kw
		);
		try{
			$srets = $api->getUserInfo($param);
		}catch(MBException $e){
			$srets = array('ret'=>4);	
		}
	}
	$recomm = new MBAdminRecomm($host,$root,$pwd,$db);
	$rets = $recomm->showrUser($p,10);
	$mods = $recomm->getMod(3);
	$modname = $mods['list'][0]['name'];
	$pnum = $mods['list'][0]['p2'];
	$thishost = $_SERVER['HTTP_HOST'];
?>
<div class="mainB">
	<form id="modForm" name="modform" method="post" action="admin_recomm_act.php?a=mod" class="table_header">
		<div class="mods">
			<label>模块名称：</label><input type="hidden" name="mp" value="3" /><input  id="modName" maxlength="20"  type="text" value="<?php echo htmlencode($modname);?>" name="mn" style="width:280px;" class="txt"/>
				每屏显示人数：<select name="pnum">
					<option value="3" <?if($pnum==3):?>selected="selected"<?endif?>>3</option>
					<option value="6" <?if($pnum==6):?>selected="selected"<?endif?>>6</option>
					<option value="9" <?if($pnum==9):?>selected="selected"<?endif?>>9</option>
					<option value="12" <?if($pnum==12):?>selected="selected"<?endif?>>12</option>
					<option value="15" <?if($pnum==15):?>selected="selected"<?endif?>>15</option>
					<option value="18" <?if($pnum==18):?>selected="selected"<?endif?>>18</option>
					<option value="21" <?if($pnum==21):?>selected="selected"<?endif?>>21</option>
					<option value="24" <?if($pnum==24):?>selected="selected"<?endif?>>24</option>
					<option value="27" <?if($pnum==27):?>selected="selected"<?endif?>>27</option>
					<option value="30" <?if($pnum==30):?>selected="selected"<?endif?>>30</option>
				</select>
			<input type="submit" value="确定" class="buttonA"/>
		</div>
		<div><cite>请设置在广播大厅中显示的模块名称，及推荐的用户数，将按照如下列表中排序显示。</cite></div>
	</form>
	<div class="sline"></div>
	<form id="form2" name="form2" method="get" action="admin_recomm.php" class="table_header" >
	<label>搜索用户：</label><input type="hidden" value="2" name="cid" /><input type="text" name="k" value="<?php echo htmlencode($kw);?>" style="width:340px;" class="txt" id="rUserk" maxlength="30" /><input type="submit" value="搜索" class="buttonA"/>
		<div><cite>请输入要推荐的用户帐号，选择搜索结果列表中的用户进行推荐</cite></div>
	</form>
	<div class="sline"></div>
	<?if($srets['ret']==0 && $kw != ''):?>
		<div class="table_header"><strong>搜索结果</strong></div>
		<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
		  <tr>
			<th>帐号</th>
			<th>姓名</th>
			<th width="100">性别</th>
			<th width="100">消息数</th>
			<th width="120">操作</th>
		  </tr>
		<tr>
			<td><?php echo htmlencode($srets['data']['name']);?></td>
			<td><?php echo htmlencode($srets['data']['nick']);?></td>
			<td><? if($srets['data']['sex']==1){echo '男';}elseif($srets['data']['sex']==2){echo '女';}else{echo '未知';}?></td>
			<td><?php echo $srets['data']['tweetnum'];?></td>
			<td><a href="/index.php?m=guest&u=<?php echo htmlencode($srets['data']['name']);?>" target="blank">查看用户</a> <a href="javascript:void(0)" id="newModule" rel="fel">推荐用户</a></td>
		</tr>
		</table>
		<div class="sline"></div>
	<?elseif($kw != ''):?>
		<div class="nodata">未搜索到对应用户，建议变更关键字重新搜索</div>
		<div class="sline"></div>
	<?endif?>
	<div class="table_header"><strong>推荐用户</strong></div>
<?if($rets['count']>0):?>	
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
	  <tr>
		<th width="100">帐号</th>
		<th>微博地址</th>
		<th>用户介绍</th>
		<th width="80">添加人员</th>
		<th width="80">添加时间</th>
		<th width="100">操作</th>
	  </tr>
	  <?foreach($rets['list'] as $key => $b):?>
	  <tr>
		<td><?php echo htmlencode($b['uname']);?></td>
		<td><a href="http://<?php echo $thishost;?>/index.php?m=guest&u=<?php echo htmlencode($b['uname']);?>" target="_blank"><?php echo $b['url'];?></a></td>
		<td><?php echo htmlencode($b['info']);?></td>
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
	<div class="nodata">尚未设置推荐用户，请点击“搜索”按钮，搜索要推荐用户设置推荐。</div>
<?endif?>
</div>
<script type="text/javascript" >
	var _mlength = <?php echo count($rets['list']);?>;
function delobj(i){
	$.post('admin_recomm_act.php?a=ru&m=del',{id:i},function(d){
		eval('r='+d);
		if(r){
			cwin.alert('操作成功！');
		}else{
			cwin.alert('操作成功！');
		}
	});
}
	$('#rUserk').keyup(function(){
		var _t = $(this).val();	
		if(_t.length>30){
			$(this).val(_t.substring(0,30));	
		}
	});

	$('#modName').keyup(function(){
		var _t = $(this).val();	
		if(_t.length>20){
			$(this).val(_t.substring(0,20));	
		}
	});

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

	$('#form2').validate({
			rules:{
				k:{
					required:true,
					maxlength:30
				}
			},
			messages:{
				k:{
					required:'请填写用户帐号',
					maxlength:'请控制在30字符内'
				}
			}
		});			

	var _html = [
		'<form id="dataForm" name="form1" method="post" action="admin_recomm_act.php?a=ru&m=add" >',
		'	<ul class="form">',
		'		<li><strong>用户帐号：</strong><span id="sName"></span><input type="hidden" name="uname" id="uName"><input type="hidden" maxlength="20" value="" name="uid" id="uId" /></li>',
		'		<li><strong>微博地址：</strong><span id="sUrl"></span><input type="hidden" name="url" id="uUrl" maxlength="200"></li>',
		'		<li><p><strong style="vertical-align:top;">用户介绍：</strong><textarea rows="5" name="info" id="uInfo"></textarea></p><p><strong>        </strong><cite style="margin:0">用户介绍最多可输入80个汉字</cite></p><input name="sort" value="0" type="hidden" /></li>',		
		'		<li><strong></strong><input type="submit" value="确定" class="button"/> <input type="reset" value="取消" class="button closeBtn1"/></li>',
		'	</ul>',
		'</form>'
	];

	var cwin=new moduleObj();
	cwin.autoResize(420,300);
	cwin.hide();	
	$("#newModule").click(function(){
		if($('#dataForm').length == 0){
			cwin.show({title:"添加推荐",text:_html.join('')});
			$('#sName').html('<?php echo htmlencode($srets['data']['name']);?>');	
			$('#sUrl').html('http://<?php echo $thishost;?>/<?php echo htmlencode($srets['data']['name']);?>');
			$('#uName').val('<?php echo htmlencode($srets['data']['name']);?>');	
			$('#uUrl').val('http://<?php echo $thishost;?>/<?php echo htmlencode($srets['data']['name']);?>');
			$('#dataForm').validate({
					errorPlacement: function(label, element) {
						if(element.attr('name')=='info'){
							label.appendTo(element.parent().next('p'));
						}
					},
					rules:{
						info:{
							maxlength:80
						}
					},
					messages:{
						info:{
							maxlength:'请控制在80字符内'
						}
					}
				});			
		}else{
			$("#dataForm").attr('action','admin_recomm_act.php?a=ru&m=add');
			$('#dataForm label.error').hide();
			$('#sName').html('<?php echo htmlencode($srets['data']['name']);?>');	
			$('#sUrl').html('http://<?php echo $thishost;?>/<?php echo htmlencode($srets['data']['name']);?>');
			$('#uName').val('<?php echo htmlencode($srets['data']['name']);?>');	
			$('#uUrl').val('http://<?php echo $thishost;?>/<?php echo htmlencode($srets['data']['name']);?>');
			$('#uInfo').val('');
			$('#uId').val('');			
			cwin.show();
		}
	});

	$('a[rel=edit]').click(function(){
		var no = $(this).attr('rev');
		if($('#dataForm').length == 0){
			cwin.show({title:"修改推荐",text:_html.join('')});
			$("#dataForm").attr('action','admin_recomm_act.php?a=ru&m=edit');
			$('#sName').html(_data[no].uname);	
			$('#sUrl').html(_data[no].url);
			$("#uName").val(_data[no].uname);	
			$('#uUrl').val(_data[no].url);
			$('#uInfo').val(_data[no].info);
			$('#uId').val(_data[no].id);			
			$('#dataForm').validate({
					errorPlacement: function(label, element) {
						if(element.attr('name')=='info'){
							label.appendTo(element.parent().next('p'));
						}
					},
					rules:{
						uname:{
							required:true,
							maxlength:20
						},
						url:{
							required:true,
							maxlength:200
						},
						info:{
							maxlength:80
						}
					},
					messages:{
						uname:{
							required:'请填写用户姓名',
							maxlength:'请控制在20字符内'
						},
						url:{
							required:'请填写微博地址',
							maxlength:'请控制在200字符内'
						},
						info:{
							maxlength:'请控制在80字符内'
						}
					}
				});			
		}else{
			cwin.show({title:"修改推荐"});
			$('#dataForm label.error').hide();
			$("#dataForm").attr('action','admin_recomm_act.php?a=ru&m=edit');
			$('#sName').html(_data[no].uname);	
			$('#sUrl').html(_data[no].url);
			$("#uName").val(_data[no].uname);	
			$('#uUrl').val(_data[no].url);
			$('#uInfo').val(_data[no].info);
			$('#uId').val(_data[no].id);				
		}
	});
	$('a[rel=del]').click(function(){
		var no = $(this).attr('rev');		
		cwin.config('确定要删除此项?','delobj('+no+')');			
	});			
</script>
