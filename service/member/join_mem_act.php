<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/UploadUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

header("Cache-Control:no-cache");
header("Pragma:no-cache");
header("Content-Type:text/html; charset=utf-8");

$mode = RequestUtil::getParam("mode", "INS");
$uid = RequestUtil::getParam("uid", "");
$upw = RequestUtil::getParam("upw", "");
$upw_cfm = RequestUtil::getParam("upw_cfm", "");
$com_name = RequestUtil::getParam("com_name", "");
$com_no = RequestUtil::getParam("com_no", "");
$manager_name = RequestUtil::getParam("manager_name", "");
$hphone = RequestUtil::getParam("hphone", "");
$hphone2 = RequestUtil::getParam("hphone2", "");
$email = RequestUtil::getParam("email", "");

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    JsUtil::alertBack("자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}

try {
    if($mode=="INS") {
        
        if (empty($uid)) {
            JsUtil::alertBack("아이디를 입력해 주십시오.   ");
            exit;
        }
        
        if (empty($upw)) {
            JsUtil::alertBack("패스워드를 입력해 주십시오.   ");
            exit;
        }
        
        if ($upw != $upw_cfm) {
            JsUtil::alertBack("패스워드 확인이 일치하지 않습니다.   ");
            exit;
        }

        if (empty($com_name)) {
            JsUtil::alertBack("회사명을 입력해 주십시오.   ");
            exit;
        }
        
        if (empty($com_no)) {
            JsUtil::alertBack("사업자번호를 입력해 주십시오.   ");
            exit;
        }

        if (empty($manager_name)) {
            JsUtil::alertBack("담당자를 입력해 주십시오.   ");
            exit;
        }

        if (empty($hphone)) {
            JsUtil::alertBack("전화번호를 입력해 주십시오.   ");
            exit;
        }

        if (empty($hphone2)) {
            JsUtil::alertBack("휴대폰 번호를 입력해 주십시오.   ");
            exit;
        }

        if (empty($email)) {
            JsUtil::alertBack("이메일을 입력해 주십시오.   ");
            exit;
        }

        if (!$_FILES["file_name"]["name"]) {
            JsUtil::alertBack("사업자 등록증 파일을 선택해 주십시오.   ");
            exit;
        }
        
        $wq = new WhereQuery(true, true);
        $wq->addAndString("uid","=",$uid);
        
        if (ToursafeMembersMgr::getInstance()->exists($wq)) {
            JsUtil::alertBack("이미 존재하는 아이디입니다.   ");
            exit;
        }

        $newFileName = UploadUtil::getNewFileName();
        $ret = UploadUtil::upload2("file_name", $newFileName, UploadUtil::$License_UpWebPath, UploadUtil::$License_MaxFileSize, UploadUtil::$License_AllowFileType, false, true);
        if ( !empty($ret["err_code"]) ) {
            JsUtil::alertBack($ret["err_msg"]." ErrCode : ".$ret["err_code"]);
            exit;
        }

        $newWebPath = $ret["newWebPath"];
        $newFileName = $ret["newFileName"];
        $fileExtName = $ret["fileExtName"];
        $fileSize = $ret["fileSize"];
       
        $arrIns = array();
        $arrIns["mem_type"] = "2";
        $arrIns["mem_state"] = "1";
        $arrIns["company_type"] = "5";
        $arrIns["uid"] = $uid;
        $arrIns["upw"] = strtoupper(hash("sha256", md5($upw)));
        $arrIns["com_name"] = $com_name;
        $arrIns["com_no"] = $com_no;
        $arrIns["manager_name"] = $manager_name;
        $arrIns["hphone"] = encode_pass($hphone,$pass_key);
        $arrIns["hphone2"] = encode_pass($hphone2,$pass_key);
        $arrIns["email"] = encode_pass($email,$pass_key);
        $arrIns["file_real_name"] = $_FILES["file_name"]["name"];
        $arrIns["file_name"] = "/home".$newWebPath.$newFileName;
        $arrIns["com_percent"] = "0";
        
        ToursafeMembersMgr::getInstance()->add($arrIns);

        JsUtil::alertReplace("등록되었습니다.    ", "/");
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>