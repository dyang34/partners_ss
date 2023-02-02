<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/plan/PlanCodeHanaMgr.php";

$company_type = RequestUtil::getParam("company_type", "");
$p_member_no = RequestUtil::getParam("member_no", "");

if(empty($company_type)) {
	JsUtil::alertBack('company_type 파라미터값이 없습니다.'    );
	exit;
}

if(empty($p_member_no)) {
	switch($company_type) {
		case "1":
			$member_no = 25;
			break;
		case "2":
			$member_no = 20000;
			break;
		case "3":
			$member_no = 30000;
			break;
		case "4":
			$member_no = 40000;
			break;
		case "5":
			$member_no = 50000;
			break;
		default:
			JsUtil::alertBack('유효하지 않은 company_type입니다.'    );
			exit;
	}
} else {
	$member_no = $p_member_no;
}

$wq = new WhereQuery(true, true);
$wq->addAndString("company_type","=",$company_type);
$wq->addAndString("member_no","=",$member_no);
$rs = PlanCodeHanaMgr::getInstance()->getPlanList($wq);

$arrPlanList = $arrPlanTripType = $arrPlanTripTypePlanType = array();
$prevTripType = $prevPlanType = $prevPlanRepreTitle = "";
for($i=0;$i<$rs->num_rows;$i++) {
	$row = $rs->fetch_assoc();

	if($prevPlanType!=$row['plan_type'] && $prevPlanType!="") {
		array_push($arrPlanTripType, ['planRepreTitle'=>$prevPlanRepreTitle, 'planType'=>$prevPlanType, 'List'=>$arrPlanTripTypePlanType]);
		$arrPlanTripTypePlanType = array();
	}

	if($prevTripType!=$row['trip_type'] && $prevTripType!="") {
		array_push($arrPlanList, $arrPlanTripType);
		$arrPlanTripType = array();
	}

	array_push($arrPlanTripTypePlanType, $row);

	$prevTripType = $row['trip_type'];
	$prevPlanType = $row['plan_type'];
	$prevPlanRepreTitle = $row['plan_repre_title'];
}

array_push($arrPlanTripType, ['planRepreTitle'=>$prevPlanRepreTitle, 'planType'=>$prevPlanType, 'List'=>$arrPlanTripTypePlanType]);
array_push($arrPlanList, $arrPlanTripType);

$file_name = "plan_list_".$company_type;
if(empty($p_member_no)) {
	$file_name .= ".json";
} else {
	$file_name .= "_".$member_no.".json";
}
$file_name = $_SERVER['DOCUMENT_ROOT']."/config/".$file_name;

file_put_contents($file_name, json_encode($arrPlanList));

/*
$fp = fopen($file_name, 'cw');
if(is_array($arrPlanList)) {
    fwrite($fp, var_export($arrPlanList, TRUE)); //or print_r()
}
fclose($fp);
*/

echo "Ok!";
?>