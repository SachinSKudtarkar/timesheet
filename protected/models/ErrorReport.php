<?php

/**
 * This is the model class for table "tbl_error_report".
 *
 * The followings are the available columns in table 'tbl_error_report':
 * @property integer $id
 * @property string $error_title
 * @property integer $step
 * @property string $device_sap_id
 * @property string $hostname
 * @property string $loopback
 * @property string $deadline_to_fix_error
 * @property string $status
 * @property string $blocked
 * @property string $reason_blocked
 * @property string $block_reported_by_date_and_details
 * @property string $created_date
 * @property integer $created_by
 * @property integer $is_deleted
 */
class ErrorReport extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_error_report';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('error_title, device_sap_id, hostname, loopback, deadline_to_fix_error, status, blocked, reason_blocked, block_reported_by_date_and_details, created_date, created_by, is_deleted', 'required'),
            array('created_by, is_deleted, step', 'numerical', 'integerOnly' => true),
            array('error_title', 'length', 'max' => 300),
            array('type', 'length', 'max' => 50),
            array('device_sap_id, hostname', 'length', 'max' => 18),
            array('loopback', 'length', 'max' => 20),
            array('deadline_to_fix_error, status, reason_blocked, block_reported_by_date_and_details', 'length', 'max' => 255),
            array('blocked', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, error_title, step, device_sap_id, hostname, loopback, deadline_to_fix_error, status, blocked, reason_blocked, block_reported_by_date_and_details, created_date, created_by, is_deleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'SrNo',
            'type' => 'Type',
            'error_title' => 'Error Title',
            'step' => 'Step',
            'device_sap_id' => 'Device Sap',
            'hostname' => 'Hostname',
            'loopback' => 'Loopback',
            'deadline_to_fix_error' => 'Deadline To Fix Error',
            'status' => 'Status',
            'blocked' => 'Blocked',
            'reason_blocked' => 'Reason Blocked',
            'block_reported_by_date_and_details' => 'Block Reported By Date And Details',
            'created_date' => 'Created Date',
            'created_by' => 'Created By',
            'is_deleted' => 'Is Deleted',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('error_title', $this->error_title, true);
        $criteria->compare('step', $this->step, true);
        $criteria->compare('device_sap_id', $this->device_sap_id, true);
        $criteria->compare('hostname', $this->hostname, true);
        $criteria->compare('loopback', $this->loopback, true);
        $criteria->compare('deadline_to_fix_error', $this->deadline_to_fix_error, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('blocked', $this->blocked, true);
        $criteria->compare('reason_blocked', $this->reason_blocked, true);
        $criteria->compare('block_reported_by_date_and_details', $this->block_reported_by_date_and_details, true);
        $criteria->compare('created_date', $this->created_date, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('is_deleted', $this->is_deleted);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getCircleCodeFromDetails($hostname, $loopback) {
        $result = Yii::app()->db->createCommand()
                ->select('cm.circle_code')
                ->from('tbl_ip_master ip')
                ->join('tbl_site_master sm', 'ip.site_sap_name = sm.site_sap_id')
                ->join('tbl_circle_master cm', 'cm.id = sm.circle_id')
                ->where('ip.ip_address = :ipaddress AND hostname = :hostname ', array(':ipaddress' => $loopback, ':hostname' => $hostname))
                ->queryRow();

        return trim($result['circle_code']);
    }

    public function dumbDetailsInErrorReport($hostname, $loopback, $sapid) {
//            $created_date = date('Y-m-d');
//            $created_by = 
//            $insertQuery = "INSERT INTO tbl_error_report (device_sap_id, hostname,loopback) VALUES('{$sapid}','{$hostname}','{$loopback}');";
//            Yii:app()->db->createCommand($insertQuery)->execute();

        if (!empty($hostname) && !empty($loopback) && !empty($sapid)) {
            $created_at = date("Y-m-d h:i:s");
            $created_by = Yii::app()->session['login']['user_id'];
            try {
                $this->isNewRecord = true;
                $this->created_date = $created_at;
                $this->attributes = array('device_sap_id' => $sapid, 'hostname' => $hostname, 'loopback' => $loopback, 'created_date' => $created_at, 'created_by' => $created_by);
                // CHelper::debug($this->attributes);
                if (!$this->save()) {
                    throw new CDbException('Transaction failed. Could not create request.');
                }
            } catch (Exception $e) {
                return false;
            }
            return 'done';
        }
        return false;
    }

    public function dumbDataFromPlanVsBuilt() {
        // $sql = "SELECT distinct(max(iteration)) as mxiteration,loopback,sap_id,host_name,`status_1`,`status_2`,`status_3`,`status_4`,`status_1_comment`,`status_2_comment`,`status_3_comment`,`status_4_comment` FROM `tbl_plan_vs_built_verification` group by `loopback` limit 10";
        $sql = "SELECT t.iteration,t.loopback,t.sap_id,t.host_name,t.status_1,t.status_2,t.status_3,t.status_4,t.status_1_comment,t.status_2_comment,t.status_3_comment,t.status_4_comment,t.type FROM tbl_plan_vs_built_verification AS t
                INNER JOIN
                (SELECT MAX(iteration) AS iteration, loopback FROM tbl_plan_vs_built_verification GROUP BY loopback)
                AS e
                ON (t.iteration = e.iteration AND t.loopback = e.loopback)
                WHERE t.type  IN('NLDAG1','MAG1')";
        $results = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($results as $result) {
            $sql = "SELECT id,error_title,step,status from tbl_error_report where loopback ='" . $result['loopback'] . "' and hostname = '" . $result['host_name'] . "'";
            $innerResults = Yii::app()->db->createCommand($sql)->queryAll();
            if (!empty($innerResults)) {

                $id0 = $innerResults[0]['id'];
                $id1 = $innerResults[1]['id'];
                $id2 = $innerResults[2]['id'];
                $step0 = $innerResults[0]['step'];
                $step1 = $innerResults[1]['step'];
                $step2 = $innerResults[2]['step'];
                $status1 = $result['status_1'];
                $status2 = $result['status_2'];
                $status3 = $result['status_3'];
                $error1 = $result['status_1_comment'];
                $error2 = $result['status_2_comment'];
                $error3 = $result['status_3_comment'];
                $query = "UPDATE tbl_error_report SET status = 
                        (CASE WHEN step = $step0 THEN '$status1'
                              WHEN step = $step1 THEN '$status2'
                              WHEN step = $step2 THEN '$status3'
                         end),error_title= 
                        (CASE WHEN step = $step0 THEN '$error1'
                              WHEN step = $step1 THEN '$error2'
                              WHEN step = $step2 THEN '$error3'
                         end)"
                        . "WHERE id IN($id0,$id1,$id2);";
                
                $command = Yii::app()->db->createCommand($query)->execute();
            } else {
                $created_at = date("Y-m-d h:i:s");
                $created_by = Yii::app()->session['login']['user_id'];
                $sapid = $result['sap_id'];
                $hostname = $result['host_name'];
                $loopback = $result['loopback'];
                $type = $result['type'];
                $importData = array();

                for ($i = 1; $i < 4; $i++) {
                    $model = new ErrorReport();
                    $error_title = isset($result['status_' . $i . '_comment']) ? $result['status_' . $i . '_comment'] : "";
                    $status = isset($result['status_' . $i]) ? $result['status_' . $i] : "";
                    //$status = ($status == 1)?'Fixed':'Not Fixed';
                    $step = $i;
                    $model->type = $type;
                    $model->error_title = $error_title;
                    $model->step = $step;
                    $model->status = $status;
                    $model->device_sap_id = $sapid;
                    $model->hostname = $hostname;
                    $model->loopback = $loopback;
                    $model->created_date = $created_at;
                    $model->created_by = $created_by;
                    $importData[] = $model->getAttributes();
                }

                if (!empty($importData)) {
                    $transaction = $model->getDbConnection()->beginTransaction();
                    try {
                        $query = $model->commandBuilder->createMultipleInsertCommand($model->tableName(), $importData);
                        $query->execute();
                        $transaction->commit();
                    } catch (Exception $ex) {
                        $transaction->rollback();
                        throw $ex;
                    }
                }
            }
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ErrorReport the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
