<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanRequestMgr.php";

if(!LoginManager::isUserLogined()) {
	$rtnVal['RESULTCD'] = "900";
	$rtnVal['RESULTMSG'] = "로그인이 필요합니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$company_type = RequestUtil::getParam("company_type","");
$member_no = RequestUtil::getParam("member_no","");
$hana_plan_no = RequestUtil::getParam("hana_plan_no","");

//$member_no = 28;
//$hana_plan_no = 27049;

if(empty($company_type) || empty($member_no) || empty($hana_plan_no)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString("member_no","=",$member_no);
$wq->addAndString("plan_no","=",$hana_plan_no);
$row = HanaPlanRequestMgr::getInstance()->getFirst($wq);

$illegal = array('&#40;', '&#41;');
$replace = array('(', ')');
$row['content'] = str_replace($illegal, $replace, stripslashes($row['content']));

$rtnVal['RESULTCD'] = "200";
$rtnVal['RESULTMSG'] = "SUCCESS";
$rtnVal['DATA'] = $row;
echo json_encode($rtnVal);
exit;
?>