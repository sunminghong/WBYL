<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-11-20 01:40
 * Filename: header.php
 * Description: 顶部导航条(logo,用户工具栏,页面导航栏,搜索栏)
******************************************************************************/
?>
		<?php if(isset($user)){ ?>
			<div class="usernavwrapper">
			<div class="usernav">
				<a href="./index.php?m=index"><?php echo $user["nick"];?></a>
				<a href="./index.php?m=userinfo">设置</a>
				<a href="./index.php?m=logout">退出</a>
			</div>
			</div>
		<?php } ?>
		<div class="header">
			<div class="logo">
				<a href="./"><?php echo $sitename;?></a>
			</div>
			<div class="pagenav">
				<a class="bigger" href="./index.php?m=index">我的主页</a>
				<a class="bigger" href="./index.php?m=public">广播大厅</a>
				<?php
				//<a class="smaller" href="#">找人</a>
				//<a class="smaller" href="#">话题</a>
				?>
			</div>
			<?php if($enablesearch){ ?>
			<div class="search">
				<form name="searchForm" id="searchForm" class="searchForm" method="get" action="./index.php">
					<fieldset class="searchField">
						<input type="hidden" name="m" value="searchall"></input>
						<input type="text"  class="searchKey" maxlength="50" name="k" />
						<button class="searchBtn" id="searchBtn">搜索</button>
					</fieldset>
				</form>
			</div>
			<?php } ?>
			<div class="navarrow"></div>
		</div>

