<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //JsUtil::alertReplace("비정상적인 접근입니다."."/");
    JsUtil::replace("/");
} else {

    $CONFIG__TRIP1_COMPANY_LIST = count(LoginManager::getUserLoginInfo('arr_trip_1_company'));
    $CONFIG__TRIP2_COMPANY_LIST = count(LoginManager::getUserLoginInfo('arr_trip_2_company'));
    $CONFIG__TRIP3_COMPANY_LIST = count(LoginManager::getUserLoginInfo('arr_trip_3_company'));

    if (LoginManager::getUserLoginInfo('mem_type') == "1") {
        JsUtil::replace("/service/contract/inquiry.php");
    } else if (!empty($CONFIG__TRIP2_COMPANY_LIST)) {
        JsUtil::replace("/service/contract/register.php?trip_type=2");
    } else if (!empty($CONFIG__TRIP1_COMPANY_LIST)) {
        JsUtil::replace("/service/contract/register.php?trip_type=1");
    } else if (!empty($CONFIG__TRIP3_COMPANY_LIST)) {
        JsUtil::replace("/service/contract/register_long.php?trip_type=3");
    } else {
        JsUtil::replace("/service/contract/inquiry.php");
    }
}
?>