<?php
if(!defined('ISWBYL')) exit('Access Denied');
importlib("watermark.fun");
class zhengshu {
	static function getBaseUrl($zid){
		return URLBASE;
	}

	static function getAllZhengshu($uid,$top=3){		
		$sql="select * from ". dbhelper::tname("ppt","zhengshu") ." where uid=$uid order by id desc limit 0,$top";
		$rs=dbhelper::getrs($sql);
		$arr=array();
		while($row=$rs->next()){
			 $arr[]=self::getBaseUrl($row['id'])."data/zhengshu/".$row['type']."_".$row['uid']."_".$row['lv'].".png";
		}
		return $arr;
	}
	static function getZhengshu($zid) {
		$sql="select * from ". dbhelper::tname("ppt","zhengshu") ." where id=$zid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			return self::getBaseUrl($zid)."data/zhengshu/".$row['type']."_".$row['uid']."_".$row['lv'].".png";
		}
		return false;
	}

	static function checkZhengshu($uid,$type,$lv) {
		$sql="select * from ". dbhelper::tname("ppt","zhengshu") ." where uid=$uid and type='$type' and lv=$lv limit 0,1";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			if(ISSAE) {
				 $s = new SaeStorage();
				 $url=$s->getUrl( 'zhengshu' , "data/zhengshu/".$row['type']."_".$row['uid']."_".$row['lv'].".png");
				return $url;
			}
			return self::getBaseUrl($row['id'])."data/zhengshu/".$row['type']."_".$row['uid']."_".$row['lv'].".png";
		}
		return false;
	}
	

	///////////////////////////////////////////////////IQ     FUN///////////////////////////////////////////////
	static function getiq($uid,$lv) {
		$iqscore=array("iqlv"=>$lv);
		$account=array("uid"=>$uid);
		$sql="select u.uid,u.screen_name,u.avatar,iq.iq,iq.testCount from ". dbhelper::tname("ppt","user") ." u inner join ". dbhelper::tname("iq","iq") ." iq on u.uid=iq.uid where u.uid=$uid";echo $sql;
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$account['screen_name']=$row['screen_name'];
			$account['avatar']=$row['avatar'];

			$iqscore['iq']=$row['iq'];
			$iqscore['testCount']=$row['testCount'];
		}else
			return "";

		$top=1;
		$sql="select count(*) as top from ". dbhelper::tname("iq","iq") ." where iq>" . $iqscore['iq']."";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		$top=$row['top'];

		$sql="select count(*) as top from ". dbhelper::tname("iq","iq") ." where iq=". $iqscore['iq'] ." and testCount<".$iqscore['testCount'] ."";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		$top += $row['top'];
//echo $top;
		//$sql="select (select count(*) from ". dbhelper::tname("iq","iq") ." where iq>" . $iqScore['iq'].") + (select count(*) from ". 
		//	dbhelper::tname("iq","iq") ." where iq=". $iqScore['iq'] ." and testCount<".$iqScore['testCount'] .")  as top ,";
		//$sql.="(select count(*) from ". dbhelper::tname("iq","iq") .") as total ";
		$sql="select count(*) as total from ". dbhelper::tname("iq","iq") ." ";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$iqscore["top"]=$top;
			if(intval($row['total'])==0)
				$iqscore['win']=100;
			else
				$iqscore['win']=$row['total'] - $top; //(1- $row['top']/$row['total'])*100;

		}else{
			$iqscore["top"]=1;
			$iqscore['win']=100;
		}

		return self::makeIQ($account,$iqscore,false);
	}

	static function makeIQ($account,$iqscore,$newmake=false) {
		$iqlv=$iqscore['iqlv'];
		$uid=$account['uid'];
		$type="iq";
		$url="";
		if(!$newmake && ($url=self::checkZhengshu($uid,$type,$iqlv))){
			return array("isold"=>1,"zsurl"=>$url);
		}
		
		$lasttime=getTimestamp();
		//加入证书，且取得证号编号
		$sql="insert into ". dbhelper::tname("ppt","zhengshu") ." set uid=$uid,type='$type',lv=$iqlv,lasttime=$lasttime";
		$zid=dbhelper::execute($sql,1);
		$no="IQ". right("0000000".$zid,7);
		if(ISSAE){
			$newName=tempnam(SAE_TMP_PATH, "SAE_IMAGE");
		}
		else
			$newName=ROOT."data/zhengshu/{$type}_{$uid}_{$iqlv}.png";

		$name=$account['screen_name'];

		$backImage=ROOT."images/zhengshu_{$type}_".$iqlv.".png";
		//$backImage=ROOT."images/zhengshu_iq_0.png";
		$text=$no;
		$color="#000000";
		$fontFile=ROOT."images/fonts/arial.ttf";
		 imageWaterText($backImage,$text,$color,263,70,12,$fontFile,$newName);

		$backImage=$newName;
		$text=$name;
		$fontFile=ROOT."images/fonts/YGY20070701.ttf";	
		if(len($text)<6) $posx=115;
		else $posx=95;
		 imageWaterText($backImage,$text,$color,$posx,205,24,$fontFile,$newName);

		$text=$iqscore['top'];
		$fontFile=ROOT."images/fonts/arial.ttf";
		if(len($text)<4) $posx=275;
		else
			$posx=260;
		 imageWaterText($backImage,$text,$color,$posx,265,16,$fontFile,$newName);

		$text= date("y.m.d",$lasttime);
		$fontFile=ROOT."images/fonts/arial.ttf";
		 imageWaterText($backImage,$text,$color,286,479,12,$fontFile,$newName);

		$waterPic=$account['avatar'];

		$rel= imageWaterPic($backImage,$waterPic,37,172);
		//echo 'backImage='.$backImage."<br/>";
		//echo 'newname='.$newName;
		if(ISSAE) {
			 $s = new SaeStorage();
			 $s->write( 'zhengshu' , "data/zhengshu/{$type}_{$uid}_{$iqlv}.png",@file_get_contents($newName)  );			 
			 $url=$s->getUrl( 'zhengshu' , "data/zhengshu/{$type}_{$uid}_{$iqlv}.png");
			  @unlink($newName);
		}else{
			$url=self::getBaseUrl($zid)."data/zhengshu/{$type}_{$uid}_{$iqlv}.png";
		}
		return array('isold'=>0,"zsurl"=>$url);
	}

///////////////////////////////////////////////////EQ LIST/////////////////////////////////////////////////////////////
	static function geteq($uid,$lv) {
		$eqscore=array("eqlv"=>$lv);
		$account=array("uid"=>$uid);
		$sql="select u.uid,u.screen_name,u.avatar,eq.eq,eq.testCount from ". dbhelper::tname("ppt","user") ." u inner join ". dbhelper::tname("eq","eq") ." eq on u.uid=eq.uid where u.uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$account['screen_name']=$row['screen_name'];
			$account['avatar']=$row['avatar'];

			$eqscore['eq']=$row['eq'];
			$eqscore['testCount']=$row['testCount'];
		}else				return "";
		
		$top=1;
		$sql="select count(*) as top from ". dbhelper::tname("eq","eq") ." where eq>" . $eqscore['eq']."";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		$top=$row['top'];

		$sql="select count(*) as top from ". dbhelper::tname("eq","eq") ." where eq=". $eqscore['eq'] ." and testCount<".$eqscore['testCount'] ."";
		$rs=dbhelper::getrs($sql);
		$row=$rs->next();
		$top += $row['top'];
//echo $top;
		//$sql="select (select count(*) from ". dbhelper::tname("eq","eq") ." where eq>" . $eqscore['eq'].") + (select count(*) from ". 
		//	dbhelper::tname("eq","eq") ." where eq=". $eqscore['eq'] ." and testCount<".$eqscore['testCount'] .")  as top ,";
		//$sql.="(select count(*) from ". dbhelper::tname("eq","eq") .") as total ";
		$sql="select count(*) as total from ". dbhelper::tname("eq","eq") ." ";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$eqscore["top"]=$top;
			if(intval($row['total'])==0)
				$eqscore['win']=100;
			else
				$eqscore['win']=$row['total'] - $top; //(1- $row['top']/$row['total'])*100;

		}else{
			$eqscore["top"]=1;
			$eqscore['win']=0;
		}

		return self::makeEQ($account,$eqscore,false);
	}

	static function makeEQ($account,$eqscore,$newmake=false) { 
		$eqlv=$eqscore['eqlv'];
		$uid=$account['uid'];
		$type="eq";
		$url="";
		if(!$newmake && ($url=self::checkZhengshu($uid,$type,$eqlv))){
			return array("isold"=>1,"zsurl"=>$url);
		}
		
		$lasttime=getTimestamp();
		//加入证书，且取得证号编号
		$sql="insert into ". dbhelper::tname("ppt","zhengshu") ." set uid=$uid,type='$type',lv=$eqlv,lasttime=$lasttime";
		$zid=dbhelper::execute($sql,1);
		$no="EQ". right("0000000".$zid,7);
		if(ISSAE){
			$newName=tempnam(SAE_TMP_PATH, "SAE_IMAGE");
		}
		else
			$newName=ROOT."data/zhengshu/{$type}_{$uid}_{$eqlv}.png";

		$name=$account['screen_name'];

		$backImage=ROOT."images/zhengshu_{$type}_".$eqlv.".png";
		//$backImage=ROOT."images/zhengshu_eq_0.png";
		$text=$no;
		$color="#000000";
		$fontFile=ROOT."images/fonts/arial.ttf";
		 imageWaterText($backImage,$text,$color,263,70,12,$fontFile,$newName);

		$backImage=$newName;
		$text=$name;
		$fontFile=ROOT."images/fonts/YGY20070701.ttf";	
		if(len($text)<6) $posx=115;
		else $posx=95;
		 imageWaterText($backImage,$text,$color,$posx,205,24,$fontFile,$newName);

		$text=$eqscore['top'];
		$fontFile=ROOT."images/fonts/arial.ttf";
		if(len($text)<4) $posx=275;
		else
			$posx=260;
		 imageWaterText($backImage,$text,$color,$posx,265,16,$fontFile,$newName);

		$text= date("y.m.d",$lasttime);
		$fontFile=ROOT."images/fonts/arial.ttf";
		 imageWaterText($backImage,$text,$color,286,479,12,$fontFile,$newName);

		$waterPic=$account['avatar'];

		$rel= imageWaterPic($backImage,$waterPic,37,172);
		//echo 'backImage='.$backImage."<br/>";
		//echo 'newname='.$newName;
		if(ISSAE) {
			 $s = new SaeStorage();
			 $s->write( 'zhengshu' , "data/zhengshu/{$type}_{$uid}_{$eqlv}.png",@file_get_contents($newName)  );			 
			 $url=$s->getUrl( 'zhengshu' , "data/zhengshu/{$type}_{$uid}_{$eqlv}.png");
			  @unlink($newName);
		}else{
			$url=self::getBaseUrl($zid)."data/zhengshu/{$type}_{$uid}_{$eqlv}.png";
		}
		return array('isold'=>0,"zsurl"=>$url);
	}

}
?>