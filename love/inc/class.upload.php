<?php
/*
鍔熻兘锛氫笂浼犳枃浠?
杩斿洖锛歛rray(true|false,msg); false鏃讹紝杩斿洖澶辫触鍘熷洜锛宼rue鏃讹紝杩斿洖鏂囦欢鍚?

demo:

inputName锛欶orm琛ㄥ崟涓璮ile鎺т欢鍚?
savePath锛氫繚瀛樼殑鐩綍
maxSize锛氬厑璁镐笂浼犵殑鏂囦欢澶у皬 鍗曚綅KB
allowType锛氬厑璁镐笂浼犵殑鏂囦欢鍚庣紑鍚嶆暟缁?
up('file1','') 鎺т欢鍚嶏紝鏂版枃浠跺悕锛堝彲閫夛紝涓嶅～鍒欒〃绀轰娇鐢ㄥ師鏂囦欢鍚嶏級

$upload = new UPLOAD(ROOT.'upload',200,array('gif','jpg','png','bmp','lua'));
print_r($upload->up('file1'));
*/
class UPLOAD{
	var $savePath,$maxSize,$allowType;
	function UPLOAD($savePath,$maxSize,$allowType) { 
		$this->savePath=$savePath; 
		$this->maxSize=$maxSize; 
		$this->allowType=$allowType;
	}
	
	function up($inputName,$saveName=''){
		if($_FILES[$inputName]['tmp_name']=='' || $_FILES[$inputName]['name']=='' || $_FILES[$inputName]['size']==0){
			return array(false,'涓婁紶鏁版嵁璇诲彇閿欒');
		}
		
		$fileNameArray=explode('.',$_FILES[$inputName]['name']);   
		$fileType=strtolower($fileNameArray[count($fileNameArray)-1]);
		
		if(!in_array($fileType,$this->allowType)){
			return array(false,'涓婁紶鏂囦欢绫诲瀷閿欒锛屼粎鍏佽涓婁紶鍚庣紑鍚嶄负'.implode(' .',$this->allowType).'鐨勬枃浠?);  
		}
		
		if($_FILES[$inputName]['size']>$this->maxSize*1024){ 
			return array(false,'涓婁紶鏂囦欢闄愬埗鍦?. $this->maxSize .'KB浠ュ唴'); 
		}
		
		if(!file_exists($this->savePath)){   
			if(!mkdir($this->savePath,0777)){
				return array(false,'鍒涘缓涓婁紶鏂囦欢淇濆瓨鏂囦欢鐩綍澶辫触');
			}   
		}
		
		$save_name=$saveName&&$saveName!=''?$saveName.'.'.$fileType:$_FILES[$inputName]['name'];
		
		if(!move_uploaded_file($_FILES[$inputName]['tmp_name'],$this->savePath.DIRECTORY_SEPARATOR.$save_name)){
			return array(false,'鏂囦欢涓婁紶杩囩▼涓彂鐢熼敊璇?);
		}
		
		switch($_FILES[$formname]['error']){   
			case 0:   
				return array(true,$save_name);   
				break;   
			case 1:
				return array(false,'涓婁紶鐨勬枃浠惰秴杩囦簡upload_max_filesize閫夐」闄愬埗鐨勫€?); 
				break;   
			case 2:
				return array(false,'涓婁紶鏂囦欢鐨勫ぇ灏忚秴杩囦簡HTML琛ㄥ崟涓璏AX_FILE_SIZE閫夐」鎸囧畾鐨勫€?);  
				break;   
			case 3:
				return array(false,'鏂囦欢鍙湁閮ㄥ垎琚笂浼?); 
				break;   
			case 4:
				return array(false,'娌℃湁鏂囦欢琚笂浼?); 
				break;   
			default:   
				return array(false,'鏈煡閿欒'); 
				break;   
		}
	}
}
?>