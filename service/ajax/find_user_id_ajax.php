<?
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

$com_name = RequestUtil::getParam("com_name", "");
$email = RequestUtil::getParam("email", "");

if(empty($com_name) || empty($email)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString("com_name","=",$com_name);
$wq->addAndString("email","=",encode_pass(trim($email), $pass_key));

$row = ToursafeMembersMgr::getInstance()->getFirst($wq);

if(empty($row)) {
	$rtnVal['RESULTCD'] = "801";
	$rtnVal['RESULTMSG'] = "해당 정보의 아이디가 존재하지 않습니다.";
} else {
	$rtnVal['RESULTCD'] = "200";
	$rtnVal['RESULTMSG'] = $row["uid"];
}

echo json_encode($rtnVal);
exit;
?>