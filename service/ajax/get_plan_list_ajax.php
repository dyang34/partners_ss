<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_array.php";


if(!LoginManager::isUserLogined()) {
	$rtnVal['RESULTCD'] = "900";
	$rtnVal['RESULTMSG'] = "로그인이 필요합니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$company_type = RequestUtil::getParam("company_type","");
$member_no = RequestUtil::getParam("member_no","");
$trip_type = RequestUtil::getParam("trip_type","");
if(empty($company_type) || empty($member_no) || empty($trip_type)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$rtnVal['RESULTCD'] = "200";
$rtnVal['RESULTMSG'] = "SUCCESS";
$rtnVal['LIST_REPRE'] = $__ARR_CONFIG_PLAN_REPRE[$company_type]['List'][$member_no][$trip_type];
$rtnVal['LIST'] = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type];
$rtnVal['CNT_CAL_TYPE'] = count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type]);
echo json_encode($rtnVal);
exit;
?>