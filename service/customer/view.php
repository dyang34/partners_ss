<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[5,1];

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
    <div class="check-box-wrap">
        <div class="customer-list-wrap">
<?php
            include $_SERVER['DOCUMENT_ROOT']."/include/customer_sub_menu.php";
?>
            <div class="view-title-wrap">
                <h2>설날 휴무 안내</h2>
                <ul class="clearfix inb">
                    <li>작성일 : 2019.05.01</li>
                    <li>작성자 : 작성자</li>
                    <li>첨부파일 : <i class="icon-file"></i></li>
                </ul>
            </div>

            <div class="view-cont-wrap">
                <p>투어세이프 비즈파트너는 여행자보험을 찾는 고객과 파트너를 이어주는 강력한 비즈니스 플랫폼입니다.</p>
                <p>1. PC와 모바일환경에서 쉽고 편리하게 사용하실 수 있습니다.</p>
                <p>반응형 시스템으로 시간과 장소등의 업무환경을 극복할 수 있어 언제 어디서나 여행자 보험을 쉽고 편리하게 관리하실 수 있습니다.</p>
                <p>2. 비즈니스 기본에 충실합니다.</p>
                <p>파트너 여러분들의 비즈니스 니즈를 충족시켜 드릴 수 있도록 여행자보험의 ​가입/관리/정산/보상 영역 기본에 집중한 강력한 올인원 기능을 제공합니다.</p>
                <p>3. 업무비용을 절감하실 수 있습니다.</p>
                <p>투어세이프 비즈파트너를 이용하여 업무비를 낮추고, 파트너분들의 기본비즈니스에 집중하세요!</p>
                <p>성공적인 비즈니스를 위한 투어세이프의 비즈파트너 서비스를 경험해 보세요!</p>
                <p>투어세이프와 함께 더 높은 성공을 위한 한걸음 함께 해주셔서 감사합니다.</p>
            </div>

            <div class="center-button-area">
                <a onClick="history.go(-1)" class="button blue">이전</a>
            </div>
        </div>
    </div>
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>