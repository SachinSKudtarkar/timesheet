<?php

/**
 * This is the model class for table "tbl_daily_attendance_report".
 *
 * The followings are the available columns in table 'tbl_daily_attendance_report':
 * @property integer $id
 * @property integer $infi_emp_id
 * @property string $in_time
 * @property string $out_time
 * @property string $shift_hrs
 * @property string $work_hrs
 * @property string $work_status
 * @property integer $created_by
 * @property string $created_date
 * @property string $updated_date
 */
class DailyAttendanceReport extends CActiveRecord
{
    public $infi_emp_name;
    public $Report;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_daily_attendance_report';
	}
        
          public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('infi_emp_id, in_time, out_time, shift_hrs, work_hrs, work_status, created_by, attendance_date, created_date, updated_date', 'required', 'on'=>'xyz'),     
            array('in_time, out_time, shift_hrs, work_hrs, work_status', 'required', 'on'=>'update'),            
            array('infi_emp_id, created_by', 'numerical', 'integerOnly'=>true),
            array('in_time, out_time, shift_hrs, work_hrs, work_status', 'length', 'max'=>10),
            array('file_name', 'file', 'allowEmpty' => true, 'types' => 'xlsx, xls, xlsm, csv'),
            array('file_name', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, infi_emp_id,infi_emp_name, in_time, out_time, shift_hrs, work_hrs, work_status, created_by, attendance_date, created_date, updated_date', 'safe','on'=>'uploadForm, search, upload, xyz' ),
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
 
    function getTimeDiff($outtime,$intime){ 
        
         $qry_command = Yii::app()->db->createCommand("SELECT TIMEDIFF('".$outtime."','".$intime."') as diff");
         $qry_result = $qry_command->queryRow();
         return $qry_result['diff'];
         
        
        
        try{
        $nextDay=$dtime>$atime?1:0;
        $dep=@EXPLODE(':',$dtime);
        $arr=@EXPLODE(':',$atime);
        $diff=ABS(MKTIME($dep[0],$dep[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr[0],$arr[1],0,DATE('n'),DATE('j')+$nextDay,DATE('y')));
        $hours=FLOOR($diff/(60*60));
        $mins=FLOOR(($diff-($hours*60*60))/(60));
        $secs=FLOOR(($diff-(($hours*60*60)+($mins*60))));
        IF(STRLEN($hours)<2){$hours="0".$hours;}
        IF(STRLEN($mins)<2){$mins="0".$mins;}
        IF(STRLEN($secs)<2){$secs="0".$secs;}
        RETURN $hours.':'.$mins.':'.$secs;
        }
        catch(Exception $e)
        {
            RETURN 0;
        }
    }
    
    function getEmpFromSrNO($sno)
    {
        if($sno)
        {
        $qry_command = Yii::app()->db->createCommand('SELECT id FROM tbl_infi_employee WHERE SrNo = '.$sno);
        $qry_result = $qry_command->queryRow();
        return $qry_result['id'];
        }
        else return $sno;
    }
 
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'infi_emp_id' => 'Infi Emp',
			'in_time' => 'In Time',
			'out_time' => 'Out Time',
			'shift_hrs' => 'Shift Hrs',
			'work_hrs' => 'Work Hrs',
			'work_status' => 'Work Status',
			'created_by' => 'Created By',
                        'attendance_date' => 'Attendance Date',
			'created_date' => 'Created Date',
			'updated_date' => 'Updated Date',
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
		$criteria->compare('t.infi_emp_id',$this->infi_emp_id,true);
                $criteria->compare('CONCAT(infiemployee.first_name," ",infiemployee.last_name)', $this->infi_emp_name,true);
		$criteria->compare('in_time',$this->in_time,true);
		$criteria->compare('out_time',$this->out_time,true);
		$criteria->compare('shift_hrs',$this->shift_hrs,true);
		$criteria->compare('work_hrs',$this->work_hrs,true);
		$criteria->compare('work_status',$this->work_status,true);
                $criteria->compare('attendance_date',$this->work_status,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('updated_date',$this->updated_date,true);
                $criteria->select = "t.id,t.attendance_date,t.infi_emp_id,CONCAT(infiemployee.first_name,' ',infiemployee.last_name) as infi_emp_name,t.in_time as in_time,t.out_time,t.shift_hrs,t.work_hrs,t.work_status";
                $criteria->join = "INNER JOIN tbl_infi_employee infiemployee on infiemployee.id = t.infi_emp_id";                
                $criteria->group = "infiemployee.SrNo";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        function getEmployeeData($empid,$m)
        {               
           
                if($empid)
                {
                    $qry_command = Yii::app()->db->createCommand("SELECT t.id,t.attendance_date,CONCAT(infiemployee.first_name,' ',infiemployee.last_name) as infi_emp_name,t.in_time as in_time,t.out_time,t.shift_hrs,t.work_hrs,t.work_status"
                                                                    . " FROM tbl_daily_attendance_report t INNER JOIN tbl_infi_employee infiemployee on infiemployee.id = t.infi_emp_id"
                                                                    . " WHERE t.infi_emp_id = ".$empid." AND MONTH(t.attendance_date) = ".$m);
                  return  $qry_result = $qry_command->queryAll(); 
                    
                     
                }
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DailyAttendanceReport the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
