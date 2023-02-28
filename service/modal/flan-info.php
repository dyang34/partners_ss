<!-- 플랜 정보 start -->
<div id="flan-info-modal" name="div_modal_window">
	<div class="modal-bg">
		<div class="modal-cont">
			<div class="title">
				<h2>플랜 정보</h2>
				<a class="close md-close"></a>
			</div>

			<div class="cont-wrap" id="div_modal_plan_desc">
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

const get_plan_desc = function(company_type, member_no, trip_type, cal_type) {		
	$.ajax({
		type : "POST",
		url : "/service/ajax/get_plan_desc_ajax.php",
		data : { 'company_type' : company_type , 'member_no' : member_no, 'trip_type' : trip_type },
		dataType : 'html',
		async : false,
		success : function(data, status)
		{
			$('#div_modal_plan_desc').html(data);
			$('input[name=rd_cal_type][value='+cal_type+']').trigger('click');
		},
		error : function(err)
		{
			alert(err.responseText);
			return false;
		}
	});
}

</script>
<!-- 플랜 정보 end -->