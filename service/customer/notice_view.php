<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/service/notice/FreeMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$menuNo=[5,1];

$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");
$no = RequestUtil::getParam("no", "");

if (empty($no)) {
    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
}

$row = FreeMgr::getInstance()->getByKey($no);

$arrFile = explode(";",$row["file_name"]);
$arrFileReal = explode(';',$row["real_file_name"]);
if($row["file_name"] != "") {
	for($i=0;$i<count($arrFile);$i++){
		$file_text .= "<i class=\"icon-file\"></i><a href='/lib/download_notice.php?file_name=".urlencode($arrFileReal[$i])."&save_file=".$arrFile[$i]."&meta=free' class=\"down_file\"><span>".$arrFileReal[$i]."</span></a> ";
	}
}

$uq = new UpdateQuery();
$uq->addNotQuot("hit","hit+1");
FreeMgr::getInstance()->edit($uq, $no);

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
    <div class="check-box-wrap">
        <div class="customer-list-wrap">
<?php
            include $_SERVER['DOCUMENT_ROOT']."/include/customer_sub_menu.php";
?>
            <div class="view-title-wrap">
                <h2><?=$row["title"]?></h2>
                <ul class="clearfix inb">
                    <li>작성일 : <?=substr($row["regdate"],0,10)?></li>
                    <li>작성자 : <?=$row["name"]?></li>
                    <li>첨부파일 : <?=$file_text?></li>
                </ul>
            </div>

            <div class="view-cont-wrap">
                <?=htmlspecialchars_decode(stripslashes($row["content"]))?>
            </div>

            <div class="center-button-area">
                <a onClick="history.go(-1)" class="button blue">이전</a>
            </div>
        </div>
    </div>
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>