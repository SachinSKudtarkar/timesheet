<?php

/**
 * This is the model class for table "tbl_day_comment_approved_hrs_log".
 *
 * The followings are the available columns in table 'tbl_day_comment_approved_hrs_log':
 * @property integer $id
 * @property string $stask_id
 * @property string $approved_hrs
 * @property integer $remarks
 * @property string $created_at
 */
class DayCommentApprovedHrsLog extends CActiveRecord
{
        public $id;
        public $stask_id;//for database
        public $approved_hrs;// for display purpose
        public $remarks;
        public $created_at;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_day_comment_approved_hrs_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                    array('approved_hrs, remarks', 'required'),
                    array('id, created_by, stask_id', 'numerical', 'integerOnly'=>true),
                    array('remarks', 'length', 'max'=>250),
                    // The following rule is used by search().
                    // @todo Please remove those attributes that should not be searched. estimated_end_date, total_hr_estimation_hour,estimated_start_date,
                    array('id,stask_id,approved_hrs,remarks,created_at'=>'search'),
            );
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'id' => 'Log Id',
                'stask_id' => 'Sub Task Id',
                'approved_hrs' => 'Approved Hours',
                'remarks' => 'Remarks',
                'created_at' => 'Created Date',
            );
	}

	
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ResourceAllocationProjectWork the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
