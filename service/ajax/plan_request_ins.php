<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanRequestMgr.php";

if(!LoginManager::isUserLogined()) {
	$rtnVal['RESULTCD'] = "900";
	$rtnVal['RESULTMSG'] = "로그인이 필요합니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$company_type = RequestUtil::getParam("company_type","");
$hana_plan_no = RequestUtil::getParam("hana_plan_no","");
$member_no = RequestUtil::getParam("member_no","");
$content = RequestUtil::getParam("content","");
$change_type = RequestUtil::getParam("change_type","");

if(empty($hana_plan_no) || empty($member_no) || empty($change_type) || empty($content)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$row = HanaPlanMgr::getInstance()->getByKey($hana_plan_no);

if (!in_array($row["plan_list_state"], $arrPlanStateUpdatable)) {
	$rtnVal['RESULTCD'] = "801";
	$rtnVal['RESULTMSG'] = "수정할 수 없는 상태입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString("member_no","=",$member_no);
$wq->addAndString("plan_no","=",$hana_plan_no);
$row_req = HanaPlanRequestMgr::getInstance()->getFirst($wq);

if(empty($row_req)) {
	$arrInsReq = array();
	$arrInsReq["plan_no"] = $hana_plan_no;
	$arrInsReq["member_no"] = $member_no;
	$arrInsReq["change_type"] = $change_type;
	$arrInsReq["content"] = $content;

	HanaPlanRequestMgr::getInstance()->add($arrInsReq);
} else {
	$uq = new UpdateQuery();
	$uq->add("change_type", $change_type);
	$uq->add("change_state", "1");
	$uq->add("content", $content);
	$uq->add("regdate", time());
	
	HanaPlanRequestMgr::getInstance()->edit($uq, $row_req["no"]);
}

$uq = new UpdateQuery();
$uq->add("plan_list_state", $change_type);
HanaPlanMgr::getInstance()->edit($uq, $hana_plan_no);

$rtnVal['RESULTCD'] = "200";
$rtnVal['RESULTMSG'] = "SUCCESS";
$rtnVal['STATUS_TEXT'] = $arrPlanStateText[$change_type];
echo json_encode($rtnVal);
exit;
?>