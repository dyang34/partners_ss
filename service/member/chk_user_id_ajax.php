<?
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

$uid = RequestUtil::getParam("uid", "");

if(empty($uid)) {
	$arrRtn = array('RESULTCD'=>'NO_PARAM','RESULTMSG'=>'아이디 값이 없습니다.');
	echo json_encode($arrRtn);
	exit;
}

if(empty(ToursafeMembersMgr::getInstance()->getByKey($uid))) {
	$arrRtn = array('RESULTCD'=>'SUCCESS','RESULTMSG'=>'');
} else {
	$arrRtn = array('RESULTCD'=>'EXISTS','RESULTMSG'=>'이미 존재하는 아이디입니다.');
}

echo json_encode($arrRtn);
exit;
?>