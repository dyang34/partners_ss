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

$__CONFIG_COMPANY_TYPE = RequestUtil::getParam("company_type","");
$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");
$member_no_org = $__CONFIG_MEMBER_NO;

if(!LoginManager::getUserLoginInfo("fg_not_common_plan")) {
	$__CONFIG_MEMBER_NO = get_default_member_no($__CONFIG_COMPANY_TYPE);
}

$hana_plan_no = RequestUtil::getParam("hana_plan_no", "");

if(empty($__CONFIG_COMPANY_TYPE) || empty($hana_plan_no)) {
    JsUtil::alert("잘못된 접근입니다.    ");
    exit;
}

$row = HanaPlanMgr::getInstance()->getByKey($hana_plan_no);
$trip_type = $row["trip_type"];
$CONFIG_PLAN_FILE_TRIP_TYPE = $trip_type;

require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_array.php";
require_once $_SERVER['DOCUMENT_ROOT']."/include/get_plan_type_array.php";

$wq = new WhereQuery(true, true);
$wq->addAndString("m.member_no","=",$member_no_org);
$wq->addAndString("hana_plan_no","=",$hana_plan_no);
$wq->addOrderBy("main_check","desc");
$wq->addOrderBy("m.no","asc");
$rs = HanaPlanMemberMgr::getInstance()->getListDetail($wq, $__CONFIG_MEMBER_NO, $trip_type);

$arrMember = array();
$arrCalType = array();
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row_mem = $rs->fetch_assoc();

        array_push($arrMember, $row_mem);
        
//        array_push($arrCalType, ["cal_type"=>$row_mem["cal_type"], "plan_type"=>$row_mem["plan_type"], "plan_code"=>$row_mem["plan_code"], "plan_title"=>$row_mem["plan_title"]]);
    }
}
/*
$arrCalType = array_unique($arrCalType, SORT_REGULAR);
$arrCalType = array_values($arrCalType);
*/
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
<?php
for($i=0;$i<count($arrMember);$i++) {

    $idx_plan_code = -1;

    $jumin1 = trim(decode_pass($arrMember[$i]["jumin_1"],$pass_key));
    $jumin2_1 = substr(trim(decode_pass($arrMember[$i]["jumin_2"],$pass_key)),0,1);

    if($jumin2_1=="1" || $jumin2_1=="2" || $jumin2_1=="5" || $jumin2_1=="6") {
        $jumin1 = "19".substr($jumin1,0,2)."-".substr($jumin1,2,2)."-".substr($jumin1,4,2);
    } else {
        $jumin1 = "20".substr($jumin1,0,2)."-".substr($jumin1,2,2)."-".substr($jumin1,4,2);
    }

    for($j=0;$j<count($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrMember[$i]["cal_type"]]);$j++) {
        if ($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrMember[$i]["cal_type"]][$j]["plan_code"]==$arrMember[$i]["plan_code"]) {
            $idx_plan_code = $j;
        }
    }

    if ($idx_plan_code < 0) {
        JsUtil::alert("잘못된 접근입니다.    ");
        exit;
    }
?>

            <!-- 1페이지 start -->
            <div class="section engInteg">
                <div class="title">
                    <h2>CERTIFICATE OF INSURANCE</h2>
                    <div class="btn-printer">
                        <a href="" value="인쇄하기" id="print" onclick="window.print()">
                            <i class="icon-printer"></i>
                        </a>
                    </div>
                </div>
                <div class="text-box-wrap">
                    ※This is to certify that We have effected insurance in the name of the insured as follows.<br>
                    ※The coverage is subject to the terms, conditions, limitations, exclusions and cancellation provisions of the policy.
                </div>
                <div class="table-wrap">
                    <table class="eng-table">
                        <colgroup>
                            <col width="22%">
                            <col width="28%">
                            <col width="22%">
                            <col width="*">
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>Type of Insurance (보험종목)</th>
                                <td>Overseas Traveler's Medical Expenses Insurance</td>
                                <th>Policy Number (증권번호)</th>
                                <td><?=!empty($row["plan_join_code"])?$row["plan_join_code"]:($__CONFIG_COMPANY_TYPE=="5"?$default_plan_join_code_samsung:$row["plan_join_code_replace"])?></td>
                            </tr>
                            <tr>
                                <th>Policy Period (보험기간)</th>
                                <td><?=$row["start_date"]." ~ ".$row["end_date"]?></td>
                                <th>Destination (여행지)</th>
                                <td><?=!empty($row["nation_txt_eng"])?$row["nation_txt_eng"]:$row["nation_txt"]?></td>
                            </tr>
                            <tr>
                                <th>Insured (피보험자)</th>
                                <td><?=$arrMember[$i]["name"]?> <?=!empty($arrMember[$i]["name_eng"])?"(".$arrMember[$i]["name_eng"].")":""?></td>
                                <th>Insured ID (생년월일)</th>
                                <td><?=$jumin1?></td>
                            </tr>
                            <tr>
                                <!-- 
                                <th>Reference Number (계약번호)</th>
                                <td>DG-20230425-0005</td>
                                 -->
                                <th>Total Premium (총납입보험료)</th>
                                <td>KRW <?=number_format($arrMember[$i]["plan_price"])?></td>
                                <th>Type (플랜명)</th>
                                <td><?=$arrMember[$i]["plan_code"]?></td>                                
<?php /*                                
                                <td><?=$arrMember[$i]["plan_code"]."_".$__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrMember[$i]['cal_type']][$idx_plan_code]['plan_start_age']."세~".$__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrMember[$i]['cal_type']][$idx_plan_code]['plan_end_age']."세"?></td>                                
*/?>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="terms-list-box">
                    <h3>
                        <i class="icon-sub"></i>
                        Terms & Conditions
                    </h3>

                    <table class="tem-table">
                        <colgroup>
                            <col width="*">
                            <col width="10%">
                            <col width="17%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>Coverage<br>(담보명)</th>
                                <th>Currency<br>(환종)</th>
                                <th>Insured Amount<br>(보상한도)</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
    for($k=1;$k<=34;$k++) {
        if(!empty($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrMember[$i]["cal_type"]][$idx_plan_code]["type_".$k])) {
?>
                            <tr>
                                <td><?=$__ARR_CONFIG_PLAN_TYPE[$__CONFIG_COMPANY_TYPE][$__CONFIG_MEMBER_NO][$trip_type]["type_".$k]["title"]?><?=!empty($__ARR_CONFIG_PLAN_TYPE[$__CONFIG_COMPANY_TYPE][$__CONFIG_MEMBER_NO][$trip_type]["type_".$k]["title_eng"])?" (".$__ARR_CONFIG_PLAN_TYPE[$__CONFIG_COMPANY_TYPE][$__CONFIG_MEMBER_NO][$trip_type]["type_".$k]["title_eng"].")":""?></td>
                                <td>KRW</td>
                                <td><?=number_format($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrMember[$i]["cal_type"]][$idx_plan_code]["type_".$k])?></td>
                            </tr>
<?php
        }
    }
?>
                        </tbody>
                    </table>

                    <div class="text-bottom-wrap">
                        <p>1. 'Disease' set out in the policy wordings is not subject to any exclusions, which includes a pandemic disease such as COVID-19.</p>
                        <p>2. This insurance covers the inusred's trip to anywhere in the world, but excluding travel ban countries and Level 3 Travel warning areas designated by Korea Ministry of Foreign Affairs.</p>
                    </div>
                </div>

                <div class="seal-flex">
                    <ul>
                        <li>
                            <p>
                                <!-- 삼성화재
                                <span>청약</span> 부서 -->
                                <span>비아이에스</span> 대리점
                            </p>
                            <p>
                                Tel : <span>1800-9010</span>
                            </p>
                            <p>E-mail : <span>toursafe@bis.co.kr</span></p>
                        </li>
                        <li>
                            <strong>
                                <span>비아이에스</span> 대리점 (인)
                            </strong>
                            <div><img src="/img/service/bis-seal.png?e" alt="비아이에스 인감"></div>
                        </li>
                    </ul>
                </div>

                <div class="foot-logo-wrap">
                    <img src="/img/service/logo-bott-<?=$__CONFIG_COMPANY_TYPE?>.png" class="logo">
                </div>
            </div>
            <!-- 1페이지 end -->
<?php
}

@$rs->free();
?>
        </div>
    </div>
</body>
</html>