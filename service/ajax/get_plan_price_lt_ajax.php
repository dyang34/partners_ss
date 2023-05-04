<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_array.php";

$CONFIG_PLAN_FILE_TRIP_TYPE = RequestUtil::getParam("trip_type","");

require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_price_array.php";

if(!LoginManager::isUserLogined()) {
	$rtnVal['RESULTCD'] = "900";
	$rtnVal['RESULTMSG'] = "로그인이 필요합니다.    ";
	echo json_encode($rtnVal);
	exit;
}

if(empty($CONFIG_PLAN_FILE_TRIP_TYPE)) {
	$rtnVal['RESULTCD'] = "801";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$term_day = -1;
$company_type = RequestUtil::getParam("company_type","");
$member_no = RequestUtil::getParam("member_no","");
$plan_code = RequestUtil::getParam("plan_code","");
$gender = RequestUtil::getParam("gender","");
$age = RequestUtil::getParam("age","");
$start_date = RequestUtil::getParam("start_date","");
$end_date = RequestUtil::getParam("end_date","");
$start_time = RequestUtil::getParam("start_time","00:00:00");
$end_time = RequestUtil::getParam("end_time","24:00:00");

$start_year = substr($start_date,0,4);	
$start_month = substr($start_date,5,2);
$start_day = substr($start_date,8,2);
$end_year = substr($end_date,0,4);
$end_month = substr($end_date,5,2);
$end_day = substr($end_date,8,2);

$totalday = ceil((strtotime($end_date." ".$end_time) - strtotime($start_date." ".$start_time)) / (60*60*24));
$years = $end_year - $start_year;
$months = ($years * 12) + (int)$end_month - (int)$start_month;
$days = (int)$end_day - (int)$start_day;
$hours = (int)$end_time - (int)$start_time;	
/*
if($age > 100) {
	$age=100;
}
*/
if(empty($company_type) || empty($member_no) || empty($CONFIG_PLAN_FILE_TRIP_TYPE) || empty($plan_code) || empty($gender) || $age=="" || empty($start_date) || empty($end_date)) {
	$rtnVal['RESULTCD'] = "801";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

if ( $totalday <= 0) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "여행기간 에러입니다. 여행 시작일이 종료일보다 늦습니다.".$totalday;
	echo json_encode($rtnVal);
	exit;
}

if ( $totalday < 91) {
	$rtnVal['RESULTCD'] = "902";
	$rtnVal['RESULTMSG'] = "3개월 초과만 가입 가능합니다.";
	echo json_encode($rtnVal);
	exit;
}

if ($age > $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE])][0]["plan_end_age"]) {
	$rtnVal['RESULTCD'] = "803";
	$rtnVal['RESULTMSG'] = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE])][0]["plan_end_age"]."세까지 가입 가능합니다.";
	echo json_encode($rtnVal);
	exit;
}

if($totalday <= 27) {
	for($i=0;$i<count($__ARR_CONFIG_PLAN_PRICE_BOUNDARY[$company_type][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$plan_code]);$i++) {
		if($totalday <= $__ARR_CONFIG_PLAN_PRICE_BOUNDARY[$company_type][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$plan_code][$i]) {
			$term_day = $__ARR_CONFIG_PLAN_PRICE_BOUNDARY[$company_type][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$plan_code][$i];
			break;
		}
	}
} else {
	if($months == 0){
			$term_day = 30;
			//1개월인경우
	} elseif($months == 1){
		if($totalday <= 45){
			if($days < 0 || ($days == 0 && $hours <= 0)){
				$term_day = 30;
			} else {
				$term_day = 45;
			}
		} else {
			$term_day = 60;
		}
	} else {
		if($totalday <= 45) {
			$term_day = 45;				
		} else {
			if ($days > 0 || ($days == 0 && $hours > 0)) {
				$months = $months + 1;
			}
			$term_day = ($months * 30);
		}		
	}		
}

if ($CONFIG_PLAN_FILE_TRIP_TYPE=="2" && $age >= 80 && $term_day > 30) {	// $totalday > 30
	$rtnVal['RESULTCD'] = "802";
	$rtnVal['RESULTMSG'] = "80세 이상 고객님은 최대 30일까지만 가입이 가능합니다.";
	echo json_encode($rtnVal);
	exit;
}

if ( $term_day <= 0) {
	$rtnVal['RESULTCD'] = "909";
	$rtnVal['RESULTMSG'] = "시스템 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$rtnVal['RESULTCD'] = "200";
$rtnVal['RESULTMSG'] = "SUCCESS";
$rtnVal['Price'] = $__ARR_CONFIG_PLAN_PRICE[$company_type][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$plan_code][$gender][$term_day][$age];
$rtnVal['Term_Day'] = $term_day;
echo json_encode($rtnVal);
exit;
?>