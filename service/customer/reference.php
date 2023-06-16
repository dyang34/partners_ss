<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[5,3];

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
                        <a href="#">삼성화재 보험약관 <i class="icon-accordion"></i> </a>
                    </div>
                    <div class="content" style="display: block;">
                        <p>
                            <a href="/download/해피투어_약관.pdf" target="_blank" class="terms">여행자보험약관 다운로드</a>
                        </p>
                    </div>
                </div>
                
                <!--div class="set">
                    <div class="title active">
                        <a href="#">삼성화재 보험약관 <i class="icon-accordion"></i> </a>
                    </div>
                    <div class="content">
                        <p>
                            <a href="https://b2b.udirect.co.kr/download/해피투어_약관.pdf" target="_blank" class="terms">여행자보험약관 다운로드</a>
                        </p>
                    </div>
                </div-->
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