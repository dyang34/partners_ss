<?
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

$uid = RequestUtil::getParam("uid", "");

if(empty($uid)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString("uid","=",$uid);

if(ToursafeMembersMgr::getInstance()->exists($wq)) {
	$rtnVal['RESULTCD'] = "801";
	$rtnVal['RESULTMSG'] = "이미 존재하는 아이디입니다.";
} else {
	$rtnVal['RESULTCD'] = "200";
	$rtnVal['RESULTMSG'] = "SUCCESS";
}

echo json_encode($rtnVal);
exit;
?>