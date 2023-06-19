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

if (LoginManager::getUserLoginInfo("mem_type")=="1") {
    JsUtil::alertReplace("접근 권한이 없습니다.    ","/");
}

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");

$_reg_date_from = RequestUtil::getParam("_reg_date_from", date("Y-m-d"));
$_reg_date_to = RequestUtil::getParam("_reg_date_to", date("Y-m-d"));
$_name = RequestUtil::getParam("_name", "");
$_plan_list_state = RequestUtil::getParam("_plan_list_state", "");
$_manager_info = RequestUtil::getParam("_manager_info", "");

if(!empty($_manager_info)) {
    $arr_manager_info = explode('|', $_manager_info);

    $_manager_name = $arr_manager_info[0];
    $_manager_idx = $arr_manager_info[1];
} else {
    $_manager_name = "";
    $_manager_idx = -1;
}

$_order_by = RequestUtil::getParam("_order_by", "a.no");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$wq = new WhereQuery(true, true);
$wq->addAndString("a.member_no","=",$__CONFIG_MEMBER_NO);
$wq->addAndStringBind("regdate", ">=", $_reg_date_from, "unix_timestamp('?')");
$wq->addAndStringBind("regdate", "<", $_reg_date_to, "unix_timestamp(date_add('?', interval 1 day))");
if($_plan_list_state=="1") {
    $wq->addAndIn("plan_list_state",array(1,6));
} else {
    $wq->addAndString("plan_list_state","=",$_plan_list_state);
}

if($_manager_idx=="0") {
    $wq->addAndString2("ifnull(manager_idx, 0)","=","0");
} else {
    $wq->addAndString("manager_name","=",$_manager_name);
}

if(!empty($_name)) {
    $wq->addAnd2("a.no in (select distinct hana_plan_no from hana_plan_member where member_no = '".$__CONFIG_MEMBER_NO."' and name = '".$_name."')");
}

$wq->addOrderBy($_order_by, $_order_by_asc);
$wq->addOrderBy("a.no", "desc");

$rs = HanaPlanMgr::getInstance()->getListRepre($wq);

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=신청내역_".date('Ymd').".xls");
Header("Content-Description: PHP5 Generated Data");
Header("Pragma: no-cache");
Header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=utf-8\">");
?>
<style>
td{font-size:11px;text-align:center;}
th{font-size:11px;text-align:center;color:white;background-color:#000081;}
</style>
<table cellpadding="3" cellspacing="0" border="1" bordercolor='#bdbebd' style='border-collapse: collapse'>
    <tr>
        <th style="color:white;background-color:#000081;">보험사</th>
        <th style="color:white;background-color:#000081;">담당자</th>
        <th style="color:white;background-color:#000081;">보험상품</th>
        <th style="color:white;background-color:#000081;">청약일</th>
        <th style="color:white;background-color:#000081;">진행상태</th>
<?php
    if(LoginManager::getUserLoginInfo("calc_period_type")=="9") {
?>
        <th style="color:white;background-color:#000081;">입금일</th>
<?php        
    }
?>

        <th style="color:white;background-color:#000081;">여행시작일</th>
        <th style="color:white;background-color:#000081;">여행종료일</th>
        <th style="color:white;background-color:#000081;">플랜코드</th>
        
        <th style="color:white;background-color:#000081;">대표 피보험자</th>
        <th style="color:white;background-color:#000081;">주민등록번호</th>
        <th style="color:white;background-color:#000081;">보험료</th>
        <th style="color:white;background-color:#000081;">증권번호</th>

        <th style="color:white;background-color:#000081;">여행지</th>
        <th style="color:white;background-color:#000081;">추가정보1</th>
        <th style="color:white;background-color:#000081;">추가정보2</th>
    </tr>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();

        //$jumin = (double)decode_pass($row["jumin_1"],$pass_key).(double)decode_pass($row["jumin_2"],$pass_key);
        //$jumin = preg_replace("/([0-9]{6})([0-9])([0-9]+)/", "$1-$2******", $jumin);

        $jumin = trim(decode_pass($row["jumin_1"],$pass_key))."-".substr(trim(decode_pass($row["jumin_2"],$pass_key)),0,1)."******";
?>
                        <tr>
                            <td><?=$arrInsuranceCompany[$row["company_type"]]?></td><!-- 보험사 -->
                            <td><?=$row["manager_name"]?></td><!-- 담당자 -->
                            <td><?=$arrTripType[$row["trip_type"]]?></td><!-- 보험상품 -->
                            <td><?=date('Y-m-d', $row["regdate"])?></td><!-- 청약일 -->
                            <td><?=$arrPlanStateText[$row["plan_list_state"]]?></td><!-- 진행상태 -->
<?php
    if(LoginManager::getUserLoginInfo("calc_period_type")=="9") {
?>
                            <td><?=$row["deposit_date"]?></td><!-- 입금일 -->
<?php        
    }
?>
                            <td><?=$row["start_date"]?></td><!-- 여행시작일 -->
                            <td><?=$row["end_date"]?></td><!-- 여행종료일 -->
                            <td><?=$row["plan_code"]." (".$row["plan_title"].")"?></td><!-- 플랜코드 -->
                            <td><?=$row["name"]?></td><!-- 대표 피보험자 -->
                            <td><?=$jumin?></td><!-- 주민등록번호 -->
                            <td><?=number_format($row["price_sum"])?></td><!-- 보험료 -->
                            <td style="mso-number-format:'\@';"><?=!empty($default_plan_join_code_fix[$row["company_type"]][$row["trip_type"]])?$default_plan_join_code_fix[$row["company_type"]][$row["trip_type"]]:(!empty($row["plan_join_code"])?$row["plan_join_code"]:$row["plan_join_code_replace"])?></td><!-- 증권번호 -->
                            <td class="tvl-dest"><?=$row["trip_type"]=="1"?"국내일원":$row["nation_txt"]?></td><!-- 여행지 -->
                            <td><?=$row["add_info1"]?></td><!-- 추가정보1 -->
                            <td><?=$row["add_info2"]?></td><!-- 추가정보2 -->
                        </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free();
?>