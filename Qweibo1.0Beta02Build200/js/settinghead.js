function checkFile( obj ){
	var extname = obj.value.split("."),
		extname = extname[extname.length-1],
		validextname = {jpg:'',jpeg:'',gif:'',png:''};
	if (extname.toLowerCase() in validextname)
	{
		document.getElementById('submitbtn').style.display= "block";
		return true;
	}
	else 
	{
		document.getElementById('submitbtn').style.display= "none";
		alert('请选择jpg、jpeg、gif、png格式的图片');
	    return false;
	}
}
function saveform(){
	$("#form_upload").submit();
}