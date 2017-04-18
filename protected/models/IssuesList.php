<?php

/**
 * This is the model class for table "tbl_issues_list".
 *
 * The followings are the available columns in table 'tbl_issues_list':
 * @property integer $id
 * @property string $description
 * @property string $implementation_status
 * @property string $staging_status
 * @property string $qa_status
 * @property string $production_status
 * @property string $date
 */
class IssuesList extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_issues_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description, implementation_status, staging_status, qa_status, production_status, date', 'required'),
			array('description', 'length', 'max'=>500),
			array('implementation_status, staging_status, qa_status, production_status', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, description, implementation_status, staging_status, qa_status, production_status, date', 'safe', 'on'=>'search'),
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
			'description' => 'Description',
			'implementation_status' => 'Implementation Status',
			'staging_status' => 'Staging Status',
			'qa_status' => 'Qa Status',
			'production_status' => 'Production Status',
			'date' => 'Date',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('implementation_status',$this->implementation_status,true);
		$criteria->compare('staging_status',$this->staging_status,true);
		$criteria->compare('qa_status',$this->qa_status,true);
		$criteria->compare('production_status',$this->production_status,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination' => false
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IssuesList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
