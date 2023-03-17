<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/plan/PlanCodePriceHanaMgr.php";

$company_type = RequestUtil::getParam("company_type", "");
$p_member_no = RequestUtil::getParam("member_no", "");
$member_no = "";

$arrPlanPriceList = $arrPlanPriceCompanyList = $arrPlanPriceMemberList = $arrPlanPriceTripType = $arrPlanPricePlanCode = $arrPlanPriceSex = $arrPlanPriceTermDay = array();
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
$rs = PlanCodePriceHanaMgr::getInstance()->getListTermDay($wq);

for($i=0;$i<$rs->num_rows;$i++) {
	$row = $rs->fetch_assoc();

	if(($prevPlanCode!=$row['plan_code'] || $prevTripType!=$row['trip_type'] || $prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) && $prevPlanCode!="") {
		$arrPlanPriceTripType[$prevPlanCode]=$arrPlanPricePlanCode;
		$arrPlanPricePlanCode = array();
	}

	if(($prevTripType!=$row['trip_type'] || $prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) && $prevTripType!="") {
		$arrPlanPriceMemberList[$prevTripType]=$arrPlanPriceTripType;
		$arrPlanPriceTripType = array();
	}

	if(($prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) && $prevMemberNo!="") {
		$arrPlanPriceCompanyList[$prevMemberNo]=$arrPlanPriceMemberList;
		$arrPlanPriceMemberList = array();
	}

	if($prevCompanyType!=$row['company_type'] && $prevCompanyType!="") {
		$arrPlanPriceList[$prevCompanyType]=$arrPlanPriceCompanyList;
		$arrPlanPriceCompanyList = array();
	}
	
	array_push($arrPlanPricePlanCode, $row['term_day']);

	$prevCompanyType = $row['company_type'];
	$prevMemberNo = $row['member_no'];
	$prevTripType = $row['trip_type'];
	$prevPlanCode = $row['plan_code'];
}

$arrPlanPriceTripType[$prevPlanCode]=$arrPlanPricePlanCode;
$arrPlanPriceMemberList[$prevTripType]=$arrPlanPriceTripType;
$arrPlanPriceCompanyList[$prevMemberNo]=$arrPlanPriceMemberList;
$arrPlanPriceList[$prevCompanyType]=$arrPlanPriceCompanyList;

$file_name = $_SERVER['DOCUMENT_ROOT']."/config/"."plan_price_boundary_list";

if(!empty($company_type)) {
	$file_name .= "_".$company_type;
}

if(!empty($p_member_no)) {
	$file_name .= "_".$p_member_no;
}

$file_name .= ".json";

file_put_contents($file_name, json_encode($arrPlanPriceList));

$wq = new WhereQuery(true, true);
$wq->addAndString("company_type","=",$company_type);
$wq->addAndString("member_no","=",$member_no);
$wq->addOrderBy("company_type","asc");
$wq->addOrderBy("member_no","asc");
$wq->addOrderBy("trip_type","asc");
$wq->addOrderBy("plan_code","asc");
$wq->addOrderBy("sex","asc");
$wq->addOrderBy("term_day","asc");
$wq->addOrderBy("age","asc");

$rs = PlanCodePriceHanaMgr::getInstance()->getList($wq);

$arrPlanPriceList = $arrPlanPriceCompanyList = $arrPlanPriceMemberList = $arrPlanPriceTripType = $arrPlanPricePlanCode = $arrPlanPriceSex = $arrPlanPriceTermDay = array();
$prevCompanyType = $prevMemberNo = $prevTripType = $prevPlanCode = $prevSex = $prevTermDay = "";

for($i=0;$i<$rs->num_rows;$i++) {
	$row = $rs->fetch_assoc();

	if(($prevTermDay!=$row['term_day'] || $prevSex!=$row['sex'] || $prevPlanCode!=$row['plan_code'] || $prevTripType!=$row['trip_type'] || $prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) && $prevTermDay!="") {
		$arrPlanPriceSex[$prevTermDay]=$arrPlanPriceTermDay;
		$arrPlanPriceTermDay = array();
	}

	if(($prevSex!=$row['sex'] || $prevPlanCode!=$row['plan_code'] || $prevTripType!=$row['trip_type'] || $prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) && $prevSex!="") {
		$arrPlanPricePlanCode[$prevSex]=$arrPlanPriceSex;
		$arrPlanPriceSex = array();
	}

	if(($prevPlanCode!=$row['plan_code'] || $prevTripType!=$row['trip_type'] || $prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) && $prevPlanCode!="") {
		$arrPlanPriceTripType[$prevPlanCode]=$arrPlanPricePlanCode;
		$arrPlanPricePlanCode = array();
	}

	if(($prevTripType!=$row['trip_type'] || $prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) && $prevTripType!="") {
		$arrPlanPriceMemberList[$prevTripType]=$arrPlanPriceTripType;
		$arrPlanPriceTripType = array();
	}

	if(($prevMemberNo!=$row['member_no'] || $prevCompanyType!=$row['company_type']) && $prevMemberNo!="") {
		$arrPlanPriceCompanyList[$prevMemberNo]=$arrPlanPriceMemberList;
		$arrPlanPriceMemberList = array();
	}

	if($prevCompanyType!=$row['company_type'] && $prevCompanyType!="") {
		$arrPlanPriceList[$prevCompanyType]=$arrPlanPriceCompanyList;
		$arrPlanPriceCompanyList = array();
	}
	
	$arrPlanPriceTermDay[$row['age']] = $row['price'];

	$prevCompanyType = $row['company_type'];
	$prevMemberNo = $row['member_no'];
	$prevTripType = $row['trip_type'];
	$prevPlanCode = $row['plan_code'];
	$prevSex = $row['sex'];
	$prevTermDay = $row['term_day'];
}

$arrPlanPriceSex[$prevTermDay]=$arrPlanPriceTermDay;
$arrPlanPricePlanCode[$prevSex]=$arrPlanPriceSex;
$arrPlanPriceTripType[$prevPlanCode]=$arrPlanPricePlanCode;
$arrPlanPriceMemberList[$prevTripType]=$arrPlanPriceTripType;
$arrPlanPriceCompanyList[$prevMemberNo]=$arrPlanPriceMemberList;
$arrPlanPriceList[$prevCompanyType]=$arrPlanPriceCompanyList;

$file_name = $_SERVER['DOCUMENT_ROOT']."/config/"."plan_price_list";

if(!empty($company_type)) {
	$file_name .= "_".$company_type;
}

if(!empty($p_member_no)) {
	$file_name .= "_".$p_member_no;
}

$file_name .= ".json";

file_put_contents($file_name, json_encode($arrPlanPriceList));

/*
echo "<pre>";
print_r($arrPlanPriceList);
echo "</pre>";
*/

echo "Ok!";
?>