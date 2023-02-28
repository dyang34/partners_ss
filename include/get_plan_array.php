<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (LoginManager::isUserLogined()) {

    if($__CONFIG_GET_PLAN_TYPE=="I") {

        if(!LoginManager::getUserLoginInfo("fg_not_common_plan")) {
            $plan_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_list_".LoginManager::getUserLoginInfo("company_type")."_".LoginManager::getUserLoginInfo("no").".json";
            $plan_repre_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_repre_list_".LoginManager::getUserLoginInfo("company_type")."_".LoginManager::getUserLoginInfo("no").".json";
        } else {
            $plan_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_list_".LoginManager::getUserLoginInfo("company_type").".json";
            $plan_repre_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_repre_list_".LoginManager::getUserLoginInfo("company_type").".json";
        }
    } else {
        $plan_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_list.json";
        $plan_repre_file_name = $_SERVER['DOCUMENT_ROOT']."/config/plan_repre_list.json";
    }

    $__ARR_CONFIG_PLAN = json_decode(file_get_contents($plan_file_name), true);
    $__ARR_CONFIG_PLAN_REPRE = json_decode(file_get_contents($plan_repre_file_name), true);
}
?>