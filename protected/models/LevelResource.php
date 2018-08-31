<?php

/**
 * This is the model class for table "tbl_level_master".
 *
 * The followings are the available columns in table 'tbl_level_master':
 * @property integer $level_id
 * @property string $level_name
 * @property string $budget_per_hour
 * @property integer $created_by
 * @property string $created_at
 * @property integer $modified_by
 * @property string $updated_at
 * @property integer $is_deleted
 */
class LevelResource extends CActiveRecord
{   
        public $level_id;
		public $level_name;//for database
		public $budget_per_hour;// for display purpose
                
		
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_assign_resource_level';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('emp_id, level_id', 'required'),
			array('level_id, emp_id, level_id, created_by, modified_by, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			//array('l', 'length', 'max'=>250), 
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched. estimated_end_date, total_hr_estimation_hour,estimated_start_date,
			array('id,emp_id, level_id,created_at,updated_at,created_by,modified_by', 'safe', 'on'=>'search'),
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
			'id' => 'Resource Level Id',
			'emp_id' => 'Employee Id',
			'level_id' => 'Level Id',
			'created_by' => 'Created By',
			'created_at' => 'Created Date',
			'modified_by' => 'Modified By',
			'updated_at' => 'Updated Date'
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
//                $criteria->select = "t.*,pr.project_name,t.spid as taskId ";//t.spid as taskId 
		$criteria->compare('id',$this->id);
		//$criteria->compare('pid',$this->pid);
		$criteria->compare('em.first_name',$this->emp_id, true);
		$criteria->compare('lv.level_name',$this->level_id,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at);
		$criteria->compare('modified_by',$this->modified_by,true);
                $criteria->order = 'created_at desc';
                $criteria->join = "INNER join tbl_level_master lv ON (lv.level_id=t.level_id) ";
                $criteria->join = "INNER join tbl_employee em ON (em.emp_id=t.emp_id)";
//                        . "INNER join tbl_pid_approval as pa ON (t.spid = pa.sub_project_id) ";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SubProject the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
          public function getAllocateHrLink($model, $row) {
        if ($model->status == 'new')
            return 'N/A';
        return CHtml::link("Allocate hours", array('#'), array('data-toggle' => "modal", 'data-target' => "#", 'class' => 'AllocateHrLink', 'data-key' => $model->spid));
    }
    
    public function getAllProject() {
		$pro = array();
		$r1 = Yii::app()->db->createCommand('select distinct t.spid, t.sub_project_description, t.sub_project_name from tbl_level_master as t where t.sub_project_description!=""')->queryAll();            
		$pro[] = 'Select Sub Project';
		foreach($r1 as $v1){
			$pro[$v1['spid']] = $v1['sub_project_description'];
		}
		return $pro;
	}
		
	public  function getLevel($model){
		$name = LevelMaster::model()->findByPk($model->level_id);
		
		return $name['level_name'];
	}
	
	public  function getEmployee($model){
		$name = Employee::model()->findByPk($model->emp_id);
		
		return ucwords($name['first_name'].' '.$name['last_name']);
	}
}
