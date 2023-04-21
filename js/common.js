var auth_token="asdf340jfaiofasdfsadf";
const arrCalTypeTitle = [[],["일반"],["주니어","성인"],["주니어","성인","시니어"],["주니어","성인","시니어1","시니어2"]];

const chk_pattern = function(p_val, p_type) {
	const pattern_num = /^[0-9]+$/g;
	const pattern_num_dot = /^[0-9.]+$/g;
	const pattern_num_comma = /^[0-9,]+$/g;
	const pattern_num_dot_comma = /^[0-9,.]+$/g;
	const pattern_jumin = /\d{2}([0]\d|[1][0-2])([0][1-9]|[1-2]\d|[3][0-1])[-]*[1-8]\d{0,6}/g;
	const pattern_email = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
	const pattern_hp = /^01([0|1|6|7|8|9])-?([0-9]{3,4})-?([0-9]{4})$/g;
	let gender;
	let l_yyyymmdd;

	switch(p_type) {
		case 'num':
			return pattern_num.test(p_val);
			break;
		case 'num_dot':
			return pattern_num_dot.test(p_val);
			break;
		case 'num_comma':
			return pattern_num_comma.test(p_val);
			break;
		case 'num_dot_comma':
			return pattern_num_dot_comma.test(p_val);
			break;
		case 'jumin':
			if(pattern_jumin.test(p_val)) {
				gender = p_val.substring(6,7);
				if(gender=="9"||gender=="0") {
					return false;
				} else {

					l_yyyymmdd = ((gender=="1"||gender=="2"||gender=="5"||gender=="6")?"19":"20")+String(p_val).substring(0,2)+'-'+String(p_val).substring(2,4)+'-'+String(p_val).substring(4,6);

					if (l_yyyymmdd > dateFormat(new Date(), "yyyy-mm-dd")) {
						return false;
					} else {
						return checkValidDate(l_yyyymmdd);
					}
				}
			} else {
				return false;
			}
			break;
		case 'email':
			return pattern_email.test(p_val);
			break;
		case 'hp':
			return pattern_hp.test(p_val);
			break;
		default:
			return false;
	}			
}

const checkValidDate = function(p_val) {
	var result = true;

	try {
	    var date = p_val.split("-");
	    var y = parseInt(date[0], 10),
	        m = parseInt(date[1], 10),
	        d = parseInt(date[2], 10);
	    
	    var dateRegex = /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;
	    result = dateRegex.test(d+'-'+m+'-'+y);
	} catch (err) {
		result = false;
	}

    return result;
}

const dateFormat = function(p_date, p_format="yyyy-mm-dd") {
	let rtn_val = "";

	switch(p_format) {
		case 'yyyymmdd':
			rtn_val = p_date.getFullYear()+((p_date.getMonth()+1)<9?"0"+(p_date.getMonth()+1):(p_date.getMonth()+1))+((p_date.getDate())<9?"0"+(p_date.getDate()):(p_date.getDate()));
			break;
		case 'yyyy-mm-dd':
			rtn_val = p_date.getFullYear()+'-'+((p_date.getMonth()+1)<9?"0"+(p_date.getMonth()+1):(p_date.getMonth()+1))+'-'+((p_date.getDate())<9?"0"+(p_date.getDate()):(p_date.getDate()));
			break;
	}

	return rtn_val;
}

const dateDiff = function(_date1, _date2) {	// ,fg_add_1month
	var diffDate_1 = _date1 instanceof Date ? _date1 : new Date(_date1);
	var diffDate_2 = _date2 instanceof Date ? _date2 : new Date(_date2);
 /*
	if(fg_add_1month) {
		diffDate_1 = new Date(diffDate_1.getFullYear(), diffDate_1.getMonth()+1, diffDate_1.getDate());
		diffDate_2 = new Date(diffDate_2.getFullYear(), diffDate_2.getMonth()+1, diffDate_2.getDate());
	}
 */
	var diff = Math.abs(diffDate_2.getTime() - diffDate_1.getTime());
	diff = Math.ceil(diff / (1000 * 60 * 60 * 24));
 
	return diff;
}

const getStdAge = function(p_stddate, p_yyyymmdd) {	

	if(!checkValidDate(p_stddate) || !checkValidDate(p_yyyymmdd)) {
		return -1;
	}

	let age;

	const cur_yyyy = parseInt(String(p_stddate).substring(0,4),10);
	const cur_mmdd = String(p_stddate).substring(5,7)+String(p_stddate).substring(8,10);

	let yyyy = parseInt(String(p_yyyymmdd).substring(0,4),10);
	let mmdd = String(p_yyyymmdd).substring(5,7)+String(p_yyyymmdd).substring(8,10);

	age = cur_yyyy - yyyy + 1;

	//생일이 지났는지 체크하여 계산
	if (cur_mmdd < mmdd) {
		age = age - 2;
	} else {
		age = age - 1;
	}

	return age;
};

//보험나이 계산
const getInsuAge = function(p_stddate, p_yyyymmdd) {

	if(!checkValidDate(p_stddate) || !checkValidDate(p_yyyymmdd)) {
		return -1;
	}

	let date = new Date(p_yyyymmdd);
	date.setMonth(date.getMonth() - 6);

    return [getStdAge(p_stddate, dateFormat(date)), getStdAge(p_stddate, p_yyyymmdd)];
};

// 해외여행 3개월, 국내여행 1개월 이내 여부 Check.
//const cutMaxTripday = function(stdate, enddate, maxdate, sthour, edhour, triptypeval) {
const cutMaxTripday = function(stdate, enddate, max_month, sthour, edhour, triptypeval) {
	var end_gap_term; 
	var ret_val = true;

	if (triptypeval == '2'){ 	
		end_gap_term = new Date(Date.parse(treemonthcal(stdate, max_month)));
	} else {
		end_gap_term = new Date(Date.parse(treemonthcal(stdate, max_month)));
	}

	var date_term=dateDiff(stdate, end_gap_term);
	var max_date_term = dateDiff(stdate, enddate);
//	console.log(max_date_term+" "+date_term);

	var startHour;
	var endHour;

	startHour = sthour;
	endHour = edhour;
	
	if (triptypeval=="1") {
		if (parseInt(date_term) < parseInt(max_date_term)) {
			ret_val = false;
		} else if (parseInt(date_term) == parseInt(max_date_term)) {
			if(Number(endHour) > Number(startHour)){
				//alert('30일 해당 기간까지만 보장됩니다');
				//alert('도착일 시간이 출발일 시간과 동일하거나 경과되었을 경우 계약기간이 계약이 성립되지 않습니다.');
				ret_val = false;
			}
		}
	} else if (triptypeval=="2") {
		if (parseInt(date_term) < parseInt(max_date_term)) {
			ret_val = false;
		} else if (parseInt(date_term) == parseInt(max_date_term)) {
			if(Number(endHour) > Number(startHour)){
				//alert('90일 해당 기간까지만 보장됩니다');
				//alert('도착일 시간이 출발일 시간과 동일하거나 경과되었을 경우 계약기간이 계약이 성립되지 않습니다.');
				ret_val = false;
			}
		}
	}

	return ret_val;				
}

// 개월 수 더하기.
const treemonthcal = function(kind, kind1) {
    var start_date = kind;
    var data_arr = start_date.split('-');
    var hap_year;
    var hap_month;
    var gap_month;
    var enddate;
    var gap_day;

    hap_month = Number(data_arr[1]) + Number(kind1);
    //console.log(hap_month);
    if(hap_month > 12){
        //gap_month = hap_month - Number(data_arr[1]);
        gap_month = hap_month - 12;

        //console.log('차이: '+gap_month);
        hap_year = Number(data_arr[0]) + 1;
    } else {
        gap_month = hap_month;
        hap_year = Number(data_arr[0]);
    }

    // 2019-08-21 추가 분 -  월을 분리해서 사용하는 중에 10보다 작은 월에 0을 붙여야 한다. (안붙이면 오류)
    if(gap_month < 10){
        gap_month = "0"+gap_month;
    } 	

    var lastDay = ( new Date(hap_year, gap_month,'')).getDate().toString();

    if(data_arr[2] > lastDay){
        gap_day = lastDay;
    } else {
        gap_day = data_arr[2];
    }
    //console.log('종료 일자 : '+lastDay);
    enddate = hap_year+"-"+gap_month+"-"+gap_day;
    //console.log('종료일 : '+enddate);
    return enddate;
}

const check_hour_max = function() {
    var stdate = $('#start_date').val();
    var enddate = $('#end_date').val();

    var sthour = $('#start_hour').val();
    var edhour = $('#end_hour').val();

    var maxdate, max_month;	

    if(g_trip_type == "2"){
//        maxdate = treemonthcal(stdate, '3');
        max_month = 3;
    } else {
//        maxdate = treemonthcal(stdate, '1');
        max_month = 1;
    }
    //console.log(maxdate+" "+sthour+" "+edhour);
    if(!cutMaxTripday(stdate, enddate, max_month, sthour, edhour, g_trip_type)){ // maxdate
        if(g_trip_type == "2"){
            alert('단기해외여행자보험은 최대 3개월까지 가입가능합니다. 3개월 이상 가입 신청 시 유학(장기체류)보험으로 신청해주세요.');
        } else {
            alert('단기국내여행자보험은 최대 1개월까지 가입가능합니다.');
        }
        return false;
    } else {
        return true;
    }
}

var setCookie = function(name, value, exp) {
	var date = new Date();
	date.setTime(date.getTime() + exp*24*60*60*1000);
	document.cookie = name + '=' + value + ';expires=' + date.toUTCString() + ';path=/';
};

var getCookie = function(name) {
	var value = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	return value? value[2] : null;
};

var goPageTop = function(){
	$('#content').animate({scrollTop:0},200);
}

//*********** Modal layerPopUp Script Start ************
/*
$(document).on('click', '.btn_modal', function() {
	var btn = $(this).attr("id");
	$("#modal").removeAttr("class").addClass(btn);
});

$(document).on('click', '.close_modal', function() {
	$("#modal").addClass("out");
});
*/

$(document).on('click','.md-close', function() {
	close_modal();
});

const close_modal = function() {
	//$('.div_modal_window').addClass("out");
	$('div[name=div_modal_window]').addClass("out");
}
//*********** Modal layerPopUp Script End ************