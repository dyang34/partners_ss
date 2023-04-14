<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //JsUtil::alertReplace("비정상적인 접근입니다."."/");
    JsUtil::replace("/");
} else {
    JsUtil::replace("/service/contract/register.php");
}
?>