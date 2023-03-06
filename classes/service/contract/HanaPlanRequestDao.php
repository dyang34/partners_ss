<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class HanaPlanRequestDao extends A_Dao
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
		 
		$sql =" select no, plan_no, member_no, jumin_1, jumin_2, change_type, change_state, content, regdate, confirm_regdate "
			 ." from hana_plan_request "
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

		$sql =" select no, plan_no, member_no, jumin_1, jumin_2, change_type, change_state, content, regdate, confirm_regdate "
			 ." from hana_plan_request"
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
	    
	    $sql =" select no, plan_no, member_no, jumin_1, jumin_2, change_type, change_state, content, regdate, confirm_regdate "
	         ." from hana_plan_request"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no, plan_no, member_no, jumin_1, jumin_2, change_type, change_state, content, regdate, confirm_regdate "
			." 		from hana_plan_request a "
			." 		INNER JOIN ( "
	        ."			select no as idx from hana_plan_request a "
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
			 ." from hana_plan_request a "
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
			 ." from hana_plan_request"
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

	    $sql =" insert hana_plan_request(plan_no, member_no, jumin_1, jumin_2, change_type, content, regdate)"
	        ." values ('".$this->checkMysql($db, $arrVal["plan_no"])
	            ."', '".$this->checkMysql($db, $arrVal["member_no"])
	            ."', '".$this->checkMysql($db, $arrVal["jumin_1"])
	            ."', '".$this->checkMysql($db, $arrVal["jumin_2"])
	            ."', '".$this->checkMysql($db, $arrVal["change_type"])
	            ."', '".$this->checkMysql($db, $arrVal["content"])
	            ."', UNIX_TIMESTAMP())"
		;
	         
		return $db->query($sql);
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update hana_plan_request"
			.$uq->getQuery($db)
	        ." where no = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
	    	$sql =" delete from hana_plan_request where no = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>