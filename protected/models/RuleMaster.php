<?php

/**
 * This is the model class for table "tbl_rule_master".
 *
 * The followings are the available columns in table 'tbl_rule_master':
 * @property integer $rule_id
 * @property string $tag_name
 * @property string $rule_type
 * @property string $device_type
 * @property string $description
 * @property integer $points
 * @property string $created_at
 * @property string $modified_at
 * @property integer $created_by
 * @property integer $modified_by
 */
class RuleMaster extends CActiveRecord {
    public $device_penalty_id;
    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_rule_master';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tag_name, rule_type, device_type, description, points', 'required'),
            array('points, created_by, modified_by', 'numerical', 'integerOnly' => true),
            array('rule_type', 'length', 'max' => 50),
            array('device_type', 'length', 'max' => 20),
            array('tag_name,final_takeoff_point', 'isUnique'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('rule_id, tag_name, rule_type, device_type, description, points, created_at, modified_at, created_by, modified_by', 'safe', 'on' => 'search'),
        );
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
            'rule_id' => 'Rule ID',
            'tag_name' => 'Tag Name',
            'rule_type' => 'Rule Type',
            'device_type' => 'Device Type',
            'description' => 'Description',
            'points' => 'Points',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
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

        $criteria->compare('rule_id', $this->rule_id);
        $criteria->compare('tag_name', $this->tag_name, true);
        $criteria->compare('rule_type', $this->rule_type, true);
        $criteria->compare('device_type', $this->device_type, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('points', $this->points);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('modified_at', $this->modified_at, true);
        $criteria->compare('created_by', $this->created_by);
        $criteria->compare('modified_by', $this->modified_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RuleMaster the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function isUnique($attribute, $params) {
        if (!empty($this->tag_name)) {
            if (!empty($this->rule_id)) {
                $model = RuleMaster::model()->find(array('condition' => "tag_name = :tag_name AND rule_id != :rule_id", 'params' => array(':tag_name' => $this->tag_name, ':rule_id' => $this->rule_id)));
            } else {
                $model = RuleMaster::model()->findByAttributes(array('tag_name' => $this->tag_name));
            }
            if (!empty($model))
                $this->addError($attribute, 'Tag Name already exist');
        }
    }

    protected function beforeSave() {
        if (empty($this->rule_id)) {
            $this->created_by = Yii::app()->session['login']['user_id'];
            $this->created_at = date("Y-m-d H:i:s");
        }
        $this->modified_by = Yii::app()->session['login']['user_id'];
        $this->modified_at = date("Y-m-d H:i:s");
        return parent::beforeSave();
    }

}
