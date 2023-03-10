<?
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_array.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_type_array.php";

$company_type = RequestUtil::getParam("company_type","");
$member_no = RequestUtil::getParam("member_no","");
$trip_type = RequestUtil::getParam("trip_type","");

if(empty($company_type) || empty($member_no) || empty($trip_type)) {
	exit;
}

$cnt_cal_type = count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type]);
/*
$arrPlanRepreTitle = array();
for($i=0;$i<count($__ARR_CONFIG_PLAN_REPRE[$company_type]['List'][$member_no][$trip_type]);$i++) {
    $arrPlanRepreTitle[$i+1] = $__ARR_CONFIG_PLAN_REPRE[$company_type]['List'][$member_no][$trip_type][$i+1]["plan_title"];
}
*/
?>

<div>
<?php
for($i=1;$i<=$cnt_cal_type;$i++) {
?>    
    <input class="radio" id="cal_type_<?=$i?>" name="rd_cal_type" value="<?=$i?>" type="radio">
<?
}
?>
    <div class="tab-menu-wrap">
<?php
for($i=1;$i<=$cnt_cal_type;$i++) {
?>    
        <label class="tab" id="cal_type_<?=$i?>-tab" for="cal_type_<?=$i?>"><?=$arrCalTypeTitle[$cnt_cal_type][$i-1]?>(<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$i][0]["plan_start_age"]?>~<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$i][0]["plan_end_age"]?>세)</label>
<?
}
?>
    </div>

    <div class="panels">
<?php
for($i=1;$i<=$cnt_cal_type;$i++) {
    $arrPlanTypePrice = array();
    $cnt_plan_type = count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$i]);
?>    

        <div class="panel plan-modal-second" id="cal_type_<?=$i?>-panel">
            <!-- Flan start -->
            <div class="plan-table-box">
                <div class="table-wrap">                    
                    <ul class="clearfix inb thead">
                        <li><span>보장명</span></li>
<?php
    for($j=0;$j<$cnt_plan_type;$j++) {
        for($k=1;$k<=34;$k++) {
            $arrPlanTypePrice[$k][$j] = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$i][$j]["type_".$k."_text"];
            $arrPlanTypePrice[$k][9] .= $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$i][$j]["type_".$k."_text"];
        }
?>
                        <li class="<?=($cnt_plan_type<2)?"plan-alone":""?>"><span><?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$i][$j]["plan_code"]." (".$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$i][$j]["plan_title"].")"?></span></li>
<?
    }
?>
                    </ul>
<?php
    for($k=1;$k<=count($arrPlanTypePrice);$k++) {

        if(!empty($arrPlanTypePrice[$k][9])) {
?>
                    <ul class="clearfix inb tbody-tr">
                        <li><span><?=$__ARR_CONFIG_PLAN_TYPE[$company_type][$member_no][$trip_type]["type_".$k]["title"]?></span></li>
<?php
            for($j=0;$j<$cnt_plan_type;$j++) {
?>
                        <li class="<?=($cnt_plan_type<2)?"plan-alone":""?>"><span><?=$arrPlanTypePrice[$k][$j]?></span></li>
<?php
               }
?>
                    </ul>
<?                
        }
    }
?>
                </div>
            </div>
        </div>
<?
}
?>
    </div>
</div>