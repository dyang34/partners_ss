<? $mNum = "2"; ?>
<?php include '../../include/header.php'; ?>
  <!-- 신쳥내역 상세 start -->
    <div class="check-box-wrap">
        <div class="top-detail-wrap">
            <div class="cont-left-wrap">
                <div class="period">
                    <strong>보험기간</strong> : 
                    <span>2023.01.22</span> ~ <span>2023.02.22</span>
                </div>

                <div class="travel">
                    <strong>여행지</strong>  : 
                    <span>일본</span>
                </div>
            
                <div class="insurance">
                    <strong>보험사</strong> : 
                    <span>삼성화재</span>
                </div>
            </div>

            <div class="btn-right-wrap">
                <!-- <a id="three" class="button blue btn-enrl-cnfr">가입 확인서</a> -->
                <a class="button blue" onclick="openPopup()">가입 확인서</a>
            </div>
        </div>
        
        
        <!-- List start -->
        <div class="table-wrap">
            <table class="table-list">
                <colgroup>
                    <col width="5%">
                    <col width="11%">
                    <col width="11%">
                    <col width="*">
                    <col width="8%">
                    <col width="14%">
                    <col width="16%">
                    <col width="10%">
                    <col width="11%">
                </colgroup>
                <thead>
                    <tr>
                        <th>no</th>
                        <th>진행상태</th>
                        <th>대표피보험자</th>
                        <th>피보험자 주민등록번호</th>
                        <th>보험나이</th>
                        <th>추가정보</th>
                        <th>플랜명</th>
                        <th>보험료</th>
                        <th>비고</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>결제완료</td>
                        <td>홍길동</td>
                        <td>901212-1******</td>
                        <td>30세</td>
                        <td>베트남 A패키지 포함 상품</td>
                        <td>표준형</td>
                        <td class="right">3,000원</td>
                        <td></td>
                    </tr>
                <tbody>
            </table>
        </div>

        <div class="center-button-area">
            <a onClick="history.go(-1)" class="button blue">이전</a>
        </div>

    </div>
    <?php include '../modal/enrl-cnfr.php'; ?> <!-- 가입 확인서 Modal -->
  <!-- 신쳥내역 상세 end -->

<script>
    function openPopup(){
        var _width = '750';
        var _height = '750';
        // 팝업을 가운데 위치시키기 위해 아래와 같이 값 구하기
        var _left = Math.ceil(( window.screen.width - _width )/2);
        var _top = Math.ceil(( window.screen.height - _height )/2);

        window.open('../modal/cnfrm-sbscr.php', 'cnfrm', 'width='+ _width +', height='+ _height +', left=' + _left + ', top='+ _top );
    }
</script>


<?php include '../../include/footer.php'; ?>