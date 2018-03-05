<?php

/**
 * This is the model class for table "ndd_isis_extra_neighbors".
 *
 * The followings are the available columns in table 'ndd_isis_extra_neighbors':
 * @property integer $id
 * @property integer $row_id
 * @property string $hostname
 * @property string $loopback0
 * @property string $router_version
 * @property string $bdi
 * @property string $interface_ip
 * @property string $neigh_hostname
 * @property string $bdi_status
 * @property integer $status
 * @property string $created_at
 * @property string $modified_at
 */
class IsisExtraNeighbors extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ndd_isis_extra_neighbors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('row_id, hostname, loopback0, router_version, bdi, bdi_status', 'required'),
			array('row_id, status', 'numerical', 'integerOnly'=>true),
			array('hostname, neigh_hostname', 'length', 'max'=>14),
			array('loopback0, interface_ip, bdi_status', 'length', 'max'=>15),
			array('router_version', 'length', 'max'=>5),
			array('bdi', 'length', 'max'=>6),
			array('created_at, modified_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, row_id, hostname, loopback0, router_version, bdi, interface_ip, neigh_hostname, bdi_status, status, created_at, modified_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'row_id' => 'Row',
			'hostname' => 'Hostname',
			'loopback0' => 'Loopback0',
			'router_version' => 'Router Version',
			'bdi' => 'Bdi',
			'interface_ip' => 'Interface Ip',
			'neigh_hostname' => 'Neigh Hostname',
			'bdi_status' => 'Bdi Status',
			'status' => 'Status',
			'created_at' => 'Created At',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('row_id',$this->row_id);
		$criteria->compare('hostname',$this->hostname,true);
		$criteria->compare('loopback0',$this->loopback0,true);
		$criteria->compare('router_version',$this->router_version,true);
		$criteria->compare('bdi',$this->bdi,true);
		$criteria->compare('interface_ip',$this->interface_ip,true);
		$criteria->compare('neigh_hostname',$this->neigh_hostname,true);
		$criteria->compare('bdi_status',$this->bdi_status,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IsisExtraNeighbors the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
