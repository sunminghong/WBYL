<? if(!defined('ROOT')) exit('Access Denied');?>
   
	<div id="footer">
		<span class="toplink">您还可以玩：
<a href="http://ciniao.me/wbapp/?tindex=app.80.index&app=app.80.intr&from=sina" target="_blank">我的80后</a>
<a href="http://q.5d13.cn/eq" target="_blank">情商EQ测试</a>
<a href="http://q.5d13.cn/iq" target="_blank">智商IQ测试</a>
<a href="http://ciniao.me/wbapp/?a=xm&from=sina" target="_blank">羡慕嫉妒恨</a>
</span>

	</div>
	<div id="copyright">
		<img style="margin-top:12px;" src="<?=$templatepath?>/ilike_images/logo_55.gif" title="看看我的范儿的LOGO" border="0"/>
			Copy © 2011  版权所有 - Developed By <a target="_blank" style="white-space:nowrap" href="<?=$orgwbsite?>">@孙铭鸿</a>
		<? if($lfrom=='tqq') { ?>
		<a href="http://v.t.qq.com/share/share.php?source=bookmark&amp;title=对@孙铭鸿 (官方微博)说:" target="_blank">意见反馈</a>　
		<? } else { ?>
		<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&amp;title=对@孙铭鸿 (官方微博)说:" target="_blank">意见反馈</a>　
		<? } ?>
		<a href="http://www.cnzz.com/stat/website.php?web_id=3130104" target="_blank">cnzz流量统计</a>
    </div>

	<div id="mask" class="transparent_class" style="display:none;"></div>
	<div id="uploaddiv" style="display:none;">
    			<a href="javascript:void(0);" id="colseupload"><img src="<?=$templatepath?>/ilike_images/closebtn.gif" /></a>
		
		<form id="uploadform" action="index.php?app=ilike&op=ijoin" method="post" name="form1" target="uploadiframe" enctype="multipart/form-data" style="margin:0px;padding:0px;display:none;" onsubmit="return submitupload();">
			<p style="margin-top:15px;border:1px solid #00f;" id="div_upload_file"><b style="font-size:24px;font-family:Georgia, 'Times New Roman', Times, serif;">1.</b>选择你的范儿：<input id="in_uploadfile" name="uploadfile" type="file" /></p>
			<p><b style="font-size:24px;font-family:Georgia, 'Times New Roman', Times, serif;">2.</b>拉票宣言：(发布后会将范儿及宣言同步到微博！)</p>
			<p><textarea name="content" id="xuanyan" cols="" rows="">我刚上传了一张范儿，欢迎你来#看看我的范儿#！</textarea></p>
			<p><input id="up_iffollow" name="iffollow" type="checkbox" value="1"/> 关注#看看我的范儿#官方微博 @孙铭鸿  <i></i></p>
			<p><a href="javascript:void(0);" id="submitdo"></a></p>
		</form>
		<div id="div_uploading" style="margin-top:115px;margin-left:20px;display1:none;text-align:center;"><img src="<?=$templatepath?>/ilike_images/lightbox_loading.gif"/></div>
	<iframe name="uploadiframe" style="display:none;"></iframe>
    </div>
	<div id="logindiv" style="display:none;">
		<iframe id="loginiframe" frameborder="0" name="loginiframe" style="width: 100%; height:395px;" border="0"></iframe>
		<div class="logindiv_bottom"><a href="javascript:void(0);" id="btn_closelogin"> 关 闭 </a></div>
	</div>

	<div id="div_share" style="display:none;">
		<div id="div_share_content">
   			<a href="javascript:void(0);" id="colseshare"><img src="<?=$templatepath?>/ilike_images/closebtn.gif" /></a>
			<p><textarea name="content" id="div_share_msg" cols="" rows="">我刚看到了一个#范儿#，特分享给大家，你可以到#看看我的范儿#给他评分，看更多的#范儿#照片！</textarea></p>
			<p><input id="iffollow" type="checkbox" value="1" /> 关注#看看我的范儿#官方微博 @孙铭鸿  <i></i></p>
			<p><input id="is_comment" type="checkbox" value="1" checked="true"/> <span id="div_share_byname">同时发表评论</span></p>
			<p><a href="javascript:void(0);" id="btn_share"></a></p>
			</div>
			<div id="div_shareing" style="margin-top:115px;margin-left:20px;display1:none;text-align:center;"><img src="<?=$templatepath?>/ilike_images/lightbox_loading.gif"/></div>
    </div>

	<div style="display:none;"><script src="http://s22.cnzz.com/stat.php?id=3130104&web_id=3130104" language="JavaScript"></script></div>
</body>
</html>