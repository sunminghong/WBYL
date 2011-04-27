<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>
<? include $this->gettpl('accountlist');?>
<!--发布区-->
<div class="d_write">
	<div class="d_wordNumBg" id="write_info">你还可以输入<span>140</span>字</div> 
	<div class="d_inputmsg"><textarea name="msg" id="msg"></textarea></div>
    <div class="d_toolers">
        <span class="pic"><a href="#">图片</a> 

        </span> 
        <span class="tit" id="write_video">
        	<a href="javascript:void(0);" >多媒体</a> 
        </span>
        <span class="tit" id="write_topic"> 
        	<a href="javascript:void(0);" >话题</a> 
        </span>
    </div> 
    <div class="d_btn"><!-- 不可点状态<div class="postBtnBg bgColorA_No"> --> 
        <a href="javascript:void(0);" id="write_submit"> 发 布 </a> 
    </div> 
    
    <!--图片上传用-->
    <form target="Upfiler_file_iframe" action="http://picupload.t.sina.com.cn/interface/pic_upload.php?marks=1&amp;markstr=t.sina.com.cn/5d13&amp;s=rdxt&amp;app=miniblog&amp;cb=http://t.sina.com.cn/upimgback.html" id="write_image_form" enctype="multipart/form-data" method="POST" style="display:none;"> 
    	<input type="file" name="pic1" id="write_file" /> 
    </form>
    <iframe name="Upfiler_file_iframe" style="display:none;width:0px;height:0px;" src="about:blank"></iframe>    
    
    <div style="display:none;" class="layerPicBg" id="write_imgpreview">
        
        <table class="fb_img"> 
            <tr> 
                <td> 
                </td> 
                <td class="j_bg"> 
                </td> 
                <td> 
                </td> 
            </tr> 
            <tr> 
                <td class="t_l"> 
                </td> 
                <td class="t_c"> 
                </td> 
                <td class="t_r"> 
                </td> 
            </tr> 
            <tr> 
                <td class="c_l"> 
                </td> 
                <td id="write_preimage" class="c_c"> 
                    <img src="http://static14.photo.sina.com.cn/small/4ba9e0b3t790489c3987d&amp;690"/> 
                </td> 
                <td class="c_r"> 
                </td> 
            </tr> 
            <tr> 
                <td class="b_l"> 
                </td> 
                <td class="b_c"> 
                </td> 
                <td class="b_r"> 
                </td> 
            </tr> 
        </table> 
    </div> 
     <!--/图片上传用-->
</div> 
<!--/发布区 --> 
<div class="cl"> &nbsp; </div>
<!--帐号标签切换区-->
<div class="d_tab">
    <ul>
<? foreach((array)$accounts as $keyid => $api) {?>
	<li onclick="switchTab('<?=$keyid?>');" ondbclick="list('<?=$keyid?>');" id="tab_<?=$keyid?>"><?=$api['name']?>【<?=$api['lfromname']?>】</li>
<?}?>

    </ul>
</div>
<!--/帐号标签切换区-->
<div class="cl"> &nbsp; </div>

<!--主工作区-->
<div id="main"> 
<? foreach((array)$accounts as $keyid => $api) {?>
	<div id="list_<?=$keyid?>" class="d_st_list"></div>
<?}?>    
</div>
<!--/主工作区-->

<script>
var islist=[];
var lastuid=null;
function g$(id) {return document.getElementById(id);}

function switchTab(uid){
	if (lastuid) {
		g$('tab_'+lastuid).className="";
		$('#list_'+lastuid).hide();
	}
	if($('#list_'+uid).html()<10)
		list(uid);
	else
		$('#list_'+uid).show();
		
	lastuid=uid;
	 g$('tab_'+uid).className="sel";	
}

function list(uid){
	if(islist[uid])return;
	islist[uid]=true;
	$.get("?app=home&act=my&op=home_timeline&kuid="+uid,function(res){
		var ph=[];
		eval('res=('+res+')');
		var rl=res.length;
		for(var i=0;i<rl;i++){
			var st=res[i];
			ph.push('<div class="d_message">\
			<div class="content">\
				<span class="author"><a href="#">'+ st.user.screen_name +'</a></span>:');
				
			ph.push(st.text);
			
			if (st.thumbnail_pic)
				ph.push('			<br/>【<a target="_blank" href="'+st.original_pic+'">查原图片</a>】');
				
			ph.push('</div>');
			
			if (st.retweeted_status){
				var ret=st.retweeted_status;
				ph.push('\
			<div class="d_comments">\
				<div class="comm_content">\
					<span class="author"><a href="#">'+ret.user.screen_name+'</a></span>:'+ret.text+'\
				<br>');
				if (ret.thumbnail_pic)
				ph.push('			【<a target="_blank" href="'+ret.original_pic+'">查原图片</a>】&nbsp;&nbsp;&nbsp;');
				ph.push(ret.created_at+' 发自'+ret.source+'\
				</div>\
				</div>');
			}
				
			ph.push('			<div class="other">\
				<div class="d_retime">\
				.'+st.created_at+' 发自'+st.source+'页\
				</div>\
				<div class="d_btn">\
				 <a href="#">对话</a> | <a href="#">转播</a> | <a href="#">★</a> | <a href="#d_top">Top</a>    \
				</div>\
			</div>\
			<div class="cl"> &nbsp; </div>\
		</div>');
		}
		//alert(ph.join(''));
		$('#list_'+uid).html(ph.join('')).show();
		islist[uid]=false;
	});	
}

$(document).ready(function(){
	var uid=$("input[name='kuid']")[0].value;
	if(uid) {alert(uid);
		switchTab(uid);
		list(uid);
	}
});
</script>
</body>
</html>
