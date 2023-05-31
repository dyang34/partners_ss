<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class ToursafeMembersCompanyMappingDao extends A_Dao
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
		 
		$sql =" select idx, uid,member_no,trip_type,company_type,com_percent,sort,reg_date, plan_member_no "
			 ." from toursafe_members_company_mapping "
			 ." where idx = ".$this->quot($db, $key)
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

		$sql =" select idx, uid,member_no,trip_type,company_type,com_percent,sort,reg_date, plan_member_no "
			 ." from toursafe_members_company_mapping"
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
	    
	    $sql =" select idx, uid,member_no,trip_type,company_type,com_percent,sort,reg_date, plan_member_no "
	         ." from toursafe_members_company_mapping"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, idx, uid,member_no,trip_type,company_type,com_percent,sort,reg_date, plan_member_no "
			." 		from toursafe_members_company_mapping a "
			." 		INNER JOIN ( "
	        ."			select idx from toursafe_members_company_mapping a "
            			.$wq->getWhereQuery()
						.$wq->getOrderByQuery()
	        ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	        ." 		) pg_idx "
	        ." 		on a.idx=pg_idx.idx "
			." ) r"
		;
			 
        return $db->query($sql);
	}
	
	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from toursafe_members_company_mapping a "
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
			 ." from toursafe_members_company_mapping"
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

	    $sql =" insert toursafe_members_company_mapping(uid,member_no,trip_type,company_type,com_percent,sort, plan_member_no,reg_date)"
	        ." values ('".$this->checkMysql($db, $arrVal["uid"])
			."', ".$this->checkMysql($db, $arrVal["member_no"])
			.", ".$this->checkMysql($db, $arrVal["trip_type"])
			.", ".$this->checkMysql($db, $arrVal["company_type"])
			.", ".$this->checkMysql($db, $arrVal["com_percent"])
			.", ".$this->checkMysql($db, $arrVal["sort"])
			.", ".$this->checkMysql($db, $arrVal["plan_member_no"])
			.", now())"
		;
		
		return $db->query($sql);
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update toursafe_members_company_mapping"
			.$uq->getQuery($db)
	        ." where idx = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
	    	$sql =" delete from toursafe_members_company_mapping where idx = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>