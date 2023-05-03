<?php
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
/*
if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}
*/

$cnt_trip1_company = count(LoginManager::getUserLoginInfo('company_type_list')[1]);
$cnt_trip2_company = count(LoginManager::getUserLoginInfo('company_type_list')[2]);
$cnt_trip3_company = count(LoginManager::getUserLoginInfo('company_type_list')[3]);

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";
?>
<body id="wrap">
<!-- Header start -->
    <header>
        <div class="head-wrap">
            <h1><a href="/branch.php"><img src="/img/common/toursafe-logo-white.png" alt="투어세이프 로고"></a></h1>
            <nav>                
                <ul class="clearfix inb">
<?php
    if ((!empty($cnt_trip1_company) || !empty($cnt_trip2_company) || !empty($cnt_trip3_company)) && LoginManager::getUserLoginInfo('mem_type') == "2") {
?>
                    <li class="<?=($menuNo[0]==1)?"active":""?>"><a href="/service/contract/<?=!empty($cnt_trip2_company)?"register.php?trip_type=2":(!empty($cnt_trip1_company)?"register.php?trip_type=1":"register_long.php?trip_type=3")?>">보험 가입</a>
                        <ul id="submenu">
<?php
        if (!empty($cnt_trip1_company)) {
?>
                            <li><a href="/service/contract/register.php?trip_type=1"><?=$arrTripType[1]?></a></li>
<?php
        }

        if (!empty($cnt_trip2_company)) {
?>        
                            <li><a href="/service/contract/register.php?trip_type=2"><?=$arrTripType[2]?></a></li>
<?php
        }

        if (!empty($cnt_trip3_company)) {
?>        
                            <li><a href="/service/contract/register_l2.php?trip_type=3">해외장기체류</a></li>
<?php
        }
?>
                        </ul>
                    </li>
<?php
    }
?>
                    <li class="<?=($menuNo[0]==2)?"active":""?>"><a href="/service/contract/inquiry.php">신청내역 조회/수정</a></li>
                    <li class="<?=($menuNo[0]==3)?"active":""?>"><a href="/service/calc/calc_list.php">실적관리</a></li>
                    <li class="<?=($menuNo[0]==4)?"active":""?>"><a href="/service/claim/info.php">청구안내</a></li>
                    <li class="<?=($menuNo[0]==5)?"active":""?>"><a href="/service/customer/notice.php">고객센터</a></li>
                </ul>
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