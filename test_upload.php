
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="/weiboyule/js/jquery.MultiFile.pack.js"></script>
<style>
	.pic_sel {color:#ccc;}
</style>
<script language="JavaScript" type="text/javascript">
$(document).ready(function(){    
	$(".btn_upload").click(function (){
			$("#fileupload"+$(this).attr("id").replace("btn_upload_","")).click();
	});
	
	
	/*$('#PortugueseFileUpload').MultiFile({
	accept:'gif|jpg|png', max:3, STRING: {
		remove:'删除',
		selected:'Selecionado: $file',
		denied:'不能选择 $ext 格式的图片！',
		duplicate:'请不要重复选择：\n$file!'
	}
	});*/
});

</script>
</head>
<body>
<form action="index.php?app=ilike&op=ijoin" method="post" name="form1" enctype="multipart/form-data">
<textarea name="content" style="width:80%;height:100px;">刚在#选美#上发了一张照片，看看我自己有没有人喜欢，呵呵！请朋友们给我投票！
</textarea>
<br/>
<input type="text" id="pic_path_1"><a href="#" class="btn_upload" id="btn_upload_1">上传照片</a><br/>


<input style1="height:0px;overflow:hidden;" type="file" name="uploadfile" id="fileupload1" class="fileupload"/>

<input type="submit" value="提交" name="btnsub" />
</form>
</body>
</html>