<?php
	if(!MB_admin){
		exit();
	}
	$recomm = new MBAdminRecomm($host,$root,$pwd,$db);
	$rets = $recomm->showrUser($p,10);
?>
<form id="form1" name="form1" method="post" action="" class="table_header">
<label>模块名称：</label><input type="text" style="width:340px;" class="txt"/> 
	每屏显示人数：<select><option>10</option></select>
<input type="submit" value="确定" class="buttonA"/>
</form>
<div class="sline"></div>
<div class="table_header"><strong>热门转播内容</strong><span><input type="submit" value="添加转播" class="buttonA" id="newModule"/></span></div>
<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
  <tr>
    <th>转播次数</th>
    <th>微博内容</th>
    <th>排序</th>
    <th>状态</th>
    <th>操作</th>
  </tr>
  <tr>
    <td>54663</td>
    <td>即日起参加腾讯网抢票活动，分享圣诞趣事，发微博进入话题#常石磊圣诞之约#，就有机会成为演唱会的现场观众，亲耳聆听天籁般的声音！<a href="http://t.qq.com" target="_blank">http://url.cn/3hZUca</a></td>
    <td>1</td>
    <td>未显示</td>
    <td><a href="#">查看微博</a> <a href="#">屏蔽</a></td>
  </tr>
</table>
<?php
	$pages = new MBPage('admin_recomm.php?cid='.$cindex,$rets['count'],$p,10);
	$pages->showpage();
?>
<script type="text/javascript">
$(function(){
	var _html = [
		'<form id="dataForm" name="form1" method="post" action="admin_recomm_act.php?a=ru&m=add" class="table_header">',
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
			$("#dataForm").attr('action','admin_recomm_act.php?a=ru&m=add');
			$("#uName").val('');	
			$('#uUrl').val('');
			$('#uInfo').val('');
			$('#uId').val('');			
			cwin.show();
		}
	});
});
</script>
