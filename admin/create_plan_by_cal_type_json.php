<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/plan/PlanCodeHanaMgr.php";

$company_type = RequestUtil::getParam("company_type", "");
$p_member_no = RequestUtil::getParam("member_no", "");
$member_no = "";
/*
if(empty($company_type)) {
	JsUtil::alertBack('company_type 파라미터값이 없습니다.'    );
	exit;
}
*/

if(!empty($company_type)) {
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
}

$wq = new WhereQuery(true, true);
$wq->addAndString("company_type","=",$company_type);
$wq->addAndString("member_no","=",$member_no);
$wq->addOrderBy("company_type","asc");
$wq->addOrderBy("member_no","asc");
$wq->addOrderBy("trip_type","asc");
$wq->addOrderBy("plan_type","asc");
$rs = PlanCodeHanaMgr::getInstance()->getPlanList($wq);

$arrPlanList = $arrPlanCompanyList = $arrPlanMemberList = $arrPlanTripType = $arrPlanPlanType = array();
$prevCompanyType = $prevMemberNo = $prevTripType = $prevPlanType = $prevPlanRepreTitle = "";

for($i=0;$i<$rs->num_rows;$i++) {
	$row = $rs->fetch_assoc();

	if($prevPlanType!=$row['plan_type'] && $prevPlanType!="") {
//		$arrPlanTripType[$prevPlanType]['planRepreTitle']=$prevPlanRepreTitle;
//		$arrPlanTripType[$prevPlanType]['List']=$arrPlanPlanType;
		$arrPlanTripType[$prevPlanType]=$arrPlanPlanType;
		$arrPlanPlanType = array();
	}

	if($prevTripType!=$row['trip_type'] && $prevTripType!="") {
		$arrPlanMemberList[$prevTripType]=$arrPlanTripType;
		$arrPlanTripType = array();
	}

	if($prevMemberNo!=$row['member_no'] && $prevMemberNo!="") {
		$arrPlanCompanyList[$prevMemberNo]=$arrPlanMemberList;
		$arrPlanMemberList = array();
	}

	if($prevCompanyType!=$row['company_type'] && $prevCompanyType!="") {
		$arrPlanList[$prevCompanyType]['company_name']=$arrInsuranceCompany[$prevCompanyType];
		$arrPlanList[$prevCompanyType]['List']=$arrPlanCompanyList;
		$arrPlanCompanyList = array();
	}
/*
	if($prevPlanType!=$row['plan_type'] || $prevTripType!=$row['trip_type'] || $prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) {
		$arrPlanPlanType[$row['plan_type']]["List"] = array();
	}
*/
	$arrPlanPlanType[$row['cal_type']] = $row;

	$prevCompanyType = $row['company_type'];
	$prevMemberNo = $row['member_no'];
	$prevTripType = $row['trip_type'];
	$prevPlanType = $row['plan_type'];
	$prevPlanRepreTitle = $row['plan_repre_title'];
}

//$arrPlanTripType[$prevPlanType]['planRepreTitle']=$prevPlanRepreTitle;
//$arrPlanTripType[$prevPlanType]['List']=$arrPlanPlanType;
$arrPlanTripType[$prevPlanType]=$arrPlanPlanType;
$arrPlanMemberList[$prevTripType]=$arrPlanTripType;
$arrPlanCompanyList[$prevMemberNo]=$arrPlanMemberList;
$arrPlanList[$prevCompanyType]['company_name']=$arrInsuranceCompany[$prevCompanyType];
$arrPlanList[$prevCompanyType]['List']=$arrPlanCompanyList;

$file_name = $_SERVER['DOCUMENT_ROOT']."/config/"."plan_list";

if(!empty($company_type)) {
	$file_name .= "_".$company_type;
}

if(!empty($p_member_no)) {
	$file_name .= "_".$p_member_no;
}

$file_name .= ".json";

file_put_contents($file_name, json_encode($arrPlanList));

/*
$fp = fopen($file_name, 'cw');
if(is_array($arrPlanList)) {
    fwrite($fp, var_export($arrPlanList, TRUE)); //or print_r()
}
fclose($fp);
*/
/*
echo "<pre>";
print_r($arrPlanList);
echo "</pre>";
*/
echo "Ok!";
?>