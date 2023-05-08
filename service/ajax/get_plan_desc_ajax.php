<?
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

$CONFIG_PLAN_FILE_TRIP_TYPE = RequestUtil::getParam("trip_type","");

require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_array.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_type_array.php";

$company_type = RequestUtil::getParam("company_type","");
$member_no = RequestUtil::getParam("member_no","");

if(empty($company_type) || empty($member_no) || empty($CONFIG_PLAN_FILE_TRIP_TYPE)) {
	exit;
}

$cnt_cal_type = count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE]);
/*
$arrPlanRepreTitle = array();
for($i=0;$i<count($__ARR_CONFIG_PLAN_REPRE[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE]);$i++) {
    $arrPlanRepreTitle[$i+1] = $__ARR_CONFIG_PLAN_REPRE[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i+1]["plan_title"];
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
        <label class="tab" id="cal_type_<?=$i?>-tab" for="cal_type_<?=$i?>"><?=$arrCalTypeTitle[$cnt_cal_type][$i-1]?>(<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][0]["plan_start_age"]?>~<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][0]["plan_end_age"]?>세)</label>
<?
}
?>
    </div>

    <div class="panels">
<?php
for($i=1;$i<=$cnt_cal_type;$i++) {
    $arrPlanTypePrice = array();
    $cnt_plan_type = count($__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i]);
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
            $arrPlanTypePrice[$k][$j] = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["type_".$k."_text"];
            $arrPlanTypePrice[$k][19] .= $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["type_".$k."_text"]; // 해당 담보에 대해 모든 플랜이 보장하지 않는지 여부 체크.
        }

        $plan_type_text = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["plan_title"];
        $plan_type_text_show = $__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["plan_code"]."<br/>[".$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["plan_title"]."]";
?>
<?/*
                <li class="<?=($cnt_plan_type<2)?"plan-alone":""?>">
                            <span>
                            
                                <?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["plan_code"].
                        " (".$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["plan_title"].")"?>
                            
                            </span>
                        </li>
*/?>
                        <li class="<?=($cnt_plan_type<2)?"plan-alone":""?>">
                            <span>
                                <?=$plan_type_text_show?>
                                <a class="button choice" name="btnChoicePlan" cal_type="<?=$i?>" plan_code="<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["plan_code"]?>" plan_type="<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["plan_type"]?>" plan_title="<?=$cal_type_text_age." ".$plan_type_text?>">선택</a>
                            </span>
                        </li>
<?
    }
?>
                    </ul>
<?php
    for($k=1;$k<=count($arrPlanTypePrice);$k++) {
        if(!empty($arrPlanTypePrice[$k][19])) {
?>
                    <ul class="clearfix inb tbody-tr">
                        <li>
                            <span><?=$__ARR_CONFIG_PLAN_TYPE[$company_type][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE]["type_".$k]["title"]?>
                                <div class="btn-mbal btn_plan_type_info_desc" c_title="<?=$__ARR_CONFIG_PLAN_TYPE[$company_type][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE]["type_".$k]["title"]?>" c_content="<?=$__ARR_CONFIG_PLAN_TYPE[$company_type][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE]["type_".$k]["content"]?>">
                                    <i class="icon-tooltip">툴팁</i>
                                </div>
                            </span>
                        </li>
<?php
            for($j=0;$j<$cnt_plan_type;$j++) {
?>
                        <li class="cls_li_plan_type_desc <?=($cnt_plan_type<2)?"plan-alone":""?>" cal_type="<?=$i?>" plan_code="<?=$__ARR_CONFIG_PLAN[$company_type]['List'][$member_no][$CONFIG_PLAN_FILE_TRIP_TYPE][$i][$j]["plan_code"]?>"><span><?=$arrPlanTypePrice[$k][$j]?></span></li>
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

<div id="question">
    <div id="mask_plan_type_desc" class="mask_background"></div>
	<div class="modal-bg">
		<div class="modal-conts" style="z-index:20;">
			<h2 id="modal_paln_type_desc_title"></h2>
			<p id="modal_paln_type_desc_content"></p>
<?/*            
			<a href="#" class="close mdclose">닫기</a>
*/?>            
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $(document).on('click', '.btn_plan_type_info_desc', function() {   // mouseover
        $('#modal_paln_type_desc_title').html($(this).attr("c_title"));
        $('#modal_paln_type_desc_content').html($(this).attr("c_content"));
        $("#question").removeAttr("class").addClass("one");

        $('#mask_plan_type_desc').css({'width':$('#question').width(),'height':$('#question').height()});  
        $('#mask_plan_type_desc').show();
    });
    
    $(document).on('click', '#mask_plan_type_desc', function() {
        $("#question").addClass("out");
        $('#mask_plan_type_desc').hide();
    });
});
</script>