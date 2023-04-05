<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<link rel="stylesheet" type="text/css" href="/css/member.css?v=<?=time()?>">
<body id="wrap">
    <div class="join-box-wrap">
        <h2>파트너스 가입</h2>
        <form name="writeForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="auto_defense" />
            <input type="hidden" name="mode" value="INS" />
            <div class="basic-wrap">
                <h3 class="fourth">기본 정보
                    <div class="necessary"> <i class="icon-necessary"></i> 표기된 항목은 필수 입력 사항입니다.</div>
                </h3>
                <div class="id-area">
                    <strong>아이디 <i class="icon-necessary"></i></strong>
                    <input type="text" class="input-member" name="uid" placeholder="아이디 입력">
                </div>
                <ul class="clearfix inb">
                    <li>
                        <strong>비밀번호 <i class="icon-necessary"></i></strong>
                        <input type="password" class="input-member" name="upw" placeholder="비밀번호 입력">
                    </li>
                    <li>
                        <strong>비밀번호 확인 <i class="icon-necessary"></i></strong>
                        <input type="password" class="input-member" name="upw_cfm" placeholder="비밀번호 확인">
                    </li>
                    
                    <li>
                        <strong>회사명 <i class="icon-necessary"></i></strong>
                        <input type="text" class="input-member" name="com_name" placeholder="회사명 입력">
                    </li>
                    <li>
                        <strong>사업자번호 <i class="icon-necessary"></i></strong>
                        <input type="text" class="input-member" name="com_no" placeholder="숫자만 입력하세요." maxlength="10" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                    </li>
                </ul>
                <div class="bsnsLic-area">
                    <strong>사업자 등록증 <i class="icon-necessary"></i></strong>
                    <div class="add-file">
                        <input type="file" name="file_name" id="addfile" class="add-file-input class_img" val1="2" val2="2">
                        <div class="add-file-txt"></div>
                        <label for="addfile"><div class="add-file-btn">찾아보기</div></label>
                    </div>
                </div>
<?/*
                        <a href="/lib/download.php?file_name=<?=urlencode('/home/data/biz_license/20211022094901.png')?>&file_real_name=<?=urlencode('오모.png')?>" target="_blank">다운로드</a>
                        <a href="/lib/download.php?file_name=<?=urlencode('/home/data/biz_license/Z2023032316795529681.pdf')?>&file_real_name=<?=urlencode('오 모 abcd 1234.pdf')?>" target="_blank">다운로드</a>
*/?>
                <ul class="clearfix inb">
                    <li>
                        <strong>관리자 <i class="icon-necessary"></i></strong>
                        <input type="text" class="input-member" name="manager_name" placeholder="실명 입력">
                    </li>
                    <li>
                        <strong>전화번호 <i class="icon-necessary"></i></strong>
                        <input type="text" class="input-member" name="hphone" placeholder="숫자만 입력하세요." onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                    </li>
                    
                    <li>
                        <strong>휴대폰 <i class="icon-necessary"></i></strong>
                        <input type="text" class="input-member" name="hphone2" placeholder="숫자만 입력하세요." onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                    </li>
                    <li>
                        <strong>이메일 <i class="icon-necessary"></i></strong>
                        <input type="text" class="input-member" name="email" placeholder="hong@udirect.co.kr">
                    </li>
                </ul>
        
                <h3 class="privacy fourth">이용약관
                    <div class="checkbox-wrap">
                        <div class="checkbox">
                            <input type="checkbox" id="chk1" name="chk1" value="Y" />
                            <label for="chk1"></label>
                        </div>
                        <span>동의합니다.</span>               
                    </div>
                </h3>
                <div class="terms-box">
                    <?php include 'terms.php'; ?>
                </div>
                
                <h3 class="privacy">개인정보 수집 및 이용목적
                    <div class="checkbox-wrap">
                        <div class="checkbox">
                            <input type="checkbox" id="chk2" name="chk2" value="Y"/>
                            <label for="chk2"></label>
                        </div>
                        <span>동의합니다.</span>               
                    </div>
                </h3>
                <div class="terms-box">
                    <?php include 'privacy.php'; ?>
                </div>
            </div>
            
            <div class="center-button-area">
                <a name="btnSave" class="button blue">가입하기</a>
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
        if ( VC_inValidText(f.upw, "패스워드") ) return false;
        //var reg_engnum = /^[A-Za-z0-9+]{4,20}$/;
        
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

        if ( VC_inValidText(f.com_name, "회사명") ) return false;
        if ( VC_inValidText(f.com_no, "사업자번호") ) return false;
        if ( VC_inValidText(f.file_name, "사업자 등록증") ) return false;
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

        if(!$('input[name=chk1]').is(':checked')) {
            alert('이용약관에 동의해 주십시오.    ');
            return false;
        }

        if(!$('input[name=chk2]').is(':checked')) {
            alert('개인정보 수집 및 이용목적에 동의해 주십시오.    ');
            return false;
        }

        if(!chk_uid(false)) {
            return false;
        }

        f.action = "./join_mem_act.php";
        f.auto_defense.value = "identicharmc!@";
        mc_consult_submitted = true;

        f.submit();	

        return false;
    });
});

const chk_uid = function(p_alert) {
	let rtnVal;

	$.ajax({
		url: '/service/ajax/chk_user_id_ajax.php',
		type: 'POST',
		dataType: "json",
		async: false,
		cache: false,
		data: {
			uid : $('input[name=uid]').val()
		},
		success: function (data, status)
        {
			if(data.RESULTCD != '200') {
				alert(data.RESULTMSG);
				$('input[name=uid]').focus();

				rtnVal = false;
            } else {
				if(p_alert) {
					alert("사용 가능한 아이디입니다.");
				}

				rtnVal = true;
			}
		},
		complete:function(){},
		error: function(xhr){}
	});

	return rtnVal;
}
/*
$(document).on('click','a[name=btnChkUid]', function() {
	if($('input[name=uid]').val().trim()=="") {
		alert("아이디를 입력해 주십시오.    ");
		$('input[name=uid]').focus();
		return false;
	}
	
	chk_uid(true);

	return false;
});
*/

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
?>