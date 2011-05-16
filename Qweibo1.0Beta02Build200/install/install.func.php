<?php
function docs_support(&$fs) {
	$s="";
        foreach($fs as $key => $item) {
                $road = $item['path'];
            if (file_exists($road))
            {
            	if(is_writeable($road))
            	{$item["current"]="可读写";$item["status"]=2;}
            	else
            	{$item["current"]="只读";$item["status"]=1;}
            }
            else
            {
            	$item["current"]="不存在";
            	$item["status"]=0;
            }
            $s.="<tr><td>".$road."</td>";
            $s.="<td>".$item['current']."</td>";
        	if ($item['status']>=$item['minpower'])
        	{$s.="<td class=\"c\"><img src=\"../style/images/admin/0.gif\" class=\"yes\"/></td>";}
        	else
        	{$s.="<td class=\"c\"><img src=\"../style/images/admin/0.gif\" class=\"no\"/></td>";}
        	$s.="</tr>";
        }
        return $s;
}

function surrounding_support(&$p) {
        foreach($p as $key => $item) {
        		$p[$key]['status'] = 1;
                if($key == 'php') {
                        $p[$key]['current'] = PHP_VERSION;
						if ($p[$key]['current']<4.3)
						{$p[$key]['status'] = 0;}
                } elseif($key == 'attachmentupload') {
                        $p[$key]['current'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : '未知';
                } elseif($key == 'gdversion') {
                        $tmp = function_exists('gd_info') ? gd_info() : array();
                        $p[$key]['current'] = empty($tmp['GD Version']) ? '不存在' : $tmp['GD Version'];
                        unset($tmp);
                        if($p[$key]['current']=="不存在")
                        {$p[$key]['status'] = 0;}
                } elseif($key == 'diskspace') {
                        if(function_exists('disk_free_space')) {
                        	$diskSize=disk_free_space(ROOT_PATH);
                        	if(floor($diskSize / (1024*1024))>=10)
                        	{
                        		if (floor($diskSize / (1024*1024))>=1024)
	                        	{$p[$key]['current'] = floor($diskSize / (1024*1024*1024)).'G';}
	                        	else
	                            {$p[$key]['current'] = floor($diskSize / (1024*1024)).'M';}
	                            $p[$key]['status'] = 1;
                        	}else
                        	{	if(floor($diskSize /1024)==0)
                        		{$p[$key]['current'] = "小于1K";}
                        		else
                        		{$p[$key]['current'] = floor($diskSize /1024).'K';}
                        		$p[$key]['status'] = 0;
                        	}
                        } else {
                                $p[$key]['current'] = '未知';
								$p[$key]['status'] = 2;
                        }
                } elseif(isset($item['c'])) {
                        $p[$key]['current'] =constant($item['c']);
                }

                if($item['r'] != '不限制' &&$key != 'diskspace'&&$key!='gdversion'&&strcmp($p[$key]['current'], $item['r']) < 0) {
                        $p[$key]['status'] = 0;
                }
        }
     $env_str="";
     foreach($p as $key => $item) {
     $wstr="";
     if($item['current']=='未知')
     {$wstr="<img src=\"images/alert.gif\" valign=\"middle\" title=\"参数无法检测，继续安装可能会有问题\"/>";}
     $env_str .= "<tr>\n";
	    $env_str .= "<td>".$item['p']."</td>\n";
	    $env_str .= "<td>".$item['r']."</td>\n";
	    $env_str .= "<td>".$item['b']."</td>\n";
	    $env_str .= "<td>".$item['current']."</td>\n";
	    if($p[$key]['status']==0) {
	    $env_str.="<td class=\"c\"><img src=\"../style/images/admin/0.gif\" class=\"no\" alt=\"".$p[$key]['status']."\"/></td>";
	    }
	    else
	    {$env_str.="<td class=\"c\"><img src=\"../style/images/admin/0.gif\" class=\"yes\" alt=\"".$p[$key]['status']."\" ".($wstr==""?"":"style=\"display:none\"")."/>".$wstr."</td>";}
	    $env_str .= "</tr>\n";
      }
     return $env_str;
}

function function_support(&$func_items)
{$func_str="";
	foreach($func_items as $item) {
                        $status = function_exists($item);
                        $func_str .= "<tr>\n";
                        if(preg_match("/openssl/",$item))
                        {$func_str .= "<td>$item()";
                         if(!$status)
                         {$func_str .="<a href=\"http://www.soso.com/q?w=%C8%E7%BA%CE%20%D4%DAPHP%C0%A9%D5%B9%C0%EF%20%B4%F2%BF%AA%20openssl%D6%A7%B3%D6\" target=\"_blank\" style=\"color:#f50\">在PHP扩展里打开openssl支持可以解决此问题</a>";}
                         $func_str .="</td>\n";
                        }else if($item=="mb_strlen")
                        {$func_str .= "<td>$item()";
                        	if(!$status)
                         {
                          $func_str .="<a href=\"http://www.soso.com/q?pid=s.idx&w=PHP+%B4%F2%BF%AA%C0%A9%D5%B9extension%3Dphp_mbstring
\" target=\"_blank\" style=\"color:#f50\">需要在PHP扩展里打开扩展extension=php_mbstring</a>";}
                         $func_str .="</td>\n";
                        }
                        else{$func_str .= "<td>$item()</td>\n";}
                        if($status) {
                                $func_str .= "<td>支持</td>\n";
                                $func_str .= "<td class=\"c\"><img src=\"../style/images/admin/0.gif\" class=\"yes\"/></td>\n";
                        } else {
                                $func_str .= "<td>不支持</td>\n";
                                $func_str .= "<td class=\"c\"><img src=\"../style/images/admin/0.gif\" class=\"no\"/></td>\n";
                        }
                        $func_str.="</tr>";
                }
  return $func_str;
}

function dir_writeable($dir) {//可读写判断
        $writeable = 0;
        if(!is_dir($dir)) {
                @mkdir($dir, 0777);
        }
        if(is_dir($dir)) {
        	$fp = @fopen("$dir/qq.txt", 'wb');
                if($fp) {
                @fclose($fp);
                @unlink("$dir/qq.txt");
                $writeable = 1;
                } 
                else {$writeable = 0;}
        }
        return $writeable;
}

function SaveSiteInfo()
{	//保存站点信息
	$site_name=addslashes($_POST["site_name"]);
	$site_description=addslashes($_POST["site_description"]);
	$app_key=addslashes($_POST["app_key"]);
	$app_secret=addslashes($_POST["app_secret"]);
	$f=fopen(USER_CONFIG,'wb');
	$row="<?php\ndefine(\"__WIN__\", ".(constant("PHP_OS")=="WINNT"?"true":"false").");\n";
	$row.="define(\"MB_SITE_NAME\",\"".$site_name."\");\n";
	$row.="define(\"MB_SITE_DESCRIPTION\",\"".$site_description."\");\n";
	$row.="define(\"MB_AKEY\",\"".$app_key."\");\n";
	$row.="define(\"MB_SKEY\",\"".$app_secret."\");";
	$row.="\n?>";
	fwrite($f,$row,strlen($row));
	fclose($f);
	return true;
}

function SaveDataBaseInfo(&$o)
{//保存数据库配置信息
if (file_exists(USER_CONFIG))
{	
	$str=file_get_contents(USER_CONFIG);
	$str=trim(str_replace("?>","",str_replace("<?php","",$str)))."\n";
	foreach($o as $key => $item)
	{
	 if (preg_match("/\"MB_".strtoupper($key)."\"/",$str))
	 {
	 	$str=preg_replace("/\\\"MB_".strtoupper($key)."\\\",\\\"[\\s\\S]*?\\\"/","\"MB_".strtoupper($key)."\",\"".$item."\"",$str);
	 }
	 else
	 {$str.="define(\"MB_".strtoupper($key)."\",\"".$item."\");\n";}
	}
	$str="<?php\n".$str."?>";
	$f=fopen(USER_CONFIG,'w');
	fwrite($f,$str,strlen($str));
	fclose($f);
	logMsg("正在写入系统配置...\n");
	return true;
}
else
{logMsg("系统配置信息写入失败\n");
 return false;
}
}

function runquery($sql,$db,$man,$mysqlLink) {//执行sql语句
$errorcode=0;$sqlrow=0;
	if($mysqlLink)
	{	if($db["database_ctype"]==0&&!mysql_select_db($db["database_name"],$mysqlLink))
		{logMsg("您指定的数据库“".$db["database_name"]."”不存在，安装失败！\n");
		 SetInstallInfo(false);
		 redirect("step4.html?action=fail&ret=6");
		 return false;
		 }
		mysql_query("set names utf8");
		try{logMsg("正在执行sql语句进行批量建表...\n");		
			foreach(explode(";\n", trim($sql)) as $sqli)
			{if($sqlrow<2&&$db["database_ctype"]==0)
				{logMsg("database_ctype:".$db["database_ctype"].",".$sqli);$sqlrow++;continue;}
			 
			 mysql_query($sqli);//logMsg($sqli);
			 $errorcode+=mysql_errno();
			 if ($errorcode!=0)
			 {
			 logMsg("对不起，您提供的数据库用户名操作权限不足！\n");
			 logMsg("错误码：".$errorcode);
			 SetInstallInfo(false);
			 redirect("step4.html?action=fail&ret=5");
			 return false;
			 break;
			 }
			}
			logMsg("创建数据库表成功！\n");
			 return true;
		}catch(Exception $e)
		{   logMsg($e);
			return false;
		}
	
	}else
	{
	SetInstallInfo(false);
	redirect("step4.html?action=fail&ret=1");
	return false;
	}	
}

function execuSQL(&$db,&$man)
{//操作mysql数据库
$result=false;
 if (file_exists(SQL_FILE_PATH))
 {logMsg("正在连接数据库服务器...\n");
 	$mysqlLink=mysql_connect($db["database_url"],$db["database_user"],$db["database_pasw"]);
 	if($mysqlLink)
 	{
 	logMsg("数据库服务器连接成功！\n");
 	$sql=file_get_contents(SQL_FILE_PATH);
 	 $sql=preg_replace("/\/\*[\\s\\S]*?\*\//","",$sql); //删注释
 	 $sql=preg_replace("/(\\r\\n)+/","\n",$sql);		//删空行以及只有分号的行
 	 $sql=preg_replace("/(\\n\\s*)+;/","",$sql);	
 	 $sql=preg_replace("/\\s{2,}/"," ",$sql);
 	 $sql=str_replace(")\n",")",$sql);
 	 $sql=str_replace("iwb_","database_prefix_",str_replace("iWeibo","database_name",$sql));
 	 $sql=str_replace("database_prefix_",$db["database_prefix"],$sql);//数据表前缀替换
 	 $sql=str_replace("database_name",$db["database_name"],$sql);
 	 $sql=str_replace("u_name_value",$man["adminName"],$sql);
 	 $sql=str_replace("u_add_time_value",time(),$sql);
 	 $sql=str_replace("u_password_value",md5($man["adminPasw"]),$sql);
 	 $sql=str_replace("default_page_tail_text","Copyright &copy; 1998-2011. All Rights Reserved",str_replace("default_logo_url","./style/images/logo.png",$sql));
 	 if(runquery($sql,$db,$man,$mysqlLink))
 	 {$result=true;}
 	 else
 	 {$result=false;}
 	  mysql_close();	
 	}else
 	{logMsg("数据库连接失败！\n");
 	 $result=false;
 	 SetInstallInfo(false);
 	 redirect("step4.html?action=fail&ret=1");
 	 }
 }
 else
 {logMsg("SQL文件“../install/iWeibo.sql”不存在！\n");SetInstallInfo(false);redirect("step4.html?action=fail&ret=3");}
  return $result;
}


function logMsg($msg)
{//输出日志
	try{
	$f=@fopen(INSTALLLOG,"ab");
	if ($f)
 	{fputs($f,$msg);}
 	 fclose($f);
 	}catch(Exception $e)
 	{SetInstallInfo(false);
 	 redirect("step4.html?action=fail&ret=3");
 	}
}

function clearInstallLog()
{//清空安装日记
$f=@fopen(INSTALLLOG,"wb");
	if ($f)
 	 {fputs($f,"");}
 	  fclose($f);
}

function checkInstalled()
{
//检查是否已经安装
	if (file_exists(USER_CONFIG))
	{
	$str=file_get_contents(USER_CONFIG);
	if (preg_match("/\\\"MB_INSTALLED\\\",true/",$str))
	 {
	 	return true;
	 }
	 else
	 {return false;}
	}
	else
	{return false;}
}

function redirect($url)
{//输出页面跳转
	//echo("<script type=\"text/javascript\">\nwindow.location.href=\"".$url."\";\n</script>");
	echo("<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=".$url."\">");
	//echo("&lt;script type=\"text/javascript\"&gt;window.location.href='".$url."';&lt;/script&gt;");
}

function removeDir($dir) {
//删除文件目录
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          removeDir($fullpath);
      }
    }
  }
  closedir($dh);
  try
  {rmdir($dir);return true;}
  catch(Exception $e)
  {return false;}
} 

function SetInstallInfo($bol)
{//输出是否成功安装到user_config.php
$bolstr=($bol?"true":"false");
if (file_exists(USER_CONFIG))
{	
	$str=file_get_contents(USER_CONFIG);
	$str=trim(str_replace("?>","",str_replace("<?php","",$str)))."\n";
	if (preg_match("/\"MB_INSTALLED\"/",$str))
	{
		$str=preg_replace("/\\\"MB_INSTALLED\\\",\\w+/","\"MB_INSTALLED\",$bolstr",$str);
	}else
	{$str.="define(\"MB_INSTALLED\",".$bolstr.");\n";}
	$str="<?php\n".$str."?>";
	$f=fopen(USER_CONFIG,'wb');
	fwrite($f,$str,strlen($str));
	fclose($f);
}
}

function removeInstallInfo($a)
{//a：1、删除install目录，2、删除安装日志，3、两个都删除
	$a=intval($a);
	if ($a==1)
	{removeDir(INSTALLPATH);}
	elseif($a==2)
	{
		if (file_exists(INSTALLLOG))
		{unlink(INSTALLLOG);}
	}
	elseif($a==3)
	{
		if (file_exists(INSTALLLOG))
		{unlink(INSTALLLOG);}
		removeDir(INSTALLPATH);		
	}
return true;
}
?>