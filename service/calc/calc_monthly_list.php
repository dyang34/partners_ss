<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanChangeMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[3,0];

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");

$_year_from = RequestUtil::getParam("_year_from", date("Y", strtotime("-1 month")));
$_year_to = RequestUtil::getParam("_year_to", date("Y"));
$_month_from = RequestUtil::getParam("_month_from", date("m", strtotime("-1 month")));
$_month_to = RequestUtil::getParam("_month_to", date("m"));
$_manager_info = RequestUtil::getParam("_manager_info", "");

if(!empty($_manager_info)) {
    $arr_manager_info = explode('|', $_manager_info);

    $_manager_name = $arr_manager_info[0];
    $_manager_idx = $arr_manager_info[1];
} else {
    $_manager_name = "";
    $_manager_idx = -1;
}

$date_from = $_year_from.sprintf("-%02d-01", $_month_from);
if($_month_to=="12") {
    $date_to = ($_year_to+1)."-01-01";
} else {
    $date_to = $_year_to.sprintf("-%02d-01", ($_month_to+1));
}

$wq = new WhereQuery(true, true);
$wq->addAndString("member_no","=",$__CONFIG_MEMBER_NO);
$wq->addAndString("change_type","=","1");
$wq->addAndStringBind("a.regdate", ">=", $date_from, "unix_timestamp('?')");
$wq->addAndStringBind("a.regdate", "<", $date_to, "unix_timestamp('?')");

if($_manager_idx==0) {
    $wq->addAndString2("ifnull(manager_idx, 0)","=","0");
} else {
    $wq->addAndString("manager_name","=",$_manager_name);
}

$wq_cancel = new WhereQuery(true, true);
$wq_cancel->addAndString("member_no","=",$__CONFIG_MEMBER_NO);
$wq_cancel->addAndString("change_type","=","3");
//$wq_cancel->addAndStringBind("a.change_date", ">=", $date_from, "unix_timestamp('?')");
//$wq_cancel->addAndStringBind("a.change_date", "<", $date_to, "unix_timestamp('?')");
$wq_cancel->addAndStringBind("a.regdate", ">=", $date_from, "unix_timestamp('?')");
$wq_cancel->addAndStringBind("a.regdate", "<", $date_to, "unix_timestamp('?')");
$wq_cancel->addAndString("manager_name","=",$_manager_name);

$rs = HanaPlanChangeMgr::getInstance()->getListMonthlySummary($wq, $wq_cancel);

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<div class="check-box-wrap">
    <div class="search-shnch-wrap">
        <form name="searchForm" method="get" action="calc_monthly_list.php">
            <table class="table-search">
                <colgroup>
                    <col width="7%">
                    <col width="17%">
                    <col width="7%">
                    <col width="17%">
                    <col width="7%">
                    <col width="17%">
                    <col width="7%">
                    <col width="*">
                    <col width="5%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>기간 (연/월)</th>
                        <td class="year-month">
                            <div class="select-box year-first">
                                <select name="_year_from">
<?php
for($i=date("Y", strtotime("-5 year"));$i<=date("Y");$i++) {
?>                                        
                                    <option value="<?=$i?>" <?=($i==$_year_from)?"selected='selected'":""?>><?=$i?>년</option>
<?php
}
?>

                                </select>
                            </div>
                            <div class="select-box month-first">
                                <select name="_month_from">
<?php
for($i=1;$i<=12;$i++) {
?>                                        
                                    <option value="<?=$i?>" <?=($i==$_month_from)?"selected='selected'":""?>><?=$i?>월</option>
<?php
}
?>                              
                                </select>
                            </div>
                            <span class="picker-interval">~</span>
                            <div class="select-box year-last">
                                <select name="_year_to">
<?php
for($i=date("Y", strtotime("-5 year"));$i<=date("Y");$i++) {
?>                                        
                                        <option value="<?=$i?>" <?=($i==$_year_to)?"selected='selected'":""?>><?=$i?>년</option>
<?php
}
?>
                                </select>
                            </div>
                            <div class="select-box month-last">
                                <select name="_month_to">
<?php
for($i=1;$i<=12;$i++) {
?>                                        
                                    <option value="<?=$i?>" <?=($i==$_month_to)?"selected='selected'":""?>><?=$i?>월</option>
<?php
}
?>                                    </select>
                                </select>
                            </div>
                        </td>
                        <th></th>
                        <td colspan="5">
                        </td>                        
                        <td rowspan="1" class="flow-btn"><a class="button search" name="btnSearch">검색</a></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
        
    <div class="adjustm-list-wrap">
        <h2 class="excel">실적 관리 - 월별 집계표
            <a name="btnExcelDownload" class="button excel">엑셀다운로드</a>
        </h2>
        <p class="notes fc-aqua">※ 현재 금액은 실시간 데이터로 인보이스 발행 금액과 다를 수 있습니다.</p>
        <!-- table start -->
        <div class="table-wrap">
            <table class="table-list">
                <colgroup>
                    <col width="14%">
                    <col width="14%">
                    <col width="14%">
                    <col width="14%">
                    <col width="14%">
                    <col width="14%">
                    <col width="*">
                </colgroup>
                <thead>
                    <tr>
                        <th rowspan="2">연/월</th>
                        <th colspan="2">청약 완료</th>
                        <th colspan="2">청약 취소</th>
                        <th rowspan="2">수수료율</th>
                        <th rowspan="2">커미션<br/><br/>( ( 완료 - 취소 ) X 수수료율 )</th>
                    </tr>
                    <tr>
                        <th>보험료</th>
                        <th>건수</th>
                        <th>보험료</th>
                        <th>건수</th>
                    </tr>
                </thead>
                <tbody>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();

//        $link_url = "inquiry".LoginManager::getUserLoginInfo('mem_type').".php?&_reg_date_from=2023-03-01&_reg_date_to=2023-03-10";
?>

                    <tr>
                        <td><?=$row["job_date"]?></td>
                        <td class="right"><?=number_format($row["inq_change_price"])?>원</td>
                        <td class="right"><?=number_format($row["inq_cnt"])?>건</td>
                        <td class="right"><?=number_format($row["cancel_change_price"])?>원</td>
                        <td class="right"><?=number_format($row["cancel_cnt"])?>건</td>
                        <td class="right"><?=$row["com_percent"]?></td>
                        <td class="right"><?=number_format($row["commition"])?>원</td>
                    </tr>
<?php
    }
} else {
?>
                <tr><td colspan="7" class="no-data">No Data.</td></tr>
<?php
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $(document).on("click","a[name=btnSearch]",function() {
        
        var f = document.searchForm;

        if(!chk_search_field(f)) {
            return false;
        }

        f.submit();
    });

    $(document).on('click','a[name=btnExcelDownload]', function() {
        
        var f = document.searchForm;

        if(!chk_search_field(f)) {
            return false;
        }
        
        f.target = "_new";
        f.action = "calc_monthly_list_xls.php";
        
        f.submit();
    });
});

const chk_search_field = function(f) {
    if(f._year_from.value > f._year_to.value) {
        alert("조회 종료일이 조회 시작일보다 과거입니다.    ");
        return false;
    } else if(f._year_from.value == f._year_to.value && f._month_from.value > f._month_to.value) {
        alert("조회 종료일이 조회 시작일보다 과거입니다.    ");
        return false;
    }

    if((Number(f._year_to.value)*12+Number(f._month_to.value))-(Number(f._year_from.value)*12+Number(f._month_from.value)) > 23) {
        alert("최대 24개월까지 조회 가능합니다.    ");
        return false;
    }

    return true;
}
</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

@ $rs->free();
?>