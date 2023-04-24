<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersCompanyMappingMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanChangeMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
	exit;
}

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");

$company_type = RequestUtil::getParam("company_type","");
$manager_idx = RequestUtil::getParam("manager_idx","");
$manager_name = RequestUtil::getParam("manager_name","");
$trip_type = RequestUtil::getParam("trip_type","");
$nation = RequestUtil::getParam("nation","");
$start_date = RequestUtil::getParam("start_date","");
$end_date = RequestUtil::getParam("end_date","");
$start_time = RequestUtil::getParam("start_time","00:00:00");
$end_time = RequestUtil::getParam("end_time","24:00:00");
$trip_purpose = RequestUtil::getParam("trip_purpose","");
$add_info1 = RequestUtil::getParam("add_info1","");
$add_info2 = RequestUtil::getParam("add_info2","");
$term_day = RequestUtil::getParam("term_day","");
$plan_repre_type = RequestUtil::getParam("plan_repre_type","");
$repre_email = RequestUtil::getParam("repre_email","");
$repre_hp = RequestUtil::getParam("repre_hp","");

$arr_name = RequestUtil::getParam("name","");
$arr_name_eng = RequestUtil::getParam("name_eng","");
$arr_jumin = RequestUtil::getParam("jumin","");
$arr_gender = RequestUtil::getParam("gender","");
$arr_age = RequestUtil::getParam("age","");
$arr_cal_type = RequestUtil::getParam("cal_type","");
$arr_plan_code = RequestUtil::getParam("plan_code","");
$arr_plan_type = RequestUtil::getParam("plan_type","");
$arr_price = RequestUtil::getParam("price","");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

$repre_hp = str_replace("-", "", $repre_hp);

$price_sum = $join_cnt = 0;
$join_name = "";
for($i=0;$i<count($arr_price);$i++) {
	if(!empty($arr_price[$i]) && !empty($arr_name[$i]) && !empty($arr_jumin[$i])) {
		$arr_price[$i] = (int)preg_replace("/[^0-9]/", "",$arr_price[$i]);
		$price_sum += $arr_price[$i];
		$join_cnt += 1;

		if(empty($join_name)) {
			$join_name = $arr_name[$i].($arr_name_eng[$i]?" / ".$arr_name_eng[$i]:"");
		}
	}
}

$session_key = date("Ymd")."|".$trip_type."|".$nation."|".$term_day."|".$price_sum."|".time();

if(!empty($join_name)) {

	$arrIns = array();
	$arrIns["company_type"] = $company_type;
	$arrIns["join_name"] = $join_name;
	$arrIns["member_no"] = $__CONFIG_MEMBER_NO;
	$arrIns["session_key"] = $session_key;
	$arrIns["plan_list_state"] = "1";
	$arrIns["order_type"] = "1";
	$arrIns["trip_type"] = $trip_type;
	$arrIns["nation_no"] = $nation;
	$arrIns["trip_purpose"] = $trip_purpose;
	$arrIns["start_date"] = $start_date;
	$arrIns["start_hour"] = mb_substr($start_time,0,2);
	$arrIns["end_date"] = $end_date;
	$arrIns["end_hour"] = mb_substr($end_time,0,2);
	$arrIns["term_day"] = $term_day;
	$arrIns["join_cnt"] = $join_cnt;
	$arrIns["plan_type"] = $plan_repre_type;
	$arrIns["manager_idx"] = $manager_idx;
	$arrIns["manager_name"] = $manager_name;
	$arrIns["add_info1"] = $add_info1;
	$arrIns["add_info2"] = $add_info2;
	$arrIns["referer_type"] = "2";

	$hana_plan_no = HanaPlanMgr::getInstance()->add($arrIns);

	$main_check = "Y";
	for($i=0;$i<count($arr_price);$i++) {
		if(!empty($arr_price[$i]) && !empty($arr_name[$i]) && !empty($arr_jumin[$i])) {
			$arrMemIns = array();
			$arrMemIns["company_type"] = $company_type;
			$arrMemIns["member_no"] = $__CONFIG_MEMBER_NO;
			$arrMemIns["hana_plan_no"] = $hana_plan_no;
			$arrMemIns["name"] = $arr_name[$i];
			$arrMemIns["name_eng"] = $arr_name_eng[$i];
	//		$arrMemIns["name_eng_first"] = 
	//		$arrMemIns["name_eng_last"] = 

			$arrMemIns["main_check"] = $main_check;
			if($main_check=="Y") {
				$arrMemIns["hphone"] = encode_pass($repre_hp, $pass_key);
				$arrMemIns["email"] = encode_pass($repre_email, $pass_key);
				$main_check = "N";
			}

			$arrMemIns["jumin_1"] = encode_pass(mb_substr($arr_jumin[$i],0,6),$pass_key);
			$arrMemIns["jumin_2"] = encode_pass(mb_substr($arr_jumin[$i],6,7),$pass_key);
			$arrMemIns["plan_code"] = $arr_plan_code[$i];
	//		$arrMemIns["plan_title"] = 
	//		$arrMemIns["plan_title_src"] = 
			$arrMemIns["plan_price"] = $arr_price[$i];
			$arrMemIns["sex"] = $arr_gender[$i];
			$arrMemIns["age"] = $arr_age[$i];

			HanaPlanMemberMgr::getInstance()->add_simple($arrMemIns);
		}
	}

	$wq = new WhereQuery(true, true);
	$wq->addAndString("member_no","=",$__CONFIG_MEMBER_NO);
	$wq->addAndString("company_type","=",$company_type);
	$wq->addAndString("trip_type","=",$trip_type);
	$row = ToursafeMembersCompanyMappingMgr::getInstance()->getFirst($wq);

	$arrChangeIns = array();
	$arrChangeIns["hana_plan_no"] = $hana_plan_no;
	$arrChangeIns["change_type"] = "1";
	$arrChangeIns["change_price"] = $price_sum;
	$arrChangeIns["in_price"] = 0;
	$arrChangeIns["com_percent"] = $row["com_percent"];
	$arrChangeIns["company_type"] = $company_type;

	HanaPlanChangeMgr::getInstance()->add($arrChangeIns);

	JsUtil::replace("./inquiry.php");						
 
} else {
    JsUtil::alertBack("비정상적인 접근입니다.    ");
    exit;
}
?>