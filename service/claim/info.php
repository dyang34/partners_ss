<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[4,0];

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
    <div class="check-box-wrap">
        <div class="adjustm-list-wrap">
            <h2>청구안내</h2>

            <!-- Tabs start -->

            <div class="tab-cont inb">
                <input id="tab1" type="radio" name="tabs" checked>
                <label for="tab1" class="first">
                <!-- 배상책임 start -->
                    <div class="box-list">
                        <i class="icon-sending"></i>
                        <div class="cont-txt">
                            <strong>배상책임</strong>
                            <p class="type-dash">- 배상책임 청구 구비서류</p>
                        </div>
                    </div>
                </label>
                <!-- 배상책임 end -->
                </label>

                <input id="tab2" type="radio" name="tabs">
                <label for="tab2">
                <!-- 의료비 start -->
                    <div class="box-list">
                        <i class="icon-medical"></i>
                        <div class="cont-txt">
                            <strong>의료비</strong>
                            <p class="type-dash">- 의료비 청구 구비서류 (국내/해외)</p>
                            <p class="type-dash">- 해외 교통사고 의료비 청구 구비서류</p>
                        </div>
                    </div>
                </label>
                <!-- 의료비 end -->
                </label>

                <input id="tab3" type="radio" name="tabs">
                <label for="tab3">
                <!-- 휴대품파손 start -->
                    <div class="box-list">
                        <i class="icon-break"></i>
                        <div class="cont-txt">
                            <strong>휴대품 파손</strong>
                            <p class="type-dash">- 휴대품 파손 청구 구비서류</p>
                            <p class="type-dash">- 항공사 파손 청구 구비서류</p>
                        </div>
                    </div>
                </label>
                <!-- 휴대품파손 end -->
                </label>

                <input id="tab4" type="radio" name="tabs">
                <label for="tab4">
                <!-- 휴대품도난 start -->
                    <div class="box-list">
                        <i class="icon-theft"></i>
                        <div class="cont-txt">
                            <strong>휴대품 도난</strong>
                            <p class="type-dash">- 휴대품 도난 청구 구비서류</p>
                            <p class="type-dash">- 휴대폰 도난 청구 구비서류</p>
                            <p class="type-dash">- 항공사 분실(도난)</p>
                        </div>
                    </div>
                </label>
                <!-- 휴대품도난 end -->
                </label>

                <!-- 배상책임 start -->
                <section id="content1" class="tab-content">
                    <div class="set">
                        <div class="title active">
                            <a href="#">1. 보험금 청구 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content" style="display: block;">
                            <p>미성년자의 경우 개인정보 동의란 (또는 위임사항)에 부모님(법정대리인)이 서명</p>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">2. 본인확인용 서류 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <p>ⓐ 해외여행 : 여권사본, 출입국증명서 (여권 스탬프면 사본으로 대체 가능)</p>
                            <p class="type-dash">
                                ⓑ 국내여행 : 신분증사본<br>
                                * 미성년자의 경우 등본 or 가족관계증명서 추가 첨부
                            </p>
                        </div>
                    </div>
                    
                    <div class="set">
                        <div class="title">
                            <a href="#">3. 통장 사본 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <p>미성년자의 경우 부모님(법정대리인) 통장사본</p>
                        </div>
                    </div>
                    
                    <div class="set">
                        <div class="title">
                            <a href="#">4. 손해배상 증빙서류 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <p class="type-dash">1) 대물 배상 시 - 피해품 수리 견적서 혹은 배상 후 지불한 영수증과 입금(결제)내역 등 증빙서류</p>
                            <p class="type-dash">2) 의료비 배상 시 - 피해자 진단서, 초진(응급)진료차트, 치료비 영수증 / 사고관련 입증서류 및 합의서</p>
                        </div>
                    </div>
                    
                    <div class="set">
                        <div class="title">
                            <a href="#">5. 주민등록 등본 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <p>주민등록 등본</p>
                        </div>
                    </div>
                </section>
                <!-- 배상책임 end -->

                <!-- 의료비 start -->
                <section id="content2" class="tab-content">
                    <div class="set">
                        <div class="title active">
                            <a href="#">1. 보험금 청구 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content" style="display: block;">
                            <p>미성년자의 경우 개인정보 동의란 (또는 위임사항)에 부모님(법정대리인)이 서명</p>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">2. 본인확인용 서류 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <p>ⓐ 해외여행 : 여권사본, 출입국증명서 (여권 스탬프면 사본으로 대체 가능)</p>
                            <p class="type-dash">
                                ⓑ 국내여행 : 신분증사본<br>
                                * 미성년자의 경우 등본 or 가족관계증명서 추가 첨부
                            </p>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">3. 통장 사본 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <p>미성년자의 경우 부모님(법정대리인) 통장사본</p>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">4. 의료비 관련 제출 서류 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>1) 목격자 확인서</li>
                                <li>2) 목격자 확인용 서류
                                    <ul>
                                        <li>ⓐ 해외치료시 : 치료비 영수증(원본), 진단서(medical record) / 초진기록 or 의사소견서</li>
                                        <li>ⓑ 국내치료시 : 진료 내역서, 영수증 사본</li>
                                        <li>ⓒ 해외치료시 진단서, 영수증 원본 없는 경우
                                            <ul>
                                                <li>: 진료 내역서, 영수증 사본 제출, 목격자 확인서(여행중 동행자 또는 가이드 등의 목격자 확인서와 함께 여권사본 및 출입국 스탬프를 함께 첨부)</li>
                                            </ul>
                                        </li>
                                        <li>* 진단서, 통원확인서, 처방전, 진료확인서, 소견서, 수술확인서, 진료차트 등에는 진단명이 기재되어 있어야 합니다.</li>
                                        <li>* 사고내용, 특성, 상품(보장내역)에 따라 추가 심사서류를 요구할 수 있습니다.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!-- 의료비 end -->

                <!-- 휴대품파손 start -->
                <section id="content3" class="tab-content">
                    <div class="set">
                        <div class="title active">
                            <a href="#">1. 보험금 청구 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content" style="display: block;">
                            <ul>
                                <li>미성년자의 경우 개인정보 동의란 (또는 위임사항)에 부모님(법정대리인)이 서명</li>
                            </ul>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">2. 본인확인용 서류 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>ⓐ 해외여행 : 여권사본, 출입국증명서 (여권 스탬프면 사본으로 대체 가능)</li>
                                <li class="type-dash">
                                    ⓑ 국내여행 : 신분증사본<br>
                                    * 미성년자의 경우 등본 or 가족관계증명서 추가 첨부
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">3. 통장 사본 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>미성년자의 경우 부모님(법정대리인) 통장사본</li>
                            </ul>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">4. 피해품 내역서 및 영수증 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>1) 공항에서 파손시
                                    <ul>
                                        <li>ⓐ 항공사에서 보상받은 경우 : 항공사 처리 확인서</li>
                                        <li>ⓑ 항공사에서 보상받지 못한 경우 : 항공사 보상불가 확인서</li>
                                    </ul>
                                </li>
                                <li>※ 파손된 물품이 핸드폰일 경우
                                    <ul>
                                        <li>ⓐ SK-이용계약등록사항증명서 / KT-원부증명서 / LG-가입사실확인서 (해당 통신사의 고객센터 혹은 대리점에서 발급 가능)</li>
                                        <li>ⓑ 파손보험가입여부를 원부증명서 또는 가입사실증명서 상단에 기재</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">5.수리비 관련 서류 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>1) 수리 가능한 경우
                                    <ul>
                                        <li>ⓐ 수리영수증(수리한 경우)</li>
                                        <li>ⓑ 수리견적서(파손 되어 수리 가능한 경우)</li>
                                    </ul>
                                </li>
                                <li>2) 수리 불가능한 경우
                                    <ul>
                                        <li>ⓐ 수리 불능확인서</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">6. 물품사진 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>휴대품이 파손 되어 보관 중인 경우</li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!-- 휴대품파손 end -->

                <!-- 휴대품도난 start -->
                <section id="content4" class="tab-content">
                    <div class="set">
                        <div class="title active">
                            <a href="#">1. 보험금 청구 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content" style="display: block;">
                            <ul>
                                <li>미성년자의 경우 개인정보 동의란 (또는 위임사항)에 부모님(법정대리인)이 서명</li>
                            </ul>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">2. 본인확인용 서류 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>ⓐ 해외여행 : 여권사본, 출입국증명서 (여권 스탬프면 사본으로 대체 가능)</li>
                                <li class="type-dash">
                                    ⓑ 국내여행 : 신분증사본<br>
                                    * 미성년자의 경우 등본 or 가족관계증명서 추가 첨부
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="set">
                        <div class="title">
                            <a href="#">3. 통장 사본 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>미성년자의 경우 부모님(법정대리인) 통장사본</li>
                            </ul>
                        </div>
                    </div>
                    <div class="set">
                        <div class="title">
                            <a href="#">4.목격자 서류 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>1) 목격자 확인서</li>
                                <li>2) 목격자 확인용 서류
                                    <ul>
                                        <li>ⓐ 해외여행 : 여권사본, 출입국증명서 (여권 스탬프면 사본으로 대체 가능)</li>
                                        <li>ⓑ 국내여행 : 신분증사본
                                            <ul>
                                                <li>* 미성년자의 경우 등본 or 가족관계증명서 추가 첨부</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="set">
                        <div class="title">
                            <a href="#">5. 피해품 내역서 및 영수증 <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>1) 물품 영수증 (없는 경우 보험금청구서 상에 상품명, 구입년월, 금액 기재)</li>
                                <li>2) 항공사 사고일 경우
                                    <ul>
                                        <li>ⓐ 항공사에서 보상받은 경우 : 항공사 처리확인서</li>
                                        <li>ⓑ 항공사에서 보상받지 못한 경우 : 항공사 보상불가 확인서</li>
                                    </ul>
                                </li>
                                <li class="type-dash">
                                    ※ 휴대폰을 제외한 일반 mp3 나 카메라의 경우에는 최근에 산것은 영수증을 꼭 준비 해주셔야 합니다.<br>
                                    몇년전에 산 물건일 경우에는 어느정도의 감안이 되오니 일단 영수증은 최대한 준비해 주시는게 좋습니다.
                                </li>
                                <li class="type-dash">
                                    ※ 도난당한 물품이 핸드폰일 경우 해당 통신사의 대리점에 가셔서 서류를 발급 받고 제출하셔야 합니다.<br>
                                    SK-이용계약등록사항증명서 / KT-원부증명서 / LG-가입사실확인서
                                </li>
                                <li class="type-dash">
                                    ※ 도난당한 물품이 핸드폰일 경우 폰케어 보험 가입된 고객 중, SK텔레콤 통신사 가입 고객은 동일기종으로 기기변경 할 경우 변경시 발생하는 자부담금(본인부담금) 확인되는 서류가 추가로 들어와야 합니다.
                                </li>
                                <li>자부담금(본인부담금) 서류는 통신사가 지정해 주는 지정대리점에서 발급가능(대리점마다 상이할 수 있음)하며 새로 개통하는 서류에 자부담금(본인부담금)을 기재하도록 요청 하거나, 혹 지정 대리점에서 관련 서류 발급이 어렵다고 할 경우 지정대리점 직원 명함에 자부담금(본인부담금)작성한 서류 보내면 됩니다.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="set">
                        <div class="title">
                            <a href="#">6. 경찰서 확인서 (Police Report) <i class="icon-accordion"></i> </a>
                        </div>
                        <div class="content">
                            <ul>
                                <li>
                                    폴리스 리포트 사본<br>
                                    ※ 수하물 도난인 경우는 리포트 불필요
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
                <!-- 휴대품도난 end -->
            </div>

            <div class="btn-bottom-area">
                <a href="https://b2b.udirect.co.kr/download/삼성화재 보험금청구서.pdf" target="_blank" >삼성화재 보험금 청구서</a>
                <!-- <a href="https://b2b.udirect.co.kr/download/삼성화재 보험금청구서.pdf" target="_blank" class="">삼성화재 보험금청구서</a> -->
                <p>※ 보험사 청구접수전 <strong>1800-9010</strong>으로 문의후 접수바랍니다</p>
            </div>
            <!-- Tabs end -->
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
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>