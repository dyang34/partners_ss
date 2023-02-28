
<!-- 보험인수 제한 국가 안내 start -->
    <div id="travel-area-modal">
        <div class="modal-bg">
            <div class="modal-cont">
				<div class="title">
					<h2>보험인수 제한 국가 안내</h2>
					<a href="#;" class="close md-close"></a>
				</div>

				<div class="cont-wrap">
					<ul class="ban-wrap">
						<li><strong>아시아</strong></li>
						<li>아프가니스탄, 이라크, 이란, 북한, 레바논, 파키스탄, 팔레스타인 자치구, 시리아, 타지키스탄, 예멘</li>
						
						<li><strong>아프리카</strong></li>
						<li>부르키나파소, 부룬디, 콩고(자이레), 중앙아프리카, 콩고, 코트디브와르, 알제리, 이집트, 기니, 리비아, 말리, 나이지리아, 수단, 시에라리온, 소말리아, 챠드, 자이레</li>
						
						<li><strong>유럽</strong></li>
						<li>우크라이나, 크림반도</li>
						
						<li><strong>북아메리카</strong></li>
						<li>쿠바, 니카라과</li>
						
						<li><strong>남아메리카</strong></li>
						<li>아이티, 베네수엘라</li>
						
						<li><strong>기타</strong></li>
						<li>
							<p>남극</p>
							<p class="type-dash">* 외교부의 여행금지대상 국가정보는 수시로 변경됩니다. 여행금지대상국가의 경우 가입이 불가하거나 또는 보상 대상에서 제외될 수 있습니다.</p>
							<a href="http://www.0404.go.kr/dev/main.mofa" class="link" target="_blank">>> 외교부 해외안전여행 여행제한 및 금지구역 확인</a>
						</li>
					</ul>
				</div>				
            </div>
        </div>
    </div>
    <script>
		$(".btn-travel-area").click(function(){
			var btn = $(this).attr("motion");
			//alert(btn);
			$("#travel-area-modal").removeAttr("class").addClass(btn);
		});

        $(".md-close").click(function(){
             $("#travel-area-modal").addClass("out");
        });
    </script>
<!-- 보험인수 제한 국가 안내 end -->