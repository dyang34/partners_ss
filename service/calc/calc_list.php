<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[3,0];

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");
if(!LoginManager::getUserLoginInfo("fg_not_common_plan")) {
	$__CONFIG_MEMBER_NO = get_default_member_no(LoginManager::getUserLoginInfo("company_type"));
}

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
    <div class="check-box-wrap">
        <div class="search-shnch-wrap">
            <table class="table-search">
                <colgroup>
                    <col width="133px">
                    <col width="370px">
                    <col width="133px">
                    <col width="370px">
                    <col width="*">
                    <col width="370px">
                    <col width="90px">
                </colgroup>
                <tbody>
                    <tr>
                        <th>신청일</th>
                        <td class="inp">
                            <div class="date_picker">
                                <input type="text" class="picker" name="start_date" id="start_date" readonly placeholder="YYYY.MM.DD">
                            </div>
                            <span class="picker-interval">~</span>
                            <div class="date_picker">
                                <input type="text" class="picker" name="end_date" id="end_date" readonly placeholder="YYYY.MM.DD">
                            </div>
                        </td>

                        <th>신청자</th>
                        <td>
                            <input type="text"  class="input-search" name="" id="" value="홍길동">
                        </td>

                        <th>기타</th>
                        <td>
                            <div class="select-box">
                                <select name="">
                                    <option value="">적당한옵션1</option>
                                    <option value="">적당한옵션2</option>
                                    <option value="">적당한옵션3</option>
                                </select>
                            </div>
                        </td>

                        <td rowspan="1" class="flow-btn"><a class="button search">검색</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="adjustm-list-wrap">
            <h2>정산 내역</h2>

            <!-- table start -->
            <div class="table-wrap">
                <table class="table-list">
                    <colgroup>
                        <col width="12%">
                        <col width="*">
                        <col width="12%">
                        <col width="12%">
                        <col width="12%">
                        <col width="12%">
                        <col width="12%">
                        <col width="12%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>날짜</th>
                            <th>보험사</th>
                            <th>총 보험료</th>
                            <th>커미션</th>
                            <th>선결재</th>
                            <th>입금 금액</th>
                            <th>받은 금액</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2023.01</td>
                            <td>삼성화재</td>
                            <td class="right">530,000 원</td>
                            <td>20%</td>
                            <td class="right">530,000 원</td>
                            <td class="right">0 원</td>
                            <td class="right">424,000 원</td>
                            <td></td>
                            <!--td> // 혹시나 해서 남겨둠 ㅠㅠ
                                <a href="#;" id="three" class="btn-printer"><i class="icon-printer"></i></a>
                            </td-->
                        </tr>
                        <tr>
                            <td>2023.01</td>
                            <td>삼성화재</td>
                            <td class="right">530,000 원</td>
                            <td>20%</td>
                            <td class="right">530,000 원</td>
                            <td class="right">0 원</td>
                            <td class="right">424,000 원</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2023.01</td>
                            <td>삼성화재</td>
                            <td class="right">530,000 원</td>
                            <td>20%</td>
                            <td class="right">530,000 원</td>
                            <td class="right">0 원</td>
                            <td class="right">424,000 원</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2023.01</td>
                            <td>삼성화재</td>
                            <td class="right">530,000 원</td>
                            <td>20%</td>
                            <td class="right">530,000 원</td>
                            <td class="right">0 원</td>
                            <td class="right">424,000 원</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2023.01</td>
                            <td>삼성화재</td>
                            <td class="right">530,000 원</td>
                            <td>20%</td>
                            <td class="right">530,000 원</td>
                            <td class="right">0 원</td>
                            <td class="right">424,000 원</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2023.01</td>
                            <td>삼성화재</td>
                            <td class="right">530,000 원</td>
                            <td>20%</td>
                            <td class="right">530,000 원</td>
                            <td class="right">0 원</td>
                            <td class="right">424,000 원</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2023.01</td>
                            <td>삼성화재</td>
                            <td class="right">530,000 원</td>
                            <td>20%</td>
                            <td class="right">530,000 원</td>
                            <td class="right">0 원</td>
                            <td class="right">424,000 원</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>2023.01</td>
                            <td>삼성화재</td>
                            <td class="right">530,000 원</td>
                            <td>20%</td>
                            <td class="right">530,000 원</td>
                            <td class="right">0 원</td>
                            <td class="right">424,000 원</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- table end -->
        </div>
    </div>
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

$rs->free();
?>
