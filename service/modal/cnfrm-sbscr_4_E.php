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
                        <div>증권번호 : <span><?=
                        
                        !empty($default_plan_join_code_fix[$__CONFIG_COMPANY_TYPE][$trip_type])?$default_plan_join_code_fix[$__CONFIG_COMPANY_TYPE][$trip_type]:(!empty($row["plan_join_code"])?$row["plan_join_code"]:$row["plan_join_code_replace"])?></span></div>
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
                                    <td><?=$arrMember[0]["name_eng"]?></td>
                                    <th>단체계약자</th>
                                    <td>UDIRECT</td>
                                </tr>

                                <tr>
                                    <th>피보험자수</th>
                                    <td><?=number_format(count($arrMember))?></td>
                                    <th>여행지</th>
                                    <td><?=($row["trip_type"]==1)?"국내일원":$row["nation_txt_eng"]?></td>
                                </tr>
                                <tr>
                                    <th>보험기간</th>
                                    <td>
                                        <span><?=$row["start_date"]." ".$row["start_hour"].":00 ~"?></span>
                                        <span><?=$row["end_date"]." ".$row["end_hour"].":00"?></span>
                                    </td>
                                    <th>여행목적</th>
                                    <td><?=$arrTripPurposeEng[$row["trip_purpose"]]?></td>
                                </tr>
                                <tr>
                                    <th>납입보험료</th>
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
                        <p>* 이 상품은 마이뱅크를 계약자로 지정한 단체보험으로, 피보험자의 보험청구는 DB손해보험에서 정상적으로 처리됩니다.</p>
                        <p>* 이 상품은 미국을 포함한 세계 어느 지역의 여행이든 보장하나, 대한민국 외교부가 지정한 여행금지국가와 3단계 여행경보지역은 보장에서 제외됩니다.</p>
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
                                    <td><?=$arrMember[0]["name_eng"]?></td>
                                    <th>보험종류</th>
                                    <td><?=$arrTripTypeEng[$trip_type]?></td>
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
                                        <span><?=$arrMember[$i]["name_eng"]?>(<?=$jumin1?>)</span>
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
                    <h2>
                        <strong>DB손해보험</strong>은 
                        <span><?=$arrMember[0]["name_eng"]?></span>님외 
                        <span><?=number_format(count($arrMember)-1)?></span>명께서 
                        <strong>해외여행자보험</strong>에 
                        <strong>가입</strong>하셨음을 
                        <strong>확인</strong>합니다
                    </h2>
                </div>

                <div class="sign-area-wrap">
                    <div class="inner">
                        <img src="/img/service/logo-directdb-col.png" alt="">
                        <span>서울시 강남구 테헤란로 432 DB금융센터</span>
                        <img src="/img/service/sign-directdb.png" alt="">
                    </div>
                </div>

                <div class="dtlng-wrap">
                    <strong>보장성보험이므로 만기시 환급금이 없습니다.</strong>
                    <p>* 이 보험계약은 예금자보호법에 따라 예금보험공사가 보호하되, 보호한도는 본 보험회사에 있는 귀하의 모든 예금보호대상 금융상품의 해지환급금(또는 만기시 보험 금이나 사고보험금)에 기타지급금을 합하여 1인당 "최고 5천만원"이며, 5천만원을 초과하는 나머지 금액은 보호하지 않습니다.</p>
                    <p>* 자세한 사항은 약관을 읽어보시기 바랍니다.</p>
                </div>
                <?=$row_company["web_site"]?>
            </div>
        </div>
    </div>
</body>
</html>
