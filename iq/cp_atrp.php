<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_profile.php 12883 2009-07-24 07:51:23Z zhengqingpeng $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
header("Content-Type:text/html; charset=".$_SC['charset']);

$op = empty($_GET['op'])?'':$_GET['op'];
if(!in_array($op, array('','next','up','showscore','init'))) {
	$op = '';
}

$qt1=0;$qt2=0;$qt3=0;$rank=0;

if($op == '') {	
	include template("cp_atrp");

} elseif ($op == 'next' || $op=='up') {
	include_once(S_ROOT.'./source/cp_atrp_conf.php');
	getQuestion($op);
	exit;
}elseif ($op=='init'){
	include_once(S_ROOT.'./source/cp_atrp_conf.php');
	getQuestion($op,True);
	exit;
} elseif ($op == 'showscore') {
	include_once(S_ROOT.'./source/cp_atrp_conf.php');
	
	saveAnswerToDB();
	include template("cp_atrp");
}


function getQuestion($act,$isinit=False){	
	global $question,$step,$qtype;

	if($isinit){
		$idx=1;
	}
	else{
		if(isset($_COOKIE['quest_idx'])){
			$idx=("0" . $_COOKIE['quest_idx'])*1;	
	
		}
		else{
			$idx=0;
		}
		
		if ($act=="next"){
			$idx=$idx+1;
		}
		else if($act=='up'){
			$idx=$idx-1;
		}
		else{
			echo "---";exit;
		}

		if ($idx<1) $idx=1;
	}
		
	setcookie('quest_idx',$idx);
	
	$idx=($idx-1)*$step+1;
	
	$html="";
	$co=count($question)-1;
		
	if($idx>$co){
		//saveAnswerToDB();
		echo '程序错误了，本不应该到此！';
		//return;
	}
	
	$step=$step+$idx;
	
	$raarr=saveAndReadAnswerToCookie($isinit);

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
		setcookie("raar_","");
		return Array();
	}
	
	$ans="";
	$raarr=array();
	if(isset($_COOKIE['raar_'])  && $_COOKIE['raar_']){
		$ans=$_COOKIE['raar_'];
		$raarr=unserialize($ans);
	}
		
	if (isset($_GET["an"])){
		//$an=explode(";",$_GET["an"]);
		$an=$_GET["an"];
		$an=str_replace('|','$raarr[',$an);
		$an=str_replace("_","]=",$an);
		eval($an);
		
		//echo var_export($raarr);
		
		setcookie("raar_",serialize($raarr));
	}
	return $raarr;
}

function saveAnswerToDB(){
	global $_SGLOBAL,$qt1,$qt2,$qt3,$rank;
	
	$raarr=saveAndReadAnswerToCookie();
	
	$qt1=round((g_s($raarr,1)+g_s($raarr,2)+g_s($raarr,3)+g_s($raarr,4))/80*g_s($raarr,20),2);
	
	//2==(E48*1.6+E49*0.4)/2
	//e48=(C13+C15+C17+C19+C21+C27+C29+C31)/8
	//E49==(C23+C25)/2	
	
	$E48=(((g_s($raarr,2)+g_s($raarr,7))/2/10*g_s($raarr,5))+((g_s($raarr,2)+g_s($raarr,7))/2/10*g_s($raarr,6))+((g_s($raarr,2)+g_s($raarr,3)+g_s($raarr,16)+g_s($raarr,18)+g_s($raarr,19))/5/10*g_s($raarr,7))	+((g_s($raarr,1)+g_s($raarr,2)+g_s($raarr,3)+g_s($raarr,14)+g_s($raarr,15)+g_s($raarr,16)+g_s($raarr,17)+g_s($raarr,18)+g_s($raarr,19))/9/10*g_s($raarr,8))+((g_s($raarr,1)+g_s($raarr,2)+g_s($raarr,3)+g_s($raarr,14)+g_s($raarr,15)+g_s($raarr,16)+g_s($raarr,17)+g_s($raarr,18)+g_s($raarr,19))/9/10*g_s($raarr,9))+	((g_s($raarr,1)+g_s($raarr,2)+g_s($raarr,3)+g_s($raarr,15)+g_s($raarr,16)+g_s($raarr,17)+g_s($raarr,18)+g_s($raarr,19))/8/10*g_s($raarr,12))+((g_s($raarr,1)+g_s($raarr,2)+g_s($raarr,3)+g_s($raarr,15)+g_s($raarr,16)+g_s($raarr,17)+g_s($raarr,18)+g_s($raarr,19))/8/10*g_s($raarr,13))+((g_s($raarr,1)+g_s($raarr,4)+g_s($raarr,18))/3/10*g_s($raarr,14)))/8;
	$E49=(((g_s($raarr,1)+g_s($raarr,2)+g_s($raarr,15)+g_s($raarr,16)+g_s($raarr,18)+g_s($raarr,19))/6/10*g_s($raarr,10))+((g_s($raarr,1)+g_s($raarr,2)+g_s($raarr,14)+g_s($raarr,15)+g_s($raarr,16)+g_s($raarr,18)+g_s($raarr,19))/7/10*g_s($raarr,11)))/2;
	$qt2=round(($E48*1.6+$E49*0.4)/2,2);
	
	//3=G49*G48/2
	$G48=(g_s($raarr,15)+g_s($raarr,16)+g_s($raarr,17)+g_s($raarr,18)+g_s($raarr,19))/5;
	$G49=g_s($raarr,20)*0.1;
	$qt3=round($G49*$G48/2,2);
 
	//echo $qt1.'==='.$qt2."===".$qt3;
		//exit;
	
	$rank=round(($qt1+$qt2+$qt3)/3,2);
	$uid=$_SGLOBAL['supe_uid'];
	$username=$_SGLOBAL['username'];
	
	/*echo $username."<br/>";
	echo $uid."<br/>";
	exit;*/	
	dbconnect();	
	
	$query = $_SGLOBAL['db']->query("SELECT uid FROM ".tname('user_dohappy')." WHERE username='".$username."'");
	$uid0 = $_SGLOBAL['db']->result($query, 0);

	if (empty($uid0)){ 
		$_SGLOBAL['db']->query('INSERT INTO '.tname('user_dohappy')."(uid,username,atrp)values(".$uid.",'".$username."',".$rank.")");
	}
	else{
		$_SGLOBAL['db']->query('UPDATE '.tname('user_dohappy')." SET atrp=".$rank." WHERE username='".$username."'");
	}
}

function g_s($raarr,$idx){
	global $question;
	if(isset($raarr[$idx])){
		$sel=$raarr[$idx];
		
		$val=$question[$idx][2][$sel*2+1];
	}
	else
		$val=0;
	return $val;	
}

?>