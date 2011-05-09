<?php
if(!defined('ISWBYL')) exit('Access Denied');

class tongji extends ctl_base
{
	function tongjitask(){
		if(rq("j",false)){
			$logtime= date("20y-m-d H",gettimestamp());
			$sql="delete from  ".dbhelper::tname("log","tongji") ." where lasttime<='{$logtime}:59:59' and lasttime>='{$logtime}:00:00';;;";echo $sql;
		$sql .="
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
			dbhelper::exesqls($sql);
			
			echo 'ok';
		}
			$sql="select * from  ".dbhelper::tname("log","tongji") ."  order by id desc";
			$rs=dbhelper::getrs($sql);
			$row=$rs->next();
			if($row){
				echo '<table>';
				echo '<tr>';
				foreach( $row as $key=>$val){
					echo "<th>$key</th>";
				}					
				echo '</tr>';				
			
				while($row){
					echo '<tr>';
					foreach( $row as $val){
						echo "<td>$val</td>";
						$row=$rs->next();
					}
						
					echo '</tr>';
					$row=$rs->next();
				}
				echo '</table>';
			}
			exit;
	}
}	


?>