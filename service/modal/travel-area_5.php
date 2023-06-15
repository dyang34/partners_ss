
<!-- 보험인수 제한 국가 안내 start -->
    <div id="travel-area-modal">
        <div class="modal-bg">
            <div class="modal-cont">
				<div class="title">
					<h2>보험인수 제한 국가 안내</h2>
					<a href="#;" class="close md-close"></a>
				</div>

				<div class="cont-wrap">
					<ul class="clearfix inb restrict">
						<li>
							<strong>아시아</strong>
							<div class="box-conts">
								아프가니스탄, 이라크, 이란, 북한, 레바논, 파키스탄, 팔레스타인 자치구, 시리아, 타지키스탄, 예멘
							</div>
						</li>
						<li>
							<strong>아프리카</strong>
							<div class="box-conts">
								부르키나파소, 부룬디, 콩고(자이레), 중앙아프리카, 콩고, 기니, 리비아, 말리, 수단, 시에라리온, 소말리아, 챠드, 자이레
							</div>
						</li>

						<li>
							<strong>유럽</strong>
							<div class="box-conts">
								우크라이나, 크림반도
							</div>
						</li>
						<li>
							<strong>북아메리카</strong>
							<div class="box-conts">
								쿠바, 니카라과
							</div>
						</li>

						<li>
							<strong>남아메리카</strong>
							<div class="box-conts">
								아이티, 베네수엘라
							</div>
						</li>
						<li>
							<strong>기타</strong>
							<div class="box-conts">
								남극
							</div>
						</li>
					</ul>

					<div class="beware">
						<p>* 외교부의 여행금지대상 국가정보는 수시로 변경됩니다.</p>
						<p>* 여행금지대상국가의 경우 가입이 불가하거나 또는 보상 대상에서 제외될 수 있습니다.</p>
						<p>* 외교부 해외안전여행 2단계까지 가입이 가능합니다. 그 외 지역은 연락바랍니다.</p>
					</div>

					<div class="btn-bottom">
						<a href="http://www.0404.go.kr/dev/main.mofa" class="link" target="_blank">외교부 해외안전여행 여행제한 및 금지구역 확인</a>
					</div>
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