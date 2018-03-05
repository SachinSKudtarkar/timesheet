<?php

require_once __DIR__ . '/../components/TestCases/NIPParser.php';

/**
 * This is the model class for table "tbl_ip_master".
 *
 * The followings are the available columns in table 'tbl_ip_master':
 * @property integer $id
 * @property string $ip_address
 * @property string $hostname 
 * @property integer $site_sap_id
 * @property string $created_at
 * @property string $updated_at
 */
class IpMaster extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_ip_master';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('ip_address', 'required'),
            // array('hostname', 'required'),
            // array('ip_address', 'unique'),
            // array('hostname', 'unique'),
            //array('site_sap_id', 'numerical', 'integerOnly' => true),
            array('id, ip_address, hostname, site_sap_name, site_sap_id,created_at, updated_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, ip_address, hostname, site_sap_name, site_sap_id, created_at, updated_at', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'ip_address' => 'Ip Address',
            'hostname' => 'Host',
            'site_sap_id' => 'Site Sap',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
        $criteria->compare('ip_address', $this->ip_address, true);
        $criteria->compare('hostname', $this->hostname, true);
        $criteria->compare('site_sap_id', $this->site_sap_id);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return IpMaster the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        parent::beforeSave();
        $criteria = new CDbCriteria;
        $criteria->compare('ip_address', $this->ip_address, true);
        $criteria->compare('hostname', $this->hostname, true);
        $result = $this->find($criteria);
        if (!empty($result->id)) {
            $this->id = $result->id;
            $this->isNewRecord = false;
        }
        if (!$this->id) {
            $this->setAttribute('created_at', date('Y-m-d H:i:s'));
        }
        $this->setAttribute('updated_at', date('Y-m-d H:i:s'));
        //return $this->id;
    }

    /**
     * Get Ip address and hostname from NIP File
     * 
     * @param string $dirPath
     * @return array
     */
    public function getNIPHostDetails($dirPath) {
        $dirPath = rtrim($dirPath, "/");
        $_parser = new NIPParser();
        $_parser->setFile($dirPath . "/" . NIPParser::NIP_FILE_NAME);
        $_parser->parse();
        $ip_address = $_parser->getIpv4Address();
        $hostname = $_parser->getHostname();
        return array('hostname' => $hostname, 'ip_address' => $ip_address, 'result' => $_parser->getResult());
    }

    /**
     * Get NIP File
     */
    public function getNIPFile($dirPath) {
        $dirPath = rtrim($dirPath, "/");
        return $dirPath . "/" . NIPParser::NIP_FILE_NAME;
    }

    /**
     * Get Lat iteration for ip
     * 
     * @param int $ip_id
     * @return int
     */
    public function getLastIteration($ip_id = NULL) {
        if (!$ip_id) {
            $ip_id = $this->id;
        }
        $iteration = 0;
        if ($ip_id) {
            $criteria = new CDbCriteria;
            $criteria->select = "MAX(iteration) as iteration";
            $criteria->compare('ip_id', $ip_id, true);
            $result = PlanResults::model()->find($criteria);

            if (!empty($result)) {
                $iteration = $result->iteration;
            }
        }
        return $iteration;
    }

    public function getIpHostList() {
        return CHtml::listData(PlanResults::model()->with('tblIpMaster')->findAll(array('group' => 't.ip_id')), 'tblIpMaster.id', 'tblIpMaster.ipHost');
    }

    public function getIpHost() {
        return $this->ip_address . " (" . $this->hostname . ")";
    }

    public function getIpIterationsList($ip_id) {
        $iterations = array();
        if (!$ip_id) {
            $ip_id = $this->id;
        }
        if ($ip_id) {
            $criteria = new CDbCriteria;
            $criteria->select = "iteration";
            $criteria->compare('ip_id', $ip_id, true);
            $criteria->group = "t.ip_id,t.iteration";
            $criteria->order = "t.iteration";

            $results = PlanResults::model()->findAll($criteria);

            return CHtml::listData($results, 'iteration', 'iteration');
        }
        return $iterations;
    }

    /**
     * Get device by hostname
     * 
     * @param string $hostname
     */
    public function getDeviceByHostname($hostname) {
        $result = false;
        if (!empty($hostname) && strlen($hostname) >= 11) {
            $device_code = substr($hostname, 8, 3);
            $result = DeviceMaster::model()->getDeviceByDeviceCode($device_code);
        }
        return $result;
    }

}
