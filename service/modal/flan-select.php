<!-- 플랜코드 변경 start -->
<div id="flan-select-modal" name="div_modal_window">
    <div class="modal-bg">
        <div class="modal-cont">
            <div class="title">
                <h2>플랜 변경</h2>
                <a class="close md-close"></a>
            </div>

            <div class="cont-wrap" id="div_modal_plan_choice">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

const get_plan_choice = function(company_type, member_no, trip_type, cal_type, plan_code) {
		
    $.ajax({
        type : "POST",
        url : "/service/ajax/get_plan_choice_ajax.php",
        data : { 'company_type' : company_type , 'member_no' : member_no, 'trip_type' : trip_type, 'cal_type' : cal_type, 'plan_code' : plan_code },
        dataType : 'html',
        async : false,
        success : function(data, status)
        {
            $('#div_modal_plan_choice').html(data);
        },
        error : function(err)
        {
            alert(err.responseText);
            return false;
        }
    });
}

</script>
<!-- 플랜코드 변경 end -->