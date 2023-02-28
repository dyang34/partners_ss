<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[5,1];

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
    <div class="check-box-wrap">
        <div class="customer-list-wrap">
<?php
            include $_SERVER['DOCUMENT_ROOT']."/include/customer_sub_menu.php";
?>
            <div class="table-box">
                <table class="table-list">
                    <colgroup>
                        <col width="5%">
                        <col width="*">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>no</th>
                            <th>제목</th>
                            <th>작성자</th>
                            <th>등록일</th>
                            <th>조회수</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>10</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td class="left">
                                <a href="view.php">설날 휴무 안내</a>
                            </td>
                            <td>관리자</td>
                            <td>2023.01.01</td>
                            <td>102</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- paginate start -->
            <div class="paginate">
                <a href="#;" class="first"><i class="prev-arrow-double"></i></a>
                <a href="#;" class="prev"><i class="prev-arrow"></i></a>
                
                <a href="#;" class="active">1</a>
                <a href="#;" class="">2</a>
                <a href="#;" class="">3</a>
                <a href="#;" class="">4</a>
                <a href="#;" class="">5</a>
                
                <a href="#;" class="next"><i class="next-arrow"></i></a>
                <a href="#;" class="last"><i class="next-arrow-double"></i></a>
            </div>
        </div>
    </div>
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>