<?php
if(!defined('ISWBYL')) exit('Access Denied');

importlib("zhengshu");
class zs extends ctl_base {
	function geturl(){
		$type=rq('type','iq');
		$lv=rq('lv',0);
		$uid=rq("uid",0);
		
		$fun='get'.$type;
		$zs=zhengshu::$fun($uid,$lv);
		if(is_array($zs))
			echo $zs['zsurl'];
		else
			echo '';
	}
		
	function clear() {
		global $timestamp;	
		$date= date("y-m-d",$timestamp)."";
		echo "$date<br/>";
		
		if(ISSAE){
			 $this->_deletefilesSAE('zhengshu',$date);
			 $this->_deletefilesSAE('zhengshu1',$date);
			 $this->_deletefilesSAE('zhengshu2',$date);
			 return;
		}

		$dir=ROOT."data/zhengshu";
		$this->_deletesub($dir,$date);
		
 	}
 	function _deletefilesSAE($domain1,$date) {
	
		//遍历Domain下所有文件
		 $stor = new SaeStorage();
		 $num = 0;
		 echo $stor->getFilesNum($domain1)."<br/>";
		 while ($num<500 && $ret = $stor->getList($domain1, "*", 100, $num ) ) {
				 foreach($ret as $file) {
					 echo "{$file}=";
					 if ($file<$date)  
						echo $stor->delete($domain1,$file);
					 else
						echo "0<br/>";

					 $num ++;
				 }
		 }
		 
		 echo "<br/>TOTAL: {$num} files<br/>";

 	}

	function _deletesub($dir,$date) {
		$ml = opendir($dir);  //打开目录
		while ($hqml = readdir($ml)){ //循环读取目录中的目录及文件
			if(is_dir($hqml)){  //判断是目录
				if($hqml!="." && $hqml!=".."){
					$this->_deletesub($dir."/".$hqml,$date);
				}
				continue;
			}
			echo $dir."/".$hqml;
			if($hqml<$date) {
				unlink($dir."/".$hqml);
				echo "=1<br/>";
			}
			else {
				echo '=0<br/>';
			}
		}
	}
}
?>
