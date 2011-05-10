<?php
if(!defined('ISWBYL')) exit('Access Denied');

class tongji extends ctl_base
{
	function tongjitask(){


		if(rq("j",false)){
			$logtime= date("20y-m-d H",gettimestamp());
			
			$sql="select sum(iqs) as iqs,sum(logs) as logs,sum(logins) as logins,sum(users) as users,sum(libs) as libs,sum(snses) as snses,sum(lib_snses) as lib_snses,sum(zhengshu) as zhengshu,sum(posts) as posts from ".dbhelper::tname("log","tongji") ." where lasttime<'{$logtime}:00:00' order by id desc limit 0,1";
			$rs=dbhelper::getrs($sql);//echo $sql;
			$row=$rs->next();

			$sql ="		select (select count(*) from ".dbhelper::tname("iq","iq") .") as iqs,
(select count(*) from ".dbhelper::tname("iq","log") ." ) as logs,
(select sum(logins) from ".dbhelper::tname("ppt","user") .") as logins,

(select count(*) from ".dbhelper::tname("ppt","user") .") as users,
(select count(*) from ".dbhelper::tname("ppt","userlib") .") as libs,
(select count(*) from ".dbhelper::tname("ppt","user_sns") .") as snses,
(select count(*) from ".dbhelper::tname("ppt","userlib_sns") .") as lib_snses,
(select count(*) from ".dbhelper::tname("ppt","zhengshu") .") as zhengshu,
(SELECT SUM( posts )  FROM  ".dbhelper::tname("ppt","user") ." WHERE posts >0) as posts ";
			$rs=dbhelper::getrs($sql);
			$row2=$rs->next();
			foreach($row2 as $k => $v){
				if(is_array($row) && isset($row[$k])){
					$row2[$k]=$row2[$k]-$row[$k];
				}
			}

			$sql="delete from  ".dbhelper::tname("log","tongji") ." where lasttime<='{$logtime}:59:59' and lasttime>='{$logtime}:00:00'";			
			dbhelper::execute($sql); 
			dbhelper::update($row2,"",dbhelper::tname("log","tongji"),"id");
			

		$sql="delete from  ".dbhelper::tname("log","tongji_sum") ." where lasttime<='{$logtime}:59:59' and lasttime>='{$logtime}:00:00';;;";
			$sql .="
			insert into ".dbhelper::tname("log","tongji_sum") ." (iqs,logs,logins,users,libs,snses,lib_snses,zhengshu,posts) 
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
				
				echo 'ok';return;
			}

			$sql="select * from  ".dbhelper::tname("log","tongji") ."  order by id desc limit 0,24";
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
					foreach( $row as $k=>$val){
							echo "<td>$val</td>";	
					}
						
					echo '</tr>';
					$row=$rs->next();
				}
				echo '</table>';
			}
			exit;

			/*
					if(rq("jjjjj",false)){
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
			exit;
		}

			
			*/
	}
}	


		
/*
			$sql="select * from  ".dbhelper::tname("log","tongji_________dfg") ."  order by lasttime";
			$rs=dbhelper::getrs($sql);
			$lastrow=$rs->next();
			dbhelper::update($lastrow,"",dbhelper::tname("log","tongji"),"id");			
			while($row=$rs->next()){
				$row3=array();
				foreach( $row as $k=>$val){
					if($k!="id") {
						if($k!="lasttime" && is_array($lastrow) && isset($lastrow[$k])){
							$row3[$k]=($row[$k]-$lastrow[$k]);
						}else
						$row3[$k]=$row[$k];
					}
				}
				dbhelper::update($row3,"",dbhelper::tname("log","tongji____________"),"id");

				$lastrow=$row;
			}

			exit;
			return;
*/

?>