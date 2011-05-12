<?php
	function getQuestion($isinit=false,$idx=-1){	
		global $question,$step,$qtype;
//echo '/*';print_r($question);echo '*/';
		$op=rq("op","");
		if($isinit || $idx!=-1){
			$idx=1;
		}
		else{
			if(!!sreadcookie('quest_idx')){
				$idx=("0" . sreadcookie('quest_idx'))*1;		
			}
			else{
				$idx=0;
				saveAndReadAnswerToCookie(true);
			}

			if ($op=="next"){
				$idx=$idx+1;
			}
			else if($op=='up'){
				$idx=$idx-1;
			}
			else{
				//echo "---";exit;
			}

			if ($idx<1) $idx=1;
		}
		$idx2=$idx;
		
		$idx=($idx-1)*$step+1;
		
		$html="";
		$co=count($question)-1;
			
		if($idx>$co){
			$idx=$idx-$step;
			$idx2=$idx2-1;
			//echo "$idx>$co";
			//saveAnswerToDB();
			//echo '程序错误了，本不应该到此！';
			//return;
		}
		
		ssetcookie('quest_idx',$idx2);
		$step=$step+$idx;
		
		$raarr=saveAndReadAnswerToCookie($isinit);

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
		
		$useTime=gettimestamp() - sreadcookie("daren_starttime") * 1;
		$useTime=gettimestamp() - sreadcookie("daren_starttime2") * 1;
		$json ="['$useTime2','$useTime','".$co."/".($idx-1)."'".$json."]";

		echo $json;
		exit();
	}

function saveAndReadAnswerToCookie($isinit=false){
	//|idx_sel;
	if($isinit){
		ssetcookie("raar_","");
		return Array();
	}

	$raarr=sreadcookie('raar_');
		
	if (rq("an",false)){
		//$an=explode(";",$_GET["an"]);
		$an=$_GET["an"];
		$an=str_replace('|','$raarr[',$an);
		$an=str_replace("_","]=",$an);
		eval($an);
		
		//echo var_export($raarr);		
		ssetcookie("raar_",$raarr);
	}
	return $raarr;
}

function g_s($raarr,$idx){
	global $question;

	if(isset($raarr[$idx])){
		$sel=$raarr[$idx];	
		$val=$question[$idx][2][$sel*2+1];
	}
	else
		$val=0;
	//echo '$idx='.$idx.';$val='.$val.';';	
	return $val;	
}

?>