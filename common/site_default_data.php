<?php
$pass_key = "ertogmdr5nhpr9e5nn5n84";

$arrInsuranceCompany = [
    1=>"CHUBB"
    ,2=>"메리츠화재"
    ,3=>"MG손해보험"
    ,4=>"DB손해보험"
    ,5=>"삼성화재"
    ,6=>"현대해상"
];

$arrTripType = [
    1=>"국내여행",
    2=>"해외여행",
    3=>"해외장기체류"
];

$arrCalTypeTitle = [[],["일반"],["주니어","성인"],["주니어","성인","시니어"],["주니어","성인","시니어1","시니어2"],["주니어","성인","시니어1","시니어2","시니어3"]];

$arrPlanStateText = [
//    1=>"결제완료"
    1=>"청약완료"
    ,2=>"취소접수"
    ,3=>"취소완료"
    ,4=>"수정접수"
    ,5=>"수정완료"
    ,6=>"청약완료"
    ,7=>"청약대기1"
    ,8=>"청약대기2"
    ,9=>"청약대기3"
];

$arrPlanChageStateText = [
    1=>"처리중"
    ,2=>"수정완료"
    ,3=>"취소완료"
];

$arrTripPurpose = [
    1=>"여행/관광"
    ,2=>"연수/출장"
];

$arrTripPurpose3 = [
    1=>"유학/연수"
    ,2=>"주재원"
    ,3=>"업무/출장"
    ,4=>"워킹홀리데이"
    ,5=>"동반"
];

$arrJob = [
    1=>"사무직"
    ,2=>"학생"
    ,3=>"미취학아동"
    ,4=>"주부"
];

$arrPlanStateUpdatable = [1,2,4,5,6];

$default_plan_join_code_fix = [
    2=>[1=>'15920-119', 2=>'15540-761']
    ,3=>[2=>'2022-0232120']
    ,5=>[1=>'82370000075665', 2=>'82370000077185']
];

$arrBank = [
    60=>'BOA 은행'
    ,54=>'HSBC 은행'
    ,10=>'NH 농협은행'
    ,23=>'SC 제일은행'
    ,39=>'경남은행'
    ,34=>'광주은행'
    ,66=>'교통은행'
    ,4=>'국민은행'
    ,77=>'기술보증기금'
    ,3=>'기업은행'
    ,31=>'대구은행'
    ,65=>'대화은행'
    ,55=>'도이치은행'
    ,52=>'모간스탠리은행'
    ,58=>'미즈호 은행'
    ,32=>'부산은행'
    ,61=>'비엔피파리바은행'
    ,64=>'산림조합중앙회'
    ,2=>'산업은행'
    ,45=>'새마을금고'
    ,8=>'수출입은행'
    ,7=>'수협은행'
    ,76=>'신용보증기금'
    ,21=>'신한은행'
    ,47=>'신협'
    ,59=>'엠유에프지은행'
    ,20=>'우리은행'
    ,71=>'우체국'
    ,50=>'저축은행'
    ,37=>'전북은행'
    ,57=>'제이피모간체이스은행'
    ,35=>'제주은행'
    ,67=>'중국건설은행'
    ,62=>'중국공상은행'
    ,63=>'중국은행'
    ,12=>'지역 농축협'
    ,90=>'카카오뱅크'
    ,89=>'케이뱅크'
    ,92=>'토스뱅크'
    ,5=>'하나은행'
    ,27=>'한국씨티은행'
];
?>