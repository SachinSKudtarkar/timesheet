<?php

/**
 * This is the model class for table "ndd_ag3_ag2_links".
 *
 * The followings are the available columns in table 'ndd_ag3_ag2_links':
 * @property string $id
 * @property string $from_hostname
 * @property string $to_hostname
 * @property string $from_ipv4
 * @property string $to_ipv4
 * @property string $from_ipv6
 * @property string $to_ipv6
 * @property string $from_logical_port
 * @property string $to_logical_port
 * @property string $from_physical_port
 * @property string $to_physical_port
 * @property string $created_at
 * @property string $modified_at
 * @property integer $is_active
 */
class ClockingDelta extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ndd_ag3_ag2_links';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('from_hostname, to_hostname, from_physical_port, to_physical_port', 'required'),
            array('is_active', 'numerical', 'integerOnly' => true),
            array('from_hostname, to_hostname', 'length', 'max' => 14, 'min' => 14, 'on' => 'update'),
            array('from_ipv4, to_ipv4', 'length', 'max' => 20),
            array('from_ipv6, to_ipv6, from_logical_port, to_logical_port', 'length', 'max' => 100),
            array('from_physical_port, to_physical_port', 'length', 'max' => 200),
            array('from_ipv4, to_ipv4', 'application.extensions.ipvalidator.IPValidator', 'version' => 'v4'),
            array('from_hostname, to_hostname,from_ipv4, to_ipv4', 'required', 'on' => 'create'),
//            array('from_ipv4, to_ipv4', 'customValidation'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, from_hostname, to_hostname, from_ipv4, to_ipv4, from_ipv6, to_ipv6, from_logical_port, to_logical_port, from_physical_port, to_physical_port', 'safe', 'on' => 'search'),
            array('from_ipv4', 'application.extensions.ipvalidator.IPValidator', 'version' => 'v4'),
            array('from_ipv6,to_ipv6', 'application.extensions.ipvalidator.IPValidator', 'version' => 'v6'),
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
            'from_hostname' => 'From Hostname',
            'to_hostname' => 'To Hostname',
            'from_ipv4' => 'From Ipv4',
            'to_ipv4' => 'To Ipv4',
            'from_ipv6' => 'From Ipv6',
            'to_ipv6' => 'To Ipv6',
            'from_logical_port' => 'From Logical Port',
            'to_logical_port' => 'To Logical Port',
            'from_physical_port' => 'From Physical Port',
            'to_physical_port' => 'To Physical Port',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'is_active' => 'Is Active',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('from_hostname', $this->from_hostname, true);
        $criteria->compare('to_hostname', $this->to_hostname, true);
        $criteria->compare('from_ipv4', $this->from_ipv4, true);
        $criteria->compare('to_ipv4', $this->to_ipv4, true);
        $criteria->compare('from_ipv6', $this->from_ipv6, true);
        $criteria->compare('to_ipv6', $this->to_ipv6, true);
        $criteria->compare('from_logical_port', $this->from_logical_port, true);
        $criteria->compare('to_logical_port', $this->to_logical_port, true);
        $criteria->compare('from_physical_port', $this->from_physical_port, true);
        $criteria->compare('to_physical_port', $this->to_physical_port, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('modified_at', $this->modified_at, true);
        $criteria->compare('is_active', $this->is_active);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return NddAg3Ag2Links the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getCCRToCSRData(){
        $model = new ClockingDelta();
        $criteria = new CDbCriteria();
        $criteria->select = "id, from_hostname, to_hostname, from_ipv4, to_ipv4, from_ipv6, to_ipv6";
        $criteria->condition = "substring(from_hostname, 9,3) = 'CCR' AND substring(to_hostname, 9,3) = 'CSR' ".
                               " AND is_active=0 AND is_deleted=0";
        $results = $this->findAll($criteria);
         
        $data_from_hostname = array();
        
        foreach ($results as $rows) {
            $data_from_hostname[$rows->from_hostname][] = $rows;
        }
        echo '<br><br>CCR to CSR :'; echo count($data_from_hostname);
        return $data_from_hostname;
    }

    public function getCCRToCCRData(){
        $criteria = new CDbCriteria();
        $criteria->select = "id, from_hostname, to_hostname, from_ipv4, to_ipv4, from_ipv6, to_ipv6";
        $criteria->condition = "substring(from_hostname, 9,3) = 'CCR' AND substring(to_hostname, 9,3) = 'CCR' ".
                               " AND is_active=0 AND is_deleted=0";

        $results1 = $this->findAll($criteria);
        
        $data_to_hostname = array();
        
        foreach ($results1 as $rows) {
            $data_to_hostname[$rows->from_hostname][] = $rows;
        }
        
        echo '<br><br> CCR to CCR: '; echo count($data_to_hostname);
        return $data_to_hostname;
    }
    
    public function getCSRToCSRData(){
        $criteria = new CDbCriteria();
        $criteria->select = "id, from_hostname, to_hostname, from_ipv4, to_ipv4, from_ipv6, to_ipv6";
        $criteria->condition = "substring(from_hostname, 9,3) = 'CSR' AND substring(to_hostname, 9,3) = 'CSR' ".
                               " AND is_active=0 AND is_deleted=0";

        $results1 = $this->findAll($criteria);
        
        $data_to_hostname = array();
        
        foreach ($results1 as $rows) {
            $data_to_hostname[$rows->from_hostname][] = $rows;
        }
        
        echo '<br><br> CSR to CSR: '; echo count($data_to_hostname);
        return $data_to_hostname;
    }
    
    public function getClockingDeltaData(){
        $data = array();
        $data['CCRtoCSR'] = $this->getCCRToCSRData();
        $data['CCRtoCCR'] = $this->getCCRToCCRData();
        $data['CSRtoCSR'] = $this->getCSRToCSRData();
        return $data;
    }
    
    public function getDeviceList($CsrCcr = false){
        if($CsrCcr){
            $IN = "'CSR','CCR'";
        }else{
            $IN = "'CSR','CCR','AAR'";
        }
        
        $sql= "Select distinct from_hostname as hostname
                    FROM ndd_ag3_ag2_links where substring(from_hostname, 9,3) IN(".$IN.") AND is_active=0 AND is_deleted=0	
                            UNION 
                    Select distinct to_hostname as hostname
                            FROM ndd_ag3_ag2_links where substring(to_hostname, 9,3) IN(".$IN.") AND is_active=0 AND is_deleted=0";
        
        $resultSet = Yii::app()->db->createCommand($sql)->queryAll();
        return $resultSet;
    }
    
    public function getClockingDelta($region,$CsrCcr = false){
        $where  = '';
        
        if($CsrCcr){
            $IN = "'CSR','CCR'";
        }else{
            $IN = "'CSR','CCR','AAR'";
            if(!empty($region))
                $where  =  " AND from_region = '".$region."'";
            else 
                return array();
        
             if(!empty($from_region) && !empty($to_region))
                $where  =  " AND ( from_region = '".$region."' OR to_region = '".$region."' )";
            
        }
        
        $sql= "SELECT * 
                FROM ndd_ag3_ag2_links 
                        WHERE substring(from_hostname, 9,3) IN(".$IN.") 
                                AND substring(to_hostname, 9,3) IN(".$IN.")
                                AND is_active=0 AND is_deleted=0 ".$where;
        
        $resultSet = Yii::app()->db->createCommand($sql)->queryAll();
        return $resultSet;
    }
    
    public function getDeviceRegions(){
        $IN = "'CSR','CCR','AAR'";
        $sql= "Select distinct from_region as region
                    FROM ndd_ag3_ag2_links where substring(from_hostname, 9,3) IN(".$IN.") AND is_active=0 AND is_deleted=0 AND from_region <> ''	
                            UNION 
                    Select distinct to_region as region
                            FROM ndd_ag3_ag2_links where substring(to_hostname, 9,3) IN(".$IN.") AND is_active=0 AND is_deleted=0 AND to_region <> ''";
        
        $resultSet = Yii::app()->db->createCommand($sql)->queryAll();
        return $resultSet;
    }
    
    public function getNldAg1Data($region,$post_link_no = null){
        
        if(!empty($post_link_no))
            $post_link_no = " AND t.link_no ='".$post_link_no."' ";
        
        $sql= "SELECT t.hostname, t.link_no, r.link_sequence_no, t.loopback0, r.ip_region, r.link_sequence_no, r.east_neighbour_hostname, r.west_neighbour_hostname FROM 
                            ndd_ag1_outputmaster AS t
                                INNER JOIN 
                            ndd_ag1_input AS r ON (t.input_id = r.id AND r.is_active = 1) WHERE t.hostname <> '' AND r.ip_region = '".$region."' ".$post_link_no." order by t.region,r.link_sequence_no";
        
        $resultSet = Yii::app()->db->createCommand($sql)->queryAll();
        return $resultSet;
    }
    
    public function getNldAg1IpRegions(){
        $sql= "SELECT t.region FROM 
                            ndd_ag1_outputmaster AS t
                                INNER JOIN 
                            ndd_ag1_input AS r ON (t.input_id = r.id AND r.is_active = 1) group by region";
        
        $resultSet = Yii::app()->db->createCommand($sql)->queryAll();
        return $resultSet;
    }
    
    public function getNldAg1Links($region){
        $sql= "SELECT t.region,t.link_no FROM 
                            ndd_ag1_outputmaster AS t
                                INNER JOIN 
                            ndd_ag1_input AS r ON (t.input_id = r.id AND r.is_active = 1) WHERE t.hostname <> '' AND t.region = '".$region."' group by t.link_no";
        
        $resultSet = Yii::app()->db->createCommand($sql)->queryAll();
        return $resultSet;
    }
    
    public function getDeviceData($hostnames,$select_params = false, $gm_host_name = false, $master_slave_data = array(), $link_no = ''){
        $order_by = '';
        $join = '';
        if($select_params){
            $select_params = 't.hostname, t.modified_sapid as sapid, h.facid, h.gne_id as neid, t.loopback0 as loopback, r.link_no, r.east_neighbour_hostname, r.west_neighbour_hostname';
            $join = ' INNER JOIN ndd_host_name h on h.host_name = t.hostname ';
            if($gm_host_name){
                $order_by = ' Case when t.hostname = "'.$gm_host_name.'" THEN 1 END DESC, ';  
            }
        }else{
            $select_params = 't.hostname, r.router_type2, r.sapid, t.link_no, r.link_sequence_no, t.loopback0, r.ip_region, r.link_sequence_no, r.east_neighbour_hostname, r.west_neighbour_hostname';
        }
        $where_link = '';
        if($link_no != ''){
            $where_link = " and t.link_no = $link_no ";
        }
        $sql= "SELECT ".$select_params." FROM 
                            ndd_ag1_outputmaster AS t
                                INNER JOIN 
                            ndd_ag1_input AS r ON (t.input_id = r.id) 
                            ".$join."
                            WHERE t.hostname IN (".$hostnames.") AND r.is_active = 1  $where_link
                            group by t.hostname order by ".$order_by." t.region,r.link_sequence_no ";
        $resultSet = Yii::app()->db->createCommand($sql)->queryAll();

        if($resultSet && is_array($resultSet) && !empty($resultSet)){
            if(!empty($master_slave_data)){
                 //echo("<pre>");print_r($resultSet);print_r($master_slave_data);
                foreach ($resultSet as $key => $value) {

                    $resultSet[$key] = $resultSet[$key]+$master_slave_data[$value['hostname']];
                    
                    if(isset($master_slave_data[$value['hostname']]['master_sapid']) && !empty($master_slave_data[$value['hostname']]['master_sapid']))
                        $resultSet[$key]['master_sapid'] = $master_slave_data[$value['hostname']]['master_sapid'];
                    else{
                        if(array_search($resultSet[$key]['master_hostname'], array_column($resultSet, 'hostname'))){
                            $resultSet[$key]['master_sapid'] = $resultSet[array_search($resultSet[$key]['master_hostname'], array_column($resultSet, 'hostname'))]['sapid'];    
                        }else{
                            $sql1 = "SELECT t.modified_sapid as sapid 
                                 FROM ndd_ag1_outputmaster AS t
                                 INNER JOIN 
                                 ndd_ag1_input AS r ON t.input_id = r.id AND r.is_active = 1 
                                 WHERE t.hostname = '".$resultSet[$key]['master_hostname']."'";
                            //$sql1 = "SELECT sapid FROM nld_adva_gm_output_master WHERE hostname = '".$resultSet[$key]['master_hostname']."'";
                            $master_sapid = Yii::app()->db->createCommand($sql1)->queryRow();
                            if(!empty($master_sapid)){
                                $resultSet[$key]['master_sapid'] = $master_sapid['sapid'];
                            }else{
                                $resultSet[$key]['master_sapid'] = '';
                            }
                        }
                    }

                    if(isset($master_slave_data[$value['hostname']]['slave_sapid']) && !empty($master_slave_data[$value['hostname']]['slave_sapid']))
                        $resultSet[$key]['slave_sapid'] = $master_slave_data[$value['hostname']]['slave_sapid'];
                    else{
                        if(array_search($resultSet[$key]['slave_hostname'], array_column($resultSet, 'hostname'))){
                            $resultSet[$key]['slave_sapid'] = $resultSet[array_search($resultSet[$key]['slave_hostname'], array_column($resultSet, 'hostname'))]['sapid'];    
                        }else{
                            $sql1 = "SELECT t.modified_sapid as sapid 
                                 FROM ndd_ag1_outputmaster AS t
                                 INNER JOIN 
                                 ndd_ag1_input AS r ON t.input_id = r.id AND r.is_active = 1 
                                 WHERE t.hostname = '".$resultSet[$key]['slave_hostname']."'";
                            //$sql1 = "SELECT sapid FROM nld_adva_gm_output_master WHERE hostname = '".$resultSet[$key]['slave_hostname']."'";
                            $slave_sapid = Yii::app()->db->createCommand($sql1)->queryRow();
                            if(!empty($slave_sapid)){
                                $resultSet[$key]['slave_sapid'] = $slave_sapid['sapid'];
                            }else{
                                $resultSet[$key]['slave_sapid'] = '';
                            }
                        }
                    }
                
                }
                return $resultSet;
            }else{
                return $resultSet;    
            }
            
        }else{
            return false;
        }// END IF ELSE
    
    }// END FUNCTION
    
}
