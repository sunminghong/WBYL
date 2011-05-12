<?php

	function getQuestion(){	
		global $question,$qtype,$timestamp;
//echo '/*';print_r($question);echo '*/';
		$timestamp=gettimestamp();
		$op=rq("op","");
		$an=array(0,0,0);
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
		ssetcookie("raar_",$raarr);		echo "/*";print_r($raarr);echo '*/';
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
		$time=$selv[1];
		$val=$question[$idx][2][$sel*2+1];
		$an=array($val,$time,0);
		//echo '/*';echo $val.";$time*/";
		if($val>0) {
			if($an[1]<=5)
				$an[2]=$an[0];
			elseif($an[1]>=15)
				$an[2]=1 ;
			else
				$an[2]=$an[0] * ((15-$an[1]) / 10);
		}
		return $an;
	}
	else
		 return array(0,0,0);
}

?>