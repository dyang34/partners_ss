<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class HanaPlanDao extends A_Dao
{
	private static $instance = null;

	private function __construct() {
	    // getInstance() 이용.
	}
	
	static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	function selectByKey($db, $key) {
		 
		$sql =" select no,member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,bill_state,common_plan,plan_join_code,plan_join_code_date,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,cancel_insert_date,cancel_confirm_date,change_date,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type,plan_join_code_replace,referer_type,chubb_return_msg, manager_idx, manager_name, add_info1, add_info2 "
			."			, (select nation_name from nation n where n.no = a.nation_no) as nation_txt "
			." from hana_plan a "
			." where no = ".$this->quot($db, $key)
		;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();

        return $row;
	}

	function selectFirst($db, $wq) {

		$sql =" select no,member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,bill_state,common_plan,plan_join_code,plan_join_code_date,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,cancel_insert_date,cancel_confirm_date,change_date,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type,plan_join_code_replace,referer_type,chubb_return_msg, manager_idx, manager_name, add_info1, add_info2 "
			." from hana_plan"
			.$wq->getWhereQuery()
			.$wq->getOrderByQuery()
		;
		
		$row = null;

		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();
		
		return $row;
	}

	function select($db, $wq) {
	    
	    $sql =" select no,member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,bill_state,common_plan,plan_join_code,plan_join_code_date,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,cancel_insert_date,cancel_confirm_date,change_date,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type,plan_join_code_replace,referer_type,chubb_return_msg, manager_idx, manager_name, add_info1, add_info2 "
			." from hana_plan"
			.$wq->getWhereQuery()
			.$wq->getOrderByQuery()
		;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no,member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,bill_state,common_plan,plan_join_code,plan_join_code_date,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,cancel_insert_date,cancel_confirm_date,change_date,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type,plan_join_code_replace,referer_type,chubb_return_msg, manager_idx, manager_name, add_info1, add_info2 "
			." 		from hana_plan a "
			." 		INNER JOIN ( "
	        ."			select no as idx from hana_plan a "
            			.$wq->getWhereQuery()
						.$wq->getOrderByQuery()
	        ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	        ." 		) pg_idx "
	        ." 		on a.no=pg_idx.idx "
			." ) r"
		;
			 
        return $db->query($sql);
	}

	function selectReprePerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, a.*, (select sum(plan_price) from hana_plan_member bb where bb.hana_plan_no = a.no) as price_sum, (select nation_name from nation n where n.no = a.nation_no) as nation_txt "
			."		,plan_state,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,hphone,email,plan_code,plan_title,plan_title_src,plan_price,sex,age,gift_state,gift_key,sms_send,thai_chk,fg_dual,nation_name "
			." 		from hana_plan a "
			." 		INNER JOIN ( "
	        ."			select no as idx from hana_plan a "
            			.$wq->getWhereQuery()
						.$wq->getOrderByQuery()
	        ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	        ." 		) pg_idx "
	        ." 		on a.no=pg_idx.idx "
	        ." 		LEFT JOIN hana_plan_member b "
	        ." 		ON a.no = b.hana_plan_no "
	        ." 		AND b.main_check = 'Y' "
			." ) r"
		;
			 
        return $db->query($sql);
	}

	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			." from hana_plan a "
			.$wq->getWhereQuery()
		;
		
		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();
		
		return $row["cnt"];
	}
	
	function exists($db, $wq) {

		$sql =" select count(*) cnt"
			." from hana_plan"
			.$wq->getWhereQuery()
		;

		$row = null;
		$result = $db->query($sql);
		if ( $result->num_rows > 0 ) {
		    $row = $result->fetch_assoc();
		}
		
		@ $result->free();
		
/*		
		$result = mysql_query($sql);
		if ( mysql_num_rows($result) > 0 ) {
		    $row = mysql_fetch_assoc($result);
		}
		
		@ mysql_free_result($result);
*/		
		if ( $row["cnt"] > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	function insert($db, $arrVal) {

	    $sql =" insert hana_plan(member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,common_plan,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type, manager_idx, manager_name, add_info1, add_info2,plan_join_code_replace,referer_type,chubb_return_msg)"	// ,plan_join_code,plan_join_code_date
	        	." values ('".$this->checkMysql($db, $arrVal["member_no"])
	            ."', '".$this->checkMysql($db, $arrVal["insurance_comp"])
	            ."', '".$this->checkMysql($db, $arrVal["plan_list_state"])
	            ."', '".$this->checkMysql($db, $arrVal["session_key"])
	            ."', '".$this->checkMysql($db, $arrVal["trip_type"])
	            ."', '".$this->checkMysql($db, $arrVal["order_type"])
	            ."', '".$this->checkMysql($db, $arrVal["common_plan"])
	            ."', '".$this->checkMysql($db, $arrVal["nation_no"])
				."', '".$this->checkMysql($db, $arrVal["trip_purpose"])
				."', '".$this->checkMysql($db, $arrVal["start_date"])
				."', '".$this->checkMysql($db, $arrVal["start_hour"])
				."', '".$this->checkMysql($db, $arrVal["end_date"])
				."', '".$this->checkMysql($db, $arrVal["end_hour"])
				."', '".$this->checkMysql($db, $arrVal["term_day"])
				."', '".$this->checkMysql($db, $arrVal["join_cnt"])
				."', '".$this->checkMysql($db, $arrVal["current_resi"])
				."', '".$this->checkMysql($db, $arrVal["plan_type"])
				."', UNIX_TIMESTAMP()"
				.", '".$this->checkMysql($db, $arrVal["check_type_1"])
				."', '".$this->checkMysql($db, $arrVal["check_type_2"])
				."', '".$this->checkMysql($db, $arrVal["check_type_3"])
				."', '".$this->checkMysql($db, $arrVal["check_type_4"])
				."', '".$this->checkMysql($db, $arrVal["check_type_5"])
				."', '".$this->checkMysql($db, $arrVal["check_type_marketing"])
				."', '".$this->checkMysql($db, $arrVal["select_agree"])
				."', '".$this->checkMysql($db, $arrVal["join_name"])
				."', '".$this->checkMysql($db, $arrVal["join_hphone"])
				."', '".$this->checkMysql($db, $arrVal["order_no"])
				."', '".$this->checkMysql($db, $arrVal["card_cd"])
				."', '".$this->checkMysql($db, $arrVal["card_name"])
				."', '".$this->checkMysql($db, $arrVal["tno"])
				."', '".$this->checkMysql($db, $arrVal["app_no"])
				."', '".$this->checkMysql($db, $arrVal["plan_memo"])
				."', '".$this->checkMysql($db, $arrVal["etc_memo1"])
				."', '".$this->checkMysql($db, $arrVal["etc_memo2"])
				."', '".$this->checkMysql($db, $arrVal["chubb_plan_no"])
				."', '".$this->checkMysql($db, $arrVal["chubb_app_premium"])
				."', '".$this->checkMysql($db, $arrVal["chubb_return_code"])
				."', '".$this->checkMysql($db, $arrVal["is_mobile"])
				."', '".$this->checkMysql($db, $arrVal["api_status"])
				."', '".$this->checkMysql($db, $arrVal["company_type"])
				."', '".$this->checkMysql($db, $arrVal["manager_idx"])
				."', '".$this->checkMysql($db, $arrVal["manager_name"])
				."', '".$this->checkMysql($db, $arrVal["add_info1"])
				."', '".$this->checkMysql($db, $arrVal["add_info2"])
				."', '".$this->checkMysql($db, $arrVal["plan_join_code_replace"])
				."', '".$this->checkMysql($db, $arrVal["referer_type"])
				."', '".$this->checkMysql($db, $arrVal["chubb_return_msg"])
	            ."')"
			;
	    
		$db->query($sql);

		return $db->insert_id;
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update hana_plan"
			.$uq->getQuery($db)
	    	." where no = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
	    	$sql =" delete from hana_plan where no = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>