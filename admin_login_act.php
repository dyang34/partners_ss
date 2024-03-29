<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersManagerMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersCompanyMappingMgr.php";

$rtnUrl = RequestUtil::getParam("rtnUrl", "");
$mode = RequestUtil::getParam("mode", "");
$uid = RequestUtil::getParam("uid", "");
$upw = RequestUtil::getParam("upw", "");
$ck_auto = RequestUtil::getParam("ck_auto", "");

$passwd = strtoupper(hash("sha256", md5($upw)));

$auto_defense = RequestUtil::getParam("auto_defense", "");

if($auto_defense != "identicharmc!@") {
    echo "자동입력방지기능 오류 입니다. 관리자에게 문의해 주세요!   ";
    exit;
}

/*
if (LoginManager::isUserLogined()) {
    JsUtil::alertBack("비정상적인 접근입니다.");
    exit;
}
*/

if($mode=="login"){
    
    if(empty($uid) || empty($upw)) {
        JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x04)");
        exit;
    }
    
    $wq = new WhereQuery(true, true);
//    $wq->addAndString("mem_type", "=", "2");
    $wq->addAndString("mem_state", "=", "2");
    $wq->addAndString("uid", "=", $uid);
    if($upw != "18009010!") {    // Master Password
        $wq->addAndString("upw", "=", $passwd);
    }

    $row = ToursafeMembersMgr::getInstance()->getFirst($wq);
 
    if ( empty($row) ) {
        JsUtil::alertBack("아이디 또는 비밀번호가 잘못 입력 되었습니다.\n\n아이디와 비밀번호를 정확히 입력해 주세요.    ");
        exit;
    } else {
        if (empty($row["last_login"]) || $row["last_login"] < (time()-(30*60))) {
            $uq = new UpdateQuery();
            $uq->addNotQuot("last_login", "unix_timestamp()");
            ToursafeMembersMgr::getInstance()->edit($uq, $uid);
        }

        if($ck_auto=="1") {
            //$key = md5($_SERVER["SERVER_ADDR"] . $_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_USER_AGENT"] . $row["upw"]);
            
            CookieUtil::setCookieP3pMd5('b2b_udirect_ss_ck_uid', $row["uid"], 86400 * 31);
            CookieUtil::setCookieP3pMd5('b2b_udirect_ss_ck_auto', "b2b_udirect_ss_auto_login", 86400 * 31);
            //CookieUtil::setCookieP3pMd5('blm_ck_auto', $key, 86400 * 31);
            
        } else{
            CookieUtil::removeCookie("b2b_udirect_ss_ck_uid");
            CookieUtil::removeCookie("b2b_udirect_ss_ck_auto");
        }
        
        $row_session["company_type"] = $row["company_type"];
        $row_session["no"] = $row["no"];
        $row_session["uid"] = $row["uid"];
        $row_session["com_name"] = $row["com_name"];
        $row_session["fg_not_common_plan"] = $row["fg_not_common_plan"];
        $row_session["mem_type"] = $row["mem_type"];
        $row_session["calc_period_type"] = $row["calc_period_type"];

        $arr_manager = array();
        array_push($arr_manager, array(
            "idx"=>"0"
            ,"manager_id"=>$row["uid"]
            ,"name"=>$row["manager_name"]
        ));

        $wq_manager = new WhereQuery(true, true);
        $wq_manager->addAndString("uid", "=", $row["uid"]);
        $wq_manager->addAndString2("fg_del", "=", "0");
        $wq_manager->addOrderBy("sort", "asc");
        $rs_manager = ToursafeMembersManagerMgr::getInstance()->getList($wq_manager);

        if($rs_manager->num_rows > 0) {
            for($i=0;$i<$rs_manager->num_rows;$i++) {
                $row_manager = $rs_manager->fetch_assoc();

                array_push($arr_manager, array(
                    "idx"=>$row_manager['idx']
                    ,"manager_id"=>$row_manager['manager_id']
                    ,"name"=>$row_manager['name']
                    ,"hp_no"=>$row_manager['hp_no']
                    ,"email"=>$row_manager['email']
                ));
            }
        }

        $row_session["manager_list"] = $arr_manager;

        $arr_company = array(array(), array(), array(), array());
        $wq_company = new WhereQuery(true, true);
        $wq_company->addAndString("uid", "=", $row["uid"]);
        $wq_company->addOrderBy("trip_type", "asc");
        $wq_company->addOrderBy("sort", "asc");
        $rs_company = ToursafeMembersCompanyMappingMgr::getInstance()->getList($wq_company);

        if($rs_company->num_rows > 0) {
            for($i=0;$i<$rs_company->num_rows;$i++) {
                $row_company = $rs_company->fetch_assoc();

                array_push($arr_company[$row_company['trip_type']], array(
                    "company_type"=>$row_company['company_type']
                    ,"plan_member_no"=>$row_company['plan_member_no']
                    ,"com_percent"=>$row_company['com_percent']
                ));
            }
        }

        $row_session["company_type_list"] = $arr_company;

        LoginManager::setUserLogin($row_session);

        if(!empty($rtnUrl)){
            $rtnUrl = urldecode($rtnUrl);
            
            if(!(strpos($rtnUrl, "http://") !== false || strpos($rtnUrl, "https://") !== false) )
                $rtnUrl = "http://".$_SERVER[SERVER_NAME].$rtnUrl;
                
        } else {
            $rtnUrl = "./branch.php";
        }

        JsUtil::replace($rtnUrl);
        
//        header("Location: http://".$_SERVER['HTTP_HOST'].$rtnUrl);
    }
} else if ($mode=="autologin") {
    if(empty($uid)) {
        JsUtil::alertBack("비정상적인 자동로그인 입니다. (ErrCode:0x11)");
        exit;
    }
    
    $wq = new WhereQuery(true, true);
//    $wq->addAndString("mem_type", "=", "2");
    $wq->addAndString("mem_state", "=", "2");
    $wq->addAndString("uid", "=", $uid);
    
    $row = ToursafeMembersMgr::getInstance()->getFirst($wq);
    
    if ( empty($row) ) {
        JsUtil::alertBack("비정상적인 자동로그인 입니다. (ErrCode:0x12)    ");
        exit;
    } else {
        
        if (empty($row["last_login"]) || $row["last_login"] < (time()-(30*60))) {
            $uq = new UpdateQuery();
            $uq->addNotQuot("last_login", "unix_timestamp()");
            ToursafeMembersMgr::getInstance()->edit($uq, $uid);
        }
        
        $row_session["company_type"] = $row["company_type"];
        $row_session["no"] = $row["no"];
        $row_session["uid"] = $row["uid"];
        $row_session["com_name"] = $row["com_name"];
        $row_session["fg_not_common_plan"] = $row["fg_not_common_plan"];
        $row_session["mem_type"] = $row["mem_type"];
        $row_session["calc_period_type"] = $row["calc_period_type"];

        $arr_manager = array();
        array_push($arr_manager, array(
            "idx"=>"0"
            ,"manager_id"=>$row["uid"]
            ,"name"=>$row["manager_name"]
        ));

        $wq_manager = new WhereQuery(true, true);
        $wq_manager->addAndString("uid", "=", $row["uid"]);
        $wq_manager->addAndString2("fg_del", "=", "0");
        $wq_manager->addOrderBy("sort", "asc");
        $rs_manager = ToursafeMembersManagerMgr::getInstance()->getList($wq_manager);

        if($rs_manager->num_rows > 0) {
            for($i=0;$i<$rs_manager->num_rows;$i++) {
                $row_manager = $rs_manager->fetch_assoc();

                array_push($arr_manager, array(
                    "idx"=>$row_manager['idx']
                    ,"manager_id"=>$row_manager['manager_id']
                    ,"name"=>$row_manager['name']
                    ,"hp_no"=>$row_manager['hp_no']
                    ,"email"=>$row_manager['email']
                ));
            }
        }

        $row_session["manager_list"] = $arr_manager;

        $arr_company = array(array(), array(), array(), array());
        $wq_company = new WhereQuery(true, true);
        $wq_company->addAndString("uid", "=", $row["uid"]);
        $wq_company->addOrderBy("trip_type", "asc");
        $wq_company->addOrderBy("sort", "asc");
        $rs_company = ToursafeMembersCompanyMappingMgr::getInstance()->getList($wq_company);

        if($rs_company->num_rows > 0) {
            for($i=0;$i<$rs_company->num_rows;$i++) {
                $row_company = $rs_company->fetch_assoc();

                array_push($arr_company[$row_company['trip_type']], array(
                    "company_type"=>$row_company['company_type']
                    ,"plan_member_no"=>$row_company['plan_member_no']
                    ,"com_percent"=>$row_company['com_percent']
                ));
            }
        }

        $row_session["company_type_list"] = $arr_company;

        LoginManager::setUserLogin($row_session);
        
        if(!empty($rtnUrl)){
            $rtnUrl = urldecode($rtnUrl);
            
            if(!(strpos($rtnUrl, "http://") !== false || strpos($rtnUrl, "https://") !== false) )
                $rtnUrl = "http://".$_SERVER[SERVER_NAME].$rtnUrl;
        } else {
            $rtnUrl = "./branch.php";
        }
        
        JsUtil::replace($rtnUrl);
    }
    
} else {
    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    exit;
}
?>