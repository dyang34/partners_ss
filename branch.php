<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //JsUtil::alertReplace("비정상적인 접근입니다."."/");
    JsUtil::replace("/");
} else {

    $cnt_trip1_company = count(LoginManager::getUserLoginInfo('company_type_list')[1]);
    $cnt_trip2_company = count(LoginManager::getUserLoginInfo('company_type_list')[2]);
    $cnt_trip3_company = count(LoginManager::getUserLoginInfo('company_type_list')[3]);

    if (LoginManager::getUserLoginInfo('mem_type') == "1") {
        JsUtil::replace("/service/contract/inquiry".LoginManager::getUserLoginInfo('mem_type').".php");
    } else if (!empty($cnt_trip2_company)) {
        JsUtil::replace("/service/contract/register.php?trip_type=2");
    } else if (!empty($cnt_trip1_company)) {
        JsUtil::replace("/service/contract/register.php?trip_type=1");
    } else if (!empty($cnt_trip3_company)) {
        JsUtil::replace("/service/contract/register_long.php?trip_type=3");
    } else {
        JsUtil::replace("/service/contract/inquiry".LoginManager::getUserLoginInfo('mem_type').".php");
    }
}
?>