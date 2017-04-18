<?php

/**
 * This is the model class for table "tbl_access_right_details".
 *
 * The followings are the available columns in table 'tbl_access_right_details':
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $value
 * @property string $page_url
 * @property integer $menu_order
 * @property string $menu_icon
 * @property integer $is_disabled
 */
class AccessRightDetails extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tbl_access_right_details';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('parent_id, name, value, menu_order', 'required'),
            array('parent_id, menu_order, is_disabled', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 50),
            array('value', 'length', 'max' => 150),
            array('page_url, menu_icon', 'length', 'max' => 200),
            array('value', 'checkUniqueWithParent'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, parent_id, name, value, page_url, menu_order, menu_icon, is_disabled', 'safe', 'on' => 'search'),
        );
    }

    public function checkUniqueWithParent($attribute, $params) {
        $model = self::model()->findByAttributes(array('parent_id' => $this->parent_id, 'value' => $this->value));
        if (!empty($model)) {
            $this->addError($attribute, "Value already exist");
        }
    }

    public function beforeSave() {
        $this->value = strtolower($this->value);
        return parent::beforeSave();
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parent' => array(self::BELONGS_TO, 'AccessRightMaster', 'parent_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'parent_id' => 'Parent',
            'name' => 'Name',
            'value' => 'Value',
            'page_url' => 'Page Url',
            'menu_order' => 'Menu Order',
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
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('page_url', $this->page_url, true);
        $criteria->compare('menu_order', $this->menu_order);
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
     * @return AccessRightDetails the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
