<? if(!defined('ROOT')) exit('Access Denied');?>
<!--帐列表区开始-->
<div id="a_list">
	<ul>
    <? foreach((array)$accounts as $keyid => $api) {?>
        <li><label><input name="kuid" type="checkbox" checked value="<?=$keyid?>"/><a href="?app=&act=my&op=index&kuid=<?=$keyid?>" target="_blank"><?=$api['name']?></a>【<?=$api['lfromname']?>】</label></li>
    <?}?>
<li><a href="index.php?app=&act=account&op=tologin&lfrom=tsina">新浪微博登录</a></li>
    </ul>
</div>
<!--帐列表区结束-->
