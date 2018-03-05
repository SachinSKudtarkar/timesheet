<?php


/**
 * This is the model class for table "tbl_task_allocation".
 *
 * The followings are the available columns in table 'tbl_task_allocation':
 * @property integer $id
 * @property integer $pid
 * @property string $spid
 * @property string $date
 * @property string $allocated_resource
 * @property string $comment
 * @property integer $created_by
 */
class TaskAllocation extends CActiveRecord
{
    public $projectName;
    public $project_name;
	public $sub_project_name;
	public $taskId;
	public $estimated_start_date;
	public $estimated_end_date;
	public $total_hr_estimation_hour;
	public $requester;
	public $day;
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_task_allocation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, spid, date, allocated_resource,  created_by', 'required'),
			array('pid,spid, created_by', 'numerical', 'integerOnly'=>true),
			array('day', 'length', 'max'=>10),
			array('allocated_resource', 'length', 'max'=>300),
			array('comment', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid,spid,date, allocated_resource, comment, created_by,project_name', 'safe', 'on'=>'search'),
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
			'pid' => 'Pid',
			'spid' => 'SPid',
			'day' => 'Day',
			'date' => 'Date',
			'allocated_resource' => 'Allocated Resource',
			'comment' => 'Comment',
			'created_by' => 'Created By',
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

                $criteria->select = "t.id, t.pid,t.spid as taskId,t.date,t.allocated_resource,pm.project_name,spm.sub_project_name,spm.estimated_start_date,spm.estimated_end_date,spm.total_hr_estimation_hour,spm.requester";
		$criteria->compare('id',$this->id);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('spid',$this->spid,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('allocated_resource',$this->allocated_resource,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('created_by',$this->created_by);
                $criteria->compare('project_name',$this->project_name,true);                
               // $criteria->select = "t.id, t.pid,t.spid as taskId,t.date,t.allocated_resource,pm.project_name,spm.sub_project_name,spm.estimated_start_date,spm.estimated_end_date,spm.total_hr_estimation_hour,spm.requester";
                $criteria->join = "INNER JOIN tbl_project_management pm ON pm.pid = t.pid  INNER JOIN tbl_sub_project spm ON spm.spid = t.spid";
				$criteria->order = "t.pid ASC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'pagination' => array(
                            'pageSize' => 10,
                 
                            ),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ResourceAllocationProjectWork the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        
        public function projectStatus()
        {
           $query = " SELECT pm.pid,pm.project_name,rap.allocated_resource,day.comment,day.day FROM tbl_project_management pm  "
                   . " INNER JOIN tbl_resource_allocation_project_work rap "                  
                   . " ON (pm.pid = rap.pid) "
                   . " INNER JOIN tbl_day_comment day"
                   . " ON (pm.pid = day.pid) ";
           $result = Yii::app()->db->createCommand($query)->queryAll();
           return $result;
        }
        
        
        public function renderButtonsSelectProject() {  
            
              echo  CHtml::link("Select Another Project", Yii::app()->createUrl("resourceallocationprojectwork/resourcearrangement"));
            
    }
}
