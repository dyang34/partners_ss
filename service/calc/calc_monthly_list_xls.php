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

Header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=월별 집계표_".date('Ymd').".xls");
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
        <th style="color:white;background-color:#000081;">연/월</th>
        <th style="color:white;background-color:#000081;">청약 완료(금액)</th>
        <th style="color:white;background-color:#000081;">청약 완료(건수)</th>
        <th style="color:white;background-color:#000081;">청약 취소(금액)</th>
        <th style="color:white;background-color:#000081;">청약 취소(건수)</th>
        <th style="color:white;background-color:#000081;">수수료율</th>
        <th style="color:white;background-color:#000081;">커미션( ( 완료 - 취소 ) X 수수료율 )</th>
    </tr>
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
    <tr>
        <td style="mso-number-format:'\@';"><?=$row["job_date"]?></td>
        <td class="right"><?=number_format($row["inq_change_price"])?></td>
        <td class="right"><?=number_format($row["inq_cnt"])?></td>
        <td class="right"><?=number_format($row["cancel_change_price"])?></td>
        <td class="right"><?=number_format($row["cancel_cnt"])?></td>
        <td class="right"><?=$row["com_percent"]?></td>
        <td class="right"><?=number_format($row["commition"])?></td>
    </tr>
<?php
    }
}
?>
</table>
<?php
@ $rs->free();
?>