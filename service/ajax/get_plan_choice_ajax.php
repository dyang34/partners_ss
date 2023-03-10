<?
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_array.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_type_array.php";

$company_type = RequestUtil::getParam("company_type","");
$member_no = RequestUtil::getParam("member_no","");
$trip_type = RequestUtil::getParam("trip_type","");
$cal_type = RequestUtil::getParam("cal_type","");
$plan_code = RequestUtil::getParam("plan_code","");

if(empty($company_type) || empty($member_no) || empty($trip_type) || empty($cal_type)) {
	exit;
}

$cnt_cal_type = count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type]);
$cnt_plan_type = count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type]);

$cal_type_text = $arrCalTypeTitle[$cnt_cal_type][$cal_type-1];
$age_from = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][0]["plan_start_age"];
$age_to = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][0]["plan_end_age"];

$cal_type_text_age = $cal_type_text." (".$age_from."~".$age_to."세)";
?>
<!-- title -->
<div class="title-box">
    <strong><?=$cal_type_text_age?></strong>
</div>
<!-- Flan Contents start -->
<div class="flansel-box-wrap">
    <div class="flansel-cont plan-modal-second">
        <!-- Flan start -->
        <div class="plan-table-box">
            <div class="table-wrap">
<?php
    if ($cnt_plan_type >= 3) {
?>
            <!-- <colgroup>
            </colgroup> -->
<?php
    }
?>
                <ul class="clearfix inb thead">
                    <li><span>보장명</span></li>
<?php
    $arrPlanTypePrice = array();
    for($j=0;$j<$cnt_plan_type;$j++) {
        for($k=1;$k<=34;$k++) {
            $arrPlanTypePrice[$k][$j] = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["type_".$k."_text"];
            $arrPlanTypePrice[$k][9] .= $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["type_".$k."_text"];
        }

        $plan_type_text = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["plan_title"];
        $plan_type_text_show = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["plan_code"]."<br/>[".$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["plan_title"]."]";
?>
                    <li class="<?=($cnt_plan_type<2)?"plan-alone":""?>">
                        <span>
                            <?=$plan_type_text_show?>
                            <a class="button <?=($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["plan_code"]==$plan_code)?"gray":"choice"?>" name="btnChoiceMemPlan" plan_code="<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["plan_code"]?>" plan_type="<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["plan_type"]?>" plan_title="<?=$cal_type_text_age." ".$plan_type_text?>">선택</a>
                        </span>
                    </li>
<?
    }
?>
                </ul>
            <?php
    for($k=1;$k<=count($arrPlanTypePrice);$k++) {

        if(!empty($arrPlanTypePrice[$k][9])) {
?>
                <ul class="clearfix inb tbody-tr">
                    <li>
                        <span><?=$__ARR_CONFIG_PLAN_TYPE[$company_type][$member_no][$trip_type]["type_".$k]["title"]?></span>
                    </li>
<?php
            for($j=0;$j<$cnt_plan_type;$j++) {
?>
                    <li class="<?=($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$trip_type][$cal_type][$j]["plan_code"]==$plan_code)?"active":""?> <?=($cnt_plan_type<2)?"plan-alone":""?>">
                        <span><?=$arrPlanTypePrice[$k][$j]?></span>
                    </li>
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
</div>
<!-- Flan Contents end -->