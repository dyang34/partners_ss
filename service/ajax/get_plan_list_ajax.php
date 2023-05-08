<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if(!LoginManager::isUserLogined()) {
	$rtnVal['RESULTCD'] = "900";
	$rtnVal['RESULTMSG'] = "로그인이 필요합니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$CONFIG_PLAN_FILE_TRIP_TYPE = RequestUtil::getParam("trip_type","");

require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_array.php";

$company_type = RequestUtil::getParam("company_type","");
$member_no = RequestUtil::getParam("member_no","");

if(empty($company_type) || empty($member_no) || empty($CONFIG_PLAN_FILE_TRIP_TYPE)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$rtnVal['RESULTCD'] = "200";
$rtnVal['RESULTMSG'] = "SUCCESS";
$rtnVal['LIST_REPRE'] = $__ARR_CONFIG_PLAN_REPRE[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE];
$rtnVal['LIST'] = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE];
$rtnVal['CNT_CAL_TYPE'] = count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE]);
echo json_encode($rtnVal);
exit;
?>