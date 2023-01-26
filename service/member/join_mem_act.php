<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_data.php";
require_once $_SERVER['DOCUMENT_ROOT']."/lib/function.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

$mode = RequestUtil::getParam("mode", "INS");
$uid = RequestUtil::getParam("uid", "");
$upw = RequestUtil::getParam("upw", "");
$upw_cfm = RequestUtil::getParam("upw_cfm", "");
$com_name = RequestUtil::getParam("com_name", "");
$com_no = RequestUtil::getParam("com_no", "");
$manager = RequestUtil::getParam("manager", "");
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

        if (empty($manager)) {
            JsUtil::alertBack("담당자를 입력해 주십시오.   ");
            exit;
        }

        if (empty($hphone)) {
            JsUtil::alertBack("전화번호를 입력해 주십시오.   ");
            exit;
        }

        if (empty($email)) {
            JsUtil::alertBack("이메일을 입력해 주십시오.   ");
            exit;
        }

        $wq = new WhereQuery(true, true);
        $wq->addAndString("uid","=",$uid);
        
        if (ToursafeMembersMgr::getInstance()->exists($wq)) {
            JsUtil::alertBack("이미 존재하는 아이디입니다.   ");
            exit;
        }
        
        $arrIns = array();
        $arrIns["mem_type"] = "2";
        $arrIns["mem_state"] = "1";
        $arrIns["company_type"] = "5";
        $arrIns["uid"] = $uid;
        $arrIns["upw"] = strtoupper(hash("sha256", md5($upw)));
        $arrIns["com_name"] = $com_name;
        $arrIns["com_no"] = $com_no;
        $arrIns["manager"] = $manager;
        $arrIns["hphone"] = encode_pass($hphone,$pass_key);
        $arrIns["hphone2"] = encode_pass($hphone2,$pass_key);
        $arrIns["email"] = encode_pass($email,$pass_key);

        ToursafeMembersMgr::getInstance()->add($arrIns);

        JsUtil::alertReplace("등록되었습니다.    ", "./join_mem.php");
    } else {
        JsUtil::alertBack("잘못된 경로로 접근하였습니다. (ErrCode:0x09)   ");
        exit;
    }
    
} catch(Exception $e) {
    JsUtil::alertBack("Exception 오류 입니다. 관리자에게 문의해 주세요!   ");
    exit;
}
?>