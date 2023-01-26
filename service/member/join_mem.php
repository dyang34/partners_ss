<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";
?>

<form name="writeForm" action="./join_mem_act.php" method="post">
	<input type="hidden" name="auto_defense" />
	<input type="hidden" name="mode" value="INS" />

        <table name="tbl_contract" class="table-basic" style="border-spacing:0px;border: 1px solid #ddd;">
			<colgroup>
				<col>
				<col>
			</colgroup>
            <tbody name="tbody_nm">
				<tr class="tr_input" tr_error_cnt="0">
					<td>아이디</td>
					<td><input type="text" name="uid"><a href="#" name="btnChkUid">중복체크</a></td>
                </tr>
				<tr class="tr_input" tr_error_cnt="0">
					<td>비번</td>
					<td><input type="text" name="upw"><input type="text" name="upw_cfm"></td>
                </tr>
				<tr class="tr_input" tr_error_cnt="0">
					<td>회사명</td>
					<td><input type="text" name="com_name"></td>
                </tr>
				<tr class="tr_input" tr_error_cnt="0">
					<td>사업자번호</td>
					<td><input type="text" name="com_no"></td>
                </tr>
				<tr class="tr_input" tr_error_cnt="0">
					<td>담당자</td>
					<td><input type="text" name="manager"></td>
                </tr>
				<tr class="tr_input" tr_error_cnt="0">
					<td>전화번호</td>
					<td><input type="text" name="hphone"></td>
                </tr>
				<tr class="tr_input" tr_error_cnt="0">
					<td>휴대폰</td>
					<td><input type="text" name="hphone2"></td>
                </tr>
				<tr class="tr_input" tr_error_cnt="0">
					<td>이메일</td>
					<td><input type="text" name="email"></td>
                </tr>
				<tr class="tr_input" tr_error_cnt="0">
					<td>동의</td>
					<td><input type="checkbox" name="chk1" value="Y">동의1<br/><input type="checkbox" name="chk2" value="Y">동의2</td>
                </tr>

			</tbody>
		</table>

		<div class="button-center">
            <a href="#" name="btnSave" class="button line-basic large">저장</a>
        </div>

</form>
			
<script src="/js/ValidCheck.js?t=<?=filemtime($_SERVER['DOCUMENT_ROOT']."/js/ValidCheck.js")?>"></script>	
<script type="text/javascript">
let mc_consult_submitted = false;
const reg_engnum = /^[A-Za-z0-9+\d$@$!%*#?&]{4,20}$/;

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
	if ( VC_inValidText(f.manager, "담당자") ) return false;
	if ( VC_inValidText(f.hphone, "전화번호") ) return false;
	if ( VC_inValidText(f.email, "이메일") ) return false;

	if(!chk_uid(false)) {
		return false;
	}

	if(!$('input[name=chk1]').is(':checked')) {
		alert('첫번째 체크해줭');
		return false;
	}
	
	if(!$('input[name=chk2]').is(':checked')) {
		alert('en번째 체크해줭');
		return false;
	}

	f.auto_defense.value = "identicharmc!@";
	mc_consult_submitted = true;

    f.submit();	

    return false;
});

let chk_uid = function(p_alert) {
	let rtnVal;

	$.ajax({
		url: '/service/member/chk_user_id_ajax.php',
		type: 'POST',
		dataType: "json",
		async: false,
		cache: false,
		data: {
			uid : $('input[name=uid]').val()
		},
		success: function (response) {
			if(response.RESULTCD != 'SUCCESS') {
				alert(response.RESULTMSG);
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

$(document).on('click','a[name=btnChkUid]', function() {
	if($('input[name=uid]').val().trim()=="") {
		alert("아이디를 입력해 주십시오.    ");
		$('input[name=uid]').focus();
		return false;
	}
	
	chk_uid(true);

	return false;
});
</script>	