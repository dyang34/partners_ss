<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[5,2];

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
    <div class="check-box-wrap">
        <div class="customer-list-wrap">
<?php
            include $_SERVER['DOCUMENT_ROOT']."/include/customer_sub_menu.php";
?>
            <div class="accordion-wrap">
                <div class="set">
                    <div class="title active">
                        <a href="#">단체여행인데 한번 가입 할 수 있나요? <i class="icon-accordion"></i> </a>
                    </div>
                    <div class="content" style="display: block;">
                        <p>네 가능합니다. 가입자 모두 동일한 플랜을 설정하거나 각자 다른 플랜을 설정 할 수도 있습니다.</p>
                    </div>
                </div>

                <div class="set">
                    <div class="title">
                        <a href="#">보험사 별로 가입 할 수 있나요? <i class="icon-accordion"></i> </a>
                    </div>
                    <div class="content">
                        <p>현재는 삼성화재만 가능 하며, 추후 보험사별 가입할 수 있도록 제공 예정입니다.</p>
                    </div>
                </div>

                <div class="set">
                    <div class="title">
                        <a href="#">정보를 잘 못 입력해서 수정하고 싶은데 버튼이 안보여요? <i class="icon-accordion"></i> </a>
                    </div>
                    <div class="content">
                        <p>수정은 출발일 전일까지만 할 수 있습니다.</p>
                    </div>
                </div>

                <div class="set">
                    <div class="title">
                        <a href="#">수정/취소는 어떻게 하나요? <i class="icon-accordion"></i> </a>
                    </div>
                    <div class="content">
                        <p>신청내역 조회/수정 메뉴에서 검색 후 오른쪽 수정 버튼을 클릭 후 수정접수 또는 취소접수로 등록 해주시면 됩니다.</p>
                    </div>
                </div>

                <div class="set">
                    <div class="title">
                        <a href="#">정산은 어떻게 하나요? <i class="icon-accordion"></i> </a>
                    </div>
                    <div class="content">
                        <p>매월 1회 인보이스를 발행하며, 내역에 따라 입금하시면 됩니다.</p>
                    </div>
                </div>

                <div class="set">
                    <div class="title">
                        <a href="#">해외여행보험 가입불가 국가는 어떻게 알 수 있나요? <i class="icon-accordion"></i> </a>
                    </div>
                    <div class="content">
                        <p>외교통상부 사이트에서 확인할 수 있으며, 해외여행경보 3,4단계(철수권고, 여행금지)에 해당되는 국가는 가입할 수 없습니다.</p>
                        <p class="type-dash">외교통상부 홈페이지 바로가기(<a href="http://www.0404.go.kr" target="_blank">http://www.0404.go.kr</a>)</p>
                    </div>
                </div>
            </div>
            
            <!-- paginate start -->
            <div class="paginate">
                <a href="#;" class="first"><i class="prev-arrow-double"></i></a>
                <a href="#;" class="prev"><i class="prev-arrow"></i></a>
                
                <a href="#;" class="active">1</a>
                <a href="#;" class="">2</a>
                <a href="#;" class="">3</a>
                <a href="#;" class="">4</a>
                <a href="#;" class="">5</a>
                
                <a href="#;" class="next"><i class="next-arrow"></i></a>
                <a href="#;" class="last"><i class="next-arrow-double"></i></a>
            </div>
        </div>
    </div>

    <script>
        // accordion menu
        $(document).ready(function() {
            $(".title").on("click", function() {
                if ($(this).hasClass("active")) {
                $(this).removeClass("active");
                $(this)
                    .siblings(".content")
                    .slideUp(500);
                $(".title i")
                    .removeClass("fa-minus")
                    .addClass("fa-plus");
                } else {
                $(".title i")
                    .removeClass("fa-minus")
                    .addClass("fa-plus");
                $(this)
                    .find("i")
                    .removeClass("fa-plus")
                    .addClass("fa-minus");
                $(".title").removeClass("active");
                $(this).addClass("active");
                $(".content").slideUp(500);
                $(this)
                    .siblings(".content")
                    .slideDown(500);
                }
            });
        });
    </script>
<?php include '../../include/footer.php'; ?> 