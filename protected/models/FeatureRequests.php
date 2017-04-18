<?php

/**
 * This is the model class for table "tbl_feature_requests".
 *
 * The followings are the available columns in table 'tbl_feature_requests':
 * @property integer $id
 * @property string $module_name
 * @property string $description
 * @property integer $existing_module_dependancy
 * @property string $features
 * @property string $expected_delivery
 * @property string $cisco_requestor
 * @property string $rjil_requestor
 * @property integer $status
 * @property integer $status_by
 * @property string $status_on
 * @property string $created_date
 * @property integer $created_by
 * @property string $updated_date
 * @property integer $updated_by
 */
class FeatureRequests extends CActiveRecord
{
        public $towhom;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_feature_requests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module_name, description, features, expected_delivery, cisco_requestor, rjil_requestor', 'required'),
			array('existing_module_dependancy, created_by, updated_by', 'numerical', 'integerOnly'=>true),
			array('module_name, description, cisco_requestor, rjil_requestor', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, module_name, description, existing_module_dependancy, features, expected_delivery, cisco_requestor, rjil_requestor, created_date, created_by, updated_date, updated_by', 'safe', 'on'=>'search'),
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
			'id' => 'Sr. No',
			'module_name' => 'Feature Name',
                        'dependent_module_name'=> 'dependent_module_name',
			'description' => 'Description',
			'existing_module_dependancy' => 'Existing Module Dependancy',
                        'requirement_for' =>'requirement_for',
			'features' => 'Features',
			'expected_delivery' => 'Expected Delivery Date',
			'cisco_requestor' => 'Cisco Requestor',
			'rjil_requestor' => 'Rjil Requestor',
                        'status' => 'Status',
                        'status_by' => 'Status By',
                        'status_on' => 'Status On',
                        'towhom' => 'Towhom',
			'created_date' => 'Requested Date',
			'created_by' => 'Request By',
			'updated_date' => 'Updated Date',
			'updated_by' => 'Updated By',
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
		$criteria->compare('module_name',$this->module_name,true);
                $criteria->compare('dependent_module_name',$this->dependent_module_name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('existing_module_dependancy',$this->existing_module_dependancy);
                $criteria->compare('features',$this->features,true);
		$criteria->compare('requirement_for',$this->requirement_for);
		$criteria->compare('expected_delivery',$this->expected_delivery,true);
		$criteria->compare('cisco_requestor',$this->cisco_requestor,true);
		$criteria->compare('rjil_requestor',$this->rjil_requestor,true);
                $criteria->compare('status',$this->status);
                $criteria->compare('status_by',$this->status_by);
                $criteria->compare('status_on',$this->status_on,true);
                $criteria->compare('towhom',$this->towhom);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FeatureRequests the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getDependency($model)
        {
            if($model->existing_module_dependancy)
            {
                return "Yes";
            }
 else {
     return "No";
 }
        }
    }

    public function renderApproveDisapprove($model) {
        $result = FeatureRequests::model()->findByPk($model->id);
        if ($result->status == 0) {
            echo CHtml::button('Approve', array('class' => 'approve', 'featureid_a' => $model->id, 'style' => 'margin-left:0px;margin-bottom:5px;'));
            echo "<br>";
            echo CHtml::button('Reject', array('class' => 'reject', 'featureid_r' => $model->id, 'style' => 'margin-left:0px;'));
        } else if ($result->status == 1) {
            echo "Approved";
        } else if ($result->status == 2) {
            echo "Rejected";
        }
    }

    public function renderNames($model) {
        $empName = Employee::model()->findByPk($model->status_by);
        echo $empName->first_name . " " . $empName->last_name;
    }

    public function renderNamesR($model) {
        $empName = Employee::model()->findByPk($model->created_by);
        echo $empName->first_name . " " . $empName->last_name;
    }

}
