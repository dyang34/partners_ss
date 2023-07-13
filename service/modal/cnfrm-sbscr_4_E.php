<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/contract/HanaPlanMemberMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$__CONFIG_COMPANY_TYPE = RequestUtil::getParam("company_type","");
$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");
$member_no_org = $__CONFIG_MEMBER_NO;
/*
if(!LoginManager::getUserLoginInfo("fg_not_common_plan")) {
	$__CONFIG_MEMBER_NO = get_default_member_no($__CONFIG_COMPANY_TYPE);
}
*/
$hana_plan_no = RequestUtil::getParam("hana_plan_no", "");

if(empty($__CONFIG_COMPANY_TYPE) || empty($hana_plan_no)) {
    JsUtil::alert("잘못된 접근입니다.    ");
    exit;
}

$row = HanaPlanMgr::getInstance()->getByKey($hana_plan_no);
$trip_type = $row["trip_type"];
$CONFIG_PLAN_FILE_TRIP_TYPE = $trip_type;

$row_company = ToursafeMembersMgr::getInstance()->getByKey($row["member_no"]);

$arr_company_type = LoginManager::getUserLoginInfo('company_type_list')[$trip_type];
for($i=0;$i<count($arr_company_type);$i++) {
    if($arr_company_type[$i]['company_type']==$__CONFIG_COMPANY_TYPE) {
        if(empty($arr_company_type[$i]['plan_member_no'])) {
            $__CONFIG_MEMBER_NO = get_default_member_no($__CONFIG_COMPANY_TYPE);
        } else {
            $__CONFIG_MEMBER_NO = $arr_company_type[$i]['plan_member_no'];
        }
        break;
    }
}

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
        //array_push($arrCalType, $row_mem["cal_type"]);
        array_push($arrCalType, ["cal_type"=>$row_mem["cal_type"], "plan_type"=>$row_mem["plan_type"], "plan_code"=>$row_mem["plan_code"], "plan_title"=>$row_mem["plan_title"]]);
    }
}

$arrCalType = array_unique($arrCalType, SORT_REGULAR);
$arrCalType = array_values($arrCalType);

foreach ($arrCalType as $key => $value) {
	$sort[$key] = $value['cal_type'];
}

array_multisort($sort, SORT_ASC, $arrCalType);

$total_price = 0;
for($i=0;$i<count($arrMember);$i++) {
    $total_price += $arrMember[$i]["plan_price"];
}

$arrPlanType = $__ARR_CONFIG_PLAN_TYPE[$__CONFIG_COMPANY_TYPE][$__CONFIG_MEMBER_NO][$trip_type];

$arrAgeText = array();
for($k=1;$k<=34;$k++) {
    if(!empty($arrPlanType["type_".$k])) {

        $arrPlanType["type_".$k]["data"] = array();

        $arrPlanTypeData = array();
        for($i=0;$i<count($arrCalType);$i++) {

            //$arrPlanTypeData["age_text"] = $__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][0]["plan_start_age"]."~".$__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][0]["plan_end_age"]."세";

            if($k==1) {
                array_push($arrAgeText, $__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][0]["plan_start_age"]."~".$__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][0]["plan_end_age"]);
            }

            for($j=0;$j<count($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]]);$j++) {
                if($arrCalType[$i]["plan_code"]==$__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][$j]["plan_code"]) {
                    array_push($arrPlanType["type_".$k]["data"], $arrPlanTypeData["price_text"] = $__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][$j]["type_".$k."_text"]);
                }
            }
        }
    }
}
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
            <div class="section directdb">
                <div class="headWrap">
                    <h1><img src="/img/service/logo-directdb.png" alt="DB손해보험"></h1>
                    <strong>
                        <span>CERTIFICATE OF INSURANCE</span>
                    </strong>
                    <div class="btn-printer">
                        <a href="" value="인쇄하기" id="print" onclick="window.print()">
                            <i class="icon-printer"></i>
                        </a>
                    </div>
                </div>

                <div class="box-conts-wrap">
                    <h2>Basic Information <!-- 기본정보 -->
                        <div>Securities number<!-- 증권번호 --> : <span><?=!empty($default_plan_join_code_fix[$__CONFIG_COMPANY_TYPE][$trip_type])?$default_plan_join_code_fix[$__CONFIG_COMPANY_TYPE][$trip_type]:(!empty($row["plan_join_code"])?$row["plan_join_code"]:$row["plan_join_code_replace"])?></span></div>
                    </h2>

                    <div class="table-box">
                        <table class="table-db">
                            <colgroup>
                                <col width="20%">
                                <col width="30%">
                                <col width="20%">
                                <col width="*">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>Payer</th><!-- 결제자 -->
                                    <td><?=$arrMember[0]["name_eng"]?></td>
                                    <th>Collective contractor</th><!-- 단체계약자 -->
                                    <td>UDIRECT</td>
                                </tr>

                                <tr>
                                    <th>Number of insured persons</th><!-- 피보험자수 -->
                                    <td><?=number_format(count($arrMember))?></td>
                                    <th>Travel destinations</th><!-- 여행지 -->
                                    <td><?=($row["trip_type"]==1)?"국내일원":$row["nation_txt_eng"]?></td>
                                </tr>
                                <tr>
                                    <th>Insurance period</th><!-- 보험기간 -->
                                    <td>
                                        <span><?=$row["start_date"]." ".$row["start_hour"].":00 ~"?></span>
                                        <span><?=$row["end_date"]." ".$row["end_hour"].":00"?></span>
                                    </td>
                                    <th>Purpose of travel</th><!-- 여행목적 -->
                                    <td><?=$arrTripPurposeEng[$row["trip_purpose"]]?></td>
                                </tr>
                                <tr>
                                    <th>Premiums paid</th><!-- 납입보험료 -->
                                    <td colspan="3"><?=number_format($total_price)?> WON</td>
<?/*                                    
                                    <th>보험료 납입일자</th>
                                    <td>2022-12-15</td>

                                    <th>보험 신청일</th>
                                    <td><?=date('Y-m-d', $row["regdate"])?></td>
*/?>                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="box-conts-wrap">
                    <h2>Insurance policy holder (insured person) information</h2> <!-- 보험가입자(피보험자) 정보 -->

                    <div class="table-box">
                        <table class="table-db">
                            <colgroup>
                                <col width="20%">
                                <col width="30%">
                                <col width="20%">
                                <col width="*">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><?=$arrMember[0]["name_eng"]?></td>
                                    <th>Insurance type</th> <!-- 보험종류 -->
                                    <td><?=$arrTripTypeEng[$trip_type]?></td>
                                </tr>

                                <tr>
                                    <th>Year of birth</th><!-- 출생년도 -->
                                    <td>
<?php                                        
if (count($arrMember) > 0) {
    for($i=0; $i<count($arrMember); $i++) {

        $jumin1 = trim(decode_pass($arrMember[$i]["jumin_1"],$pass_key));
        $jumin2_1 = substr(trim(decode_pass($arrMember[$i]["jumin_2"],$pass_key)),0,1);

        if($jumin2_1=="1" || $jumin2_1=="2" || $jumin2_1=="5" || $jumin2_1=="6") {
            $jumin1 = "19".substr($jumin1,0,2);
        } else {
            $jumin1 = "20".substr($jumin1,0,2);
        }
?>
                                        <span><?=$arrMember[$i]["name_eng"]?>(<?=$jumin1?>)</span>
<?php
    }
}                                        
?>
                                    </td>
                                    <th>Mobile number</th><!-- 휴대폰번호 -->
                                    <td><?=mb_substr(decode_pass($arrMember[0]["hphone"],$pass_key),0,-3)."***"?></td>
                                </tr>
                                <tr>
                                    <th>E-mail</th><!-- 이메일 -->
                                    <td><?=decode_pass($arrMember[0]["email"],$pass_key)?></td>
                                    <th>Beneficiary of Insurance money</th><!-- 보험금수령인 -->
                                    <td>
                                        <span>On death: legal heir</span>
                                        <span>Other than death: principal (provided that he is a legal representative in case of a minor)</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
<?php
    for($i=1;$i<=ceil(count($arrCalType)/3);$i++) {
?>    
                <div class="box-conts-wrap">
                    <h2>Coverage by age (<?=$i?>)</h2><!-- 연령별 보장내용 -->

                    <div class="table-box">
                        <div class="db-table-box">
                            <ul class="thead">
                                <li>&nbsp;</li>
<?php
    for($j=(($i-1)*3);$j<(($i*3)>count($arrCalType)?count($arrCalType):($i*3));$j++) {
?>   
                                <li><?=$arrAgeText[$j]?></li>
<?php         
    }
?>    
                            </ul>

                            
                            
<?php
    for($k=1;$k<count($arrPlanType)+1;$k++) {
        $fg_empty = "";

        for($j=(($i-1)*3);$j<(($i*3)>count($arrCalType)?count($arrCalType):($i*3));$j++) {
            $fg_empty .= $arrPlanType["type_".$k]["data"][$j];
        }

        if($fg_empty) {
?>   
                            <ul>
                                <li><?=$arrPlanType["type_".$k]["title_eng"]?></li>
<?php                                
                                for($j=(($i-1)*3);$j<(($i*3)>count($arrCalType)?count($arrCalType):($i*3));$j++) {
?>
                                <li>
<?php
                                    $type_text = $arrPlanType["type_".$k]["data"][$j];
                                    $type_text = str_replace("원", " WON", $type_text);
                                    $type_text = str_replace("가입", "JOIN", $type_text);

                                    echo $type_text;
?>
                                </li>
<?php         
                                }
?>
                            </ul>
<?php
        }
    }
?>    
                        </div>
                    </div>
                </div>
<?php
    }
?>

                <div class="sign-cnfrm-wrap">
                    <ul>
                        <li>
                            ◎ The above Medical Expenses Coverages are applied to medical treatment Worldwide Excluding Korea.<br>Medical Expenses incurred are defined in the Original Policy's Terms & Conditions.
                        </li>
                        <li>◎ Accident Medical Expense,Per accident(in Korea) / Disease Expense,Per sickness(in Korea)</li>
                        <li>◎ Disease Expense Coverage : this coverage includes the Medical expense caused by Covid-19 infection up to the coverage limit of this policy.</li>
                        <li><span class="fc-red">◎ Please submit Claims to:</span>
                            <ul>
                                <li>
                                    <strong>[DB Solutions] ※Hours of Operation : 08:00 - 17:00 (Weekdays) - Pacific Time</strong>
                                </li>
                                <li>
                                    - USA, Canada, Australia, New Zealand, and most Anglophonic European and African countries.</li>
                                <li>PO BOX 5588, Diamond Bar, CA  91765</li>
                            </ul>
                            <div class="box-contact">
                                <div class="inner">
                                    <span>
                                        Tel: + 1-909-444-5511
                                    </span>
                                    <span>
                                        Fax : + 1-909-444-5533
                                    </span>
                                    <span>
                                        Email: info@dbsclaim.com
                                    </span>
                                    <span>
                                        www.dbsclaim.com
                                    </span>
                                </div>
                            </div>
                            <ul>
                                <li>- Other Countries</li>
                                <li>
                                    <strong>[ Emergency customer service center (Korean) ] (24h Assistance Center)</strong>
                                </li>
                                <li>Tel : 82-2-3011-5200 (Collect Call)</li>
                                <li>www.flyingdoctors.co.kr</li>
                            </ul>
                        </li>
                        <li>◎ DB Insurance Call Center: +82 1588-0100</li>
                        <li>◎ Sales Representative : ( 070)4281-0086 )</li> 
                        <li class="eng">
                            <strong>DB INSURANCE CO., LTD. latest financial ratings are as follows : A. M. BEST'S : <br>A(Excellent)</strong>
                        </li>
                        <li>STANDARD&POOR'S : A</li>
                    
                    </ul>

                    <div class="sign-area-wrap eng">
                        <div class="inner">
                            <img src="/img/service/logo-directdb-col-eng.png?n" alt="">
                            <span>DB Finance Center, 432, Teheran-ro, Gangnam-gu, Seoul, Korea, 06194</span>
                            <img src="/img/service/sign-directdb-eng.png?e" alt="">
                        </div>
                    </div>
                </div>

                <div class="box-conts-wrap last">
                    <strong>Date of issue : 2023.07.12</strong>

                    <div class="table-box">
                        <table class="table-db">
                            <colgroup>
                                <col width="12%">
                                <col width="*">
                                <col width="13%">
                                <col width="23%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>Sales Representative</th>
                                    <td>
                                    BIS Co., Ltd. / Kim Sungil<br>
                                        (unique number : 20190581090012, contact : 1800-9010)
                                    </td>
                                    <th>Homepage</th>
                                    <td>www.idbins.com<!-- 홍페이지 --></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
