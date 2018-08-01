	<?php

/**
 * This is the model class for table "tbl_access_role_master".
 *
 * The followings are the available columns in table 'tbl_access_role_master':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $emp_id
 * @property integer $access_type
 * @property integer $is_active
 * @property integer $created_by
 * @property string $created_date
 * @property string $updated_date
 * @property integer $updated_by
 */
class AccessRoleMaster extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_access_role_master';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent_id, emp_id, access_type, created_by, created_date, updated_date', 'required'),
			array('parent_id, emp_id, access_type, is_active, created_by, updated_by', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent_id, emp_id, access_type, is_active, created_by, created_date, updated_date, updated_by', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		
		// 	return array(
		// 	 'Creater' => array(self::BELONGS_TO, 'Employee', 'parent_id,emp_id'),

		// );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent_id' => 'Managers',
			'emp_id' => 'Resources',
			'access_type' => 'Access Type',
			'is_active' => 'Is Active',
			'created_by' => 'Created By',
			'created_date' => 'Created Date',
			'updated_date' => 'Updated Date',
			'updated_by' => 'Updated By',
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('emp_id',$this->emp_id);
		$criteria->compare('access_type',$this->access_type);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('updated_date',$this->updated_date,true);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccessRoleMaster the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}




    public function set_roles($params){
        if(isset($params)){
            
            $parent_id = $params['parent_id'];
            // $emp_id[$parent_id] = $parent_id;
             $access_type = 2;
            $created_by = Yii::app()->session['login']['user_id'];
            $updated_by = Yii::app()->session['login']['user_id'];
            $created_date = date('Y-m-d H:i:s');
            $updated_date = date('Y-m-d H:i:s');
foreach ($params['emp_id'] as $key => $emp_id) {
               
            
            Yii::app()->db->createCommand("Insert  Into tbl_access_role_master  (`parent_id`,
  `emp_id`,
  `access_type`,
  `is_active`,
  `created_by` ,
  `created_date`  ,
  `updated_date` ,
  `updated_by` )
  values (
'{$parent_id}','{$emp_id}','{$access_type}',1,'{$created_by}','{$created_date}','{$updated_date}','{$updated_by}'
  )
  ")->execute();
        }
    }
          
            
    }


    public function set_manager($list){
        if(isset($list)){
            $access_type = 1;
            $created_by = Yii::app()->session['login']['user_id'];
            $updated_by = Yii::app()->session['login']['user_id'];
            $created_date = date('Y-m-d H:i:s');
            $updated_date = date('Y-m-d H:i:s');
foreach ($list as $key => $emp_id) {
           $parent_id =  $emp_id;   
  
            Yii::app()->db->createCommand("Insert  Into tbl_access_role_master  (`parent_id`,
  `emp_id`,
  `access_type`,
  `is_active`,
  `created_by` ,
  `created_date`  ,
  `updated_date` ,
  `updated_by` )
  values (
'{$parent_id}','{$emp_id}','{$access_type}',1,'{$created_by}','{$created_date}','{$updated_date}','{$updated_by}'
  )
  ")->execute();
        }

        
    }
        }
    

    public function get_roles(){
        $rawData = Yii::app()->db->createCommand("select * from tbl_access_role_master")->queryAll();

       $dataProvider = new CArrayDataProvider($rawData, array(
           
            'pagination'=>array(
                'pageSize'=>15,
            ),
        ));
        return $dataProvider;
    }


    public function get_manager_list(){
       return $rawData = Yii::app()->db->createCommand("select first_name,last_name,em.emp_id,email from tbl_access_role_master as arm inner join tbl_employee as em on (arm.emp_id = em.emp_id and arm.is_active =1)
            where arm.access_type = 1 and em.is_active=1 and is_password_changed = 'yes' ")->queryAll();


    }

    public function get_resource_list($manager_id){
    	if($manager_id){
$Resources_list = [];

		 $rawData = Yii::app()->db->createCommand("select first_name,last_name,em.emp_id from tbl_access_role_master as arm inner join tbl_employee as em on (arm.emp_id = em.emp_id and arm.is_active =1)
            where arm.access_type = 2 and em.is_active=1 and is_password_changed = 'yes' and parent_id = {$manager_id} ")->queryAll();

			foreach ($rawData as $key => $value) {
			 	$Resources_list[$value['emp_id']] = $value['emp_id'];
			 	$Resources_list[$value['email']] = $value['email'];
			 } 
    	}

    	return $Resources_list;
    }

    public function get_day_comment($emp_id){
    	if($emp_id){
    		$day = date('d.m.Y',strtotime("-1 days"));

    		 $query =  "select CONCAT(first_name,' ',last_name) as name,t.day,pm.project_name,sb.sub_project_name,
 st.sub_task_name ,t.comment,t.hours,email from tbl_day_comment as t 
 INNER JOIN tbl_project_management pm ON (t.pid = pm.pid) 
 INNER JOIN tbl_employee emp ON (emp.emp_id = t.emp_id) 
 LEFT join tbl_sub_project sb ON (sb.spid=t.spid )
 left Join tbl_sub_task as st on (st.stask_id = t.stask_id)  
 where emp.emp_id = '{$emp_id}' and t.day = '{$day}'  order by id DESC limit 1"; 

 $rawData = Yii::app()->db->createCommand($query)->queryAll();
 		return  $rawData;
    	}
    }



}
