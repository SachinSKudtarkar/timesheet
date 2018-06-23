<?php

/**
 * This is the model class for table "db_rjilautots_dev.tbl_task".
 *
 * The followings are the available columns in table 'db_rjilautots_dev.tbl_task':
 * @property integer $task_id
 * @property string $task_name
 * @property string $description
 * @property string $status
 * @property integer $is_approved
 * @property integer $created_by
 * @property string $created_at
 * @property integer $is_delete
 *
 * The followings are the available model relations:
 * @property SubTask[] $subTasks
 */
class Task extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_task';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_name, description', 'required'),
			array('is_approved, created_by, is_delete', 'numerical', 'integerOnly'=>true),
			array('task_name, description', 'length', 'max'=>255),
			array('status', 'length', 'max'=>10),
			array('task_name', 'unique', 'message' => 'Type already exists!'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('task_id, task_name, description, status, is_approved, created_by, created_at, is_delete', 'safe', 'on'=>'search'),
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
			'subTasks' => array(self::HAS_MANY, 'SubTask', 'task_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'task_id' => 'Task',
			'task_name' => 'Task Name',
			'description' => 'Description',
			'status' => 'Status',
			'is_approved' => 'Is Approved',
			'created_by' => 'Created By',
			'created_at' => 'Created At',
			'is_delete' => 'Is Delete',
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

		$criteria->compare('task_id',$this->task_id);
		$criteria->compare('task_name',$this->task_name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('is_approved',$this->is_approved);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('is_delete',$this->is_delete);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Task the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getAllTask() {
            $pro = array();
            $r1 = Yii::app()->db->createCommand('select distinct t.task_id, t.description, t.task_name from tbl_task as t where t.task_name!=""')->queryAll();            
            $pro[] = 'Select Task Type';
            foreach($r1 as $v1){
                $pro[$v1['task_id']] = $v1['task_name'];
            }
            return $pro;
        }
}
