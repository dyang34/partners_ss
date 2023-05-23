<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class HanaPlanMemberDao extends A_Dao
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
		 
		$sql =" select no,member_no,hana_plan_no,plan_state,main_check,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,hphone,email,plan_code,plan_title,plan_title_src,plan_price,sex,age,gift_state,gift_key,sms_send,chubb_relator_seq,chubb_relator_premium,chubb_relator_return_code,thai_chk,fg_dual,nation_name, job "
			 ." from hana_plan_member "
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

		$sql =" select no,member_no,hana_plan_no,plan_state,main_check,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,hphone,email,plan_code,plan_title,plan_title_src,plan_price,sex,age,gift_state,gift_key,sms_send,chubb_relator_seq,chubb_relator_premium,chubb_relator_return_code,thai_chk,fg_dual,nation_name, job "
			 ." from hana_plan_member"
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
	    
	    $sql =" select no,member_no,hana_plan_no,plan_state,main_check,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,hphone,email,plan_code,plan_title,plan_title_src,plan_price,sex,age,gift_state,gift_key,sms_send,chubb_relator_seq,chubb_relator_premium,chubb_relator_return_code,thai_chk,fg_dual,nation_name, job "
	         ." from hana_plan_member"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectDetail($db, $wq, $plan_member_no) {
	    
	    $sql =" select m.no,m.member_no,m.hana_plan_no,m.plan_state,m.main_check,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,m.hphone,m.email,m.plan_code,m.plan_title,m.plan_title_src,m.plan_price,sex,age,gift_state,gift_key,sms_send,chubb_relator_seq,chubb_relator_premium,chubb_relator_return_code,thai_chk,fg_dual,nation_name, job "
			."			,a.cal_type, a.plan_type, a.sort "
	        ." from hana_plan_member m "
			." left join plan_code_hana a "
			." on a.plan_code = m.plan_code "
			." and a.member_no = ".$plan_member_no
	        .$wq->getWhereQuery()
	        .$wq->getOrderByQuery()
	    ;

        return $db->query($sql);
	}

	function selectDetailTriptype3($db, $wq, $plan_member_no) {
	    
	    $sql =" select m.no,m.member_no,m.hana_plan_no,m.plan_state,m.main_check,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,m.hphone,m.email,m.plan_code,m.plan_title,m.plan_title_src,m.plan_price,sex,age,gift_state,gift_key,sms_send,chubb_relator_seq,chubb_relator_premium,chubb_relator_return_code,thai_chk,fg_dual,nation_name, job "
			."			,a.cal_type, a.plan_type, a.sort "
	        ." from hana_plan_member m "
			." left join plan_code_longterm a "
			." on a.plan_code = m.plan_code "
			." and a.member_no = ".$plan_member_no
	        .$wq->getWhereQuery()
	        .$wq->getOrderByQuery()
	    ;

        return $db->query($sql);
	}

	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no,member_no,hana_plan_no,plan_state,main_check,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,hphone,email,plan_code,plan_title,plan_title_src,plan_price,sex,age,gift_state,gift_key,sms_send,chubb_relator_seq,chubb_relator_premium,chubb_relator_return_code,thai_chk,fg_dual,nation_name, job "
			." 		from hana_plan_member a "
			." 		INNER JOIN ( "
	        ."			select no as idx from hana_plan_member a "
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
			 ." from hana_plan_member a "
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
			 ." from hana_plan_member"
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
	
	function insert_simple($db, $arrVal) {

	    $sql =" insert hana_plan_member(member_no,hana_plan_no,main_check,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,hphone,email,plan_code,plan_title,plan_title_src,plan_price,sex,age, job)"
	        ." values ('".$this->checkMysql($db, $arrVal["member_no"])
	            ."', '".$this->checkMysql($db, $arrVal["hana_plan_no"])
	            ."', '".$this->checkMysql($db, $arrVal["main_check"])
	            ."', '".$this->checkMysql($db, $arrVal["name"])
	            ."', '".$this->checkMysql($db, $arrVal["name_eng"])
	            ."', '".$this->checkMysql($db, $arrVal["name_eng_first"])
	            ."', '".$this->checkMysql($db, $arrVal["name_eng_last"])
	            ."', '".$this->checkMysql($db, $arrVal["jumin_1"])
	            ."', '".$this->checkMysql($db, $arrVal["jumin_2"])
	            ."', '".$this->checkMysql($db, $arrVal["hphone"])
	            ."', '".$this->checkMysql($db, $arrVal["email"])
	            ."', '".$this->checkMysql($db, $arrVal["plan_code"])
	            ."', (select plan_title from plan_code_hana where company_type= '".$this->checkMysql($db, $arrVal["company_type"])."' and plan_code='".$this->checkMysql($db, $arrVal["plan_code"])."' order by (case when member_no= ".$this->checkMysql($db, $arrVal["member_no"])." then 999999 else member_no end) desc limit 1)"
				.", (select plan_title_src from plan_code_hana where company_type= '".$this->checkMysql($db, $arrVal["company_type"])."' and plan_code='".$this->checkMysql($db, $arrVal["plan_code"])."' order by (case when member_no= ".$this->checkMysql($db, $arrVal["member_no"])." then 999999 else member_no end) desc limit 1)"
	            .", '".$this->checkMysql($db, $arrVal["plan_price"])
	            ."', '".$this->checkMysql($db, $arrVal["sex"])
	            ."', '".$this->checkMysql($db, $arrVal["age"])
	            ."', '".$this->checkMysql($db, $arrVal["job"])
	            ."')"
		;
	                
		return $db->query($sql);

	}
	
	function insert_simple_triptype3($db, $arrVal) {

	    $sql =" insert hana_plan_member(member_no,hana_plan_no,main_check,name,name_eng,name_eng_first,name_eng_last,jumin_1,jumin_2,hphone,email,plan_code,plan_title,plan_title_src,plan_price,sex,age,job)"
	        ." values ('".$this->checkMysql($db, $arrVal["member_no"])
	            ."', '".$this->checkMysql($db, $arrVal["hana_plan_no"])
	            ."', '".$this->checkMysql($db, $arrVal["main_check"])
	            ."', '".$this->checkMysql($db, $arrVal["name"])
	            ."', '".$this->checkMysql($db, $arrVal["name_eng"])
	            ."', '".$this->checkMysql($db, $arrVal["name_eng_first"])
	            ."', '".$this->checkMysql($db, $arrVal["name_eng_last"])
	            ."', '".$this->checkMysql($db, $arrVal["jumin_1"])
	            ."', '".$this->checkMysql($db, $arrVal["jumin_2"])
	            ."', '".$this->checkMysql($db, $arrVal["hphone"])
	            ."', '".$this->checkMysql($db, $arrVal["email"])
	            ."', '".$this->checkMysql($db, $arrVal["plan_code"])
	            ."', (select plan_title from plan_code_longterm where company_type= '".$this->checkMysql($db, $arrVal["company_type"])."' and plan_code='".$this->checkMysql($db, $arrVal["plan_code"])."' order by (case when member_no= ".$this->checkMysql($db, $arrVal["member_no"])." then 999999 else member_no end) desc limit 1)"
				.", (select plan_title_src from plan_code_longterm where company_type= '".$this->checkMysql($db, $arrVal["company_type"])."' and plan_code='".$this->checkMysql($db, $arrVal["plan_code"])."' order by (case when member_no= ".$this->checkMysql($db, $arrVal["member_no"])." then 999999 else member_no end) desc limit 1)"
	            .", '".$this->checkMysql($db, $arrVal["plan_price"])
	            ."', '".$this->checkMysql($db, $arrVal["sex"])
	            ."', '".$this->checkMysql($db, $arrVal["age"])
	            ."', '".$this->checkMysql($db, $arrVal["job"])
	            ."')"
		;
	                
		return $db->query($sql);

	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update hana_plan_member"
			.$uq->getQuery($db)
	        ." where no = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
	    	$sql =" delete from hana_plan_member where no = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>