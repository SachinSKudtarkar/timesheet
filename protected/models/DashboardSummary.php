<?php

/**
 * Description of DashboardSummary
 *
 * @author Pratik Gotmare <pratikgotmare@ocatalog.com>
 */
class DashboardSummary {

    public static function getNddDeliveredOnDate($date) {
        $count = NddOutputMaster::model()->getNddsDeliveredOnDate($date);
        return $count;
    }

    public static function getUniqueNddDeliveredOnDate($date) {
        $count = NddOutputMaster::model()->getUniqueNddsDeliveredOnDate($date);
        return $count;
    }

    public static function getUniqueNLDNddDeliveredOnDate($date) {
        $count = NddAg1Outputmaster::model()->getUniqueNLDNddsDeliveredOnDate($date);
        return $count;
    }

    public static function getUniqueMetroNddDeliveredOnDate($date) {
        $count = NddMag1Outputmaster::model()->getUniqueMetroNddsDeliveredOnDate($date);
        return $count;
    }

    public static function getNddDeliveredTillDate($date) {
        $count = NddOutputMaster::model()->getNddsDeliveredTillDate($date);
        return $count;
    }

    public static function getUniqueNddDeliveredTillDate($date) {
        $count = NddOutputMaster::model()->getUniqueNddsDeliveredTillDate($date);
        return $count;
    }

    public static function getUniqueNLDNddDeliveredTillDate($date) {
        $count = NddAg1Outputmaster::model()->getUniqueNLDNddsDeliveredTillDate($date);
        return $count;
    }

    public static function getUniqueMetroNddDeliveredTillDate($date) {
        $count = NddMag1Outputmaster::model()->getUniqueMetroNddsDeliveredTillDate($date);
        return $count;
    }

    public static function getSitesIntegratedOnDate($date) {

        return $result = Yii::app()->db->createCommand()
                ->select('IFNULL(sum(if(im.status="1",1,0)),0) as unique_devices, COUNT(IFNULL(t.id,0)) as device_integrated')
                ->from('tbl_assigned_sites as t JOIN tbl_ip_master AS im ON (t.site_id = im.id) ')
                ->where('t.status = "1" AND DATE(t.date_completed) = "' . $date . '" ')
                ->queryRow();
    }

    public static function getAG1SitesIntegratedOnDate($date) {

        return $result = Yii::app()->db->createCommand()
                ->select('IFNULL(sum(if(im.status="1",1,0)),0) as unique_devices, COUNT(IFNULL(t.id,0)) as device_integrated')
                ->from('tbl_assigned_sites as t JOIN tbl_ip_master AS im ON (t.site_id = im.id) ')
                ->where('t.status = "1" AND DATE(t.date_completed) = "' . $date . '" AND (im.ndd_source_type = "M_AG1" OR im.ndd_source_type = "NLD_AG1")')
                ->queryRow();
    }

    public static function getCSSSitesIntegratedOnDate($date) {

        return $result = Yii::app()->db->createCommand()
                ->select('IFNULL(sum(if(im.status="1",1,0)),0) as unique_devices, COUNT(IFNULL(t.id,0)) as device_integrated')
                ->from('tbl_assigned_sites as t JOIN tbl_ip_master AS im ON (t.site_id = im.id) ')
                ->where('t.status = "1" AND DATE(t.date_completed) = "' . $date . '" AND im.ndd_source_type = "CSS"')
                ->queryRow();
    }

    public static function getSitesIntegratedTillDate($date) {
        return $result = Yii::app()->db->createCommand()
                ->select('IFNULL(sum(if(im.status="1",1,0)),0) as unique_devices, COUNT(IFNULL(t.id,0)) as device_integrated')
                ->from('tbl_assigned_sites as t JOIN tbl_ip_master AS im ON (t.site_id = im.id) ')
                ->where('t.status = "1" AND DATE(t.date_completed)<= "' . $date . '" ')
                ->queryRow();
    }

    public static function getAG1SitesIntegratedTillDate($date) {
        return $result = Yii::app()->db->createCommand()
                ->select('IFNULL(sum(if(im.status="1",1,0)),0) as unique_devices, COUNT(IFNULL(t.id,0)) as device_integrated')
                ->from('tbl_assigned_sites as t JOIN tbl_ip_master AS im ON (t.site_id = im.id) ')
                ->where('t.status = "1" AND DATE(t.date_completed)<= "' . $date . '" AND (im.ndd_source_type = "M_AG1" OR im.ndd_source_type = "NLD_AG1")')
                ->queryRow();
    }

    public static function getCSSSitesIntegratedTillDate($date) {
        return $result = Yii::app()->db->createCommand()
                ->select('IFNULL(sum(if(im.status="1",1,0)),0) as unique_devices, COUNT(IFNULL(t.id,0)) as device_integrated')
                ->from('tbl_assigned_sites as t JOIN tbl_ip_master AS im ON (t.site_id = im.id) ')
                ->where('t.status = "1" AND DATE(t.date_completed)<= "' . $date . '" AND im.ndd_source_type = "CSS"')
                ->queryRow();
    }

    public static function get1ACompletedOnDate($date) {
        
    }

    public static function get1ACompletedTillDate($date) {
        
    }

    public static function get1BCompletedOnDate($date) {
        $sql = "SELECT 
                    COUNT(*) AS cnt
                FROM
                    tbl_1b_batch_status tbs 
                        INNER JOIN
                    tbl_1b_qa_manager tqm ON (tbs.area_id = tqm.area_id
                        AND tbs.batch_no = tqm.batch_no)
                WHERE
                        tbs.final_status = 2
                        AND DATE(tqm.emp_response_dt) = '" . $date . "'
                        ";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data['cnt'];
    }

    public static function getAG1CSS1BCompletedOnDate($date, $type = null) {
        $sql = "SELECT 
                    COUNT(*) AS cnt
                FROM
                    tbl_1b_batch_status tbs 
                        INNER JOIN
                    tbl_1b_qa_manager tqm ON (tbs.area_id = tqm.area_id
                        AND tbs.batch_no = tqm.batch_no)
                WHERE
                        tbs.final_status = 2
                        AND DATE(tqm.emp_response_dt) = '" . $date . "' AND tbs.device_type = '" . $type . "'";

        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data['cnt'];
    }

    public static function getAG1CSS1BCompletedTillDate($date, $type = null) {
        $sql = "SELECT 
                    COUNT(*) AS cnt
                FROM
                    tbl_1b_batch_status tbs 
                        INNER JOIN
                    tbl_1b_qa_manager tqm ON (tbs.area_id = tqm.area_id
                        AND tbs.batch_no = tqm.batch_no)
                WHERE
                        tbs.final_status = 2
                        AND DATE(tqm.emp_response_dt) <= '" . $date . "' AND tbs.device_type = '" . $type . "'";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data['cnt'];
    }

    public static function get1BCompletedTillDate($date) {
        $sql = "SELECT 
                    COUNT(*) AS cnt
                FROM
                    tbl_1b_batch_status tbs 
                        INNER JOIN
                    tbl_1b_qa_manager tqm ON (tbs.area_id = tqm.area_id
                        AND tbs.batch_no = tqm.batch_no)
                WHERE
                        tbs.final_status = 2
                        AND DATE(tqm.emp_response_dt) <= '" . $date . "'
                        ";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return $data['cnt'];
    }

    public static function getBuildSitesCompletedOnDate($date) {
        $condition = 'DATE(site_survey_completed_date)=:site_survey_completed_date AND site_survey_status = "1"';
        $params = array('site_survey_completed_date' => $date);
        return SiteMaster::model()->count($condition, $params);
    }

    public static function getBuildSitesCompletedTillDate($date) {
        $condition = 'DATE(site_survey_completed_date)<=:site_survey_completed_date AND site_survey_status = "1"';
        $params = array('site_survey_completed_date' => $date);
        return SiteMaster::model()->count($condition, $params);
    }

    public static function getSiteInstallationCompletedOnDate($date) {
        $condition = 'DATE(cold_installation_date)=:cold_installation_date AND cold_installation = "1"';
        $params = array('cold_installation_date' => $date);
        return SiteInstDepStatus::model()->count($condition, $params);
    }

    public static function getSiteInstallationCompletedTillDate($date) {
        $condition = 'DATE(cold_installation_date) <=:cold_installation_date AND cold_installation = "1"';
        $params = array('cold_installation_date' => $date);
        return SiteInstDepStatus::model()->count($condition, $params);
    }

    public static function getHotoCompletedOnDate($date) {
        $condition = ' ready_for_hoto=:ready_for_hoto  AND DATE(hoto_for_ready_date)=:hoto_for_ready_date';
        $params = array('ready_for_hoto' => 'Yes', 'hoto_for_ready_date' => $date);
        //return HotoRedinessMaster::model()->count($condition, $params);

        $sql = "SELECT COUNT(b.batch_name) AS total
                FROM hoto_readiness_batch_details_view AS b
                INNER JOIN hoto_rediness_master AS t ON (t.batch_name = b.batch_name)
                WHERE DATE(b.approved_date) = '" . $date . "'
                      AND b.batch_status IN (1, 2)";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public static function getAG1CSSHotoCompletedOnDate($date, $type = NULL) {
        $condition = ' ready_for_hoto=:ready_for_hoto  AND DATE(hoto_for_ready_date)=:hoto_for_ready_date';
        $params = array('ready_for_hoto' => 'Yes', 'hoto_for_ready_date' => $date);
        //return HotoRedinessMaster::model()->count($condition, $params);

        $sql = "SELECT COUNT(b.batch_name) AS total
                FROM hoto_readiness_batch_details_view AS b
                INNER JOIN hoto_rediness_master AS t ON (t.batch_name = b.batch_name)
                WHERE DATE(b.approved_date) = '" . $date . "'
                      AND b.batch_status IN (1, 2) AND SUBSTRING(t.host_name,9,3) = '".$type."'";
        $data = Yii::app()->db->createCommand($sql)->queryRow(); 
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public static function getAG1CSSHotoCompletedTillDate($date, $type = NULL) {
        //$condition = ' ready_for_hoto=:ready_for_hoto AND DATE(hoto_for_ready_date)<=:hoto_for_ready_date ';
        //$params = array('ready_for_hoto' => 'Yes', 'hoto_for_ready_date' => $date );
        //return HotoRedinessMaster::model()->count($condition, $params); 
        $sql = "SELECT COUNT(b.batch_name) AS total
                FROM hoto_readiness_batch_details_view AS b
                INNER JOIN hoto_rediness_master AS t ON (t.batch_name = b.batch_name)
                WHERE DATE(b.approved_date) <= '" . $date . "'
                        AND b.batch_status IN (1, 2) AND SUBSTRING(t.host_name,9,3) = '".$type."'";
        $data = Yii::app()->db->createCommand($sql)->queryRow(); 
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public static function getHotoCompletedTillDate($date) {
        //$condition = ' ready_for_hoto=:ready_for_hoto AND DATE(hoto_for_ready_date)<=:hoto_for_ready_date ';
        //$params = array('ready_for_hoto' => 'Yes', 'hoto_for_ready_date' => $date );
        //return HotoRedinessMaster::model()->count($condition, $params); 
        $sql = "SELECT COUNT(b.batch_name) AS total
                FROM hoto_readiness_batch_details_view AS b
                INNER JOIN hoto_rediness_master AS t ON (t.batch_name = b.batch_name)
                WHERE DATE(b.approved_date) <= '" . $date . "'
                        AND b.batch_status IN (1, 2)";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public static function getSO6CompletedOnDate($date) {
        $sql = "SELECT SUM(e.total) AS total 
                FROM (
                    SELECT COUNT(so6_id) AS total
                    FROM tbl_so6_soap_log
                    WHERE DATE(created_at) = '" . $date . "' AND return_status = 1
                    GROUP BY neid
                ) AS e";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

    public static function getSO6CompletedTillDate($date) {
        $sql = "SELECT SUM(e.total) AS total 
                FROM (
                    SELECT COUNT(so6_id) AS total
                    FROM tbl_so6_soap_log
                    WHERE DATE(created_at) <= '" . $date . "' AND return_status = 1
                    GROUP BY neid
                ) AS e";
        $data = Yii::app()->db->createCommand($sql)->queryRow();
        return !empty($data['total']) ? $data['total'] : 0;
    }

}

?>
