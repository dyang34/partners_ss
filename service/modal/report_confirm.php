<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

$trip_type = RequestUtil::getParam("trip_type", "");
$company_type = RequestUtil::getParam("company_type", "");
$hana_plan_no = RequestUtil::getParam("hana_plan_no", "");
switch($company_type) {
    case "5":
        if($trip_type=="1") {
            JsUtil::replace("cnfrm-sbscr_5.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
        } else {
            JsUtil::replace("cnfrm-sbscr_T.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
        }
        break;
    default:
        if($trip_type=="1") {
            //JsUtil::replace("cnfrm-sbscr.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
            JsUtil::replace("cnfrm-sbscr_T.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
        } else {
            JsUtil::replace("cnfrm-sbscr_E.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
        }
    break;
}
?>