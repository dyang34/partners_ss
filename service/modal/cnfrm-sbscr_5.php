<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMemberMgr.php";

require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_array.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_type_array.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$__CONFIG_COMPANY_TYPE = RequestUtil::getParam("company_type","");
$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");
$member_no_org = $__CONFIG_MEMBER_NO;

if(!LoginManager::getUserLoginInfo("fg_not_common_plan")) {
	$__CONFIG_MEMBER_NO = get_default_member_no(LoginManager::getUserLoginInfo("company_type"));
}

$hana_plan_no = RequestUtil::getParam("hana_plan_no", "");

if(empty($__CONFIG_COMPANY_TYPE) || empty($hana_plan_no)) {
    JsUtil::alert("잘못된 접근입니다.    ");
    exit;
}

$row = HanaPlanMgr::getInstance()->getByKey($hana_plan_no);
$trip_type = $row["trip_type"];

$wq = new WhereQuery(true, true);
$wq->addAndString("m.member_no","=",$member_no_org);
$wq->addAndString("hana_plan_no","=",$hana_plan_no);
$rs = HanaPlanMemberMgr::getInstance()->getListDetail($wq);

$arrMember = array();
$arrCalType = array();
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row_mem = $rs->fetch_assoc();

        array_push($arrMember, $row_mem);
        //array_push($arrCalType, $row_mem["cal_type"]);
        array_push($arrCalType, ["cal_type"=>$row_mem["cal_type"], "plan_type"=>$row_mem["plan_type"], "plan_code"=>$row_mem["plan_code"], "plan_title"=>$row_mem["plan_title"]]);
    }
}

$arrCalType = array_unique($arrCalType, SORT_REGULAR);
$arrCalType = array_values($arrCalType);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,width=device-width">

    <title>가입 확인서</title>

    <link rel="shortcut icon" href="/img/common/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" href="/img/common/favicon.png"/>

    <link rel="stylesheet" type="text/css" href="/css/style.css?v=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="/css/basic.css?v=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="/css/button.css?v=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="/css/modal.css?v=<?=time()?>">

    <script type="text/javascript" src="/js/jquery-3.4.1.min.js"></script>  
</head>
<body>
    <div class="cnfrm-wrap">
        <div class="printer-wrap">
            <!-- 1페이지 start -->
            <div class="section">
                <div class="title">
                    <h2>가입 확인서</h2>
                    <div class="btn-printer">
                        <a href="" value="인쇄하기" id="print" onclick="window.print()">
                            <i class="icon-printer"></i>
                        </a>
                    </div>
                </div>

                <!-- 기본 정보 start -->
                <h4 class="sub-title">기본 정보</h4>
                <table class="table-modal">
                    <colgroup>
                        <col width="18%">
                        <col width="32%">
                        <col width="18%">
                        <col width="*">
                    </colgroup>
                    <tbody>
                        <tr>
                            <th>여행사</th>
                            <td class="left"><?=LoginManager::getUserLoginInfo("com_name")?></td>
                            
                            <th>보험명</th>
                            <td class="left"><?=$arrInsuranceCompany[$row["company_type"]]." ".$arrTripType[$trip_type]."보험"?></td>
                        </tr>
                        <tr>
                            <th>대표피보험자</th>
                            <td class="left"><?=$row["join_name"]?></td>
                            
                            <th>여행기간</th>
                            <td class="left"><?=$row["start_date"]?> 〜 <?=$row["end_date"]?> </td>
                        </tr>
                        <tr>
                            <th>청약일자</th>
                            <td class="left"><?=date('Y-m-d', $row["regdate"])?></td>
                            
                            <th>가입인원</th>
                            <td class="left"><?=number_format($rs->num_rows)?>명</td>
                        </tr>
                    </tbody>
                </table>

                <h4 class="sub-title">피보험자별  상세</h4>
                <table class="table-modal">
                    <colgroup>
                        <col width="7%">
                        <col width="15%">
                        <col width="15%">
                        <col width="12%">
                        <col width="15%">
                        <col width="18%">
                        <col width="*">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>성명</th>
                            <th>생년월일</th>
                            <th>성별</th>
                            <th>플랜명</th>
                            <th>플랜코드</th>
                            <th>보험료</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
if (count($arrMember) > 0) {
    for($i=0; $i<count($arrMember); $i++) {

        $jumin1 = decode_pass($arrMember[$i]["jumin_1"],$pass_key);
        $jumin2_1 = substr(decode_pass($arrMember[$i]["jumin_2"],$pass_key),0,1);

        if($jumin2_1=="1" || $jumin2_1=="2" || $jumin2_1=="5" || $jumin2_1=="6") {
            $jumin1 = "19".substr($jumin1,0,2)."-".substr($jumin1,2,2)."-".substr($jumin1,4,2);
        } else {
            $jumin1 = "20".substr($jumin1,0,2)."-".substr($jumin1,2,2)."-".substr($jumin1,4,2);
        }
?>                    
                        <tr>
                            <td><?=$i+1?></td>
                            <td><?=$arrMember[$i]["name"]?></td>
                            <td><?=$jumin1?></td>
                            <td><?=($arrMember[$i]["sex"]==1)?"남성":"여성"?></td>
                            <td><?=$arrMember[$i]["plan_title"]?></td>
                            <td><?=$arrMember[$i]["plan_code"]?></td>
                            <td><?=number_format($arrMember[$i]["plan_price"])?>원</td>
                        </tr>
                        <?
    }
}
?>
                    </tbody>
                </table>
            </div>


<?
/*
    $cnt_cal_type = count($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]["List"][$__CONFIG_MEMBER_NO][$trip_type]);
    for($i=0; $i<count($arrCalType); $i++) {
        $arrPlanTypePrice = array();
        $cnt_plan_type = count($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]]);
?>
            <!-- 2페이지 start -->
            <div class="section">
                <!-- 담보내용 start -->
                <h4 class="sub-title">담보내용 <?=$arrCalTypeTitle[$cnt_cal_type][$arrCalType[$i]-1]?></h4>
                <table class="table-modal">
                <colgroup>
                    <col width="*">
<?php                    
        for($j=0;$j<$cnt_plan_type;$j++) {                    
?>                        
                    <col width="<?=11+(11*(4-$cnt_plan_type))?>%">
<?php
        }
?>                                        
                </colgroup>
                    <thead>
                        <tr>
                            <th>보장명</th>
<?php
        for($j=0;$j<$cnt_plan_type;$j++) {
            for($k=1;$k<=34;$k++) {
                $arrPlanTypePrice[$k][$j] = $__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]][$j]["type_".$k."_text"];
                $arrPlanTypePrice[$k][9] .= $__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]][$j]["type_".$k."_text"];
            }
?>    
                        <th><?=$__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]][$j]["plan_title"]?></th>
<?
        }
?>
                        </tr>
                    </thead>
                    <tbody>
<?php
        for($k=1;$k<=count($arrPlanTypePrice);$k++) {

            if(!empty($arrPlanTypePrice[$k][9])) {
?>
                    <tr>
                        <th><?=$__ARR_CONFIG_PLAN_TYPE[$__CONFIG_COMPANY_TYPE][$__CONFIG_MEMBER_NO][$trip_type]["type_".$k]["title"]?></th>
<?php
                for($j=0;$j<$cnt_plan_type;$j++) {
    ?>
                            <td><?=$arrPlanTypePrice[$k][$j]?></td>
<?php
                }
?>
                    </tr>
<?                
            }
        }
?>
                    </tbody>
                </table>
            </div>
<?
    }
*/    


    $cnt_cal_type = count($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]["List"][$__CONFIG_MEMBER_NO][$trip_type]);
    for($i=0; $i<count($arrCalType); $i++) {
        $arrPlanTypePrice = array();
        $cnt_plan_type = count($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]]);
?>
        <!-- 2페이지 start -->
        <div class="section">
            <!-- 담보내용 start -->
            <h4 class="sub-title">담보내용 <?=$arrCalTypeTitle[$cnt_cal_type][$arrCalType[$i]["cal_type"]-1]?> <?=$arrCalType[$i]["plan_title"]?>(<?=$arrCalType[$i]["plan_code"]?>)</h4>
            <table class="table-modal">
            <colgroup>
                <col width="70%">
                <col width="*">
            </colgroup>
                <thead>
                    <tr>
                        <th>보장명</th>
                        <th>보장금액</th>
                    </tr>
                </thead>
                <tbody>
<?php
        for($j=0;$j<$cnt_plan_type;$j++) {
            if ($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][$j]["plan_code"]==$arrCalType[$i]["plan_code"]) {
                for($k=1;$k<=34;$k++) {
                    if(!empty($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][$j]["type_".$k."_text"])) {
?>
                    <tr>
                        <th><?=$__ARR_CONFIG_PLAN_TYPE[$__CONFIG_COMPANY_TYPE][$__CONFIG_MEMBER_NO][$trip_type]["type_".$k]["title"]?></th>
                        <td><?=$__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][$j]["type_".$k."_text"]?></td>
                    </tr>
<?php
                    }
                }

                break;
            }
        }
?>
                </tbody>
            </table>
        </div>
<?
    }
?>
        </div>
    </div>
</body>
</html>