<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/Page.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/notice/FreeMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[5,1];

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");

$currentPage = RequestUtil::getParam("currentPage", "1");
$pageSize = RequestUtil::getParam("pageSize", "15");

$pg = new Page($currentPage, $pageSize);

$_order_by = RequestUtil::getParam("_order_by", "no");
$_order_by_asc = RequestUtil::getParam("_order_by_asc", "desc");

$wq = new WhereQuery(true, true);
$wq->addAndIn("table_name",array("board_1","board_2"));
$wq->addAndString("del_key","=","Y");
$wq->addAndString("secret","=","N");

$wq->addOrderBy("notice", "desc");
$wq->addOrderBy($_order_by, $_order_by_asc);
$wq->addOrderBy("no", "desc");

$rs = FreeMgr::getInstance()->getListPerPage($wq, $pg);

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<form name="pageForm" method="get">
    <input type="hidden" name="currentPage" value="<?=$currentPage?>">
</form>

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
<?php
if ($rs->num_rows > 0) {
    for($i=0; $i<$rs->num_rows; $i++) {
        $row = $rs->fetch_assoc();
?>
                        <tr>
                            <td><?=number_format($pg->getMaxNumOfPage() - $i)?></td><!-- no -->
                            <td class="left">
                                <a href="notice_view.php?no=<?=$row["no"]?>"><?=$row["title"]?></a>
                            </td>
                            <td><?=$row["name"]?></td>
                            <td><?=substr($row["regdate"],0,10)?></td>
                            <td><?=number_format($row["hit"])?></td>
                        </tr>
<?php
    }
} else {
?>
                <tr><td colspan="5" class="no-data">No Data.</td></tr>
<?php
}
?>

                    </tbody>
                </table>
            </div>

            <?=$pg->getNaviForFuncULifeB2B("goPage", "<<", "<", ">", ">>")?>

        </div>
    </div>

    <script type="text/javascript">

let g_req_obj;

// 달력 script
$(document).ready(function() {
   
});

const goPage = function(page) {
	var f = document.pageForm;
	f.currentPage.value = page;
	f.action = "notice.php";
	f.submit();
}
</script>

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

$rs->free();
?>