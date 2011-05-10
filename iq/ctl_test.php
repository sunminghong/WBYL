<?php
if(!defined('ISWBYL')) exit('Access Denied');

$qt1=0;$qt2=0;$qt3=0;$rank=0;


	function readQuestion() {
		include('test_conf.php');
		return $question;
	}

function g_s($raarr,$idx){
	$question=readQuestion();
	if(isset($raarr[$idx])){
		$sel=$raarr[$idx];	
		$val=$question[$idx][2][$sel*2+1];
	}
	else
		$val=0;
	//echo '$idx='.$idx.';$val='.$val.';';	
	return $val;	
}

class test extends ctl_base
{
	function index() {
		$this->display("test_index");
	}
	function init() {
		$this->getQuestion(true);
		exit;
	}
	function next() {
		$this->getQuestion(false);
		exit;
	}
	function up() {
		$this->getQuestion(false);
		exit;
	}
	function showscore() {
			$this->saveAnswerToDB();
			$this->display("test_index");
	}

	function getQuestion($isinit=False){	
		global $step,$qtype;
		$op=rq("op","");
		$question=readQuestion();
		if($isinit){
			$idx=1;
		}
		else{
			if(sreadcookie('quest_idx')){
				$idx=("0" . sreadcookie('quest_idx'))*1;	
		
			}
			else{
				$idx=0;
			}

			if ($op=="next"){
				$idx=$idx+1;
			}
			else if($op=='up'){
				$idx=$idx-1;
			}
			else{
				echo "---";exit;
			}

			if ($idx<1) $idx=1;
		}
			
		ssetcookie('quest_idx',$idx);
		
		$idx=($idx-1)*$step+1;
		
		$html="";
		$co=count($question)-1;
			
		if($idx>$co){
			//$this->saveAnswerToDB();
			echo "$idx>$co";
			return;
		}
		
		$step=$step+$idx;
		
		$raarr=$this->saveAndReadAnswerToCookie($isinit);

		//echo $idx . $step;
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
		

		$json ="['".$co."/".($idx-1)."'".$json."]";

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

	private function saveAnswerToDB(){
		global $qt1,$qt2,$qt3,$rank,$timestamp;
	
		$question=readQuestion();
		$raarr=$this->saveAndReadAnswerToCookie();
		//print_r($raarr);print_r($question);
		$qt1=0;
		for($i=1;$i<count($question)+1;$i++){
			$qt1+=g_s($raarr,$i);echo $i;
		}
		
		$rank=round($qt1,2);
				
		echo '$rank='.$rank."<br/>";
		echo $username."<br/>";
		echo $uid."<br/>";
		exit;
		//写入数据库
	}
}
?>