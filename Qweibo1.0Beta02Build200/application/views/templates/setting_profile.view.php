<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-12-02 20:04
 * Filename: setting_profile.view.php
 * Extend: base_1col.view.php
 * Description: 设置个人资料
 * 继承的变量:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 新声明的变量:
 * 
 * 变量列表:
 * $title,$user,$sitename,$lang,$arrowPosition,$extrahead,$content
 * 
 * 已使用的变量:
 * 
 * 子模板可用变量: 
 * 
******************************************************************************/
ob_start();
?>
<script src="./js/cityall.js"></script>
<script src="./js/relatedSelection.js"></script>
<script src="./js/settingprofile.js"></script>
<script src="./js/richMsgBox.js"></script>
<script>
<?php if(array_key_exists("country_code",$user) && array_key_exists("province_code",$user) && array_key_exists("city_code",$user)){ ?>
	var initselect = {value:"?><?php echo $user["country_code"];?>><?php echo $user["province_code"]=="0"?"?":$user["province_code"];?>><?php echo $user["city_code"]=="0"?"?":$user["city_code"];?>"};
<?php }else{ ?>
	var initselect = {value:"?>1>11>1"};
<?php } ?>
var citylist = new relatedSelection(cityall,initselect);
<?php if( isset($message) && !empty( $message["text"] ) ){ ?>
		$(function(){
			$.richAlertBox("<?php echo $message["text"];?>","<?php echo $message["type"];?>").show();
		});
<?php } ?>
</script>
<?php
$extrahead = ob_get_contents();
ob_end_clean();
ob_start();
?>
<div class="shezhimain">
	<div class="tabbar">
		<ul class="tabs">
			<li class="tab tab88x33 active">个人资料</li>
			<li class="tab tab88x33"><a href="./index.php?m=userhead">修改头像</a></li>
		</ul>
	</div>
	<?php 
		/*
		 if( isset($message) ){ 
			echo "<div class=\"settingheadmsg ".$message["type"]."message\">".$message["text"]."</div>";
		}
		*/
	?>
	<form action="./index.php?m=userinfo" method="post" id="setForm">
		<table border="0" cellspacing="0" cellpadding="0" class="tbList">
		<tbody>
		<tr>
			<th>帐号:</th>
			<td class="userId fs14"><?php echo $user["name"] ?>
			<!--<a href="https://password.qq.com" target="_blank" class="password">修改密码&gt;&gt;</a>-->
			</td>
		</tr>
		<tr>
			<th>
				<span class="red">*</span>昵称:
			</th>
			<td>
				<input type="text" name="nick" id="nick" autocomplete="off" class="inputTxt" value="<?php echo $formuser["nick"]?>">
				<span class="error">
				<b id="nickpass"></b>
				<?php if(array_key_exists("nick",$formerror)){ ?>
					<div id="nicktips" class="error">仅支持中文、字母、数字、下划线或减号</div>
				<?php }else{ ?>
					<div id="nicktips" class="gray">1-12个中文、字母、数字、下划线或减号</div>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>性别:</th>
			<td>
				<input name="sex" id="gender" type="radio" value="2" <?php echo $formuser["sex"]==2?"checked":""; ?>>&nbsp;<label for="gender" class="fs14">女</label>&nbsp;&nbsp;
				<input name="sex" id="gender2" type="radio" value="1" <?php echo $formuser["sex"]==1?"checked":""; ?>>&nbsp;<label for="gender2" class="fs14">男</label>
				<?php if( isset($formerror) && array_key_exists("sex",$formerror) ){ ?>
					<span class="error"><?php echo $formerror["sex"]?></span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th><span class="red">*</span>生日:</th>
			<td>
				<select name="year" id="year">
				<?php
					$yearArray = range(1900,2010);
					foreach($yearArray as $year)
					{
						$selected = ($formuser['birth_year']==$year or (!$formuser['birth_year'] and $year==1990))?"selected":"";
						echo "<option value=\"{$year}\" {$selected}>{$year}年</option>";
					}
				?>
				</select>
				<select name="month" id="month">
				<?php
					$monthArray = range(1,12);
					foreach($monthArray as $month)
					{
						$selected = ($formuser['birth_month']==$month or (!$formuser['birth_month'] and $month==1))?"selected":"";
						echo "<option value=\"{$month}\" {$selected}>{$month}月</option>";
					}
				?>
				</select>
				<select name="day" id="day">
				<?php
					$dayArray = range(1,31);
					foreach($dayArray as $day)
					{
						$selected = ($formuser['birth_day']==$day or (!$formuser['birth_day'] and $day==1))?"selected":"";
						echo "<option value=\"{$day}\" {$selected}>{$day}日</option>";
					}
				?>
				</select>
				<?php if( isset($formerror) && array_key_exists("birthday",$formerror) ){ ?>
					<span class="error"><?php echo $formerror["birthday"]?></span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th>所在地:</th>
			<td>
				<span id="citySelect">
				<script type="text/javascript">document.write(citylist.getHTML())</script>
				</span>
				<?php if( isset($formerror) && array_key_exists("location",$formerror) ){ ?>
					<span class="error"><?php echo $formerror["location"]?></span>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th>个人介绍:</th>
			<td>
			<textarea name="introduction" id="summary" class="inputArea"><?php if(isset($formuser) && array_key_exists("introduction",$formuser)){echo $formuser["introduction"];}?></textarea>
			<?php if( isset($formerror) && array_key_exists("introduction",$formerror) ){ ?>
					<span class="error"><?php echo $formerror["introduction"]?></span>
			<?php }else{ ?>
			<span class="gray" id="introtips">140字以内</span></td>
			<?php } ?>
		</tr>
		<tr>
			<th></th>
			<td><a class="saveBtn" id="submitbtn" onclick="return saveform();">保&nbsp;存</a></td>
		</tr>
		</tbody></table>
		</form>
</div>
<?php
	$content = ob_get_contents();
	ob_end_clean();
	require_once pathJoin( TEMPLATE_DIR,'base_1col.view.php' );
?>

