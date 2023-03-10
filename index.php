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
$b2b_udirect_ss_ck_uid = CookieUtil::getCookieMd5("b2b_udirect_ss_ck_uid");

if(!$b2b_udirect_ss_ck_auto) $b2b_udirect_ss_ck_auto = "";

if (LoginManager::isUserLogined()) {
    
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
<link rel="stylesheet" href="/css/swiper-bundle.min.css" />
<link rel="stylesheet" type="text/css" href="/css/member.css?v=<?=time()?>">
<script>
if(window.location.protocol == "http:"){
	window.location.protocol = "https:";
}
</script>
<?php
}
?>
    <body id="wrap">
        
    <div class="login-wrap">
        <div class="login-cont-box">
            <div class="info-box-left">
                <h1><img src="/img/common/logo-toursafe.png" alt="??????????????? ??????"></h1>
                <div class="rgstr-guide-wrpa">
                    <div class="rgstr-guide">
                        <strong>????????????</strong>
                        <p class="type-number">1. ?????? ??? ????????? ?????? ?????? ????????????.</p>
                        <p class="type-number">2. ????????? ?????? ?????? ?????? ?????? ?????????. ????????? ?????? ????????? ?????????.</p>
                        <p class="type-number">3. ?????? ??? ????????? ????????? ?????? ?????????.</p>
                    </div>

                    <div class="user-manual">
                        <strong>????????? ?????????</strong>
                        <a href="#;" class="button gray">????????????</a>
                    </div>

                    <div class="join-contact">
                        <strong>????????????</strong>
                        <span class="tel-number">1800-9010</span>
                    </div>
                </div>

                <div class="banner">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">Slide 1</div>
                            <div class="swiper-slide">Slide 2</div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
            <div class="login-box-right">
                <h2>??????????????? ???????????? <span>?????????</span></h2>

                <form name="writeForm" class="custom-form" method="post" autocomplete="off">
                    <input type="hidden" name="auto_defense" />
                    <input type="hidden" name="mode" value="login" />
                    <div class="login-inputbox-wrap">
                        <ul class="clearfix inb">
                            <li>
                                <input type="text" class="input-login" name="uid" id="uid" value="<?=$b2b_udirect_ss_ck_uid?>" placeholder="admin ID">
                            </li>
                            <li>
                                <input type="password" class="input-login" name="upw" id="upw" placeholder="password">
                            </li>
                            <li>
                                <a href="#;" onClick="javascript:login_submit();return false;" class="button blue">LOGIN</a>
                            </li>
                        </ul>
                        <div class="save-find">
                            <div class="check-box-save">
                                <div class="checkbox">
                                    <input type="checkbox" id="nologin" name="ck_auto" value="1" <?=$b2b_udirect_ss_ck_uid?"checked='checked'":""?>/>
                                    <label for="nologin"></label>
                                </div>
                                ????????? ??????<?/*?????? ?????????*/?>
                            </div>
                            <div class="find-right-area">
                                <a href="/service/member/forget.php" class="link-find">????????? ??????</a>
                                <a href="/service/member/forget.php?mode=pw" class="link-find">???????????? ??????</a>
                            </div>
                        </div>
                        <div class="join-wrap">
                            <a href="/service/member/join_mem.php" class="button gray">????????????</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>  
    </div>

<?php
/*
if ($b2b_udirect_ss_ck_auto=="b2b_udirect_ss_auto_login" && !empty($b2b_udirect_ss_ck_uid)) {
?>

<form name="autoLoginForm" method="post" action="./admin_login_act.php">
	<input type="hidden" name="mode" value="autologin" />
	<input type="hidden" name="auto_defense" value="identicharmc!@" />
    <input type="hidden" name="rtnUrl" value="<?=urlencode($rtnUrl)?>" />
    <input type="hidden" name="uid" value="<?=$b2b_udirect_ss_ck_uid?>" />
</form>

<script type="text/javascript">
document.autoLoginForm.submit();
</script>

<?php 
}
*/
?>       
        
<script src="/js/ValidCheck.js"></script>
<!-- Swiper JS -->
<script src="/js/swiper-bundle.min.js"></script>
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

    if ( VC_inValidText(f.uid, "?????????") ) return false;
    if ( f.uid.value == "?????????" ) {
    	alert("???????????? ????????? ????????????.");
    	f.uid.focus();
		return false;
    }
    if ( VC_inValidText(f.upw, "????????????") ) return false;

	f.auto_defense.value = "identicharmc!@";
	
    f.action = "./admin_login_act.php";
    f.submit();
}	

//]]>

// Initialize Swiper
var swiper = new Swiper(".mySwiper", {
    loop: true,
    pagination: {
    el: ".swiper-pagination",
    },
});
</script>

    </body>
</html>