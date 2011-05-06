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
		$sql="select * from ". dbhelper::tname("ppt","zhengshu") ." where uid=$uid and type='$type' and lv=$lv";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			return self::getBaseUrl($row['id'])."data/zhengshu/".$row['type']."_".$row['uid']."_".$row['lv'].".png";
		}
		return false;
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
		$no="IQ". left("0000000".$zid,7);
		if(ISSAE){
			$newName=tempnam(SAE_TMP_PATH, "SAE_IMAGE");
		}
		else
			$newName=ROOT."data/zhengshu/{$type}_{$uid}_{$iqlv}.png";

		$name=$account['screen_name'];
		$avatar=$account['avatar'];

		$backImage=ROOT."images/zhengshu_{$type}_".$iqlv.".png";echo $backImage;
		//$backImage=ROOT."images/zhengshu_iq_0.png";
		$text=$no;
		$color="#000000";
		$fontFile=ROOT."images/fonts/simsun.ttc";
		 imageWaterText($backImage,$text,$color,263,70,13,$fontFile,$newName);

		$backImage=$newName;
		$text=$name;
		$fontFile=ROOT."images/fonts/YGY20070701.ttf";	
		if(len($text)<6) $posx=115;
		else $posx=95;
		 imageWaterText($backImage,$text,$color,$posx,205,24,$fontFile,$newName);

		$text=$iqscore['top'];
		$fontFile=ROOT."images/fonts/simsun.ttC";
		if(len($text)<4) $posx=275;
		else
			$posx=260;
		 imageWaterText($backImage,$text,$color,$posx,268,18,$fontFile,$newName);

		$text= date("y.m.d",$lasttime);
		$fontFile=ROOT."images/fonts/simsun.ttc";
		 imageWaterText($backImage,$text,$color,286,479,12,$fontFile,$newName);

		$waterPic=$account['avatar'];

		$rel= imageWaterPic($backImage,$waterPic,37,172);

		if(ISSAE) {
			 $s = new SaeStorage();
			 $s->write( 'example' , "data/zhengshu/{$type}_{$uid}_{$iqlv}.png", 'bookcontent!' );			 
			 $url=$s->getUrl( 'example' , "data/zhengshu/{$type}_{$uid}_{$iqlv}.png");
		}else{
			$url=self::getBaseUrl($zid)."data/zhengshu/{$type}_{$uid}_{$iqlv}.png";
		}
		return array('isold'=>0,"zsurl"=>$url);
	}

}





?>