<? if(!defined('ROOT')) exit('Access Denied');?>
<? include $this->gettpl('ilike_header');?>
    <div id="main">
    	<div id="left" class="">
            <div id="j" style="display:none;" class="pngfix"></div> 
        	<div class="loginfalse" style=" display:none">
                
                <a href="javascript:void(0);" id="loginbtn">
						<? if($lfrom=='tqq') { ?>
						<img src="<?=$templatepath?>/images/btn_login_tqq.png" />
								<? } else { ?>
		<img src="<?=$templatepath?>/images/sign-in-with-sina-32.png" />
		<? } ?>
				</a>
            </div>
            <div class="logintrue" style=" display:none">
                <div id="preimgdiv" >
                    <div id="preflag" class="pngfix"><span id="prescore"></span><font face="黑体">分</font></div> 
                    <img id="preimg"/>
                </div>            
                <div id="preintr">共有 <font id="prepnum" color="#ffffff">0</font> 次投票</div>
                <a id="uploadbtn" href="javascript:void(0);"></a>
            </div>
			<a id="uploadbtn2" href="javascript:void(0);" style="display:none;"></a>
        </div>
        <div id="content">
        	<div id="scorediv">
            	<a href="javascript:void(0);" id="jiong"></a>
                <a href="javascript:void(0);" id="s1" class="scorenum" title="给这张范儿评 1 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s2" class="scorenum" title="给这张范儿评 2 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s3" class="scorenum" title="给这张范儿评 3 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s4" class="scorenum" title="给这张范儿评 4 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s5" class="scorenum" title="给这张范儿评 5 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s6" class="scorenum" title="给这张范儿评 6 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s7" class="scorenum" title="给这张范儿评 7 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s8" class="scorenum" title="给这张范儿评 8 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s9" class="scorenum" title="给这张范儿评 9 分，并看下一张"></a>
                <a href="javascript:void(0);" id="s10" class="scorenum" title="给这张范儿评 10 分，并看下一张"></a>
				<a href="javascript:void(0);" id="shuai" class="f"></a>
            </div>
			<div id="filterdiv">范儿这么多，我要筛选：<select id="sel_sex"style="width:100px;"><option value="0">靓女、帅哥</option><option value="2">靓女</option><option value="1">帅哥</option></select>　　<b style="color:#9900ff">*点击上面打分看下一个范儿↑</b></div>
            <div id="photodiv"><!--<img src="<?=$templatepath?>/ilike_images/nophoto.jpg" id="photodiv_img"/>--></div>
            
            <div id="photobtn"><a href="javascript:void(0);" target="_blank" id="btn_watchta">查看TA的微博 » </a>　　<a href="javascript:void(0);" id="btn_followta">喜欢TA的范儿，我关注TA » </a>　　<a href="javascript:void(0);" id="btn_bury" title="这是三俗、非人的范儿">我要埋葬它 » </a></div>

				<a href="javascript:void(0)" id="notice_rate" title="点击上面的数字对该照片进行投票并看下一张" class="pngfix"></a>
	<a href="javascript:void(0)" id="notice_share" title="分享到你的微博" class="pngfix"></a>


            <div id="blackdiv"></div>
        </div>
        <div id="right">
        	<div id="nextimgdiv">
            	<div id="nextflag" class="pngfix"></div>
            	<img id="nextimg"/>
            </div>
        	
            <div id="msglist">
            	

            </div>
        </div>
		<div style="clear:both;"></div>
    </div>
    
<script type="text/javascript" src="<?=$templatepath?>/ilike_images/ilike.js?v=0.45"></script>
	<? include $this->gettpl('ilike_footer');?>