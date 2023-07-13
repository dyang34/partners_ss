<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

$mode = RequestUtil::getParam("mode", "");
$company_type = RequestUtil::getParam("company_type", "");
$hana_plan_no = RequestUtil::getParam("hana_plan_no", "");
$referer_type = RequestUtil::getParam("referer_type", "");

if($company_type=="4" && $referer_type != 2) {
    if($mode=="1") {
        JsUtil::replace("cnfrm-sbscr_4.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
    } else {
        JsUtil::replace("cnfrm-sbscr_4_E.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
    }
} else if ($company_type=="5") {
    if($mode=="1") {
        JsUtil::replace("cnfrm-sbscr_5.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
    } else {
        JsUtil::replace("cnfrm-sbscr_T.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
    }
} else {
    if($mode=="1") {
        //JsUtil::replace("cnfrm-sbscr.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
        JsUtil::replace("cnfrm-sbscr_T.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
    } else {
        JsUtil::replace("cnfrm-sbscr_E.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
    }
}
?>