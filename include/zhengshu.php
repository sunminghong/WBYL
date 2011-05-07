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
	
	static function getiq($uid,$lv) {
		$iqscore=array("iqlv"=>$lv);
		$account=array("uid"=>$uid);
		$sql="select u.uid,u.screen_name,u.avatar,iq.iq,iq.testCount from ". dbhelper::tname("ppt","user") ." u inner join ". dbhelper::tname("iq","iq") ." iq on u.uid=iq.uid where u.uid=$uid";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$account['screen_name']=$row['screen_name'];
			$account['avatar']=$row['avatar'];

			$iqscore['iq']=$row['iq'];
			$iqscore['testCount']=$row['testCount'];
		}else				return "";
		
		$sql="select (select count(*) from ". dbhelper::tname("iq","iq") ." where iq>" . $iqscore['iq'].") + (select count(*) from ". dbhelper::tname("iq","iq") ." where iq=". $iqscore['iq'] ." and testCount<".$iqscore['testCount'] .")  as top";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$iqscore["top"]=$row['top'];
			
		}else{
			$iqscore["top"]=1;
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

}





?>