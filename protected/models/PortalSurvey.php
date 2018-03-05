<?php

/**
 * This is the model class for table "portal_survey".
 *
 * The followings are the available columns in table 'portal_survey':
 * @property integer $id
 * @property integer $emp_id
 * @property string $overall_satisfaction
 * @property string $helped_in_a_day_to_day_work
 * @property string $feature_you_like_most
 * @property string $modules_are_slower
 * @property string $module_like_most_and_why
 * @property string $portal_impacted_day_to_day_activities
 * @property string $much_time_you_are_saving
 * @property string $project_platform_you_appreciate
 * @property string $suggest_to_portal_development_team
 * @property string $portal_support_team_efficient
 * @property string $suggest_project_delivery_portal
 * @property string $created_at
 */
class PortalSurvey extends CActiveRecord {

    public $before_time_you_use;
    public $before_time_you_use_am;
    public $after_time_you_use;
    public $after_time_you_use_am;
    public $email_id;
    public $first_name;
    
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'portal_survey';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('emp_id, overall_satisfaction, helped_in_a_day_to_day_work, feature_you_like_most, modules_are_slower, module_like_most_and_why, portal_impacted_day_to_day_activities, much_time_you_are_saving, project_platform_you_appreciate, suggest_to_portal_development_team, portal_support_team_efficient, suggest_project_delivery_portal,much_time_you_spent,time_you_use_to_leave_office, created_at', 'required'),
            array('emp_id', 'numerical', 'integerOnly' => true),
            array('overall_satisfaction, helped_in_a_day_to_day_work, feature_you_like_most, modules_are_slower, much_time_you_are_saving, project_platform_you_appreciate, portal_support_team_efficient', 'length', 'max' => 100),
            array('module_like_most_and_why, portal_impacted_day_to_day_activities, suggest_to_portal_development_team, suggest_project_delivery_portal,much_time_you_spent,time_you_use_to_leave_office', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, emp_id, overall_satisfaction, helped_in_a_day_to_day_work, feature_you_like_most, modules_are_slower, module_like_most_and_why, portal_impacted_day_to_day_activities, much_time_you_are_saving, project_platform_you_appreciate, suggest_to_portal_development_team, portal_support_team_efficient, suggest_project_delivery_portal,much_time_you_spent,time_you_use_to_leave_office, created_at', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (empty($this->id)) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        return parent::beforeSave();
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Modifier' => array(self::BELONGS_TO, 'Employee', 'emp_id'),
//            'Employee' => array(self::BELONGS_TO, 'Employee', 'emp_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'emp_id' => 'Employee Id',
            'overall_satisfaction' => 'Overall Satisfaction',
            'helped_in_a_day_to_day_work' => 'Helped In A Day To Day Work',
            'feature_you_like_most' => 'Feature You Like Most',
            'modules_are_slower' => 'Modules Are Slower',
            'module_like_most_and_why' => 'Module Like Most And Why',
            'portal_impacted_day_to_day_activities' => 'Portal Impacted Day To Day Activities',
            'much_time_you_are_saving' => 'Much Time You Are Saving',
            'project_platform_you_appreciate' => 'Project Platform You Appreciate',
            'suggest_to_portal_development_team' => 'Suggest To Portal Development Team',
            'portal_support_team_efficient' => 'Portal Support Team Efficient',
            'suggest_project_delivery_portal' => 'Suggest Project Delivery Portal',
            'much_time_you_spent' => 'Much Time You Spent',
            'time_you_use_to_leave_office' => 'Time You Use To Leave Office',
            'created_at' => 'Created At',
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
        $criteria->with = array('Modifier');
        $criteria->compare('id', $this->id);
        $criteria->compare('t.emp_id', $this->emp_id);
        $criteria->compare('overall_satisfaction', $this->overall_satisfaction, true);
        $criteria->compare('helped_in_a_day_to_day_work', $this->helped_in_a_day_to_day_work, true);
        $criteria->compare('feature_you_like_most', $this->feature_you_like_most, true);
        $criteria->compare('modules_are_slower', $this->modules_are_slower, true);
        $criteria->compare('module_like_most_and_why', $this->module_like_most_and_why, true);
        $criteria->compare('portal_impacted_day_to_day_activities', $this->portal_impacted_day_to_day_activities, true);
        $criteria->compare('much_time_you_are_saving', $this->much_time_you_are_saving, true);
        $criteria->compare('project_platform_you_appreciate', $this->project_platform_you_appreciate, true);
        $criteria->compare('suggest_to_portal_development_team', $this->suggest_to_portal_development_team, true);
        $criteria->compare('portal_support_team_efficient', $this->portal_support_team_efficient, true);
        $criteria->compare('suggest_project_delivery_portal', $this->suggest_project_delivery_portal, true);
        $criteria->compare('much_time_you_spent', $this->much_time_you_spent, true);
        $criteria->compare('time_you_use_to_leave_office', $this->time_you_use_to_leave_office, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PortalSurvey the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getEmaiFromId($model) {
        $criteria = new CDbCriteria;
        $criteria->select = "email";
        $criteria->condition = "emp_id='" . $model['emp_id'] . "'";
        $result = Employee::model()->find($criteria);
        if (!empty($result))
            return $result->email;
}

    public static function getEmployeeDropdown($tableName = '') {
        $criteria = new CDbCriteria;
        $criteria->select = "CONCAT(t.first_name ,' ', t.last_name) AS first_name, t.emp_id";
        $criteria->join = "INNER JOIN {$tableName} AS e ON (t.emp_id = e.emp_id)";
        return Employee::model()->findAll($criteria);
    }

}
