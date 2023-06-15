<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";


if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>

<div class="terms-box-wrap">
    <h2>이용약관</h2>
    <div class="conts-box">
        <?php include $_SERVER['DOCUMENT_ROOT']."/service/member/terms.php"; ?>
    </div>
</div>


<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>