<?php

	//取得当前所选考试类型，1 为综合
	function readQtype(){
		$qtype=rq("qtype",false);
		if($qtype===false) {
			$qtype=sreadcookie("daren_qtype");
			if(!$qtype) $qtype=1;
		}else {
			ssetcookie("daren_qtype",intval($qtype));
		}
		$qtype=intval($qtype);
		return $qtype;
	}
	function readqtypelist(){
		$cache=new CACHE();		
		$qtypearr=$cache->get("daren_qtype_arr"); 
		if( 1==0 && is_array($qtypearr)) {
			return $qtypearr;
		}
		
		$qtypearr=array(
			1=>array('综合',''),
			2=>array('文学','12'),
			3=>array('历史','6'),
			4=>array('地理','8'),
			5=>array('常识','10'),
			6=>array('自然','18'),
			7=>array('科技','17'),
			8=>array('物理天文','15,9'),
			9=>array('文体','1,21,19'),
			10=>array('电脑类','16'),
			11=>array('其它','5,13,20,2')
		);
	/*
		$sql="select * from ".dbhelper::tname("daren","tmb_type")." where num>=500 order by num desc";
		$rs=dbhelper::getrs($sql);
		while($row=$rs->next()) {
			$qtypearr[intval($row["id"])]=$row["ctype"];
		}
		*/
		$cache->set("daren_qtype_arr",$qtypearr);
		return $qtypearr;
	}

	function getQuestion(){	
		global $question,$qtype,$timestamp;
//echo '/*';print_r($question);echo '*/';
		$timestamp=gettimestamp();
		$op=rq("op","");
		$an=array(0,0,0);$ans=array(0,0);
		//if($isinit || $idx!=-1){
		//	$idx=1;
		//}
		//else{
			if(!!sreadcookie('quest_idx')){
				$idx=("0" . sreadcookie('quest_idx'))*1;
			}
			else{
				$idx=0;
				saveAndReadAnswerToCookie(0);
				ssetcookie('daren_starttime2',$timestamp);
				ssetcookie('daren_usetime',"");
				ssetcookie('daren_iqvalue',"");
				ssetcookie("daren_ans","");
			}

			if ($op=="next" || $op=='up'){
				$raarr=saveAndReadAnswerToCookie($idx);				
				$an=g_s($raarr,$idx);
				$ans=computeScore($raarr);
				$idx=$idx+1;
				ssetcookie('daren_starttime2',$timestamp);
			}
			//else if($op=='up'){
			//	$idx=$idx-1;
			//	ssetcookie('daren_starttime2',$timestamp);
			//}
			
			if ($idx<1) $idx=1;
		//}
		$idx2=$idx;
		
		$idx=($idx-1)*STEP+1;
		
		$html="";
		$co=count($question)-1;
			
		if($idx>$co){
			$idx=$idx-STEP;
			$idx2=$idx2-1;
		}
		
		ssetcookie('quest_idx',$idx2);
		$step=STEP+$idx;
		
		//print_r($question);

		//echo $idx .":".$co.":" . $step;
		$json="";
		for($idx;$idx<=$co && $idx<$step;$idx++){
			$quest=$question[$idx];
			
			if(isset($raarr[$idx]))
				$ra=$raarr[$idx];
			else
				$ra=-1;
			
			$json.=",{'idx':$idx,'t':".$quest[0].",'q':'".$quest[1]."','a':[".$ra;
			
			$anco=count($quest[2]);
			for($ii=0;$ii<$anco;$ii++){
				$json.=",'".$quest[2][$ii]."'";
				$ii++;
			}
			$json.="]}";
		}
		
		$useTime=$timestamp- sreadcookie("daren_starttime") * 1;
		$useTime2=$timestamp - sreadcookie("daren_starttime2") * 1;

		$json ="['$useTime2','$useTime','".$co."/".($idx-1)."',".$an[2].",".$ans[0]."".$json."]";

		echo $json;
		exit();
	}

function saveAndReadAnswerToCookie($idx=0){
	global $timestamp;
	if(!$idx){
		ssetcookie("raar_",array());
		return Array();
	}elseif ($idx===true){
		$idx=intval("0" . sreadcookie('quest_idx'));
	}
	$useTime2=$timestamp - sreadcookie("daren_starttime2");
	$raarr=sreadcookie('raar_');
	
	if (rq("an",false)!==false){
		$an=$_GET["an"];
		$raarr[$idx]=$an."|".$useTime2;
		ssetcookie("raar_",$raarr);		//echo "/*";print_r($raarr);echo '*/';
	}
	return $raarr;
}

function computeScore($raarr){
	global $question;
	$qt1=0;
	$ans=array();

	for($i=1;$i<count($question);$i++){
		$an=g_s($raarr,$i);		
		$ans[$i]=$an;
		$qt1+=$an[2];
	}
	return array($qt1,$ans);
}

function g_s($raarr,$idx){
	global $question;

	if(isset($raarr[$idx])){
		$selv=$raarr[$idx];
		$selv=explode("|",$selv);
		$sel=$selv[0];
		if(!is_numeric($sel)) return array(0,0,0);

		$time=$selv[1];
		$val=$question[$idx][2][$sel*2+1];
		$an=array($val,$time,0);
		//echo '/*';echo $val.";$time*/";
		if($val>0) {
			if($an[1]<=5)
				$an[2]=$an[0];
			elseif($an[1]>=15)
				$an[2]=10 ;
			else
				$an[2]=10+($an[0]-10) * ((15-$an[1]) / 10);
		}
		return $an;
	}
	else
		 return array(0,0,0);
}

?>