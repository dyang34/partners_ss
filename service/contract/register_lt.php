<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/nation/NationMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
    exit;
}

$menuNo=[1,0];

$trip_type = RequestUtil::getParam("trip_type","2");

if($trip_type=="1") {
    $arr_company_type = LoginManager::getUserLoginInfo('company_type_list')[1];
} else {
    $arr_company_type = LoginManager::getUserLoginInfo('company_type_list')[2];
}

if (empty(count($arr_company_type))) {
    JsUtil::alertReplace("비정상적인 접근입니다. (ErrCode:0x05)    ","/");
    exit;
}

$company_type = RequestUtil::getParam("company_type",$arr_company_type[0]['company_type']);

$now_date = date('Y-m-d');

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");
if(!LoginManager::getUserLoginInfo("fg_not_common_plan")) {
	$__CONFIG_MEMBER_NO = get_default_member_no($company_type);
}

$arrNation = array();
$wq = new WhereQuery(true,true);
if($company_type=="2") {
    $wq->addAndString("use_type_meritz","=","1");
} else if($company_type=="3") {
    $wq->addAnd2("(use_type='Y' or no in ('512', '508', '509', '487')) ");
} else if($company_type=="5") {
    $wq->addAnd2("(use_type='Y' or no in ('509')) ");
} else {
    $wq->addAndString("use_type","=","Y");
}
$wq->addOrderBy("nation_name","asc");

$rs = NationMgr::getInstance()->getList($wq);
if($rs->num_rows > 0) {
	for($i=0;$i<$rs->num_rows;$i++) {
		$row = $rs->fetch_assoc();
		$arrNation[$row["no"]] = $row["nation_name"];
	}
}

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
  <!-- 청약등록 start -->
    <!-- 기본정보 start -->
<form name="writeForm" id="writeForm" method="post" autocomplete="off">
    <input type="hidden" name="auto_defense" />
    <input type="hidden" name="term_day" />
    <div class="search-basic-wrap">
        <h2>기본정보</h2>
        <table class="table-search">
            <colgroup>
                <col width="133px">
                <col width="400px">
                <col width="133px">
                <col width="400px">
                <col width="*">
                <col width="400px">
            </colgroup>
            <tbody>
                <tr>
                    <th>보험사 <em class="bulStyle1">*</em></th>
                    <td>
<?php
    if (count($arr_company_type) < 2) {
?>
                        <input type="text" class="input-search" readonly value="<?=$arrInsuranceCompany[$company_type]?>">
                        <input type="hidden" name="company_type" value="<?=$company_type?>" />
<?php
    } else {
?>                        
                        <div class="select-box">
                            <select name="company_type">
<?php
        for($i=0;$i<count($arr_company_type);$i++) {
?>
                                <option value="<?=$arr_company_type[$i]['company_type']?>" <?=$company_type==$arr_company_type[$i]['company_type']?"selected='selected'":""?>><?=$arrInsuranceCompany[$arr_company_type[$i]['company_type']]?></option>
<?
    }
?>
                            </select>
                        </div>
<?php
    }
?>
                    </td>

                    <th>계약 담당자 <em class="bulStyle1">*</em></th>                    
                    <td>
<?php
                    $arrManager = LoginManager::getUserLoginInfo("manager_list");

                    if($arrManager) {
?>
                        <div class="select-box">
                            <select name="manager_idx">
<?php
                                for($i=0;$i<count($arrManager);$i++) {
?>
                                <option value="<?=$arrManager[$i]['idx']?>"><?=$arrManager[$i]['name']?></option>
<?
                                }
?>
                            </select>
                        </div>
                        <input type="hidden" name="manager_name" />
<?php
                    } else {
?>
                        <input type="hidden" name="manager_idx" value="0" />
                        <input type="text" class="input-search" name="manager_name" readonly value="담당자" />
<?php                        
                    }
?>
                    </td>

                    <th>청약일</th>
                    <td><input type="text"  class="input-search" name="" id="" readonly value="<?=$now_date?>" tabindex="-1"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- 기본정보 end -->
    
    <!-- 여행정보 start -->
    <div class="search-basic-wrap">
        <h2>여행정보</h2>
        <table class="table-search">
            <colgroup>
                <col width="133px">
                <col width="400px">
                <col width="133px">
                <col width="400px">
                <col width="*">
                <col width="400px">
            </colgroup>
            <tbody>
                <tr>
                    <th>여행종류 <em class="bulStyle1">*</em><a class="btn-travel-type" name="icon_trip_type_2" motion="three" style="<?=$trip_type=="1"?"display:none;":""?>"><i class="icon-question"></i></a></th>
                    <td>
<?php
    if($_GET["fg_trip_type_show"]=="Y") {
?>        
                        <div class="radio-wrap">
                            <input type="radio" id="trvTypeChk2" name="trip_type" value="2" checked='checked'>
                            <label for="trvTypeChk2">해외</label>
                            <div class="check"></div>
                        </div>
                        <div class="radio-wrap">
                            <input type="radio" id="trvTypeChk1" name="trip_type" value="1">
                            <label for="trvTypeChk1">국내</label>
                            <div class="check"></div>
                        </div>
<?php
    } else {
?>    
                        <input type="hidden" name="trip_type" value="<?=$trip_type?>" />
                        <input type="text"  class="input-search" name="" id="" readonly value="<?=$arrTripType[$trip_type]?>">
<?php
    }
?>

                    </td>

                    <th>여행지역 <em class="bulStyle1">*</em><a class="btn-travel-area" name="icon_trip_type_2" motion="three" style="<?=$trip_type=="1"?"display:none;":""?>"><i class="icon-globe"></i></a></th>   
                    <td class="nation">
<?php
    if($trip_type=="2") {
?>
                        <div name="div_nation_2">
                            <select name="nation_srch" class="sel_item" >
                                <option value="">여행지역 선택</option>
<?php                                     	
	    foreach($arrNation as $key => $value) {
?>
                                <option value="<?=$key?>"><?=$value?></option>
<?php
        }
?>
                            </select>
                        </div>
<?php
    }
?>
                        <div name="div_nation_1" class="tbl-div" style="<?=$trip_type=="2"?"display:none;":""?>">국내일원</div>
                        <input type="hidden" name="nation" value=""/>
                    </td>

                    <th>여행기간 <em class="bulStyle1">*</em></th>
                    <td class="inpt">
                        <div class="date_picker">
                            <input type="text" class="picker format-date" name="start_date" id="start_date"  placeholder="YYYY-MM-DD" maxlength="10">
                        </div>
                        <input type="hidden" name="start_hour" id="start_hour" value="00" />
                        <span class="picker-interval">~</span>
                        <div class="date_picker">
                            <input type="text" class="picker format-date" name="end_date" id="end_date"  placeholder="YYYY-MM-DD" maxlength="10">
                        </div>
                        <input type="hidden" name="end_hour" id="end_hour" value="24" />
                    </td>
                </tr>
                <tr>
                    <th>여행목적 <em class="bulStyle1">*</em></th>
                    <td class="inpt">
                        <div class="select-box">
                            <select name="trip_purpose">
                                <option value="1">여행/관광</option>
                                <option value="2">연수/출장</option>
                            </select>
                        </div>
                    </td>

                    <th>추가정보 1</th>                    
                    <td><input type="text"  class="input-search" name="add_info1" placeholder="50자 이내로 작성해주세요"></td>

                    <th>추가정보 2</th>
                    <td><input type="text"  class="input-search" name="add_info2" placeholder="50자 이내로 작성해주세요"></td>
                </tr>
<?php if($_GET["hp"]=="Y") { ?>
                <tr>
                    <th>휴대폰 <em class="bulStyle1">*</em></th>
                    <td><input type="tel" class="input-search input_number input_phone" name="repre_hp" placeholder="010-2345-6789" maxlength="11"></td>

                    <th>이메일 <em class="bulStyle1">*</em></th>                    
                    <td><input type="email"  class="input-search" name="repre_email" placeholder="bis@bis.co.kr"></td>

                    <td colspan="2" class="last"></td>
                </tr>
<?php } ?>
            </tbody>
        </table>
    </div>
    <!-- 여행정보 end -->

    <!-- 플랜정보선택 start -->
    <div class="search-basic-wrap">
        <h2 class="six">플랜선택
            <div class="select-style-01">
                <select name="plan_repre_type" id="">
                </select>
            </div>
        </h2>
        
        <div class="flan-info-wrap inb">
            <!-- 주니어 start -->
            <div class="flan-box-list div_plan_type" name="divPlantypeCal1">
                <ul class="clearfix inb">
                    <li class="title"><span name="title_plan_type_sub_cal1"></span> <a class="btn-flan-info" motion="three" cal_type="1"><i class="icon-question"></i></a></li>
                    <li>
                        <div class="select-box">
                            <select name="plan_type_sub_cal1" class="cls_select_plan_sub" age_from="" age_to="" age_to_limit="">
                            </select>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- 주니어 end -->
            <!-- 성인 start -->
            <div class="flan-box-list div_plan_type" name="divPlantypeCal2">
                <ul class="clearfix inb">
                    <li class="title"><span name="title_plan_type_sub_cal2"></span> <a class="btn-flan-info" motion="three" cal_type="2"><i class="icon-question"></i></a></li>
                    <li>
                        <div class="select-box">
                            <select name="plan_type_sub_cal2" class="cls_select_plan_sub" age_from="" age_to="" age_to_limit="">
                            </select>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- 성인 end -->
            <!-- 시니어1 start -->
            <div class="flan-box-list div_plan_type" name="divPlantypeCal3">
                <ul class="clearfix inb">
                    <li class="title"><span name="title_plan_type_sub_cal3"></span> <a class="btn-flan-info" motion="three" cal_type="3"><i class="icon-question"></i></a></li>
                    <li>
                        <div class="select-box">
                            <select name="plan_type_sub_cal3" class="cls_select_plan_sub" age_from="" age_to="" age_to_limit="">
                            </select>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- 시니어1 end -->
            <!-- 시니어2 start -->
            <div class="flan-box-list div_plan_type" name="divPlantypeCal4">
                <ul class="clearfix inb">
                    <li class="title"><span name="title_plan_type_sub_cal4"></span> <a class="btn-flan-info" motion="three" cal_type="4"><i class="icon-question"></i></a></li>
                    <li>
                        <div class="select-box">
                            <select name="plan_type_sub_cal4" class="cls_select_plan_sub" age_from="" age_to="" age_to_limit="">
                            </select>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- 시니어2 end -->
        </div>
    </div>
    <!-- 플랜정보선택 end -->

    <!-- 정보입력 start -->
    <div class="table-list-wrap">
        <h2>정보입력</h2>
        <div class="right-btn-area">
            <div class="number">
                <div class="many">
                    피보험자수 <span class="number" name="spanTotalCnt">0</span> 명
                </div>
                <div class="amount">
                    총보험료  <span class="number" name="spanTotalPrice">0</span>원
                </div>
            </div>
            <a href="#" name="btnAddRow" class="button add" tabindex="-1">추가</a>
            <a href="#" name="btnDelRow" class="button delete" tabindex="-1">삭제</a>
            <a href="#" name="btnCalc" class="button blue" style="display:none;" tabindex="-1">산출</a>
        </div>
        <!-- table start -->
        <div class="table-wrap">
            <table class="table-list" name="tbl_contract">
                <colgroup>
                    <col width="3%">
                    <col width="5%">
                    <col width="8%">
                    <col width="10%">
                    <col width="12%">
                    
                    <col width="7%">
                    <col width="7%">
                    <col width="7%">
                    <col width="12%">
                    <col width="*">

                    <col width="8%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="">
                            <div class="checkbox">
                                <input type="checkbox" id="chk_All" name="chk_All" />
                                <label for="chk_All"></label>
                            </div>
                        </th>
                        <th>no</th>
                        <th class="navy">이름(국문)</th>
                        <th class="navy">이름(영문)</th>
                        <th class="navy">주민등록번호</th>
                        <th>성별</th>
                        <th>나이</th>
                        <th>연령대</th>
                        <th>플랜코드</th>
                        <th>플랜명</th>
                        <th>보험료(원)</th>
                    </tr>
                </thead>
                <tbody name="tbody_nm">
                    <tr class="tr_input" tr_error_cnt="0">
                        <td td_error="0" class="no">
                            <div class="checkbox">
                                <input type="checkbox" id="" name="chk_row" />
                                <label for=""></label>
                            </div>
                        </td>
                        <td td_error="0" name="td_no"><span class="td_last_obj" name="span_no"></span>
                            <div name="div_err" class="tooltip">
                                <span name="span_err"></span>
                                <div class="tooltip-content">
                                    <p name="p_err"></p>
                                </div>
                            </div>
                        </td>
                        <td td_error="0" class="navy"><input type="text" class="input pastable td_last_obj" autocomplete="bis-prevent-auto" name="name[]"></td>
                        <td td_error="0" class="navy"><input type="text" class="input pastable td_last_obj" autocomplete="bis-prevent-auto" name="name_eng[]"></td>
                        <td td_error="0" class="navy"><input type="text" class="input pastable input_jumin td_last_obj tr_td_last_obj" autocomplete="bis-prevent-auto" name="jumin_show[]" maxlength="13" auto_field="jumin_1" ><input type="hidden" name="jumin[]"></td>
                        <td td_error="0" class=""><input type="text" class="input td_last_obj" name="gender_text[]" tabindex="-1" readonly><input type="hidden" name="gender[]" /></td>
                        <td td_error="0" class=""><input type="text" class="input td_last_obj" name="age[]" age_std="" tabindex="-1" readonly></td>
                        <td td_error="0" class=""><input type="text" class="input td_last_obj" name="cal_type_text[]" tabindex="-1" readonly><input type="hidden" name="cal_type[]" /></td>
                        <td td_error="0" class=""><input type="text" class="input td_last_obj" name="plan_code[]" tabindex="-1" readonly><input type="hidden" name="plan_type[]" /></td>
                        <td td_error="0" class="flan">
                            <input type="text" class="input1 td_last_obj" name="plan_title[]" id="" tabindex="-1" readonly value="">
                            <a href="#" name="btnSearchPlan" class="button change btn-flan-select" tabindex="-1" motion="three">변경</a>
                        </td>
                        <td td_error="0" class="right"><input type="text" class="input td_last_obj" name="price[]" tabindex="-1" readonly></td>
                    </tr>
                    <tr class="tr_input" tr_error_cnt="0">
                        <td td_error="0" class="no">
                            <div class="checkbox">
                                <input type="checkbox" id="" name="chk_row" />
                                <label for=""></label>
                            </div>
                        </td>
                        <td td_error="0" name="td_no"><span class="td_last_obj" name="span_no"></span>
                            <div name="div_err" class="tooltip">
                                <span name="span_err"></span>
                                <div class="tooltip-content">
                                    <p name="p_err"></p>
                                </div>
                            </div>
                        </td>
                        <td td_error="0" class="navy"><input type="text" class="input pastable td_last_obj" autocomplete="bis-prevent-auto" name="name[]"></td>
                        <td td_error="0" class="navy"><input type="text" class="input pastable td_last_obj" autocomplete="bis-prevent-auto" name="name_eng[]"></td>
                        <td td_error="0" class="navy"><input type="text" class="input pastable input_jumin td_last_obj tr_td_last_obj" autocomplete="bis-prevent-auto" name="jumin_show[]" maxlength="13" auto_field="jumin_1" ><input type="hidden" name="jumin[]"></td>
                        <td td_error="0" class=""><input type="text" class="input td_last_obj" name="gender_text[]" tabindex="-1" readonly><input type="hidden" name="gender[]" /></td>
                        <td td_error="0" class=""><input type="text" class="input td_last_obj" name="age[]" age_std="" tabindex="-1" readonly></td>
                        <td td_error="0" class=""><input type="text" class="input td_last_obj" name="cal_type_text[]" tabindex="-1" readonly><input type="hidden" name="cal_type[]" /></td>
                        <td td_error="0" class=""><input type="text" class="input td_last_obj" name="plan_code[]" tabindex="-1" readonly><input type="hidden" name="plan_type[]" /></td>
                        <td td_error="0" class="flan">
                            <input type="text" class="input1 td_last_obj" name="plan_title[]" id="" tabindex="-1" readonly value="">
                            <a href="#" name="btnSearchPlan" class="button change btn-flan-select" tabindex="-1" motion="three">변경</a>
                        </td>
                        <td td_error="0" class="right"><input type="text" class="input td_last_obj" name="price[]" tabindex="-1" readonly></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="center-button-area">
            <a href="#" name="btnApply" class="button sbscr">청약 신청</a>
        </div>
        <!-- table end -->
    </div>
    <!-- 정보입력 end -->
  <!-- 청약등록 end -->
</form>

<!-- modal start -->
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/service/modal/travel-type.php';    // 여행 종류.
require_once $_SERVER['DOCUMENT_ROOT'].'/service/modal/travel-area.php';    // 보험인수 제한 국가 안내.
require_once $_SERVER['DOCUMENT_ROOT'].'/service/modal/flan-info.php';      // 플랜 정보.
require_once $_SERVER['DOCUMENT_ROOT'].'/service/modal/flan-select.php';    // 플랜코드 변경.
?>
<!-- modal end -->

<link href="/css/select2.css?v=<?=time()?>" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".sel_item").select2();
/*
	var w = $(".select2").css('width');
	add_w = parseInt(w)+50;
	$(".select2").css('width',add_w);
*/
});

function reset_Select2() {  // Select 리셋시 사용.
    $(".sel_item").val('');
    $(".sel_item").trigger('change');
}
</script>

<?/*
<script type="text/javascript" src="/js/ValidCheck.js?v=<?=filemtime($_SERVER['DOCUMENT_ROOT']."/travel/meritz/js/ValidCheck.js")?>"></script>
*/?>
<script type="text/javascript">
const fg_auto_calc = <?=$_GET['fg_auto_calc']!="N"?"true":"false"?>;
const g_company_type = "<?=$company_type?>";
const g_member_no = "<?=$__CONFIG_MEMBER_NO?>";
const g_now_date = "<?=$now_date?>";
let g_trip_type = "<?=$trip_type?>";
//let g_trip_type="2";

let mc_consult_submitted = false;
let g_obj_td;

let default_plan_type = 2;
let str_plan_type, plan_repre_title;
let arr_plan_type_sub_cal;
let cnt_cal_type;
let max_age;

const arr_paste_col_idx = [2,3,4];
const col_idx_jumin = 4;
const tr_contents = '<tr class="tr_input" tr_error_cnt="0">'
                    + '    <td td_error="0" class="no">'
                    + '        <div class="checkbox">'
                    + '            <input type="checkbox" id="" name="chk_row" />'
                    + '            <label for=""></label>'
                    + '        </div>'
                    + '    </td>'
                    + '    <td td_error="0" name="td_no"><span class="td_last_obj" name="span_no"></span>'
                    + '        <div name="div_err" class="tooltip"> '
                    + '             <span name="span_err"></span> '
                    + '             <div class="tooltip-content"> '
                    + '                 <p name="p_err"></p> '
                    + '             </div> '
                    + '        </div> '
                    + '    </td> '
                    + '    <td td_error="0" class="navy"><input type="text" class="input pastable td_last_obj" autocomplete="bis-prevent-auto" name="name[]"></td>'
                    + '    <td td_error="0" class="navy"><input type="text" class="input pastable td_last_obj" autocomplete="bis-prevent-auto" name="name_eng[]"></td>'
                    + '    <td td_error="0" class="navy"><input type="text" class="input pastable input_jumin td_last_obj tr_td_last_obj" autocomplete="bis-prevent-auto" name="jumin_show[]" maxlength="13" auto_field="jumin_1" ><input type="hidden" name="jumin[]"></td>'
                    + '    <td td_error="0" class=""><input type="text" class="input td_last_obj" name="gender_text[]" tabindex="-1" readonly><input type="hidden" name="gender[]" /></td>'
                    + '    <td td_error="0" class=""><input type="text" class="input td_last_obj" name="age[]" age_std="" tabindex="-1" readonly></td>'
                    + '    <td td_error="0" class=""><input type="text" class="input td_last_obj" name="cal_type_text[]" tabindex="-1" readonly><input type="hidden" name="cal_type[]" /></td>'
                    + '    <td td_error="0" class=""><input type="text" class="input td_last_obj" name="plan_code[]" tabindex="-1" readonly><input type="hidden" name="plan_type[]" /></td>'
                    + '    <td td_error="0" class="flan">'
                    + '        <input type="text" class="input1 td_last_obj" name="plan_title[]" id="" tabindex="-1" readonly value="">'
                    + '        <a href="#" name="btnSearchPlan" class="button change btn-flan-select" tabindex="-1" motion="three">변경</a>'
                    + '    </td>'
                    + '    <td td_error="0" class="right"><input type="text" class="input td_last_obj" name="price[]" tabindex="-1" readonly></td>'
                    + '</tr>';

$(document).ready(function() {
    let today = new Date();
    let maxday = new Date();
    
    maxday.setMonth(maxday.getMonth()+6);
    maxday.setDate(maxday.getDate()-1);

    let settingDate;
//    var tomorrow = new Date(Date.parse(today) + (1000 * 60 * 60 * 24));

    /************* 테이블 내 cell 이동, tr 생성 Start *************/
    // Enter나 ↓ 화살표 클릭시 테이블 내 tr 하위 이동 혹은 tr 생성. ↑ 화살표 클릭시 테이블 내 tr 상위 이동.
    $(document).on('keyup', '.td_last_obj', function(e) {

        if(window.event.keyCode==13 || window.event.keyCode==40 ) {	//  || window.event.keyCode==98     // Enter나 ↓ 화살표 클릭시 테이블 내 tr 하위 이동 혹은 tr 생성.

            e.stopPropagation();
            e.preventDefault();    

            const obj_table = $(this).closest('table');
            const tr_last_idx = obj_table.find('tr:last').index();
            let tr_idx = $(this).closest('tr').index();
            let td_idx = $(this).closest('td').index();
            
            if( tr_idx >= tr_last_idx) {
                obj_table.append(tr_contents);
                numbering_row();
            }

            obj_table.find('tr:eq('+(tr_idx+2)+') td:eq('+(td_idx)+') input[type=text]').focus();
            
        } else if(window.event.keyCode==38 ) {	// || window.event.keyCode==104     // ↑ 화살표 클릭시 테이블 내 tr 상위 이동.

            e.stopPropagation();
            e.preventDefault();    

            const obj_table = $(this).closest('table');
            const tr_first_idx = obj_table.find('tr:first').index();
            let tr_idx = $(this).closest('tr').index();
            let td_idx = $(this).closest('td').index();

            if( tr_idx > tr_first_idx) {
                obj_table.find('tr:eq('+(tr_idx)+') td:eq('+(td_idx)+') input[type=text]').focus();	
            }
        }
    });

    // 마지막 tr, 마지막 활성 td에서 [TAB] 클릭시 행 추가.
    $(document).on('keydown', '.tr_td_last_obj', function(e) {

        if(window.event.keyCode==9 ) {

            const obj_table = $(this).closest('table');
            const tr_last_idx = obj_table.find('tr:last').index();
            let tr_idx = $(this).closest('tr').index();
            let td_idx = $(this).closest('td').index();
            
            if( tr_idx >= tr_last_idx) {
                obj_table.append(tr_contents);
                numbering_row();
            }
        }
    });
    /************* 테이블 내 cell 이동, tr 생성 End *************/

    /************* 달력 datepicker 설정 Start *************/
    $("#start_date").datepicker({
        showOn: "both",
        dateFormat: "yy-mm-dd",
        buttonImage: "/img/service/icon-calendar.svg",
        buttonImageOnly: true,
        showOtherMonths: true,
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        buttonText: "Select date",
        minDate: today,
        maxDate: maxday,
        onClose: function( selectedDate ) {
            //$("#end_date").val("");

            if(selectedDate!="") {
                if(!checkValidDate(selectedDate)) {
                    alert("여행시작일이 올바른 날짜가 아닙니다.    ");
                    $("#start_date").val("");
                    $("#start_date").focus();
                    $("#start_date").datepicker("show");
                    $("#end_date").val("");
                    $("#end_date").datepicker("disable");
                } else {

                    if($("#end_date").val() && $("#end_date").val() < selectedDate) {
                        $("#end_date").val("");
                    }

                    if (g_trip_type=='2') {
                        //$("#end_date").datepicker( "option", "maxDate", new Date(Date.parse(selectedDate) + (1000 * 60 * 60 * 24 * 90)) );
                        settingDate = new Date(Date.parse(treemonthcal(selectedDate, '3')));
                        settingDate.setDate(settingDate.getDate()-1);
                    } else {
                        //$("#end_date").datepicker( "option", "maxDate", new Date(Date.parse(selectedDate) + (1000 * 60 * 60 * 24 * 30)) );
                        settingDate = new Date(Date.parse(treemonthcal(selectedDate, '1')));
                        settingDate.setDate(settingDate.getDate()-1);
                    }

                    if($("#end_date").val() && $("#end_date").val() > dateFormat(settingDate)) {
                        $("#end_date").val("");
                    }

                    $("#end_date").datepicker("enable");
                    $("#end_date").datepicker("option", "minDate", new Date(Date.parse(selectedDate)) );
                    $("#end_date").datepicker("option", "maxDate", settingDate);
                }
            }

            $('table[name=tbl_contract] tr').each(function(index, item) {
                if(index>0) {
                    obj = $(this).find('input[name="jumin_show[]"]');
                    if (obj.val()) {
                        chk_jumin(obj);
                    }
                }
            });

//            if($("#end_date").datepicker('getDate')) {
                calendar_calc_price();
//            }

//                else {
//                    $("#end_date").datepicker("show");
//                    $("#end_date").datepicker("");

//                    $("#end_date").focus();
//                }
        }
    });

    $("#end_date").datepicker({
        showOn: "both",
        dateFormat: "yy-mm-dd",
        buttonImage: "/img/service/icon-calendar.svg",
        buttonImageOnly: true,
        showOtherMonths: true,
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        monthNamesShort: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
        buttonText: "Select date",
        minDate: today,
        onClose: function( selectedDate ) {  
            if(selectedDate!="") {

                if(!checkValidDate(selectedDate)) {
                    alert("여행종료일이 올바른 날짜가 아닙니다.    ");
                    $("#end_date").val("");
                    $("#end_date").focus();
                    $("#end_date").datepicker("show");
                } else {

                    var start = $('#start_date').datepicker('getDate');
                    var end   = $('#end_date').datepicker('getDate');
                    endDate = end.setDate(end.getDate() + 1);
                    var days = (endDate - start)/1000/60/60/24;
    //                $("#thai_day").val(days);
                }
            }

            calendar_calc_price();
        }
    });

    $(document).on("keyup", ".format-date", function() {
//    $(".format-date").keyup(function() {
        if( this.value.length > 10){
            this.value = this.value.substr(0, 10);
        }
        var val         = this.value.replace(/\D/g, '');
        var original    = this.value.replace(/\D/g, '').length;
        var conversion  = '';
        for(i=0;i<2;i++){
            if (val.length > 4 && i===0) {
                conversion += val.substr(0, 4) + '-';
                val         = val.substr(4);
            }
            else if(original>6 && val.length > 2 && i===1){
                conversion += val.substr(0, 2) + '-';
                val         = val.substr(2);
            }
        }
        conversion += val;
        this.value = conversion;
    });
    /************* 달력 datepicker 설정 Start *************/

    $(document).on('change','#start_hour, #end_hour', function() {
        calendar_calc_price();
    });
    
    $(document).on('input, click', '.input_number', function(e) {

        e.stopPropagation();
        e.preventDefault();    

        $(this).val($(this).val().replace(/[^0-9]/g,''));
    });

    $(document).on('blur', '.input_phone', function(e) {

        e.stopPropagation();
        e.preventDefault();    

        let hp = $(this).val();

        if(chk_pattern(hp, 'hp')) {
            $(this).val(hp.replace(/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/,"$1-$2-$3"));
        }
    });

/*
    $(document).on('blur', '.input_price', function(e) {

        e.stopPropagation();
        e.preventDefault();    

        if(chk_pattern($(this).val(), 'num_comma')) {
            $(this).val($(this).val().toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
        }
    });

    $(document).on('click', '.input_price', function(e) {

        e.stopPropagation();
        e.preventDefault();    

        if(chk_pattern($(this).val(), 'num_comma')) {
            $(this).val($(this).val().replace(/[^0-9]/g,''));
        }
    });

    $(document).on('change', '.input_price', function(e){

        e.stopPropagation();
        e.preventDefault();    

        let fg_check = false;
        let val_tgt_num = "";
        const auto_field = $(this).attr('auto_field');

        fg_check = chk_row_no_error($(this), 'num_comma');

        switch(auto_field) {
            case 'price_1':
                if(fg_check && $(this).val()!='') {
                    val_tgt_num = $(this).val().replace(/[^0-9]/g,'') * 5;
                    $(this).closest('td').next().next().html(val_tgt_num.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                } else {
                    $(this).closest('td').next().next().html("");
                }
                break;
        }

        return false;
    });
*/

    $(document).on('change', '.input[name="name[]"]', function(e){

        e.stopPropagation();
        e.preventDefault();    

        chk_submit_button(fg_auto_calc);

        return false;
    });

    /************* 주민등록번호 input 제어 Start *************/
    $(document).on('input', '.input_jumin', function(e) {

        e.stopPropagation();
        e.preventDefault();    

        $(this).val($(this).val().replace(/[^0-9]/g,''));
    });

    $(document).on('blur', '.input_jumin', function(e) {

        e.stopPropagation();
        e.preventDefault();    

        asterisk_jumin_no($(this));
    });

    $(document).on('click, focusin', '.input_jumin', function(e) {

        e.stopPropagation();
        e.preventDefault();    

        if(chk_pattern($(this).next().val(), 'jumin')) {
            $(this).val($(this).next().val());
        }
    });

    $(document).on('change', '.input_jumin', function(e){

        e.stopPropagation();
        e.preventDefault();    

        $(this).next().val($(this).val());
        chk_jumin($(this));

        if(fg_auto_calc) {
            set_row_price($(this));
        }

        return false;
    });
    /************* 주민등록번호 input 제어 End *************/

    /************* 테이블 붙여넣기 기능 Start *************/
    $(document).on('paste', '.pastable', function(e) {

        const $this= $(this);
        let pasted;
        let bbbb, cccc;
        let row_idx, col, obj_tr, obj;
        let paste_error_cnt = 0;

        if (window.clipboardData && window.clipboardData.getData) {	/** ie 용 **/
            pasted = window.clipboardData.getData('Text');
        } else if (e.originalEvent.clipboardData.getData) {			/** 그 외 **/
            pasted = e.originalEvent.clipboardData.getData('text/plain');
        }

        pasted = pasted.trim('\r\n');
        bbbb = pasted.split('\r\n');
//		cccc = bbbb[0].split('\t');

        e.stopPropagation();
        e.preventDefault();    

        let td_idx = $this.closest('td').index();
        let tr_idx = $this.closest('tr').index();
//		var mm = $this.closest('td').length;
//		var obj = {};

        $.each(pasted.split('\r\n'), function(idx_y, val_y) {
            $.each(val_y.split('\t'),function(idx_x, val_x) {
                row_idx = tr_idx+idx_y, col_idx = td_idx+idx_x;

                val_x = val_x.trim();
                
                if (arr_paste_col_idx.includes(col_idx)) {

                    obj_tr = $this.closest('table').find('tr:eq('+(row_idx+1)+')');
                    if(obj_tr.length === 0) {
                        $('tbody[name=tbody_nm]').append(tr_contents);
                    }

                    obj = $this.closest('table').find('tr:eq('+(row_idx+1)+') td:eq('+col_idx+') input[type=text]');
                    
                    if(col_idx==col_idx_jumin) {

                        obj.val(val_x.replace(/[^0-9]/g,''));

                        if(!chk_jumin(obj) && val_x!='') {
                            paste_error_cnt++;
                        }

                        asterisk_jumin_no(obj);
                    } else {
                        obj.val(val_x);
                    }
                }
            });
        });

/*		
        if(paste_error_cnt > 0){
            alert('적합하지않는 주민번호가 있습니다.');
            return false;
        }    
*/
        numbering_row();
        calc_price(fg_auto_calc);
    });
    /************* 테이블 붙여넣기 기능 End *************/

    // 행 추가.
    $(document).on('click','a[name=btnAddRow]', function() {
        $('tbody[name=tbody_nm]').append(tr_contents);
        numbering_row();
    });

    // 행 삭제.
    $(document).on('click','a[name=btnDelRow]', function() {
        $('input[name=chk_row]:checked').each(function(index, item) {
            $(this).closest('tr').remove();
        });

        if($('table[name=tbl_contract] tr').length < 2) {
            $('tbody[name=tbody_nm]').append(tr_contents);
        }

        numbering_row();
        set_sum(fg_auto_calc);
    });

    // 산출.
    $(document).on('click','a[name=btnCalc]', function() {

        if(!chk_all_set_field(true, fg_auto_calc)) {
            return false;
        }

        calc_price(true);
    });

    // 체크박스 ALL.
    $(document).on('change','input[name=chk_All]', function(e) {
        $('input[name=chk_row]').prop('checked', $(this).prop('checked'));
    });

    // 여행종류 변경.
    $(document).on('change','select[name=company_type]',function() {

        location.href = "register.php?trip_type="+g_trip_type+"&company_type="+$(this).val();
/*
        g_company_type = $(this).val();
        g_member_no = ;
        get_plan_repre_list($(this).val(), g_member_no, g_trip_type);
        chg_member_plan(0, 200);
        calc_price(fg_auto_calc);
*/
        return false;
    });

    // 여행종류 변경.
    $(document).on('change','input[name=trip_type]',function() {

        // 멀티보험사 지원 버전으로 변경. → 향후 여행종류 변경 버전 적용 시 : trip_type change event 발생 시 보험사 변경되어야 함. 또한 보험사에 따른 여행지역도 변경되어야 함.
        if($(this).val()=="1") {

            $('div[name=div_nation_1]').show();
            $('div[name=div_nation_2]').hide();
            $('a[name=icon_trip_type_2]').hide();

            if(checkValidDate($('#start_date').val())) {
                settingDate = new Date(Date.parse(treemonthcal($('#start_date').val(), '1')));
                settingDate.setDate(settingDate.getDate()-1);

                if($('#end_date').datepicker('getDate') && $('#end_date').datepicker('getDate') > settingDate) {
                    $("#end_date").val("");
                }

                $("#end_date").datepicker( "option", "maxDate", settingDate);
            }
        } else {

            $('div[name=div_nation_1]').hide();
            $('div[name=div_nation_2]').show();
            $('a[name=icon_trip_type_2]').show();
            
            if(checkValidDate($('#start_date').val())) {
                settingDate = new Date(Date.parse(treemonthcal($('#start_date').val(), '3')));
                settingDate.setDate(settingDate.getDate()-1);
                $("#end_date").datepicker( "option", "maxDate", settingDate );
            }
        }

        g_trip_type = $(this).val();

        if(checkValidDate($('#start_date').val()) && checkValidDate($('#end_date').val())) {
            if(!check_hour_max()){
                $('#end_date').val('');
            }
        }

        get_plan_repre_list(g_company_type, g_member_no, $(this).val());
        chg_member_plan(0, 200);
        calc_price(fg_auto_calc);
        
        return false;
    });

    // 대표 플랜 변경시.
    $(document).on('change','select[name=plan_repre_type]',function() {
        for(i=1;i<=cnt_cal_type;i++) {
            $('select[name=plan_type_sub_cal'+i+'] option[plan_type*="'+$(this).val()+'"][plan_sort=\'0\']').prop("selected", true);
        }

        chg_member_plan(0, 200);
        calc_price(fg_auto_calc);
    });

    // 나이대별 플랜 변경시.
    $(document).on('change','.cls_select_plan_sub',function() {
        chg_member_plan($(this).attr('age_from'), $(this).attr('age_to_limit'));
        calc_price(fg_auto_calc);

        return false;
    });

    // 여행지역 변경시.
    $(document).on('change','select[name=nation_srch]',function() {
        chk_submit_button(fg_auto_calc);
        return false;
    });

    // 청약 신청 버튼.
    $(document).on('click','a[name=btnApply]', function() {

        if(!chk_all_set_field(true, true)) {
            return false;
        }

        if (!confirm("신청하시겠습니까?    ")) {
            return false;
        }

        if(g_trip_type=="1") {
            $('input[name=nation]').val("0");
        } else {
            $('input[name=nation]').val($('select[name=nation_srch]').val());
        }

<?php
        if($arrManager) {
?>
        $('input[name=manager_name]').val($('select[name=manager_idx] option:checked').text());
<?php        
        }
?>

        if(mc_consult_submitted == true) { return false; }

        var f = document.writeForm;
        f.action="/service/contract/register_act.php";
        f.auto_defense.value = "identicharmc!@";
	
        mc_consult_submitted = true;

        f.submit();	
    });

	// 플랜별 보장 내역 모달창 띄우기.
	$(document).on('click', '.btn-flan-info', function() {
		let motion = $(this).attr("motion");
        let obj_a, plan_code_selected;

        get_plan_desc(g_company_type, g_member_no, g_trip_type, $(this).attr("cal_type"));

        //$('.cls_li_plan_type_desc').removeClass('active');

        for(i=1;i<=cnt_cal_type;i++) {
            plan_code_selected = $('select[name=plan_type_sub_cal'+i+']').val();
            obj_a = $('a[name=btnChoicePlan][plan_code='+plan_code_selected+']');
            obj_a.removeClass('choice');
            obj_a.addClass('gray');

            $('.cls_li_plan_type_desc[plan_code='+plan_code_selected+']').addClass('active');
        }

		$("#flan-info-modal").removeAttr("class").addClass(motion);
	});

    // 개인별 플랜 변경 모달창 띄우기.
	$(document).on('click', '.active-plan-chg', function() {
        let obj_tr = $(this).closest('tr');
        let cal_type = obj_tr.find('input[name="cal_type[]"').val();
        let plan_code = obj_tr.find('input[name="plan_code[]"').val();
        g_obj_td = $(this);

        get_plan_choice(g_company_type, g_member_no, g_trip_type, cal_type, plan_code);

		$("#flan-select-modal").removeAttr("class").addClass($(this).attr("motion"));
	});

    $(document).on('click', 'a[name=btnChoiceMemPlan]', function() {
        let obj_tr = g_obj_td.closest('tr');

        obj_tr.find('input[name="plan_code[]"').val($(this).attr('plan_code'));
        obj_tr.find('input[name="plan_type[]"').val($(this).attr('plan_type'));
        obj_tr.find('input[name="plan_title[]"').val($(this).attr('plan_title'));

        close_modal();

        set_row_price(g_obj_td);

        return false;
    });

    $(document).on('click', 'a[name=btnChoicePlan]', function() {

        let l_cal_type = $(this).attr('cal_type');
        let l_plan_code = $(this).attr('plan_code');

        $('a[name=btnChoicePlan][cal_type='+l_cal_type+']').removeClass('gray');
        $('a[name=btnChoicePlan][cal_type='+l_cal_type+']').addClass('choice');
        
        $(this).removeClass('choice');
        $(this).addClass('gray');

        $('.cls_li_plan_type_desc[cal_type='+l_cal_type+']').removeClass('active');
        $('.cls_li_plan_type_desc[plan_code='+l_plan_code+']').addClass('active');

        $('select[name=plan_type_sub_cal'+l_cal_type+']').val($(this).attr('plan_code'));
        $('select[name=plan_type_sub_cal'+l_cal_type+']').trigger('change');

    /*
        obj_tr.find('input[name="plan_code[]"').val($(this).attr('plan_code'));
        obj_tr.find('input[name="plan_type[]"').val($(this).attr('plan_type'));
        obj_tr.find('input[name="plan_title[]"').val($(this).attr('plan_title'));

        chg_member_plan($(this).attr('age_from'), $(this).attr('age_to_limit'));
        calc_price(fg_auto_calc);
*/
        return false;
    });

    $("#end_date").datepicker("disable");
    numbering_row();
    get_plan_repre_list(g_company_type, g_member_no, g_trip_type);

    if(!fg_auto_calc) {
        $('a[name=btnCalc]').show();
    }
});

// 주민등록번호 별표 처리.
const asterisk_jumin_no = function(obj) {
	let jumin_no = obj.val();

    if (jumin_no.includes('******')) {
        return false;
    }

	if(chk_pattern(obj.val(), 'jumin')) {
		jumin_no = obj.val().toString().replace(/[^0-9]/g,'').replace(/([\d|*]{6})([\d|*]+)/, '$1$2');
		obj.val(jumin_no.replace(jumin_no, jumin_no.replace(/(-?)([1-8]{1})([0-9]{6})\b/gi, '$1$2******')));
	}

	obj.next().val(jumin_no);
}

const chk_jumin = function(obj) {
	let fg_check = false;
	let val_tgt_num = "", birthday, gender, arr_age, age_insu, age_std, price, gender_type;
	let start_date = $('input[name=start_date]').val();
    let obj_tr = obj.closest('tr');
	const auto_field = obj.attr('auto_field');
	const value_len = obj.val().length;
	
	fg_check = chk_row_no_error(obj, 'jumin');
	
	if(fg_check && value_len < 13 && value_len > 0) {
		obj.val(obj.val()+"1".repeat(13-value_len));
	}

	switch(auto_field) {
		case 'jumin_1':
			if(fg_check && value_len > 0) {

                if(checkValidDate(start_date)) {

                    gender = obj.val().substring(6,7);
                    birthday = ((gender=="1"||gender=="2"||gender=="5"||gender=="6")?"19":"20")+obj.val().substring(0,2)+'-'+obj.val().substring(2,4)+'-'+obj.val().substring(4,6);

                    if (g_company_type=="1" || g_company_type=="3") {
                        arr_age = getInsuAge(g_now_date, birthday);
                    } else {
                        arr_age = getInsuAge(start_date, birthday);
                    }

                    age_insu = arr_age[0];
                    age_std = arr_age[1];

                    if (age_insu >= 0 && age_std >= 0) {
                       
                        if (gender%2==0) {
                            //obj.closest('td').next().children('.td_last_obj').val("여성");
                            obj_tr.find('input[name="gender_text[]"]').val("여성");
                            obj_tr.find('input[name="gender[]"]').val("2");
                        } else {
                            //obj.closest('td').next().children('.td_last_obj').val("남성");
                            obj_tr.find('input[name="gender_text[]"]').val("남성");
                            obj_tr.find('input[name="gender[]"]').val("1");
                        }

                        //obj.closest('td').next().next().children('.td_last_obj').val(age_insu);
                        obj_tr.find('input[name="age[]"]').val(age_insu);
                        obj_tr.find('input[name="age[]"]').attr("age_std", age_std);

                        rtn_arr_val = get_member_plan(age_insu, age_std);
                        
                        if (rtn_arr_val[0] != "") {
    /*                        
                            obj.closest('td').next().next().next().children('.td_last_obj').val(rtn_arr_val[2]);
                            obj.closest('td').next().next().next().next().children('.td_last_obj').val(rtn_arr_val[0]);
                            obj.closest('td').next().next().next().next().next().children('.td_last_obj').val(rtn_arr_val[1]);
                            obj.closest('td').next().next().next().next().next().next().children('.td_last_obj').val(rtn_arr_val[3]);
    */                        
                            obj_tr.find('input[name="plan_code[]"]').val(rtn_arr_val[0]); // plan_code
                            obj_tr.find('input[name="plan_type[]"]').val(rtn_arr_val[1]); // plan_type
                            obj_tr.find('input[name="plan_title[]"]').val(rtn_arr_val[2]); // plan_title
                            obj_tr.find('input[name="cal_type[]"]').val(rtn_arr_val[3]); // cal_type
                            obj_tr.find('input[name="cal_type_text[]"]').val(rtn_arr_val[4]); // cal_type_text
                            obj_tr.find('a[name="btnSearchPlan"]').addClass("active-plan-chg");
                        } else {
                            obj_tr.find('input[name="gender_text[]"]').val(""); // gender_text
                            obj_tr.find('input[name="gender[]"]').val(""); // gender
                            obj_tr.find('input[name="age[]"]').val(""); // age
                            obj_tr.find('input[name="age[]"]').attr("age_std",""); // age_std
                            obj_tr.find('input[name="plan_code[]"]').val(""); // plan_code
                            obj_tr.find('input[name="plan_type[]"]').val(""); // plan_type
                            obj_tr.find('input[name="plan_title[]"]').val(""); // plan_title
                            obj_tr.find('input[name="cal_type[]"]').val(""); // cal_type
                            obj_tr.find('input[name="cal_type_text[]"]').val(""); // cal_type_text
                            obj_tr.find('a[name="btnSearchPlan"]').removeClass("active-plan-chg");
                            if(fg_auto_calc) {
                                obj_tr.find('input[name="price[]"]').val(""); // price
                            }
                        }
                    } else {
                        obj_tr.find('input[name="gender_text[]"]').val(""); // gender_text
                        obj_tr.find('input[name="gender[]"]').val(""); // gender
                        obj_tr.find('input[name="age[]"]').val(""); // age
                        obj_tr.find('input[name="age[]"]').attr("age_std",""); // age_std
                        obj_tr.find('input[name="plan_code[]"]').val(""); // plan_code
                        obj_tr.find('input[name="plan_type[]"]').val(""); // plan_type
                        obj_tr.find('input[name="plan_title[]"]').val(""); // plan_title
                        obj_tr.find('input[name="cal_type[]"]').val(""); // cal_type
                        obj_tr.find('input[name="cal_type_text[]"]').val(""); // cal_type_text
                        obj_tr.find('a[name="btnSearchPlan"]').removeClass("active-plan-chg");
                        if(fg_auto_calc) {
                            obj_tr.find('input[name="price[]"]').val(""); // price
                        }
                    }
                } else {
                    obj_tr.find('input[name="gender_text[]"]').val(""); // gender_text
                    obj_tr.find('input[name="gender[]"]').val(""); // gender
                    obj_tr.find('input[name="age[]"]').val(""); // age
                    obj_tr.find('input[name="age[]"]').attr("age_std",""); // age_std
                    obj_tr.find('input[name="plan_code[]"]').val(""); // plan_code
                    obj_tr.find('input[name="plan_type[]"]').val(""); // plan_type
                    obj_tr.find('input[name="plan_title[]"]').val(""); // plan_title
                    obj_tr.find('input[name="cal_type[]"]').val(""); // cal_type
                    obj_tr.find('input[name="cal_type_text[]"]').val(""); // cal_type_text
                    obj_tr.find('a[name="btnSearchPlan"]').removeClass("active-plan-chg");
                    if(fg_auto_calc) {
                        obj_tr.find('input[name="price[]"]').val(""); // price
                    }
                }
			} else {
/*                
				obj.closest('td').next().children('.td_last_obj').val("");
				obj.closest('td').next().next().children('.td_last_obj').val("");
				obj.closest('td').next().next().next().children('.td_last_obj').val("");
                obj.closest('td').next().next().next().next().children('.td_last_obj').val("");
                obj.closest('td').next().next().next().next().next().children('.td_last_obj').val("");
                obj.closest('td').next().next().next().next().next().next().children('.td_last_obj').val("");
*/
                
                obj_tr.find('input[name="gender_text[]"]').val(""); // gender_text
                obj_tr.find('input[name="gender[]"]').val(""); // gender
                obj_tr.find('input[name="age[]"]').val(""); // age
                obj_tr.find('input[name="age[]"]').attr("age_std",""); // age_std
                obj_tr.find('input[name="plan_code[]"]').val(""); // plan_code
                obj_tr.find('input[name="plan_type[]"]').val(""); // plan_type
                obj_tr.find('input[name="plan_title[]"]').val(""); // plan_title
                obj_tr.find('input[name="cal_type[]"]').val(""); // cal_type
                obj_tr.find('input[name="cal_type_text[]"]').val(""); // cal_type_text
                obj_tr.find('a[name="btnSearchPlan"]').removeClass("active-plan-chg");
                if(fg_auto_calc) {
                    obj_tr.find('input[name="price[]"]').val(""); // price
                }
			}

			break;
	}

	return fg_check;
}

// 행(tr)별 보험료 세팅.
const set_row_price = function(obj) {
    let obj_tr = obj.closest('tr');
    let gender, age_insu, plan_code;

    if(checkValidDate($('input[name=start_date]').val()) && checkValidDate($('input[name=end_date]').val()) && $('input[name=start_date]').val() <= $('input[name=end_date]').val()) {
        jumin = obj_tr.find('input[name="jumin[]"]').val();
        gender = obj_tr.find('input[name="gender[]"]').val(); // gender
        age_insu = obj_tr.find('input[name="age[]"]').val(); // age_insu
        plan_code = obj_tr.find('input[name="plan_code[]"]').val(); // plan_code

        //term_day = dateDiff($('input[name=start_date]').val(), $('input[name=end_date]').val(), false)+1;

        if (jumin) {
            obj_tr = obj.closest('tr');
            price = get_member_price(g_company_type, g_member_no, g_trip_type, plan_code, gender, age_insu, $('input[name=start_date]').val(), $('input[name=end_date]').val());

            if (price > 0) {
                //obj.closest('td').next().next().next().next().next().next().children('.td_last_obj').val(price.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                obj_tr.find('input[name="price[]"]').val(price.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")); // price
                change_tr_status(obj_tr, 0);
            } else {
                //obj.closest('td').next().next().next().next().next().next().children('.td_last_obj').val("");
                obj_tr.find('input[name="price[]"]').val(""); // price
                change_tr_status(obj_tr,price);
            }
        } else {
            obj_tr.find('input[name="price[]"]').val(""); // price
            change_tr_status(obj_tr, 0);
        }

        set_sum(true);
    } else {
        //obj.closest('td').next().next().next().next().next().next().children('.td_last_obj').val("");
        obj_tr.find('input[name="price[]"]').val(""); // price
    }
}

// 행(tr) 에러 여부 세팅 및 체크.
const chk_row_no_error = function(obj, chk_type) {

	const obj_td = obj.closest('td');
	const obj_tr = obj_td.closest('tr');
	const td_error_origin = obj_td.attr('td_error');
	const tr_error_cnt_origin = obj_tr.attr('tr_error_cnt');

	let tr_error_cnt = Number(tr_error_cnt_origin);
	let fg_no_error = true;

	if(obj.val()) {
		fg_no_error = chk_pattern(obj.val(), chk_type);
	}

	if(fg_no_error) {
		obj_td.attr('td_error','0');
	} else {
		obj_td.attr('td_error','1');
	}

	tr_error_cnt = tr_error_cnt-Number(td_error_origin)+Number(obj_td.attr('td_error'));
	obj_tr.attr('tr_error_cnt', tr_error_cnt);

	if(tr_error_cnt < 1 && tr_error_cnt_origin > 0) {
        change_tr_status(obj_tr, 0);
	} else if(tr_error_cnt > 0 && tr_error_cnt_origin < 1) {
        change_tr_status(obj_tr, -1);
	}

	return fg_no_error;
}

const change_tr_status = function(obj_tr, status) {
    let err_color = "#FE3B1F", err_msg = err_tooltip = "";
    
    if(status==0) {
        err_color = "WHITE";
    }
    
    switch(status) {
        case -11:
            err_msg = "[여행일 제한]";
            err_tooltip = "여행자보험 가입시 80세이상 고객님은 최대 1개월까지만 가입이 가능합니다.";
            break;
        case -12:
            err_msg = "[ 연령 제한 ]";
            err_tooltip = max_age+"세까지만 가입 가능합니다.";
            break;
    }
/*
    if(err_msg) {
        obj_tr.find('span[name=div_err]').show();
    } else {
        obj_tr.find('span[name=div_err]').hide();
    }
*/
    obj_tr.find('span[name=span_err]').html(err_msg);
    obj_tr.find('p[name=p_err]').html(err_tooltip);
    obj_tr.find('.td_last_obj').each(function(i){
			$(this).css("color",err_color);
	});
}

// no 출력.
const numbering_row = function() {
    let obj_cb;

	$('table[name=tbl_contract] tr').each(function(index, item) {
		if(index>0) {
            obj_cb = $(this).children('td:eq(0)').find('input[type=checkbox]');
            obj_cb.attr('id','chk_row'+index);
            obj_cb.next().attr('for','chk_row'+index);

            $(this).children('td:eq(1)').find('span[name=span_no]').html(index);
		}
	});
}

// 대표 플랜 가져오기.
const get_plan_repre_list = function(p_company_type, p_member_no, p_trip_type) {
    let plan_type_sub_title = plan_type_sub_list = cal_type_text = "";
    let age_from = age_to = 0;
    let idx_last;

	$.ajax({
		type : "POST",
		url : "/service/ajax/get_plan_list_ajax.php",
		data : { 'company_type' : p_company_type , 'member_no' : p_member_no, 'trip_type' : p_trip_type },
		dataType : 'json',
        async : false,
		success : function(data, status)
		{
//			console.log(data.RESULTCD);
//			console.log(JSON.stringify(data)); // <> parse()

            if(data.RESULTCD == "200") {
            
                cnt_cal_type = data.CNT_CAL_TYPE;
        
                $('.div_plan_type').hide();
                $('.cls_select_plan_sub').attr('age_from','999');
                $('.cls_select_plan_sub').attr('age_to','999');
                $('.cls_select_plan_sub').attr('age_to_limit','999');

                for(i=0;i<cnt_cal_type;i++) {
                    $('div[name=divPlantypeCal'+(i+1)+']').show();
                }

                plan_repre_title = "";
                str_plan_type = "";
                arr_plan_type_sub_cal = new Array();

                $.each(data.LIST_REPRE , function(idx, item){

                    str_plan_type += "<option value=\""+idx+"\" ";
                    if(idx==default_plan_type) {
                        str_plan_type += "selected='selected'";
                    }

                    str_plan_type += ">"+item.plan_title+"</option>";
                });

                $('select[name=plan_repre_type]').html(str_plan_type);

                $.each(data.LIST , function(idx, item){

                    $.each(item, function(idx_sub, item_sub){

                        if(!arr_plan_type_sub_cal[idx_sub] ) {
                            arr_plan_type_sub_cal[idx_sub] = new Array();
                        }

                        if(plan_type_sub_title=="") {
                            cal_type_text = arrCalTypeTitle[cnt_cal_type][idx-1];
                            plan_type_sub_title = cal_type_text + " ("+item_sub.plan_start_age+"~"+item_sub.plan_end_age+"세)";
                            age_from = item_sub.plan_start_age;
                            age_to = item_sub.plan_end_age;
                        }

                        plan_type_sub_options = cal_type_text + " " + item_sub.plan_code + " (";
                        plan_type_sub_list += "<option value=\""+item_sub.plan_code+"\" plan_type=\""+item_sub.plan_type+"\" plan_title=\""+item_sub.plan_title+"\" plan_sort=\""+item_sub.sort+"\" ";
                        if(item_sub.plan_type.includes(default_plan_type) && item_sub.sort==0) {
                            plan_type_sub_list += "selected='selected'";
                        }
                        //plan_type_sub_list += ">"+plan_type_sub_title+" "+item_sub.plan_title+"</option>";
                        plan_type_sub_list += ">"+plan_type_sub_options+item_sub.plan_title+")</option>";
                    });

                    $('span[name=title_plan_type_sub_cal'+idx+']').html(plan_type_sub_title);
                    $('select[name=plan_type_sub_cal'+idx+']').html(plan_type_sub_list);
                    $('select[name=plan_type_sub_cal'+idx+']').attr('age_from', age_from);
                    $('select[name=plan_type_sub_cal'+idx+']').attr('age_to', age_to);
                    $('select[name=plan_type_sub_cal'+idx+']').attr('age_to_limit', age_to);
                    $('select[name=plan_type_sub_cal'+idx+']').attr('cal_type', idx);
                    $('select[name=plan_type_sub_cal'+idx+']').attr('cal_type_text', cal_type_text);

                    idx_last = idx;
                    plan_type_sub_title = plan_type_sub_list = "";
                    max_age = age_to;
                });

                $('select[name=plan_type_sub_cal'+idx_last+']').attr('age_to_limit', '200');

            } else if(Number(data.RESULTCD) == 900) {
                alert(data.RESULTMSG);
                location.replace('/');
            } else {
                alert(data.RESULTMSG);
            }
		},
		error : function(err)
		{
			alert(err.responseText);
			return false;
		}
	});
}

// 해당 나이대 플랜 적용.
const chg_member_plan = function(age_from, age_to) {
    let tr_age, tr_age_std, obj_tr, rtn_arr_val;

    $('table[name=tbl_contract] tr').each(function(index, item) {
		if(index>0) {
            obj_tr = $(this);
            tr_age = obj_tr.find('input[name="age[]"]').val();
            tr_age_std = obj_tr.find('input[name="age[]"]').attr("age_std");

            if(tr_age) {
                tr_age = Number(tr_age);
/*
                if(age_to==14) {
                    age_to = 15;
                }
*/
                if(((tr_age >= age_from && tr_age <= age_to) || (age_to==14 && tr_age==15 && tr_age_std==14 && g_company_type!="3")) && !(age_from==15 && tr_age_std==14 && g_company_type!="3")) {

                    rtn_arr_val = get_member_plan(tr_age, tr_age_std);

                    if (rtn_arr_val[0] != "") {
                        obj_tr.find('input[name="plan_code[]"]').val(rtn_arr_val[0]); // plan_code
                        obj_tr.find('input[name="plan_type[]"]').val(rtn_arr_val[1]); // plan_type
                        obj_tr.find('input[name="plan_title[]"]').val(rtn_arr_val[2]); // plan_title
                        obj_tr.find('input[name="cal_type[]"]').val(rtn_arr_val[3]); // cal_type
                        obj_tr.find('input[name="cal_type_text[]"]').val(rtn_arr_val[4]); // cal_type_text
                        obj_tr.find('a[name="btnSearchPlan"]').addClass("active-plan-chg");
                    } else {
                        obj_tr.find('input[name="plan_code[]"]').val(""); // plan_code
                        obj_tr.find('input[name="plan_type[]"]').val(""); // plan_type
                        obj_tr.find('input[name="plan_title[]"]').val(""); // plan_title
                        obj_tr.find('input[name="cal_type[]"]').val(""); // cal_type
                        obj_tr.find('input[name="cal_type_text[]"]').val(""); // cal_type_text
                        obj_tr.find('a[name="btnSearchPlan"]').removeClass("active-plan-chg");
                    }
                }
            }
		}
	});
}

// 1명 플랜 적용.
const get_member_plan = function(age_insu, age_std) {   // 보험 나이, 만 나이.
    let rtn_plan_code = rtn_plan_type = rtn_plan_title = cal_type = cal_type_text = "";

    if(age_insu==15 && age_std==14 && g_company_type!="3") {
        rtn_plan_code = $('select[name=plan_type_sub_cal1]').val();
        rtn_plan_type = $('select[name=plan_type_sub_cal1] option:selected').attr('plan_type');
        //rtn_plan_title = $('select[name=plan_type_sub_cal1] option:selected').text();
        rtn_plan_title = $('select[name=plan_type_sub_cal1]').attr('cal_type_text') + " (" + $('select[name=plan_type_sub_cal1]').attr('age_from')+"~"+$('select[name=plan_type_sub_cal1]').attr('age_to')+"세) "+$('select[name=plan_type_sub_cal1] option:selected').attr('plan_title');
        cal_type = 1;
        cal_type_text = $('select[name=plan_type_sub_cal1]').attr('cal_type_text');
    } else {
        $('.cls_select_plan_sub').each(function(idx, item){
            if(Number($(this).attr('age_from')) <= age_insu && Number($(this).attr('age_to_limit')) >= age_insu) {
                rtn_plan_code = $(this).val();
                rtn_plan_type = $(this).find(':selected').attr('plan_type');
                //rtn_plan_title = $(this).find(':selected').text();
                rtn_plan_title = $(this).attr('cal_type_text') + " (" + $(this).attr('age_from')+"~"+$(this).attr('age_to')+"세) "+$(this).find(':selected').attr('plan_title');
                cal_type = $(this).attr('cal_type');
                cal_type_text = $(this).attr('cal_type_text');
                
                return false;
            }
        });
    }

    return [rtn_plan_code, rtn_plan_type, rtn_plan_title, cal_type, cal_type_text];
}

// 1명 보험료 가져오기.
const get_member_price = function(p_company_type, p_member_no, p_trip_type, p_plan_code, p_gender, p_age, p_start_date, p_end_date) {
    let rtn_val = -1;

    if(checkValidDate(p_start_date) && checkValidDate(p_end_date) && p_start_date <= p_end_date) {
        $.ajax({
            type : "POST",
            url : "/service/ajax/get_plan_price_ajax.php",
            data : { 'company_type' : p_company_type , 'member_no' : p_member_no, 'trip_type' : p_trip_type, 'plan_code' : p_plan_code, 'gender' : p_gender, 'age' : p_age, 'start_date' : p_start_date, 'end_date' : p_end_date },
            dataType : 'json',
            async : false,
            success : function(data, status)
            {
                if(data.RESULTCD == "200") {
                    rtn_val = data.Price;
                } else {
                    if(Number(data.RESULTCD) > 900) {
                        alert(data.RESULTMSG);
                    } else if(Number(data.RESULTCD) == 900) {
                        alert(data.RESULTMSG);
                        location.replace('/');
                    } else if(Number(data.RESULTCD) == 802) {
                        rtn_val = -11;
                    } else if(Number(data.RESULTCD) == 803) {
                        rtn_val = -12;
                    } else {
                        rtn_val = -1;
                    }
                }

                $('input[name=term_day]').val(data.Term_Day);
            },
            error : function(err)
            {
                alert(err.responseText);
                rtn_val = -1;
                return -1;
            }
        });
    }

    return rtn_val;
}

const calendar_calc_price = function() {
    if(checkValidDate($('#start_date').val()) && checkValidDate($('#end_date').val())) {
        if(!check_hour_max()){
            $('#end_date').val('');
        }
    }

    calc_price(fg_auto_calc);
}

// 전체 인원 가격 세팅.
const calc_price = function(fg_process) {
    let data, idx=0;
    let p_plan_code = p_age = p_gender = comma_txt = "";

    if(checkValidDate($('input[name=start_date]').val()) && checkValidDate($('input[name=end_date]').val())  && $('input[name=start_date]').val() <= $('input[name=end_date]').val() && fg_process) {

        $('table[name=tbl_contract] tr').each(function(index, item) {
            if(index>0) {
//                tr_error_cnt = $(this).attr('tr_error_cnt');

                //if($(this).find('input[name="jumin[]"]').val().trim() != "" && $(this).find('input[name="plan_code[]"]').val().trim() != "") {
                if($(this).find('input[name="plan_code[]"]').val().trim() != "") {

                    p_plan_code += comma_txt+$(this).find('input[name="plan_code[]"]').val();
                    p_age += comma_txt+$(this).find('input[name="age[]"]').val();
                    p_gender += comma_txt+$(this).find('input[name="gender[]"]').val();
                    
                    comma_txt =",";
                }
            }
        });

        if(comma_txt && fg_process) {

            //term_day = dateDiff($('input[name=start_date]').val(), $('input[name=end_date]').val(), false)+1;

//            data = $("#writeForm").serialize();
//            data += "&company_type="+g_company_type+"&member_no="+g_member_no+"&start_date="+$('input[name=start_date]').val()+"&end_date="+$('input[name=end_date]').val();
            $.ajax({
                type : "POST",
                url : "/service/ajax/get_plan_price_arr_ajax.php",
//                data : data,
                data : { 'company_type' : g_company_type , 'member_no' : g_member_no, 'trip_type' : g_trip_type, 'plan_code' : p_plan_code, 'gender' : p_gender, 'age' : p_age, 'start_date' : $('input[name=start_date]').val(), 'end_date' : $('input[name=end_date]').val() },
                dataType : 'json',
                async : false,
                success : function(data, status)
                {
                    if(data.RESULTCD == "200") {
                        
                        $('table[name=tbl_contract] tr').each(function(index, item) {
                            if(index>0) {
//                                tr_error_cnt = $(this).attr('tr_error_cnt');

                                //if($(this).find('input[name="jumin[]"]').val().trim() != "" && $(this).find('input[name="plan_code[]"]').val().trim() != "") {
                                if($(this).find('input[name="plan_code[]"]').val().trim() != "") {
                                    if(data.Price[idx] > 0) {
                                        $(this).find('input[name="price[]"]').val(data.Price[idx].toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
                                        change_tr_status($(this),0);
                                        //$(this).find('input[name="price[]"]').val(data.Price[idx]);
                                    } else {
                                        $(this).find('input[name="price[]"]').val("");
                                        change_tr_status($(this),data.Price[idx]);
                                    }

                                    idx++
                                }
                            }
                        });

                        $('input[name=term_day]').val(data.Term_Day);

                    } else if(Number(data.RESULTCD) == 900) {
                        alert(data.RESULTMSG);
                        location.replace('/');
                    } else {
                        alert(data.RESULTMSG);
                        return -1;
                    }
                },
                error : function(err)
                {
                    alert(err.responseText);
                    return -1;
                }
            });
        }
    } else {
        $('input[name="price[]"]').val("");
    }

    set_sum(fg_process);
}

// 총 금액, 총 인원 표시.
const set_sum = function(fg_process) {
    let sum = cnt = 0;

    if (fg_process) {
        $('table[name=tbl_contract] tr').each(function(index, item) {
            if(index>0) {
                if($(this).find('input[name="price[]"]').val()) {
                    cnt += 1;
                    sum += Number($(this).find('input[name="price[]"]').val().replace(/[^0-9]/g,''));
                }
            }
        });

        $('span[name=spanTotalCnt]').html(cnt.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
        $('span[name=spanTotalPrice]').html(sum.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));

        chk_submit_button(fg_process);
    }
}

// Submit 버튼 색상 변경.
const chk_submit_button = function(fg_process) {
    if(fg_process) {
        if(chk_all_set_field(false, true)) {
            $('a[name=btnApply]').addClass('red');
            $('a[name=btnApply]').removeClass('sbscr');
        } else {
            $('a[name=btnApply]').addClass('sbscr');
            $('a[name=btnApply]').removeClass('red');
        }
    }
}

// 전체 필드 체크.
const chk_all_set_field = function(fg_msg, fg_chk_price) {

    let rtn_val = true, fg_exist_data = false;;

    if(g_trip_type=="2" && $('select[name=nation_srch]').val()=="") {
        if(fg_msg) {
            alert("여행지역을 선택해 주십시오.    ");
        }

        return false;
    }

    if (!checkValidDate($('input[name=start_date]').val())) {
        if(fg_msg) {
            alert("여행 시작일을 입력해 주십시오.    ");
            $('input[name=start_date]').datepicker("show");
        }

        return false;
    }

    if (!checkValidDate($('input[name=end_date]').val())) {
        if(fg_msg) {
            alert("여행 종료일을 입력해 주십시오.    ");
            $('input[name="end_date"]').datepicker("show");
        }

        return false;
    }

    if($('input[name=repre_hp]').val()) {
        if(!chk_pattern($('input[name=repre_hp]').val(), 'hp')) {
            if(fg_msg) {
                alert("올바른 휴대폰 번호를 입력해 주십시오.    ");
                $('input[name="repre_hp"]').focus();
            }

            return false;
        }
<?php if($_GET["hp"]=="Y") { ?>
    } else {
        if(fg_msg) {
            alert("휴대폰 번호를 입력해 주십시오.    ");
            $('input[name="repre_hp"]').focus();
        }

        return false;
<?php } ?>
    }

    if($('input[name=repre_email]').val()) {
        if(!chk_pattern($('input[name=repre_email]').val(), 'email')) {
            if(fg_msg) {
                alert("올바른 이메일을 입력해 주십시오.    ");
                $('input[name="repre_email"]').focus();
            }

            return false;
        }
<?php if($_GET["hp"]=="Y") { ?>
    } else {
        if(fg_msg) {
            alert("이메일을 입력해 주십시오.    ");
            $('input[name="repre_email"]').focus();
        }

        return false;
<?php } ?>
    }

    $('table[name=tbl_contract] tr').each(function(index, item) {
		if(index>0) {
            if($(this).find('input[name="jumin[]"]').val() || $(this).find('input[name="name[]"]').val() || $(this).find('input[name="name_eng[]"]').val()) {

                if(!$(this).find('input[name="name[]"]').val()) {
                    if(fg_msg) {
                        alert("이름(국문)을 입력해 주십시오.    ");
                        $(this).find('input[name="name[]"]').focus();
                    }

                    rtn_val = false;
                    return false;
                }

                if(!$(this).find('input[name="jumin[]"]').val()) {
                    if(fg_msg) {
                        alert("주민등록번호를 입력해 주십시오.    ");
                        $(this).find('input[name="jumin[]"]').focus();
                    }

                    rtn_val = false;
                    return false;
                }

                if($(this).attr('tr_error_cnt') > 0) {
                    if(fg_msg) {
                        alert("주민등록번호가 유효하지 않습니다.    ");
                        $(this).find('input[name="jumin[]"]').focus();
                    }

                    rtn_val = false;
                    return false;
                }

                if(fg_chk_price) {
                    if(!$(this).find('input[name="price[]"]').val()) {
                        if(fg_msg) {
                            alert("유효하지 않은 Data가 존재합니다.    ");
                            $(this).find('input[name="jumin[]"]').focus();
                        }

                        rtn_val = false;
                        return false;
                    }
                }

                fg_exist_data = true;
            }
        }
	});

    if (rtn_val && !fg_exist_data) {
        if(fg_msg) {
            alert("가입자 정보를 입력해 주십시오.    ");
        }

        return false;
    }

    return rtn_val;
}

</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

$rs->free();
?>