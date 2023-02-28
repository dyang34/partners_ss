<? $mNum = "2"; ?><?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";
include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<script type="text/javascript">
    // 달력 script
    $(document).ready(function() {
        var today = new Date();
        var tomorrow = new Date(Date.parse(today) + (1000 * 60 * 60 * 24));

        $("#start_date").datepicker({
        showOn: "both",
        dateFormat: "yy-mm-dd",
        buttonImage: "/img/service/icon-calendar.svg?en",
        buttonImageOnly: true,
        showOtherMonths: true,
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        buttonText: "Select date",
        minDate: today,
        onClose: function( selectedDate ) {  
        }         
        });

        $(" #end_date").datepicker({
        showOn: "both",
        dateFormat: "yy-mm-dd",
        buttonImage: "/img/service/icon-calendar.svg?en",
        buttonImageOnly: true,
        showOtherMonths: true,
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        buttonText: "Select date",
        minDate: tomorrow              
        });
    });
</script>

    <!-- 신청내역 조회/수정 start -->
    <div class="check-box-wrap">
        <div class="search-shnch-wrap">
            <div class="shnch-hstry">
                <table class="table-search">
                    <colgroup>
                        <col width="132px">
                        <col width="400px">
                        <col width="132px">
                        <col width="*">
                    </colgroup>
                    <tbody>
                        <tr>
                            <th>신청일</th>
                            <td>
                                <div class="date_picker">
                                    <input type="text" class="picker" name="start_date" id="start_date" readonly placeholder="YYYY.MM.DD">
                                </div>
                            </td>

                            <th>신청자</th>   
                            <td>
                                <input type="text"  class="input-search" name="" id="" value="홍길동">
                                <a href="#;" class="button search">검색</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- List start -->
        <div class="table-list-wrap">
            <div class="table-history-wrap">
                <table class="table-list">
                    <colgroup>
                        <col width="6%">
                        <col width="10%">
                        <col width="10%">
                        <col width="14%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="*">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th rowspan="2">no</th>
                            <th>신청일</th>
                            <th>진행상태</th>
                            <th>플랜명</th>
                            <th>대표 피보험자(신청자)</th>
                            <th>피보험자 주민등록번호</th>
                            <th colspan="2">여행지</th>
                            <th rowspan="2">수정요청</th>
                        </tr>
                        <tr>
                            <th>보험시작일</th>
                            <th>보험시작일</th>
                            <th>플랜코드</th>
                            <th>보험료</th>
                            <th>증권(신청)번호</th>
                            <th>핸드폰</th>
                            <th>증권(신청)번호</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2">1</td>
                            <td><a href="view.php">2023.02.01</a></td>
                            <td><a href="view.php">청약완료</a></td>
                            <td><a href="view.php">성인 (15~70세 미만) 표준형</a></td>
                            <td><a href="view.php">홍길동</a></td>
                            <td><a href="view.php">901214-1******</a></td>
                            <td colspan="2" class="tvl-dest"><a href="view.php">태국 (파타니, 얄라, 나라티왓, 송클라주(Pattani, Yala, Narathiwat Provinces, Songkhla Provinces)...</a></td>
                            <td rowspan="2">
                                <a href="#;" id="three" class="button mdfy btn-check-mdfy">수정</a>
                                <span class="mdfy-text">수정 접수</span>
                            </td>
                        </tr>

                        <tr>
                            <td><a href="view.php">2023.02.05</a></td>
                            <td><a href="view.php">2023.02.12</a></td>
                            <td><a href="view.php">M2</a></td>
                            <td><a href="view.php">36,000원</a></td>
                            <td><a href="view.php">D1023123</a></td>
                            <td><a href="view.php">010-1234-1234</a></td>
                            <td><a href="view.php">베트남 A패키지 포함 상품</a></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2">2</td>
                            <td><a href="view.php">2023.02.01</a></td>
                            <td><a href="view.php">청약완료</a></td>
                            <td><a href="view.php">성인 (15~70세 미만) 표준형</a></td>
                            <td><a href="view.php">홍길동</a></td>
                            <td><a href="view.php">901214-1******</a></td>
                            <td colspan="2" class="tvl-dest"><a href="view.php">태국 (파타니, 얄라, 나라티왓, 송클라주(Pattani, Yala, Narathiwat Provinces, Songkhla Provinces)...</a></td>
                            <td rowspan="2">
                                <a href="#;" id="three" class="button mdfy btn-check-mdfy">수정</a>
                                <span class="mdfy-text">수정 접수</span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="view.php">2023.02.05</a></td>
                            <td><a href="view.php">2023.02.12</a></td>
                            <td><a href="view.php">M2</a></td>
                            <td><a href="view.php">36,000원</a></td>
                            <td><a href="view.php">D1023123</a></td>
                            <td><a href="view.php">010-1234-1234</a></td>
                            <td><a href="view.php">베트남 A패키지 포함 상품</a></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2">3</td>
                            <td><a href="view.php">2023.02.01</a></td>
                            <td><a href="view.php">청약완료</a></td>
                            <td><a href="view.php">성인 (15~70세 미만) 표준형</a></td>
                            <td><a href="view.php">홍길동</a></td>
                            <td><a href="view.php">901214-1******</a></td>
                            <td colspan="2" class="tvl-dest"><a href="view.php">태국 (파타니, 얄라, 나라티왓, 송클라주(Pattani, Yala, Narathiwat Provinces, Songkhla Provinces)...</a></td>
                            <td rowspan="2">
                                <a href="#;" id="three" class="button mdfy btn-check-mdfy">수정</a>
                                <span class="mdfy-text">수정 접수</span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="view.php">2023.02.05</a></td>
                            <td><a href="view.php">2023.02.12</a></td>
                            <td><a href="view.php">M2</a></td>
                            <td><a href="view.php">36,000원</a></td>
                            <td><a href="view.php">D1023123</a></td>
                            <td><a href="view.php">010-1234-1234</a></td>
                            <td><a href="view.php">베트남 A패키지 포함 상품</a></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2">4</td>
                            <td><a href="view.php">2023.02.01</a></td>
                            <td><a href="view.php">청약완료</a></td>
                            <td><a href="view.php">성인 (15~70세 미만) 표준형</a></td>
                            <td><a href="view.php">홍길동</a></td>
                            <td><a href="view.php">901214-1******</a></td>
                            <td colspan="2" class="tvl-dest"><a href="view.php">태국 (파타니, 얄라, 나라티왓, 송클라주(Pattani, Yala, Narathiwat Provinces, Songkhla Provinces)...</a></td>
                            <td rowspan="2">
                                <a href="#;" id="three" class="button mdfy btn-check-mdfy">수정</a>
                                <span class="mdfy-text">수정 접수</span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="view.php">2023.02.05</a></td>
                            <td><a href="view.php">2023.02.12</a></td>
                            <td><a href="view.php">M2</a></td>
                            <td><a href="view.php">36,000원</a></td>
                            <td><a href="view.php">D1023123</a></td>
                            <td><a href="view.php">010-1234-1234</a></td>
                            <td><a href="view.php">베트남 A패키지 포함 상품</a></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2">5</td>
                            <td><a href="view.php">2023.02.01</a></td>
                            <td><a href="view.php">청약완료</a></td>
                            <td><a href="view.php">성인 (15~70세 미만) 표준형</a></td>
                            <td><a href="view.php">홍길동</a></td>
                            <td><a href="view.php">901214-1******</a></td>
                            <td colspan="2" class="tvl-dest"><a href="view.php">태국 (파타니, 얄라, 나라티왓, 송클라주(Pattani, Yala, Narathiwat Provinces, Songkhla Provinces)...</a></td>
                            <td rowspan="2">
                                <a href="#;" id="three" class="button mdfy btn-check-mdfy">수정</a>
                                <span class="mdfy-text">수정 접수</span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="view.php">2023.02.05</a></td>
                            <td><a href="view.php">2023.02.12</a></td>
                            <td><a href="view.php">M2</a></td>
                            <td><a href="view.php">36,000원</a></td>
                            <td><a href="view.php">D1023123</a></td>
                            <td><a href="view.php">010-1234-1234</a></td>
                            <td><a href="view.php">베트남 A패키지 포함 상품</a></td>
                        </tr>
                        
                        <tr>
                            <td rowspan="2">6</td>
                            <td><a href="view.php">2023.02.01</a></td>
                            <td><a href="view.php">청약완료</a></td>
                            <td><a href="view.php">성인 (15~70세 미만) 표준형</a></td>
                            <td><a href="view.php">홍길동</a></td>
                            <td><a href="view.php">901214-1******</a></td>
                            <td colspan="2" class="tvl-dest"><a href="view.php">태국 (파타니, 얄라, 나라티왓, 송클라주(Pattani, Yala, Narathiwat Provinces, Songkhla Provinces)...</a></td>
                            <td rowspan="2">
                                <a href="#;" id="three" class="button mdfy btn-check-mdfy">수정</a>
                                <span class="mdfy-text">수정 접수</span>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="view.php">2023.02.05</a></td>
                            <td><a href="view.php">2023.02.12</a></td>
                            <td><a href="view.php">M2</a></td>
                            <td><a href="view.php">36,000원</a></td>
                            <td><a href="view.php">D1023123</a></td>
                            <td><a href="view.php">010-1234-1234</a></td>
                            <td><a href="view.php">베트남 A패키지 포함 상품</a></td>
                        </tr>                    
                    <tbody>
                </table>
            </div>
            
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
        <!-- List start -->
    </div>
    <!-- 신청내역 조회/수정 end -->

    
<!-- Modal 수정 start -->
<div id="check-modify-modal">
        <div class="modal-bg">
            <div class="modal-cont">
				<div class="title">
					<h2>수정</h2>
					<a href="#;" class="close md-close"></a>
				</div>

				<div class="cont-wrap">					
                    <div class="select-box">
                        <select name="" id="">
                            <option value="">수정 접수</option>
                            <option value="">취소 접수</option>
                        </select>
                    </div>

                    <textarea name="" id="" class="textarea"></textarea>
				</div>				
            </div>
        </div>
    </div>
    <script>
        $(".btn-check-mdfy").click(function(){
            var btn = $(this).attr("id");
            //alert(btn);
            $("#check-modify-modal").removeAttr("class").addClass(btn);
        });

        $(".md-close").click(function(){
             $("#check-modify-modal").addClass("out");
        });
    </script>

<!-- Modal 수정 end -->
<?php include '../../include/footer.php'; ?> 