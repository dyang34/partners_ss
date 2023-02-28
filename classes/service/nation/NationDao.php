<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class NationDao extends A_Dao
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
		 
		$sql =" select no, nation_type, nation_name, use_type, use_type_meritz, nation_code, disp_type, regdate, deldate "
			 ." from nation "
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

		$sql =" select no, nation_type, nation_name, use_type, use_type_meritz, nation_code, disp_type, regdate, deldate "
			 ." from nation"
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
	    
	    $sql =" select no, nation_type, nation_name, use_type, use_type_meritz, nation_code, disp_type, regdate, deldate "
	         ." from nation"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no, nation_type, nation_name, use_type, use_type_meritz, nation_code, disp_type, regdate, deldate "
			." 		from nation a "
			." 		INNER JOIN ( "
	        ."			select no as idx from nation a "
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
			 ." from nation a "
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
			 ." from nation"
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
	    $sql =" insert nation(no, nation_type, nation_name, use_type, use_type_meritz, nation_code, disp_type, regdate, deldate)"
	        ." values ('".$this->checkMysql($db, $arrVal["uid"])
			."', '".$this->checkMysql($db, $arrVal["manager_id"])
			."', '".$this->checkMysql($db, $arrVal["manager_pw"])
			."', '".$this->checkMysql($db, $arrVal["name"])
			."', '".$this->checkMysql($db, $arrVal["hp_no"])
			."', '".$this->checkMysql($db, $arrVal["email"])
			."', '".$this->checkMysql($db, $arrVal["fg_del"])
			."', now())"
		;
		
		return $db->query($sql);
*/		
	}
	
	function update($db, $uq, $key) {
/*	    
	    $sql =" update nation"
			.$uq->getQuery($db)
	        ." where no = ".$this->quot($db, $key);
	        
		return $db->query($sql);
*/		
	}
	
	function delete($db, $key) {
/*		
	    if ($key) {
	    	$sql =" delete from nation where no = ".$this->quot($db, $key);
			return $db->query($sql);
		}
*/		
	}	
}
?>