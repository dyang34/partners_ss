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
                array_push($arrAgeText, $__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][0]["plan_start_age"]."~".$__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]][0]["plan_end_age"]."세");
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
                        <span>해외여행자보험 가입증명서</span>
                    </strong>
                    <div class="btn-printer">
                        <a href="" value="인쇄하기" id="print" onclick="window.print()">
                            <i class="icon-printer"></i>
                        </a>
                    </div>
                </div>

                <div class="box-conts-wrap">
                    <h2>기본정보
                        <div>증권번호 : <span><?=!empty($default_plan_join_code_fix[$__CONFIG_COMPANY_TYPE][$trip_type])?$default_plan_join_code_fix[$__CONFIG_COMPANY_TYPE][$trip_type]:(!empty($row["plan_join_code"])?$row["plan_join_code"]:$row["plan_join_code_replace"])?></span></div>
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
                                    <th>결제자</th>
                                    <td><?=$arrMember[0]["name"]?></td>
                                    <th>단체계약자</th>
                                    <td>유다이렉트</td>
                                </tr>

                                <tr>
                                    <th>피보험자수</th>
                                    <td><?=number_format(count($arrMember))?>명</td>
                                    <th>여행지</th>
                                    <td><?=($row["trip_type"]==1)?"국내일원":$row["nation_txt"]?></td>
                                </tr>
                                <tr>
                                    <th>보험기간</th>
                                    <td>
                                        <span><?=$row["start_date"]." ".$row["start_hour"].":00부터"?></span>
                                        <span><?=$row["end_date"]." ".$row["end_hour"].":00까지"?></span>
                                    </td>
                                    <th>여행목적</th>
                                    <td><?=$arrTripPurpose[$row["trip_purpose"]]?></td>
                                </tr>
                                <tr>
                                    <th>납입보험료</th>
                                    <td colspan="3"><?=number_format($total_price)?>원</td>
<?/*                                    
                                    <th>보험료 납입일자</th>
                                    <td>2022-12-15</td>

                                    <th>보험 신청일</th>
                                    <td><?=date('Y-m-d', $row["regdate"])?></td>
*/?>                                    
                                </tr>
                            </tbody>
                        </table>
                        <!-- 
                            <p>* 이 상품은 마이뱅크를 계약자로 지정한 단체보험으로, 피보험자의 보험청구는 DB손해보험에서 정상적으로 처리됩니다.</p>
                            <p>* 이 상품은 미국을 포함한 세계 어느 지역의 여행이든 보장하나, 대한민국 외교부가 지정한 여행금지국가와 3단계 여행경보지역은 보장에서 제외됩니다.</p>
                        -->
                    </div>
                </div>

                <div class="box-conts-wrap">
                    <h2>보험가입자(피보험자) 정보</h2>

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
                                    <th>이름</th>
                                    <td><?=$arrMember[0]["name"]?></td>
                                    <th>보험종류</th>
                                    <td><?=$arrTripType[$trip_type]?>자보험</td>
                                </tr>

                                <tr>
                                    <th>출생년도</th>
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
                                        <span><?=$arrMember[$i]["name"]?>(<?=$jumin1?>년)</span>
<?php
    }
}                                        
?>
                                    </td>
                                    <th>휴대폰번호</th>
                                    <td><?=mb_substr(decode_pass($arrMember[0]["hphone"],$pass_key),0,-3)."***"?></td>
                                </tr>
                                <tr>
                                    <th>이메일</th>
                                    <td><?=decode_pass($arrMember[0]["email"],$pass_key)?></td>
                                    <th>보험금수령인</th>
                                    <td>
                                        <span>사망시:법정상속인</span>
                                        <span>사망외:본인(단, 미성년자 경우 법정대리인)</span>
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
                    <h2>연령별 보장내용 (<?=$i?>)</h2>

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
                                <li><?=$arrPlanType["type_".$k]["title"]?></li>
<?php                                
                                for($j=(($i-1)*3);$j<(($i*3)>count($arrCalType)?count($arrCalType):($i*3));$j++) {
?>
                                <li><?=$arrPlanType["type_".$k]["data"][$j]?></li>
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
                        <li>◎ 이 증서는 보험가입 확인용 입니다.</li>
                        <li>◎ 해당 보험은 만기환급금 및 해약환급금이 없습니다.</li>
                        <li><span class="fc-red">◎ 보험금청구 :</span>
                            <ul>
                                <li>
                                    <strong>[DB Solutions] ※Hours of Operation : 08:00 - 17:00 (Weekdays) - Pacific Time</strong>
                                </li>
                                <li>- 미국,캐나다,호주,뉴질랜드,유럽과 아프리카의 영어통용국에서 발생 의료비 지불보증 및 보험금청구대행</li>
                                <li>PO BOX 5588, Diamond Bar, CA  91765</li>
                            </ul>
                            <div class="box-contact">
                                <div class="inner">
                                    <span>
                                        Tel: + 1-909-444-5511<br>
                                        Email: info@dbsclaim.com
                                    </span>
                                    <span>
                                        Fax : + 1-909-444-5533<br>
                                        www.dbsclaim.com
                                    </span>
                                </div>
                            </div>
                            <ul>
                                <li>- 그 외 국가</li>
                                <li>
                                    <strong>[DB손해보험 해외긴급지원 우리말서비스] (24시간 지원센터)</strong>
                                </li>
                                <li>Tel : 82-2-3011-5200 (Collect Call)</li>
                                <li>www.flyingdoctors.co.kr</li>
                            </ul>
                        </li>
                        <li>◎ DB손해보험 콜센터 : +82 1588-0100</li>
                        <li>◎ 보험담당직원 : ( 070)4281-0086 )</li>
                    </ul>

                    <div class="sign-area-wrap">
                        <div class="inner">
                            <img src="/img/service/logo-directdb-col.png" alt="">
                            <span>서울시 강남구 테헤란로 432 DB금융센터</span>
                            <img src="/img/service/sign-directdb.png" alt="">
                        </div>
                    </div>
                </div>

                <div class="box-conts-wrap last">
                    <strong>발행일  : 2023.07.12</strong>

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
                                    <th>담당자</th>
                                    <td>
                                        주식회사 비아이에스 / 김성일<br>
                                        (고유번호 : 20190581090012, 연락처 : 1800-9010)
                                    </td>
                                    <th>홈페이지</th>
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
