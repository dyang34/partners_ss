/** Left Menu euni 수정 */
const openNav = function() {
  document.getElementById("Sidenav").style.width = "250px";
  document.getElementById("conts").style.marginLeft = "250px";
  document.getElementById("nav").style.marginLeft = "250px";

  $('.hide_full_screen').fadeIn();
  $('.title-area').css("padding-top","115px");
  $('.toggle_padding').css("padding","0 50px");

  setCookie("leftmenu_close", "false", 1);
}

const closeNav = function() {
  document.getElementById("Sidenav").style.width = "0px";
  document.getElementById("conts").style.marginLeft= "0px";
  document.getElementById("nav").style.marginLeft= "0px";

  $('.hide_full_screen').fadeOut();
  $('.title-area').css("padding-top","30px");
  $('.toggle_padding').css("padding","0px");

  setCookie("leftmenu_close", "true", 1);
}

// 우 더블 클릭 
var prev_time = "";
$(document).on('contextmenu','.list-area, .write-area', function(e){
    
  var time = new Date().getTime();

  if (prev_time!="" && ((time-prev_time)<300)) {
    if(document.getElementById("Sidenav").style.width=="0px") {
      openNav();
    } else {
      closeNav();
    }
//        e.preventDefault();
      
  } else {
      prev_time = time;
  }

  return false;
});

$(document).ready(function() {

  //drop down menu
  var Accordion = function(el, multiple) {
		this.el = el || {};
		this.multiple = multiple || false;

		// Variables
		var link = this.el.find('.link');
		// Eventos
		link.on('click', {el: this.el, multiple: this.multiple},this.dropdown)
	}

	Accordion.prototype.dropdown = function(e) {
		var $el = e.data.el;
			$this = $(this),
			$next = $this.next();
		// Desencadena evento de apertura en los elementos siguientes a la clase link = ul.submenu
		$next.slideToggle();
		// Agregar clase open a elemento padre del elemento con clase link = li
		$this.parent().toggleClass('open');		
		//Parametro inicial que permite ver 1 solo submenu abierto 
		if(!e.data.multiple){
			$el.find('.submenu').not($next).slideUp().parent().removeClass('open');
		}    
	}

	// Elegir submenus multiples (true) submenus uno a la vez (false)
	var accordion = new Accordion($('#accordion'), false);

  if(getCookie("leftmenu_close")=="true") {
    closeNav();
  }
  
});

const chk_pattern = function(p_val, p_type) {
	const pattern_num = /^[0-9]+$/g;
	const pattern_num_dot = /^[0-9.]+$/g;
	const pattern_num_comma = /^[0-9,]+$/g;
	const pattern_num_dot_comma = /^[0-9,.]+$/g;
	const pattern_jumin = /\d{2}([0]\d|[1][0-2])([0][1-9]|[1-2]\d|[3][0-1])-?[1-8]\d{0,6}/g;
	const pattern_email = /^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i;
	const pattern_hp = /^01([0|1|6|7|8|9])-?([0-9]{3,4})-?([0-9]{4})$/g;
	let gender;

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
				return checkValidDate(((gender=="1"||gender=="2"||gender=="5"||gender=="6")?"19":"20")+String(p_val).substring(0,2)+'-'+String(p_val).substring(2,4)+'-'+String(p_val).substring(4,6));
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

const getStdAge = function(p_curdate, p_yyyymmdd) {	

	if(!checkValidDate(p_curdate) || !checkValidDate(p_yyyymmdd)) {
		return -1;
	}

	let age;

	const cur_yyyy = parseInt(String(p_curdate).substring(0,4),10);
	const cur_mmdd = String(p_curdate).substring(5,7)+String(p_curdate).substring(8,10);

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
const getInsuAge = function(p_curdate, p_yyyymmdd) {

	if(!checkValidDate(p_curdate) || !checkValidDate(p_yyyymmdd)) {
		return -1;
	}

	let date = new Date(p_yyyymmdd);
	date.setMonth(date.getMonth() - 6);

    return getStdAge(p_curdate, dateFormat(date));
};