<?php
/**
 * 用户模块
 * @param 
 * @return
 * @author luckyxiang
 * @package /application/controller/
 */
require_once MB_CTRL_DIR.'/base_mod.class.php';
require_once MB_COMM_DIR.'/city.class.php';

class UserMod extends BaseMod
{	
	/**
	 * (设置)用户头像
	 * @return unknown_type
	 */
	public function userHeadAct()
	{
		$data = array();
		try
		{	if (isset($_GET["url"])&&$_GET["url"]!="")
			{	
				header('Content-Type:image/gif');
				$img = file_get_contents($_GET["url"]);
				echo($img);
				return;
			}
			if (!empty($GLOBALS['HTTP_RAW_POST_DATA'])||MBValidator::isUploadFile($_FILES["pic"]))
			{
				if(!empty($GLOBALS['HTTP_RAW_POST_DATA']))
				{//通过flash截图
					 $name =time().'.jpg';
					 $fileContent=$GLOBALS['HTTP_RAW_POST_DATA'];
					 $len=strlen($fileContent);
					 
					 /*
					 $name =time().'.jpg';
						$im = fopen($name,'a' );
						fwrite($im,$HTTP_RAW_POST_DATA);
						fclose($im);
						*/
				}
				else
				{//通过表单上传
					$len = intval($_FILES["pic"]["size"]);
					$fileContent =  file_get_contents($_FILES["pic"]["tmp_name"]);
					$name=$_FILES["pic"]["name"];
				}
				$picType = MBUtil::getFileType(substr($fileContent, 0, 2));
				if($len < 2 || $len > 2*1024*1024)		//图片最大2M
				{
					throw new MBException("最大支持2M图片");
				}
				
				if($picType!="jpg" && $picType!="gif" && $picType!="png")
				{
					throw new MBException("图片仅支持jpg/jpeg/gif/png类型");
				}
				//pic参数是个数组
				//$p = array("pic" => array($_FILES["pic"]["type"], $_FILES["pic"]["name"], $fileContent));
				$p = array("pic" => array($picType,$name, $fileContent));//echo($p["pic"][1]);return;
				MBGlobal::getApiClient()->updateUserHead($p);
				$data["message"] = array("type"=>"success", "text"=>"头像上传成功！");
			}
		}
		catch(MBException $e)
		{
			$data["message"] = array("type"=>"error", "text"=>$e->getMessage());
		}
		usleep(100000);			//给点时间set
		//get
		$userInfo = MBGlobal::getApiClient()->getUserInfo();	
		
		$data["title"] = "个人头像设置";
		$data["user"] = $userInfo["data"];
		if(!empty($data["user"]["head"]))
		{
			$data["user"]["head"] .= "/120";
		}
		echo BaseMod::renderView($data, "setting_head.view.php");
		return;
	}
	
	
	/**
	 * (设置)用户信息
	 * @return unknown_type
	 */
	public function userInfoAct()
	{	
		$p = array();
		$error = array();
		
		//昵称
		if(isset($_POST["nick"]))
		{
			$nick = $_POST["nick"];
			$nick =preg_replace("/[:\"\'\s\]\[\(\)]/","",$nick);
			//$nick=str_replace("'","",str_replace("\"","",$nick));
			if(!MBValidator::isUserNick($nick))
			{
				$error["nick"] = "仅支持中文、字母、数字、下划线或减号";
			}
			$p["nick"] = $nick;
		}
		//性别 0 ，1：男2：女
		if(isset($_POST["sex"]))
		{
			$sex = intval($_POST["sex"]);
			if($sex < 1 || $sex > 2)
			{
				$sex = 2;
			}
			$p["sex"] = $sex;
		}
		//出生年
		if(isset($_POST["year"]))
		{
			$year = intval($_POST["year"]);
			$thisYear = date("Y");
			if($year < 1891 || $year > $thisYear)
			{
				$error["birthday"] = "年份错误";
				
			}
			$p["year"] = $year;
		}
		//出生月
		if(isset($_POST["month"]))
		{
			$month = intval($_POST["month"]);
			if($month < 1 || $month > 12)
			{
				$error["birthday"] = "月份错误";
			}
			$p["month"] = $month;
		}
		//出生日
		if(isset($_POST["day"]))
		{
			$day = intval($_POST["day"]);
			if($day < 1 || $day > 31)
			{
				$error["birthday"] = "日期错误";
			}
			$p["day"] = $day;
		}
		//国家码
		if(isset($_POST["countrycode"]))
		{
			$countrycode = $_POST["countrycode"];
			if(!key_exists($countrycode, (array)MBCity::$cityConfig))
			{
				$error["location"] = "国家错误";
			}
			$p["countrycode"] = $countrycode;
		}
		//地区码(可能没有,?表示没省份)
		if(isset($_POST["provincecode"]) && $_POST["provincecode"]!="?")
		{
			$provincecode = $_POST["provincecode"];
			if(!key_exists($provincecode, (array)MBCity::$cityConfig[$countrycode]["province"]))
			{
				$error["location"] = "地区错误";
			}
			$p["provincecode"] = $provincecode;
		}
		else
		{
			$p["provincecode"] = 0;
		}
		//城市码(可能没有)
		if(isset($_POST["citycode"]))
		{
			$citycode = $_POST["citycode"];
			if(!key_exists($citycode, (array)MBCity::$cityConfig[$countrycode]["province"][$provincecode]["city"]))
			{
				$error["location"] = "城市错误";
			}
			$p["citycode"] = $citycode;
		}
		else
		{
			$p["citycode"] = 0;
		}
		//个人介绍
		if(isset($_POST["introduction"]))
		{
			$introduction = $_POST["introduction"];
			$introduction = str_replace("<","&lt;",str_replace(">","&gt;",$introduction));
			if(strlen($introduction) > 140*3)
			{
				$error["introduction"] = "个人介绍超长";
			}
			$p["introduction"] = $introduction;
		}
		
		if(empty($error))			//没有错误
		{
			$data = array();
			if(count($p) == 9)			//set操作,所有信息都得有
			{
				try 
				{
					MBGlobal::getApiClient()->updateMyinfo($p);
					$data["message"] = array("type"=>"success", "text"=>"个人资料保存成功！");
					usleep(100000);			//给点时间set
				}
				catch(MBException $e)
				{
					$data["message"] = array("type"=>"error", "text"=>"个人资料保存失败！");
				}
			}
			//get
			$userInfo = MBGlobal::getApiClient()->getUserInfo();
			if(empty($userInfo["data"]["province_code"]))
			{
				$userInfo["data"]["province_code"] = 0;	
			}
			//API BUG
			//$userInfo["data"]["country_code"] = strrev($userInfo["data"]["country_code"]);
			//$userInfo["data"]["province_code"] = strrev($userInfo["data"]["province_code"]);
			//$userInfo["data"]["city_code"] = strrev($userInfo["data"]["city_code"]);
			$data["user"] = $userInfo["data"];
			
			//get到的数据有可能set还未生效
			if(count($p) == 9)
			{
				if(!empty($p["nick"]))
				{
					$userInfo["data"]["nick"] = $p["nick"];
				}
				if(!empty($p["sex"]))
				{
					$userInfo["data"]["sex"] = $p["sex"];
				}
				if(!empty($p["year"]))
				{
					$userInfo["data"]["birth_year"] = $p["year"];
				}
				if(!empty($p["month"]))
				{
					$userInfo["data"]["birth_month"] = $p["month"];
				}
				if(!empty($p["day"]))
				{
					$userInfo["data"]["birth_day"] = $p["day"];
				}
				if(!empty($p["countrycode"]))
				{
					$userInfo["data"]["country_code"] = $p["countrycode"];
				}
				if(!empty($p["provincecode"]))
				{
					$userInfo["data"]["province_code"] = $p["provincecode"];
				}
				if(!empty($p["citycode"]))
				{
					$userInfo["data"]["city_code"] = $p["citycode"];
				}
				$userInfo["data"]["introduction"] = $p["introduction"];
			}
			
			//模版数据
			$data["title"] = "个人资料设置";
			$data["formuser"] = $userInfo["data"];
			
			echo BaseMod::renderView($data, "setting_profile.view.php");
			return;
		}
		else			//有错误肯定是set操作
		{
			$userInfo = MBGlobal::getApiClient()->getUserInfo();
			if(empty($userInfo["data"]["province_code"]))
			{
				$userInfo["data"]["province_code"] = 0;	
			}
			//API BUG
			//$userInfo["data"]["country_code"] = strrev($userInfo["data"]["country_code"]);
			//$userInfo["data"]["province_code"] = strrev($userInfo["data"]["province_code"]);
			//$userInfo["data"]["city_code"] = strrev($userInfo["data"]["city_code"]);
			
			$data = array();
			$data["user"] = $userInfo["data"];
			$data["title"] = "个人资料设置";
			$data["formuser"] = $p;
			//$data["name"] = $_SESSION["name"];
			$data["formerror"] = $error;
			echo BaseMod::renderView($data, "setting_profile.view.php");
			return;
		}
	}
}
?>