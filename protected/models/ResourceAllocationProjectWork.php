<?php

/**
 * This is the model class for table "tbl_resource_allocation_project_work".
 *
 * The followings are the available columns in table 'tbl_resource_allocation_project_work':
 * @property integer $id
 * @property integer $pid
 * @property string $day
 * @property string $date
 * @property string $allocated_resource
 * @property string $comment
 * @property integer $created_by
 */
class ResourceAllocationProjectWork extends CActiveRecord
{
    public $projectName;
    public $project_name;
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_resource_allocation_project_work';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pid, day, date, allocated_resource, comment, created_by', 'required'),
			array('pid, created_by', 'numerical', 'integerOnly'=>true),
			array('day', 'length', 'max'=>10),
			array('allocated_resource', 'length', 'max'=>300),
			array('comment', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pid, day, date, allocated_resource, comment, created_by,project_name', 'safe', 'on'=>'search'),
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
			'day' => 'Day',
			'date' => 'Date',
			'allocated_resource' => 'Allocated Resource',
			'comment' => 'Comment',
			'created_by' => 'Created By',
			'modified_by' => 'modified_by ',
			'modified_at' => 'modified_at',
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

                $criteria->select = "t.*,pm.project_name";
		$criteria->compare('id',$this->id);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('allocated_resource',$this->allocated_resource,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('modified_by',$this->modified_by);
		$criteria->compare('modified_at',$this->modified_at);
                $criteria->compare('project_name',$this->project_name,true);                
                $criteria->select = "t.id, t.pid,t.date,t.allocated_resource,pm.project_name";
                $criteria->join = "INNER JOIN tbl_project_management pm ON pm.pid = t.pid";
                $criteria->order = "t.pid ASC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                    'pagination' => array(
                            'pageSize' => 50,
                 
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
	
	
	public function search_forResourceTask()
	{
			
	$command = $this->connection_invetory->createCommand(" 
            SELECT concat(em.first_name,' ',em.last_name) as name,pm.project_name,sb.sub_project_name,sb.estimated_end_date,sb.estimated_start_date,sb.total_hr_estimation_hour,sb.Priority 
			FROM tbl_task_allocation as ta inner join tbl_sub_project as sb on(ta.spid = sb.spid), tbl_employee em , tbl_project_management as pm 
			WHERE  em.emp_id in (ta.allocated_resource) and sb.pid = pm.pid order by em.first_name;
        ")->queryAll();
        $dataProvider = new CArrayDataProvider($command, array(
            'id'=>'name',
            'sort'=>array(
                'defaultOrder'=>'name DESC',
            ),
            'pagination'=>array(
                'pageSize'=>15,
            ),
        ));
        return $dataProvider;
	}
      public function getAllocateHrLink($model, $row) {
        if ($model->status == 'new')
            return 'N/A';
        return CHtml::link("View Feedback", array('#'), array('data-toggle' => "modal", 'data-target' => "#", 'class' => 'AllocateHrLink', 'data-key' => $model->spid));
    }
}
