<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
/*
if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}
*/
include $_SERVER['DOCUMENT_ROOT']."/include/head.php";
?>
<body id="wrap">
<!-- Header start -->
    <header>
        <div class="head-wrap">
            <h1><img src="/img/common/logo-toursafe.png" alt="투어세이프 로고"></h1>
            <nav>
                <a href="/service/contract/register.php" class="<?=($menuNo==1)?"active":""?>">보험 가입</a>
                <a href="/service/contract/inquiry.php" class="<?=($menuNo==2)?"active":""?>">신청내역 조회/수정</a>
                <a href="#" class="<?=($menuNo==3)?"active":""?>">정산관리</a>
                <a href="#" class="<?=($menuNo==4)?"active":""?>">청구안내</a>
                <a href="#" class="<?=($menuNo==5)?"active":""?>">고객센터</a>
            </nav>
<?php
if (LoginManager::isUserLogined()) {
?>
            <div class="box-btn-head">
                <a href="/service/member/join_mem_modify.php" class="button id-name"><?=LoginManager::getUserLoginInfo("com_name")?></a> <!-- onclick="return false;"-->
                <a href="/admin_logout.php" class="button logout">로그아웃</a>
            </div>
<?php
}
?>
        </div>
    </header>
<!-- Header end -->