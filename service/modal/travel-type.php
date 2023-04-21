
<!-- 여행 종류 start -->
    <div id="travel-type-modal">
        <div class="modal-bg">
            <div class="modal-cont">
				<div class="title">
					<h2>해외여행 시 유의 사항</h2>
					<a href="#;" class="close md-close"></a>
				</div>

				<div class="cont-wrap">
					<p class="first">※ 이중국적자, 외국인의 경우 모국으로 여행은 가입 불가합니다.</p>
					<p>※ 가입 시점의 현재 체류지가 국내인 경우에만 가입이 가능합니다.</p>
				</div>				
            </div>
        </div>
    </div>
    <script>
        $(".btn-travel-type").click(function(){
            var btn = $(this).attr("motion");
            //alert(btn);
            $("#travel-type-modal").removeAttr("class").addClass(btn);
        });

        $(".md-close").click(function(){
             $("#travel-type-modal").addClass("out");
        });
    </script>

<!-- 여행 종류 end -->