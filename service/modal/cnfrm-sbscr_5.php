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
            <div class="section sslife">
                <div class="title">
                    <h2 >삼성화재 해외여행보험 피보험자 등록 접수 확인서</h2>
                    <div class="btn-printer">
                        <a href="" value="인쇄하기" id="print" onclick="window.print()">
                            <i class="icon-printer"></i>
                        </a>
                    </div>
                    <p>* 본 확인서는 삼성화재 해피투어 계약에 아래 보장조건으로 피보험자 통지 업무를 진행함을 <span>비아이에스 유라이프</span> 대리점이 확인하는 내용입니다.</p>
                </div>

                <!-- 기본 정보 start -->
                <h4><i class="icon-subtit"></i>계약 기본 사항</h4>
                <table class="table-modal">
                    <colgroup>
                        <col width="30%">
                        <col width="*">
                    </colgroup>
                    <tbody>
                        <tr>
                            <th>계약자</th>
                            <td><?=LoginManager::getUserLoginInfo("com_name")?></td>
                        </tr>
                        <tr>
                            <th>피보험자</th>
                            <td><?=$arrMember[0]["name"]?></td>
                        </tr>
<?/*
                        <tr>
                            <th>증권번호</th>
                            <td>1234567891012</td>
                        </tr>
*/?>                        
                        <tr>
                            <th>생년월일 / 성별</th>
                            <td>
<?
$jumin1 = trim(decode_pass($arrMember[0]["jumin_1"],$pass_key));
$jumin2_1 = substr(trim(decode_pass($arrMember[0]["jumin_2"],$pass_key)),0,1);

if($jumin2_1=="1" || $jumin2_1=="2" || $jumin2_1=="5" || $jumin2_1=="6") {
    $jumin1 = "19".substr($jumin1,0,2)."-".substr($jumin1,2,2)."-".substr($jumin1,4,2);
} else {
    $jumin1 = "20".substr($jumin1,0,2)."-".substr($jumin1,2,2)."-".substr($jumin1,4,2);
}

echo $jumin1." / ".($arrMember[0]["sex"]==1?"남":"여");
?>                                
                            </td>
                        </tr>
                        <tr>
                            <th>보험기간</th>
                            <td><?=$row["start_date"]." ".$row["start_hour"]."시 ~ ".$row["end_date"]." ".$row["end_hour"]."시"?></td>
                        </tr>
                        <tr>
                            <th>여행지역</th>
                            <td><?=($row["trip_type"]==1)?"국내일원":$row["nation_txt"]?></td>
                        </tr>
<?/*                        
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
*/?>                        
                    -->
                    </tbody>
                </table>
                
                <div class="cont-wrap">
                    <ul>
                        <li>□ 위 피보험자는 삼성화재 해피투어 계약(증권번호 <span class=""><?=$default_plan_join_code_fix[$__CONFIG_COMPANY_TYPE][$trip_type]?></span> )의 포괄계약 추가특약에 따라서 본 대리점의 피보험자 통보 / 보험료 정산 진행시 담보되며, 정해진 통보주기 이전의 사고에 대해서도 보장됩니다.</li>
                        <li>□ 단, 피보험자 주민번호 확인을 통한 삼성화재의 계약 반영 이전에는 개별 피보험자가 직접 본인의 보험 가입 내용을 확인할 수 없으며 사고 접수가 불가하오니, 위와 같은 상황에 해당 하는 경우 주민번호 정보와 함께 별도 요청이 필요합니다.</li>
                    </ul>
                    <p class="blrd">* 보험계약 전 상품설명서 및 약관을 읽어 보시기 바랍니다.</p>
                    <p class="blrd">* 삼성화재는 해당 상품에 대해 충분히 설명할 의무가 있으며, 가입자는 가입에 앞서 이에 대해 모집종사자로부터 충분한 설명을 받으시기 바랍니다.</p>
                    <p class="blrd">* 보험계약자가 기존에 체결했던 보험 계약을 해지하고 다른 보험계약을 체결하면 보험인수가 거절되거나 보험료가 인상되거나 보장내용이  달라질 수 있습니다. 또한 지급한도, 면책사항 등에 따라 보험금 지급이 제한될 수 있습니다.</p>
                    <p class="blrd">* 이 보험계약은 예금자보호법에 따라 예금보험공사가 보호하되, 보호 한도는 본 보험회사에 있는 귀하의 모든 예금보호 대상 금융상품의 해약환급금<br>
                        (또는 만기 시 보험금이나 사고보험금)에 기타지급금을 합하여 1인당 “최고 5천만원＂이며, 5천만원을 초과하는 나머지 금액은 보호하지 않습니다.<br>
                        단, 보험계약자와 보험료납부자가 법인이면 보호되지 않습니다.
                    </p>
                </div>
                <div class="seal-flex">
                    <ul>
                        <li>
                            <p>
                                삼성화재
                                <span>청약</span> 부서
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
                    <div class="logo"></div>
                </div>
            </div>


<?
    $cnt_cal_type = count($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]["List"][$__CONFIG_MEMBER_NO][$trip_type]);
    for($i=0; $i<count($arrCalType); $i++) {
        $arrPlanTypePrice = array();
        $cnt_plan_type = count($__ARR_CONFIG_PLAN[$__CONFIG_COMPANY_TYPE]['List'][$__CONFIG_MEMBER_NO][$trip_type][$arrCalType[$i]["cal_type"]]);
?>
            <!-- 2페이지 start -->
            <div class="section sslife">
                <!-- 담보내용 start -->
                <h4><i class="icon-subtit"></i>담보내용 <?=$arrCalTypeTitle[$cnt_cal_type][$arrCalType[$i]["cal_type"]-1]?> <?=$arrCalType[$i]["plan_title"]?>(<?=$arrCalType[$i]["plan_code"]?>)</h4>
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

                <div class="foot-logo-wrap">
                    <div class="logo"></div>
                </div>
            </div>
<?
    }
?>
            <!-- 3페이지 start -->
           <div class="section sslife">
                <h4 class="sub-title"><i class="icon-subtit"></i> 피보험자별  상세</h4>
                <table class="table-modal">
                    <colgroup>
                        <col width="7%">
                        <col width="%">
                        <col width="%">
                        <col width="%">
                        <col width="%">
                        <col width="%">
                        <col width="%">
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

        $jumin1 = trim(decode_pass($arrMember[$i]["jumin_1"],$pass_key));
        $jumin2_1 = substr(trim(decode_pass($arrMember[$i]["jumin_2"],$pass_key)),0,1);

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
                

                <div class="foot-logo-wrap">
                    <div class="logo"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>