<?php

/**
 * This is the model class for table "tbl_employee".
 *
 * The followings are the available columns in table 'tbl_employee':
 * @property string $emp_id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $access_type
 * @property string $access_rights
 * @property string $created_date
 * @property string $modified_date
 * @property integer $is_active
 * @property integer $is_deleted
 * @property integer $created_by
 * @property integer $modified_by
 */
class Roles extends CActiveRecord {

    public $repeat_password;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_roles_manager';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('is_deleted, created_by, modified_by', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 250),
            array('name,updated_date,created_date', 'safe'),
            array('name', 'unique'),
            // array('name,created_date,created_by', 'on'=>'create'),
            // The fo 18:03 llowing rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('name, id,access_rights', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'name' => 'Roles Name',
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

        $criteria->compare('name', $this->name, true);
        //$criteria->compare('modified_by',$this->modified_by);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Employee the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Save before data
     * @return type saver data
     */
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->created_by = Yii::app()->session['login']['user_id'];
            $this->created_date = new CDbExpression('NOW()');
        } else {
            $this->modified_by = Yii::app()->session['login']['user_id'];
            $this->updated_date = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

}
