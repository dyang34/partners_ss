<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (LoginManager::isUserLogined()) {

    if($__CONFIG_GET_PLAN_TYPE=="I") {

        if(!LoginManager::getUserLoginInfo("fg_not_common_plan")) {
            $plan_type_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_type_list_".LoginManager::getUserLoginInfo("company_type")."_".LoginManager::getUserLoginInfo("no").".json";
        } else {
            $plan_type_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_type_list_".LoginManager::getUserLoginInfo("company_type").".json";
        }
    } else {
        $plan_type_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_type_list.json";
    }

    $__ARR_CONFIG_PLAN_TYPE = json_decode(file_get_contents($plan_type_file_name), true);
}
?>