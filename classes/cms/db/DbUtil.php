<?php
include $_SERVER['DOCUMENT_ROOT']."/classes/cms/CmsConfig.php";

class DbUtil
{
    static function getConnection() {
        @ $db = new mysqli(CmsConfig::$mysql_host, CmsConfig::$mysql_user, CmsConfig::$mysql_password, CmsConfig::$mysql_database);
        
        if ( $db->connect_errno ) {
            throw new Exception("DbUtil getConnection Error!");
        } else {
            // 한글처리.
//            mysqli_query($db, 'set names euckr');
            mysqli_query($db, 'set names utf8');
            return $db;
        }
    }

    static function freeProcedureResult($db) {
        while ( $db->more_results() ) {
            if ( $db->next_result() ) {
                if ( $use_result = $db->use_result() ) {
                    $use_result->close();
                }
            }
        }
    }
}
?>