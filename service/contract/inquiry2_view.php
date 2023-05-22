<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMemberMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

if (LoginManager::getUserLoginInfo("mem_type")=="1") {
    JsUtil::alertReplace("접근 권한이 없습니다.    ","/");
}

$menuNo=[2,0];

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");
$hana_plan_no = RequestUtil::getParam("hana_plan_no", "");

$row = HanaPlanMgr::getInstance()->getByKey($hana_plan_no);

$wq = new WhereQuery(true, true);
$wq->addAndString("member_no","=",$__CONFIG_MEMBER_NO);
$wq->addAndString("hana_plan_no","=",$hana_plan_no);
$rs = HanaPlanMemberMgr::getInstance()->getList($wq);

$arrPrintableState = [1, 5, 6];

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
  <!-- 신쳥내역 상세 start -->
    <div class="check-box-wrap">
        <div class="top-detail-wrap">
            <div class="cont-left-wrap">
                <div class="period">
                    <strong>여행기간</strong> : 
                    <span><?=$row["start_date"]?></span> ~ <span><?=$row["end_date"]?></span>
                </div>

                <div class="travel">
                    <strong>여행지</strong>  : 
                    <span><?=($row["trip_type"]==1)?"국내일원":$row["nation_txt"]?></span>
                </div>
            
                <div class="insurance">
                    <strong>보험사</strong> : 
                    <span><?=$arrInsuranceCompany[$row["company_type"]]?></span>
                </div>
            </div>

            <div class="btn-right-wrap">
                <!-- <a id="three" class="button blue btn-enrl-cnfr">가입 확인서</a> -->
<?php
    if (in_array($row["plan_list_state"], $arrPrintableState)) {
        if($row["company_type"]=="5") {
?>        
                <a class="button blue" onclick="openPopup(1)">가입 확인서</a>
                <a class="button blue cfm_type2" onclick="openPopup(2)">통합 확인서(영문, 국문)</a>
<?php
        } else {
?>        
                <a class="button blue" onclick="openPopup(1)">가입 확인서</a>
                <a class="button blue" onclick="openPopup(2)">영문 확인서</a>
<?php
        }
    }
?>                
            </div>
        </div>
        
        <!-- List start -->
        <div class="table-wrap">
            <table class="table-list">
                <colgroup>
                    <col width="5%">
                    <col width="7%">
                    <col width="7%">
                    <col width="10%">
                    <col width="10%">
                    <col width="7%">
                    <col width="7%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="*">
                </colgroup>
                <thead>
                    <tr>
                        <th>no</th>
                        <th>진행상태</th>
                        <th>피보험자</th>
                        <th>영문성명</th>
                        <th>주민등록번호</th>
                        <th>성별</th>
                        <th>보험나이</th>
                        <th>플랜코드</th>
                        <th>플랜명</th>
                        <th>보험료</th>
                        <th>비고</th>
                    </tr>
                </thead>
                <tbody>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row_mem = $rs->fetch_assoc();
        //$jumin = (double)decode_pass($row_mem["jumin_1"],$pass_key).(double)decode_pass($row_mem["jumin_2"],$pass_key);
        //$jumin = preg_replace("/([0-9]{6})([0-9])([0-9]+)/", "$1-$2******", $jumin);
        $jumin = trim(decode_pass($row_mem["jumin_1"],$pass_key))."-".substr(trim(decode_pass($row_mem["jumin_2"],$pass_key),0,1))."******";
?>                    
                    <tr>
                        <td><?=$i+1?></td>
                        <td><?=$arrPlanStateText[$row["plan_list_state"]]?></td>
                        <td><?=$row_mem["name"]?></td>
                        <td><?=$row_mem["name_eng"]?></td>
                        <td><?=$jumin?></td>
                        <td><?=($row_mem["sex"]==1)?"남성":"여성"?></td>
                        <td><?=$row_mem["age"]?>세</td>
                        <td><?=$row_mem["plan_code"]?></td>
                        <td><?=$row_mem["plan_title"]?></td>
                        <td class="right"><?=number_format($row_mem["plan_price"])?>원</td>
                        <td></td>
                    </tr>
<?
    }
}
?>
                <tbody>
            </table>
        </div>

        <div class="center-button-area">
            <a onClick="history.go(-1);return false;" class="button blue">이전</a>
        </div>

    </div>
<?/*    
    <?php include '../modal/enrl-cnfr.php'; ?> <!-- 가입 확인서 Modal -->
*/?>    
  <!-- 신쳥내역 상세 end -->

<script type="text/javascript">
    function openPopup(p_trip_type){
        var _width = '750';
        var _height = '750';
        // 팝업을 가운데 위치시키기 위해 아래와 같이 값 구하기
        var _left = Math.ceil(( window.screen.width - _width )/2);
        var _top = Math.ceil(( window.screen.height - _height )/2);

        window.open('/service/modal/report_confirm.php?trip_type='+p_trip_type+'&company_type=<?=$row["company_type"]?>&hana_plan_no=<?=$hana_plan_no?>', 'cnfrm', 'width='+ _width +', height='+ _height +', left=' + _left + ', top='+ _top );
    }
</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

$rs->free();
?>