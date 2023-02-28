<?php
require_once $_SERVER['DOCUMENT_ROOT']."/common/site_default_set.php";

require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/login/LoginManager.php";
require_once $_SERVER['DOCUMENT_ROOT']."/classes/admin/ToursafeMembersMgr.php";

include $_SERVER['DOCUMENT_ROOT']."/include/header.php";
?>
<link rel="stylesheet" type="text/css" href="/css/member.css?v=<?=time()?>">

<div class="join-box-wrap">
        <h2>파트너스 정보 수정</h2>
        <form name="writeForm" action="./join_mem_act.php" method="post">
            <input type="hidden" name="auto_defense" />
            <input type="hidden" name="mode" value="INS" />	
                <div class="basic-wrap">
                    <h3 class="fourth">기본 정보</h3>
                    <div class="id-area">
                        <strong>아이디 <i class="icon-necessary"></i></strong>
                        <input type="text" class="input-member" name="" id="" value="admin">
                    </div>
                    <ul class="clearfix inb">
                        <li>
                            <strong>비밀번호 <i class="icon-necessary"></i></strong>
                            <input type="password" class="input-member" name="" id="">
                        </li>
                        <li>
                            <strong>비밀번호 확인 <i class="icon-necessary"></i></strong>
                            <input type="password" class="input-member" name="" id="">
                        </li>
                        
                        <li>
                            <strong>회사명 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="" id="" value="(주)비아이에스">
                        </li>
                        <li>
                            <strong>사업자번호 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="" id="" value="636-87-00912" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                        </li>
                        
                        <li>
                            <strong>담당자 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="" id="" value="홍길동">
                        </li>
                        <li>
                            <strong>전화번호 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="" id="" value="1670-9010" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                        </li>
                        
                        <li>
                            <strong>휴대폰 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="" id="" value="010-2345-6789" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" pattern="[0-9]*">
                        </li>
                        <li>
                            <strong>이메일 <i class="icon-necessary"></i></strong>
                            <input type="text" class="input-member" name="" id="" value="insurance@bis.co.kr">
                        </li>
                    </ul>
                </div>

                <div class="basic-wrap rprsn">
                    <h3 class="fifth">담당자 관리</h3>
                    <div class="rprsn-area">
                        <strong>계약 담당자 추가</strong>
                        <div class="rprsn-input">
                        <input type="text" class="input-member" name="" id="" placeholder="홍길동">
                        <a href="#;" class="button blue">추가</a>
                        </div>
                        <div class="tag-wrap">
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        <span>홍길동<i class="icon-delete"></i></span>
                        </div>
                    </div>
                </div>

                <div class="center-button-area">
                    <a href="#;" class="button blue">수정하기</a>
                </div>
        </form>
    </div>

<?php
include $_SERVER['DOCUMENT_ROOT']."/include/footer.php";
?>