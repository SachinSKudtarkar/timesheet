<?php

/**
 * This is the model class for table "tbl_dc_server_info".
 *
 * The followings are the available columns in table 'tbl_dc_server_info':
 * @property integer $id
 * @property integer $conn_tor_id
 * @property string $ilo_hostname
 * @property string $serial_no
 * @property string $model
 * @property string $mac
 * @property string $city
 * @property string $dc
 * @property integer $server_hall
 * @property integer $row
 * @property integer $rack
 * @property string $u_location
 * @property integer $ps1
 * @property integer $ps2
 * @property integer $ps3
 * @property integer $ps4
 * @property integer $ps5
 * @property integer $ps6
 * @property integer $ps7
 * @property integer $ps8
 * @property integer $ps9
 * @property integer $ps10
 * @property integer $ps11
 * @property integer $ps12
 * @property string $nic_port_1
 * @property string $nic_port_2
 * @property string $nic_port_3 
 * @property string $nic_port_4
 * @property string $nic_port_5
 * @property string $nic_port_6
 * @property string $nic_port_7
 * @property string $nic_port_8
 * @property string $nic_port_9
 * @property string $nic_port_10
 * @property string $nic_port_11
 * @property string $nic_port_12
 * @property string $hbo_1
 * @property string $hbo_2
 * @property string $hbo_3
 * @property string $hbo_4
 * @property string $hbo_5
 * @property string $hbo_6
 * @property string $hbo_7
 * @property string $hbo_8
 * @property string $hbo_9
 * @property string $hbo_10
 * @property string $hbo_11
 * @property string $hbo_12
 * @property integer $created_by
 * @property string $created_at
 * @property integer $modified_by
 * @property string $modified_at
 */
class DcServerInfo extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_dc_server_info';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('conn_tor_id, server_hall, row, rack, ps1, ps2, ps3, ps4, ps5, ps6, ps7, ps8, ps9, ps10, ps11, ps12, created_by, modified_by', 'numerical', 'integerOnly' => true),
            array('ilo_hostname', 'length', 'max' => 18),
            array('mac', 'unique','message'=>'mac already exists!'),
            array('serial_no, model, mac', 'length', 'max' => 40),
            array('city', 'length', 'max' => 4),
            array('dc', 'length', 'max' => 3),
            array('rack,u_location', 'isRackUlocationExist'),
            array('u_location', 'length', 'min' => 0, 'max' => 2),
            array('u_location', 'in', 'range' => range(0, 45)),
            array('nic_port_1, nic_port_2, nic_port_3, nic_port_4, nic_port_5, nic_port_6, nic_port_7, nic_port_8, nic_port_9, nic_port_10, nic_port_11, nic_port_12, hbo_1, hbo_2, hbo_3, hbo_4, hbo_5, hbo_6, hbo_7, hbo_8, hbo_9, hbo_10, hbo_11, hbo_12', 'length', 'max' => 45),
            array('created_at, modified_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, conn_tor_id, ilo_hostname,ilo_ip, serial_no, model, mac, city, dc, server_hall, row, rack, u_location,model_variant,ilo_password, ps1, ps2, ps3, ps4, ps5, ps6, ps7, ps8, ps9, ps10, ps11, ps12, nic_port_1, nic_port_2, nic_port_3, nic_port_4, nic_port_5, nic_port_6, nic_port_7, nic_port_8, nic_port_9, nic_port_10, nic_port_11, nic_port_12, hbo_1, hbo_2, hbo_3, hbo_4, hbo_5, hbo_6, hbo_7, hbo_8, hbo_9, hbo_10, hbo_11, hbo_12, created_by, created_at, modified_by, modified_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Creater' => array(self::BELONGS_TO, 'Employee', 'created_by'),
            'Modifier' => array(self::BELONGS_TO, 'Employee', 'modified_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            //'conn_tor_id' => 'Conn Tor',
            'ilo_hostname' => 'ILO Hostname',
            'ilo_ip' => 'ILO IP',
            'serial_no' => 'Serial No',
            'model' => 'Model',
            'mac' => 'ILO Mac',
            'city' => 'City',
            'dc' => 'Dc',
            'server_hall' => 'Server Hall',
            'row' => 'Row',
            'rack' => 'Rack',
            'u_location' => 'U Location',
            'ps1' => 'PS1',
            'ps2' => 'PS2',
            'ps3' => 'PS3',
            'ps4' => 'PS4',
            'ps5' => 'PS5',
            'ps6' => 'PS6',
            'ps7' => 'PS7',
            'ps8' => 'PS8',
            'ps9' => 'PS9',
            'ps10' => 'PS10',
            'ps11' => 'PS11',
            'ps12' => 'PS12',
            'ilo_password' => 'ILO Password',
            'nic_port_1' => 'NIC Port 1',
            'nic_port_2' => 'NIC Port 2',
            'nic_port_3' => 'NIC Port 3',
            'nic_port_4' => 'NIC Port 4',
            'nic_port_5' => 'NIC Port 5',
            'nic_port_6' => 'NIC Port 6',
            'nic_port_7' => 'NIC Port 7',
            'nic_port_8' => 'NIC Port 8',
            'nic_port_9' => 'NIC Port 9',
            'nic_port_10' => 'NIC Port 10',
            'nic_port_11' => 'NIC Port 11',
            'nic_port_12' => 'NIC Port 12',
            'hbo_1' => 'HBA 1',
            'hbo_2' => 'HBA 2',
            'hbo_3' => 'HBA 3',
            'hbo_4' => 'HBA 4',
            'hbo_5' => 'HBA 5',
            'hbo_6' => 'HBA 6',
            'hbo_7' => 'HBA 7',
            'hbo_8' => 'HBA 8',
            'hbo_9' => 'HBA 9',
            'hbo_10' => 'HBA 10',
            'hbo_11' => 'HBA 11',
            'hbo_12' => 'HBA 12',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'modified_by' => 'Modified By',
            'modified_at' => 'Modified At',
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
        $criteria->compare('conn_tor_id', $this->conn_tor_id);
        $criteria->compare('ilo_hostname', $this->ilo_hostname, true);
        $criteria->compare('ilo_ip', $this->ilo_ip, true);
        $criteria->compare('serial_no', $this->serial_no, true);
        $criteria->compare('model', $this->model, true);
        $criteria->compare('mac', $this->mac, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('dc', $this->dc, true);
        $criteria->compare('server_hall', $this->server_hall);
        $criteria->compare('row', $this->row);
        $criteria->compare('rack', $this->rack);
        $criteria->compare('u_location', $this->u_location, true);
        $criteria->compare('model_variant', $this->model_variant, true);
        $criteria->compare('ilo_password', $this->ilo_password, true);
        $criteria->compare('ps1', $this->ps1);
        $criteria->compare('ps2', $this->ps2);
        $criteria->compare('ps3', $this->ps3);
        $criteria->compare('ps4', $this->ps4);
        $criteria->compare('ps5', $this->ps5);
        $criteria->compare('ps6', $this->ps6);
        $criteria->compare('ps7', $this->ps7);
        $criteria->compare('ps8', $this->ps8);
        $criteria->compare('ps9', $this->ps9);
        $criteria->compare('ps10', $this->ps10);
        $criteria->compare('ps11', $this->ps11);
        $criteria->compare('ps12', $this->ps12);
        $criteria->compare('nic_port_1', $this->nic_port_1, true);
        $criteria->compare('nic_port_2', $this->nic_port_2, true);
        $criteria->compare('nic_port_3', $this->nic_port_3, true);
        $criteria->compare('nic_port_4', $this->nic_port_4, true);
        $criteria->compare('nic_port_5', $this->nic_port_5, true);
        $criteria->compare('nic_port_6', $this->nic_port_6, true);
        $criteria->compare('nic_port_7', $this->nic_port_7, true);
        $criteria->compare('nic_port_8', $this->nic_port_8, true);
        $criteria->compare('nic_port_9', $this->nic_port_9, true);
        $criteria->compare('nic_port_10', $this->nic_port_10, true);
        $criteria->compare('nic_port_11', $this->nic_port_11, true);
        $criteria->compare('nic_port_12', $this->nic_port_12, true);
        $criteria->compare('hbo_1', $this->hbo_1, true);
        $criteria->compare('hbo_2', $this->hbo_2, true);
        $criteria->compare('hbo_3', $this->hbo_3, true);
        $criteria->compare('hbo_4', $this->hbo_4, true);
        $criteria->compare('hbo_5', $this->hbo_5, true);
        $criteria->compare('hbo_6', $this->hbo_6, true);
        $criteria->compare('hbo_7', $this->hbo_7, true);
        $criteria->compare('hbo_8', $this->hbo_8, true);
        $criteria->compare('hbo_9', $this->hbo_9, true);
        $criteria->compare('hbo_10', $this->hbo_10, true);
        $criteria->compare('hbo_11', $this->hbo_11, true);
        $criteria->compare('hbo_12', $this->hbo_12, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('modified_by', $this->modified_by);
        $criteria->compare('modified_at', $this->modified_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10,
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DcServerInfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rackOddEvenCombination($data) {
        $rackArray = array();
        $value1 = $data->rack;
        $value2 = 0;
        if (($value1 % 2) == 0) {
            $value2 = $value1 - 1;
        } else {
            $value2 = $value1 + 1;
        }
        $rackArray = array($value1 => $value1, $value2 => $value2);
        return CHtml::dropDownList($data->id, $data->rack, $rackArray, array("empty" => "Select", "style" => "width:100px;text-align:center;", "id" => "rack_id_$data->id"));
    }

    public function setUlocation($model) {
        return "<input class=\"ulocation\" name=\"ulocation_$model->id\" id = \"$model->id\" type=\"text\" value = \"$model->u_location\" style= width:50px;text-align:center; maxlength=\"2\"  /> ";
    }

    public function setModelVariant($model) {
        $modelVariantArray      = array();
        $model_variant_array    = array();
        $modelVariantArray = DcModelTypeMaster::model()->findAllByAttributes(array('model' => $model->model)); //, array(":mod" => $model->model));
        foreach ($modelVariantArray as $value) {
            $model_variant_array = array_map(function ($value) {
                return array($value['model_variant'] => $value['model_variant']);
            }, $modelVariantArray);
        }
        $model_variant_array = array_reduce($model_variant_array, 'array_merge', array());
//        print_r($model->model_variant );exit();
        return CHtml::dropDownList($model->id, $model->model_variant, $model_variant_array, array("empty" => "Select", "style" => "width:100px;text-align:center;", "id" => "model_variant_$model->id"));
    }

    public function setILOPassword($model) {
        return "<input class=\"ilopassword\" name=\"ilo_password_$model->id\" type=\"password\" value = \"$model->ilo_password\" style=width:75px;text-align:center; maxlength=\"8\" />";
    }

    public function isRackUlocationExist($rack, $uLocation) {
        $isExists = DcServerInfo::model()->exists("rack=:rack", "u_location=:u_location", array(":rack" => $rack, ":u_location" => $uLocation));
        if ($isExist) {
            $message = 'Combination of rack & u-location already exist.';
            $this->addError($uLocation, $message);
        }
    }

    public function getServerDetail($server) {
        if (!empty($server)) {
            $powersupply = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

            $credentials['username'] = 'idcadm';
            $credentials['password'] = 'Idc!@#123';
            $ssh = new SshConnect;
            $ssh->gm_collect = FALSE;
//                print("Connected Host::");
//                print($server['connected_host']);
            $ssh->connect($server['connected_host'], $credentials);
            $command = 'show /system1';
            $output = $ssh->getCommandOutput($command);
            //print_r($output);
            if (!empty($output)) {
                $lines = explode("\n", $output);
                foreach ($lines as $line) {
                    if (preg_match("/^name/", trim($line))) {
                        $tempModel = explode('=', $line);
                        $devicemodel = $tempModel[1];   //deviceModel Capture 
//                            print("DeviceModel::");
//                            print($devicemodel);
                    }
                    if (preg_match("/^number/", trim($line))) {
                        $tempSerialnumber = explode('=', $line);
                        $serialNumber = $tempSerialnumber[1];   //Serialnumber Capture
//                            print("SerialNumber::");
//                            print($serialNumber);
                    }
                    if (preg_match("/^powersupply/", trim($line))) {
                        $powersupply[(filter_var($line, FILTER_SANITIZE_NUMBER_INT)) - 1] = 1;
//                            print("Power Supply::");
//                            print_r($powersupply);
                    }
                }
            }

            $command2 = 'show /map1/enetport1';
            $macAndHostnameOutput = $ssh->getCommandOutput($command2);
            if (!empty($macAndHostnameOutput)) {
                $maclines = explode("\n", $macAndHostnameOutput);
                foreach ($maclines as $line) {
                    if (preg_match("/^PermanentAddress/", trim($line))) {
                        $tempPermanentAddress = explode('=', $line);
                        $macID = $tempPermanentAddress[1];   //PermanentAddress Capture
//                            print("Mac::");
//                            print($macID);
                    }
                    if (preg_match("/^SystemName/", trim($line))) {
                        $tempSystemName = explode('=', $line);
                        $systemName = $tempSystemName[1];   //SystemName Capture
//                            print("SystemName::");
//                            print($macID);
                    }
                }
            }
            //$server['host_mac'] // '9cb6.540d.0d84'
// $portMacAdd //28:80:23:9e:85:20
            $server_host_mac = $server['host_mac'];

            if (isset($server_host_mac)) {
                $ex = explode('.', $server_host_mac);

                $temp_str = '';

                if (count($ex) > 0) {
                    $temp_str = implode($ex);
                }

                $temp = str_split($temp_str);
                $str_len = count($temp);

                //RULE :: To build MacId.
                $check_index = array(2, 4, 6, 8, 10);
                for ($i = 0; $i < $str_len; $i++) {
                    if (in_array($i, $check_index))
                        $new_macID .= ':' . $temp[$i];
                    else
                        $new_macID .= $temp[$i];
                }

                if (!empty($portMacAdd) && is_array($portMacAdd)) {
                    if (in_array($new_macID, $portMacAdd)) {
                        $update_dc_connected_tor = DcConnectedTorInfo::model()->updateAll(array('serial_no' => $serialNumber), 'id = ' . $server['id']);
                    }
                }//endOf if $portMacAdd array
            }//endOf if


            if (isset($macID)) {
                //echo '<br /> MacID :: '.$macID;

                $ex = explode(':', $macID);
                $ex1 = explode('.', $macID);

                $temp_str = '';

                if (count($ex) > 0) {
                    $temp_str = implode($ex);
                } else if (count($ex1) > 0) {
                    $temp_str = implode($ex1);
                }


                $temp = str_split($temp_str);
                $str_len = count($temp);

                //echo '<pre>'; print_r($temp); echo '</pre>';
                $check_index = array(4, 8);
                for ($i = 0; $i < $str_len; $i++) {
                    if (in_array($i, $check_index))
                        $new_macID .= '.' . $temp[$i];
                    else
                        $new_macID .= $temp[$i];
                }

                //echo '<br />TEMP str :: '.$temp_str;
                //echo '<br />New macID:: '.$new_macID;

                $dc_connected_tor_ID = $server['id'];

                $update_dc_connected_tor = DcConnectedTorInfo::model()->updateAll(array('serial_no' => $serialNumber, 'is_virtual_machine' => 'PM'), 'id = ' . $dc_connected_tor_ID);
            }//endOf if
            $command3 = 'show /system1/network1/integrated_NICs';
            $outputNIC = $ssh->getCommandOutput($command3);
            if (!empty($outputNIC)) {
                $niclines = explode("\n", $outputNIC);
                $portMacAdd = array();
                foreach ($niclines as $key => $line) {
                    if ($key != 0) {
                        //echo "/^Port".$key."NIC_MACAddress/";      
                        if (preg_match("/^Port/", trim($line))) {
                            $temp = explode('=', $line);
                            $portMacAdd[] = $temp[1]; // Port1NIC_MACAddress
//                                print("Mac Address::");
//                                print_r($portMacAdd);
                        }
                    }
                }
            }

            if (!empty($portMacAdd) && is_array($portMacAdd)) {
                $model = new DcServerInfo;
                $model->conn_tor_id = $server['id'];
                $model->ilo_hostname = $systemName;
                $model->ilo_ip = $server['connected_host'];
                $model->serial_no = $serialNumber;
                $model->model = $devicemodel;
                $model->mac = $macID;
                $model->city = SUBSTR($server['hostname'], 0, 4);
                $model->dc = SUBSTR($server['hostname'], 4, 2);
                $model->server_hall = SUBSTR($server['hostname'], 7, 2);
                $model->row = SUBSTR($server['hostname'], 9, 1);
                $model->rack = SUBSTR($server['hostname'], 11, 2);
                $model->u_location = 0;
                $model->model_variant = '';
                $model->ilo_password = '';
                foreach ($portMacAdd as $key => $val) {
                    $attr = 'nic_port_' . ($key + 1);
                    $model->{$attr} = $val;
                }
                foreach ($powersupply as $key => $val) {
                    $attr = 'ps' . ($key + 1);
                    $model->{$attr} = $val;
                }
                $model->created_by = Yii::app()->session['login']['user_id'];
                $model->created_at = date('Y-m-d H:i:s');
                $model->modified_by = Yii::app()->session['login']['user_id'];
                $model->modified_at = date('Y-m-d H:i:s');
                //print_r($model);

                if ($model->save(FALSE)) {
                    $flag = True;
                    print_r($model);
                } else {
                    $flag = False;
                    print_r($model->getErrors());
                }
            }
        }
    }

    public function getPS1($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps1) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS2($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps2) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS3($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps3) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS4($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps4) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS5($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps5) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS6($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps6) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS7($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps7) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS8($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps8) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS9($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps9) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS10($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps10) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS11($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps11) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

    public function getPS12($data) {
        //$powerSupply = 'ps' . $number;
        if ($data->ps12) {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballgreen.png", "Reachable", array("style" => "width:16px"));
        } else {
            return CHtml::image(Yii::app()->getBaseUrl() . "/images/ballred.png", "Not reachable", array("style" => "width:16px"));
        }
    }

}
