<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[2,0];

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "10");

$pg = new Page($currentPage, $pageSize);

$_reg_date_from = RequestUtil::getParam("_reg_date_from", date("Y-m-d"));
$_reg_date_to = RequestUtil::getParam("_reg_date_to", date("Y-m-d"));
$_name = RequestUtil::getParam("_name", "");
$_plan_list_state = RequestUtil::getParam("_plan_list_state", "");
$_manager_name = RequestUtil::getParam("_manager_name", "");

$_order_by = RequestUtil::getParam("_order_by", "a.no");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$wq = new WhereQuery(true, true);
$wq->addAndString("member_no","=",$__CONFIG_MEMBER_NO);
$wq->addAndStringBind("regdate", ">=", $_reg_date_from, "unix_timestamp('?')");
$wq->addAndStringBind("regdate", "<", $_reg_date_to, "unix_timestamp(date_add('?', interval 1 day))");
if($_plan_list_state=="1") {
    $wq->addAndIn("plan_list_state",array(1,6));
} else {
    $wq->addAndString("plan_list_state","=",$_plan_list_state);
}
$wq->addAndString("manager_name","=",$_manager_name);

if(!empty($_name)) {
    $wq->addAnd2("no in (select distinct hana_plan_no from hana_plan_member where member_no = '".$__CONFIG_MEMBER_NO."' and name = '".$_name."')");
}

$wq->addOrderBy($_order_by, $_order_by_asc);
$wq->addOrderBy("no", "desc");

$rs = HanaPlanMgr::getInstance()->getListReprePerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>

<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">

    <input type="hidden" name="_reg_date_from" value="<?=$_reg_date_from?>">
    <input type="hidden" name="_reg_date_to" value="<?=$_reg_date_to?>">
    <input type="hidden" name="_name" value="<?=$_name?>">
    <input type="hidden" name="_plan_list_state" value="<?=$_plan_list_state?>">
    <input type="hidden" name="_order_by" value="<?=$_order_by?>">
    <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">
</form>

    <!-- 신청내역 조회/수정 start -->
    <div class="check-box-wrap">
        <div class="search-shnch-wrap">

            <form name="searchForm" method="get" action="inquiry1.php">
                <input type="hidden" name="_order_by" value="<?=$_order_by?>">
                <input type="hidden" name="_order_by_asc" value="<?=$_order_by_asc?>">

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
                            <th>청약일</th>
                            <td class="inp">
                                <div class="date_picker">
                                    <input type="text" class="picker" name="_reg_date_from" id="start_date" readonly value="<?=$_reg_date_from?>">
                                </div>
                                <span class="picker-interval">~</span>
                                <div class="date_picker">
                                    <input type="text" class="picker" name="_reg_date_to" id="end_date" readonly value="<?=$_reg_date_to?>">
                                </div>
                            </td>

                            <th>피보험자</th>
                            <td>
                                <input type="text"  class="input-search" name="_name" value="<?=$_name?>" placeholder="검색할 피보험자 이름을 입력하세요.">
                            </td>

                            <th>진행상태</th>
                            <td>
                                <div class="select-box">
                                    <select name="_plan_list_state">
                                        <option value="">전체</option>
<?php
	foreach($arrPlanStateText as $key => $value) {
        if( $key < 6) {
?>
                                        <option value="<?=$key?>" <?=$_plan_list_state==$key?"selected":""?>><?=$value?></option>
<?php
        }
    }
?>
                                    </select>
                                </div>
                            </td>

                            <th></th>
                            <td>
                            </td>

                            <td rowspan="1" class="flow-btn"><a class="button search" name="btnSearch">검색</a></td>
                        </tr>
                    </tbody>
                </table>
            </form>            
        </div>
        <!-- List start -->
        <div class="table-list-wrap">
            <div class="table-history-wrap">
                <table class="table-list">
                    <colgroup>
                        <col width="3%">

                        <col width="7%">
                        <col width="7%">
                        <col width="7%">
                        <col width="5%">
<?php
    if(LoginManager::getUserLoginInfo("calc_period_type")=="9") {
?>
                        <col width="7%">
<?php        
    }
?>
                        <col width="7%">

                        <col width="9%">

                        <col width="8%">

                        <col width="7%">
                        <col width="8%">

                        <col width="12%">
                        <col width="*">
                    </colgroup>
                    <thead>
                        <tr>
                            <th rowspan="2">no</th>
                            <th rowspan="2">보험사</th>
                            <th rowspan="2">보험상품</th>
                            <th rowspan="2">청약일</th>
                            <th rowspan="2">진행상태</th>
<?php
    if(LoginManager::getUserLoginInfo("calc_period_type")=="9") {
?>
                            <th rowspan="2">입금일</th>
<?php        
    }
?>

                            <th>여행시작일</th>

                            <th rowspan="2">플랜코드</th>
<?php /*                            
                            <th>대표 피보험자</th>
*/?>
                            <th rowspan="2">대표 피보험자</th>

                            <th rowspan="2">보험료</th>
                            <th rowspan="2">증권번호</th>

                            <th rowspan="2">여행지</th>
                            <th>추가정보1</th>
                        </tr>
                        <tr>
<?php /*                            
                            <th>담당자</th>
*/?>                            
                            <th>여행종료일</th>
<?php /*                            
                            <th>주민등록번호</th>
*/?>
                            <th>추가정보2</th>
                        </tr>
                    </thead>
                    <tbody>

<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();

        //$link_url = "inquiry1_view.php?hana_plan_no=".$row["no"];
/*        
        //$jumin = (double)decode_pass($row["jumin_1"],$pass_key).(double)decode_pass($row["jumin_2"],$pass_key);
        //$jumin = preg_replace("/([0-9]{6})([0-9])([0-9]+)/", "$1-$2******", $jumin);
*/        
//        $jumin = trim(decode_pass($row["jumin_1"],$pass_key))."-".substr(trim(decode_pass($row["jumin_2"],$pass_key)),0,1)."******";
?>
                        <tr>
                        
                            <td rowspan="2"><?=number_format($pg->getMaxNumOfPage() - $i)?></td><!-- no -->
                            <td rowspan="2"><a><?=$arrInsuranceCompany[$row["company_type"]]?></a></td><!-- 보험사 -->
                            <td rowspan="2"><a><?=$arrTripType[$row["trip_type"]]?></a></td><!-- 보험상품 -->
                            <td rowspan="2"><a><?=date('Y-m-d', $row["regdate"])?></a></td><!-- 청약일 -->
                            <td rowspan="2"><a name="plan_list_state_text"><?=$arrPlanStateText[$row["plan_list_state"]]?></a></td><!-- 진행상태 -->
<?php
    if(LoginManager::getUserLoginInfo("calc_period_type")=="9") {
?>
                            <td rowspan="2"><a><?=$row["deposit_date"]?></a></td><!-- 입금일 -->
<?php        
    }
?>
                            <td><a><?=$row["start_date"]?></a></td><!-- 여행시작일 -->
                            <td rowspan="2"><a><?=$row["plan_code"]." (".$row["plan_title"].")"?></a></td><!-- 플랜코드 -->
<?php /*                            
                            <td><a><?=$row["name"]?></a></td><!-- 대표 피보험자 -->
*/?>                            
                            <td rowspan="2"><a><?=substr($row['name'],0,-3)."*"?></a></td><!-- 대표 피보험자 -->
                            <td rowspan="2"><a><?=number_format($row["price_sum"])?>원</a></td><!-- 보험료 -->
                            <td rowspan="2"><a><?=$row["plan_join_code"]?$row["plan_join_code"]:$row["plan_join_code_replace"]?></a></td><!-- 증권번호 -->
                            <td rowspan="2" class="tvl-dest"><a><?=$row["trip_type"]=="1"?"국내일원":$row["nation_txt"]?></a></td><!-- 여행지 -->
                            <td><a><?=$row["add_info1"]?></a></td><!-- 추가정보1 -->
                        </tr>
                        <tr>
<?php /*                            
                            <td><a><?=$row["manager_name"]?></a></td><!-- 담당자 -->
*/?>                            
                            <td><a><?=$row["end_date"]?></a></td><!-- 여행종료일 -->
<?php /*                            
                            <td><a><?=$jumin?></a></td><!-- 주민등록번호 -->
*/?>                            
                            <td><a><?=$row["add_info2"]?></a></td><!-- 추가정보2 -->
                        </tr>
<?php
    }
} else {
?>
                <tr><td colspan="12" class="no-data">No Data.</td></tr>
<?php
}
?>
                    <tbody>
                </table>
            </div>
            <?=$pg->getNaviForFuncULifeB2B("goPage", "<<", "<", ">", ">>")?>
        </div>
        <!-- List start -->
    </div>
    <!-- 신청내역 조회/수정 end -->
    
<!-- Modal 수정 start -->
    <div id="check-modify-modal" name="div_modal_window">
        <div class="modal-bg">
            <div class="modal-cont">
				<div class="title">
					<h2>수정</h2>
					<a class="close md-close"></a>
				</div>

				<div class="cont-wrap">					
                    <div class="select-box">
                        <select name="change_type" >
                            <option value="4">수정 접수</option>
                            <option value="2">취소 접수</option>
                        </select>
                    </div>
                    <textarea name="" id="req_content" class="textarea"></textarea>
                    
                    <div class="btn-cent">
                        <a name="btnRequestSave" class="button blue">저장</a>
                        <a class="md-close button black">취소</a>
                    </div>
				</div>
            </div>
        </div>
    </div>
<!-- Modal 수정 end -->

<script src="/js/ValidCheck.js"></script>	
<script type="text/javascript">

let g_req_obj;

// 달력 script
$(document).ready(function() {
    $("#start_date, #end_date").datepicker({
        showOn: "both",
        dateFormat: "yy-mm-dd",
        buttonImage: "/img/service/icon-calendar.svg",
        buttonImageOnly: true,
        showOtherMonths: true,
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        buttonText: "Select date",
    });

    $(document).on("click","a[name=btnSearch]",function() {
	
        var f = document.searchForm;

        if ( VC_inValidDate(f._reg_date_from, "청약 시작일") ) return false;
        if ( VC_inValidDate(f._reg_date_to, "청약 종료일") ) return false;

        var arrFromDate=f._reg_date_from.value.split('-');
        var arrToDate=f._reg_date_to.value.split('-');
        
        var fromDate = new Date(arrFromDate[0],arrFromDate[1]-1,arrFromDate[2]);
        var toDate = new Date(arrToDate[0],arrToDate[1]-1,arrToDate[2]);

        toDate.setMonth(toDate.getMonth()-24);
        
        if (fromDate < toDate) {
            alert("최대 24개월 단위로 조회하실 수 있습니다.    ");
            f._order_date_from.focus();
        
            return false;
        }
        
        f.submit();	
    });

    $(document).on("click",".btn-check-mdfy",function() {

        g_req_obj = $(this);
        //let plan_list_state = g_req_obj.attr('plan_list_state');

        $.ajax({
            type : "POST",
            url : "/service/ajax/get_plan_request.php",
            data : { 'company_type' : $(this).attr("company_type"), 'member_no' : '<?=$__CONFIG_MEMBER_NO?>', 'hana_plan_no' : $(this).attr("hana_plan_no") },
            dataType : 'json',
            async : false,
            success : function(data, status)
            {
                if(data.RESULTCD == "200") {

                    if(data.DATA.no !== undefined && data.DATA.change_state == 1 ) {

                        $('#req_content').val(data.DATA.content);
                        $('select[name=change_type]').val(data.DATA.change_type);
                    }

                    $("#check-modify-modal").removeAttr("class").addClass(g_req_obj.attr("mode"));

                } else if(Number(data.RESULTCD) == 900) {
                        alert(data.RESULTMSG);
                        location.replace('/');
                } else {
                    if(Number(data.RESULTCD) > 900) {
                        alert(data.RESULTMSG);
                    }
                }
            },
            error : function(err)
            {
                alert(err.responseText);
            }
        });

        //return false;
    });

    $(document).on("click","a[name=btnRequestSave]",function() {

        if($('#req_content').val().trim()=="") {
            alert("요청 내역을 입력해 주십시오.    ");
            $('#req_content').focus();
            return false;
        }

        $.ajax({
            type : "POST",
            url : "/service/ajax/plan_request_ins.php",
            data : { 'company_type' : g_req_obj.attr("company_type"), 'member_no' : '<?=$__CONFIG_MEMBER_NO?>', 'hana_plan_no' : g_req_obj.attr("hana_plan_no"), 'change_type' : $('select[name=change_type]').val(), 'content' : $('#req_content').val() },
            dataType : 'json',
            async : false,
            success : function(data, status)
            {
                if(data.RESULTCD == "200") {
                    g_req_obj.closest("tr").find("a[name=plan_list_state_text]").html(data.STATUS_TEXT);
                
                } else if(Number(data.RESULTCD) == 900) {
                        alert(data.RESULTMSG);
                        location.replace('/');
                } else {
                    if(Number(data.RESULTCD) > 900) {
                        alert(data.RESULTMSG);
                    }
                }
            },
            error : function(err)
            {
                alert(err.responseText);
            }
        });

        close_modal();

        return false;
    });
});

const goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "inquiry1.php";
	f.submit();
}
</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

$rs->free();
?>