<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

$company_type = RequestUtil::getParam("company_type", "");
$hana_plan_no = RequestUtil::getParam("hana_plan_no", "");
switch($company_type) {
    case "5":
        JsUtil::replace("cnfrm-sbscr_5.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
        break;
    default:
        JsUtil::replace("cnfrm-sbscr.php?company_type=".$company_type."&hana_plan_no=".$hana_plan_no);
        break;
}
?>