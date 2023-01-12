<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/UpdateQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/SystemUtil.php";

$rtnUrl = RequestUtil::getParam("rtnUrl", "");

$b2b_udirect_ss_ck_auto = CookieUtil::getCookieMd5("b2b_udirect_ss_ck_auto");
$b2b_udirect_ss_ck_userid = CookieUtil::getCookieMd5("b2b_udirect_ss_ck_userid");

if(!$b2b_udirect_ss_ck_auto) $b2b_udirect_ss_ck_auto = "";

if (LoginManager::isUserLogined() && !empty(LoginManager::getUserLoginInfo("grade"))) {
    
    $wq = new WhereQuery(true, true);
    $wq->addAndString("uid", "=", LoginManager::getUserLoginInfo("uid"));
    
    $row = ToursafeMembersMgr::getInstance()->getFirst($wq);
    
    if ( empty($row) ) {
        JsUtil::replace("./admin_logout.php");
        exit;
    } else {
        if (empty($row["last_login"]) || $row["last_login"] < (time()-(30*60))) {
            $uq = new UpdateQuery();
            $uq->addNotQuot("last_login", "unix_timestamp()");
            ToursafeMembersMgr::getInstance()->edit($uq, LoginManager::getUserLoginInfo("uid"));
        }
    }
    
    if (!empty($rtnUrl)) {
        JsUtil::replace($rtnUrl);
        exit;
    } else {
        $rtnUrl = "/branch.php";
        JsUtil::replace($rtnUrl);
        exit;
    }
}

if(!empty($rtnUrl)) {
    $rtnUrl = urldecode($rtnUrl);
}

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";


if (!SystemUtil::isLocalhost()) {
?>
<script>
if(window.location.protocol == "http:"){
	window.location.protocol = "https:";
}
</script>
<?php
}
?>
    <body id="lgoin">
        <div id="wrap">
            <div class="lg-box">
                <div class="log-area">
                    <h1><img src="/images/common/bis_logo.png" alt=""></h1>
                    <h2>U-Direct B2B System.</h2>

                    <form name="writeForm" class="custom-form" method="post" autocomplete="off">
                        <input type="hidden" name="auto_defense" />
                        <input type="hidden" name="mode" value="login" />
                        <div class="id_pw_wrap inb">
                            <div class="input_row">
                                <div class="icon_cell">
                                    <span class="icon_id">
                                        <span class="blind">아이디</span>
                                    </span>
                                </div>
                                <input type="text" name="uid" id="uid" class="input-login" />
                            </div>
                            <div class="input_row">
                                <div class="icon_cell">
                                    <span class="icon_pw">
                                        <span class="blind">비밀번호</span>
                                    </span>
                                </div>
                                <input type="password" class="input-login" name="upw"  id="upw" />
                            </div>
                        </div>

                        <div class="check_box_wrap">
                            <div class="choice-round">
                                <input type="checkbox"  id="nologin" name="ck_auto" value="1" />
                                <label for="nologin">자동 로그인<span class="box"></span></label>
                            </div>
                        </div>
                        <div class="button-center">
                            <a href="#" onClick="javascript:login_submit();return false;" class="button login xlarge">로그인</a>
                        </div>
                    </form>
                </div>
        
                <div class="caption">
                    Copyright ⓒ 2023 (주)유라이프파이낸셜. All rights reserved.
                </div>
            </div>    
        </div>

<?php
if ($b2b_udirect_ss_ck_auto=="b2b_udirect_ss_auto_login" && !empty($b2b_udirect_ss_ck_userid)) {
?>

<form name="autoLoginForm" method="post" action="./admin_login_act.php">
	<input type="hidden" name="mode" value="autologin" />
	<input type="hidden" name="auto_defense" value="identicharmc!@" />
    <input type="hidden" name="rtnUrl" value="<?=urlencode($rtnUrl)?>" />
    <input type="hidden" name="uid" value="<?=$b2b_udirect_ss_ck_userid?>" />
</form>

<script type="text/javascript">
document.autoLoginForm.submit();
</script>

<?php 
}
?>       
        
<script src="/js/ValidCheck.js"></script>
<script language="javascript">
//<![CDATA[

$(document).on('keypress','#uid, #upw',function(e) {
	if (e.keyCode === 13) {
		login_submit();
		return false;
	}
});

function login_submit(){
	var f = document.writeForm;

    if ( VC_inValidText(f.uid, "아이디") ) return false;
    if ( f.uid.value == "아이디" ) {
    	alert("아이디를 입력해 주십시오.");
    	f.uid.focus();
		return false;
    }
    if ( VC_inValidText(f.upw, "패스워드") ) return false;

	f.auto_defense.value = "identicharmc!@";
	
    f.action = "./admin_login_act.php";
    f.submit();
}	

//]]>
</script>

    </body>
</html>