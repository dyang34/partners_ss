<?
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/HiworksApiUtil.php";

//require_once $_SERVER['DOCUMENT_ROOT']."/lib/mail_sender.php";

$uid = RequestUtil::getParam("uid", "");
$com_name = RequestUtil::getParam("com_name", "");
$email = RequestUtil::getParam("email", "");

if(empty($uid) || empty($com_name) || empty($email)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

$wq = new WhereQuery(true, true);
$wq->addAndString("uid","=",$uid);
$wq->addAndString("com_name","=",$com_name);
$wq->addAndString("email","=",encode_pass(trim($email), $pass_key));

$row = ToursafeMembersMgr::getInstance()->getFirst($wq);

if(empty($row)) {
	$rtnVal['RESULTCD'] = "801";
	$rtnVal['RESULTMSG'] = "해당 정보의 아이디가 존재하지 않습니다.";
} else {

	$param = $uid."|".date('YmdHis')."|".$row["upw"];
	
	$link = "https://b2b.udirect.co.kr/service/member/reset_pw.php?p1=".encode_pass(trim($param), $pass_key);

	$mail_sender = "no-reply@udirect.co.kr";
	$email_title = "투어세이프 B2B시스템 비밀번호 재설정";
	$email_content = "안녕하세요.<br/><br/>
저희 서비스를 이용해주셔서 감사합니다.<br/><br/>
아래 링크를 클릭하시고 새로운 비밀번호를 설정해주세요.<br/><br/>
<a href='".$link."' target='_blank'>".$link."</a><br/><br/>
※ 위 링크는 24시간만 유효합니다.<br/><br/>
문의사항이 있으시다면 언제든지 저희 고객센터(1800-9010)로 문의해주시기 바랍니다.<br/><br/>
감사합니다.";

	HiworksApiUtil::sendMail("bis", $email, "","", $email_title, $email_content);
	
	$rtnVal['RESULTCD'] = "200";
	$rtnVal['RESULTMSG'] = "SUCCESS";
}

echo json_encode($rtnVal);
exit;
?>