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
		 
		$sql =" select no,member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,bill_state,common_plan,plan_join_code,plan_join_code_date,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,cancel_insert_date,cancel_confirm_date,change_date,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type,plan_join_code_replace,referer_type,chubb_return_msg "
			 ." from hana_plan "
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

		$sql =" select no,member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,bill_state,common_plan,plan_join_code,plan_join_code_date,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,cancel_insert_date,cancel_confirm_date,change_date,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type,plan_join_code_replace,referer_type,chubb_return_msg "
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
	    
	    $sql =" select no,member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,bill_state,common_plan,plan_join_code,plan_join_code_date,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,cancel_insert_date,cancel_confirm_date,change_date,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type,plan_join_code_replace,referer_type,chubb_return_msg "
	         ." from hana_plan"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no,member_no,insurance_comp,plan_list_state,session_key,trip_type,order_type,bill_state,common_plan,plan_join_code,plan_join_code_date,nation_no,trip_purpose,start_date,start_hour,end_date,end_hour,term_day,join_cnt,current_resi,plan_type,regdate,check_type_1,check_type_2,check_type_3,check_type_4,check_type_5,check_type_marketing,select_agree,join_name,join_hphone,order_no,card_cd,card_name,tno,app_no,plan_memo,etc_memo1,etc_memo2,cancel_insert_date,cancel_confirm_date,change_date,chubb_plan_no,chubb_app_premium,chubb_return_code,is_mobile,api_status,company_type,plan_join_code_replace,referer_type,chubb_return_msg "
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
/*
	    $sql =" insert hana_plan(mem_type,mem_state,uid,upw,com_name,email,hphone,com_no,regdate,com_percent,com_percent_other,last_login,post_no,post_addr,post_addr_detail,fax_contact,web_site,com_open_date,etc,insuran1,insuran2,insuran3,insuran4,insuran5,insuran6,insuran7,file_real_name,file_name,insuran8,insuran9,insuran10,company_type )"
	        ." values ('".$this->checkMysql($db, $arrVal["no"])
	        ."', password('".$this->checkMysql($db, $arrVal["passwd"])."')"
	            .", '".$this->checkMysql($db, $arrVal["name"])
	            ."', '".$this->checkMysql($db, $arrVal["grade"])
	            ."', '".$this->checkMysql($db, $arrVal["fg_outside"])
	            ."', '".$this->checkMysql($db, $arrVal["hp_no"])
	            ."', '".$this->checkMysql($db, $arrVal["email"])
	            ."', '".$this->checkMysql($db, $arrVal["grade_alarm"])
	            ."', '".$this->checkMysql($db, $arrVal["hiworks_id"])
	            ."', now())"
	                ;
	                
	                return $db->query($sql);
*/	                
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