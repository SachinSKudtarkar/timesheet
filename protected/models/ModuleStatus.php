<?php

/**
 * This is the model class for table "tbl_module_status".
 *
 * The followings are the available columns in table 'tbl_module_status':
 * @property integer $id
 * @property string $module_name
 * @property string $developer
 * @property string $requester_type
 * @property string $requestor
 * @property string $start_date
 * @property integer $on_production
 * @property integer $on_testserver
 * @property integer $on_staging
 * @property integer $on_development
 * @property integer $created_by
 * @property string $created_date
 * @property integer $updated_by
 * @property string $updated_date
 * @property integer $is_active
 * @property integer $is_deleted
 */
class ModuleStatus extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_module_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module_name, developer, start_date', 'required'),
			array('on_production, on_testserver, on_staging, on_development, created_by, updated_by, is_active, is_deleted', 'numerical', 'integerOnly'=>true),
			array('module_name, developer, requestor', 'length', 'max'=>250),
			array('requester_type', 'length', 'max'=>50),
                        array('module_name', 'unique', 'message' => 'Module With this name already exists!'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			 array('id, module_name, developer, requester_type, requestor, start_date, on_production, date_on_production, on_testserver, date_on_testserver, on_staging, date_on_staging, on_development, date_on_development, created_by, created_date, updated_by, updated_date, is_active, is_deleted', 'safe', 'on'=>'search'),
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
                        'module_name' => 'Module Name',
                        'developer' => 'Developer',
                        'manager' => 'Manager',
                        'requester_type' => 'Requester Type',
                        'requestor' => 'Requestor Name',
                        'start_date' => 'Start Date',
                        'on_production' => 'On Production',
                        'date_on_production' => 'Date On Production',
                        'on_testserver' => 'On Testserver',
                        'date_on_testserver' => 'Date On Testserver',
                        'on_staging' => 'On Staging',
                        'date_on_staging' => 'Date On Staging',
                        'on_development' => 'On Development',
                        'date_on_development' => 'Date On Development',
                        'created_by' => 'Created By',
                        'created_date' => 'Created Date',
                        'updated_by' => 'Updated By',
                        'updated_date' => 'Updated Date',
                        'is_active' => 'Is Active',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
                $criteria->compare('module_name',$this->module_name,true);
                $criteria->compare('developer',$this->developer,true);
                $criteria->compare('manager',$this->manager,true);
                $criteria->compare('requester_type',$this->requester_type,true);
                $criteria->compare('requestor',$this->requestor,true);
                $criteria->compare('start_date',$this->start_date,true);
                $criteria->compare('on_production',$this->on_production);
                $criteria->compare('date_on_production',$this->date_on_production,true);
                $criteria->compare('on_testserver',$this->on_testserver);
                $criteria->compare('date_on_testserver',$this->date_on_testserver,true);
                $criteria->compare('on_staging',$this->on_staging);
                $criteria->compare('date_on_staging',$this->date_on_staging,true);
                $criteria->compare('on_development',$this->on_development);
                $criteria->compare('date_on_development',$this->date_on_development,true);
                $criteria->compare('created_by',$this->created_by);
                $criteria->compare('created_date',$this->created_date,true);
                $criteria->compare('updated_by',$this->updated_by);
                $criteria->compare('updated_date',$this->updated_date,true);
                $criteria->compare('is_active',$this->is_active);
                $criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ModuleStatus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function renderChkPro($model)
        {   
            echo CHtml::checkBox('onpro',($model->on_production == 1)?true:false, array (
                                        'value'=>'prod','class'=>'chk','rid'=>$model->id,'style'=>'margin-left:10px;',
                                        ));
        }        
        public function renderChkTest($model)
        {   
            echo CHtml::checkBox('ontest',($model->on_testserver == 1)?true:false, array (
                                        'value'=>'test','class'=>'chk','rid'=>$model->id,'style'=>'margin-left:10px;',
                                        ));
        }
        public function renderChkStag($model)
        {   
            echo CHtml::checkBox('onstag',($model->on_staging == 1)?true:false, array (
                                        'value'=>'stag','class'=>'chk','rid'=>$model->id,'style'=>'margin-left:10px;',
                                        ));
        
        echo $resultstring = implode(", ", $namesarray);
    }

   
        public function getDevs($model)
        {
            $devIds = $model->developer;
            $query = "SELECT CONCAT(first_name,'  ',last_name) as name from tbl_infi_employee WHERE id IN(".$devIds.")";
            $result = YII::app()->db->createCommand($query)->queryAll();
            $namesarray = array();
            foreach($result as $each)
            {
                $namesarray[] = $each['name'];
            }
         echo   $resultstring = implode(", ",$namesarray);
            
        }
        
         public function getManags($model)
        {
            $mrgids = $model->manager;
            $query = "SELECT CONCAT(first_name,'  ',last_name) as name from tbl_infi_employee WHERE id IN(".$mrgids.")";
            $result = YII::app()->db->createCommand($query)->queryAll();
            $namesarray = array();
            foreach($result as $each)
            {
                $namesarray[] = $each['name'];
            }
         echo   $resultstring = implode(", ",$namesarray);
            
        }
}
