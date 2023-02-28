<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/nation/NationMgr.php";

$nation_search = RequestUtil::getParam("nation_search","");
if(empty($nation_search)) {
	$rtnVal['RESULTCD'] = "901";
	$rtnVal['RESULTMSG'] = "필수 요청값 에러입니다.    ";
	echo json_encode($rtnVal);
	exit;
}

if($nation_search == 'ㄱ') {
	$where = " (nation_name RLIKE '^(ㄱ|ㄲ)' OR ( nation_name >= '가' AND nation_name < '나' )) ";
} else if($nation_search == 'ㄴ') {
 	$where = " (nation_name RLIKE '^ㄴ' OR ( nation_name >= '나' AND nation_name < '다' )) ";
} else if($nation_search == 'ㄷ') {
 	$where = " (nation_name RLIKE '^(ㄷ|ㄸ)' OR ( nation_name >= '다' AND nation_name < '라' )) ";
} else if($nation_search == 'ㄹ') {
 	$where = " (nation_name RLIKE '^ㄹ' OR ( nation_name >= '라' AND nation_name < '마' )) ";
} else if($nation_search == 'ㅁ') {
 	$where = " (nation_name RLIKE '^ㅁ' OR ( nation_name >= '마' AND nation_name < '바' )) ";
} else if($nation_search == 'ㅂ') {
 	$where = " (nation_name RLIKE '^ㅂ' OR ( nation_name >= '바' AND nation_name < '사' )) ";
} else if($nation_search == 'ㅅ') {
 	$where = " (nation_name RLIKE '^(ㅅ|ㅆ)' OR ( nation_name >= '사' AND nation_name < '아' )) ";
} else if($nation_search == 'ㅇ') {
 	$where = " (nation_name RLIKE '^ㅇ' OR ( nation_name >= '아' AND nation_name < '자' )) ";
} else if($nation_search == 'ㅈ') {
 	$where = " (nation_name RLIKE '^(ㅈ|ㅉ)' OR ( nation_name >= '자' AND nation_name < '차' )) ";
} else if($nation_search == 'ㅊ') {
 	$where = " (nation_name RLIKE '^ㅊ' OR ( nation_name >= '차' AND nation_name < '카' )) ";
} else if($nation_search == 'ㅋ') {
 	$where = " (nation_name RLIKE '^ㅋ' OR ( nation_name >= '카' AND nation_name < '타' )) ";
} else if($nation_search == 'ㅌ') {
 	$where = " (nation_name RLIKE '^ㅌ' OR ( nation_name >= '타' AND nation_name < '파' )) ";
} else if($nation_search == 'ㅍ') {
 	$where = " (nation_name RLIKE '^ㅍ' OR ( nation_name >= '파' AND nation_name < '하' )) ";
} else if($nation_search == 'ㅎ') {
	$where = " (nation_name RLIKE '^ㅎ' OR ( nation_name >= '하')) ";
} else {
	$where = " (nation_name LIKE '".$nation_search."%')";
}

$wq = new WhereQuery(true,true);
$wq->addAnd2($where);
$rs = NationMgr::getInstance()->getList($wq);

$arrRtn = array();
if($rs->num_rows > 0) {
	for($i=0;$i<$rs->num_rows;$i++) {
		$row = $rs->fetch_assoc();
		array_push($arrRtn, array('nation_code'=>$row['no'],'nation_name'=>$row['nation_name']));
	}
}

$rtnVal['RESULTCD'] = "200";
$rtnVal['RESULTMSG'] = "SUCCESS";
$rtnVal['LIST'] = $arrRtn;
echo json_encode($rtnVal);
exit;
?>