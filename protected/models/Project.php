<?php

/**
 * This is the model class for table "tbl_project".
 *
 * The followings are the available columns in table 'tbl_project':
 * @property integer $id
 * @property string $project_name
 * @property string $project_description
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property integer $type
 * @property integer $hr_clocked
 * @property integer $category
 * @property integer $created_by
 * @property string $created_date
 * @property integer $updated_by
 * @property string $updated_date
 * @property integer $is_deleted
 */
class Project extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_project';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_name,created_by, created_date, updated_by, updated_date', 'required'),
			array('id, type, hr_clocked, category, created_by, updated_by, is_deleted', 'numerical', 'integerOnly'=>true),
			array('project_name, project_description', 'length', 'max'=>255),
			array('status', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, project_name, project_description, start_date, end_date, status, type, hr_clocked, category, created_by, created_date, updated_by, updated_date, is_deleted', 'safe', 'on'=>'search'),
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
			'project_name' => 'Project ID',
			'project_description' => 'Project Description',
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'status' => 'Status',
			'type' => 'Type',
			'hr_clocked' => 'Hr Clocked',
			'category' => 'Category',
			'created_by' => 'Created By',
			'created_date' => 'Created Date',
			'updated_by' => 'Updated By',
			'updated_date' => 'Updated Date',
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
		$criteria->compare('project_name',$this->project_name,true);
		$criteria->compare('project_description',$this->project_description,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('hr_clocked',$this->hr_clocked);
		$criteria->compare('category',$this->category);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('updated_by',$this->updated_by);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Project the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function GetId(){
            
        }
        
        public function getAllProject() {
            $pro = array();
            $r1 = Yii::app()->db->createCommand('select distinct pid,project_name from tbl_project_management')->queryAll();            
            $pro[] = 'Select Project';
            foreach($r1 as $v1){
                $pro[$v1['pid']] = $v1['project_name'];
            }
            return $pro;
        }
		
		
}
