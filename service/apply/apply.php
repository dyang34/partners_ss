<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

include $_SERVER['DOCUMENT_ROOT']."/include/head.php";

print_r($_SESSION);
?>

나는야 엑셀이라네~♪<br/><br/>
<style>
    th {border: 1px solid #ddd;font-weight:bold;}
    td {border: 1px solid #ddd;}
    input {border:0px;outline: none;text-align:center;}
</style>

<button name="btnAddRow">열추가</button>
<div class="list-cont-wrap">
	<table>
		<tr>
			<td>여행시작일</td>
			<td><input type="text" name="start_date" value="<?=date('Y-m-d')?>"></td>
		</tr>
	</table>
        <table name="tbl_contract" class="table-basic" style="border-spacing:0px;border: 1px solid #ddd;">
            	<colgroup>
				<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            		<col>
            	</colgroup>
                <thead>	
                    <tr>
						<th><input type="checkbox" name="chk_All"></th>
                        <th>no</th>
						<th>이름(국문)</th>
                        <th>이름(영문)</th>
                        <th>주민등록번호</th>
                        <th style="background-color:lightgray">성별</th>
                        <th style="background-color:lightgray">나이</th>
						<th style="background-color:lightgray">구분</th>
						<th>플랜코드</th>
						<th>플랜명</th>
						<th style="background-color:lightgray">보험료</th>
                    </tr>
                </thead>
            <tbody name="tbody_nm">
                    <tr class="tr_input" tr_error_cnt="0">
						<td td_error="0"><input type="checkbox" name="chk_row"></td>
						<td td_error="0" class="td_last_obj" name="td_no"></td>
						<td td_error="0"><input type="text" class="td_last_obj"></td>
						<td td_error="0"><input type="text" class="td_last_obj"></td>
                        <td td_error="0"><input type="text" class="td_last_obj input_jumin" maxlength="13" auto_field="jumin_1"><input type="hidden" name="jumin"></td>
<?/*
                        <td td_error="0"><input type="text" class="td_last_obj input_price" auto_field="price_1"></td>
*/?>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>	
                        <td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
						<td td_error="0"><input type="text" class="td_last_obj"></td>
						<td td_error="0"><span></span><button name="btnSearchPlan">플랜 찾기</button></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
                    </tr>
					<tr class="tr_input" tr_error_cnt="0">
						<td td_error="0"><input type="checkbox" name="chk_row"></td>
						<td td_error="0" class="td_last_obj" name="td_no"></td>
						<td td_error="0"><input type="text" class="td_last_obj" name="name[]"></td>
						<td td_error="0"><input type="text" class="td_last_obj" name="name_eng[]"></td>
                        <td td_error="0"><input type="text" class="td_last_obj input_jumin" name="jumin_show[]" maxlength="13" auto_field="jumin_1"><input type="hidden" name="jumin"></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>	
                        <td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
						<td td_error="0"><input type="text" class="td_last_obj"></td>
						<td td_error="0"><span></span><button name="btnSearchPlan">플랜 찾기</button></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
                    </tr>
					<tr class="tr_input" tr_error_cnt="0">
						<td td_error="0"><input type="checkbox" name="chk_row"></td>
						<td td_error="0" class="td_last_obj" name="td_no"></td>
						<td td_error="0"><input type="text" class="td_last_obj" name="name[]"></td>
						<td td_error="0"><input type="text" class="td_last_obj" name="name_eng[]"></td>
                        <td td_error="0"><input type="text" class="td_last_obj input_jumin" name="jumin_show[]" maxlength="13" auto_field="jumin_1"><input type="hidden" name="jumin"></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>	
                        <td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
						<td td_error="0"><input type="text" class="td_last_obj"></td>
						<td td_error="0"><span></span><button name="btnSearchPlan">플랜 찾기</button></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
                    </tr>
					<tr class="tr_input" tr_error_cnt="0">
						<td td_error="0"><input type="checkbox" name="chk_row"></td>
						<td td_error="0" class="td_last_obj" name="td_no"></td>
						<td td_error="0"><input type="text" class="td_last_obj" name="name[]"></td>
						<td td_error="0"><input type="text" class="td_last_obj" name="name_eng[]"></td>
                        <td td_error="0"><input type="text" class="td_last_obj input_jumin" name="jumin_show[]" maxlength="13" auto_field="jumin_1"><input type="hidden" name="jumin"></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>	
                        <td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
						<td td_error="0"><input type="text" class="td_last_obj"></td>
						<td td_error="0"><span></span><button name="btnSearchPlan">플랜 찾기</button></td>
						<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>
                    </tr>
			</tbody>
		</table>
    </div>
    <button name="btnAddRow">열추가</button>


<script type="text/javascript">

var arr_paste_col_idx = [2,3,4];
var col_idx_jumin = 4;
const tr_contents = '<tr class="tr_input" tr_error_cnt="0">'
					+ 	'<td td_error="0"><input type="checkbox" name="chk_row"></td>'
					+ 	'<td td_error="0" class="td_last_obj" name="td_no"></td>'
					+ 	'<td td_error="0"><input type="text" class="td_last_obj" name="name[]"></td>'
					+ 	'<td td_error="0"><input type="text" class="td_last_obj" name="name_eng[]"></td>'
                    + 	'<td td_error="0"><input type="text" class="td_last_obj input_jumin" name="jumin_show[]" maxlength="13" auto_field="jumin_1"><input type="hidden" name="jumin"></td>'
					+ 	'<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>'	
                    + 	'<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>'
					+ 	'<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>'
					+ 	'<td td_error="0"><input type="text" class="td_last_obj"></td>'
					+ 	'<td td_error="0"><span></span><button name="btnSearchPlan">플랜 찾기</button></td>'
					+ 	'<td td_error="0" class="td_last_obj" style="background-color:lightgray"></td>'
            		+ '</tr>';

$(document).ready(function() {

	$(document).on('keyup', '.td_last_obj', function(e) {

		if(window.event.keyCode==13 || window.event.keyCode==40 ) {	//  || window.event.keyCode==98

			e.stopPropagation();
			e.preventDefault();    

			const obj_table = $(this).closest('table');
			const tr_last_idx = obj_table.find('tr:last').index();
			let tr_idx = $(this).closest('tr').index();
			let td_idx = $(this).closest('td').index();
			
			if( tr_idx >= tr_last_idx) {
				obj_table.append(tr_contents);
				numbering_row();
			}

			obj_table.find('tr:eq('+(tr_idx+2)+') td:eq('+(td_idx)+') input[type=text]').focus();
		} else if(window.event.keyCode==38 ) {	// || window.event.keyCode==104

			e.stopPropagation();
			e.preventDefault();    

			const obj_table = $(this).closest('table');
			const tr_first_idx = obj_table.find('tr:first').index();
			let tr_idx = $(this).closest('tr').index();
			let td_idx = $(this).closest('td').index();

			if( tr_idx > tr_first_idx) {
				obj_table.find('tr:eq('+(tr_idx)+') td:eq('+(td_idx)+') input[type=text]').focus();	
			}
		}
	});

	$(document).on('input', '.input_price', function(e) {

		e.stopPropagation();
		e.preventDefault();    

		$(this).val($(this).val().replace(/[^0-9]/g,''));
	});

	$(document).on('blur', '.input_price', function(e) {

		e.stopPropagation();
		e.preventDefault();    

		if(chk_pattern($(this).val(), 'num_comma')) {
			$(this).val($(this).val().toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
		}
	});

	$(document).on('click', '.input_price', function(e) {

		e.stopPropagation();
		e.preventDefault();    

		if(chk_pattern($(this).val(), 'num_comma')) {
			$(this).val($(this).val().replace(/[^0-9]/g,''));
		}
	});

    $(document).on('change', '.input_price', function(e){

		e.stopPropagation();
		e.preventDefault();    

		let fg_check = false;
		let val_tgt_num = "";
		const auto_field = $(this).attr('auto_field');

		fg_check = chk_row_no_error($(this), 'num_comma');
		
		switch(auto_field) {
			case 'price_1':
				if(fg_check && $(this).val()!='') {
					val_tgt_num = $(this).val().replace(/[^0-9]/g,'') * 5;
					$(this).closest('td').next().next().html(val_tgt_num.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
				} else {
					$(this).closest('td').next().next().html("");
				}
				break;
		}
		
		return false;
    });

	$(document).on('input', '.input_jumin', function(e) {

		e.stopPropagation();
		e.preventDefault();    

		$(this).val($(this).val().replace(/[^0-9]/g,''));
	});

	$(document).on('blur', '.input_jumin', function(e) {

		e.stopPropagation();
		e.preventDefault();    

		asterisk_jumin_no($(this));
	});

	$(document).on('click', '.input_jumin', function(e) {

		e.stopPropagation();
		e.preventDefault();    

		if(chk_pattern($(this).next().val(), 'jumin')) {
			$(this).val($(this).next().val());
		}
	});

	$(document).on('change', '.input_jumin', function(e){

		e.stopPropagation();
		e.preventDefault();    

		chk_jumin($(this));
		
		return false;
	});

	$(document).on('paste', 'input[type=text]', function(e) {

		const $this= $(this);
		let pasted;
		let bbbb, cccc;
		let row_idx, col, obj_tr, obj;
		let paste_error_cnt = 0;

		if (window.clipboardData && window.clipboardData.getData) {	/** ie 용 **/
			pasted = window.clipboardData.getData('Text');
		} else if (e.originalEvent.clipboardData.getData) {			/** 그 외 **/
			pasted = e.originalEvent.clipboardData.getData('text/plain');
		}

		pasted = pasted.trim('\r\n');
		bbbb = pasted.split('\r\n');
//		cccc = bbbb[0].split('\t');
		
		e.stopPropagation();
		e.preventDefault();    

		let td_idx = $this.closest('td').index();
		let tr_idx = $this.closest('tr').index();
//		var mm = $this.closest('td').length;
//		var obj = {};
		//console.log("tr Idx:"+tr_idx+"td Idx:"+td_idx+"클립보드 col수"+cccc.length);
/*		
		var limit_td = 14;
		if((td_idx+(cccc.length - 1)) > limit_td){
			alert('복사할 데이터가 셀보다 큽니다.');
			return false;
		}
*/
		
		$.each(pasted.split('\r\n'), function(idx_y, val_y) {
			$.each(val_y.split('\t'),function(idx_x, val_x) {
				row_idx = tr_idx+idx_y, col_idx = td_idx+idx_x;

				val_x = val_x.trim();
				
				if (arr_paste_col_idx.includes(col_idx)) {

                    obj_tr = $this.closest('table').find('tr:eq('+(row_idx+1)+')');
                    if(obj_tr.length === 0) {
                        $('tbody[name=tbody_nm]').append(tr_contents);
                    }

                    obj = $this.closest('table').find('tr:eq('+(row_idx+1)+') td:eq('+col_idx+') input[type=text]');
                    obj.val(val_x);
                    
					if(col_idx==col_idx_jumin) {
						if(!chk_jumin(obj) && val_x!='') {
							paste_error_cnt++;
						}

						asterisk_jumin_no(obj);
					}
				}
			});
		});

/*		
		if(paste_error_cnt > 0){
			alert('적합하지않는 주민번호가 있습니다.');
			return false;
		}    
*/
		numbering_row();
	});

	$(document).on('change', 'input[name=start_date]', function(e){

		e.stopPropagation();
		e.preventDefault();    

		$('table[name=tbl_contract] tr').each(function(index, item) {
			if(index>0) {

				obj = $(this).find('input[name="jumin_show[]"]');
				if (obj.val()) {
					chk_jumin(obj);
				}
			}
		});
		
		return false;
	});



	numbering_row();
});

const asterisk_jumin_no = function(obj) {
	let jumin_no = obj.val();

	if(chk_pattern(obj.val(), 'jumin')) {
		jumin_no = obj.val().toString().replace(/[^0-9]/g,'').replace(/([\d|*]{6})([\d|*]+)/, '$1$2');
		obj.val(jumin_no.replace(jumin_no, jumin_no.replace(/(-?)([1-4]{1})([0-9]{6})\b/gi, '$1$2******')));
	}

	obj.next().val(jumin_no);
}

const chk_jumin = function(obj) {
	let fg_check = false;
	let val_tgt_num = "", birthday, gender, age, age_type;
	let start_date = $('input[name=start_date]').val();
	const auto_field = obj.attr('auto_field');
	const value_len = obj.val().length;
	
	fg_check = chk_row_no_error(obj, 'jumin');
	
	if(fg_check && value_len < 13 && value_len > 0) {
		obj.val(obj.val()+"1".repeat(13-value_len));
	}

	switch(auto_field) {
		case 'jumin_1':
			if(fg_check && value_len > 0) {

				gender = obj.val().substring(6,7);
				birthday = ((gender=="1"||gender=="2"||gender=="5"||gender=="6")?"19":"20")+obj.val().substring(0,2)+'-'+obj.val().substring(2,4)+'-'+obj.val().substring(4,6);

				age = getInsuAge(start_date, birthday);
//					age_type = 

				if (gender%2==0) {
					obj.closest('td').next().html("여성");
				} else {
					obj.closest('td').next().html("남성");
				}
				
				obj.closest('td').next().next().html(age);
				obj.closest('td').next().next().next().html(val_tgt_num.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ","));
			} else {
				obj.closest('td').next().html("");
				obj.closest('td').next().next().html("");
				obj.closest('td').next().next().next().html("");
			}

			break;
	}

	return fg_check;
}

const chk_row_no_error = function(obj, chk_type) {

	const obj_td = obj.closest('td');
	const obj_tr = obj_td.closest('tr');
	const td_error_origin = obj_td.attr('td_error');
	const tr_error_cnt_origin = obj_tr.attr('tr_error_cnt');

	let tr_error_cnt = Number(tr_error_cnt_origin);
	let fg_no_error = true;

	if(obj.val()) {
		fg_no_error = chk_pattern(obj.val(), chk_type);
	}

	if(fg_no_error) {
		obj_td.attr('td_error','0');
	} else {
		obj_td.attr('td_error','1');
	}

	tr_error_cnt = tr_error_cnt-Number(td_error_origin)+Number(obj_td.attr('td_error'));
	obj_tr.attr('tr_error_cnt', tr_error_cnt);

	if(tr_error_cnt < 1 && tr_error_cnt_origin > 0) {
		obj_tr.find('.td_last_obj').each(function(i){
			$(this).css("color","black");
		});
	} else if(tr_error_cnt > 0 && tr_error_cnt_origin < 1) {
		obj_tr.find('.td_last_obj').each(function(i){
			$(this).css("color","red");
		});
	}

	return fg_no_error;
}

const numbering_row = function() {
	$('table[name=tbl_contract] tr').each(function(index, item) {
		if(index>0) {
			$(this).children('td:eq(1)').html(index);
		}
	});
}

$(document).on('click','button[name=btnSearchPlan]', function() {
    alert("눌러도 소용이 없네.. 테스트니까..");
});

$(document).on('click','button[name=btnAddRow]', function() {
    $('tbody[name=tbody_nm]').append(tr_contents);
});

$(document).on('change','input[name=chk_All]', function(e) {
	$('input[name=chk_row]').prop('checked', $(this).prop('checked'));
});

</script>