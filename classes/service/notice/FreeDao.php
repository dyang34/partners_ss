<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class FreeDao extends A_Dao
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
		 
		$sql =" select no,table_name,notice_type,uid,upw,notice,secret,name,title,hit,content,file_name,real_file_name,file_name2,real_file_name2,editdate,regdate,remote_ip,select_type,youtube_url,in_date,email,text_type,del_key,phone,qna_type,qna_type_add,answer_type,answer_text "
			 ." from free "
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

		$sql =" select no,table_name,notice_type,uid,upw,notice,secret,name,title,hit,content,file_name,real_file_name,file_name2,real_file_name2,editdate,regdate,remote_ip,select_type,youtube_url,in_date,email,text_type,del_key,phone,qna_type,qna_type_add,answer_type,answer_text "
			 ." from free"
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
	    
	    $sql =" select no,table_name,notice_type,uid,upw,notice,secret,name,title,hit,content,file_name,real_file_name,file_name2,real_file_name2,editdate,regdate,remote_ip,select_type,youtube_url,in_date,email,text_type,del_key,phone,qna_type,qna_type_add,answer_type,answer_text "
	         ." from free"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no,table_name,notice_type,uid,upw,notice,secret,name,title,hit,content,file_name,real_file_name,file_name2,real_file_name2,editdate,regdate,remote_ip,select_type,youtube_url,in_date,email,text_type,del_key,phone,qna_type,qna_type_add,answer_type,answer_text "
			." 		from free a "
			." 		INNER JOIN ( "
	        ."			select no as idx from free a "
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
			 ." from free a "
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
			 ." from free"
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
	    $sql =" insert free(mem_type,mem_state,uid,upw,com_name,email,hphone,com_no,regdate,com_percent,com_percent_other,last_login,post_no,post_addr,post_addr_detail,fax_contact,web_site,com_open_date,etc,insuran1,insuran2,insuran3,insuran4,insuran5,insuran6,insuran7,file_real_name,file_name,insuran8,insuran9,insuran10,company_type )"
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
	    
	    $sql =" update free"
			.$uq->getQuery($db)
	        ." where no = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
	    	$sql =" delete from free where no = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>