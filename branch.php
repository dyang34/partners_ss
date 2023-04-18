<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //JsUtil::alertReplace("비정상적인 접근입니다."."/");
    JsUtil::replace("/");
} else {

    $trip_1_company_show = count(LoginManager::getUserLoginInfo('arr_trip_1_company'));
    $trip_2_company_show = count(LoginManager::getUserLoginInfo('arr_trip_2_company'));
    $trip_3_company_show = count(LoginManager::getUserLoginInfo('arr_trip_3_company'));

    if (!empty($trip_2_company_show)) {
        JsUtil::replace("/service/contract/register.php?trip_type=2");
    } else if (!empty($trip_1_company_show)) {
        JsUtil::replace("/service/contract/register.php?trip_type=1");
    } else if (!empty($trip_3_company_show)) {
        JsUtil::replace("/service/contract/register_long.php?trip_type=3");
    } else {
        JsUtil::replace("/service/calc/calc_list.php");
    }
}
?>