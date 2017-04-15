<?php

/**
 * This is the model class for table "tbl_access_right_master".
 *
 * The followings are the available columns in table 'tbl_access_right_master':
 * @property integer $id
 * @property string $type
 * @property string $heading
 * @property string $page_url
 * @property integer $heading_order
 * @property string $menu_icon
 * @property integer $is_disabled
 */
class AccessRightMaster extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_access_right_master';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type, heading', 'required'),
            array('heading_order, is_disabled', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 20),
            array('heading, menu_icon', 'length', 'max' => 100),
            array('page_url', 'length', 'max' => 200),
            array('type', 'checkUnique'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type, heading, page_url, heading_order, menu_icon, is_disabled', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function checkUnique($attribute, $params) {
        $model = self::model()->findByAttributes(array($attribute => $this->$attribute));
        if (!empty($model)) {
            $this->addError($attribute, "The type you have entered is already exist");
        }
    }

    public function beforeSave() {
        $this->type = strtoupper($this->type);
        return parent::beforeSave();
    }

    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'accessRightDetails' => array(self::HAS_MANY, 'AccessRightDetails', 'parent_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'type' => 'Type',
            'heading' => 'Heading',
            'page_url' => 'Page Url',
            'heading_order' => 'Heading Order',
            'menu_icon' => 'Menu Icon',
            'is_disabled' => 'Is Disabled',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('heading', $this->heading, true);
        $criteria->compare('page_url', $this->page_url, true);
        $criteria->compare('heading_order', $this->heading_order);
        $criteria->compare('menu_icon', $this->menu_icon, true);
        $criteria->compare('is_disabled', $this->is_disabled);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccessRightMaster the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
