<?php
	if(!MB_admin){
		exit();
	}
	$recomm = new MBAdminRecomm($host,$root,$pwd,$db);
	$rets = $recomm->showTopic($p,10);
	$mods = $recomm->getMod(1);
	$modname = $mods['list'][0]['name'];
	$select = (int) $_SELECT['select'];
	@include_once('data/recommtopic.php');
	if(!is_array($recommtopic)){
		$recommtopic = array(
				'count'=>0,
				'list' => array()
			);
	}elseif(!is_array($recommtopic['list'])){
		$recommtopic = array(
				'count'=>0,
				'list' => array()
			);
	}
?>
	<form id="modForm" name="modform" method="post" action="admin_recomm_act.php?a=mod" class="table_header">
		<div class="mods">
			<label>模块名称：</label><input type="hidden" name="mp" value="1" /><input type="text" id="modName" maxlength="20" value="<?php echo htmlencode($modname);?>" name="mn" style="width:280px;" class="txt"/>
			<input type="submit" value="确定" class="buttonA"/>
		</div>
		<div><cite>请设置希望在广播大厅中显示的模块名称，点击确定后生效。</cite></div>
	</form>
	<div class="table_header">
		<strong>已设置话题</strong>
		<cite>已设置的话题如下表显示，加粗部分为当前生效的话题。</cite>
		<span><input type="button" value="新建话题" class="buttonA" id="newModule"/></span>
	</div>
	<?php if($rets['count']>0){?>
	<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
	  <tr>
		<th>话题</th>
		<th width="150">开始时间</th>
		<th width="150">结束时间</th>
		<th width="100">添加人员</th>
		<th width="100">操作</th>
	  </tr>
	  <?foreach($rets['list'] as $key => $b):?>
	  <tr <?if(in_array($b['tit'],$recommtopic['list']) ){$select++;$_SESSION['select']=$select;echo 'style="font-weight:600;"';}?>>
		<td><?php echo htmlencode($b['tit'])?></td>
		<td><?php echo date('Y-m-d H:i', $b['st'])?></td>
		<td><?php echo date('Y-m-d H:i', $b['et'])?></td>
		<td><?php echo htmlencode($b['uname'])?></td>
		<td><a href="javascript:void(0);" rel="edit" rev="<?php echo $key;?>">编辑</a> <a href="javascript:void(0);" rel="del" rev="<?php echo $b['id'];?>">删除</a></td>
	  </tr>
	  <?endforeach?>
	</table>
	<?php }else{?>
		<div class="nodata">目前还没有推荐话题！</div>
	<?php }?>
<?php
	$pages = new MBPage('admin_recomm.php?cid='.$cindex,$rets['count'],$p,10);
	$pages->showpage();
?>
<script type="text/javascript" >
	var _mlength = <?php echo count($rets['list']);?>;
	function delobj(i){
		$.post('admin_recomm_act.php?a=ht&m=del',{id:i},function(d){
			eval('r='+d);
			if(r){
				cwin.alert('操作成功！');
			}else{
				cwin.alert('操作成功！');
			}
		});	
	}	
	function crTime(o,d){
		var _d = new Date(d*1000);
		var _y = _d.getFullYear();
		var _m = _d.getMonth()+1;
		var _day = _d.getDate();
		var _hh = _d.getHours();
		var _mm = _d.getMinutes();
		if(_m<10){
			_m = '0'+_m;	
		}
		if(_day<10){
			_day='0'+_day;
		}
		if(_hh<10){
			_hh = '0' + _hh;
		}
		if(_mm<10){
			_mm = '0' + _mm;
		}
		o.val(_y+'-'+_m+'-'+_day+' '+ _hh+':'+_mm);
	}
	var _data = [];	
	<?php if($rets['count']>0){?>
		<?php foreach($rets['list'] as $key => $b){?>
		_data.push({
			'id': <?php echo $b['id'];?>,
			'tit': '<?php echo htmlencode($b['tit']);?>',
			'st': <?php echo $b['st'];?>,
			'et': <?php echo $b['et'];?>
		});
		<?php }?>
	<?php }?>

	var _html = [
		'<form id="dataForm" name="form1" method="post" action="admin_recomm_act.php?a=ht&m=add">',
		'	<ul class="form">',
		'		<li><strong>话题名：</strong><input id="htTit" type="text" maxlength="20" name="t" style="width:140px;" class="txt"/><input type="hidden" value="" name="id" id="htId" /></li>',
		'		<li><strong>生效时间：</strong><input type="text" name="s1" maxlength="20" style="width:140px;" id="sDate" class="txt"/><input type="hidden" value="" name="s" id="sT" /></li>',
		'		<li><strong>结束时间：</strong><input id="eDate" maxlength="20" type="text" name="e1" style="width:140px;" class="txt"/><input type="hidden" value="" name="e" id="eT" /></li>',
		'		<li><strong></strong>时间格式为2011-01-01 08:30</li>',
		'		<li><strong></strong><input type="submit" value="确定" class="button"/> <input type="reset" value="取消" class="button closeBtn"/></li>',		
		'</form>'
	];

	var cwin=new moduleObj();
	cwin.autoResize(420,200);
	cwin.hide();

	$('#modName').keyup(function(){
		var _t = $(this).val();
		if(_t.length>20){
			$(this).val(_t.substring(0,20));	
		}
	});

	$.validator.addMethod("time",function(value,element,params){
		if (new RegExp(/^[\d]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d{1}|3[0-1])[\s]{1}([0-1]\d|2[0-4])[:：][0-5]\d$/).test(value) ){
			var _t = value.split(' ');
			var _td = _t[0].split('-');
			if(_td[0]>1970 && _td[0] < 2035){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}	
	},"时间格式错误");
	$.validator.addMethod("endtime",function(value,element,params){
		$("#eDate").blur();
		var _s = parseInt($('#sT').val());
		var _e = parseInt($('#eT').val());
		if(_s<_e){
			return true;	
		}else{
			return false;
		}
	},'不可早于生效时间');

	function getTime(value){
		var _t = value.split(' ');
		var _td = _t[0].split('-');
		var _ts = _t[1].split(':');
		var _year = parseInt(_td[0]);
		var _m = parseInt(_td[1]);
		var _day = parseInt(_td[2]);
		if(_m == 2){
			if(_year%1000==0 && _year%400==0 && _day>29){
				_day = 29;	
			}else if(_year%4==0 && _year%100 !=0 && _day>29){
				_day = 29;	
			}else if(_day>28){
				_day = 28;
			}	
		}else if(_m == 4 || _m == 6 || _m == 9 || _m == 11){
			if(_day>30){
				_day = 30;
			}	
		}
		var _tt = new Date(_td[0],_td[1]-1,_day,_ts[0],_ts[1]);
		var _st = Date.parse(_tt);
		return _st;	
	}

	$("#sDate").live('blur',function(){
		var _v = $(this).val();
		_v = _v.replace('：',':');
		if (new RegExp(/^[\d]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d{1}|3[0-1])[\s]{1}([0-1]\d|2[0-4])[:：][0-5]\d$/).test(_v) ){
			$('#sT').val(getTime(_v)/1000);
		}
	});

	$("#eDate").live('blur',function(){
		var _v = $(this).val();
		_v = _v.replace('：',':');
		if (new RegExp(/^[\d]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d{1}|3[0-1])[\s]{1}([0-1]\d|2[0-4])[:：][0-5]\d$/).test(_v) ){
			$('#eT').val(getTime(_v)/1000);
		}
	});
	
	$('#modForm').validate({
			errorPlacement: function(label, element) {
				label.appendTo(element.parent())
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

	$("#newModule").click(function(){
		if($('#dataForm').length == 0){
			cwin.show({title:"添加话题",text:_html.join('')});
			$('#dataForm').validate({
					rules:{
						t:{
							required:true,
							maxlength:20
						},
						s1:{
							required:true,
							time:true
						},
						e1:{
							required:true,
							time:true,
							endtime:true
						}
					},
					messages:{
						t:{
							required:'请填写话题名',
							maxlength:'话题名不能超过20个字'
						},
						s1:{
							required:'请填写开始时间'
						},
						e1:{
							required:'请填写结束时间'
						}
					}
				});			
		}else{
			$("#dataForm").attr('action','admin_recomm_act.php?a=ht&m=add');
			$('#dataForm label.error').hide();
			$('#htTit').val('');	
			$('#htId').val('');
			$('#sDate').val('');
			$('#eDate').val('');
			$('#sT').val('');
			$('#eT').val('');
			cwin.show();
		}
	});

	$('a[rel=edit]').click(function(){
		var no = $(this).attr('rev');
		if($('#dataForm').length == 0){
			cwin.show({title:"修改话题",text:_html.join('')});
			$("#dataForm").attr('action','admin_recomm_act.php?a=ht&m=edit');
			$('#htTit').val(_data[no].tit);	
			$('#htId').val(_data[no].id);
			$('#sT').val(_data[no].st);
			$('#eT').val(_data[no].et);
			crTime($('#sDate'),_data[no].st);
			crTime($('#eDate'),_data[no].et);
			$('#dataForm').validate({
					rules:{
						t:{
							required:true,
							maxlength:20
						},
						s1:{
							required:true,
							time:true
						},
						e1:{
							required:true,
							time:true,
							endtime:true
						}
					},
					messages:{
						t:{
							required:'请填写话题名',
							maxlength:'话题名不能超过20个字'
						},
						s1:{
							required:'请填写开始时间'
						},
						e1:{
							required:'请填写结束时间'
						}						
					}
				});			
		}else{
			cwin.show({title:"修改话题"});
			$("#dataForm").attr('action','admin_recomm_act.php?a=ht&m=edit');
			$('#htTit').val(_data[no].tit);	
			$('#htId').val(_data[no].id);
			$('#sT').val(_data[no].st);
			$('#eT').val(_data[no].et);
			
			crTime($('#sDate'),_data[no].st);
			crTime($('#eDate'),_data[no].et);
			$('#addArea').fadeIn("slow");				
		}		

	});

	$('a[rel=del]').click(function(){
		var no = $(this).attr('rev');
		cwin.config('确定要删除此项?','delobj('+no+')');			
	});	


</script>
