<?php

/**
 * This is the model class for table "tbl_project_management".
 *
 * The followings are the available columns in table 'tbl_project_management':
 * @property integer $pid
 * @property string $project_name
 * @property string $project_description
 * @property string $requester
 * @property string $estimated_end_date
 * @property string $total_hr_estimation_hour
 * @property string $status
 * @property integer $type
 * @property integer $hr_clocked
 * @property integer $category
 * @property string $customer
 * @property integer $is_billable
 * @property integer $created_by
 * @property string $created_date
 * @property integer $updated_by
 * @property string $updated_date
 * @property integer $is_deleted
 */
class ProjectManagement extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public $sub_project_name;

    public function tableName() {
        return 'tbl_project_management';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('project_id,project_name, project_description, requester,estimated_start_date, estimated_end_date, total_hr_estimation_hour,', 'required'),//, estimated_end_date,estimated_start_date, status, type
            array('type, hr_clocked,category, is_billable, created_by, updated_by, is_deleted', 'numerical', 'integerOnly' => true),
            array('project_name, project_description, requester', 'length', 'max' => 250),
            array('total_hr_estimation_hour', 'length', 'max' => 10),
            // array('project_name','validated_projectname'),
            array('status', 'length', 'max' => 25),
            array('customer', 'length', 'max' => 255),
            array('project_name', 'unique', 'message' => 'Program already exists!'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('pid, project_id,project_name, project_description, requester, estimated_end_date, total_hr_estimation_hour, status, type, hr_clocked,category, customer, is_billable, created_by, created_date, updated_by, updated_date, is_deleted,sub_project_name', 'safe', 'on' => 'search'),
        );
    }
    public function validated_projectname($attribute,$params){
        if($params['project_name']){
            $data_check = ProjectManagement::model()->findAll(
                array(
                    'condition' => 'project_name = :project_name',
                    'params'    => array(':project_name' => $params['project_name'])
                )
            );
            if($data_check)
            $this->addError($attribute, 'Project Name already present');

        }

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
            'pid' => 'Pid',
            'project_id' => 'Program Id',
            'project_name' => 'Program Name',
            'project_description' => 'Program Description',
            'requester' => 'Requester',
            'estimated_start_date' => 'Program Start Date',
            'estimated_end_date' => 'Estimated End Date',
            'total_hr_estimation_hour' => 'Total Estimation Hours',
            'status' => 'Status',
            'type' => 'Type',
            'hr_clocked' => 'Hr Clocked',
            'category' => 'Category',
            'customer' => 'Customer',
            'is_billable' => 'Is Billable',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
       // $criteria->select = "t.*,sbpr.sub_project_name ";
        $criteria->compare('t.pid', $this->pid);
		$criteria->compare('t.project_id', $this->project_id, true);
        $criteria->compare('project_name', $this->project_name, true);
        $criteria->compare('project_description', $this->project_description, true);
        $criteria->compare('requester', $this->requester, true);
        $criteria->compare('estimated_end_date', $this->estimated_end_date, true);
        $criteria->compare('total_hr_estimation_hour', $this->total_hr_estimation_hour, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('estimated_start_date', $this->estimated_start_date, true);
        $criteria->compare('estimated_end_date', $this->estimated_start_date, true);
        $criteria->compare('hr_clocked', $this->hr_clocked);
        $criteria->compare('category', $this->category);
        $criteria->compare('customer', $this->customer, true);
        $criteria->compare('is_billable', $this->is_billable);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('created_date', $this->created_date, true);
        $criteria->compare('updated_by', $this->updated_by);
        $criteria->compare('updated_date', $this->updated_date, true);
        $criteria->compare('is_deleted', $this->is_deleted);
        $criteria->compare('sub_project_name', $this->sub_project_name,true);
        $criteria->compare('is_deleted', $this->is_deleted);
        //$criteria->join = "LEFT join tbl_sub_project sbpr ON sbpr.pid=t.pid";
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.pid DESC',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProjectManagement the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
