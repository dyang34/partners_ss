<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/plan/PlanCodeTypeHanaMgr.php";

$company_type = RequestUtil::getParam("company_type", "");
$p_member_no = RequestUtil::getParam("member_no", "");
$member_no = "";

$arrPlanTypeList = $arrPlanTypeCompanyList = $arrPlanTypeMemberList = $arrPlanTypeTripType = $arrPlanTypePlanCode = $arrPlanTypeSex = $arrPlanTypeTermDay = array();
$prevCompanyType = $prevMemberNo = $prevTripType = $prevPlanCode = $prevSex = $prevTermDay = "";

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
$wq->addOrderBy("cast(replace(plan_type, 'type_', '') AS unsigned)","asc");

$rs = PlanCodeTypeHanaMgr::getInstance()->getList($wq);

$arrPlanTypeList = $arrPlanTypeCompanyList = $arrPlanTypeMemberList = $arrPlanTypeTripType = $arrPlanTypePlanType = array();
$prevCompanyType = $prevMemberNo = $prevTripType = "";

for($i=0;$i<$rs->num_rows;$i++) {
	$row = $rs->fetch_assoc();

	if($prevTripType!=$row['trip_type'] && $prevTripType!="") {
		$arrPlanTypeMemberList[$prevTripType]=$arrPlanTypeTripType;
		$arrPlanTypeTripType = array();
	}

	if($prevMemberNo!=$row['member_no'] && $prevMemberNo!="") {
		$arrPlanTypeCompanyList[$prevMemberNo]=$arrPlanTypeMemberList;
		$arrPlanTypeMemberList = array();
	}

	if($prevCompanyType!=$row['company_type'] && $prevCompanyType!="") {
		$arrPlanTypeList[$prevCompanyType]=$arrPlanTypeCompanyList;
		$arrPlanTypeCompanyList = array();
	}
	
	$arrPlanTypeTripType[$row['plan_type']] = ["title"=>$row['title'], "content"=>$row['content']];

	$prevCompanyType = $row['company_type'];
	$prevMemberNo = $row['member_no'];
	$prevTripType = $row['trip_type'];
}

$arrPlanTypeMemberList[$prevTripType]=$arrPlanTypeTripType;
$arrPlanTypeCompanyList[$prevMemberNo]=$arrPlanTypeMemberList;
$arrPlanTypeList[$prevCompanyType]=$arrPlanTypeCompanyList;

$file_name = $_SERVER['DOCUMENT_ROOT']."/config/"."plan_type_list";

if(!empty($company_type)) {
	$file_name .= "_".$company_type;
}

if(!empty($p_member_no)) {
	$file_name .= "_".$p_member_no;
}

$file_name .= ".json";

file_put_contents($file_name, json_encode($arrPlanTypeList));

/*
echo "<pre>";
print_r($arrPlanTypeList);
echo "</pre>";
*/

echo "Ok!";
?>