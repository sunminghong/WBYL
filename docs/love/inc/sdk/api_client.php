<?php
/**
 * 寮€鏀惧钩鍙版搷浣滅被
 * @param 
 * @return
 * @author tuguska
 */
class MBApiClient
{
    /** 
     * 鏋勯€犲嚱鏁?
     *  
     * @access public 
     * @param mixed $wbakey 搴旂敤APP KEY 
     * @param mixed $wbskey 搴旂敤APP SECRET 
     * @param mixed $accecss_token OAuth璁よ瘉杩斿洖鐨則oken 
     * @param mixed $accecss_token_secret OAuth璁よ瘉杩斿洖鐨則oken secret 
     * @return void 
	 */
	public $host = 'open.t.qq.com';
    function __construct( $wbakey , $wbskey , $accecss_token , $accecss_token_secret ) 
	{
        $this->oauth = new MBOpenTOAuth( $wbakey , $wbskey , $accecss_token , $accecss_token_secret ); 
	}

	/******************
	 * 鑾峰彇鐢ㄦ埛娑堟伅
     * @access public 
	*@f 鍒嗛〉鏍囪瘑锛?锛氱涓€椤碉紝1锛氬悜涓嬬炕椤碉紝2鍚戜笂缈婚〉锛?
	*@t: 鏈〉璧峰鏃堕棿锛堢涓€椤?0锛岀户缁細鏍规嵁杩斿洖璁板綍鏃堕棿鍐冲畾锛?
	*@n: 姣忔璇锋眰璁板綍鐨勬潯鏁帮紙1-20鏉★級
	*@name: 鐢ㄦ埛鍚?绌鸿〃绀烘湰浜?
	 * *********************/
	public function getTimeline($p){
		if(!isset($p['name'])){
			$url = 'http://open.t.qq.com/api/statuses/home_timeline?f=1';
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'pageflag' => $p['f'],
				'reqnum' => $p['n'],
				'pagetime' =>  $p['t']
			);					
		}else{
			$url = 'http://open.t.qq.com/api/statuses/user_timeline?f=1';
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'pageflag' => $p['f'],
				'reqnum' => $p['n'],
				'pagetime' =>  $p['t'],
				'name' => $p['name']
			);					
		}
	 	return $this->oauth->get($url,$params); 
	}

	/******************
	 * 骞挎挱澶у巺娑堟伅
	*@p: 璁板綍鐨勮捣濮嬩綅缃紙绗竴娆¤姹傛槸濉?锛岀户缁姹傝繘濉笂娆¤繑鍥炵殑Pos锛?
	*@n: 姣忔璇锋眰璁板綍鐨勬潯鏁帮紙1-20鏉★級
	 * *********************/
	public function getPublic($p){
		$url = 'http://open.t.qq.com/api/statuses/public_timeline?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'pos' => $p['p'],
			'reqnum' => $p['n']	
		);
	 	return $this->oauth->get($url,$params); 
	}

	/******************
	*鑾峰彇鍏充簬鎴戠殑娑堟伅 
	*@f 鍒嗛〉鏍囪瘑锛?锛氱涓€椤碉紝1锛氬悜涓嬬炕椤碉紝2鍚戜笂缈婚〉锛?
	*@t: 鏈〉璧峰鏃堕棿锛堢涓€椤?0锛岀户缁細鏍规嵁杩斿洖璁板綍鏃堕棿鍐冲畾锛?
	*@n: 姣忔璇锋眰璁板綍鐨勬潯鏁帮紙1-20鏉★級
	*@l: 褰撳墠椤垫渶鍚庝竴鏉¤褰曪紝鐢ㄧ敤绮剧‘缈婚〉鐢?
	*@type : 0 鎻愬強鎴戠殑, other 鎴戝彂琛ㄧ殑
	**********************/
	public function getMyTweet($p){
		$p['type']==0?$url = 'http://open.t.qq.com/api/statuses/mentions_timeline?f=1':$url = 'http://open.t.qq.com/api/statuses/broadcast_timeline?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'pageflag' => $p['f'],
			'reqnum' => $p['n'],
			'pagetime' => $p['t'],
			'lastid' => $p['l']	
		);
	 	return $this->oauth->get($url,$params); 
	}

	/******************
	*鑾峰彇璇濋涓嬬殑娑堟伅
	*@t: 璇濋鍚嶅瓧
	*@f 鍒嗛〉鏍囪瘑锛圥ageFlag = 1琛ㄧず鍚戝悗锛堜笅涓€椤碉級鏌ユ壘锛汸ageFlag = 2琛ㄧず鍚戝墠锛堜笂涓€椤碉級鏌ユ壘锛汸ageFlag = 3琛ㄧず璺冲埌鏈€鍚庝竴椤? PageFlag = 4琛ㄧず璺冲埌鏈€鍓嶄竴椤碉級
	*@p: 鍒嗛〉鏍囪瘑锛堢涓€椤?濉┖锛岀户缁炕椤碉細鏍规嵁杩斿洖鐨?pageinfo鍐冲畾锛?
	*@n: 姣忔璇锋眰璁板綍鐨勬潯鏁帮紙1-20鏉★級
	**********************/
	public function getTopic($p){
		$url = 'http://open.t.qq.com/api/statuses/ht_timeline?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'pageflag' => $p['f'],
			'reqnum' => $p['n'],
			'httext' => $p['t'],
			'pageinfo' => $p['p']
		);
	 	return $this->oauth->get($url,$params); 
	}

	/******************
	*鑾峰彇涓€鏉℃秷鎭?
	*@id: 寰崥ID
	**********************/
	public function getOne($p){
		$url = 'http://open.t.qq.com/api/t/show?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'id' => $p['id']
		);
	 	return $this->oauth->get($url,$params); 
	}

	/******************
	*鍙戣〃涓€鏉℃秷鎭?
	*@c: 寰崥鍐呭
	*@ip: 鐢ㄦ埛IP(浠ュ垎鏋愮敤鎴锋墍鍦ㄥ湴)
	*@j: 缁忓害锛堝彲浠ュ～绌猴級
	*@w: 绾害锛堝彲浠ュ～绌猴級
	*@p: 鍥剧墖
	*@r: 鐖秈d
	*@type: 1 鍙戣〃 2 杞挱 3 鍥炲 4 鐐硅瘎
	**********************/
	public function postOne($p){
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'content' => $p['c'],
			'clientip' => $p['ip'],
			'jing' => $p['j'],
			'wei' => $p['w']
		);
		switch($p['type']){
			case 2:
				$url = 'http://open.t.qq.com/api/t/re_add?f=1';
				$params['reid'] = $p['r'];
				return $this->oauth->post($url,$params); 
				break;
			case 3:
				$url = 'http://open.t.qq.com/api/t/reply?f=1';
				$params['reid'] = $p['r'];
				return $this->oauth->post($url,$params); 
				break;
			case 4:
				$url = 'http://open.t.qq.com/api/t/comment?f=1';
				$params['reid'] = $p['r'];
				return $this->oauth->post($url,$params); 
				break;
			default:
				if(!empty($p['p'])){
					$url = 'http://open.t.qq.com/api/t/add_pic?f=1';
					$params['pic'] = $p['p'];
					return $this->oauth->post($url,$params,true); 
				}else{
					$url = 'http://open.t.qq.com/api/t/add?f=1';
					return $this->oauth->post($url,$params); 
				}	
			break;			
		}	

	}

	/******************
	*鍒犻櫎涓€鏉℃秷鎭?
	*@id: 寰崥ID
	**********************/
	public function delOne($p){
		$url = 'http://open.t.qq.com/api/t/del?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'id' => $p['id']
		);
	 	return $this->oauth->post($url,$params); 
	}	

	/******************
	*鑾峰彇杞挱鍜岀偣璇勬秷鎭垪琛?
	*@reid锛氳浆鍙戞垨鑰呭洖澶嶆牴缁撶偣ID锛?
	*@f锛氾紙鏍规嵁dwTime锛夛紝0锛氱涓€椤碉紝1锛氬悜涓嬬炕椤碉紝2鍚戜笂缈婚〉锛?
	*@t锛氳捣濮嬫椂闂存埑锛屼笂涓嬬炕椤垫椂鎵嶆湁鐢紝鍙栫涓€椤垫椂蹇界暐锛?
	*@tid锛氳捣濮媔d锛岀敤浜庣粨鏋滄煡璇腑鐨勫畾浣嶏紝涓婁笅缈婚〉鏃舵墠鏈夌敤锛?
	*@n锛氳杩斿洖鐨勮褰曠殑鏉℃暟(1-20)锛?
	*@Flag:鏍囪瘑0 杞挱鍒楄〃锛?鐐硅瘎鍒楄〃 2 鐐硅瘎涓庤浆鎾垪琛?
	**********************/
	public function getReplay($p){
		$url = 'http://open.t.qq.com/api/t/re_list?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'rootid' => $p['reid'],
			'pageflag' => $p['f'],
			'reqnum' => $p['n'],
			'flag' => $p['flag']
		);
		if(isset($p['t'])){
			$params['pagetime'] = $p['t'];	
		}
		if(isset($p['tid'])){
			$params['twitterid'] = $p['tid'];	
		}
	 	return $this->oauth->get($url,$params); 	
	}

	/******************
	*鑾峰彇褰撳墠鐢ㄦ埛鐨勪俊鎭?
	*@n:鐢ㄦ埛鍚?绌鸿〃绀烘湰浜?
	**********************/
	public function getUserInfo($p=false){
		if(!$p || !$p['n']){
			$url = 'http://open.t.qq.com/api/user/info?f=1';
			$params = array(
				'format' => MB_RETURN_FORMAT
			);
		}else{
			$url = 'http://open.t.qq.com/api/user/other_info?f=1';
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'name' => $p['n']
			);
		}
	 	return $this->oauth->get($url,$params); 	
	}

	/******************
	*鏇存柊鐢ㄦ埛璧勬枡
	*@p 鏁扮粍,鍖呮嫭浠ヤ笅:
	*@nick: 鏄电О
	*@sex: 鎬у埆 0 锛?锛氱敺2锛氬コ
	*@year:鍑虹敓骞?1900-2010
	*@month:鍑虹敓鏈?1-12
	*@day:鍑虹敓鏃?1-31
	*@countrycode:鍥藉鐮?
	*@provincecode:鍦板尯鐮?
	*@citycode:鍩庡競 鐮?
	*@introduction: 涓汉浠嬬粛
	**********************/
	public function updateMyinfo($p){
		$url = 'http://open.t.qq.com/api/user/update?f=1';
		$p['format'] = MB_RETURN_FORMAT;
	 	return $this->oauth->post($url,$p); 	
	}	

	/******************
	*鏇存柊鐢ㄦ埛澶村儚
	*@Pic:鏂囦欢鍩熻〃鍗曞悕 鏈瓧娈典笉鑳芥斁鍏ュ埌绛惧悕涓蹭腑
	******************/
	public function updateUserHead($p){
		$url = 'http://open.t.qq.com/api/user/update_head?f=1';
		$p['format'] = MB_RETURN_FORMAT;
		return $this->oauth->post($url, $p, true); 	
	}	

	/******************
	*鑾峰彇鍚紬鍒楄〃/鍋跺儚鍒楄〃
	*@num: 璇锋眰涓暟(1-30)
	*@start: 璧峰浣嶇疆
	*@n:鐢ㄦ埛鍚?绌鸿〃绀烘湰浜?
	*@type: 0 鍚紬 1 鍋跺儚
	**********************/
	public function getMyfans($p){
		try{
			if($p['n']  == ''){
				$p['type']?$url = 'http://open.t.qq.com/api/friends/idollist':$url = 'http://open.t.qq.com/api/friends/fanslist';
			}else{
				$p['type']?$url = 'http://open.t.qq.com/api/friends/user_idollist':$url = 'http://open.t.qq.com/api/friends/user_fanslist';
			}
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'name' => $p['n'],
				'reqnum' => $p['num'],
				'startindex' => $p['start']
			);
		 	return $this->oauth->get($url,$params);
		} catch(MBException $e) {
			$ret = array("ret"=>0, "msg"=>"ok"
					, "data"=>array("timestamp"=>0, "hasnext"=>1, "info"=>array()));
			return $ret;
		}
	}

	/******************
	*鏀跺惉/鍙栨秷鏀跺惉鏌愪汉
	*@n: 鐢ㄦ埛鍚?
	*@type: 0 鍙栨秷鏀跺惉,1 鏀跺惉 ,2 鐗瑰埆鏀跺惉
	**********************/	
	public function setMyidol($p){
		switch($p['type']){
			case 0:
				$url = 'http://open.t.qq.com/api/friends/del?f=1';
				break;
			case 1:
				$url = 'http://open.t.qq.com/api/friends/add?f=1';
				break;
			case 2:
				$url = 'http://open.t.qq.com/api/friends/addspecail?f=1';
				break;
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'name' => $p['n']
		);
	 	return $this->oauth->post($url,$params);		
	}
	
	/******************
	*妫€娴嬫槸鍚︽垜绮変笣鎴栧伓鍍?
	*@n: 鍏朵粬浜虹殑甯愭埛鍚嶅垪琛紙鏈€澶?0涓?閫楀彿鍒嗛殧锛?
	*@flag: 0 妫€娴嬬矇涓濓紝1妫€娴嬪伓鍍?
	**********************/	
	public function checkFriend($p){
		$url = 'http://open.t.qq.com/api/friends/check?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'names' => $p['n'],
			'flag' => $p['type']
		);
		return $this->oauth->get($url,$params);
	}

	/******************
	*鍙戠淇?
	*@c: 寰崥鍐呭
	*@ip: 鐢ㄦ埛IP(浠ュ垎鏋愮敤鎴锋墍鍦ㄥ湴)
	*@j: 缁忓害锛堝彲浠ュ～绌猴級
	*@w: 绾害锛堝彲浠ュ～绌猴級
	*@n: 鎺ユ敹鏂瑰井鍗氬笎鍙?
	**********************/
	public function postOneMail($p){
		$url = 'http://open.t.qq.com/api/private/add?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'content' => $p['c'],
			'clientip' => $p['ip'],
			'jing' => $p['j'],
			'wei' => $p['w'],
			'name' => $p['n']
			);
		return $this->oauth->post($url,$params); 
	}
	
	/******************
	*鍒犻櫎涓€灏佺淇?
	*@id: 寰崥ID
	**********************/
	public function delOneMail($p){
		$url = 'http://open.t.qq.com/api/private/del?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'id' => $p['id']
		);
	 	return $this->oauth->post($url,$params); 
	}
	
	/******************
	*绉佷俊鏀朵欢绠卞拰鍙戜欢绠?
	*@f 鍒嗛〉鏍囪瘑锛?锛氱涓€椤碉紝1锛氬悜涓嬬炕椤碉紝2鍚戜笂缈婚〉锛?
	*@t: 鏈〉璧峰鏃堕棿锛堢涓€椤?0锛岀户缁細鏍规嵁杩斿洖璁板綍鏃堕棿鍐冲畾锛?
	*@n: 姣忔璇锋眰璁板綍鐨勬潯鏁帮紙1-20鏉★級
	*@type : 0 鍙戜欢绠?1 鏀朵欢绠?
	**********************/	
	public function getMailBox($p){
		if($p['type']){
			$url = 'http://open.t.qq.com/api/private/recv?f=1';
		}else{
			$url = 'http://open.t.qq.com/api/private/send?f=1';
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'pageflag' => $p['f'],
			'pagetime' => $p['t'],
			'reqnum' => $p['n']
		);
	 	return $this->oauth->get($url,$params);		
	}	

	/******************
	*鎼滅储
	*@k:鎼滅储鍏抽敭瀛?
	*@n: 姣忛〉澶у皬
	*@p: 椤电爜
	*@type : 0 鐢ㄦ埛 1 娑堟伅 2 璇濋 
	**********************/	
	public function getSearch($p){
		switch($p['type']){
			case 0:
				$url = 'http://open.t.qq.com/api/search/user?f=1';
				break;
			case 1:
				$url = 'http://open.t.qq.com/api/search/t?f=1';
				break;
			case 2:
				$url = 'http://open.t.qq.com/api/search/ht?f=1';
				break;
			default:
				$url = 'http://open.t.qq.com/api/search/t?f=1';
				break;
		}		

		$params = array(
			'format' => MB_RETURN_FORMAT,
			'keyword' => $p['k'],
			'pagesize' => $p['n'],
			'page' => $p['p']
		);
	 	return $this->oauth->get($url,$params);		
	}	

	/******************
	*鐑棬璇濋
	*@type: 璇锋眰绫诲瀷 1 璇濋鍚嶏紝2 鎼滅储鍏抽敭瀛?3 涓ょ绫诲瀷閮芥湁
	*@n: 璇锋眰涓暟锛堟渶澶?0锛?
	*@Pos :璇锋眰浣嶇疆锛岀涓€娆¤姹傛椂濉?锛岀户缁～涓婃杩斿洖鐨凱OS
	**********************/	
	public function getHotTopic($p){
		$url = 'http://open.t.qq.com/api/trends/ht?f=1';
		if($p['type']<1 || $p['type']>3){
			$p['type'] = 1;
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'type' => $p['type'],
			'reqnum' => $p['n'],
			'pos' => $p['pos']
		);
	 	return $this->oauth->get($url,$params);		
	}			

	/******************
	*鏌ョ湅鏁版嵁鏇存柊鏉℃暟
	*@op :璇锋眰绫诲瀷 0锛氬彧璇锋眰鏇存柊鏁帮紝涓嶆竻闄ゆ洿鏂版暟锛?锛氳姹傛洿鏂版暟锛屽苟瀵规洿鏂版暟娓呴浂
	*@type锛? 棣栭〉鏈娑堟伅璁版暟锛? @椤垫秷鎭鏁?7 绉佷俊椤垫秷鎭鏁?8 鏂板绮変笣鏁?9 棣栭〉骞挎挱鏁帮紙鍘熷垱鐨勶級
	**********************/	
	public function getUpdate($p){
		$url = 'http://open.t.qq.com/api/info/update?f=1';
		if(isset($p['type'])){
			if($p['op']){
				$params = array(
					'format' => MB_RETURN_FORMAT,
					'op' => $p['op'],
					'type' => $p['type']
				);			
			}else{
				$params = array(
					'format' => MB_RETURN_FORMAT,
					'op' => $p['op']
				);			
			}
		}else{
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'op' => $p['op']
			);
		}
	 	return $this->oauth->get($url,$params);		
	}	

	/******************
	*娣诲姞/鍒犻櫎 鏀惰棌鐨勫井鍗?
	*@id : 寰崥id
	*@type锛? 娣诲姞 0 鍒犻櫎
	**********************/	
	public function postFavMsg($p){
		if($p['type']){
			$url = 'http://open.t.qq.com/api/fav/addt?f=1';
		}else{
			$url = 'http://open.t.qq.com/api/fav/delt?f=1';
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'id' => $p['id']
		);
	 	return $this->oauth->post($url,$params);		
	}

	/******************
	*娣诲姞/鍒犻櫎 鏀惰棌鐨勮瘽棰?
	*@id : 寰崥id
	*@type锛? 娣诲姞 0 鍒犻櫎
	**********************/	
	public function postFavTopic($p){
		if($p['type']){
			$url = 'http://open.t.qq.com/api/fav/addht?f=1';
		}else{
			$url = 'http://open.t.qq.com/api/fav/delht?f=1';
		}
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'id' => $p['id']
		);
	 	return $this->oauth->post($url,$params);		
	}	

	/******************
	*鑾峰彇鏀惰棌鐨勫唴瀹?
	*******璇濋
	n:璇锋眰鏁帮紝鏈€澶?5
	f:缈婚〉鏍囪瘑  0锛氶椤?  1锛氬悜涓嬬炕椤?2锛氬悜涓婄炕椤?
	t:缈婚〉鏃堕棿鎴?
	lid:缈婚〉璇濋ID锛岀娆¤姹傛椂涓?
	*******娑堟伅
	f 鍒嗛〉鏍囪瘑锛?锛氱涓€椤碉紝1锛氬悜涓嬬炕椤碉紝2鍚戜笂缈婚〉锛?
	t: 鏈〉璧峰鏃堕棿锛堢涓€椤?0锛岀户缁細鏍规嵁杩斿洖璁板綍鏃堕棿鍐冲畾锛?
	n: 姣忔璇锋眰璁板綍鐨勬潯鏁帮紙1-20鏉★級
	*@type 0 鏀惰棌鐨勬秷鎭? 1 鏀惰棌鐨勮瘽棰?
	**********************/	
	public function getFav($p){
		if($p['type']){
			$url = 'http://open.t.qq.com/api/fav/list_ht?f=1';
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'reqnum' => $p['n'],		
				'pageflag' => $p['f'],		
				'pagetime' => $p['t'],		
				'lastid' => $p['lid']		
				);
		}else{
			$url = 'http://open.t.qq.com/api/fav/list_t?f=1';	
			$params = array(
				'format' => MB_RETURN_FORMAT,
				'reqnum' => $p['n'],		
				'pageflag' => $p['f'],		
				'pagetime' => $p['t']		
				);
		}
	 	return $this->oauth->get($url,$params);		
	}

	/******************
	*鑾峰彇璇濋id
	*@list: 璇濋鍚嶅瓧鍒楄〃锛坅bc,efg,锛?
	**********************/	
	public function getTopicId($p){
			$url = 'http://open.t.qq.com/api/ht/ids?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'httexts' => $p['list']
		);
	 	return $this->oauth->get($url,$params);		
	}	

	/******************
	*鑾峰彇璇濋鍐呭
	*@list: 璇濋id鍒楄〃锛坅bc,efg,锛?
	**********************/	
	public function getTopicList($p){
			$url = 'http://open.t.qq.com/api/ht/info?f=1';
		$params = array(
			'format' => MB_RETURN_FORMAT,
			'ids' => $p['list']
		);
	 	return $this->oauth->get($url,$params);		
	}		
}
?>
