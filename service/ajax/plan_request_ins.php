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

$hana_plan_no = RequestUtil::getParam("hana_plan_no","");
$member_no = RequestUtil::getParam("member_no","");
$content = RequestUtil::getParam("content","");
$change_type = RequestUtil::getParam("change_type","");

if(empty($hana_plan_no) || empty($member_no)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString("member_no","=",$member_no);
$wq->addAndString("plan_no","=",$hana_plan_no);
$row = HanaPlanRequestMgr::getInstance()->getFirst($wq);

if(!empty($row)) {
	$arrInsReq = array();
	$arrInsReq["plan_no"] = $hana_plan_no;
	$arrInsReq["member_no"] = $member_no;
	$arrInsReq["change_type"] = $change_type;
	$arrInsReq["content"] = $content;
} else {

}

$uq = new UpdateQuery();
$uq->add("plan_list_state","$change_type");

HanaPlanMgr::getInstance()->update($hana_plan_no, $uq);


<?/*

$sql="insert hana_plan_request set
		plan_no='".$select_num."',
		member_no='".$row_mem_info['no']."',
		change_type='".$change_type."',
		change_state='1',
		content='".$content."',
		regdate='".time()."'
	";
mysql_query($sql);

$sql="update hana_plan set
		plan_list_state='".$change_type."'
	  where no='".$select_num."'
	";
mysql_query($sql);

*/?>








$rtnVal['RESULTCD'] = "200";
$rtnVal['RESULTMSG'] = "SUCCESS";
$rtnVal['DATA'] = $row;
echo json_encode($rtnVal);
exit;
?>