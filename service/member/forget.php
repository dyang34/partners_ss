<?php
require_once $_SERVER['DOCUMENT_ROOT']."/include/common.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/util/RequestUtil.php";

$mode = RequestUtil::getParam("mode","");

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<link rel="stylesheet" type="text/css" href="/css/member.css?v=<?=time()?>">

<div class="forget-box-wrap">
    <input type="hidden" name="auto_defense" />
    <div class="box-wrap">
        <input class="radio" id="id-find" name="group" type="radio" <?=$mode!="pw"?"checked='checked'":""?>>
        <input class="radio" id="pw-find" name="group" type="radio" <?=$mode=="pw"?"checked='checked'":""?>>

        <div class="tab-menu-wrap">
            <label class="tab" id="id-find-tab" for="id-find">아이디 찾기</label>
            <label class="tab" id="pw-find-tab" for="pw-find">비밀번호 찾기</label>
        </div>

        <div class="panels">
            <!-- 아이디 찾기 start -->
            <form name="findIDForm">
                <div class="panel" id="id-find-panel">
                    <div name="divFindId">
                        <div class="id-area">
                            <strong>기업명</strong>
                            <input type="text" class="input-member" name="com_name" placeholder="기업명">
                        </div>
                        <div class="email-area">
                            <strong>이메일주소</strong>
                            <input type="text" class="input-member" name="email" placeholder="이메일주소">
                        </div>
                    
                        <div class="center-button-area">
                            <a name="btnFindID" class="button blue">확인</a>
                        </div>
                    </div>
                    <div name="divResult" style="display:none;">
                        <div class="id-find-finish">
                            <span>아이디 조회 결과 입력하신 정보와 일치하는 아이디는 아래와 같습니다.</span>
                            <strong id="strong_find_id">ss_b2b</strong>
                        </div>
                        <div class="center-button-area">
                            <a href="/" class="button blue">로그인 </a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- 아이디 찾기 end -->
            
            <!-- 비밀번호 찾기 start -->
            <form name="findPWForm">
                <div class="panel" id="pw-find-panel">
                    <div class="id-area">
                        <strong>아이디</strong>
                        <input type="text" class="input-member" name="uid" placeholder="아이디">
                    </div>
                    <div class="id-area">
                        <strong>기업명</strong>
                        <input type="text" class="input-member" name="com_name" placeholder="기업명">
                    </div>
                    <div class="email-area">
                        <strong>이메일주소</strong>
                        <input type="text" class="input-member" name="email" placeholder="이메일주소">
                    </div>
                    
                    <div class="center-button-area">
                        <a name="btnFindPW" class="button blue">확인</a>
                    </div>
                </div>
            </form>
            <!-- 비밀번호 찾기 end -->
        </div>
    </div>
</div>

<script src="/js/ValidCheck.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', 'a[name=btnFindID]', function() {
            let f = document.findIDForm;

            if ( VC_inValidText(f.com_name, "기업명") ) return false;
            if ( VC_inValidText(f.email, "이메일") ) return false;

            $.ajax({
                type : "POST",
                url : "/service/ajax/find_user_id_ajax.php",
                data : { 'com_name' : f.com_name.value , 'email' : f.email.value },
                dataType : 'json',
                async : false,
                success : function(data, status)
                {
                    if(data.RESULTCD == "200") {
                        $('#strong_find_id').html(data.RESULTMSG);
                        $('div[name=divFindId').hide();
                        $('div[name=divResult').show();
                    } else {
                        alert(data.RESULTMSG);
                    }
                },
                error : function(err)
                {
                    alert(err.responseText);
                    return -1;
                }
            });
        });

        $(document).on('click', 'a[name=btnFindPW]', function() {
            let f = document.findPWForm;

            if ( VC_inValidText(f.uid, "아이디") ) return false;
            if ( VC_inValidText(f.com_name, "기업명") ) return false;
            if ( VC_inValidText(f.email, "이메일") ) return false;

            $.ajax({
                type : "POST",
                url : "/service/ajax/find_user_pw_ajax.php",
                data : { 'uid' : f.uid.value , 'com_name' : f.com_name.value , 'email' : f.email.value },
                dataType : 'json',
                async : false,
                success : function(data, status)
                {
                    if(data.RESULTCD == "200") {
                        alert(f.email.value+"로 비밀번호 재설정 URL을 전송하였습니다.\r\n\r\n메일을 확인하시기 바랍니다.");
                    } else {
                        alert(data.RESULTMSG);
                    }
                },
                error : function(err)
                {
                    alert(err.responseText);
                    return -1;
                }
            });
        });

    });
</script>
<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>