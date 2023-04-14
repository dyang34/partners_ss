<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/JsUtil.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

$p1 = RequestUtil::getParam("p1","");

if(empty($p1)) {
    JsUtil::alertReplace("비정상적인 접근입니다. (Error Code : 0x01)   ","/");
    exit;
}

$p1 = trim(decode_pass($p1,$pass_key));

$arrParam = explode('|',$p1);

$wq = new WhereQuery(true, true);
$wq->addAndString("uid","=",$arrParam[0]);
$row = ToursafeMembersMgr::getInstance()->getFirst($wq);

if(empty($row)) {
    JsUtil::alertReplace("비정상적인 접근입니다. (Error Code : 0x02)   ","/");
    exit;
}

$expire_date = date('YmdHis', strtotime('-1 day'));

if($expire_date > $arrParam[1]) {
    JsUtil::alertReplace("해당 비밀번호 재설정 URL은\\r\\n24시간이 경과하여 더 이상 유효하지 않습니다.\\r\\n\\r\\n[비밀번호 찾기]를 다시 이용해 주십시오.   ","/");
    exit;
}

if($row["upw"] != $arrParam[2]) {
    JsUtil::alertReplace("이미 비밀번호를 변경한 이력이 있습니다.\\r\\n\\r\\n재변경을 원할시 [비밀번호 찾기]를 다시 이용해 주십시오.   ","/");
    exit;
}

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<link rel="stylesheet" type="text/css" href="/css/member.css?v=<?=time()?>">

<div class="reset-pw-wrap">
    <div class="cont-wrap">
        <h2>비밀번호 재설정</h2>

        <form name="writeForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="auto_defense" />
            <input type="hidden" name="mode" value="UPD_PW" />
            <input type="hidden" name="uid" value="<?=$arrParam[0]?>" />

            <div class="reset-conts">
                <div class="input-area">
                    <strong>새로운 비밀번호</strong>
                    <input type="password" class="input-member" name="upw" placeholder="비밀번호 입력">
                </div>
                
                <div class="input-area">
                    <strong>새로운 비밀번호 확인</strong>
                    <input type="password" class="input-member" name="upw_cfm" placeholder="비밀번호 입력">
                </div>
                    
                <div class="center-button-area">
                    <a name="btnSave" class="button blue">비밀번호 변경</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

let mc_consult_submitted = false;
const reg_engnum = /^[A-Za-z0-9+\d$@$!%*#?&]{4,20}$/;

$(document).ready(function() {
    $(document).on("click","a[name=btnSave]",function() {
        if(mc_consult_submitted == true) { return false; }
        
        let f = document.writeForm;

        if (!reg_engnum.test(f.upw.value)) {
            alert("패스워드는 숫자와 영문, 일부 특수문자($@$!%*#?&)만 가능하며, 4~20자리여야 합니다.    ");
            f.upw.focus();
            return;
        }

        if (f.upw.value != f.upw_cfm.value) {
            alert("패스워드 확인이 일치하지 않습니다.    ");
            f.upw_cfm.focus();
            return false;
        }

        f.action = "./join_mem_act.php";
        f.auto_defense.value = "identicharmc!@";
        mc_consult_submitted = true;

        f.submit();	

        return false;
    });
});
</script>
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>