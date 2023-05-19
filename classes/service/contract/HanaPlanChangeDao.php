<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/classes/cms/db/A_Dao.php";

class HanaPlanChangeDao extends A_Dao
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
		 
		$sql =" select no,hana_plan_no,change_type,change_price,change_date,regdate,in_price,add_input_1,add_input_2,com_percent,com_point,company_type "
			 ." from hana_plan_change "
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

		$sql =" select no,hana_plan_no,change_type,change_price,change_date,regdate,in_price,add_input_1,add_input_2,com_percent,com_point,company_type "
			 ." from hana_plan_change"
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
	    
	    $sql =" select no,hana_plan_no,change_type,change_price,change_date,regdate,in_price,add_input_1,add_input_2,com_percent,com_point,company_type "
	         ." from hana_plan_change"
	         .$wq->getWhereQuery()
	         .$wq->getOrderByQuery()
	         ;

        return $db->query($sql);
	}
	
	function selectPerPage($db, $wq, $pg) {
		$sql =" select @rnum:=@rnum+1 as rnum, r.* from ("
			."		select @rnum:=0, no,hana_plan_no,change_type,change_price,change_date,regdate,in_price,add_input_1,add_input_2,com_percent,com_point,company_type "
			." 		from hana_plan_change a "
			." 		INNER JOIN ( "
	        ."			select no as idx from hana_plan_change a "
            			.$wq->getWhereQuery()
						.$wq->getOrderByQuery()
	        ."     		limit ".$pg->getStartIdx().", ".$pg->getPageSize()
	        ." 		) pg_idx "
	        ." 		on a.no=pg_idx.idx "
			." ) r"
		;
			 
        return $db->query($sql);
	}
	
	function selectMonthlySummary($db, $wq, $wq_cancel) {
	
		$sql =" SELECT job_date, group_concat(distinct concat(com_percent,'%')) com_percent, SUM(inq_change_price) as inq_change_price, SUM(cancel_change_price) as cancel_change_price, SUM(inq_cnt) as inq_cnt, SUM(cancel_cnt) AS cancel_cnt, round(sum(commition)) as commition "
			." FROM ( "
			." 	SELECT change_type, date_format(from_unixtime(a.regdate), '%Y-%m') as job_date, com_percent, change_price AS inq_change_price, 0 AS cancel_change_price, 1 inq_cnt, 0 AS cancel_cnt, (change_price * com_percent / 100) as commition "
			." 		FROM hana_plan_change a "
			." 		LEFT JOIN hana_plan b "
			." 		ON a.hana_plan_no = b.no "
					.$wq->getWhereQuery()
			." 	UNION ALL "
			." 	SELECT change_type, date_format(from_unixtime(case when a.change_date = '' then a.regdate else a.change_date end), '%Y-%m') as job_date, com_percent, 0 AS inq_change_price, change_price AS cancel_change_price, 0 inq_cnt, 1 AS cancel_cnt, (change_price * com_percent / 100) as commition "
			." 		FROM hana_plan_change a "
			." 		LEFT JOIN hana_plan b "
			." 		ON a.hana_plan_no = b.no "
					.$wq_cancel->getWhereQuery()
			." ) AS t "
			." GROUP BY job_date "
			." ORDER BY job_date desc "
		;
			
		return $db->query($sql);
	}

	function selectMonthlySummary2($db, $wq, $wq_cancel) {
	
		$sql =" SELECT company_type, job_date, trip_type, group_concat(distinct concat(com_percent,'%')) com_percent, SUM(inq_change_price) as inq_change_price, SUM(cancel_change_price) as cancel_change_price, SUM(inq_cnt) as inq_cnt, SUM(cancel_cnt) AS cancel_cnt, round(sum(commition)) as commition "
			." FROM ( "
			." 	SELECT a.company_type, trip_type, change_type, date_format(from_unixtime(a.regdate), '%Y-%m') as job_date, com_percent, change_price AS inq_change_price, 0 AS cancel_change_price, 1 inq_cnt, 0 AS cancel_cnt, (change_price * com_percent / 100) as commition "
			." 		FROM hana_plan_change a "
			." 		LEFT JOIN hana_plan b "
			." 		ON a.hana_plan_no = b.no "
					.$wq->getWhereQuery()
			." 	UNION ALL "
			." 	SELECT a.company_type, trip_type, change_type, date_format(from_unixtime(case when a.change_date = '' then a.regdate else a.change_date end), '%Y-%m') as job_date, com_percent, 0 AS inq_change_price, change_price AS cancel_change_price, 0 inq_cnt, 1 AS cancel_cnt, (change_price * com_percent / 100) as commition "
			." 		FROM hana_plan_change a "
			." 		LEFT JOIN hana_plan b "
			." 		ON a.hana_plan_no = b.no "
					.$wq_cancel->getWhereQuery()
			." ) AS t "
			." GROUP BY company_type, job_date, trip_type "
			." ORDER BY job_date desc, company_type, trip_type "
		;
			
		return $db->query($sql);
	}

	function selectCount($db, $wq) {

		$sql =" select count(*) cnt"
			 ." from hana_plan_change a "
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
			 ." from hana_plan_change"
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

	    $sql =" insert hana_plan_change(hana_plan_no,change_type,change_price,change_date,in_price,add_input_1,add_input_2,com_percent,com_point,company_type,regdate )"
	        ." values ('".$this->checkMysql($db, $arrVal["hana_plan_no"])
	            ."', '".$this->checkMysql($db, $arrVal["change_type"])
	            ."', '".$this->checkMysql($db, $arrVal["change_price"])
	            ."', '".$this->checkMysql($db, $arrVal["change_date"])
	            ."', '".$this->checkMysql($db, $arrVal["in_price"])
	            ."', '".$this->checkMysql($db, $arrVal["add_input_1"])
	            ."', '".$this->checkMysql($db, $arrVal["add_input_2"])
	            ."', '".$this->checkMysql($db, $arrVal["com_percent"])
	            ."', '".$this->checkMysql($db, $arrVal["com_point"])
	            ."', '".$this->checkMysql($db, $arrVal["company_type"])
	            ."', UNIX_TIMESTAMP())"
		;
	         
		return $db->query($sql);
	}
	
	function update($db, $uq, $key) {
	    
	    $sql =" update hana_plan_change"
			.$uq->getQuery($db)
	        ." where no = ".$this->quot($db, $key);
	        
		return $db->query($sql);
	}
	
	function delete($db, $key) {
	    if ($key) {
	    	$sql =" delete from hana_plan_change where no = ".$this->quot($db, $key);
			return $db->query($sql);
		}
	}	
}
?>