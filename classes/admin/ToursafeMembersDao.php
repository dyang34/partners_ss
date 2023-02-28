<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class ToursafeMembersDao extends A_Dao
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
		 
		$sql =" select no,mem_type,mem_state,uid,upw,com_name,email,hphone,com_no,regdate,com_percent,com_percent_other,last_login,post_no,post_addr,post_addr_detail,fax_contact,web_site,com_open_date,etc,insuran1,insuran2,insuran3,insuran4,insuran5,insuran6,insuran7,file_real_name,file_name,insuran8,insuran9,insuran10,company_type,hphone2, fg_not_common_plan "
			 ." from toursafe_members "
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

		$sql =" select no,mem_type,mem_state,uid,upw,com_name,email,hphone,com_no,regdate,com_percent,com_percent_other,last_login,post_no,post_addr,post_addr_detail,fax_contact,web_site,com_open_date,etc,insuran1,insuran2,insuran3,insuran4,insuran5,insuran6,insuran7,file_real_name,file_name,insuran8,insuran9,insuran10,company_type,hphone2, fg_not_common_plan "
			 ." from toursafe_members"
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
	    
	    $sql =" select no,mem_type,mem_state,uid,upw,com_name,email,hphone,com_no,regdate,com_percent,com_percent_other,last_login,post_no,post_addr,post_addr_detail,fax_contact,web_site,com_open_date,etc,insuran1,insuran2,insuran3,insuran4,insuran5,insuran6,insuran7,file_real_name,file_name,insuran8,insuran9,insuran10,company_type,hphone2, fg_not_common_plan "
	         ." from toursafe_members"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no,mem_type,mem_state,uid,upw,com_name,email,hphone,com_no,regdate,com_percent,com_percent_other,last_login,post_no,post_addr,post_addr_detail,fax_contact,web_site,com_open_date,etc,insuran1,insuran2,insuran3,insuran4,insuran5,insuran6,insuran7,file_real_name,file_name,insuran8,insuran9,insuran10,company_type,hphone2, fg_not_common_plan "
			." 		from toursafe_members a "
			." 		INNER JOIN ( "
	        ."			select no as idx from toursafe_members a "
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
			 ." from toursafe_members a "
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
			 ." from toursafe_members"
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

	    $sql =" insert toursafe_members(mem_type,mem_state,uid,upw,com_name,email,hphone,com_no,com_percent,com_percent_other,post_no,post_addr,post_addr_detail,fax_contact,web_site,com_open_date,etc,insuran1,insuran2,insuran3,insuran4,insuran5,insuran6,insuran7,file_real_name,file_name,insuran8,insuran9,insuran10,company_type,hphone2,regdate)"
	        ." values ('".$this->checkMysql($db, $arrVal["mem_type"])
			."', '".$this->checkMysql($db, $arrVal["mem_state"])
			."', '".$this->checkMysql($db, $arrVal["uid"])
			."', '".$this->checkMysql($db, $arrVal["upw"])
			."', '".$this->checkMysql($db, $arrVal["com_name"])
			."', '".$this->checkMysql($db, $arrVal["email"])
			."', '".$this->checkMysql($db, $arrVal["hphone"])
			."', '".$this->checkMysql($db, $arrVal["com_no"])
			."', '".$this->checkMysql($db, $arrVal["com_percent"])
			."', '".$this->checkMysql($db, $arrVal["com_percent_other"])				
			."', '".$this->checkMysql($db, $arrVal["post_no"])				
			."', '".$this->checkMysql($db, $arrVal["post_addr"])				
			."', '".$this->checkMysql($db, $arrVal["post_addr_detail"])				
			."', '".$this->checkMysql($db, $arrVal["fax_contact"])				
			."', '".$this->checkMysql($db, $arrVal["web_site"])				
			."', '".$this->checkMysql($db, $arrVal["com_open_date"])				
			."', '".$this->checkMysql($db, $arrVal["etc"])				
			."', '".$this->checkMysql($db, $arrVal["file_real_name"])				
			."', '".$this->checkMysql($db, $arrVal["file_name"])				
			."', '".$this->checkMysql($db, $arrVal["insuran1"])				
			."', '".$this->checkMysql($db, $arrVal["insuran2"])				
			."', '".$this->checkMysql($db, $arrVal["insuran3"])				
			."', '".$this->checkMysql($db, $arrVal["insuran4"])				
			."', '".$this->checkMysql($db, $arrVal["insuran5"])				
			."', '".$this->checkMysql($db, $arrVal["insuran6"])				
			."', '".$this->checkMysql($db, $arrVal["insuran7"])				
			."', '".$this->checkMysql($db, $arrVal["insuran8"])				
			."', '".$this->checkMysql($db, $arrVal["insuran9"])				
			."', '".$this->checkMysql($db, $arrVal["insuran10"])				
			."', '".$this->checkMysql($db, $arrVal["company_type"])				
			."', '".$this->checkMysql($db, $arrVal["hphone2"])				
			."', unix_timestamp())"
		;
		
		return $db->query($sql);
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update toursafe_members"
			.$uq->getQuery($db)
	        ." where uid = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
	    	$sql =" delete from toursafe_members where uid = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>