<?php
if(!defined('ISWBYL')) exit('Access Denied');

class tongji extends ctl_base
{
	function tongjitask(){
		$sql="
		insert into ".dbhelper::tname("log","tongji") ." (iqs,logs,logins,users,libs,snses,lib_snses,zhengshu,posts) 
		select (select count(*) from iq_iq) as iqs,
(select count(*) from iq_log ) as logs,
(select sum(logins) from ppt_user) as logins,

(select count(*) from ppt_user) as users,
(select count(*) from ppt_userlib) as libs,
(select count(*) from ppt_user_sns) as snses,
(select count(*) from ppt_userlib_sns) as lib_snses,
(select count(*) from ppt_zhengshu) as zhengshu,
(SELECT SUM( posts )  FROM  `ppt_user` WHERE posts >0) as posts";
			dbhelper::execute($sql);
			
			echo 'ok';
			exit;
	}
}	


?>