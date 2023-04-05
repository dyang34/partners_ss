<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/WhereQuery.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersManagerMgr.php";

if (!LoginManager::isUserLogined()) {
    //    JsUtil::alertBack("비정상적인 접근입니다. (ErrCode:0x05)    ");
    JsUtil::alertReplace("로그인이 필요합니다.    ","/");
}

$__CONFIG_COMPANY_TYPE = LoginManager::getUserLoginInfo("company_type");
$__CONFIG_MEMBER_NO = LoginManager::getUserLoginInfo("no");

$wq = new WhereQuery(true, true);
$wq->addAndString("no","=",$__CONFIG_MEMBER_NO);
$wq->addAndString("uid","=",LoginManager::getUserLoginInfo("uid"));
$row = ToursafeMembersMgr::getInstance()->getFirst($wq);

$wq = new WhereQuery(true, true);
$wq->addAndString("uid","=",LoginManager::getUserLoginInfo("uid"));
$wq->addAndString2("fg_del", "=", "0");
$wq->addOrderBy("sort","asc");
$rs_manager = ToursafeMembersManagerMgr::getInstance()->getList($wq);

$arrManager = array();
if ($rs_manager->num_rows > 0) {
    for($i=0;$i<$rs_manager->num_rows;$i++) {
        $row_manager = $rs_manager->fetch_assoc();

        array_push($arrManager, $row_manager);
    }
}

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<link rel="stylesheet" type="text/css" href="/css/member.css?v=<?=time()?>">

<div class="join-box-wrap">
        <h2>파트너스 정보 수정</h2>
        <form name="writeForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="auto_defense" />
            <input type="hidden" name="mode" value="UPD" />	
                <div class="basic-wrap">
                    <h3 class="fourth">기본 정보</h3>
                    <div class="id-area">
                        <strong>아이디</strong>
                        <input type="text" class="input-member" name="uid" value="<?=$row["uid"]?>" readonly>
                    </div>
                    <ul class="clearfix inb">
                        <li>
                            <strong>비밀번호</strong>
                            <input type="password" class="input-member" name="upw">
                        </li>
                        <li>
                            <strong>비밀번호 확인</strong>
                            <input type="password" class="input-member" name="upw_cfm">
                        </li>
                        
                        <li>
                            <strong>회사명 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="com_name" value="<?=$row["com_name"]?>">
                        </li>
                        <li>
                            <strong>사업자번호 (숫자만 입력) <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="com_no" maxlength="10" value="<?=$row["com_no"]?>" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                        </li>
                    </ul>
                    <div class="bsnsLic-area">
                        <strong>사업자 등록증</strong>
                        <div class="add-file">
                            <input type="file" name="file_name" id="addfile" class="add-file-input class_img" val1="2" val2="2">
                            <div class="add-file-txt"></div>
                            <label for="addfile"><div class="add-file-btn">찾아보기</div></label>
                        </div>
<?php
if(!empty($row["file_name"])) {
?>
                        <a href="/lib/download.php?file_name=<?=urlencode($row["file_name"])?>&file_real_name=<?=urlencode($row["file_real_name"])?>" target="_blank" class="dwon-buisn">이미 등록된 사업자 등록증</a>
<?
}
?>                        
                    </div>
                    <ul class="clearfix inb">
                        <li>
                            <strong>관리자 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="manager_name" value="<?=$row["manager_name"]?>">
                        </li>
                        <li>
                            <strong>전화번호 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="hphone" value="<?=decode_pass($row["hphone"],$pass_key)?>" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                        </li>
                        <li>
                            <strong>휴대폰 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="hphone2" value="<?=decode_pass($row["hphone2"],$pass_key)?>" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                        </li>
                        <li>
                            <strong>이메일 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="email" value="<?=decode_pass($row["email"],$pass_key)?>">
                        </li>
                    </ul>
                </div>

                <div class="basic-wrap rprsn">
                    <h3 class="fifth">담당자 관리</h3>
                    <div class="rprsn-area">
                        <strong>계약 담당자 추가</strong>
                        <div class="rprsn-input">
                        <input type="text" class="input-member" name="manager_name_add" placeholder="최대 6글자" maxlength="6" onKeypress="javascript:if(event.keyCode==13) {addManager();return false;}">
                        <a name="addManager" class="button blue">추가</a>
                        </div>
                        <div class="tag-wrap" id="divManager">
<?php
    for($i=0;$i<count($arrManager);$i++) {
?>                            
                        <span><input type="text" value="<?=$arrManager[$i]["name"]?>" class="input_manager_name"><i name="btn_manager_del_exists" manager_name="<?=$arrManager[$i]["name"]?>" class="icon-delete"></i></span>
<?php
    }
?>
                        </div>
                    </div>
                </div>

                <div class="center-button-area">
                    <a name="btnSave" class="button blue">수정하기</a>
                </div>
        </form>
    </div>
    <script src="/js/ValidCheck.js?t=<?=filemtime($_SERVER['DOCUMENT_ROOT']."/js/ValidCheck.js")?>"></script>	
<script type="text/javascript">
let mc_consult_submitted = false;
const reg_engnum = /^[A-Za-z0-9+\d$@$!%*#?&]{4,20}$/;

$(document).ready(function() {
    $(document).on("click","a[name=btnSave]",function() {
        if(mc_consult_submitted == true) { return false; }
        
        let f = document.writeForm;

        if ( VC_inValidText(f.uid, "ID") ) return false;
        
        if(f.upw.value != "") {
            if (!reg_engnum.test(f.upw.value)) {
                alert("패스워드는 숫자와 영문, 일부 특수문자($@$!%*#?&)만 가능하며, 4~20자리여야 합니다.    ");
                f.upw.focus();
                return;
            }
        }

        if (f.upw.value != f.upw_cfm.value) {
            alert("패스워드 확인이 일치하지 않습니다.    ");
            f.upw_cfm.focus();
            return false;
        }

        if ( VC_inValidText(f.com_name, "회사명") ) return false;
        if ( VC_inValidText(f.com_no, "사업자번호") ) return false;
        if ( VC_inValidText(f.manager_name, "관리자") ) return false;

        if ( VC_inValidText(f.hphone, "전화번호") ) return false;
        if(f.hphone.value.length < 7) {
            alert("전화번호를 확인해 주십시오.");
            f.hphone.focus();
            return false;
        }

        if ( VC_inValidText(f.hphone2, "휴대폰") ) return false;
        if(!chk_pattern(f.hphone2.value, 'hp')) {
            alert("휴대폰번호를 확인해 주십시오.");
            f.hphone2.focus();
            return false;
        }

        if ( VC_inValidText(f.email, "이메일") ) return false;
        if(!chk_pattern(f.email.value, "email")) {
            alert("이메일 형식이 일치하지 않습니다.    ");
            f.email.focus();
            return false;
        }

        f.action = "./join_mem_act.php";
        f.auto_defense.value = "identicharmc!@";
        mc_consult_submitted = true;

        f.submit();	

        return false;
    });

    $(document).on('click','i[name=btn_manager_del_exists]',function() {
        let manager_name = $(this).attr('manager_name');

        $('#divManager').prepend("<input type='hidden' name='manager_name_del[]' value='"+manager_name+"'/>");
        $(this).closest("span").remove();
    });


    $(document).on('click','i[name=btn_manager_del]',function() {
        $(this).closest("span").remove();
    });

    $(document).on('click','a[name=addManager]',function() {
        addManager();
    });
});

const addManager = function() {
    let l_manager_name = $.trim($('input[name=manager_name_add]').val());
    let l_exist = false;

    if(l_manager_name=="") {
        alert("담당자를 입력해 주십시오.    ");
        $('input[name=manager_name_add]').focus();
        return false;
    }

    $('.input_manager_name').each(function(index, obj) {
        if($(this).val()==l_manager_name) {
            alert("이미 등록되어 있는 담당자입니다.    ");
            l_exist = true;
        }
    });

    if(!l_exist) {
        $('#divManager').append("<span><input type=\"text\" name=\"manager_name_add[]\" class=\"input_manager_name\" value=\""+l_manager_name+"\"><i name=\"btn_manager_del\" class=\"icon-delete\"></i></span>");
        $('input[name=manager_name_add]').val("");
        $('input[name=manager_name_add]').focus();
    }
}

// finle upload
var fileinput = document.querySelector(".add-file-input"),
    button = document.querySelector(".add-file-btn"),
    returntxt = document.querySelector(".add-file-txt");

button.addEventListener("keydown", function(event){
    if(event.keyCode == 13 || event.keyCode == 32){
    fileinput.focus();
    }
});
button.addEventListener("click", function(event){
    fileinput.focus();
    return false;
});
fileinput.addEventListener("change", function(event){
    returntxt.innerHTML = this.value;
});
</script>
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";

@$rs->free();
?>