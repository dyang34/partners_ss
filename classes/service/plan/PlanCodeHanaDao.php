<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class PlanCodeHanaDao extends A_Dao
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
		 
		$sql =" select no,member_no,trip_type,plan_code,cal_type,plan_type,plan_title,plan_title_src,plan_start_age,plan_end_age,company_type,type_1,type_2,type_3,type_4,type_5,type_6,type_7,type_8,type_9,type_10,type_11,type_12,type_13,type_14,type_15,type_16,type_17,type_18,type_19,type_20,type_21,type_22,type_23,type_24,type_25,type_26,type_27,type_28,type_29,type_30,type_31,type_32,type_33,type_34,type_1_text,type_2_text,type_3_text,type_4_text,type_5_text,type_6_text,type_7_text,type_8_text,type_9_text,type_10_text,type_11_text,type_12_text,type_13_text,type_14_text,type_15_text,type_16_text,type_17_text,type_18_text,type_19_text,type_20_text,type_21_text,type_22_text,type_23_text,type_24_text,type_25_text,type_26_text,type_27_text,type_28_text,type_29_text,type_30_text,type_31_text,type_32_text,type_33_text,type_34_text "
			 ." from plan_code_hana "
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

		$sql =" select no,member_no,trip_type,plan_code,cal_type,plan_type,plan_title,plan_title_src,plan_start_age,plan_end_age,company_type,type_1,type_2,type_3,type_4,type_5,type_6,type_7,type_8,type_9,type_10,type_11,type_12,type_13,type_14,type_15,type_16,type_17,type_18,type_19,type_20,type_21,type_22,type_23,type_24,type_25,type_26,type_27,type_28,type_29,type_30,type_31,type_32,type_33,type_34,type_1_text,type_2_text,type_3_text,type_4_text,type_5_text,type_6_text,type_7_text,type_8_text,type_9_text,type_10_text,type_11_text,type_12_text,type_13_text,type_14_text,type_15_text,type_16_text,type_17_text,type_18_text,type_19_text,type_20_text,type_21_text,type_22_text,type_23_text,type_24_text,type_25_text,type_26_text,type_27_text,type_28_text,type_29_text,type_30_text,type_31_text,type_32_text,type_33_text,type_34_text "
			 ." from plan_code_hana"
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
	    
	    $sql =" select no,member_no,trip_type,plan_code,cal_type,plan_type,plan_title,plan_title_src,plan_start_age,plan_end_age,company_type,type_1,type_2,type_3,type_4,type_5,type_6,type_7,type_8,type_9,type_10,type_11,type_12,type_13,type_14,type_15,type_16,type_17,type_18,type_19,type_20,type_21,type_22,type_23,type_24,type_25,type_26,type_27,type_28,type_29,type_30,type_31,type_32,type_33,type_34,type_1_text,type_2_text,type_3_text,type_4_text,type_5_text,type_6_text,type_7_text,type_8_text,type_9_text,type_10_text,type_11_text,type_12_text,type_13_text,type_14_text,type_15_text,type_16_text,type_17_text,type_18_text,type_19_text,type_20_text,type_21_text,type_22_text,type_23_text,type_24_text,type_25_text,type_26_text,type_27_text,type_28_text,type_29_text,type_30_text,type_31_text,type_32_text,type_33_text,type_34_text "
	         ." from plan_code_hana"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no,member_no,trip_type,plan_code,cal_type,plan_type,plan_title,plan_title_src,plan_start_age,plan_end_age,company_type,type_1,type_2,type_3,type_4,type_5,type_6,type_7,type_8,type_9,type_10,type_11,type_12,type_13,type_14,type_15,type_16,type_17,type_18,type_19,type_20,type_21,type_22,type_23,type_24,type_25,type_26,type_27,type_28,type_29,type_30,type_31,type_32,type_33,type_34,type_1_text,type_2_text,type_3_text,type_4_text,type_5_text,type_6_text,type_7_text,type_8_text,type_9_text,type_10_text,type_11_text,type_12_text,type_13_text,type_14_text,type_15_text,type_16_text,type_17_text,type_18_text,type_19_text,type_20_text,type_21_text,type_22_text,type_23_text,type_24_text,type_25_text,type_26_text,type_27_text,type_28_text,type_29_text,type_30_text,type_31_text,type_32_text,type_33_text,type_34_text "
			." 		from plan_code_hana a "
			." 		INNER JOIN ( "
	        ."			select no as idx from plan_code_hana a "
            			.$wq->getWhereQuery()
						.$wq->getOrderByQuery()
	        ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	        ." 		) pg_idx "
	        ." 		on a.no=pg_idx.idx "
			." ) r"
		;
			 
        return $db->query($sql);
	}
	
	function selectReprePlanList($db, $wq) {
		$sql =" SELECT company_type, member_no, trip_type, plan_title, MIN(cast(plan_type AS UNSIGNED )) AS plan_type "
			."FROM plan_code_hana "
			.$wq->getWhereQuery()
			."GROUP BY company_type, member_no, trip_type, plan_title "
			."ORDER BY company_type, member_no, trip_type, MIN(cast(plan_type AS UNSIGNED )) "
		;

		return $db->query($sql);
	}

	function selectPlanListByPlanType($db, $wq) {

		$sql =" SELECT a.company_type, a.member_no, a.trip_type, a.plan_title as plan_repre_title, a.plan_type, b.cal_type, b.plan_code, b.plan_start_age, b.plan_end_age, b.plan_title, b.company_type "
			."FROM ( "
			."	SELECT company_type, member_no, trip_type, plan_title, MIN(cast(plan_type AS UNSIGNED )) AS plan_type "
			."	FROM plan_code_hana a "
			.$wq->getWhereQuery()
			."	GROUP BY company_type, member_no, trip_type, plan_title "
			.") AS a "
			."LEFT JOIN plan_code_hana b "
			."ON a.trip_type = b.trip_type "
			."AND a.company_type = b.company_type "
			."AND b.plan_type LIKE CONCAT('%',a.plan_type,'%') "
			."ORDER BY a.company_type, a.member_no, a.trip_type, a.plan_type, b.cal_type "
		;

		return $db->query($sql);
	}

	function selectPlanListByCalType($db, $wq) {

		$sql =" SELECT a.company_type, a.member_no, a.trip_type, a.plan_type, a.cal_type, plan_code, a.plan_start_age, a.plan_end_age, a.plan_title "
			." , b.plan_title as plan_repre_title, b.plan_type as plan_repre_type "
			." 	FROM plan_code_hana a "
			." 	LEFT JOIN ( "
			." 	SELECT company_type, member_no, trip_type, plan_title, MIN(cast(plan_type AS UNSIGNED )) AS plan_type "
			." 	FROM plan_code_hana a "
			." 	GROUP BY company_type, member_no, trip_type, plan_title "
			." 	) b "
			." ON a.trip_type = b.trip_type "
			." AND a.company_type = b.company_type "
			." AND a.member_no = b.member_no "
			." AND a.plan_type LIKE CONCAT('%',b.plan_type,'%') "
			.$wq->getWhereQuery()
			." ORDER BY cal_type "
		;

		return $db->query($sql);
	}

	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from plan_code_hana a "
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
			 ." from plan_code_hana"
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
	    $sql =" insert plan_code_hana(mem_type,mem_state,uid,upw,com_name,email,hphone,com_no,regdate,com_percent,com_percent_other,last_login,post_no,post_addr,post_addr_detail,fax_contact,web_site,com_open_date,etc,insuran1,insuran2,insuran3,insuran4,insuran5,insuran6,insuran7,file_real_name,file_name,insuran8,insuran9,insuran10,company_type )"
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
	    
	    $sql =" update plan_code_hana"
			.$uq->getQuery($db)
	        ." where no = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
	    	$sql =" delete from plan_code_hana where no = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>