<? if(!defined('ROOT')) exit('Access Denied');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>微博IQ测试</title>
	<link type="text/css" rel="stylesheet" href="<?=$templatepath?>/iq_images/iq.css" />
<!--<script type="text/javascript" src="js/jquery.min.js"></script>-->
<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.5.2/jquery.min.js"></script>
<script>var op="<?=$op?>"; </script>
<script type="text/javascript" src="<?=$templatepath?>/iq_images/iq.js"></script>
</head>
<body>

	<div class="mainFrame">
		
		<? if($op=="login") { ?>
		<div class="ui-widget">
			
			<div class="login">
			<strong>　　IQ：</strong>就是常说的智商，与此对应的还有EQ、AQ、XQ...；
			<strong>微博IQ测试：</strong>就是常说的智商测试。特别提醒，现在是<font color="#33ff33">beta</font>版，本测试准确度有一定的误差！<br/>
				<!--<a href="?act=account&op=tologin" border="0"><img src="<?=$templatepath?>/images/login.png" alt="用微博帐号登录" /></a><br />-->
				<a id="div_follow" href="javascript:follow(0);"><img src="<?=$templatepath?>/iq_images/followiq.png" alt="关注官方微博"></a>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="微博IQ测试" /></a>
			</div>
		</div>
		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>欢迎使用微博IQ测试</strong>
				</div>
				<div class="welcomeDiv1" id="msg_list">

				</div>
			</div>
		</div>
		<div class="div_login">
			<a href="?act=account&op=tologin" border="0"><img src="<?=$templatepath?>/images/sign-in-with-sina-32.png" alt="用微博帐号登录" /></a>
		</div>

<? } elseif($op=="ready") { ?>

		<div class="ui-widget">
			
			<div class="login">
			<? if(is_array($account) ) { ?>
<font color="#ff3333"><?=$account['screen_name']?></font>, 你一共测试<?=$iqScore['testCount']?> 次， 最高IQ值是 <?=$iqScore['iq']?> , 全国排名第 <?=$iqScore['top']?> ,打败了全国 <?=$iqScore['win']?>% 的人!加油！！！<a href="?act=account&op=logout">退出</a> 
<? } else { ?>
<? } ?>
<br/><br/>
				<a id="div_follow" href="javascript:follow(0);"><img src="<?=$templatepath?>/iq_images/followiq.png" alt="关注官方微博"></a>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="微博IQ测试" /></a>
			</div>
		</div>
				<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div class="welcomeDiv1" id="msg_list">

				</div>
			</div>
		</div>
		<div class="contentFrame">
			<div class="ui-widget" style="text-align:center; color:#696a62;">
				<div>
					<strong>微博IQ测试约需25分钟，你准备好了吗？</strong>
				</div>
				<div class="welcomeDiv1">
					1。吃喝拉撒搞定了吗？（根据本站统计，人在三分饥的时候思维状态最佳）
				</div>
				<div class="welcomeDiv1">
					2。身边没有阿猫阿狗吧？如果有，请给足粮食，让它安静点！
				</div>
				<div class="welcomeDiv1">
					3。如果你测试结果不理想，请不要气馁，可以过一段时间再来一次（测试间隔最好在一个月以上）！
				</div>
				<div class="welcomeDiv1">
					4。如果结果显示你是天才，请不要窃喜，因为前面已有爱因思坦，后面也会有更聪明的人！
				</div>
			</div>
		</div>
		<div class="div_login">
		
			<button onclick="location.href='?app=iq&op=ican'">我 准 备 好 了</button>
		</div>

<? } elseif($op=="ican") { ?>

		<div class="ui-widget">			
			<div class="login">
				微博IQ测试须在30分钟内完成（33题）：<br/>
			<!--<input name="B1" onclick="startclock()" type="button" value="开始计时">--><span id="face">00:00</span>
			</div>
			
			<div class="logo">
				<a href="?app=iq" border="0"><img src="<?=$templatepath?>/iq_images/iq_logo.jpg" alt="微博IQ测试" /></a>
			</div>
		</div>		
		<div class="contentFrame" style="clear:both;text-align:center; ">
			<div class="ui-widget" style="text-align:center; color:#696a62; display:none;" id="ui-widget-result">
				<div>
					<strong>谢谢你参加智商测试! </strong><p>
					<strong>你的智商为:<span id="iq_val"></span>分  
您所用的时间：<span id="use_time"></span></strong></p>
				</div>
				<div class="welcomeDiv1" style="width:580px;margin-left:250px;">
					70-89  --智力低下 <br/>
				
					90-99  --智力中等<br/>
				
					100-109--智力中上<br/>
				
					110-119--智力优秀<br/>
				
					120-129--智力非常优秀<br/>
				
					130-139--智力非常非常优秀
				</div>
				<div class="welcomeDiv1" style="width:580px;margin-left:250px;font-weight:bold;">
				每个人的IQ是随着你的经历、学识的增长而进步的,<br/>
				我们也将收集更科学的题目，常回来看看!
				</div>
			</div>
			<div class="div_login" id="ui-widget-result-send" style="display:none;">
			
				<button onclick="sendmsg();">发到微博里作个纪念！</button> 
				<button onclick="location.href='?app=iq&op=ican'">我 再 测 试 一 次</button>
			</div>

			<div class="ui-widget" style="text-align:center; color:#696a62;" id="ui-widget-content">

<table style="BORDER-COLLAPSE: collapse" cellspacing="0" cellpadding="0" width="750" border="0" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0">
	<tr>
		<td height="10" >
		</form>
		<div align="center">
			<center>
			<table border="0" cellpadding="0" cellspacing="0" width="580">
				<tr>
					<td width="560">
					<form id="K">
						<p><font color="#000000">1.选出不同类的一项:<small><input name="q1" type="radio" value="V1"></small>A.蛇 
						<small><input name="q1" type="radio" value="V2"></small>B.大树 
						<small><input name="q1" type="radio" value="V3"></small>C.老虎</font></p>
						<p><font color="#000000">2.在下列分数中,选出不同类的一项:<small><input name="q2" type="radio" value="V1">
						</small>A.3/5 <small>
						<input name="q2" type="radio" value="V2"> </small>B.3/7<small>
						<input name="q2" type="radio" value="V3"> </small>C.3/9</font></p>
						<p><font color="#000000">3.男孩对男子,正如女孩对______.</font></p>
						<p><font color="#000000"><small>
						<input name="q3" type="radio" value="V1"></small>A.青年<small>　 
						<input name="q3" type="radio" value="V2"></small>B.孩子<small>　　<input name="q3" type="radio" value="V3"></small>C.夫人　<small><input name="q3" type="radio" value="V4"></small>D.姑娘<small>　<input name="q3" type="radio" value="V5"></small>E.妇女</font></p>
						<p><font color="#000000">4.如果笔相对于写字,那么书相对于______.</font></p>
						<p><font color="#000000"><small>
						<input name="q4" type="radio" value="V1"></small>A.娱乐　<small><input name="q4" type="radio" value="V2"></small>B.阅读 
						　<small><input name="q4" type="radio" value="V3"></small>C.学文化 
						　<small><input name="q4" type="radio" value="V4"></small>D.解除疲劳</font></p>
						<p><font color="#000000">5. 马之于马厩,正如人之于______.</font></p>
						<p><font color="#000000">
						<input name="q5" type="radio" value="V1">A.牛棚 
						<input name="q5" type="radio" value="V2">B.马车 
						<input name="q5" type="radio" value="V3">C.房屋 
						<input name="q5" type="radio" value="V4">D.农场 
						<input name="q5" type="radio" value="V5">E.楼房</font></p>
						<p><font color="#000000">6. &quot;2 8 14 20 ___&quot; 请写出 &quot;___&quot;处的数字<input name="T6" size="4"></font></p>
						<p><font color="#000000">7. 如果下列四个词可以组成一个正确的句子,就选是,否则选否.</font></p>
						<p><font color="#000000">　 生活 水里 鱼 在　 
						<input name="q7" type="radio" value="V1">A 是　<input name="q7" type="radio" value="V2">否</font></p>
						<p><font color="#000000">8. 如果下列六个词可以组成一个正确的句子,就选正确,否则选错误</font></p>
						<p><font color="#000000">　 球棒 的 用来 是 棒球 打　<input name="q8" type="radio" value="V1">A 
						是　<input name="q8" type="radio" value="V2">否</font></p>
						<p><font color="#000000">9. 动物学家与社会学家对应,正如动物与_____相对</font></p>
						<p><font color="#000000">
						<input name="q9" type="radio" value="V1">A.人类　<input name="q9" type="radio" value="V2">B.问题 
						<input name="q9" type="radio" value="V3">C.社会 
						<input name="q9" type="radio" value="V4">D.社会学</font></p>
						<p><font color="#000000">10.如果所有的妇女都有大衣,那么漂亮的妇女会有:</font></p>
						<p><font color="#000000">
						<input name="q10" type="radio" value="V1">A.给多的大衣 
						<input name="q10" type="radio" value="V2">B.时髦的大衣 
						<input name="q10" type="radio" value="V3">C.大衣 
						<input name="q10" type="radio" value="V4">D.昂贵的大衣</font></p>
						<p><font color="#000000">11. &quot;1 3 2 4 6 5 7 ___&quot; 请写出&quot;____&quot;处的数字<input name="T11" size="4"></font></p>
						<p><font color="#000000">12.南之于西北,正如西之于： 
						<input name="q12" type="radio" value="V1"> A.西北 
						<input name="q12" type="radio" value="V2">B.东北 
						<input name="q12" type="radio" value="V3">C.西南 
						<input name="q12" type="radio" value="V4">D.东南</font></p>
						<p><font color="#000000">13.找出不同类的一项:<input name="q13" type="radio" value="V1">A.铁锅 
						<input name="q13" type="radio" value="V2">B.小勺 
						<input name="q13" type="radio" value="V3">C.米饭 
						<input name="q13" type="radio" value="V4">D.碟子</font></p>
						<p><font color="#000000">14. &quot;9 7 8 6 7 5 ___&quot; 请写出&quot;___&quot;处的数字<input name="T14" size="4"></font></p>
						<p><font color="#000000">15.找出不同类的一项:<input name="q15" type="radio" value="V1">A写字台 
						<input name="q15" type="radio" value="V2">B.沙发 
						<input name="q15" type="radio" value="V3">C.电视 
						<input name="q15" type="radio" value="V4">D.桌布</font></p>
						<p><font color="#000000">16.右面的图中紧接的图形应是下面哪个:<img border="0" height="50" src="<?=$templatepath?>/iq_images/Iq16a-1.gif" width="150"></font></p>
						<div align="left">
							<table border="0" cellpadding="0" cellspacing="0" width="202">
								<tr>
									<td colspan="4" width="202">
									<font color="#000000">
									<img border="0" height="50" src="<?=$templatepath?>/iq_images/iq16b-1.gif" width="200"></font></td>
								</tr>
								<tr>
									<td width="50"><font color="#000000">
									<input name="q16" type="radio" value="V1">A</font></td>
									<td width="79"><font color="#000000">
									<input name="q16" type="radio" value="V2">B</font></td>
									<td width="37"><font color="#000000">
									<input name="q16" type="radio" value="V3">C</font></td>
									<td width="36"><font color="#000000">
									<input name="q16" type="radio" value="V4">D</font></td>
								</tr>
							</table>
						</div>
						<p><font color="#000000">17. 961 (25) 432</font></p>
						<p><font color="#000000">　　　932 (___) 731 请写出&quot;___&quot;处的数字<input name="T17" size="4"></font></p>
						<p><font color="#000000">18.选项A.B.C.D.中,哪项该填在 &quot;XOOOOXXOOOXXX&quot; 
						后面</font></p>
						<p><font color="#000000">
						<input name="q18" type="radio" value="V1">A.XOO
						<input name="q18" type="radio" value="V2">B.OOX
						<input name="q18" type="radio" value="V3">C.XOX
						<input name="q18" type="radio" value="V4">D.OXX</font></p>
						<p><font color="#000000">19.望子成龙的家长往往____苗助长</font></p>
						<p><font color="#000000">
						<input name="q19" type="radio" value="V1">A.揠 
						<input name="q19" type="radio" value="V2">B.堰 
						<input name="q19" type="radio" value="V3">C.偃</font></p>
						<p><font color="#000000">20.填上空缺的词</font></p>
						<p><font color="#000000">金黄的头发 (黄山) 刀山火海</font></p>
						<p><font color="#000000">赞美人生 (<input name="T20" size="4">) 
						卫国战争</font></p>
						<p><font color="#000000">21.选出不同类的一项:<input name="q21" type="radio" value="V1">A.地板 
						<input name="q21" type="radio" value="V2">B.壁橱 
						<input name="q21" type="radio" value="V3">C.窗户 
						<input name="q21" type="radio" value="V4">D.窗帘</font></p>
						<p><font color="#000000">22. &quot;1 8 27 ___&quot; 请写出&quot;___&quot;处的数字<input name="T22" size="4"></font></p>
						<p><font color="#000000">23.填上空缺的词</font></p>
						<p><font color="#000000">罄竹难书(书法)无法无天</font></p>
						<p><font color="#000000">作奸犯科(<input name="T23" size="4">)教学相长</font></p>
						<p><font color="#000000">24.在括号内填上一个字,使其与括号前的字组成一个词,同时又与括号后的字也能组成一个词:</font></p>
						<p><font color="#000000">款(<input name="T24" size="4">)样</font></p>
						<p><font color="#000000">25.填入空缺的字母</font></p>
						<p><font color="#000000">　　 B F K Q
						<input name="T25" size="4"></font></p>
						<p><font color="#000000">26.填入空缺的数字</font></p>
						<p><font color="#000000">　　16 (96) 12　　　 10 (<input name="T26" size="4">) 
						15</font></p>
						<p><font color="#000000">27.找出不同类的一项:</font></p>
						<p><font color="#000000">
						<input name="q27" type="radio" value="V1">A.斑马 
						<input name="q27" type="radio" value="V2">B.军马 
						<input name="q27" type="radio" value="V3">C.赛马 
						<input name="q27" type="radio" value="V4">D.骏马 
						<input name="q27" type="radio" value="V5">E.驸马</font></p>
						<p><font color="#000000">28.在括号内填上一个字,使其与括号前的字组成一个词,同时又与括号后的字也能组成一个词:</font></p>
						<p><font color="#000000">祭(<input name="T28" size="4">)定</font></p>
						<p><font color="#000000">29.在括号内填入一个字,使之既有前一个词的意思,又有后一个词的意思 
						顶部(<input name="T29" size="8">)震荡</font></p>
						<p><font color="#000000">30.填入空缺的数字 65 37 17 (<input name="T30" size="4">)</font></p>
						<p><font color="#000000">31.填入空缺的数字 41 (28) 27 83(<input name="T31" size="4">) 
						65</font></p>
						<p><font color="#000000">32.在abcd四个图形中选出可以填入右边&quot;？&quot;处的一个<img border="0" height="50" src="<?=$templatepath?>/iq_images/iq32a-1.gif" width="200"></font></p>
						<div align="left">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><font color="#000000">
									<input name="q32" type="radio" value="V1"></font></td>
									<td><font color="#000000">
									<input name="q32" type="radio" value="V2"></font></td>
									<td><font color="#000000">
									<input name="q32" type="radio" value="V3"></font></td>
									<td><font color="#000000">
									<input name="q32" type="radio" value="V4"></font></td>
								</tr>
								<tr>
									<td colspan="4"><font color="#000000">
									<img border="0" height="50" src="<?=$templatepath?>/iq_images/iq32b-1.gif" width="200"></font></td>
								</tr>
							</table>
						</div>
						<p><font color="#000000">33.填上空缺的字母</font></p>
						<p><font color="#000000">　　C F I　 　　D H L　　 　 E J (<input name="T33" size="2">)</font></p>
						<div align="center">
							<p>
							<button name="B1" onclick="calculate();return false;" >查看我的得分（IQ值）</button></p>
						</div>
					</form>
					</td>
				</tr>
			</table>
			</center></div>
		</td>
	</tr>
</table>
			</div>
</div>
<? } ?>

		<div class="bottom">
			<span>Copy &copy; 2011 微博IQ测试 版权所有 - Developed By 
				<a href="http://t.sina.com.cn/5d13" target="_blank">5d13</a>
			</span>
			<div>
				<a href="http://v.t.sina.com.cn/share/share.php?source=bookmark&title=对@孙铭鸿 (官方微博)说:" target="_blank">意见反馈</a>
			</div>
			<div>
	   			<a href="http://sae.sina.com.cn" target="_blank"><img src="http://static.sae.sina.com.cn/image/poweredby/poweredby.png" title="Powered by Sina App Engine" /></a>
	   		</div>
	    </div>

</body>
</html>