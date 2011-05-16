<?php 
	if(!MB_admin){
		exit();
	}
	$verifyUser = new MBAdminVerify($host,$root,$pwd,$db);
	$rets = array("count"=>0,"list"=>array());
	$act = $_GET["act"];
	switch($act){
		case "search":
			$kw = $_GET["k"];
			if(empty($kw)){
				$rets = $verifyUser->get($p,10);
			}else{
				$rets = $verifyUser->search($kw,$p,10);
			}
			break;
		default:
			$rets = $verifyUser->get($p,10);
	}
?>
<form id="kForm" name="form1" method="GET" action="admin_verify.php" class="table_header">
<label for="vusername">搜索用户：</label><input type="hidden" value="1" name="cid"><input type="hidden" value="search" name="act"><input type="text" name="k" value="" style="width:340px;" class="txt" id="vusername"><input type="submit" value="搜索" class="buttonA">
<br><label></label><cite>请输入已认证用户的用户名</cite>
</form>
<div class="table_header"><strong>已认证的用户</strong></div>
<?if(count($rets["list"])>0){?>
<table width="96%" border="0" cellspacing="0" cellpadding="0" align="center" class="tableA">
  <tr>
    <th width="120">用户名</th>
    <th>认证信息</th>
    <th width="150">添加时间</th>
    <th width="100">操作</th>
  </tr>
  <?foreach($rets["list"] as $item){?>
  <tr>
    <td id="name"><?php echo htmlspecialchars($item['name'],ENT_QUOTES);?></td>
	<td id="desc"><?php echo htmlencode($item['desc']);?></td>
    <td><?php echo date('Y-m-d h:m:s', $item['time_add']);?></td>
    <td><a href="javascript:void(0);" rel="edit" rev="<?php echo $item["id"];?>">编辑</a> <a href="javascript:;" rel="delv" rev="admin_verify_act.php?act=del&id=<?php echo $item['id'];?>">取消认证</a></td>
  </tr>
 <?}?>
</table>
<?}else{?>
	<div class="nodata">还没有任何认证用户</div>
<?}?>
<?php
	$pages = new MBPage('admin_verify.php?cid=1',$rets["count"],$p,10);
	$pages->showpage();
?>
<script>
var cwin=new moduleObj();
cwin.autoResize(420,220);
cwin.hide();
function gotourl( v ){
	document.location.href=v;
}
$('a[rel=edit]').click(function(){
	var _this = $(this);
	var form = "<form id=\"form1\" name=\"form1\" method=\"post\" id=\"dataForm\" action=\"admin_verify_act.php?act=update\" class=\"table_header\">"
		+"<div style=\"overflow:hidden;\">"
		+"<label for=\"vusername\" style=\"display:block;width:80px;float:left;font-size:14px;\">微博帐号：<\/label>"
		+"<input type=\"hidden\" name=\"id\" value=\""
		+_this.attr("rev")
		+"\"/>"
		+"<input id=\"vusername\" type=\"text\" name=\"name\" value=\""
		+_this.parent().parent().find("#name").text()
		+"\"style=\"display:block;float:left;width:120px;\" class=\"txt\"\/>"
		+"<\/div>"
		+"<div style=\"clear:both;margin-top:20px;overflow:hidden;\">"
		+"<label for=\"vuserdesc\" style=\"display:block;float:left;width:80px;font-size:14px;\">认证信息：<\/label>"
		+"<textarea id=\"vuserdesc\" type=\"text\" name=\"desc\" style=\"display:block;float:left;width:273px;height:80px;\" class=\"txt\">"
		+_this.parent().parent().find("#desc").text()
		+"<\/textarea>"
		+"<\/div>"
		+"<div style=\"clear:both;margin-top:20px;margin-left:40px;\">"
		+"<input type=\"submit\" value=\"确定\" class=\"button\"\/>"
		+"&nbsp;&nbsp;&nbsp;&nbsp;"
		+"<input type=\"reset\" value=\"取消\" class=\"button closeBtn\"\/>"
		+"<\/div>"
		+"<\/form>";
	cwin.show({title:"修改认证信息",text:form});
});

$('a[rel=delv]').click(function(){
	var requrl = $(this).attr("rev");
	cwin.config('确定要删除此项?','gotourl(\"'+requrl+'\")');
})
</script>